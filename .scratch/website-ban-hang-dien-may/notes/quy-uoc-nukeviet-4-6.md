# Quy ước viết module trên NukeViet 4.6.03

NV = `E:\Dev\www\nukeviet_banhang\.work\nvzip\nukeviet`. Mọi mục đều đọc từ mã lõi, trừ chỗ ghi [suy luận]. Module mẫu để bắt chước: `NV\modules\news`, `NV\modules\page`.

## 1. Bộ khung một module

`modules/<ten>/`: `version.php`, `action_mysql.php` (`$sql_create_module`, `$sql_drop_module`), `functions.php` (ngoài site), `admin.functions.php` (`$allow_func`), `admin.menu.php`, `global.functions.php`, `theme.php`, `funcs/<op>.php`, `admin/<op>.php`, `blocks/`, `language/<lang>.php` + `admin_<lang>.php`, tuỳ chọn `mobile/<op>.php`, `comment.php`, `menu.php`, `notification.php`, `rssdata.php`, `search.php`, `siteinfo.php`.

- Điều phối ngoài site: `NV\index.php` dòng 150-345 — nạp ngôn ngữ (thiếu thì rơi về `en`), chọn theme, `$module_info['template']` rơi về `default` nếu theme hiện tại không có thư mục module (dòng 312-318), nạp `functions.php`, rồi `theme.php` (ưu tiên bản ghi đè `themes/<theme>/modules/<mod>/theme.php`, dòng 329-333), rồi `funcs/<op>.php` (hoặc `mobile/<op>.php`). `$op` phải có trong bảng `_modfuncs` (sinh từ `version.php` khi cài); phần còn lại của URL nằm trong `$array_op`.
- Mỗi tệp mở đầu bằng guard hằng số: `NV_IS_MOD_<TEN>` (site), `NV_IS_FILE_ADMIN` (quản trị), `NV_MAINFILE` (dùng chung), `NV_IS_FILE_MODULES` (`action_mysql.php`).
- Regex tên hợp lệ (module, func, block, theme...): `NV\includes\constants.php` dòng 245-257. PHP tối thiểu 7.4.
- Biến hay dùng: `$module_name` (tên trên site, có thể là module ảo), `$module_file` (thư mục mã), `$module_data` (tiền tố bảng), `$module_upload`, `$module_info`, `$module_config[$module_name]`, `$site_mods`, `$global_config`, `$admin_info`, `$user_info`, `$client_info`. Bảng: `$db_config['prefix'] . '_' . $module_data . '_x'` (thêm `NV_LANG_DATA` nếu theo ngôn ngữ).

## 2. Lấy dữ liệu vào: `$nv_Request`

`NV\vendor\vinades\nukeviet\Core\Request.php`. Không đọc `$_GET/$_POST` trực tiếp.

| Hàm | Dòng | Ghi chú |
|---|---|---|
| `get_int($name, $mode, $default)` | 1595 | `get_absint` 1612, `get_float` 1627, `get_bool` 1580 |
| `get_string` | 1642 | chuỗi thô đã lọc |
| `get_title($name, $mode, $default, $specialchars)` | 1688 | một dòng, bỏ thẻ |
| `get_textarea($name, $default, $allowed_tags, $save)` | 1765 | chỉ POST |
| `get_editor($name, $default, $allowed_tags)` | 1722 | nội dung trình soạn thảo |
| `get_array` / `get_typed_array($name, $mode, $type, $default)` | 1782 / 1801 | mảng có ép kiểu |
| `isset_request($names, $mode, $all)` | 1508 | |
| `set_Session`, `set_Cookie`, `unset_request` | 1399, 1354, 1421 | |

`$mode` là chuỗi `'post'`, `'get'`, `'post,get'`, `'session'`, `'cookie'`, `'request'`.

## 3. CSDL: `$db`

`NV\vendor\vinades\nukeviet\Core\Database.php` (kế thừa PDO; ERRMODE_EXCEPTION, không giả lập prepare, FETCH_ASSOC).

