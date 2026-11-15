# 05: Xuất gói module và cài lên site sạch

**What to build:** Quản trị xuất shops thành gói bằng chức năng đóng gói của NukeViet, rồi cài gói đó lên một bản NukeViet 4.6.03 thứ hai hoàn toàn sạch, dùng theme mặc định. Ticket này dựng môi trường thứ hai và bài kiểm thử đóng gói để mọi ticket sau chạy lại được.

**Blocked by:** 02 Cài shops gốc và bài kiểm thử đầu tiên

**Status:** skipped (chủ cửa hàng yêu cầu bỏ qua ngày 15/11/2026; lỗi của mã gốc chỉ sửa khi nó chặn một ticket khác)

- [ ] Có môi trường NukeViet thứ hai, dựng và xoá được bằng lệnh, không đụng dữ liệu site chính
- [ ] Gói xuất từ quản trị site chính cài được lên site thứ hai không lỗi
- [ ] Trên site thứ hai, khách vãng lai đặt được một đơn hàng với theme mặc định
- [ ] Gỡ module trên site thứ hai thì không còn bảng nào của shops
- [ ] Shops không yêu cầu sửa tệp nào của lõi NukeViet hay module khác
- [ ] Bài kiểm thử đóng gói chạy lại được bằng một lệnh
