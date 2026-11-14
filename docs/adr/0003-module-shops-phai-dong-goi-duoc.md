---
status: accepted
---

# Module shops phải đóng gói được bằng chức năng xuất module của NukeViet

Module shops phải cài được lên một site NukeViet 4.6 khác chỉ bằng gói do chức năng đóng gói module trong quản trị tạo ra. Vì vậy mọi thứ module cần để chạy (mã, bảng dữ liệu, cấu hình mặc định, ngôn ngữ, template, cổng thanh toán) nằm trong các đường dẫn chuẩn của module, và không tính năng nào của shops được dựa vào việc sửa mã lõi NukeViet hay sửa module khác.

## Consequences

- Module phải chạy đúng trên theme mặc định; theme riêng của site chỉ ghi đè giao diện, không chứa nghiệp vụ.
- Cải tiến đăng nhập thuộc về theme và module thành viên, không thuộc shops, nên không đi kèm gói shops.
- Dữ liệu sản phẩm mẫu không nằm trong gói module; nó được nạp riêng.
