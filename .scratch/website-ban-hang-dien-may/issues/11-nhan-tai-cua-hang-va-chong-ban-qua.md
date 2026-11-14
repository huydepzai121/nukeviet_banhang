# 11: Nhận tại cửa hàng và chống bán quá

**What to build:** Khách chọn giao tận nơi hoặc nhận tại một cửa hàng còn hàng. Tồn kho được trừ đúng nơi khi đơn được tạo và trả lại khi đơn bị huỷ. Không bao giờ bán quá số đang có.

**Blocked by:** 10 Cửa hàng và tồn kho theo cửa hàng

**Status:** ready-for-agent

- [ ] Nhận tại cửa hàng trừ tồn của cửa hàng được chọn; giao tận nơi trừ kho mặc định do quản trị chọn
- [ ] Chỉ chọn được cửa hàng còn đủ hàng cho mọi dòng trong giỏ
- [ ] Nhận tại cửa hàng có phí vận chuyển bằng 0
- [ ] Hai yêu cầu đặt hàng gửi cùng lúc cho đơn vị hàng cuối cùng: đúng một đơn thành công, đơn kia được báo hết hàng
- [ ] Huỷ đơn trả tồn về đúng cửa hàng đã trừ, có dòng nhật ký
- [ ] Giỏ hàng báo khi số lượng chọn vượt tồn
