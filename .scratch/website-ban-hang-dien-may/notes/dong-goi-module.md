# Đóng gói / xuất module trên NukeViet 4.6.03

Nguồn đọc: `E:\Dev\www\nukeviet_banhang\.work\nvzip\nukeviet\` (viết tắt `NV/`). Ký hiệu: **[Mã]** = đọc thấy trong mã, **[Suy luận]** = suy ra, chưa chạy thử.

## 1. Chức năng nằm ở đâu

- **[Mã]** Đóng gói module: `NV/admin/extensions/manage.php` dòng 40-327 (chú thích gốc: "Package extensions (Odd feature: Package module, theme)"). Trang: Quản trị > Mở rộng > Quản lý (`nv=extensions&op=manage`), mỗi dòng có link `url_package` (dòng 667) dạng `...&op=manage&type=module&title=shops&checksess=md5('package_module_shops_' . NV_CHECK_SESSION)`. Là yêu cầu GET, trả thẳng tệp `nv4_module_shops.zip` (dòng 319-321).
- **[Mã]** Chức năng khác, KHÔNG phải cái ADR-0003 nói tới: `NV/admin/themes/package_theme_module.php` — chỉ gom phần giao diện của module trong một theme (`themes/<theme>/modules/<mod>/`, `css/<mod>.css`, `js/<mod>*.js`, `images/<mod>/`), không có `modules/`, không có `config.ini` → không cài lại được như một module.
- **[Mã]** Danh sách ở trang manage lấy từ bảng `<prefix>_setup_extensions WHERE title=basename` (dòng 637). Module chép tay vào `modules/` chỉ có dòng trong bảng này sau khi mở trang Quản lý module > Thiết lập module mới (`NV/admin/modules/setup.php` dòng 131-187 tự INSERT, `version = <version trong version.php> + ' ' + strtotime(date)`, `id` không truyền nên = 0 theo mặc định cột — schema ở `NV/install/action_mysql.php`).

## 2. Gom CHÍNH XÁC những gì (type = module) — `manage.php` dòng 94-152

| Thứ được gom | Điều kiện | Dòng |
|---|---|---|
| `modules/<tên>/` (cả thư mục, đệ quy) | tồn tại | 96-97 |
| `themes/<T>/modules/<tên>/` | `T = default` nếu `themes/default/modules/<tên>` tồn tại; nếu không thì `T = $global_config['site_theme']`. **Chỉ MỘT theme.** | 110-118 |
| `themes/<T>/css/<tên>.css` | đúng một tệp, đúng tên | 120-122 |
| `themes/<T>/js/<tên>*.js` | glob theo tiền tố tên module | 124-127 |
| `themes/<T>/images/<tên>/` | cả thư mục | 129-131 |
| `themes/admin_default/css/<tên>.css` | | 136-138 |
| `themes/admin_default/js/<tên>*.js` | glob | 140-143 |
| `themes/admin_default/images/<tên>/` | | 145-147 |
| `themes/admin_default/modules/<tên>/` | | 149-151 |
| Mọi tệp có trong bảng `<prefix>_extension_files` (type=module, title=<tên>) mà chưa nằm trong các thư mục trên và còn tồn tại | | 86-91, 258-273 |
| `config.ini` ở gốc zip, sinh tự động | `[extension] id,type,name,version` `[author] name,email` `[note] text` | 297-314 |

KHÔNG được gom (trừ khi có trong `_extension_files`):
- `assets/<tên>/`, `uploads/<tên>/` (ảnh mẫu, tệp tải lên).
- `themes/mobile_default/...` và mọi theme khác ngoài theme `T` ở trên (kể cả theme riêng của site nếu `themes/default/modules/<tên>` tồn tại).
- Tệp css/js trong theme không mang đúng tên module: vd `themes/default/css/jquery.bxslider.css`, `themes/default/js/jquery.bxslider.min.js`, `themes/admin_default/js/content.js` của shops gốc.
- `includes/cronjobs/*`, `includes/plugin/*`, `vendor/*`, tệp ngôn ngữ ngoài `modules/<tên>/language/`.
- Block nằm ở `themes/<theme>/blocks/` (block của theme). Block của module nằm trong `modules/<tên>/blocks/` nên đi theo thư mục module.
- Dữ liệu CSDL, cấu hình đang chạy: không có gì được xuất; site đích tạo lại bằng `action_mysql.php`.

Bảng `_extension_files` chỉ được ghi khi cài bằng cách **tải gói zip lên** (`NV/admin/extensions/upload.php` dòng 345-363): mọi tệp trong zip được ghi lại. Cài bằng chép tay thì bảng này rỗng với module đó.

## 3. Trường version và các bẫy khi xuất

- **[Mã]** `manage.php` dòng 76-80: `version` trong `_setup_extensions` phải khớp `/^([0-9\.]+) ([0-9]+)$/`, nếu không gói mang `version="N/A"`.
- **[Mã]** Khi cài lại, `upload.php` dòng 593 đòi `version` khớp `$global_config['check_version'] = '/^([0-9]{1})\.([0-9]{1})\.([0-9]{2})$/'` (`NV/includes/constants.php` dòng 258) → version module phải dạng `4.6.00` (1 chữ số . 1 chữ số . 2 chữ số). `4.6.0` hay `4.10.00` đều bị từ chối.
- **[Mã]** Email tác giả phải hợp lệ (`functions.php` dòng 234). `manage.php` dòng 68-73 tách email từ cột `author` dạng `Tên <email>` hoặc `Tên (email)`; không tách được thì ghi `N/A` → gói KHÔNG cài lại được. `version.php` của shops có `'author' => 'VINADES <contact@vinades.vn>'` nên qua.
- **[Mã]** `strtotime()` với chuỗi ngày kiểu `'Wednesday, August 19, 2026 at 8:38:36 AM UTC+07:00'` trả `false` (đã thử bằng php CLI 8.5 trên chuỗi tự gõ) → `setup.php` dòng 162-166 dùng `NV_CURRENTTIME` thay thế, không gây lỗi.

## 4. Gói cài lại theo cơ chế nào — `NV/admin/extensions/upload.php`

1. Điều kiện được tải lên: `$global_config['extension_setup']` = 1 hoặc 3 **và** IP máy khách nằm trong `$global_config['extension_setup_ips']` (dòng 469; `manage.php` dòng 572-583). Bản cài mới ghi `extension_setup = 3`, `extension_setup_ips = []` (`NV/install/index.php` dòng 1350-1351) → **mặc định danh sách IP rỗng, phải thêm IP (trong Docker là IP của gateway/proxy mà PHP nhìn thấy) thì mới tải gói lên được**. Token: `checksess = md5(NV_CHECK_SESSION . 'submit-ext')` (dòng 476).
2. Kiểm tra zip (dòng 531-662):
   - Tổng dung lượng giải nén ≤ `NV_UPLOAD_MAX_FILESIZE` và ≤ 10 lần kích thước zip (dòng 539-548). `NV_UPLOAD_MAX_FILESIZE = min(upload_max_filesize, post_max_size, nv_max_size)` (`NV/includes/core/admin_functions.php` dòng 138-141). **Shops gốc đã ~2,23 MB chưa nén** (đo trên bản clone: `modules/shops` 1.319.469 B + `themes/default/modules/shops` 114.837 + `themes/default/images/shops` 451.164 + `themes/admin_default/modules/shops` 265.118 + css/js) → vượt mức 2M mặc định của PHP. Phải nâng `upload_max_filesize`/`post_max_size` trong php.ini của cả hai site và cấu hình `nv_max_size`; về sau thêm ảnh/JS thì càng phải để dư.
   - Phải có `config.ini` ở gốc zip (dòng 551-562), `type` thuộc `module|block|theme|cron`, tên khớp `check_module = /^[a-zA-Z0-9\-]+$/`.
   - `check_structure()` (`NV/admin/extensions/functions.php` dòng 284-340): ở gốc chỉ được có `config.ini`; thư mục gốc cho phép: `assets`, `themes`, `modules`, `uploads`, `includes/plugin`, `vendor`; tệp phải nằm sâu ít nhất 2 cấp; trong `modules/` chỉ được có thư mục đúng tên module; trong `assets`/`uploads` cấm đuôi `php, php3, php4, php5, phtml, inc`; trong `includes/` chỉ chấp nhận `includes/plugin` → **`includes/cronjobs/...` bị từ chối**.
   - Kiểm tra MIME từng tệp không phải `.php/.tpl` theo `includes/ini/mime.ini` (dòng 173-241); tệp lạ (vd `.map`, `LICENSE` không đuôi) có thể bị báo, quản trị phải bấm "bỏ qua" (`dismiss`). **[Suy luận]** shops gốc có `themes/default/images/shops/OwlCarousel2/LICENSE` và `tiny-slider/sourcemaps/*.map` — có nguy cơ dính bước này; cần thử thật ở ticket 05.
3. Giải nén vào thư mục tạm, xoá mọi `.htaccess`, đọc `is_sysmod`/`virtual` từ `modules/<tên>/version.php` bằng regex (dòng 121-142 — hai khoá này phải viết dạng `'is_sysmod' => 0,` / `'virtual' => 1,`), rồi **chuyển từng tệp đè vào gốc site** (dòng 281-318), ghi `_setup_extensions` (nếu chưa có) và `_extension_files`.
4. Sau đó chuyển tới `nv=modules&op=setup` (dòng 431-432). Quản trị bấm cài: `NV/admin/modules/setup.php` dòng 27-97 → `nv_setup_data_module()` (`NV/admin/modules/functions.php` dòng 150-387): xoá config cũ của module, `include modules/<tên>/action_mysql.php` rồi chạy `$sql_create_module`, đăng ký mọi tệp `funcs/*.php` vào `_modfuncs` (những func có trong `modfuncs` của `version.php` được `show_func=1`), gán layout, tạo `uploads_dir`/`files_dir`, nạp dữ liệu mẫu `language/data_<lang>.php` nếu chọn; cuối cùng `nv_setup_block_module()` và `act=1`.

Gỡ module: `NV/admin/modules/del.php` dòng 29-57 include `action_mysql.php` với `NV_MODULE_DELETE`, chạy `$sql_drop_module`; xoá block, modfuncs, config theo ngôn ngữ, thư mục `uploads/<tên>`, `assets/<tên>`. **Không đụng bảng cron, không đụng bảng plugin** → thứ gì module tự đăng ký ngoài các bảng đó phải tự dọn trong `$sql_drop_module`. Xoá tệp là việc riêng ở Mở rộng > Quản lý (`manage.php` dòng 330-539).

## 5. Hệ quả: tệp mới của shops đặt ở đâu

| Loại tệp | Đặt ở | Lý do |
|---|---|---|
| Mã nghiệp vụ, func, admin, ngôn ngữ, `action_mysql.php` | `modules/shops/...` | gom nguyên thư mục |
| Lớp PSR-4 | `modules/shops/<Thư mục>/<Lớp>.php`, namespace `NukeViet\Module\shops\<Thư mục>` | autoload sẵn có `NukeViet\Module\ => modules/` (`NV/vendor/composer/autoload_psr4.php`), mẫu: `modules/news/Shared/Logs.php` |
| Cổng thanh toán | `modules/shops/<thư mục riêng>/` (lớp PSR-4 hoặc tệp include) | không dựa vào module `wallet` hay `vendor/` |
| Block | `modules/shops/blocks/(global|module).<tên>.php` + `.ini` cùng tên | đi theo thư mục module |
| Template ngoài site | `themes/default/modules/shops/*.tpl` | theme duy nhất được gom |
| CSS ngoài site | gộp vào `themes/default/css/shops.css` (một tệp duy nhất) | chỉ gom đúng `<tên>.css` |
| JS ngoài site | `themes/default/js/shops*.js` (vd `shops.js`, `shops.checkout.js`, `shops-slider.js`) | glob `shops*.js` |
| Ảnh, thư viện bên thứ ba (css/js/font) ngoài site | `themes/default/images/shops/...` | cả thư mục được gom; shops gốc đã để tiny-slider, OwlCarousel ở đây |
| Template/CSS/JS/ảnh quản trị | `themes/admin_default/modules/shops/`, `css/shops.css`, `js/shops*.js`, `images/shops/` | như trên |
| Tác vụ định kỳ | KHÔNG dùng `includes/cronjobs/` (không gom, và bị `check_structure` từ chối). Dùng cách của chính shops gốc: chạy theo mốc thời gian lưu trong config khi module được nạp (`modules/shops/global.functions.php` dòng 40-75), và/hoặc một func riêng trong `modules/shops/funcs/` để cron hệ điều hành gọi qua HTTP | xem `quy-uoc-nukeviet-4-6.md` mục cron |
| Ảnh mặc định (no-image...) | `themes/default/images/shops/` chứ không phải `assets/` hay `uploads/` | hai thư mục kia không được gom |
| Dữ liệu mẫu | ngoài gói (ADR-0003) | |

Việc phải xử lý với shops gốc để gói tự đủ (**[Mã]** các tham chiếu, **[Suy luận]** hệ quả trên site sạch):
- `themes/default/css/jquery.bxslider.css` + `themes/default/js/jquery.bxslider.min.js` được `block.bxproduct_center.tpl` dòng 3-4 nạp qua `LIB_PATH = themes/default/` (`blocks/global.block_bxproduct_center.php` dòng 264) → không được gom → chuyển vào `themes/default/images/shops/` hoặc đổi tên `shops*.js` và gộp css.
- `themes/admin_default/js/content.js` được `themes/admin_default/modules/shops/content.tpl` dòng 468 nạp → không được gom (tên không bắt đầu bằng `shops`) → đổi tên thành `shops_content.js` (lõi làm vậy với `news_content.js`).
- `carrier_config_items.tpl` dòng 132 nạp `modules/shops/js/content.js`, `blocks/global.block_search.php` dòng 55 nạp `modules/shops/js/user.js` — hai tệp này không tồn tại trong repo.
- Nếu site chính dùng theme riêng thì gói vẫn lấy `themes/default/...` (vì `themes/default/modules/shops` tồn tại) — đúng với ADR-0003 "module phải chạy đúng trên theme mặc định"; phần ghi đè trong theme riêng không đi theo gói shops.

Mẹo có thể dùng (**[Suy luận]**, chưa thử): nếu lần đầu cài shops gốc bằng tải zip lên (thay vì chép tay) thì mọi tệp trong zip được ghi vào `_extension_files` và sẽ được xuất lại kể cả tệp ngoài đường dẫn chuẩn; nhưng tệp thêm mới về sau vẫn không tự vào bảng đó, nên không nên dựa vào cơ chế này — cứ đặt tệp đúng đường dẫn chuẩn. Lưu ý zip của repo gốc không tải lên trực tiếp được vì ở gốc có `README-SHOPS.md`, `CHANGELOG-SHOPS.txt`, `CONTRIBUTING-SHOPS.md`, `.gitignore` (gốc zip chỉ được có `config.ini`).

## Chưa xác minh

- Chưa chạy thật việc xuất rồi cài lại; bước kiểm tra MIME và giới hạn dung lượng cần thử ở ticket 05.
- `site/` đang dùng không được đọc theo yêu cầu, nên chưa biết giá trị thật của `extension_setup_ips`, `nv_max_size`, `upload_max_filesize` trong môi trường Docker.
