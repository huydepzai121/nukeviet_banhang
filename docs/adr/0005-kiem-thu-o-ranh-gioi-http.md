---
status: accepted
---

# Kiểm thử hành vi ở ranh giới HTTP của site đang chạy

Bài kiểm thử đóng vai khách hoặc nhân viên: mở trang, gửi form, đọc nội dung trả về. Không gọi thẳng hàm PHP và không khẳng định bằng cách đọc bảng.

Lý do: spec đã chốt một điểm kiểm thử duy nhất là site đang chạy. Test gắn vào hàm nội bộ sẽ gãy khi đổi cách viết mà hành vi vẫn đúng. Bộ `tests/specs` và các flow trong `e2e/flows.md` đều đi qua HTTP.
