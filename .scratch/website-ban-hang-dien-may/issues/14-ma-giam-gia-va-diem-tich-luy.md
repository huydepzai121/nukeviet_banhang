# 14: Mã giảm giá và điểm tích luỹ

**What to build:** Khách nhập mã giảm giá ở giỏ hàng và thấy ngay số tiền được giảm hoặc lý do mã không dùng được. Thành viên dùng điểm tích luỹ để trừ vào đơn hàng và được cộng điểm khi đơn hoàn tất.

**Blocked by:** 06 Gom việc tính giá về một nơi

**Status:** ready-for-agent

- [ ] Nhân viên tạo mã với giới hạn lượt dùng, thời hạn, giá trị đơn tối thiểu và phạm vi sản phẩm
- [ ] Mã hết hạn, hết lượt, chưa đủ giá trị đơn hoặc sai phạm vi đều bị từ chối kèm lý do cụ thể
- [ ] Lượt dùng chỉ bị trừ khi đơn được tạo; huỷ đơn thì trả lượt
- [ ] Hai đơn cùng lúc dùng lượt cuối cùng của một mã: chỉ một đơn được giảm
- [ ] Thành viên thấy số điểm hiện có, dùng điểm không vượt số đang có và không vượt giá trị đơn
- [ ] Điểm được cộng khi đơn hoàn tất và thu lại khi đơn bị huỷ
