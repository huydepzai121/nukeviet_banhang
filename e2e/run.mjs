// Runs the flows in e2e/flows.md against a seeded NukeViet site.
import { mkdirSync, readFileSync } from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import puppeteer from 'puppeteer-core';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const env = Object.fromEntries(
    readFileSync(path.join(root, '.env'), 'utf8')
        .split(/\r?\n/)
        .filter((line) => /^\s*[A-Za-z_]/.test(line) && !line.trim().startsWith('#'))
        .map((line) => {
            const idx = line.indexOf('=');
            let value = line.slice(idx + 1).trim();
            if ((value.startsWith('"') && value.endsWith('"')) || (value.startsWith("'") && value.endsWith("'"))) {
                value = value.slice(1, -1);
            }
            return [line.slice(0, idx).trim(), value];
        })
);

const base = process.env.SITE_URL || `http://127.0.0.1:${env.HTTP_PORT || 8080}`;
const outDir = process.env.E2E_OUT || '/opt/cursor/artifacts/e2e';
mkdirSync(outDir, { recursive: true });

const browser = await puppeteer.launch({
    executablePath: process.env.CHROME_PATH || '/usr/local/bin/google-chrome',
    headless: true,
    args: ['--no-sandbox', '--disable-dev-shm-usage', '--disable-gpu']
});
const page = await browser.newPage();
page.setDefaultTimeout(20000);
await page.setViewport({ width: 1366, height: 900 });
page.on('dialog', (dialog) => dialog.accept());

const results = [];

async function shot(name) {
    const file = path.join(outDir, `${name}.png`);
    await page.screenshot({ path: file, fullPage: true });
    return file;
}

async function flow(name, fn) {
    try {
        await fn();
        const file = await shot(name);
        results.push({ name, status: 'PASS', screenshot: file });
        console.log(`PASS ${name} ${file}`);
    } catch (error) {
        let file = '';
        try {
            file = await shot(`${name}-FAIL`);
        } catch {
            file = '';
        }
        results.push({ name, status: 'FAIL', screenshot: file, error: error.message });
        console.log(`FAIL ${name} ${error.message}`);
    }
}

function assertText(text, expected, label) {
    if (!text.includes(expected)) {
        throw new Error(`${label} thiếu "${expected}". Đoạn đầu: ${text.replace(/\s+/g, ' ').slice(0, 240)}`);
    }
}

