# 02: Cài shops gốc và bài kiểm thử đầu tiên

**What to build:** Module shops lấy từ nhánh develop của kho chính thức được cài lên site, và một khách vãng lai đặt được một đơn hàng trả tiền khi nhận hàng. Đây cũng là lúc dựng bộ kiểm thử qua HTTP: khôi phục cơ sở dữ liệu về trạng thái đã biết rồi chạy một bài mua hàng trọn luồng. Chỉ sửa những lỗi chặn việc cài và chặn luồng này; các lỗi khác ghi lại cho ticket 3 và 4.

**Blocked by:** 01 Dựng môi trường và cài NukeViet 4.6.03

**Status:** ready-for-agent

- [ ] Mã shops nguyên bản được commit riêng trước mọi sửa đổi
- [ ] Shops cài được qua trang quản trị, các bảng được tạo, không có lỗi PHP trong nhật ký
- [ ] Nhân viên tạo được một ngành hàng và một sản phẩm
- [ ] Khách vãng lai thêm sản phẩm vào giỏ và đặt được đơn trả tiền khi nhận hàng; đơn hiện trong quản trị
- [ ] Một lệnh khôi phục dữ liệu rồi chạy bài kiểm thử mua hàng, chạy lại nhiều lần cho cùng kết quả
- [ ] Có danh sách lỗi phát hiện được, chia theo ngoài site và quản trị
- [ ] Đã đọc mã chức năng đóng gói module và ghi lại nó gom những thư mục nào
