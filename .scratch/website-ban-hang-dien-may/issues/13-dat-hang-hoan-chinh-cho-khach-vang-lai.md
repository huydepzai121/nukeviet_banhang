# 13: Đặt hàng hoàn chỉnh cho khách vãng lai

**What to build:** Khách vãng lai đặt hàng chỉ với họ tên, số điện thoại và địa chỉ chọn từ danh sách tỉnh thành, phường xã. Khách thấy phí vận chuyển và bản tóm tắt trước khi xác nhận, nhận email xác nhận, và tra cứu được đơn bằng số điện thoại cùng mã đơn.

**Blocked by:** 03 Rà và sửa lỗi shops ngoài site

**Status:** ready-for-agent

- [ ] Thêm vào giỏ không rời trang; đổi số lượng và bỏ sản phẩm trong giỏ
- [ ] Địa chỉ chọn từ danh sách; số điện thoại sai định dạng bị từ chối
- [ ] Phí vận chuyển tính theo bảng phí tỉnh thành do quản trị cấu hình
- [ ] Đơn đạt ngưỡng thì miễn phí vận chuyển; chưa đạt thì hiện còn thiếu bao nhiêu
- [ ] Bản tóm tắt hiện từng dòng hàng, giảm giá, phí và tổng tiền, trùng với đơn được tạo
- [ ] Email xác nhận được gửi kèm mã đơn
- [ ] Tra cứu đúng số điện thoại và mã đơn thì hiện đơn; sai một trong hai thì không lộ thông tin gì
- [ ] Tra cứu sai nhiều lần liên tiếp bị giới hạn tần suất
