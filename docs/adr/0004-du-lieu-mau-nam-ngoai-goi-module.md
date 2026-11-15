---
status: accepted
---

# Dữ liệu mẫu nạp bằng một lệnh riêng, số lượng theo yêu cầu xem trang đầy

Spec gốc nói khoảng 150 đến 200 sản phẩm và không đưa dữ liệu mẫu vào gói module. Chủ cửa hàng sau đó yêu cầu trang phải đầy khi chụp và kiểm thử: 15–20 ngành hàng có ngành con, 300–500 sản phẩm, hơn 100 thành viên và hơn 200 đơn.

Chúng tôi giữ dữ liệu mẫu ngoài gói module (ADR 0003) và nạp bằng `php tools/seed-sangnha.php`. Số lượng lấy theo yêu cầu sau: 420 sản phẩm, 130 thành viên, 260 đơn, 450 đánh giá. Ảnh do lệnh nạp tự vẽ. Chạy lại lệnh này thay bộ dữ liệu mẫu, không nhân đôi.

Phần thương hiệu, thông số, biến thể và tồn theo cửa hàng chờ các ticket tương ứng vì schema shops gốc chưa có các cột đó.
