# 35: Dữ liệu sản phẩm mẫu

**What to build:** Người triển khai chạy một bước riêng và site có đủ cây ngành hàng điện máy với 150 đến 200 sản phẩm, kèm thương hiệu, thông số, biến thể, cửa hàng và tồn kho. Dữ liệu này không nằm trong gói module.

**Blocked by:** 07 Thương hiệu; 09 Bộ thông số và lọc theo thông số; 10 Cửa hàng và tồn kho theo cửa hàng

**Status:** ready-for-agent

- [ ] Đủ các ngành hàng: điện thoại, laptop, máy tính bảng, đồng hồ thông minh, âm thanh, phụ kiện, máy tính và màn hình, linh kiện, tivi, máy lạnh, tủ lạnh, máy giặt, gia dụng, SIM và thẻ cào
- [ ] Mỗi ngành hàng có khoảng 10 đến 15 sản phẩm với bộ thông số đầy đủ để lọc và so sánh
- [ ] Tên và thông số theo sản phẩm thật; giá là giá giả định; ảnh là ảnh minh hoạ tự tạo
- [ ] Có dấu hiệu nhìn thấy được cho biết đây là dữ liệu mẫu
- [ ] Không dùng ảnh hay đoạn mô tả lấy từ trang bán lẻ khác
- [ ] Chạy lại bước nạp không tạo dữ liệu trùng
- [ ] Site chạy đúng khi không nạp dữ liệu mẫu