- Có tham số ngoài: `$sth = $db->prepare('... WHERE alias = :alias'); $sth->bindParam(':alias', $alias, PDO::PARAM_STR); $sth->execute();` (dòng 582).
- Số nguyên đã ép kiểu thì nối thẳng vào `$db->query()` (546) / `$db->exec()` (565); chuỗi bắt buộc `$db->quote()` hoặc bind.
- Thêm và lấy id: `$db->insert_id($sql, 'id', $data_insert)` (dòng 107) với `$data_insert['key']` ứng `:key`.
- Trình dựng truy vấn: `$db->sqlreset()->select()->from()->join()->where()->group()->having()->order()->limit()->offset()`, lấy chuỗi bằng `->sql()` (dòng 356-493). Mẫu phân trang: đếm bằng `select('COUNT(*)')` rồi đổi `select` + `limit/offset`.
- LIKE: `$db->dblikeescape()` (315). Thêm cột theo ngôn ngữ: `columns_add` (214).
- Cache: `$nv_Cache->db($sql, $key_field, $module_name)`; sau khi ghi gọi `$nv_Cache->delMod($module_name)`.
- Nhật ký quản trị: `nv_insert_logs(NV_LANG_DATA, $module_name, $title, $note, $admin_info['userid'])`.

## 4. Chống giả mạo yêu cầu (checkss)

- `NV_CHECK_SESSION = md5(NV_CACHE_PREFIX . $nv_Request->session_id)` — `NV\includes\mainfile.php` dòng 159.
- Thao tác trên một bản ghi: token `md5($id . NV_CHECK_SESSION)`, so với `$nv_Request->get_string('checkss', 'post')` — xem `NV\modules\page\admin\del.php` dòng 17-19, `NV\modules\news\admin\del_content.php` dòng 17-23.
- Form: `md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid'])` — `NV\admin\themes\package_theme_module.php` dòng 16; gán `{CHECKSS}` vào input ẩn / tham số ajax.
- Thao tác ghi chỉ nhận POST. Trả ajax bằng `nv_jsonOutput()` / `nv_htmlOutput()`.
- Shops hiện dùng `md5($id . session_id())` và 49/70 tệp quản trị không có token → khi sửa tệp nào thì đổi sang mẫu trên.

## 5. Ngôn ngữ

- 4.6 dùng MẢNG: `$lang_module['key']` (từ `modules/<mod>/language/<lang>.php` hoặc `admin_<lang>.php`), `$lang_global`, `$lang_block` trong block. Tệp ngôn ngữ gán `$lang_module['x'] = '...'` và có `$lang_translator`.
- Lớp `$nv_Lang` (`\NukeViet\Core\Language`) là của NukeViet 5 — KHÔNG có trong 4.6.03 (tìm `nv_Lang` trong NV: 0 kết quả). Không dùng.
- Trong template: `$xtpl->assign('LANG', $lang_module); $xtpl->assign('GLANG', $lang_global);` → `{LANG.key}`.
- Shops hiện có `vi` (và các tệp ngôn ngữ kèm repo); chuỗi mới phải thêm vào mọi tệp ngôn ngữ đang có để không ra khoá trống.

## 6. Giao diện: XTemplate

- Không có Smarty trong 4.6 (0 kết quả). Bootstrap 3 + jQuery.
- Site: `new XTemplate('x.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);` Quản trị: thay bằng `$global_config['module_theme']`.
- Cú pháp tpl: `<!-- BEGIN: main -->...<!-- END: main -->`, biến `{TEN}` / `{ROW.field}`; PHP: `assign()`, `parse('main.loop')`, `text('main')`.
- Xuất trang: site `include NV_ROOTDIR . '/includes/header.php'; echo nv_site_theme($contents); include NV_ROOTDIR . '/includes/footer.php';` — quản trị `nv_admin_theme($contents)`. Đặt `$page_title`, `$key_words`, `$description`.
- Mọi func site nên đặt `$page_url` + `$canonicalUrl = getCanonicalUrl($page_url)` (`NV\includes\functions.php` dòng 1871-1950): hàm này CHUYỂN HƯỚNG khi URL đang truy cập không khớp `$page_url` → `$page_url` phải chứa đủ tham số bắt buộc (đây là nghi phạm của lỗi in đơn và tìm kiếm trong shops).
- Tiện ích: `nv_generate_page()` (1648), `nv_sendmail()` (1310), `nv_insert_notification()` (2655), `nv_aleditor()` (trình soạn thảo, CKEditor 5), `nv_redirect_location()`, `nv_url_rewrite()`.
- Tệp tĩnh riêng của module đặt ở `themes/<theme>/css/<mod>.css`, `themes/<theme>/js/<mod>*.js`, `themes/<theme>/images/<mod>/` — lõi tự nạp css/js trùng tên module, và đây cũng là các đường dẫn duy nhất bộ đóng gói thu (xem `dong-goi-module.md`).

