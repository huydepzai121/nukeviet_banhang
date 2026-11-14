# 03: Rà và sửa lỗi shops ngoài site

**What to build:** Khách đi qua mọi trang ngoài site của shops gốc mà không gặp lỗi: ngành hàng, chi tiết, tìm kiếm, giỏ hàng, đặt hàng, lịch sử, tra cứu đơn, so sánh, yêu thích, đánh giá. Các lỗi đặt hàng và tìm kiếm đã được báo trên kho chính thức phải được tái hiện rồi sửa. Nếu danh sách lỗi quá dài cho một ticket thì tách theo nhóm chức năng.

**Blocked by:** 02 Cài shops gốc và bài kiểm thử đầu tiên

**Status:** ready-for-agent

- [ ] Mỗi lỗi trong danh sách ngoài site có bài kiểm thử tái hiện trước khi sửa và qua sau khi sửa, hoặc ghi rõ lý do không sửa
- [ ] Mọi trang ngoài site của shops trả về mã 200 và không sinh cảnh báo hay lỗi PHP
- [ ] Tìm kiếm trả đúng sản phẩm theo tên
- [ ] Đặt hàng thành công với sản phẩm có và không có khuyến mãi
- [ ] Mọi dữ liệu vào lấy qua đối tượng yêu cầu của hệ thống, truy vấn dùng câu lệnh chuẩn bị sẵn, dữ liệu ra được mã hoá
