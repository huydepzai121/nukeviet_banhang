# 10: Cửa hàng và tồn kho theo cửa hàng

**What to build:** Quản trị khai báo các cửa hàng. Nhân viên nhập và điều chỉnh tồn kho của từng biến thể tại từng cửa hàng, có ghi lý do. Khách xem trang chi tiết thấy biến thể đang chọn còn hàng ở những cửa hàng nào.

**Blocked by:** 08 Biến thể

**Status:** ready-for-agent

- [ ] Quản trị thêm, sửa, ẩn cửa hàng với địa chỉ, toạ độ, giờ mở cửa, số điện thoại
- [ ] Nhân viên chỉnh tồn theo cặp biến thể và cửa hàng; mỗi lần chỉnh sinh một dòng nhật ký có người, thời điểm, lý do
- [ ] Sản phẩm còn hàng khi tổng tồn các cửa hàng lớn hơn 0
- [ ] Biến thể hết hàng vẫn hiện trên trang chi tiết nhưng không thêm vào giỏ được
- [ ] Trang chi tiết liệt kê đúng các cửa hàng còn hàng cho biến thể đang chọn
- [ ] Có danh sách biến thể sắp hết hàng theo ngưỡng cấu hình được
- [ ] Tồn kho của bản shops cũ được chuyển sang kho mặc định khi nâng cấp
