# 36: Nghiệm thu trọn luồng và đóng gói lần cuối

**What to build:** Toàn bộ site được nghiệm thu so với spec: mọi bài kiểm thử qua, gói shops và gói theme xuất ra và cài lại được, và một site đang dùng bản shops cũ nâng cấp lên được mà không mất dữ liệu.

**Blocked by:** Tất cả các ticket từ 01 đến 35

**Status:** ready-for-agent

- [ ] Toàn bộ bài kiểm thử qua trên site chính
- [ ] Gói shops cuối cùng cài lên site sạch dùng theme mặc định và qua bài kiểm thử mua hàng, gồm một cổng thanh toán ở chế độ thử nghiệm
- [ ] Gói theme cài được lên site sạch đã có shops
- [ ] Site có dữ liệu của bản shops gốc nâng cấp lên bản mới: sản phẩm, đơn hàng và tồn kho còn nguyên
- [ ] Gỡ module trên site sạch không để sót bảng
- [ ] Không có tệp nào của lõi NukeViet hay module khác bị sửa so với commit gốc
- [ ] Mỗi user story trong spec được đối chiếu: đã đạt, hoặc ghi rõ phần chưa đạt và lý do
- [ ] Có bản sao lưu cơ sở dữ liệu cuối cùng và hướng dẫn dựng lại site
