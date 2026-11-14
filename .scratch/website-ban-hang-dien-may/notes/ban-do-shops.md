# Bản đồ mã module shops (nhánh develop, version.php 4.6.00)

Gốc: `C:\Users\Admin\AppData\Local\Temp\claude\a6b6136091890e57\f83290c3-6678-473f-8f38-1fe7a4f72bf4\scratchpad\module-shops\modules\shops\` (mọi đường dẫn dưới đây tương đối với thư mục này). Mô tả từng tệp dựa trên việc đọc phần đầu tệp và tên; những tệp đã đọc kỹ có ghi số dòng.

## 1. Tệp khung

| Tệp | Vai trò |
|---|---|
| `version.php` | khai báo module (xem `cai-shops.md`) |
| `action_mysql.php` (876 dòng) | tạo/xoá 44 bảng + cấu hình mặc định |
| `functions.php` | nạp khi ra ngoài site: định nghĩa `NV_IS_MOD_SHOPS`, require `global.functions.php` + `site.functions.php`, xác định `$catid`/`$groupid` từ URL, chuyển `op=main` thành `viewcat`/`detail` theo alias (dòng 65-90), nạp wishlist; các hàm `GetDataIn*`, `SetSessionProView`, `nv_custom_tpl` |
| `global.functions.php` | dùng chung site + admin: `$pro_config`, tự xoá đơn quá hạn (dòng 40-70), tự đăng/hết hạn sản phẩm (dòng 72-134), `product_number_order()` (318), `product_number_sell()` (347), `UpdatePoint()` (560), `nv_listmail_notify()`, cây loại/nhóm |
| `site.functions.php` | cache loại, nhóm, tiền tệ, khối lượng, địa điểm, nhà vận chuyển, cửa hàng, giảm giá (dòng 15-106); `nv_get_price()` (117), `nv_currency_conversion()` (200), `nv_number_format()` (220), `nv_get_decimals()` (236), `nv_weight_conversion()` (258), `nv_shipping_price()` (297) |
| `theme.php` (2209 dòng) | toàn bộ hàm dựng giao diện ngoài site bằng XTemplate (danh sách ở mục 6) |
| `admin.functions.php` | `$allow_func` (dòng 15-87), hàm sắp xếp cây loại/nhóm/địa điểm, form trường tuỳ biến, `email_new_order_payment()` |
| `admin.menu.php` | menu quản trị, ẩn/hiện theo cấu hình (`use_shipping`, `review_active`, `active_warehouse`, `use_coupons`, `point_active`, `download_active`, `template_active`, `active_payment`) |
| `fields.check.php` | kiểm tra dữ liệu trường tuỳ biến khi lưu sản phẩm |
| `comment.php`, `menu.php`, `notification.php`, `rssdata.php`, `search.php`, `siteinfo.php` | điểm tích hợp với module comment, menu, thông báo quản trị, feeds, seek (tìm kiếm toàn site), siteinfo |
| `wallet.admin.php` | móc cho module `wallet` cập nhật đơn; phần thân còn `// FIXME` (dòng 30-37), chưa làm gì |

## 2. Funcs ngoài site (`funcs/`, 29 tệp)

