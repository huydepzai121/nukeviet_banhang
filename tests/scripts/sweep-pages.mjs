// Opens every storefront and administration page of the shops module once
// (GET only) and reports what went wrong: HTTP status, PHP error output in the
// page, unexpected redirects, and new lines in the NukeViet error log.
//
// A bug-hunting aid, not a test: run it against a site that already has a
// category, a product and an order (for example right after "npm test").
//   node scripts/sweep-pages.mjs > sweep.json
import { readdirSync, readFileSync, statSync } from 'node:fs';
import path from 'node:path';
import { Browser } from '../lib/browser.mjs';
import { projectRoot } from '../lib/env.mjs';
import { adminUrl, signInAsStaff } from '../lib/staff.mjs';

const moduleDir = path.join(projectRoot, 'site', 'modules', 'shops');
const logDir = path.join(projectRoot, 'site', 'data', 'logs', 'error_logs');
const PHP_NOISE = /Fatal error|Parse error|Warning<\/b>|Notice<\/b>|Deprecated<\/b>|Uncaught |SQLSTATE\[/;

const ops = (dir) => readdirSync(path.join(moduleDir, dir)).filter((f) => f.endsWith('.php')).map((f) => f.slice(0, -4)).sort();

function logFiles() {
    return readdirSync(logDir).filter((f) => f.endsWith('_error_log.log')).map((f) => path.join(logDir, f));
}

function logSize() {
    return Object.fromEntries(logFiles().map((f) => [f, statSync(f).size]));
}

function newLogLines(before) {
    const lines = [];
    for (const f of logFiles()) {
        const text = readFileSync(f).subarray(before[f] || 0).toString('utf8');
        for (const line of text.split(/\r?\n/)) {
            if (line.startsWith('[')) lines.push(line.replace(/^\[[^\]]*\] \[[^\]]*\] \[[^\]]*\] /, ''));
        }
    }
    return [...new Set(lines)];
}

async function visit(browser, area, name, href, { ajax = false } = {}) {
    const before = logSize();
    let page;
    const requested = browser.resolve(href);
    try {
        page = ajax
            ? await browser.request('GET', href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            : await browser.get(href);
    } catch (error) {
        return { area, name, href, problem: `request failed: ${error.message}` };
    }
    await new Promise((r) => setTimeout(r, 150));
    const noise = PHP_NOISE.exec(page.body);
    return {
        area,
        name,
        href,
        status: page.status,
        redirectedTo: page.url !== requested ? page.url : undefined,
        length: page.body.length,
        phpOutput: noise ? page.body.slice(Math.max(0, noise.index - 80), noise.index + 300).replace(/\s+/g, ' ') : undefined,
        log: newLogLines(before)
    };
}

const staff = await signInAsStaff();
const guest = new Browser();
const results = [];
const site = (query) => `/index.php?language=vi&nv=shops${query}`;
const now = () => Date.now();

// Storefront: every function file, plain and the way the page scripts call it.
for (const op of ops('funcs')) {
    results.push(await visit(guest, 'site', op, site(op === 'main' ? '' : `&op=${op}`)));
    results.push(await visit(guest, 'site', `${op} (ajax)`, site(`&op=${op}&nocache=${now()}`), { ajax: true }));
}

// Storefront pages with the parameters real links carry.
const extra = [
    ['main rewritten', '/shops/'],
    ['category', '/shops/tu-lanh/'],
    ['category page 2', '/shops/tu-lanh/page-2/'],
    ['detail', '/shops/tu-lanh/tu-lanh-inverter-300-lit.html'],
    ['detail popup', site('&op=tu-lanh/tu-lanh-inverter-300-lit.html&popup=1')],
    ['cart', '/shops/cart/'],
    ['order', '/shops/order/'],
    ['history', '/shops/history/'],
    ['wishlist', '/shops/wishlist/'],
    ['compare', '/shops/compare/'],
    ['search rewritten', '/shops/search/'],
    ['search with keyword', site('&op=search&q=tu+lanh')],
    ['search_result', site('&op=search_result&keyword=tu+lanh')],
    ['group', '/shops/group/'],
    ['tag', '/shops/tag/tu-lanh'],
    ['rss', '/shops/rss/'],
    ['rss category', '/shops/rss/tu-lanh/'],
    ['sitemap', '/sitemap-vi.shops.xml'],
    ['print_pro', site('&op=print_pro&id=1')],
    ['print_pro rewritten', '/shops/print_pro/tu-lanh/tu-lanh-inverter-300-lit.html'],
    ['print order without key', site('&op=print&order_id=1')],
    ['payment without key', site('&op=payment&order_id=1')],
    ['shippingajax', site('&op=shippingajax&get_location=1')],
    ['download', site('&op=download&id_files=1')],
    ['sendmail', site('&op=sendmail&id=1')],
    ['review', site('&op=review&id=1')],
    ['seek search', '/index.php?language=vi&nv=seek&q=tu+lanh'],
    ['seek search in shops', '/index.php?language=vi&nv=seek&q=inverter&m=shops']
];
for (const [name, href] of extra) results.push(await visit(guest, 'site', name, href));

// The same storefront pages for a signed-in person (staff session).
for (const op of ['history', 'wishlist', 'point', 'order', 'cart']) {
    results.push(await visit(staff, 'site (đã đăng nhập)', op, site(`&op=${op}`)));
}

// Administration: every function file, then the pages that need a record.
for (const op of ops('admin')) {
    results.push(await visit(staff, 'admin', op, adminUrl(`nv=shops&op=${op}`)));
}
const adminExtra = [
    ['content edit', 'op=content&id=1'],
    ['cat edit', 'op=cat&catid=1&parentid=0'],
    ['or_view', 'op=or_view&order_id=1'],
    ['items in category', 'op=items&catid=1'],
    ['items search', 'op=items&q=inverter&stype=-'],
    ['order search', 'op=order&order_code=S000001'],
    ['warehouse for product', 'op=warehouse&listid=1'],
    ['warehouse_logs', 'op=warehouse_logs'],
    ['seller for product', 'op=seller&pro_id=1'],
    ['review', 'op=review'],
    ['setting', 'op=setting']
];
for (const [name, query] of adminExtra) {
    results.push(await visit(staff, 'admin', name, adminUrl(`nv=shops&${query}`)));
}

const interesting = results.filter((r) => r.problem || r.status !== 200 || r.phpOutput || r.log?.length);
console.log(JSON.stringify({ visited: results.length, withFindings: interesting.length, results }, null, 1));