## 7. Cronjob

- Lõi: `NV\includes\core\cronjobs.php` (nạp ở `mainfile.php` dòng 368), bảng `NV_CRONJOBS_GLOBALTABLE` (`start_time, inter_val, inter_val_type, run_file, run_func, params, del, is_sys, act, last_time, last_result, <lang>_cron_name`).
- `run_file` bắt buộc nằm trong `includes/cronjobs/` (tên khớp `/^([a-zA-Z0-9\-\_\.]+)\.php$/`), `run_func` phải tồn tại, trả `false` thì cron bị tắt. Thêm qua giao diện `NV\admin\settings\cronjobs_add.php`.
- KHÔNG có cách chính thức cho module tự đăng ký cron: gói module không được chứa `includes/cronjobs` (không được thu khi đóng gói và bị `check_structure` từ chối khi cài), gỡ module cũng không dọn dòng cron.
- Cách phù hợp ADR 0003 [suy luận từ các ràng buộc trên]: (a) "cron giả" trong module như shops đang làm — `global.functions.php` dòng 40-75, chạy theo mốc thời gian lưu trong config mỗi khi có lượt truy cập; hoặc (b) một func site của module (có khoá bí mật) để cron hệ điều hành gọi. Việc cần đúng giờ (hết hạn giữ hàng, đối soát thanh toán) nên dùng (b), kèm (a) làm dự phòng.

## 8. Hook / plugin

- 4.6 không có hệ hook (`nv_apply_hook`, `nv_add_hook`: 0 kết quả; đó là của NukeViet 5).
- Chỉ có plugin toàn cục: `includes/plugin/<ten>.php` + `.ini`, đăng ký trong bảng `_plugin` (`plugin_file, plugin_area, weight`) qua `NV\admin\settings\plugin.php`; chạy ở 3 vị trí: `mainfile.php` dòng 260 (trước kết nối CSDL), 465 (sau khi khởi tạo), `footer.php` dòng 35 (trước khi xuất HTML).
- `includes/plugin` là thư mục gốc hợp lệ của gói ứng dụng nhưng bộ đóng gói module không tự thu → muốn kèm phải có dòng trong `_extension_files`. Nên tránh phụ thuộc plugin cho shops.
- Điểm móc giữa các module là các tệp quy ước (`search.php`, `menu.php`, `comment.php`, `rssdata.php`, `siteinfo.php`, `notification.php`) và việc kiểm tra `isset($site_mods['ten'])` rồi `require` tệp của module kia (cách shops gọi `wallet`).

## 9. Autoload PSR-4

