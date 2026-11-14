# 18: MoMo

**What to build:** Khách chọn MoMo, thanh toán ở chế độ thử nghiệm của cổng, và quay về trang đơn hàng đã được xác nhận.

**Blocked by:** 16 Giao kèo cổng thanh toán và SePay

**Status:** ready-for-agent

- [ ] Cổng tuân theo giao kèo chung, không sửa phần ghi nhận thanh toán
- [ ] Thanh toán thành công: đơn sang chờ xử lý
- [ ] Khách huỷ hoặc thanh toán thất bại: đơn vẫn chờ thanh toán, khách được báo rõ và thanh toán lại được khi còn hạn
- [ ] Thông báo sai chữ ký bị từ chối
- [ ] Số tiền trong thông báo khác số tiền của đơn thì không xác nhận đơn
- [ ] Thông báo trùng chỉ ghi nhận một lần
