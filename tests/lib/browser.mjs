// A minimal stand-in for a web browser: keeps cookies, follows redirects and
// submits forms the way the pages themselves describe them.
import { baseUrl } from './env.mjs';

const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36';

export function decodeEntities(text) {
    return text
        .replace(/&#(\d+);/g, (_, n) => String.fromCodePoint(Number(n)))
        .replace(/&#x([0-9a-f]+);/gi, (_, n) => String.fromCodePoint(parseInt(n, 16)))
        .replace(/&quot;/g, '"')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&nbsp;/g, ' ')
        .replace(/&amp;/g, '&');
}

function attributes(tag) {
    const out = {};
    const re = /([a-zA-Z_:][-a-zA-Z0-9_:.]*)(?:\s*=\s*(?:"([^"]*)"|'([^']*)'|([^\s"'>]+)))?/g;
    const body = tag.replace(/^<\s*[a-zA-Z0-9]+/, '').replace(/\/?>$/, '');
    let m;
    while ((m = re.exec(body))) {
        out[m[1].toLowerCase()] = decodeEntities(m[2] ?? m[3] ?? m[4] ?? '');
    }
    return out;
}

/** Text a person would read on the page: tags dropped, whitespace collapsed. */
export function visibleText(html) {
    return decodeEntities(
        html
            .replace(/<(script|style)\b[\s\S]*?<\/\1>/gi, ' ')
            .replace(/<[^>]+>/g, ' ')
    ).replace(/\s+/g, ' ').trim();
}

export class Page {
    constructor(response, url, body) {
        this.status = response.status;
        this.url = url;
        this.body = body;
        this.contentType = response.headers.get('content-type') || '';
    }

    get text() {
        return visibleText(this.body);
    }

    /** Addresses of the links on the page whose address matches the pattern. */
    links(pattern) {
        const out = [];
        const re = /<a\b[^>]*>/gi;
        let m;
        while ((m = re.exec(this.body))) {
            const href = attributes(m[0]).href;
            if (href !== undefined && pattern.test(href)) out.push(href);
        }
        return out;
    }

    /**
     * The fields a browser would send for the first form whose opening tag
     * matches the pattern, with the values the page pre-filled.
     */
    form(pattern = /./) {
        const re = /<form\b[^>]*>/gi;
        let m;
        while ((m = re.exec(this.body))) {
            if (!pattern.test(m[0])) continue;
            const end = this.body.indexOf('</form', m.index);
            const inner = this.body.slice(m.index, end === -1 ? undefined : end);
            const attrs = attributes(m[0]);
            return {
                action: attrs.action || this.url,
                method: (attrs.method || 'get').toLowerCase(),
                fields: formFields(inner)
            };
        }
        throw new Error(`No form matching ${pattern} on ${this.url}`);
    }
}

function formFields(html) {
    const fields = [];
    const re = /<(input|select|textarea)\b[^>]*>/gi;
    let m;
    while ((m = re.exec(html))) {
        const kind = m[1].toLowerCase();
        const a = attributes(m[0]);
        if (!a.name || 'disabled' in a) continue;
        if (kind === 'input') {
            const type = (a.type || 'text').toLowerCase();
            if (['submit', 'button', 'image', 'reset', 'file'].includes(type)) continue;
            if ((type === 'checkbox' || type === 'radio') && !('checked' in a)) continue;
            fields.push([a.name, a.value ?? (type === 'checkbox' || type === 'radio' ? 'on' : '')]);
        } else if (kind === 'textarea') {
            const end = html.indexOf('</textarea', re.lastIndex);
            fields.push([a.name, decodeEntities(html.slice(re.lastIndex, end))]);
        } else {
            const end = html.indexOf('</select', re.lastIndex);
            const options = [...html.slice(re.lastIndex, end).matchAll(/<option\b[^>]*>/gi)].map((o) => attributes(o[0]));
            const chosen = options.filter((o) => 'selected' in o);
            if ('multiple' in a) {
                for (const o of chosen) fields.push([a.name, o.value ?? '']);
            } else {
                const o = chosen[0] || options[0];
                if (o) fields.push([a.name, o.value ?? '']);
            }
        }
    }
    return fields;
}

/** Replaces (or adds) the given fields in a list of [name, value] pairs. */
export function fill(fields, values) {
    const out = fields.filter(([name]) => !(name in values));
    for (const [name, value] of Object.entries(values)) {
        if (value === undefined || value === null) continue;
        for (const v of Array.isArray(value) ? value : [value]) out.push([name, String(v)]);
    }
    return out;
}

export class Browser {
    constructor(base = baseUrl) {
        this.base = base;
        this.cookies = new Map();
        this.location = base + '/';
    }

    resolve(href) {
        return new URL(decodeEntities(href), this.location).toString();
    }

    #storeCookies(response) {
        for (const line of response.headers.getSetCookie()) {
            const pair = line.split(';', 1)[0];
            const eq = pair.indexOf('=');
            if (eq < 1) continue;
            const name = pair.slice(0, eq).trim();
            const value = pair.slice(eq + 1).trim();
            if (value === '' || value === 'deleted') this.cookies.delete(name);
            else this.cookies.set(name, value);
        }
    }

    async request(method, href, { fields, headers = {}, follow = true } = {}) {
        let url = this.resolve(href);
        const background = Boolean(headers['X-Requested-With']);
        for (let hop = 0; hop < 10; hop++) {
            const h = {
                'User-Agent': USER_AGENT,
                Accept: 'text/html,application/xhtml+xml,application/json;q=0.9,*/*;q=0.8',
                'Accept-Language': 'vi,en;q=0.8',
                Referer: this.location,
                ...headers
            };
            if (this.cookies.size) h.Cookie = [...this.cookies].map(([k, v]) => `${k}=${v}`).join('; ');
            let body;
            if (method === 'POST') {
                h.Origin = new URL(this.base).origin;
                h['Content-Type'] = 'application/x-www-form-urlencoded';
                body = new URLSearchParams(fields || []).toString();
            }
            const response = await fetch(url, { method, headers: h, body, redirect: 'manual' });
            this.#storeCookies(response);
            const location = response.headers.get('location');
            if (follow && response.status >= 300 && response.status < 400 && location) {
                await response.arrayBuffer();
                url = new URL(location, url).toString();
                method = 'GET';
                continue;
            }
            const page = new Page(response, url, await response.text());
            if (!background) this.location = url;
            return page;
        }
        throw new Error(`Too many redirects for ${href}`);
    }

    get(href, options) {
        return this.request('GET', href, options);
    }

    post(href, fields, options = {}) {
        return this.request('POST', href, { ...options, fields });
    }

    /** POST the way the site's own scripts do it, without leaving the page. */
    ajax(href, fields) {
        return this.request('POST', href, { fields, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    }

    /** Submit a form found on a page, overriding some of its fields. */
    submit(form, values = {}) {
        const fields = fill(form.fields, values);
        if (form.method === 'post') return this.post(form.action, fields);
        const url = new URL(this.resolve(form.action));
        for (const [k, v] of fields) url.searchParams.append(k, v);
        return this.get(url.toString());
    }
}