| Tệp | Làm gì |
|---|---|
| `main.php` | trang chủ module: tất cả sản phẩm / theo loại / theo nhóm (`home_data`), sắp xếp, phân trang |
| `viewcat.php` | danh sách sản phẩm của một ngành hàng (lưới/danh sách, lọc theo nhóm, sắp xếp) |
| `detail.php` | chi tiết sản phẩm, sản phẩm cùng loại, đã xem, tab, trường tuỳ biến, bình luận |
| `group.php` | sản phẩm theo nhóm (`/group/<alias>`) |
| `blockcat.php` | sản phẩm theo "block sản phẩm" (`/blockcat/<alias>`) |
| `tag.php` | sản phẩm theo tag |
| `search.php` | tìm kiếm trong module (từ khoá, loại, khoảng giá, ngày, nhóm) |
| `compare.php` | so sánh sản phẩm (danh sách id trong session) |
| `wishlist.php` / `wishlist_update.php` | xem / thêm-bớt (ajax) sản phẩm yêu thích, cần đăng nhập |
| `setcart.php` | ajax thêm/sửa số lượng trong giỏ (`$_SESSION[<module_data>_cart]`, khoá `<id>_<group>`), kiểm tra tồn kho dòng 36, 53, 72 |
| `remove.php` | ajax xoá một dòng giỏ |
| `loadcart.php` | ajax trả HTML/số lượng/tổng tiền giỏ (cho block giỏ hàng) |
| `cart.php` | trang giỏ hàng, kiểm tra/áp mã giảm giá, sửa đơn cũ |
| `order.php` (711 dòng) | form đặt hàng + xử lý `postorder`: tính tổng, coupon, điểm, phí vận chuyển, ghi `orders`, `orders_id`, `orders_id_group`, `orders_shipping`, trừ kho, gửi mail |
| `shippingajax.php` | ajax lấy nhà vận chuyển + phí theo địa điểm/cửa hàng |
| `payment.php` | trang xem đơn sau khi đặt (token `md5(order_id . sitekey . session_id())`), sửa/huỷ sửa đơn, khởi tạo và nhận kết quả thanh toán qua module `wallet` |
| `checkorder.php` | ajax kiểm tra trạng thái đơn |
| `history.php` | lịch sử đơn của thành viên |
| `delhis.php` | ajax khách tự xoá đơn, cộng trả kho |
| `point.php` | lịch sử điểm tích luỹ |
| `print.php` | in đơn hàng (ngoài site; quản trị cũng trỏ về đây) |
| `print_pro.php` | bản in của trang sản phẩm |
| `review.php` | danh sách + gửi đánh giá (sao + nội dung) |
| `download.php` | tải tệp đính kèm sản phẩm theo nhóm quyền |
| `sendmail.php` | gửi sản phẩm cho bạn bè — đang hỏng (truy vấn bảng kiểu module news) |
| `ajax.php` | lưu lựa chọn sắp xếp / kiểu xem vào session |
| `rss.php`, `sitemap.php` | RSS, sitemap sản phẩm |

## 3. Funcs quản trị (`admin/`, 70 tệp)

| Nhóm | Tệp | Làm gì |
|---|---|---|
| Tổng quan | `main.php` | thống kê số sản phẩm, đơn hàng |
| Sản phẩm | `items.php` | danh sách + lọc + thao tác hàng loạt |
| | `content.php` (1311 dòng) | thêm/sửa sản phẩm (giá, kho, nhóm, ảnh, tag, quà tặng, trường tuỳ biến, tệp) |
| | `del_content.php`, `publtime.php`, `exptime.php` | xoá, đăng lại, cho hết hạn |
| | `alias.php`, `keywords.php`, `tagsajax.php`, `getcatalog.php`, `getgroup.php`, `custom_form.php`, `getprice.php` | ajax phụ trợ form sản phẩm |
| | `updateprice.php` | cập nhật giá hàng loạt theo loại |
| | `view.php` | chuyển hướng ra trang sản phẩm ngoài site |
| | `seller.php` | danh sách đơn đã bán của một sản phẩm |
| Ngành hàng | `cat.php`, `list_cat.php`, `change_cat.php`, `del_cat.php` | CRUD loại sản phẩm (cách tính giá `typeprice`, mẫu thông số `form`, điểm) |
| Nhóm | `group.php`, `list_group.php`, `change_group.php`, `del_group.php` | CRUD nhóm sản phẩm (thuộc tính: màu, size...) |
| Block sản phẩm | `blockcat.php`, `list_block_cat.php`, `chang_block_cat.php`, `del_block_cat.php`, `block.php`, `list_block.php`, `change_block.php` | nhóm sản phẩm thủ công và thứ tự trong nhóm |
| Mẫu thông số | `template.php`, `detemplate.php`, `fields.php`, `field_tab.php`, `tabs.php` | mẫu dữ liệu, trường tuỳ biến, gán trường vào tab, tab trang chi tiết (chỉ quản trị tối cao) |
| Đơn hàng | `order.php` | danh sách + lọc đơn |
| | `or_view.php` | xem đơn, lịch sử giao dịch, link in |
| | `active_pay.php` | xác nhận / huỷ xác nhận thanh toán thủ công, gửi mail (chỉ quản trị tối cao) |
| | `or_del.php` | xoá đơn, cộng trả kho |
| | `order_seller.php` | thống kê sản phẩm đã bán |
| | `print.php` | tệp chết: không có trong `$allow_func`, cập nhật cột không tồn tại |
| Giảm giá | `discounts.php` | chương trình giảm giá theo số lượng + thời hạn |
| | `coupons.php`, `coupons_view.php` | mã giảm giá và lịch sử dùng |
| Điểm | `point.php` | điểm tích luỹ của khách |
| Kho | `warehouse.php` | phiếu nhập kho (cộng `rows.product_number`, `group_quantity`) |
| | `warehouse_logs.php` | lịch sử nhập kho |
| Vận chuyển | `location.php`, `list_location.php`, `change_location.php`, `del_location.php` | địa điểm (cây) |
| | `carrier.php`, `carrier_config.php`, `carrier_config_items.php` | nhà vận chuyển, bảng giá theo vùng + khối lượng |
| | `shops.php` | cửa hàng/kho xuất và nhà vận chuyển áp dụng |
| | `shipping.php` | danh sách vận đơn |
| Danh mục phụ | `prounit.php`+`delunit.php`, `money.php`+`delmoney.php`, `weight.php`+`delweight.php` | đơn vị tính, tiền tệ, đơn vị khối lượng |
| Khác | `tags.php` | quản lý tag |
| | `review.php` | duyệt đánh giá |
| | `download.php` | tệp đính kèm |
| | `docpay.php` | nội dung hướng dẫn thanh toán + mẫu mail đơn hàng (lưu ra tệp `data/<lang>_<module_data>_*.txt`) |
| | `setting.php` | cấu hình module |
| | `change_source.php`, `del_source.php` | tệp chết (bảng `_sources` không tồn tại, không có trong `$allow_func`) |

