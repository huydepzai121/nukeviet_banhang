// What a shopper does on the storefront when the action is driven by the
// page's own script rather than by a link or a form.
import assert from 'node:assert/strict';
import { fill } from './browser.mjs';

/**
 * Presses "add to cart" on a product page. The button carries the product's
 * number; the storefront script posts it together with the quantity.
 */
export async function addToCart(browser, productPage, quantity = 1) {
    const button = /<button[^>]*btn-order-cart[^>]*data-id="(\d+)"/.exec(productPage.body);
    assert.ok(button, `trang sản phẩm (${productPage.url}) có nút thêm vào giỏ hàng`);
    const reply = await browser.ajax(
        `/index.php?language=vi&nv=shops&op=setcart&id=${button[1]}&group=&nocache=${Date.now()}`,
        [['num', String(quantity)]]
    );
    return reply.body.trim();
}

/**
 * Sends the order form. The storefront script posts it in the background and
 * then takes the shopper to the address the site answers with.
 */
export async function placeOrder(browser, checkoutPage, details) {
    const form = checkoutPage.form(/id="fpost"/);
    const reply = await browser.ajax(form.action, fill(form.fields, details));
    let answer;
    try {
        answer = JSON.parse(reply.body);
    } catch {
        assert.fail(`gửi phiếu đặt hàng phải nhận được câu trả lời hợp lệ, nhận được: ${reply.body.slice(0, 300)}`);
    }
    assert.ok(answer.redirect, `đặt hàng phải thành công, nhận được: ${reply.body.slice(0, 300)}`);
    return browser.get(answer.redirect);
}
