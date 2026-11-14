---
status: accepted
---

# Xây trên NukeViet 4.6, chưa chuyển lên NukeViet 5

Yêu cầu ban đầu là làm module bán hàng cho NukeViet 5, nhưng tại thời điểm quyết định (15/11/2026) NukeViet 5 chưa có bản phát hành nào: nó chỉ là nhánh `nukeviet5.0` còn thay đổi hằng ngày và chưa có hướng dẫn chuyển module chính thức. Chúng tôi chọn NukeViet 4.6.03 (bản ổn định mới nhất) làm nền, vì một module bán hàng hoàn chỉnh cần nền không đổi dưới chân.

## Considered Options

- **NukeViet 5 (nhánh `nukeviet5.0`, ghim một commit)**: đúng yêu cầu ban đầu, nhưng phải viết lại khoảng 85 file giao diện của module (XTemplate sang Smarty, Bootstrap 3 sang 5, đổi cách gọi ngôn ngữ) trước khi thêm được tính năng nào, và có thể phải sửa lại khi lõi thay đổi.
- **NukeViet 4.6.03**: được chọn.

## Consequences

Phần viết mới phải tách xử lý nghiệp vụ khỏi lớp giao diện, để khi NukeViet 5 phát hành chính thức thì việc chuyển chỉ còn là viết lại template.