Token chống giả mạo trong quản trị (kết quả tìm `checkss|NV_CHECK_SESSION|session_id()`): CÓ ở 21 tệp (`block, active_pay, carrier_config_items, carrier_config, coupons, carrier, del_cat, del_content, del_group, field_tab, exptime, items, discounts, order, order_seller, or_del, or_view, publtime, shops, tabs, warehouse`); 49 tệp còn lại KHÔNG có, trong đó có các tệp ghi dữ liệu: `cat, content, group, blockcat, change_*, del_block_cat, del_location, delmoney, delunit, delweight, detemplate, docpay, download, fields, location, money, prounit, review, setting, tags, template, updateprice, weight`. Token đang dùng lại thường là `md5(... . session_id())` chứ không phải `NV_CHECK_SESSION` như lõi.

## 4. Bảng trong `action_mysql.php` (44 bảng, tiền tố `<prefix>_<module_data>_`, đều `ENGINE=MyISAM`)

| Bảng (dòng) | Mục đích | Cột đáng chú ý |
|---|---|---|
| **Sản phẩm** | | |
| `rows` (313) | sản phẩm | `listcatid` (MỘT ngành hàng), `product_code`, `product_number` (tồn kho, int có dấu), `product_price`, `price_config` (serialize: bậc giá theo số lượng), `saleprice`, `money_unit`, `product_unit`, `product_weight`, `discount_id`, ảnh, `gift_from/to`, `num_sell`, `showprice`, `status`, `publtime/exptime`; cột theo ngôn ngữ `<lang>_title, _alias, _hometext, _bodytext, _gift_content, _address, _tag_title, _tag_description` |
| `catalogs` (151) | ngành hàng (cây) | `typeprice` (0 không giảm, 1 theo chương trình giảm giá, 2 theo từng sản phẩm/bậc số lượng — `admin/cat.php` dòng 381-385), `form` (mẫu thông số), `group_price`, `cat_allow_point`, `cat_number_point`, `cat_number_product` |
| `units` (408) | đơn vị tính | |
| `review` (369) | đánh giá | `product_id, userid, sender, content, rating, status` |
| `wishlist` (538) | yêu thích | một dòng mỗi user, `listid` dạng chuỗi id |
| `tags_<lang>` (545), `tags_id_<lang>` (557) | tag | |
| `files` (694), `files_rows` (708) | tệp đính kèm và liên kết với sản phẩm | |
| `tabs` (715) | tab trang chi tiết | |
| `block_cat` (382), `block` (401) | "block sản phẩm" (nhóm thủ công) và thành viên | |
| **Nhóm / thuộc tính** | | |
| `group` (229) | nhóm (cây: Màu > Đỏ...) | `indetail`, `in_order`, `is_require` |
| `group_cateid` (254) | nhóm áp cho ngành hàng nào | |
| `group_items` (260) | sản phẩm thuộc nhóm nào | |
| `group_quantity` (275) | **tồn kho theo tổ hợp nhóm** | `pro_id, listgroup` (id tăng dần, nối dấu phẩy), `quantity` |
| **Mẫu thông số** | | |
| `template` (187) | mẫu dữ liệu | `alias`, `<lang>_title` |
| `field` (199) | định nghĩa trường | `field`, `listtemplate`, `tab`, `field_type` (number, date, textbox, textarea, editor, select, radio, checkbox, multiselect), `field_choices`, `sql_choices`, `match_*`, `language` |
| `field_value_<lang>` (220) | giá trị trường theo sản phẩm (EAV) | `rows_id, field_id, field_value` |
| **Kho** | | |
| `warehouse` (282) | phiếu nhập | |
| `warehouse_logs` (291) | dòng phiếu nhập | `pro_id, quantity, price, money_unit` |
| `warehouse_logs_group` (302) | dòng phiếu nhập theo tổ hợp nhóm | |
| **Đơn hàng** | | |
| `orders` (415) | đơn | `order_code` (unique), thông tin người mua, `user_id`, `shop_id`, `unit_total`, `order_total`, `transaction_status` (-1 chờ duyệt, 0 chưa thanh toán, 1 đã gửi thanh toán, 2 đã trả chờ xử lý, 3 hoàn/huỷ, 4 đã thanh toán — `funcs/payment.php` dòng 170-184), `transaction_id`, `transaction_count`. KHÔNG có cột trạng thái giao hàng, không có cột tổng giảm giá/phí ship riêng |
| `orders_id` (444) | **dòng đơn** | `order_id, proid, num, price` (giá đã giảm, theo đơn vị hay cả dòng tuỳ chỗ ghi — xem mục 7), `discount_id`, `listgroupid` |
| `orders_id_group` (456) | nhóm đã chọn của dòng đơn | |
| `orders_shipping` (462) | thông tin giao hàng của đơn | `ship_location_id, ship_shops_id, ship_carrier_id, weight, ship_price` |
| `transaction` (481) | giao dịch thanh toán | `payment` (tên cổng, hiện chỉ `'wallet'`), `payment_id`, `payment_amount`, `payment_data` |
| **Khuyến mãi / điểm** | | |
| `discounts` (524) | chương trình giảm giá | `begin_time, end_time, config` (serialize: `discount_from, discount_to, discount_number, discount_unit` p hoặc tiền) |
| `coupons` (565), `coupons_product` (590), `coupons_history` (581) | mã giảm giá, sản phẩm áp dụng, lịch sử | `type` (p = %), `discount`, `total_amount` (đơn tối thiểu), `date_start/end`, `uses_per_coupon(_count)` |
| `point` (596), `point_queue` (602), `point_history` (608) | số điểm, điểm chờ cộng theo đơn, lịch sử | |
| **Vận chuyển** | | |
| `location` (617) | địa điểm (cây) | |
| `carrier` (630) | nhà vận chuyển | |
| `carrier_config` (642), `carrier_config_items` (651), `carrier_config_location` (661), `carrier_config_weight` (668) | bảng giá: cấu hình > mục > vùng áp dụng > bậc khối lượng/giá | |
| `shops` (676), `shops_carrier` (687) | cửa hàng (điểm xuất hàng) và nhà vận chuyển + bảng giá gắn với nó | `shops` chỉ có `name, location, address, description` — chưa có tồn kho theo cửa hàng |
| **Danh mục** | | |
| `money_<lang>` (497) | tiền tệ, tỉ giá, làm tròn, định dạng số (mặc định USD, VND) | |
| `weight_<lang>` (512) | đơn vị khối lượng (g, kg) | |

