# 15: Vòng đời đơn hàng và giữ hàng

**What to build:** Đơn hàng đi qua các trạng thái chờ thanh toán, chờ xử lý, đang giao, hoàn tất, đã huỷ. Đơn chờ thanh toán giữ hàng trong thời gian cấu hình được rồi tự huỷ. Khách tự huỷ được đơn chưa xử lý và nhận email khi đơn đổi trạng thái.

**Blocked by:** 11 Nhận tại cửa hàng và chống bán quá

**Status:** ready-for-agent

- [ ] Nhân viên chỉ chuyển được trạng thái theo đúng trình tự cho phép, có ghi chú; mỗi lần chuyển được lưu lại
- [ ] Đơn chờ thanh toán hiện thời hạn giữ hàng cho khách
- [ ] Quá hạn giữ hàng thì đơn tự huỷ và tồn kho được trả lại, kể cả khi không ai mở trang
- [ ] Thời gian giữ hàng cấu hình được trong quản trị, mặc định 30 phút
- [ ] Khách huỷ được đơn ở trạng thái chờ thanh toán hoặc chờ xử lý, không huỷ được đơn đang giao
- [ ] Đơn có cờ "cần xử lý thủ công" kèm lý do; nhân viên gỡ cờ sau khi xử lý
- [ ] Khách nhận email mỗi khi đơn đổi trạng thái
