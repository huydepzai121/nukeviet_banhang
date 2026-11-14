# 16: Giao kèo cổng thanh toán và SePay

**What to build:** Khách chọn chuyển khoản, thấy mã QR có sẵn số tiền và nội dung, chuyển tiền, và trang đơn hàng tự chuyển sang đã thanh toán. Ticket này dựng giao kèo chung mà mọi cổng thanh toán tuân theo và phần ghi nhận thanh toán dùng chung, với SePay là cổng đầu tiên.

**Blocked by:** 15 Vòng đời đơn hàng và giữ hàng

**Status:** ready-for-agent

- [ ] Quản trị bật, tắt cổng, nhập khoá và chọn chế độ thử nghiệm trong trang quản trị; khoá không nằm trong mã nguồn
- [ ] Thông báo đủ tiền: đơn sang chờ xử lý và trang đơn hàng của khách tự cập nhật
- [ ] Thông báo thiếu tiền: đơn vẫn chờ thanh toán và ghi số đã nhận
- [ ] Thông báo thừa tiền: đơn được xác nhận và bật cờ cần hoàn phần dư
- [ ] Cùng một thông báo gửi hai lần: chỉ ghi nhận một lần
- [ ] Thông báo sai khoá hoặc sai chữ ký bị từ chối và đơn không đổi
- [ ] Tiền về sau khi đơn đã huỷ: đơn không được khôi phục, bật cờ cần xử lý
- [ ] Mọi thông báo nhận được đều được lưu để đối soát
- [ ] Trả tiền khi nhận hàng vẫn hoạt động qua cùng giao kèo