Không có bảng cổng thanh toán (`_payment`) — nhánh develop đã bỏ. Cấu hình module nằm trong bảng config chung của lõi (`NV_CONFIG_GLOBALTABLE`, dòng 729-787 + cấu hình bình luận 853-876).

## 5. Block sẵn có (`blocks/`)

| Tệp | `.ini` | Làm gì |
|---|---|---|
| `global.block_bxproduct_center.php` | có | slider sản phẩm (bxslider), chọn nguồn theo block sản phẩm, nhiều tuỳ chọn |
| `global.block_product_center.php` | có | danh sách/slider sản phẩm (mới, bán chạy...) — tên hàm `nv_global_product_center` |
| `global.block_relates_product.php` | có (tên trong ini ghi nhầm "Block RSS") | sản phẩm liên quan/mới theo loại |
| `global.block_catalogs.php` | có | cây ngành hàng |
| `global.block_price_view.php` | có (tên ini ghi nhầm "block_catalogs") | khối lọc/khoảng giá |
| `global.block_cart.php` | không | giỏ hàng mini (nạp qua `loadcart`) |
| `global.block_search.php` | không | form tìm kiếm sản phẩm; nạp `modules/shops/js/user.js` không tồn tại (dòng 55) |
| `module.block_filter_product.php` | có | lọc sản phẩm theo nhóm |
| `module.block_filter_product_cat.php` | có | lọc theo nhóm trong ngành hàng |
| `module.block_others_product.php` | không | sản phẩm khác cùng loại |
| `module.block_product_center.php` | không | sản phẩm nổi bật (tiny-slider) |

