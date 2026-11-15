# 02: Cài shops gốc và bài kiểm thử đầu tiên

**What to build:** Module shops lấy từ nhánh develop của kho chính thức được cài lên site, và một khách vãng lai đặt được một đơn hàng trả tiền khi nhận hàng. Đây cũng là lúc dựng bộ kiểm thử qua HTTP: khôi phục cơ sở dữ liệu về trạng thái đã biết rồi chạy một bài mua hàng trọn luồng. Chỉ sửa những lỗi chặn việc cài và chặn luồng này; các lỗi khác ghi lại cho ticket 3 và 4.

**Blocked by:** 01 Dựng môi trường và cài NukeViet 4.6.03

**Status:** done

- [x] Mã shops nguyên bản được commit riêng trước mọi sửa đổi
- [x] Shops cài được qua trang quản trị, các bảng được tạo, không có lỗi PHP trong nhật ký
- [x] Nhân viên tạo được một ngành hàng và một sản phẩm
- [x] Khách vãng lai thêm sản phẩm vào giỏ và đặt được đơn trả tiền khi nhận hàng; đơn hiện trong quản trị
- [x] Một lệnh khôi phục dữ liệu rồi chạy bài kiểm thử mua hàng, chạy lại nhiều lần cho cùng kết quả
- [x] Có danh sách lỗi phát hiện được, chia theo ngoài site và quản trị
- [x] Đã đọc mã chức năng đóng gói module và ghi lại nó gom những thư mục nào

## Kết quả

- Mã shops gốc (nhánh develop, commit `a9575390`) cài và chạy luồng khách vãng lai đặt đơn mà không phải sửa dòng nào.
- Chạy kiểm thử: `cd tests && npm test` (khôi phục cơ sở dữ liệu từ `tests/.baseline/site.sql`, xoá cache, chạy 4 bước: cài module, tạo ngành hàng, tạo sản phẩm, khách đặt đơn và nhân viên thấy đơn). Bản chụp không nằm trong git vì chứa hash mật khẩu quản trị và khoá của site; tạo bằng `npm run baseline` trên bản NukeViet mới cài, chưa có shops.
- Shops gốc chưa có lựa chọn "trả tiền khi nhận hàng" tường minh: đơn không thanh toán trực tuyến ở trạng thái "Chưa thanh toán" và bài kiểm thử khẳng định đúng điều đó.
- Danh sách lỗi: `notes/danh-sach-loi.md`. Kết quả xuất gói thật: `notes/dong-goi-module.md` mục "Đã kiểm trên site chạy thật".
