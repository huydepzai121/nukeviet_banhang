---
status: accepted
---

# Phát triển tiếp từ mã module shops nhánh develop, không viết lại từ đầu

Bản shops trên kho store NukeViet lỗi nhiều và mục tiêu là một module bán hàng hoàn chỉnh, nên viết mới hoàn toàn là lựa chọn dễ nghĩ tới. Chúng tôi vẫn lấy mã nhánh `develop` của `nukeviet/module-shops` (phiên bản 4.6.00) làm gốc, giữ tên module `shops` và cấu trúc bảng sẵn có, vì phần nghiệp vụ nền (danh mục, giỏ hàng, đơn hàng, kho, mã giảm giá, điểm, vận chuyển, khoảng 44 bảng) đã có và viết lại nó không tạo thêm giá trị.

## Consequences

Module sẽ lệch xa kho chính thức và không còn nhận bản vá ngược từ đó; lỗi của mã gốc là trách nhiệm của dự án này. Phần nào của mã gốc không cứu được thì viết lại riêng phần đó.