Template block: `themes/default/modules/shops/block.*.tpl`.

## 6. Cơ chế thanh toán hiện tại

- **[Mã]** Không còn cổng thanh toán tích hợp trong module. `funcs/payment.php`:
  - dòng 142-147: chỉ khi `isset($site_mods['wallet'])` và có `modules/wallet/wallet.class.php` thì mới sinh link thanh toán (`&payment=1&checksum=md5(order_id . sitekey)`).
  - dòng 299-351: tạo dòng `transaction` (`payment='wallet'`), đặt `orders.transaction_status=1`, gọi `nukeviet_wallet::getInfoPayment()` rồi chuyển hướng sang wallet.
  - dòng 229-297: nhận về (`worderid`, `wchecksum`), `nukeviet_wallet::getOrderPaid()`, cập nhật `transaction` + `orders`, trạng thái 4 thì `UpdatePoint()`.
  - `wallet.admin.php`: điểm móc phía quản trị của wallet, chưa cài đặt.
- **[Mã]** Không có wallet: đơn dừng ở `transaction_status` 0 (hoặc -1 nếu `auto_check_order=0`), hiển thị nội dung "hướng dẫn thanh toán" lấy từ tệp `data/<lang>_<module_data>_docpay_content.txt` (dòng 151-157). Quản trị xác nhận tay ở `admin/active_pay.php`. Đây chính là luồng COD hiện có.
- Hệ quả cho ticket 16-19: phải tự dựng giao kèo cổng thanh toán bên trong `modules/shops/`; bảng `transaction` dùng lại được (`payment`, `payment_id` varchar(22) — ngắn, `payment_amount`, `payment_data`); chưa có điểm nhận IPN/webhook nào (không có func nào không cần session).

## 7. Nơi tính giá hiện nay (phục vụ ticket 06)

Hàm trung tâm: `site.functions.php` dòng 117-190 `nv_get_price($pro_id, $currency_convert, $number = 1, $per_pro = false, $module = '')`:
- mỗi lần gọi là một truy vấn `SELECT ... FROM rows WHERE id=` (dòng 127) → N+1 trên trang danh sách;
- giá gốc = `product_price` × số lượng (trừ khi `$per_pro`), làm tròn theo `money_<lang>.round`;
- nhánh theo `catalogs.typeprice` của ngành hàng: `2` → bậc giá `price_config` theo số lượng (dòng 138-147); `1` → chương trình `discounts` còn hạn (`begin_time < now` và `end_time > now` hoặc `end_time = 0`), theo khoảng số lượng, % hoặc số tiền (dòng 148-166); `0` + cấu hình `saleprice_active` → `saleprice` (dòng 167-173);
- quy đổi tiền tệ rồi trả mảng `price, price_format, discount, discount_format, discount_percent, discount_unit, sale, sale_format, unit`;
- phụ thuộc biến toàn cục `$global_array_shops_cat, $pro_config, $money_config, $discounts_config`; dòng 126 ghi đè `global $module_data` khi truyền `$module`.

Nơi gọi / tính lại (đây là danh sách cần gom):

