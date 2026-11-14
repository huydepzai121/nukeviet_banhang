# 22: Flash sale

**What to build:** Nhân viên tạo đợt flash sale có khung giờ, mỗi sản phẩm trong đợt có giá và số suất riêng. Khách thấy đợt đang chạy kèm đếm ngược và số suất còn lại, mua được với giá của đợt cho tới khi hết suất hoặc hết giờ.

**Blocked by:** 08 Biến thể; 11 Nhận tại cửa hàng và chống bán quá

**Status:** ready-for-agent

- [ ] Trong khung giờ và còn suất: giá của đợt được áp ở danh sách, chi tiết, giỏ hàng và đơn hàng
- [ ] Hết giờ hoặc hết suất: sản phẩm về giá thường mà không ai phải sửa tay
- [ ] Suất chỉ bị trừ khi đơn được tạo thành công, không trừ khi bỏ vào giỏ
- [ ] Khách có hàng trong giỏ nhưng suất đã hết: bước đặt hàng báo rõ và hiện giá thường trước khi xác nhận
- [ ] Hai đơn cùng lúc cho suất cuối cùng: chỉ một đơn được giá của đợt
- [ ] Một số điện thoại chỉ mua được một suất cho mỗi sản phẩm trong một đợt
- [ ] Huỷ đơn trả suất nếu đợt còn hiệu lực
- [ ] Có trang liệt kê đợt đang chạy và các đợt sắp tới
