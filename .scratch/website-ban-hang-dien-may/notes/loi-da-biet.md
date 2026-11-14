# Issue đang mở của nukeviet/module-shops

Nguồn: `gh issue list --repo nukeviet/module-shops --state open --limit 100` chạy ngày 2026-11-15 — 75 issue mở (tiêu đề + nhãn lấy đúng từ GitHub). Phần "tóm tắt" ở nhóm A dựa trên nội dung issue đã đọc ở lượt khảo sát và đối chiếu với mã nhánh develop (`...\scratchpad\module-shops\modules\shops\`); nhóm B chỉ dựa trên tiêu đề (chưa đọc nội dung từng issue). Chưa tái hiện lỗi nào trên site chạy thật.

Ký hiệu cột "Ở đâu": S = ngoài site, A = quản trị.

## A. Báo lỗi (ưu tiên cho ticket 03 và 04)

| # | Tiêu đề | Tóm tắt một dòng | Ở đâu | Đối chiếu mã develop |
|---|---|---|---|---|
| 725 | Lỗi truy vấn sai chủ đề | `GetCatidInParent` dùng sai biến toàn cục mảng loại nên lấy nhầm danh sách loại con | S | hàm trong `global.functions.php`; chưa kiểm chứng |
| 722 | Lỗi phần in của module shops | Trang in đơn hàng báo lỗi / bị chuyển hướng do `getCanonicalUrl` | S + A (quản trị bấm in cũng mở `funcs/print.php`) | [suy luận] `page_url` không chứa tham số `order_id`, `checkss` nên lõi 4.6 chuyển hướng |
| 713 | Lỗi đặt hàng | Bấm đặt hàng thì nhảy sang trang lỗi (bản 4.5.04) | S | [suy luận] nghi `SHOW TABLE STATUS` lấy auto_increment để sinh mã đơn hoặc nhánh coupon trong `funcs/order.php` |
| 712 | Lỗi hiện thị chi tiết sản phẩm | Nội dung tab chi tiết không hiện cho tới khi bấm vào tab | S | template `detail.tpl`; chưa kiểm chứng |
| 707 | Lỗi không đặt hàng được khi bật chế độ đặt hàng không giới hạn số lượng | Bật "đặt hàng không giới hạn" thì không đặt được; số lượng theo nhóm không lưu | S (+A khi lưu sản phẩm) | liên quan `active_order_number`, `group_quantity`, `funcs/setcart.php` |
| 705 | Lỗi search -> tự động redirect về trang chính... | Tìm kiếm bị chuyển về trang chính, block search hỏng | S | [suy luận] cùng nguyên nhân `getCanonicalUrl` như #722; block search còn nạp `js/user.js` không tồn tại |
| 700 | Lỗi không thêm được sản phẩm yêu thích... | Thêm yêu thích lỗi TypeError | S | [đã thấy trong mã] `funcs/wishlist_update.php` dòng 30 gọi `sizeof()` trên giá trị không phải mảng — chết trên PHP 8 |
| 689 | Uncaught DivisionByZeroError: Division by zero (nhãn discuss) | Chia cho 0 trong `nv_generate_page` trên PHP 8 khi số bản ghi mỗi trang bằng 0 | S | cần đảm bảo `per_page` > 0 |
| 646 | Lỗi không hiển thị sản phẩm khi cấu hình xem theo loại sản phẩm (nhãn bug) | Trang chủ kiểu "theo loại" không hiện sản phẩm của loại cha | S | `funcs/main.php`; chưa kiểm chứng |
| 629 | Lỗi loadcart khi bật chức năng tích lũy điểm | `loadcart` lỗi biến `$xtpl` chưa khai báo khi bật điểm | S | `funcs/loadcart.php` quanh dòng 171 |
| 587 | Lỗi tính năng sendmail, in và save | Gửi bạn bè, in, lưu trang sản phẩm đều hỏng | S | [đã thấy trong mã] `funcs/sendmail.php` truy vấn kiểu module news |
| 584 | Lỗi hiển thị reponsive của block global block_bxproduct_center | Block slider vỡ trên màn hình nhỏ | S (block) | — |
| 579 | Nhấn nút "Mua ngay" vào giỏ hàng không hiển thị mặt hàng đã mua | Mua ngay xong giỏ trống | S | `funcs/setcart.php` + session giỏ |
| 537 | Vấn đề khi thanh toán bằng điểm | Trả bằng điểm tính sai / không trừ đúng | S | `funcs/order.php` dòng 130-148, 407-417 |
| 525 | Lỗi không thêm được icon hình minh họa cho loại sản phẩm | Không tải được ảnh cho loại sản phẩm | A | `admin/cat.php` |
| 524 | Hiển thị số lượng sản phẩm theo loại không đúng | Số đếm sản phẩm theo loại sai | S | — |
| 520 | Lỗi sản phẩm bán chạy | Block sản phẩm bán chạy hiện một cột, không trượt | S (block) | `global.block_product_center` |
| 507 | xem thêm sản phẩm cùng loại trong detail... | Trang chi tiết liệt kê toàn bộ sản phẩm cùng loại, không giới hạn | S | `funcs/detail.php` |
| 500 | up shop mới nhất 4.018 - không gửi phiếu thông tin đặt hàng được | Form đặt hàng không gửi được sau khi nâng cấp | S | có thể trùng #713 |
| 366 | Khi Hủy xác nhận thanh toán đơn không có thông báo (enhancement) | Huỷ xác nhận thanh toán không có phản hồi | A | `admin/active_pay.php` |
| 203 | Nhập kho xong rồi chưa theo dõi được số lượng sản phẩm còn trong kho (enhancement) | Không có màn hình theo dõi tồn sau nhập kho | A | `admin/warehouse*.php` |

## B. Đề xuất / góp ý (chỉ theo tiêu đề)

| # | Tiêu đề | Nhãn | Ở đâu |
|---|---|---|---|
| 720 | Đề xuất tính năng cập nhật tình trạng cho đơn hàng | | A |
| 719 | Đề xuất tích hợp phí vận chuyển qua các kênh api | | S + A |
| 714 | Tích hợp thanh toán đơn hàng qua Momo hoặc ... | | S |
| 704 | Cấu hình lại hiển thị danh mục trong module shops | future | S |
| 675 | Thay đổi giao diện chọn hình ảnh | future | A |
| 666 | Thêm tính năng nhiều giá cho 1 sản phẩm, giá theo thuộc tính | future | S + A |
| 638 | Tối ưu quản lý loại sản phẩm | enhancement | A |
| 622 | Thêm cấu hình sử dụng giá cũ, giá mới | future | A |
| 606 | Tích hợp dịch vụ vận chuyển | | S + A |
| 605 | Cải tiến chức năng tùy biến dữ liệu | | A |
| 585 | Điều khiển hiển thị sản phẩm độc lập cho các loại màn hình | | S |
| 581 | Có nên thêm tính năng resize cho ảnh folder upload? | | A |
| 556 | Thắc mắc về vấn đề giá | | S |
| 544 | Chức năng tự tạo đơn hàng trong admin shop | future | A |
| 535 | Thêm biến phí vận chuyển | future | S |
| 521 | Thay đổi kiểu hiển thị menu danh mục sản phẩm | | S |
| 502 | Góp ý chút | | ? |
| 498 | Sắp xếp vị trí tin và sản phẩm | | A |
| 480 | Rê chuột trong hình để zoom ảnh | future | S |
| 470 | Ẩn hiện các table khi đăng sản phẩm | future | A |
| 465 | Thêm chức năng trong Quản lý Giảm giá | future | A |
| 455 | Góp ý về modal khi ấn đặt hàng | | S |
| 445 | Một số góp ý cho dev shop | | ? |
| 433 | Xem hình ảnh khác của sản phẩm | future | S |
| 431 | Điều chỉnh cơ chế giảm giá và coupon | | S |
| 412 | Chọn xoá nhiều trong quản trị shops | | A |
| 408 | Bổ sung tính năng cho module shop | enhancement | ? |
| 404 | Gộp thông tin đơn hàng vào thông tin tài khoản | future | S |
| 396 | Bổ sung một số tính năng cần thiết | | ? |
| 395 | Đề nghị bổ sung | future | ? |
| 387 | Vấn đề về điểm tích lũy | future | S |
| 381 | Tính năng tích lũy điểm | future | S |
| 341 | Thêm trạng thái đơn hàng vận chuyển | enhancement | A |
| 328 | Góp ý module shops | future | ? |
| 321 | Vài vấn đề góp ý | enhancement | ? |
| 319 | [Hỏi] Đường dẫn hiển thị sản phẩm cùng block sản phẩm | enhancement | S |
| 310 | Tối ưu hình ảnh xem chi tiết | enhancement | S |
| 303 | Bổ sung nút duyệt "Đánh giá sản phẩm" trong admin | enhancement | A |
| 292 | Cấu hình kích thước hình minh họa | enhancement | A |
| 269 | Cấu hình số sản phẩm hiển thị theo chuyên mục | | A |
| 257 | Tuỳ chọn "cho phép khách huỷ đơn hàng không?" | enhancement | S + A |
| 253 | Trường tuỳ chỉnh tiêu đề loại sản phẩm | enhancement | A |
| 246 | Thêm icon xoá mức giá | enhancement | A |
| 244 | Block sản phẩm | duplicate, enhancement | S |
| 234 | Chức năng yêu cầu báo giá | future | S |
| 225 | Trang xem danh sách sản phẩm trong block sản phẩm | enhancement | S |
| 219 | Hiển thị giá theo nhóm tài khoản | future | S |
| 214 | Block hiển thị sản phẩm theo loại | | S |
| 204 | Tìm sản phẩm theo nhóm trong ACP | enhancement | A |
| 201 | Thông báo tình trạng đơn hàng | enhancement | S |
| 196 | Xác nhận thanh toán cần form nhập chi tiết | future | A |
| 180 | Tuỳ chọn nội dung trang in hoá đơn | enhancement | S + A |
| 169 | Lọc sản phẩm theo nhóm trong ACP | future | A |
| 157 | Mua nhanh sản phẩm | future | S |

## C. Lỗi thấy khi đọc mã nhưng chưa có issue

- Cộng trả kho gọi thiếu tham số: `funcs/order.php` dòng 319 và `global.functions.php` dòng 58 gọi `product_number_order()` không truyền `$listgroup` nên `'+'` rơi vào vị trí `$listgroup`, kiểu mặc định vẫn là `'-'` → trừ kho thêm lần nữa (S).
- `funcs/delhis.php` dùng `$data['status']`, `admin/print.php` cập nhật cột `status` — bảng `orders` không có cột này (S / A, tệp admin là tệp chết).
- `funcs/payment.php` dòng 123 tính lại giá hiện tại thay vì đọc giá đã lưu của đơn; dòng 239 nối `$worderid` vào SQL không qua bind (S).
- Coupon số tiền cho sản phẩm chỉ định làm sai tổng đơn: `funcs/order.php` dòng 176-177 (S).
- 49/70 tệp quản trị không kiểm tra token, kể cả tệp xoá/sửa dữ liệu (A) — xem `ban-do-shops.md` mục 3.
- `search.php` (tích hợp seek) truy vấn đầu trỏ vào bảng `_group`; `admin.functions.php` `$allow_func` có `copy_product` không tồn tại tệp; `action_mysql.php` xoá 5 bảng không hề tạo (vô hại).
