# Danh sách lỗi của shops gốc trên NukeViet 4.6.03 (chạy thật)

Thu ngày 15/11/2026 trên site Docker (`http://localhost:8086`, PHP 8.2, MariaDB 11.4), mã shops nguyên bản (commit nhập mã, chưa sửa dòng nào). Dữ liệu lúc rà: 1 ngành hàng "Tủ lạnh" (id 1), 1 sản phẩm id 1 tồn 5, vài đơn của khách vãng lai và một đơn của thành viên.

Cách thu:
- `tests/scripts/sweep-pages.mjs`: GET mọi tệp trong `funcs/` (29) và `admin/` (70) của shops, mỗi tệp ngoài site gọi hai kiểu (thường và kiểu ajax có `nocache`), cộng các địa chỉ có tham số thật — tổng 172 lượt. Mỗi lượt ghi mã HTTP, chuyển hướng, thông báo PHP trong trang và dòng mới của `site/data/logs/error_logs/*_error_log.log`.
- Đi tay (bằng script) các luồng có trạng thái: đặt đơn, in đơn, sửa đơn, tìm kiếm, yêu thích, so sánh, thành viên xem lịch sử.
- Log container `php`, `nginx`: không có lỗi PHP nào ngoài những gì NukeViet đã ghi.

Lưu ý khi đọc log: NukeViet chỉ ghi mỗi lỗi một lần trong ngày (theo mã băm trong `error_logs/tmp/`), nên một lỗi không xuất hiện lại ở lần chạy sau không có nghĩa là đã hết.

Kết quả chung: **cài đặt và luồng khách vãng lai đặt một đơn không gặp lỗi chặn nào** — không phải sửa mã. Cài tạo 44 bảng `nv4_shops_*`, nhật ký lỗi không có dòng nào của shops trong lúc cài và trong luồng mua hàng.

Ký hiệu: `S` = `/index.php?language=vi&nv=shops`, `A` = `/admin/index.php?language=vi&nv=shops`. Đường dẫn tệp tính từ `site/modules/shops/`.

## Ngoài site

| # | Cách tái hiện | Biểu hiện | Tệp nghi ngờ |
|---|---|---|---|
| S1 | GET `S&op=search&q=zzzzqq` (hoặc gửi form tìm kiếm `#fsea` ở `/shops/search/`, hoặc `/shops/search/?q=zzzzqq`) | Bị chuyển hướng về `/index.php/shops/search/`, mất `q`; trang hiện mọi sản phẩm dù từ khoá không khớp gì. Khớp issue #705 | `funcs/search.php` dòng 68-72: `$page_url` không mang tham số tìm kiếm rồi gọi `getCanonicalUrl()` |
| S2 | Đặt một đơn, lấy `order_id` + `checkss` trên trang thanh toán, GET `S&op=print&order_id=N&checkss=...` (dạng không rewrite — chính là dạng nút "In đơn hàng" trong quản trị dùng) | Chuyển về trang chủ module, không in được. Khớp issue #722. Dạng rewrite `/index.php/shops/print/?order_id=N&checkss=...` (link trên trang thanh toán của khách) thì in được | `funcs/print.php` dòng 23-28 (`$page_url` thiếu `order_id`, `checkss`) |
| S3 | Mở link in dạng rewrite ở S2 | In được nhưng log: `Warning: Undefined array key "group_id"` | `funcs/print.php` dòng 44 (cột thật là `listgroupid`) |
| S4 | Đăng nhập thành viên, mở `/shops/history/` hoặc `S&op=history` | Chuyển về `/index.php/shops/`: thành viên không xem được lịch sử đơn hàng | `funcs/history.php` dòng 27-32: `$page_url` thiếu `op=history` |
| S5 | Đăng nhập thành viên chưa có danh sách yêu thích, POST (ajax) `S&op=wishlist_update&nocache=<ms>` với `id=1&ac=add` | HTTP 500, thân rỗng. Log: `sizeof(): Argument #1 ($value) must be of type Countable|array, bool given`. Khớp issue #700 | `funcs/wishlist_update.php` dòng 30 |
| S6 | Khách chưa so sánh gì, POST (ajax) `S&op=compare&nocache=<ms>` với `compare=1&id=1` | HTTP 500. Log: `in_array(): Argument #2 ($haystack) must be of type array, bool given` | `funcs/compare.php` dòng 20-24 (session rỗng, `unserialize` trả `false`) |
| S7 | GET `S&op=sendmail` (có hay không có `id`) | HTTP 500. Log: `SQLSTATE[42S02] ... Table 'nv4_vi_shops_0' doesn't exist`. Khớp issue #587 | `funcs/sendmail.php` (truy vấn kiểu module news) |
| S8 | Trên trang thanh toán của đơn vừa đặt, bấm "Sửa đơn hàng" (`/index.php/shops/payment/?edit=&order_id=N&checkss=...`) | Vào được giỏ ở chế độ sửa nhưng log 3 dòng `Warning: Trying to access array offset on value of type bool` | `funcs/cart.php` dòng 84, 99-101: tra bảng `rows` bằng `id` của dòng đơn thay vì `proid` → tồn kho và khối lượng của dòng đang sửa bị rỗng |
| S9 | Giỏ trống, GET `/shops/order/` | Chuyển về trang chủ module; log `Warning: Undefined array key "shops_cart"` và `Undefined variable $weight_total` | `funcs/order.php` dòng 628, 694 |
| S10 | GET `S&op=detail` (không có alias) | Trang vẫn ra; log `Warning: Undefined variable $alias_url` + `Deprecated: PDO::quote(): Passing null` | `funcs/detail.php` dòng 72 |
| S11 | POST (ajax) `S&op=detail&nocache=<ms>` với `check_quantity=1&id_pro=1&pro_unit=Chiếc&listid=` | Trả `OK__Số lượng: Chiếc` (thiếu con số); log `Warning: Undefined variable $quantity` | `funcs/detail.php` dòng 21 |
| S12 | GET `/shops/rss/tu-lanh/` | Trang ra; log `Warning: Undefined array key 7` | `funcs/rss.php` dòng 48 |
| S13 | GET `/sitemap-vi.shops.xml` hoặc `S&op=sitemap` | HTTP 404 dù đã có sản phẩm. Chưa tìm nguyên nhân | `funcs/sitemap.php` |
| S14 | GET `/shops/khong-ton-tai/`, `/shops/tu-lanh/page-2/` (trang không có) | Chuyển về trang chủ module thay vì báo không tìm thấy | `funcs/viewcat.php`, `functions.php` |
| S15 | Mở `/shops/cart/` khi có hàng, tắt JavaScript (hoặc đọc HTML) | "Tổng số tiền cần thanh toán" trống: tổng chỉ được nạp bằng ajax `loadcart&t=2` | `themes/default/modules/shops/cart.tpl` dòng 120, 143 |
| S16 | Đặt xong một đơn, đọc trang thanh toán | "Đặt ngày: 15-11-2026 lúc 03:19'" — thừa dấu nháy sau giờ | `funcs/payment.php` hoặc `theme.php` (định dạng ngày) |
| S17 | Đặt đơn (bất kỳ) | Đơn vẫn tạo; log `User Warning: Không dùng được các hàm gửi thư` (`includes/functions.php` dòng 1594) → email xác nhận không được gửi. Nguyên nhân là môi trường: `mailer_mode=mail` mà container PHP không có trình gửi thư. Cần một hộp thư thử (vd Mailpit) trước khi kiểm user story 46 | môi trường Docker, không phải shops |

