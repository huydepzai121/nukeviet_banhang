// From an empty NukeViet site to a guest's cash-on-delivery order.
// The steps build on each other and run in order.
import { describe, test, before } from 'node:test';
import assert from 'node:assert/strict';
import { Browser } from '../lib/browser.mjs';
import { loadsCleanly, shows } from '../lib/expect.mjs';
import { addToCart, placeOrder } from '../lib/shopper.mjs';
import { adminUrl, signInAsStaff } from '../lib/staff.mjs';

describe('khách vãng lai đặt đơn trả tiền khi nhận hàng', () => {
    let staff;

    before(async () => {
        staff = await signInAsStaff();
    });

    test('nhân viên cài module shops từ trang quản trị và trang bán hàng mở được', async () => {
        const setup = await staff.get(adminUrl('nv=modules&op=setup'));
        const [install] = setup.links(/setmodule=shops/);
        assert.ok(install, 'trang thiết lập module mới có liên kết cài shops');

        loadsCleanly(await staff.get(install), 'trang sau khi cài');

        const shopAdmin = await staff.get(adminUrl('nv=shops'));
        loadsCleanly(shopAdmin, 'trang quản trị shops');
        shows(shopAdmin, 'Tổng số đơn đặt hàng 0 đơn đặt', 'trang quản trị shops');

        loadsCleanly(await new Browser().get('/shops/'), 'trang bán hàng');
    });

    test('nhân viên tạo một ngành hàng và khách mở được trang ngành hàng đó', async () => {
        const form = (await staff.get(adminUrl('nv=shops&op=cat'))).form(/frm-cat-content/);
        const saved = await staff.submit(form, { title: 'Tủ lạnh', alias: 'tu-lanh' });
        loadsCleanly(saved, 'trang sau khi lưu ngành hàng');

        const list = await staff.get(adminUrl('nv=shops&op=cat'));
        shows(list, 'Tủ lạnh', 'danh sách ngành hàng trong quản trị');

        const category = await new Browser().get('/shops/tu-lanh/');
        loadsCleanly(category, 'trang ngành hàng');
        shows(category, 'Tủ lạnh', 'trang ngành hàng');
    });

    test('nhân viên tạo một sản phẩm và khách thấy sản phẩm kèm giá trong ngành hàng', async () => {
        const units = await staff.get(adminUrl('nv=shops&op=prounit'));
        await staff.submit(units.form(), { title: 'Chiếc' });

        const add = await staff.get(adminUrl('nv=shops&op=content'));
        const form = add.form(/frm-submit/);
        const [, categoryId] = /<option[^>]*value="(\d+)"[^>]*>[^<]*Tủ lạnh/.exec(add.body) || [];
        assert.ok(categoryId, 'ô chọn ngành hàng có Tủ lạnh');

        const saved = await staff.submit(form, {
            title: 'Tủ lạnh Inverter 300 lít',
            alias: 'tu-lanh-inverter-300-lit',
            catid: categoryId,
            product_code: 'TL300',
            product_number: 5,
            product_price: 7500000,
            money_unit: 'VND',
            hometext: 'Tủ lạnh hai cửa tiết kiệm điện',
            bodytext: '<p>Dung tích 300 lít, ngăn đá trên.</p>',
            status: 1
        });
        loadsCleanly(saved, 'trang sau khi lưu sản phẩm');

        const listing = await new Browser().get('/shops/tu-lanh/');
        loadsCleanly(listing, 'trang ngành hàng');
        shows(listing, 'Tủ lạnh Inverter 300 lít', 'trang ngành hàng');
        shows(listing, /7[.,]500[.,]000/, 'trang ngành hàng');
    });

    test('khách vãng lai thêm sản phẩm vào giỏ, đặt đơn trả tiền khi nhận hàng, và nhân viên thấy đơn đó', async () => {
        const guest = new Browser();
        const listing = await guest.get('/shops/tu-lanh/');
        const [productLink] = listing.links(/tu-lanh-inverter-300-lit/);
        assert.ok(productLink, 'trang ngành hàng có liên kết tới sản phẩm');
        const product = await guest.get(productLink);
        loadsCleanly(product, 'trang sản phẩm');

        const added = await addToCart(guest, product, 2);
        assert.match(added, /^OK_/, `thêm vào giỏ phải thành công, nhận được: ${added.slice(0, 200)}`);

        const cart = await guest.get('/shops/cart/');
        loadsCleanly(cart, 'trang giỏ hàng');
        shows(cart, 'Tủ lạnh Inverter 300 lít', 'trang giỏ hàng');
        shows(cart, '15.000.000', 'trang giỏ hàng');

        const checkout = await guest.submit(cart.form(/id="fpro"/), { cart_order: 'Đặt hàng' });
        loadsCleanly(checkout, 'trang đặt hàng');
        shows(checkout, 'Tổng số tiền cần thanh toán: 15.000.000 đ', 'trang đặt hàng');

        const placed = await placeOrder(guest, checkout, {
            order_name: 'Nguyễn Văn An',
            order_email: 'an.nguyen@example.com',
            order_phone: '0912345678',
            order_address: '12 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
            order_note: 'Giao giờ hành chính',
            check: 1
        });
        loadsCleanly(placed, 'trang sau khi đặt hàng');
        shows(placed, 'Nguyễn Văn An', 'trang đơn hàng của khách');
        shows(placed, '15.000.000', 'trang đơn hàng của khách');
        const [orderCode] = /\bS\d{6}\b/.exec(placed.text) || [];
        assert.ok(orderCode, `trang đơn hàng của khách phải hiện mã đơn; đang hiện: ${placed.text.slice(0, 300)}`);
        shows(placed, 'Chưa thanh toán', 'trang đơn hàng của khách');

        const orders = await staff.get(adminUrl('nv=shops&op=order'));
        loadsCleanly(orders, 'danh sách đơn hàng trong quản trị');
        shows(orders, orderCode, 'danh sách đơn hàng trong quản trị');
        shows(orders, 'an.nguyen@example.com', 'danh sách đơn hàng trong quản trị');
        shows(orders, 'Tổng số đơn đặt hàng: 1', 'danh sách đơn hàng trong quản trị');

        const [orderLink] = orders.links(/op=or_view/);
        assert.ok(orderLink, 'danh sách đơn hàng có liên kết xem đơn');
        const order = await staff.get(orderLink);
        loadsCleanly(order, 'trang xem đơn trong quản trị');
        for (const expected of [
            orderCode,
            'Nguyễn Văn An',
            '0912345678',
            '12 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
            'Tủ lạnh Inverter 300 lít',
            'Tổng tiền: 15.000.000 đ',
            'Chưa thanh toán',
            'Giao giờ hành chính'
        ]) {
            shows(order, expected, 'trang xem đơn trong quản trị');
        }
    });
});