await flow('home', async () => {
    await page.goto(`${base}/index.php/shops/`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    const html = await page.content();
    assertText(html, 'Dữ liệu mẫu', 'trang cửa hàng');
    assertText(text, 'đ', 'trang cửa hàng');
    if (!html.includes('page-2')) throw new Error('trang cửa hàng không có phân trang');
});

await flow('category', async () => {
    await page.goto(`${base}/index.php/shops/dien-lanh/`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Điện lạnh', 'ngành Điện lạnh');
    assertText(text, 'Tủ lạnh', 'ngành Điện lạnh');
});

await flow('category-page', async () => {
    await page.goto(`${base}/index.php/shops/tu-lanh/page-2/`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Tủ lạnh', 'trang 2 tủ lạnh');
    assertText(text, 'đ', 'trang 2 tủ lạnh');
});

await flow('product', async () => {
    await page.goto(`${base}/index.php/shops/smartphone/samsung-galaxy-a15-128gb.html`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Samsung Galaxy A15 128GB', 'trang sản phẩm');
    assertText(text, 'Dữ liệu mẫu', 'trang sản phẩm');
    const button = await page.$('button.btn-order-cart');
    if (!button) throw new Error('không thấy nút thêm vào giỏ');
});

await flow('search', async () => {
    await page.goto(`${base}/index.php/shops/search/?q=Galaxy+A15`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Galaxy A15', 'tìm kiếm');
});

await flow('cart', async () => {
    await page.goto(`${base}/index.php/shops/smartphone/samsung-galaxy-a15-128gb.html`, { waitUntil: 'domcontentloaded' });
    await page.click('button.btn-order-cart');
    await page.waitForNetworkIdle({ idleTime: 400, timeout: 8000 }).catch(() => {});
    await page.goto(`${base}/index.php/shops/cart/`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Samsung Galaxy A15 128GB', 'giỏ hàng');
});

await flow('checkout', async () => {
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('input[name="cart_order"]')
    ]);
    await page.waitForFunction(() => {
        const form = document.querySelector('#fpost');
        const events = window.jQuery && form ? window.jQuery._data(form, 'events') : null;
        return Boolean(events && events.submit && events.submit.length);
    });
    const text = await page.evaluate(() => document.body.innerText);
    const form = await page.$('#fpost');
    if (!form) throw new Error('không thấy phiếu đặt hàng');
    assertText(text, 'đ', 'trang đặt hàng');
    assertText(text, 'Samsung Galaxy A15', 'trang đặt hàng');
});

await flow('order-confirmation', async () => {
    await page.locator('#order_name').fill('Nguyễn Văn An');
    await page.locator('#order_email').fill('an.nguyen@example.com');
    await page.locator('#order_phone').fill('0912345678');
    if (await page.$('#order_address')) await page.locator('#order_address').fill('12 Lê Lợi, Quận 1, TP. Hồ Chí Minh');
    if (await page.$('#order_note')) await page.locator('#order_note').fill('Giao giờ hành chính');
    await page.click('#check');
    // Same request the submit button's script sends: post the form to this page.
    const body = await page.evaluate(() => new Promise((resolve, reject) => {
        const form = document.querySelector('#fpost');
        window.jQuery.ajax({
            type: 'POST',
            url: window.location.href,
            data: window.jQuery(form).serialize(),
            success: (json) => resolve(typeof json === 'string' ? json : JSON.stringify(json)),
            error: (xhr) => reject(new Error((xhr.responseText || xhr.statusText || 'ajax').slice(0, 240)))
        });
    }));
    let redirect = '';
    try {
        redirect = JSON.parse(body).redirect || '';
    } catch {
        throw new Error(`đặt hàng không trả JSON. ${body.replace(/\s+/g, ' ').slice(0, 240)}`);
    }
    if (!redirect) throw new Error(`đặt hàng không có địa chỉ chuyển. ${body.slice(0, 240)}`);
    await page.goto(new URL(redirect, page.url()).toString(), { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Nguyễn Văn An', 'trang đơn');
    assertText(text, '0912345678', 'trang đơn');
    if (!/\bS\d{3,}\b/.test(text)) {
        throw new Error(`trang đơn không có mã đơn. Đoạn đầu: ${text.replace(/\s+/g, ' ').slice(0, 240)}`);
    }
});

await flow('admin-products', async () => {
    await page.goto(`${base}/admin/index.php`, { waitUntil: 'domcontentloaded' });
    if (await page.$('input[name="nv_password"]')) {
        await page.type('input[name="nv_login"]', env.NV_ADMIN_USER);
        await page.type('input[name="nv_password"]', env.NV_ADMIN_PASS);
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'domcontentloaded' }),
            page.click('input[type="submit"], button[type="submit"]')
        ]);
    }
    await page.goto(`${base}/admin/index.php?language=vi&nv=shops&op=items`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Galaxy', 'danh sách sản phẩm');
});

await flow('admin-product-form', async () => {
    await page.goto(`${base}/admin/index.php?language=vi&nv=shops&op=content`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    const title = await page.$('input[name="title"]');
    const price = await page.$('input[name="product_price"]');
    if (!title || !price) throw new Error(`form thêm sản phẩm thiếu ô. ${text.replace(/\s+/g, ' ').slice(0, 180)}`);
});

await flow('admin-product-edit', async () => {
    await page.goto(`${base}/admin/index.php?language=vi&nv=shops&op=items`, { waitUntil: 'domcontentloaded' });
    const href = await page.evaluate(() => {
        const link = [...document.querySelectorAll('a')].find((a) => /op=content/.test(a.getAttribute('href') || '') && /id=/.test(a.getAttribute('href') || ''));
        return link ? link.href : '';
    });
    if (!href) throw new Error('danh sách không có liên kết sửa sản phẩm');
    await page.goto(href, { waitUntil: 'domcontentloaded' });
    const value = await page.$eval('input[name="title"]', (el) => el.value);
    if (!value) throw new Error('form sửa không có tên sản phẩm');
});

await flow('admin-orders', async () => {
    await page.goto(`${base}/admin/index.php?language=vi&nv=shops&op=order`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    if (!/\bS\d{3,}\b/.test(text)) throw new Error('danh sách đơn không có mã đơn');
});

await flow('admin-order-detail', async () => {
    const href = await page.evaluate(() => {
        const link = [...document.querySelectorAll('a')].find((a) => /op=or_view/.test(a.getAttribute('href') || ''));
        return link ? link.href : '';
    });
    if (!href) throw new Error('không có liên kết xem đơn');
    await page.goto(href, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    if (!/\bS\d{3,}\b/.test(text)) throw new Error('trang đơn quản trị không có mã đơn');
    if (!/\d{8,}/.test(text)) throw new Error('trang đơn quản trị không có số điện thoại');
});

await flow('admin-categories', async () => {
    await page.goto(`${base}/admin/index.php?language=vi&nv=shops&op=cat`, { waitUntil: 'domcontentloaded' });
    const text = await page.evaluate(() => document.body.innerText);
    assertText(text, 'Điện thoại', 'ngành hàng');
    assertText(text, 'Điện lạnh', 'ngành hàng');
    assertText(text, 'Tủ lạnh', 'ngành hàng');
});

await browser.close();

const failed = results.filter((row) => row.status === 'FAIL');
console.log('\n| Flow | Result | Screenshot |');
console.log('| --- | --- | --- |');
for (const row of results) {
    console.log(`| ${row.name} | ${row.status} | ${row.screenshot} |`);
}
if (failed.length) process.exit(1);