- `NV\vendor\composer\autoload_psr4.php`: `NukeViet\Module\` => `NV_ROOTDIR/modules`. Lớp `NukeViet\Module\shops\Pricing\Calculator` nằm ở `modules/shops/Pricing/Calculator.php` — phân biệt hoa thường theo đúng tên thư mục module (`shops` viết thường) vì máy chủ Linux.
- Ví dụ có sẵn: `NV\modules\news\Shared\Logs.php` (`namespace NukeViet\Module\news\Shared;`).
- Hệ quả: lớp nghiệp vụ mới (tính giá cho ticket 06, cổng thanh toán) đặt ngay trong `modules/shops/<Thu_muc>/` — vừa tự nạp, vừa được đóng gói, không cần sửa composer. Lưu ý module ảo: namespace theo `$module_file`, không theo `$module_name`.

## 10. Đăng nhập OAuth của module users

- `NV\modules\users\funcs\oauth.php` dòng 51-59: với `server` thuộc `$global_config['openid_servers']`, nạp `modules/users/login/oauth-<server>.php` hoặc `cas-<server>.php`. Có sẵn: `oauth-facebook`, `oauth-google`, `oauth-zalo`, `cas-single-sign-on`.
- Các tệp này dùng lớp `NukeViet\OAuth\OAuth2\*` (league/oauth2-client trong vendor), lưu `oauth2state` vào session, trả thông tin vào session `openid_attribs`, rồi chuyển về trang đăng nhập / `editinfo/openid` để ghép tài khoản. Khoá ứng dụng cấu hình trong quản trị users.
- Đăng nhập quản trị riêng: `NV\includes\core\admin_login_{facebook,google,zalo}.php`.
- Theo ADR 0003, cải tiến đăng nhập không thuộc shops: thêm nhà cung cấp là thêm tệp trong `modules/users/login/` (tức sửa lõi / module users), không nằm trong gói shops.

## 11. Block và cấu hình block

- Tệp: `modules/<mod>/blocks/global.<ten>.php` (đặt được ở mọi module) hoặc `module.<ten>.php` (chỉ trong chính module) — regex ở `constants.php` dòng 249. Block của theme: `themes/<theme>/blocks/`.
- Tệp block: guard `NV_MAINFILE`, bọc hàm trong `if (!nv_function_exists('ten_ham'))`, cuối tệp gán `$content = ten_ham($block_config);` (trong `if (defined('NV_SYSTEM'))`). `$block_config` chứa cấu hình + `module`, `bid`, `block_name`.
- Cấu hình: tệp `.ini` CÙNG TÊN, thực chất là XML:

```xml
<?xml version="1.0" encoding="utf-8"?>
<block>
    <info><name>...</name><author>...</author><website>...</website><description>...</description></info>
    <config><numrow>10</numrow></config>
    <datafunction>nv_block_config_xxx</datafunction>
    <submitfunction>nv_block_config_xxx_submit</submitfunction>
</block>
```

- `datafunction($module, $data_block, $lang_block)` trả HTML form (input tên `config_<khoa>`); `submitfunction($module, $lang_block)` trả `['error' => [], 'config' => [...]]` đọc bằng `$nv_Request`. Chuỗi cho form lấy từ tệp ngôn ngữ block `modules/<mod>/language/block.<ten file>_<lang>.php` nếu có, không thì dùng `$lang_module`. Xem các block có ini của shops và `NV\modules\news\blocks\`.
- Block global chạy ngoài ngữ cảnh module: phải tự nạp config/hàm của module (lý do shops có 3 bản sao `nv_get_price_tmp()`); template tìm ở theme hiện tại, không có thì về `default`.

## 12. Cài / gỡ

- `action_mysql.php` chạy khi cài, cài lại và gỡ: `$sql_drop_module` luôn chạy trước; `$sql_create_module` chỉ khi cài. Gỡ module (`NV\admin\modules\del.php`) chạy `$sql_drop_module`, xoá config, modfuncs, block, thư mục upload của module — nhưng không xoá cron, plugin, tệp do module tự ghi vào `data/`. Bảng mới thêm cho shops phải có mặt trong `$sql_drop_module` (ticket 05: gỡ không để lại bảng).
- Bảng theo ngôn ngữ và theo site: xử lý trong vòng lặp ngôn ngữ của `action_mysql.php` (xem cách shops tạo `money_<lang>`, cột `<lang>_title`).

## Chưa xác minh

- Tên/tệp chính xác của tệp ngôn ngữ block (mục 11) lấy theo trí nhớ về quy ước, chưa mở lại mã nạp block để ghi số dòng.
- Hành vi cron khi `run_file` thiếu chỉ đọc mã, chưa chạy thử.
