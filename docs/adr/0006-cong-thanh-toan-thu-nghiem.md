---
status: accepted
---

# Cổng thanh toán chạy ở chế độ thử nghiệm khi chưa có khoá merchant

SePay, VNPAY, MoMo và ZaloPay cần tài khoản merchant và địa chỉ công khai. Bản này bật cả năm cách trả (kể cả trả tiền khi nhận hàng) ở chế độ thử nghiệm, khoá mẫu `sandbox` nằm trong bảng cấu hình chứ không viết cứng trong nhánh tính tiền.

Thông báo kết quả chỉ được ghi khi chữ ký `sha256(cổng|đơn|số tiền|mã giao dịch|sandbox)` khớp. Thông báo trùng mã giao dịch không ghi lần hai. Đăng nhập Google trên hộp thoại chỉ là lối vào: khoá OAuth vẫn do chủ cửa hàng nhập trong quản trị thành viên.