| Nơi | Dòng | Ghi chú |
|---|---|---|
| `theme.php` — chi tiết | 231, 467-485 (bảng bậc giá tự quy đổi) | **tính giá ngay trong hàm dựng template** |
| `theme.php` — in sản phẩm | 568 | |
| `theme.php` — giỏ hàng `cart_product()` | 632/634 | hai cách gọi (`$per_pro` true/false) tuỳ nhánh |
| `theme.php` — xác nhận đơn `users_order()` | 807/809 | như trên |
| `theme.php` — so sánh | 1497 | |
| `theme.php` — lưới / danh sách | 1719, 1847 | |
| `theme.php` — giỏ mini | 2141 | |
| `theme.php` — trang đơn/in/mail | 949-950, 1103-1104, 1401 | KHÔNG gọi `nv_get_price`, tự nhân `product_price × product_number` từ dữ liệu được truyền vào |
| `funcs/loadcart.php` | 58 | tổng giỏ |
| `funcs/order.php` | 121 (tổng, theo cả dòng), 341 (ghi `orders_id.price`, **theo đơn vị** `$per_pro = true`) | tổng đơn và giá dòng đơn tính bằng hai lời gọi khác nhau |
| `funcs/payment.php` | 123 | **tính lại giá hiện tại** khi xem đơn thay vì đọc `orders_id.price` → giá hiển thị của đơn cũ đổi theo giá mới/khuyến mãi hết hạn |
| `funcs/setcart.php` | 44 | lưu `product_price` thô vào session (không dùng lại để tính) |
| `funcs/cart.php` | 213, 225 | truyền `product_price` thô cho theme |
| `blocks/global.block_bxproduct_center.php` | 137-154, 313 | hàm bọc `nv_get_price_tmp()` nạp lại config/discount cho module ảo |
| `blocks/global.block_product_center.php` | 81-89, 235 | bản sao thứ hai của `nv_get_price_tmp()` |
| `blocks/global.block_relates_product.php` | 82-90, 243 | bản sao thứ ba |
| `blocks/module.block_others_product.php` | 73 | |
| `blocks/module.block_product_center.php` | 99 | |
| `admin/getprice.php`, `admin/updateprice.php`, `admin/order.php` dòng 94, `admin/warehouse_logs.php` dòng 185 | | tự làm tròn/định dạng/quy đổi riêng |
| Sắp xếp và lọc theo giá | `funcs/main.php` 50-52, `viewcat.php` 128-130, `search.php` 65, 111-113, 137-142, block 192-194 | dùng thẳng cột `product_price` trong SQL → **không phản ánh giá sau giảm** |

Giảm giá cấp đơn (coupon) nằm riêng: `funcs/order.php` dòng 171-188 và `funcs/loadcart.php` (phần đầu); nhánh coupon số tiền cho sản phẩm chỉ định (dòng 176-177) gán `$total = $total_coupons - discount`, làm mất phần còn lại của đơn và phí vận chuyển — lỗi. Điểm tích luỹ: `order.php` dòng 130-139, 148, 407-417. Phí vận chuyển: `nv_shipping_price()`.

Các điểm phải giữ nguyên hành vi khi gom: quy tắc làm tròn theo tiền tệ, ba nhánh `typeprice`, điều kiện thời hạn, khác biệt "giá cả dòng" và "giá đơn vị" (`$per_pro`), `showprice` + `active_price` quyết định có hiện giá.

## 8. Nơi trừ / cộng tồn kho

- Hàm: `global.functions.php` dòng 318-337 `product_number_order($listid, $listnum, $listgroup, $type = '-')` — `UPDATE rows SET product_number = product_number ± n` và, nếu có tổ hợp nhóm, `UPDATE group_quantity SET quantity = quantity ± n`. Không kiểm tra đủ hàng, không khoá, không transaction.
- Đặt hàng: `funcs/order.php` dòng 381-384 (trừ khi `active_order_number == '0'`), ngay sau khi ghi các dòng `orders_id`; `product_number_sell()` dòng 387 cộng `num_sell`.
- Kiểm tra tồn duy nhất trước đó: lúc thêm vào giỏ, `funcs/setcart.php` dòng 36, 53, 72 (so với `rows.product_number`, không xét `group_quantity`). Lúc bấm đặt hàng KHÔNG kiểm tra lại.
- Cộng trả: sửa đơn `funcs/order.php` dòng 319 (lỗi tham số — thực tế lại trừ); khách xoá đơn `funcs/delhis.php` dòng 51; quản trị xoá đơn `admin/or_del.php` dòng 43, 105; tự xoá đơn quá hạn `global.functions.php` dòng 58 (lỗi tham số + dùng sai id).
- Nhập kho: `admin/warehouse.php` dòng 73-88 (cộng `rows.product_number`, ghi `warehouse_logs`, `warehouse_logs_group`, và `group_quantity`).
- Không có khái niệm giữ hàng, không có tồn theo cửa hàng (bảng `shops` chỉ phục vụ tính phí vận chuyển).

## Chưa xác minh

- Mô tả một dòng của các tệp chưa đọc kỹ (phần lớn `admin/*`) dựa trên phần đầu tệp và tên; cần đọc lại khi sửa.
- Ý nghĩa chính xác của `orders_id.price` (đơn vị hay cả dòng) ở mọi nơi đọc nó chưa đối chiếu hết; `funcs/order.php` dòng 341-353 ghi giá đơn vị.
