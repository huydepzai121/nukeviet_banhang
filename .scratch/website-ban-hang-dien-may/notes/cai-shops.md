# Cài module shops (nhánh develop) lên NukeViet 4.6.03

Bản clone: `C:\Users\Admin\AppData\Local\Temp\claude\a6b6136091890e57\f83290c3-6678-473f-8f38-1fe7a4f72bf4\scratchpad\module-shops\` (viết tắt `SH/`), commit `a9575390d5aac05390eada350031724978c5448b` (09/11/2026, "Bổ sung link rss"). Lõi: `E:\Dev\www\nukeviet_banhang\.work\nvzip\nukeviet\` (`NV/`). **[Mã]** = đọc thấy, **[Suy luận]** = chưa chạy thử. Không có tệp PHP nào của bản clone được thực thi.

## 1. Cấu trúc repo và ánh xạ

Gốc repo (289 tệp) trùng với gốc site NukeViet, chép đè 1-1:

| Trong repo | Vào site | Ghi chú |
|---|---|---|
| `modules/shops/` | `modules/shops/` | `admin/` (70 tệp op), `funcs/` (29), `blocks/` (11 block + 7 `.ini`), `language/` (`vi.php`, `en.php`, `admin_vi.php`, `admin_en.php`, `data_vi.php`), `action_mysql.php`, `functions.php`, `global.functions.php`, `site.functions.php`, `admin.functions.php`, `admin.menu.php`, `theme.php`, `version.php`, `fields.check.php`, `comment.php`, `menu.php`, `notification.php`, `rssdata.php`, `search.php`, `siteinfo.php`, `wallet.admin.php` |
| `themes/default/modules/shops/` (33 tpl) | cùng đường dẫn | template ngoài site |
| `themes/default/css/shops.css`, `css/jquery.bxslider.css` | cùng đường dẫn | |
| `themes/default/js/shops.js`, `js/jquery.bxslider.min.js` | cùng đường dẫn | |
| `themes/default/images/shops/` | cùng đường dẫn | ảnh + `jquery.ez-plus.js`, `OwlCarousel2/`, `tiny-slider/`, `rate/`, `icon_files/` |
| `themes/admin_default/modules/shops/` (49 tpl), `css/shops.css`, `js/shops.js`, `js/content.js` | cùng đường dẫn | giao diện quản trị |
| `assets/shops/2015_05/*.jpg`, `uploads/shops/2015_05/*.jpg` | cùng đường dẫn | 1 ảnh mẫu cho dữ liệu mẫu |
| `config.ini` | KHÔNG chép | tệp mô tả gói (id 31, **version 4.5.04** — lệch với `version.php`) |
| `README-SHOPS.md`, `CHANGELOG-SHOPS.txt`, `CONTRIBUTING-SHOPS.md`, `.gitignore` | KHÔNG chép | |

Không có: `composer.json`, `vendor/`, thư mục `payment/`, `modules/shops/mobile/`, template cho `mobile_default`, tệp cài đặt/cập nhật riêng, `service_shops_nganluong.php` (chỉ còn tên trong `.gitignore`).

## 2. Phiên bản và yêu cầu

- **[Mã]** `SH/modules/shops/version.php` dòng 15-33: `name=Shops`, `version=4.6.00`, `date='Wednesday, August 19, 2026 at 8:38:36 AM UTC+07:00'`, `is_sysmod=0`, `virtual=1`, `modfuncs='main,viewcat,detail,search,cart,order,payment,complete,history,group,compare,wishlist,tag,point,shippingajax,download, blockcat,ajax'`, `uploads_dir = [shops, shops/temp_pic, shops/<Y_m>, shops/files]`, `files_dir = [shops/files_tpl]`.
  - `complete` có trong `modfuncs` nhưng không có `funcs/complete.php` → vô hại (`nv_setup_data_module` chỉ đánh dấu những func có tệp, `NV/admin/modules/functions.php` dòng 306-314).
  - `print`, `print_pro`, `review`, `setcart`, `loadcart`, `remove`, `checkorder`, `delhis`, `sendmail`, `wishlist_update`, `rss`, `sitemap` không có trong `modfuncs` → vẫn được đăng ký vào `_modfuncs` nhưng `show_func=0` (không gán block).
- **[Mã]** Không khai báo yêu cầu PHP ở đâu trong repo. Lõi 4.6.03 đòi PHP ≥ 7.4 (`NV/composer.json`, `NV/includes/ini.php` dòng 428). Mã shops đã được vá cho PHP 8 ở nhiều chỗ (mẫu `list(...) = $x->fetch(3) ?: [null]`).
- **[Mã]** `README-SHOPS.md`: "Nếu sử dụng thanh toán online cần dùng Composer cài thêm `paypal/sdk-core-php`, `paypal/permissions-sdk-php`, `paypal/merchant-sdk-php`, `nusphere/nusoap`". **Câu này đã lỗi thời đối với nhánh develop**: tìm trong toàn bộ `modules/shops` không còn chữ `paypal`, `nusoap`, `vendor`, `composer` nào. Thanh toán trực tuyến hiện chỉ đi qua module ngoài `wallet` (`funcs/payment.php` dòng 145, 230-231, 300-302: `modules/wallet/wallet.class.php`, lớp `nukeviet_wallet`). Không có module wallet thì shops vẫn chạy, chỉ không có nút thanh toán trực tuyến → luồng COD của ticket 02 không cần phụ thuộc nào.

## 3. Cách cài

Chép tay (cách phù hợp với ticket 02 "commit mã nguyên bản trước"):
1. Chép `modules/shops`, `themes/default/{modules/shops, css/shops.css, css/jquery.bxslider.css, js/shops.js, js/jquery.bxslider.min.js, images/shops}`, `themes/admin_default/{modules/shops, css/shops.css, js/shops.js, js/content.js}`; tuỳ chọn `assets/shops`, `uploads/shops` (chỉ cần khi nạp dữ liệu mẫu).
2. Quản trị > Quản lý module > "Thiết lập module mới" (`nv=modules&op=setup`). Việc mở trang này tự ghi shops vào `_setup_extensions` (`NV/admin/modules/setup.php` dòng 131-187; điều kiện: có `funcs/main.php` + `functions.php` hoặc `admin/main.php` + `admin.functions.php`, tệp khác rỗng).
3. Bấm cài shops (link `setmodule=shops&checkss=md5('setmodule' . 'shops' . NV_CHECK_SESSION)`, thêm `&sample=1` nếu muốn dữ liệu mẫu `language/data_vi.php`). Hệ thống chạy `action_mysql.php`, đăng ký func, tạo thư mục upload, bật `act=1` rồi chuyển sang trang sửa module (`op=edit&mod=shops`).
4. Tên bảng: `<prefix>_shops_*` (`module_data = shops`, KHÔNG có tiền tố ngôn ngữ, trừ `_money_<lang>`, `_weight_<lang>`, `_tags_<lang>`, `_tags_id_<lang>`, `_field_value_<lang>`). Đa ngôn ngữ bằng cột `<lang>_title`... thêm bằng `ALTER TABLE`.

Qua gói zip: xem `dong-goi-module.md` mục 4 (cần `extension_setup_ips`, giới hạn dung lượng, gốc zip chỉ có `config.ini`).

Cấu hình mặc định liên quan ticket 02 (**[Mã]** `action_mysql.php` dòng 729-783): `active_guest_order=1` (khách vãng lai đặt được), `active_order=1`, `active_price=1`, `active_order_number=0` (có trừ kho, sản phẩm phải có `product_number` > 0 mới thêm giỏ được — `funcs/setcart.php` dòng 36), `auto_check_order=1`, `active_payment=1`, `use_shipping=0`, `use_coupons=0`, `point_active=0`, `active_warehouse=0`, `money_unit=VND`, `review_captcha=1`, `format_order_id='S%06s'`.

## 4. Theme

- **[Mã]** Lõi 4.6.03 có 3 theme: `admin_default`, `default`, `mobile_default` (`NV/themes/`). `default` là Bootstrap 3 + XTemplate, layout mặc định `left-main-right`.
- **[Mã]** Shops CÓ template cho `default` (33 tpl) và `admin_default` (49 tpl). KHÔNG có cho `mobile_default`.
- **[Mã]** Khi theme đang dùng không có `modules/shops`, lõi đặt `$module_info['template'] = 'default'` (`NV/index.php` dòng 312-318) và shops dựng tpl theo `$module_info['template']` (`theme.php` dòng 74...) → tpl của `default` được dùng lại. **[Suy luận]** trên `mobile_default` trang vẫn ra nhưng thiếu `shops.css`/`shops.js` của theme đó (theme mobile tự nạp css/js theo tên module trong thư mục của nó) → cần kiểm thử; nếu không cần giao diện mobile riêng thì đặt site/ module dùng theme responsive (`mobile = :pcsite`).
- Thư viện kèm theo: `block.product_center.tpl` dòng 2-3 dùng `tiny-slider`; `block.bxproduct_center.tpl` dòng 3-4 dùng bxslider; `order.tpl` dòng 2-3 dùng `assets/js/select2` của lõi (có sẵn trong 4.6.03); `search.tpl` dùng `jquery-ui` của lõi. `jquery.ez-plus.js`, `OwlCarousel2` nằm trong repo nhưng tìm không thấy tpl/php/`shops.js` nào gọi tới → có vẻ là tệp thừa.

## 5. Điểm có khả năng gây lỗi trên 4.6.03 + PHP 8.2

Đã đối chiếu: mọi hàm `nv_*` mà shops gọi đều tồn tại trong lõi 4.6.03 (trừ `nv_fix_source` trong tệp chết `admin/del_source.php`); không dùng `$nv_Lang`, `each()`, `create_function`, `strftime`, `utf8_encode`, `${var}`; không truy cập `$_GET/$_POST` trực tiếp (có dùng `$_SESSION` trực tiếp cho giỏ hàng). Không thấy trở ngại cú pháp cho việc cài. Các điểm sau là lỗi lúc chạy:

Chặn hoặc sát luồng ticket 02:
1. **[Mã]** `funcs/order.php` dòng 96: nếu mã giảm giá trong session không còn trong CSDL thì `$counpons = false`, `$counpons['id']` nối vào SQL rỗng → PDOException. Chỉ xảy ra khi có coupon.
2. **[Mã]** `funcs/order.php` dòng 26-49: `$data_order` ban đầu không có khoá `order_address` → cảnh báo "Undefined array key" khi hiển thị form (tuỳ `theme.php` có đọc hay không — cần chạy thật).
3. **[Mã]** `funcs/order.php` dòng 264-268: lấy `auto_increment` bằng `SHOW TABLE STATUS` để dựng mã đơn trước khi INSERT; trên MySQL 8 số liệu này có thể bị cache (`information_schema_stats_expiry`) → mã đơn tạm trùng → vi phạm `UNIQUE KEY order_code` → `insert_id` trả false, đặt hàng thất bại im lặng. **[Suy luận]** đây là ứng viên giải thích issue #713/#500; phải tái hiện.
4. **[Suy luận]** MySQL chế độ strict: nhiều cột `NOT NULL` không có mặc định (`orders.transaction_count`, `rows.price_config`, `rows.homeimgalt`, `rows.<lang>_hometext`, `review.sender`...). Câu INSERT nào bỏ sót cột là lỗi. `orders` INSERT ở `order.php` dòng 271-280 có đủ cột; các chỗ khác (thêm sản phẩm ở `admin/content.php`) cần chạy thật.
5. **[Mã]** Bảng dùng `ENGINE=MyISAM` → không có transaction; trừ kho bằng `UPDATE ... product_number = product_number - n` không kiểm tra lại tồn (`global.functions.php` dòng 328), cột `product_number int` có dấu nên có thể âm. Không chặn ticket 02 nhưng là gốc của ticket 11 (chống bán quá).

Lỗi chắc chắn trên PHP 8 (TypeError/Fatal) hoặc SQL:
6. **[Mã]** `funcs/wishlist_update.php` dòng 30: `sizeof($wishlist)` khi `fetch()` trả `false` → `TypeError` (issue #700, vẫn còn trong develop).
7. **[Mã]** `global.functions.php` dòng 58 và `funcs/order.php` dòng 319: gọi `product_number_order($listid, $listnum, '+')` thiếu tham số `$listgroup` → `'+'` rơi vào `$listgroup`, `$type` vẫn là `'-'` → **trừ kho thêm lần nữa thay vì cộng trả**, kèm `$listgroup[$i]` trên chuỗi. Ở dòng 50-58 còn dùng `id` của dòng đơn thay cho `proid` và ghi đè biến `$result` của vòng lặp ngoài.
8. **[Mã]** `funcs/delhis.php`: đọc `$data['status']` — bảng `orders` không có cột `status` → điều kiện `null == 0` luôn đúng → đơn nào cũng xoá được. `admin/print.php` dòng 24: `UPDATE orders SET status = 1` → lỗi SQL (cột không tồn tại); tệp này cũng không nằm trong `$allow_func` (`admin.functions.php` dòng 15-87) nên không truy cập được.
9. **[Mã]** `funcs/print.php` dòng 44: `$row['group_id']` — bảng `orders_id` không có cột này (cột là `listgroupid`) → cảnh báo. Dòng 23-28: `$page_url` không chứa `order_id`/`checkss` rồi gọi `getCanonicalUrl($page_url)`; link in (`theme.php` dòng 1045, `admin/or_view.php` dòng 246) là dạng `index.php?...` chưa rewrite. Theo `getPageUrl()` (`NV/includes/functions.php` dòng 1871-1917) nếu path yêu cầu khác path đã rewrite thì trả `false` → `nv_redirect_location($page_url)` → mất tham số → rơi vào nhánh else, chuyển về trang chủ module. **[Suy luận]** đây là lỗi in đơn (#722) khi bật rewrite.
10. **[Mã]** `funcs/search.php` dòng 68-72: cùng kiểu — `$page_url` không mang tham số tìm kiếm, `getCanonicalUrl()` chuyển hướng khi path không khớp. **[Suy luận]** khớp mô tả #705 (search tự redirect, block search lỗi). `blocks/global.block_search.php` dòng 55 còn nạp `modules/shops/js/user.js` không tồn tại.
11. **[Mã]** `funcs/sendmail.php`: truy vấn bảng `NV_PREFIXLANG . '_shops_<catid>'` (di sản module news) → lỗi SQL (#587).
12. **[Mã]** `admin.functions.php` dòng 15-87: `$allow_func` có `copy_product` nhưng không có `admin/copy_product.php`; có các tệp không được cho phép/chết: `admin/print.php`, `change_source.php`, `del_source.php` (bảng `_sources` không tồn tại).
13. **[Mã]** `action_mysql.php` dòng 97-139: phần gỡ có DROP các bảng không hề được tạo (`_info`, `_group_quantity_logs`, `_carrier_location`, `_carrier_weight`, `_field_<lang>`) — vô hại vì `IF EXISTS`. Các bảng dùng chung chỉ bị DROP khi `$op != 'setup'` và ngôn ngữ đang gỡ là ngôn ngữ duy nhất còn cài (dòng 47-140) → tiêu chí "gỡ xong không còn bảng" của ticket 05 đạt khi site chỉ cài shops ở một ngôn ngữ; với hai ngôn ngữ phải gỡ ở cả hai.
14. **[Mã]** `site.functions.php` dòng 225: `number_format((int) $number, ...)` ép về int → mất phần thập phân với tiền tệ có lẻ; dòng 126: `nv_get_price()` gán lại biến `global $module_data` khi được gọi từ block của module khác → có thể làm hỏng các truy vấn sau đó trong cùng request.
15. **[Mã]** Bảo mật cần xử lý ở ticket 03/04: `funcs/payment.php` dòng 234-239 nối `$worderid` (`get_title`) thẳng vào SQL; `admin/getprice.php` nối `id` (`get_title`) vào SQL; 49/70 tệp `admin/` không có kiểm tra token nào (danh sách ở `ban-do-shops.md`); đặt hàng ngoài site không có token chống giả mạo.

## 6. Điểm lệch cần biết khi làm ticket 02

- `config.ini` của repo ghi 4.5.04; khi xuất gói từ quản trị, version lấy từ `_setup_extensions` (= `version.php` 4.6.00) nên không ảnh hưởng.
- Chú thích trong `funcs/payment.php` dòng 105-110 cho thấy mã đã được vá chắp nhiều lần; trang thanh toán tính lại giá bằng `nv_get_price()` (dòng 123) thay vì dùng giá đã lưu ở `orders_id.price`.

## Chưa xác minh

- Chưa cài thật (không được đụng `site/`). Mọi mục **[Suy luận]** cần tái hiện ở ticket 02-04.
- Chưa đọc hết `admin/content.php` (1311 dòng), `theme.php` (2209 dòng), các tpl; chưa rà `shops.js`.
