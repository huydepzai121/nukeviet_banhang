# 01: Dựng môi trường và cài NukeViet 4.6.03

**What to build:** Người triển khai chạy một lệnh và có site NukeViet 4.6.03 hoạt động trong Docker (máy chủ web, PHP, MariaDB). Lần cài trước bị dừng giữa chừng và giải nén sai thư mục nên phải giải nén lại toàn bộ mã nguồn trước khi chạy trình cài đặt.

**Blocked by:** Không (bắt đầu được ngay)

**Status:** ready-for-agent

- [ ] Trang chủ và trang quản trị trả về mã 200 trên cổng đã cấu hình
- [ ] Đăng nhập được quản trị bằng tài khoản trong tệp môi trường; mật khẩu không xuất hiện trong git hay trong nhật ký
- [ ] Thư mục cài đặt đã được xoá sau khi cài xong
- [ ] Dừng rồi khởi động lại các container thì dữ liệu còn nguyên
- [ ] Có lệnh sao lưu và khôi phục cơ sở dữ liệu chạy được
- [ ] Commit đầu tiên chứa mã NukeViet nguyên bản; không đẩy lên kho từ xa