Ghi nhận không phải lỗi mã nhưng lệch spec (để các ticket tính năng xử lý):
- Form đặt hàng bắt buộc email (`order_email (*)`); user story 40 chỉ đòi họ tên, điện thoại, địa chỉ.
- Không có lựa chọn "trả tiền khi nhận hàng" tường minh: đơn tạo ra ở trạng thái "Chưa thanh toán", trang thanh toán ghi "hệ thống tạm thời không hỗ trợ thanh toán trực tuyến đơn hàng này". Bài kiểm thử hiện coi đây là đơn trả tiền khi nhận hàng.
- `/shops/wishlist/` khi danh sách trống và `/shops/point/` khi chưa bật điểm đều chuyển về trang chủ module mà không báo gì (theo cấu hình, không phải lỗi).

Chưa tái hiện, vẫn cần kiểm ở ticket 03 (xem `cai-shops.md` mục 5 và `loi-da-biet.md`): mã đơn lấy từ `SHOW TABLE STATUS` (#713/#500 — trên MariaDB 11.4 đặt 4 đơn liên tiếp không trùng mã), nhánh mã giảm giá trong `order.php`, cộng trả kho sai dấu khi sửa/huỷ đơn, `delhis.php` đọc cột `status` không tồn tại, block tìm kiếm nạp `js/user.js` không có, các block của shops (chưa đặt block nào lên trang).

## Quản trị

| # | Cách tái hiện | Biểu hiện | Tệp nghi ngờ |
|---|---|---|---|
| A1 | Chưa tạo nhóm block nào, GET `A&op=block` (menu "Block sản phẩm") | HTTP 500, trang trắng. Log: `Warning: Undefined array key 0` (dòng 39), `Undefined array key ""` (dòng 45) | `admin/block.php` dòng 36-45 (không xử lý trường hợp chưa có block) |
| A2 | `A&op=or_view&order_id=1`, bấm "In đơn hàng" | Cửa sổ in mở link `S&op=print&...` dạng không rewrite → về trang chủ module (cùng gốc với S2) | `funcs/print.php`, `admin/or_view.php` dòng 246 |
| A3 | Sau khi cài (không nạp dữ liệu mẫu), GET `A&op=content` | Không thêm được sản phẩm: báo "Chưa có đơn vị sản phẩm" và chuyển sang trang đơn vị. Cài đặt không tạo đơn vị mặc định nào | `action_mysql.php` (dữ liệu khởi tạo), `admin/content.php` |
| A4 | GET `A&op=change_source`, `A&op=del_source`, `A&op=print` | HTTP 404: tệp có trong `admin/` nhưng không nằm trong `$allow_func` (tệp chết) | `admin.functions.php` dòng 15-87 |
| A5 | GET `A&op=warehouse`, `A&op=warehouse_logs`, `A&op=seller` (kể cả kèm `listid=1`/`pro_id=1`) | Đều chuyển về danh sách sản phẩm; danh sách sản phẩm không có link nhập kho khi `active_warehouse=0`. Chưa đi được các màn hình kho — cần bật cấu hình kho rồi rà lại ở ticket 04 | `admin/warehouse.php`, `admin/items.php` |
| A6 | `A&op=order` | Danh sách đơn chỉ hiện email người đặt, không có tên và điện thoại; không lọc được theo tên/điện thoại | `admin/order.php`, `themes/admin_default/modules/shops/order.tpl` |

Các trang quản trị còn lại (64 tệp) trả HTTP 200 hoặc chuyển hướng hợp lệ khi GET không tham số và không ghi lỗi vào log. Các tệp chỉ trả lời POST/ajax (`del_*`, `change_*`, `list_*`, `active_pay`, `or_del`...) mới được gọi bằng GET rỗng, chưa thử thao tác ghi; việc thiếu kiểm tra token ở 49/70 tệp (xem `ban-do-shops.md` mục 3) chưa được kiểm bằng yêu cầu thật.
