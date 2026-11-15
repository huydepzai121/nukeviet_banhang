# Hệ thống thiết kế Sáng Nhà

Tham chiếu: trang bán lẻ điện máy hiện đại (khung trắng, ảnh sản phẩm lớn nền sáng, giá rõ, badge giảm giá, đầu trang dính) và bản màu đã duyệt: xanh cổ vịt + hồng sen. Không dùng đỏ/vàng nhận diện của các chuỗi lớn, không dùng giao diện Bootstrap mặc định của NukeViet.

## Màu

| Token | Mã | Dùng cho |
| --- | --- | --- |
| primary | `#0D6E6A` | Nút chính, liên kết, logo |
| primary-dark | `#0A5754` | Nút khi nhấn |
| ink | `#14252A` | Chữ chính, giá bán |
| muted | `#5C6E73` | Chữ phụ |
| line | `#E6EEEE` | Viền thẻ, ô nhập |
| paper | `#F5F7F7` | Nền trang |
| card | `#FFFFFF` | Thẻ, đầu trang |
| sale | `#C2185B` | Giá đang giảm, badge |
| sale-soft | `#FDEAF1` | Nền flash sale |
| foot | `#102A30` | Chân trang |

## Chữ

Be Vietnam Pro, tải kèm trong theme (`css/custom.css` qua Google Fonts, dự phòng system-ui). Cỡ: tên sản phẩm thẻ 14/20, giá 18/700, tiêu đề trang 28/700. Số tiền dùng tabular-nums.

## Hình khối

- Khoảng cách: 4, 8, 12, 16, 24, 32, 48.
- Bo góc: nút 10px, thẻ 16px, ảnh 12px, ô tìm kiếm viên thuốc.
- Bóng thẻ: `0 1px 2px rgba(16,42,48,.06)`. Khi rê: `0 12px 28px rgba(16,42,48,.10)`.
- Ảnh sản phẩm tỉ lệ 1:1, nền `#F3F5F5`, `object-fit: contain`.
- Nút chính đặc xanh. Nút phụ viền xanh, nền trắng. Không dùng nút đỏ/xanh lá của Bootstrap.

## Thành phần

- Đầu trang dính: logo, tìm kiếm, yêu thích, giỏ có số.
- Menu ngành hàng mở panel.
- Banner ảnh thật, không khối màu đặc.
- Thẻ sản phẩm: ảnh, badge giảm, tên 2 dòng, giá và giá gạch, không nút “Đặt hàng” chiếm nửa thẻ.
- Trang chi tiết: ảnh lớn, tên, giá, số lượng, hai nút mua. Ẩn ngày đăng, khối Tweet, mã CMS.
- Giỏ và đặt hàng: nền trắng, bảng không kẻ ô Bootstrap.
- Điện thoại: tìm kiếm full hàng, thanh đáy 5 mục, lưới 2 cột.
