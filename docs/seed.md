# Nạp dữ liệu mẫu Sáng Nhà

Dữ liệu mẫu không nằm trong gói module shops. Site vẫn chạy khi chưa nạp.

## Điều kiện

- NukeViet đã cài (`site/config.php` tồn tại).
- Module shops đã được cài trong quản trị.
- PHP CLI có GD.

## Lệnh

```bash
php tools/seed-sangnha.php
```

Chạy lại lệnh này xoá ngành hàng, sản phẩm, đơn, đánh giá và thành viên mẫu rồi tạo lại, không nhân đôi.

Sau khi chạy, script in số lượng và xoá cache file của NukeViet (kể cả thư mục con).

## Dữ liệu được tạo

- 20 ngành hàng (6 ngành cha và 14 ngành con), gồm điện thoại, laptop, máy tính bảng, màn hình, âm thanh, đồng hồ thông minh, tivi, máy lạnh, tủ lạnh, máy giặt, gia dụng, linh kiện, SIM và thẻ cào.
- 420 sản phẩm, giá VND, phần lớn có giá giảm, mô tả có chữ "Dữ liệu mẫu", tồn kho (một phần hết hàng).
- Ảnh minh hoạ tự vẽ, ghi vào `site/uploads/shops/seed/` (thư mục upload không đưa vào git).
- 130 thành viên mẫu. Đăng nhập: `khach001`, mật khẩu `Mau123a!`.
- 260 đơn ở các trạng thái chờ duyệt, chưa thanh toán, đang thanh toán, đã thanh toán đang giữ, hoàn trả và đã nhận tiền.
- 450 đánh giá đã duyệt.

Sản phẩm mốc để kiểm thử: Samsung Galaxy A15 128GB, đường dẫn `/shops/smartphone/samsung-galaxy-a15-128gb.html`.

Thương hiệu riêng, bộ thông số, biến thể và tồn theo cửa hàng chưa có trong schema hiện tại. Những phần đó thuộc các ticket 07, 09 và 10; bộ nạp sẽ bổ sung khi các bảng đó có.
