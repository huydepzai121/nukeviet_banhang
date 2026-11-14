# 04: Rà và sửa lỗi shops trong quản trị

**What to build:** Nhân viên dùng mọi trang quản trị của shops gốc mà không gặp lỗi: ngành hàng, sản phẩm, nhóm, mẫu thông số, đơn hàng, in đơn, mã giảm giá, kho, vận chuyển, điểm, cấu hình. Lỗi in đơn đã được báo trên kho chính thức phải được tái hiện rồi sửa.

**Blocked by:** 02 Cài shops gốc và bài kiểm thử đầu tiên

**Status:** ready-for-agent

- [ ] Mỗi lỗi trong danh sách quản trị có bài kiểm thử tái hiện trước khi sửa và qua sau khi sửa, hoặc ghi rõ lý do không sửa
- [ ] Mọi trang quản trị của shops mở được và không sinh cảnh báo hay lỗi PHP
- [ ] Thêm, sửa, xoá chạy đúng ở từng mục quản trị
- [ ] In đơn hàng ra đúng nội dung đơn
- [ ] Mọi thao tác ghi trong quản trị có kiểm tra chống giả mạo yêu cầu
