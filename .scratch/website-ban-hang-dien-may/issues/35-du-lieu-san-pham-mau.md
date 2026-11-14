# 35: Dữ liệu sản phẩm mẫu

**What to build:** Người triển khai chạy một bước riêng và site có đủ cây ngành hàng điện máy với 150 đến 200 sản phẩm, kèm thương hiệu, thông số, biến thể, cửa hàng và tồn kho. Dữ liệu này không nằm trong gói module.

**Blocked by:** 07 Thương hiệu; 09 Bộ thông số và lọc theo thông số; 10 Cửa hàng và tồn kho theo cửa hàng

**Status:** ready-for-agent

## Comments

- 2026-11-14: Chủ cửa hàng yêu cầu số lượng lớn hơn spec (300–500 sản phẩm, 100+ thành viên, 200+ đơn). Đã có `php tools/seed-sangnha.php` nạp 20 ngành hàng, 420 sản phẩm, 130 thành viên, 260 đơn, 450 đánh giá trên schema shops hiện tại. Thương hiệu, bộ thông số, biến thể và tồn theo cửa hàng vẫn chờ ticket 07, 09, 10 nên các mục chấp nhận đó chưa đánh dấu xong.

- [ ] Đủ các ngành hàng: điện thoại, laptop, máy tính bảng, đồng hồ thông minh, âm thanh, phụ kiện, máy tính và màn hình, linh kiện, tivi, máy lạnh, tủ lạnh, máy giặt, gia dụng, SIM và thẻ cào
- [ ] Mỗi ngành hàng có khoảng 10 đến 15 sản phẩm với bộ thông số đầy đủ để lọc và so sánh
- [ ] Tên và thông số theo sản phẩm thật; giá là giá giả định; ảnh là ảnh minh hoạ tự tạo
- [ ] Có dấu hiệu nhìn thấy được cho biết đây là dữ liệu mẫu
- [ ] Không dùng ảnh hay đoạn mô tả lấy từ trang bán lẻ khác
- [ ] Chạy lại bước nạp không tạo dữ liệu trùng
- [ ] Site chạy đúng khi không nạp dữ liệu mẫu
