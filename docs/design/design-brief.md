# Bản mô tả thiết kế: website bán hàng điện máy

Trạng thái: **chờ chủ cửa hàng duyệt**. Chưa dựng theme khi bản này chưa được chấp thuận.
Ngày: 15/11/2026
Thuật ngữ theo `GLOSSARY.md`. Spec: `docs/specs/website-ban-hang-dien-may.md`.

Tệp đi kèm trong thư mục này:

| Tệp | Nội dung |
|---|---|
| [wireframes.html](wireframes.html) | Bố cục mức khối của 5 trang, mỗi trang bản máy tính và điện thoại, có ghi block nào ở đâu |
| [components.html](components.html) | Mẫu trực quan: logo, bảng màu, thang chữ, thẻ sản phẩm, nút, ô nhập, nhãn |
| [logo-sang-nha-nen-sang.svg](logo-sang-nha-nen-sang.svg) | Logo cho nền sáng |
| [logo-sang-nha-nen-toi.svg](logo-sang-nha-nen-toi.svg) | Logo cho nền tối (nền trong suốt, đặt trên nền tối mới thấy) |
| [logo-sang-nha-bieu-tuong.svg](logo-sang-nha-bieu-tuong.svg) | Biểu tượng riêng (favicon, ảnh đại diện) |
| [logo-sang-nha-xem-truoc.svg](logo-sang-nha-xem-truoc.svg) | Ảnh xem trước hai bản logo cạnh nhau |

Mở hai tệp HTML bằng trình duyệt. Chúng tự chứa, chỉ tải phông chữ từ Google Fonts; không có mạng thì dùng phông hệ thống.

![Xem trước logo](logo-sang-nha-xem-truoc.svg)

## 1. Cần chủ cửa hàng quyết định

| # | Việc cần chọn | Các lựa chọn | Đề xuất chính |
|---|---|---|---|
| 1 | Tên cửa hàng | Sáng Nhà / Nhịp Điện / Đom Đóm (mục 3) | **Sáng Nhà** |
| 2 | Bảng màu | A: xanh cổ vịt + hồng sen / B: chàm + cam đất (mục 5) | **Phương án A** |
| 3 | Logo | Chấp thuận biểu tượng "ngôi nhà có tia sáng" + chữ, hoặc yêu cầu sửa (mục 4) | Chấp thuận bản hiện tại |
| 4 | Kiểu chữ | Be Vietnam Pro cho toàn site (mục 6) | Chấp thuận |
| 5 | Bố cục 5 trang | Chấp thuận hoặc ghi chỗ cần sửa trên từng trang (mục 8) | Chấp thuận |
| 6 | Thanh đáy trên điện thoại (Trang chủ, Ngành hàng, Flash sale, Giỏ hàng, Tài khoản) | Có / không | Có |
| 7 | Nhãn "Sắp hết hàng" cho khách | Hiện / không hiện; nếu hiện thì dùng ngưỡng nào | Hiện, dùng chung ngưỡng với danh sách sắp hết hàng của nhân viên |

Việc chủ cửa hàng nên tự làm trước khi chốt tên (bên triển khai không làm thay được): tra cứu nhãn hiệu tại Cục Sở hữu trí tuệ, tra tên doanh nghiệp trên Cổng thông tin quốc gia về đăng ký doanh nghiệp, và kiểm tra tình trạng tên miền tại nhà đăng ký.

Nếu chọn tên khác "Sáng Nhà" hoặc bảng màu B: logo và hai tệp HTML sẽ được làm lại theo lựa chọn đó trước khi dựng theme.

**Đã duyệt ngày 15/11/2026:** chủ cửa hàng chọn tên **Sáng Nhà** và bảng màu **phương án A**. Các mục 3 đến 7 chưa được nhắc riêng nên dùng theo đề xuất chính; việc tra cứu nhãn hiệu, tên doanh nghiệp và tên miền vẫn chưa làm.

## 2. Nguyên tắc

- Thiết kế riêng. Bố cục theo các quy ước chung của bán lẻ trực tuyến (ô tìm kiếm ở đầu trang, cây ngành hàng bên trái, bộ lọc bên trái lưới sản phẩm, tóm tắt đơn bên phải). Không lấy tên, logo, màu nhận diện, hình ảnh, câu chữ hay bố cục đặc trưng của trang nào.
- Điện thoại trước: mọi trang thiết kế cho một cột rồi mới mở rộng.
- Giá và tình trạng hàng luôn rõ: giá bán, giá gốc, mức giảm và nhãn tồn kho có vị trí cố định trên mọi thẻ và trang.
- Màu nhấn chỉ dành cho khuyến mãi và flash sale. Nút mua hàng dùng màu chính, để khuyến mãi không lấn át hành động mua.
- Đọc được với mọi người: chữ thường đạt tương phản 4,5:1 trở lên, vùng bấm tối thiểu 44 x 44px, màu không phải tín hiệu duy nhất, tôn trọng thiết lập giảm chuyển động.
- Không bịa thông tin: liên hệ để dạng `[Địa chỉ]`, `[Số điện thoại]`, `[Email]`; cam kết dịch vụ để `[Cam kết 1]`... cho chủ cửa hàng điền; tên sản phẩm trong bản vẽ là tên chung, giá là giá minh hoạ.

## 3. Tên cửa hàng

| Phương án | Ý nghĩa | Điểm mạnh | Điểm cần cân nhắc |
|---|---|---|---|
| **Sáng Nhà** (đề xuất chính) | Đồ điện làm sáng căn nhà; "sáng" còn mang nghĩa lựa chọn sáng suốt | Hai âm tiết, thuần Việt, dễ đọc dễ nhớ; gắn với gia đình, hợp cả điện tử lẫn gia dụng; viết không dấu `sangnha` vẫn rõ | Dễ bị hiểu là cửa hàng đèn chiếu sáng nếu đứng một mình, nên logo luôn kèm dòng "ĐIỆN MÁY" |
| Nhịp Điện | Nhịp sống chạy bằng điện, gợi sự năng động | Nói thẳng ngành hàng; khác biệt | Hơi kỹ thuật, kém ấm áp; "điện" dễ gợi thiết bị điện công nghiệp |
| Đom Đóm | Đốm sáng nhỏ thân thuộc | Rất dễ nhớ, có sẵn hình ảnh làm biểu tượng | Từ rất phổ biến, đã có nhiều bên dùng ở ngành khác; hai tên miền `domdom.vn` và `domdom.com.vn` đang có chủ; nghe thiên về đồ trẻ em |

### Đã kiểm thế nào (ngày 15/11/2026)

- Tìm kiếm web cụm chính xác trong ngoặc kép: "điện máy Sáng Nhà", "điện máy Đom Đóm", "Nhịp Điện" kèm "cửa hàng điện máy", và từng tên kèm từ "thương hiệu" cùng dạng tên miền. Không kết quả nào cho thấy một nhà bán lẻ hay thương hiệu điện máy đang hoạt động mang ba tên này.
- Tra bản ghi DNS của tên miền: `sangnha.vn`, `sangnha.com`, `sangnha.com.vn`, `dienmaysangnha.vn`, `dienmaysangnha.com`, `nhipdien.vn`, `nhipdien.com`, `dienmaynhipdien.vn`, `dienmaydomdom.vn` không có bản ghi; `domdom.vn` và `domdom.com.vn` có bản ghi, tức đang có người dùng (chưa mở xem họ kinh doanh gì).
- Một phương án ban đầu là "Tầm An" đã bị loại vì kết quả tìm kiếm cho thấy có đơn vị điện máy tên gần giống ("An Tâm").

### Điều chưa chắc chắn

- **Chưa tra cứu đăng ký nhãn hiệu.** Bản này không khẳng định gì về việc ba tên trên đã hay chưa được đăng ký nhãn hiệu.
- Chưa tra tên doanh nghiệp đã đăng ký, chưa tìm trên mạng xã hội, bản đồ và các sàn thương mại điện tử. Cửa hàng nhỏ ở địa phương không có website có thể không hiện trong kết quả tìm kiếm.
- Tên miền không có bản ghi DNS vẫn có thể đã được đăng ký; chưa tra WHOIS.
- Kết quả tìm kiếm chỉ phản ánh thời điểm kiểm.

## 4. Logo

Gồm biểu tượng và chữ:

- **Biểu tượng**: hình ngôi nhà bo góc, khoét một tia sáng bốn cánh ở giữa. Nhà là "Nhà", tia sáng là "Sáng" và cũng gợi điện. Một màu, không chuyển sắc, nhận ra được ở cỡ 16px.
- **Chữ**: "Sáng Nhà" đậm, bên dưới là dòng "ĐIỆN MÁY" chữ hoa giãn cách. "Sáng" cùng màu với biểu tượng, "Nhà" màu chữ chính.
- **Phông**: Be Vietnam Pro (ExtraBold cho tên, Bold cho dòng mô tả), giấy phép SIL Open Font License 1.1, lấy từ Google Fonts. Chữ trong tệp SVG **đã chuyển thành path**, không cần cài phông để hiển thị đúng.
- Biểu tượng tự vẽ bằng path, không dùng hình có sẵn.

| Bản | Biểu tượng và "Sáng" | "Nhà" | "ĐIỆN MÁY" | Dùng trên |
|---|---|---|---|---|
| Nền sáng | `#0D6E6A` | `#14252A` | `#3F5257` | Trắng, neutral-50, primary-50 |
| Nền tối | `#6FD0C8` | `#FFFFFF` | `#C9D3D5` | secondary-900 (chân trang, thanh tiện ích) |

Quy tắc dùng:

- Khoảng trống quanh logo tối thiểu bằng nửa chiều cao biểu tượng.
- Chiều cao tối thiểu 28px cho bản đầy đủ; nhỏ hơn thì dùng biểu tượng riêng (tối thiểu 16px).
- Đầu trang máy tính: cao 44px. Đầu trang điện thoại: cao 32px. Chân trang: cao 40px, bản nền tối.
- Không đổi màu, không kéo méo, không thêm bóng đổ, không đặt bản nền sáng lên nền tối hay ngược lại.

## 5. Bảng màu

### Phương án A (đề xuất chính): xanh cổ vịt + hồng sen

Xanh cổ vịt đậm cho cảm giác tin cậy, sạch, hợp hàng công nghệ lẫn gia dụng. Hồng sen đủ nổi để báo khuyến mãi mà không phải đỏ hay vàng. Bảng màu tránh các màu chủ đạo thường gặp ở các nhà bán lẻ điện máy lớn trong nước: đỏ thuần, vàng thuần, cam và xanh dương tươi. Nhận định này dựa trên hiểu biết chung về thị trường, chưa đối chiếu mã màu của từng bên.

| Token | Mã | Dùng cho |
|---|---|---|
| `primary-700` | `#0A5754` | Nút chính khi rê chuột, chữ liên kết |
| `primary-600` | `#0D6E6A` | **Màu chính**: nút chính, logo, mục đang chọn |
| `primary-300` | `#6FD0C8` | Trên nền tối; viền tiêu điểm |
| `primary-100` | `#D5EDEA` | Nền nhấn nhẹ |
| `primary-50` | `#EAF6F4` | Nền mục đang chọn, nền block |
| `secondary-900` | `#102A30` | **Màu phụ** (xanh mực): thanh tiện ích, chân trang |
| `secondary-700` | `#1E4650` | Nền phụ trên vùng tối |
| `accent-700` | `#9C1249` | Chữ khuyến mãi trên nền nhạt, nút nhấn khi rê chuột |
| `accent-600` | `#C2185B` | **Màu nhấn** (hồng sen): mức giảm, flash sale, giá đang giảm |
| `accent-50` | `#FDEAF1` | Nền block flash sale, nền nhãn |
| `neutral-900` | `#14252A` | Chữ chính |
| `neutral-700` | `#3F5257` | Chữ phụ |
| `neutral-500` | `#62757A` | Chữ mờ, giá gốc gạch ngang, chữ gợi ý trong ô nhập |
| `neutral-400` | `#7C8D91` | Viền ô nhập |
| `neutral-300` | `#C9D3D5` | Đường kẻ, viền thẻ |
| `neutral-100` | `#EEF2F2` | Nền ô ảnh, nền vô hiệu |
| `neutral-50` | `#F6F8F8` | Nền trang |
| `neutral-0` | `#FFFFFF` | Nền thẻ, nền ô nhập |
| `success-700` / `success-50` | `#18703A` / `#E5F4EA` | Còn hàng, áp mã thành công, đã thanh toán |
| `warning-700` / `warning-50` | `#8A5300` / `#FFF3D9` | Sắp hết hàng, hết Suất flash sale, sắp hết hạn giữ hàng |
| `danger-700` / `danger-50` | `#B42318` / `#FDECEA` | Lỗi nhập liệu, vượt tồn kho, thanh toán thất bại |
| `info-700` / `info-50` | `#1D5C9E` / `#E7F0FA` | Thông tin trung tính, Cửa hàng còn hàng |
| `rating` | `#B86E00` | Hình ngôi sao đánh giá |

Màu đỏ chỉ xuất hiện ở trạng thái lỗi, không dùng làm màu nhận diện.

### Tương phản đã tính

Tính theo công thức độ chói tương đối của WCAG 2.1. Ngưỡng AA: 4,5:1 cho chữ thường; 3:1 cho chữ lớn và thành phần giao diện.

| Chữ / hình | Nền | Tỉ lệ | Kết quả |
|---|---|---|---|
| neutral-900 `#14252A` | trắng | 15,82:1 | Đạt AA |
| neutral-900 `#14252A` | neutral-50 `#F6F8F8` | 14,84:1 | Đạt AA |
| neutral-700 `#3F5257` | trắng | 8,22:1 | Đạt AA |
| neutral-700 `#3F5257` | neutral-50 | 7,71:1 | Đạt AA |
| neutral-700 `#3F5257` | neutral-100 `#EEF2F2` | 7,28:1 | Đạt AA |
| neutral-500 `#62757A` | trắng | 4,83:1 | Đạt AA |
| neutral-500 `#62757A` | neutral-50 | 4,53:1 | Đạt AA (sát ngưỡng) |
| neutral-500 `#62757A` | neutral-100 | 4,29:1 | **Không đạt cho chữ thường**: không đặt chữ mờ trên neutral-100, dùng neutral-700 |
| trắng | primary-600 `#0D6E6A` | 6,08:1 | Đạt AA |
| trắng | primary-700 `#0A5754` | 8,39:1 | Đạt AA |
| primary-600 `#0D6E6A` | primary-50 `#EAF6F4` | 5,49:1 | Đạt AA |
| primary-700 `#0A5754` | primary-50 | 7,58:1 | Đạt AA |
| primary-700 `#0A5754` | primary-100 `#D5EDEA` | 6,84:1 | Đạt AA |
| trắng | secondary-900 `#102A30` | 15,04:1 | Đạt AA |
| neutral-300 `#C9D3D5` | secondary-900 | 9,86:1 | Đạt AA |
| primary-300 `#6FD0C8` | secondary-900 | 8,26:1 | Đạt AA |
| trắng | secondary-700 `#1E4650` | 10,26:1 | Đạt AA |
| trắng | accent-600 `#C2185B` | 5,87:1 | Đạt AA |
| trắng | accent-700 `#9C1249` | 8,09:1 | Đạt AA |
| accent-600 `#C2185B` | accent-50 `#FDEAF1` | 5,09:1 | Đạt AA |
| accent-700 `#9C1249` | accent-50 | 7,02:1 | Đạt AA |
| success-700 `#18703A` | trắng / success-50 | 6,14:1 / 5,40:1 | Đạt AA |
| warning-700 `#8A5300` | trắng / warning-50 | 6,33:1 / 5,75:1 | Đạt AA |
| danger-700 `#B42318` | trắng / danger-50 | 6,57:1 / 5,75:1 | Đạt AA |
| info-700 `#1D5C9E` | trắng / info-50 | 6,83:1 / 5,93:1 | Đạt AA |
| neutral-400 `#7C8D91` (viền ô nhập) | trắng | 3,46:1 | Đạt 3:1 cho thành phần giao diện; không dùng làm màu chữ |
| rating `#B86E00` (hình sao) | trắng | 3,99:1 | Đạt 3:1 cho đồ hoạ; điểm số bằng chữ đi kèm dùng neutral-700 |

### Phương án B (dự phòng): chàm + cam đất

| Vai trò | Mã | Tương phản đã tính |
|---|---|---|
| Chính (chàm) | `#3B3A8F` | chữ trắng trên nền này 9,60:1 |
| Phụ (chàm đậm) | `#1F1E4D` | chữ trắng trên nền này 15,54:1 |
| Nhấn khuyến mãi (cam đất) | `#B8431A` | chữ trắng trên nền này 5,45:1; chữ màu này trên trắng 5,45:1 |

Trung tính và trạng thái giữ như phương án A. Cân nhắc: cam đất gần sắc đỏ cam hơn hồng sen, nên kém khác biệt với các trang bán lẻ khác so với phương án A. Nếu chọn B, các bậc nhạt và đậm còn lại sẽ được tính tương phản đầy đủ trước khi dựng theme.

## 6. Kiểu chữ

- **Một họ phông cho toàn site: Be Vietnam Pro** (Google Fonts, SIL OFL 1.1). Phông được thiết kế cho tiếng Việt, đủ bộ dấu, dấu chồng (ẵ, ễ, ỗ, ự) không chạm dòng trên. Dùng 4 độ đậm: 400, 500, 600, 700.
- Dự phòng: `"Be Vietnam Pro", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif`.
- Tải phông với `font-display: swap`, chỉ tập ký tự `vietnamese` và `latin`. Khi dựng theme có thể lưu phông tại chỗ thay vì tải từ Google.
- Giá và số lượng dùng số đều cột (`font-variant-numeric: tabular-nums`). Giá viết `8.990.000 ₫`.

| Tên | Máy tính (cỡ/dòng) | Điện thoại | Độ đậm | Dùng cho |
|---|---|---|---|---|
| display | 32/40 | 26/34 | 700 | Tiêu đề banner, trang đặc biệt |
| h1 | 24/32 | 20/28 | 700 | Tên sản phẩm, tên Ngành hàng |
| h2 | 20/28 | 18/26 | 600 | Tiêu đề block, tiêu đề mục |
| h3 | 16/24 | 16/24 | 600 | Tiêu đề nhóm, tiêu đề thẻ |
| body | 16/24 | 16/24 | 400 | Mô tả, nội dung, chữ trong ô nhập |
| body-sm | 14/21 | 14/21 | 400 hoặc 500 | Tên sản phẩm trên thẻ, nhãn ô nhập, bảng Thông số |
| caption | 12/18 | 12/18 | 400 hoặc 600 | Nhãn, chú thích, số đánh giá |
| price-lg | 24/32 | 22/30 | 700 | Giá ở trang chi tiết |
| price | 18/24 | 16/24 | 700 | Giá trên thẻ sản phẩm |

Không dùng chữ nhỏ hơn 12px. Đoạn văn dài tối đa khoảng 75 ký tự mỗi dòng.

## 7. Lưới, khoảng cách, hình khối

| Mục | Giá trị |
|---|---|
| Điểm ngắt | Điện thoại dưới 768px; máy tính bảng 768 đến 1023px; máy tính từ 1024px |
| Khung nội dung | Tối đa 1200px, canh giữa; lề 16px (điện thoại), 24px (từ 768px) |
| Lưới | 12 cột, khe 16px (điện thoại 12px) |
| Lưới sản phẩm | Điện thoại 2 cột; máy tính bảng 3 cột; máy tính 4 cột (trang Ngành hàng) hoặc 5 cột (block rộng hết khung) |
| Thang khoảng cách | 4, 8, 12, 16, 24, 32, 48, 64px |
| Bo góc | 6px (nút, ô nhập, nhãn); 10px (thẻ, block); tròn hẳn (viên bấm) |
| Bóng | Thẻ: `0 1px 2px rgba(16,42,48,.08)`; nổi (rê chuột, hộp xổ): `0 6px 18px rgba(16,42,48,.14)` |
| Chuyển động | 150 đến 200ms; tắt khi người dùng bật giảm chuyển động; banner trượt có nút tạm dừng |
| Vùng bấm | Tối thiểu 44 x 44px, cách nhau ít nhất 8px |
| Tiêu điểm bàn phím | Viền `primary-300` dày 3px, cách 2px |

Máy tính bảng dùng bố cục máy tính cho đầu trang và chân trang, nhưng bộ lọc mở dạng ngăn kéo và menu ngành hàng thu vào nút như trên điện thoại.

## 8. Bố cục các trang

Bản vẽ: [wireframes.html](wireframes.html). Trong bản vẽ, khối viền đứt màu xanh là block của module shops, có ghi số ticket dựng block đó (29 hoặc 30). Phần dưới đây tóm tắt và nêu lý do.

### 8.1 Khung chung

**Đầu trang máy tính**, từ trên xuống:

1. Thanh tiện ích (nền secondary-900): Hệ thống cửa hàng, Tra cứu bảo hành, Tin tức, `[Số điện thoại]`; bên phải là Đăng nhập / Đăng ký (mở hộp thoại).
2. Hàng chính: Logo | block **Ô tìm kiếm có gợi ý** | block **Tra cứu đơn hàng nhanh** (nút mở hộp nhỏ) | Tài khoản, Yêu thích | block **Giỏ hàng thu nhỏ có xem nhanh**.
3. Thanh điều hướng (dính khi cuộn): nút "Ngành hàng" mở block **Menu ngành hàng nhiều cấp**, rồi Flash sale, Trả góp, Thu cũ đổi mới, Hệ thống cửa hàng, Tin tức.

**Đầu trang điện thoại** (dính): nút mở ngăn kéo, Logo, Giỏ hàng thu nhỏ; dòng dưới là Ô tìm kiếm có gợi ý. Ngăn kéo trái chứa Menu ngành hàng nhiều cấp, Tra cứu đơn hàng nhanh, Hệ thống cửa hàng, Đăng nhập.

**Thanh đáy điện thoại** (dính, phần của theme): Trang chủ, Ngành hàng, Flash sale, Giỏ hàng, Tài khoản. Ở trang chi tiết, giỏ hàng và đặt hàng, thanh này nhường chỗ cho thanh hành động của trang (mua, đặt hàng).

**Chân trang** (nền secondary-900, logo bản nền tối): `[Địa chỉ]`, `[Số điện thoại]`, `[Email]`; nhóm Hỗ trợ; nhóm Chính sách; Hệ thống cửa hàng và các cách thanh toán được nhận. Trên điện thoại các nhóm thu gọn, bấm để mở.

**Thanh so sánh nổi** (block): dính đáy màn hình ở mọi trang có thẻ sản phẩm, chỉ hiện khi đã chọn ít nhất một sản phẩm.

### 8.2 Vị trí 16 block

| Block | Ticket | Trang chủ | Ngành hàng | Chi tiết | Giỏ hàng | Đặt hàng |
|---|---|---|---|---|---|---|
| Flash sale có đếm ngược | 29 | Dưới dải cam kết, rộng hết khung | | | | |
| Menu ngành hàng nhiều cấp | 29 | Cột trái 3/12, mở sẵn (điện thoại: ngăn kéo) | Trong nút "Ngành hàng" | Trong nút "Ngành hàng" | Trong nút "Ngành hàng" | |
| Sản phẩm theo ngành hàng dạng thẻ chuyển | 29 | Dưới flash sale | | | | |
| Bán chạy | 29 | Dưới banner dải ngang | | | | |
| Mới về | 29 | Dưới Bán chạy | | | | |
| Giảm giá nhiều nhất | 29 | Dưới Mới về | | | | |
| Thương hiệu nổi bật | 29 | Dưới Giảm giá nhiều nhất | | | | |
| Banner khuyến mãi | 29 | Ba vị trí: chính 6/12 cạnh menu, hai ô phụ 3/12, dải ngang giữa trang | | | | |
| Bộ lọc | 30 | | Cột trái 3/12 (điện thoại, máy tính bảng: ngăn kéo từ đáy) | | | |
| Sản phẩm đã xem gần đây | 30 | Gần cuối trang | Cuối trang | Cuối trang | Cuối trang | |
| Thường được mua cùng | 30 | | | Ngay dưới vùng mua hàng, rộng hết khung | Dưới danh sách dòng hàng | |
| Thanh so sánh nổi | 30 | Dính đáy | Dính đáy | Dính đáy | | |
| Cửa hàng còn hàng | 30 | | | Cột phải 3/12 (điện thoại: dòng thu gọn dưới chọn Biến thể) | | |
| Giỏ hàng thu nhỏ có xem nhanh | 30 | Đầu trang | Đầu trang | Đầu trang | Đầu trang, chỉ hiện số | |
| Ô tìm kiếm có gợi ý | 30 | Đầu trang | Đầu trang | Đầu trang | Đầu trang | |
| Tra cứu đơn hàng nhanh | 30 | Đầu trang (điện thoại: trong ngăn kéo) | Đầu trang | Đầu trang | Đầu trang | |

Trang đặt hàng cố ý không có block nào: đầu trang rút gọn, không gợi ý sản phẩm, để khách đi hết luồng.

### 8.3 Trang chủ

Thứ tự từ trên xuống trên máy tính: đầu trang; hàng đầu gồm Menu ngành hàng (3/12), Banner chính (6/12), hai Banner phụ (3/12); dải cam kết dịch vụ; Flash sale; Sản phẩm theo ngành hàng dạng thẻ chuyển; Banner dải ngang; Bán chạy; Mới về; Giảm giá nhiều nhất; Thương hiệu nổi bật; Đã xem gần đây; Tin tức; chân trang.

Lý do: màn hình đầu tiên đủ ba đường vào hàng (tìm kiếm, cây ngành hàng, chương trình đang chạy). Flash sale đặt ngay sau vì có giới hạn thời gian. Các block gợi ý xen kẽ dạng lưới và dạng trượt để trang không đều đều.

Điện thoại: một cột theo cùng thứ tự; menu vào ngăn kéo; có thêm dải biểu tượng Ngành hàng cấp 1 trượt ngang; block dạng trượt thấy 2 thẻ rưỡi để gợi ý còn nội dung; block dạng lưới 2 cột.

Block không có dữ liệu (không có Đợt flash sale đang chạy, khách chưa xem sản phẩm nào) ẩn hẳn, không để khung trống.

### 8.4 Trang ngành hàng có bộ lọc

Máy tính: đường dẫn; tiêu đề Ngành hàng kèm số sản phẩm; hàng Ngành hàng cấp dưới và logo Thương hiệu để lọc nhanh; bên trái block Bộ lọc (3/12, dính theo); bên phải (9/12) là các bộ lọc đang chọn, hàng sắp xếp, lưới 4 cột, phân trang.

- Bộ lọc gồm Thương hiệu, khoảng giá, từng Thông số được đánh dấu dùng để lọc. Mỗi lựa chọn có số sản phẩm khớp; lựa chọn có 0 kết quả thì mờ và không bấm được.
- Sắp xếp: Bán chạy, Mới nhất, Giảm nhiều, Giá thấp đến cao, Giá cao đến thấp.
- Bộ lọc và sắp xếp nằm trong địa chỉ trang, gửi đường dẫn cho người khác thì thấy đúng kết quả.
- Dùng phân trang có số trang thay vì cuộn vô hạn: khách quay lại đúng chỗ và tới được chân trang.

Điện thoại: thanh công cụ dính gồm nút "Bộ lọc (số đang chọn)" và "Sắp xếp". Bộ lọc mở dạng **ngăn kéo từ đáy**, cao khoảng 85% màn hình; đáy ngăn kéo có "Xoá tất cả" và "Xem N sản phẩm", N cập nhật ngay khi chọn. Lưới 2 cột.

### 8.5 Trang chi tiết sản phẩm

Máy tính, vùng đầu chia ba cột:

- **Ảnh (5/12)**: ảnh chính tỉ lệ 1:1 và hàng ảnh nhỏ; ảnh đổi theo Biến thể.
- **Mua hàng (4/12)**: tên sản phẩm, Thương hiệu, điểm đánh giá; dải flash sale (đếm ngược, Suất còn lại) khi sản phẩm đang trong Đợt flash sale; giá bán, giá gốc, mức giảm; chọn Biến thể; nhãn tồn kho và số lượng; "Mua ngay" (nút chính) và "Thêm vào giỏ" (nút viền); Trả góp, Thu cũ đổi mới; So sánh, Yêu thích.
- **Bên phải (3/12)**: block Cửa hàng còn hàng theo Biến thể đang chọn; cam kết dịch vụ.

Chọn Biến thể:

- Mỗi nhóm lựa chọn (dung lượng, màu) là một hàng viên bấm. Viên dung lượng hiện kèm giá để so nhanh.
- Chọn xong thì giá, ảnh, nhãn tồn kho và block Cửa hàng còn hàng đổi theo, không tải lại trang.
- Biến thể hết hàng vẫn hiện: viền đứt, chữ gạch ngang, có chữ "hết hàng", không chọn mua được.
- Tổ hợp đang chọn hết hàng thì nút mua đổi thành "Hết hàng" và vô hiệu.

Phía dưới: block Thường được mua cùng; rồi hai cột gồm mô tả, đánh giá, hỏi đáp (8/12) và bảng Thông số rút gọn có nút "Xem đầy đủ thông số" (4/12, dính theo); sản phẩm tương tự; Đã xem gần đây.

Điện thoại: ảnh vuốt ngang; dải flash sale; tên; giá; chọn Biến thể; nhãn tồn kho; Cửa hàng còn hàng thu gọn một dòng; rồi các phần còn lại xếp dọc, phần dài thu gọn. **Thanh mua hàng dính đáy** (giỏ, "Thêm vào giỏ", "Mua ngay") luôn nhìn thấy.

### 8.6 Giỏ hàng

Máy tính: chỉ báo ba bước (Giỏ hàng, Thông tin đặt hàng, Hoàn tất); bên trái (8/12) là thanh ngưỡng miễn phí vận chuyển ("Mua thêm ... để được miễn phí vận chuyển") và danh sách dòng hàng; bên phải (4/12, dính theo) là ô Mã giảm giá, tạm tính, giảm giá, tổng tiền và nút "Tiến hành đặt hàng". Dưới cùng: Thường được mua cùng, Đã xem gần đây.

- Mỗi dòng hàng: ảnh, tên, Biến thể, nhãn flash sale nếu có, đơn giá, ô số lượng, thành tiền, nút xoá.
- Số lượng vượt tồn kho: báo ngay dưới dòng hàng đó, bằng chữ, màu lỗi.
- Mã giảm giá: kết quả hiện ngay dưới ô, là số tiền được giảm hoặc lý do mã không dùng được.
- Giỏ rỗng: thông báo và nút "Xem hàng".

Điện thoại: dòng hàng thành thẻ xếp dọc; thanh dính đáy gồm tổng tiền và nút "Tiến hành đặt hàng".

### 8.7 Đặt hàng

Một trang, không chia nhiều bước, để Khách vãng lai đặt nhanh. Đầu trang rút gọn: Logo, chỉ báo bước, `[Số điện thoại]`, liên kết về giỏ hàng.

Bên trái (7/12):

- **A. Người nhận**: họ tên, số điện thoại (bắt buộc); email không bắt buộc, dùng để nhận xác nhận. Khách vãng lai có lời mời đăng nhập để điền sẵn; Thành viên chọn từ sổ địa chỉ.
- **B. Cách nhận hàng**: hai thẻ chọn một. Giao tận nơi: tỉnh thành và phường xã chọn từ danh sách, địa chỉ cụ thể; phí vận chuyển hiện ngay. Nhận tại Cửa hàng: chọn Cửa hàng còn đủ hàng; phí 0 ₫.
- **C. Thanh toán**: thẻ chọn một, chỉ hiện cách đang bật: trả tiền khi nhận hàng, chuyển khoản bằng mã QR, VNPAY, MoMo, ZaloPay. Chọn Cổng thanh toán trực tuyến thì hiện thời hạn giữ hàng.
- **D. Ghi chú** (không bắt buộc).

Bên phải (5/12, dính theo): tóm tắt Đơn hàng gồm từng dòng hàng, Mã giảm giá, điểm tích luỹ (chỉ Thành viên), tạm tính, giảm giá, phí vận chuyển, tổng tiền, nút "Đặt hàng".

Hết Suất flash sale: hộp cảnh báo màu cảnh báo ngay trong tóm tắt, ghi rõ sản phẩm nào về giá thường, giá cũ gạch ngang và giá mới. Tổng tiền cập nhật trước khi khách bấm "Đặt hàng".

Điện thoại: tóm tắt Đơn hàng thu gọn một dòng ở đầu (số sản phẩm và tổng tiền), cảnh báo hết Suất luôn hiện; các mục A đến D xếp dọc; danh sách tỉnh thành, phường xã mở toàn màn hình có ô tìm; thanh dính đáy gồm tổng tiền và nút "Đặt hàng".

## 9. Thành phần lặp lại

Mẫu trực quan: [components.html](components.html).

### Thẻ sản phẩm

Thứ tự cố định từ trên xuống:

1. Ảnh tỉ lệ 1:1. Góc trên trái: nhãn flash sale và nhãn mức giảm. Góc trên phải: nút yêu thích.
2. Tên sản phẩm, tối đa 2 dòng, body-sm đậm 500.
3. **Giá bán**: đậm 700. Màu chữ chính khi giá thường; màu nhấn khi đang giảm hoặc flash sale. Sản phẩm nhiều Biến thể hiện giá thấp nhất, có chữ "Từ" đứng trước.
4. **Giá gốc** gạch ngang (neutral-500) và **mức giảm** dạng "−18%" (accent-700). Không giảm thì dòng này để trống, giữ chiều cao để các thẻ thẳng hàng.
5. Trong Đợt flash sale: thanh Suất và chữ "Còn 12/50 Suất".
6. **Điểm đánh giá**: hình sao, điểm trung bình, số lượt trong ngoặc. Chưa có thì ghi "Chưa có đánh giá".
7. Nhãn tồn kho; ô "So sánh" (ở trang Ngành hàng và kết quả tìm kiếm).

Thẻ hết hàng: ảnh mờ đi, tên và giá màu chữ phụ, nhãn "Hết hàng"; vẫn bấm vào xem được. Rê chuột: thẻ nổi lên, viền màu chính. Thẻ thu gọn (Đã xem gần đây): chỉ ảnh, tên, giá bán.

### Nút

| Loại | Hình thức | Dùng cho |
|---|---|---|
| Chính | Nền primary-600, chữ trắng | Một nút mỗi màn hình: Mua ngay, Tiến hành đặt hàng, Đặt hàng |
| Viền | Nền trắng, viền primary-600, chữ primary-700 | Thêm vào giỏ, hành động phụ |
| Nhấn | Nền accent-600, chữ trắng | Chỉ cho lời mời vào khuyến mãi (Xem flash sale) |
| Chữ | Không nền, chữ primary-700 | Xem tất cả, Xem thêm |
| Nguy hiểm | Viền và chữ danger-700 | Xoá, Huỷ đơn |

Cỡ: 44px (chuẩn), 52px (mua và đặt hàng), 36px (chỉ trên máy tính hoặc trong bảng). Trạng thái: thường, rê chuột, tiêu điểm, đang xử lý (vòng xoay, khoá bấm lặp), vô hiệu.

### Ô nhập

- Nhãn luôn nằm trên ô; dấu `*` cho ô bắt buộc.
- Cao 44px, chữ 16px, viền neutral-400 dày 1,5px, bo 6px.
- Tiêu điểm: viền primary-600 và vòng primary-300. Lỗi: viền danger-700 và dòng báo lỗi bằng chữ ngay dưới ô. Vô hiệu: nền neutral-100.
- Các dạng: ô chữ, danh sách chọn, ô kèm nút (Mã giảm giá), ô số lượng có nút trừ và cộng, thẻ chọn một (cách nhận hàng, cách thanh toán), viên chọn Biến thể.
- Số điện thoại mở bàn phím số trên điện thoại.

### Nhãn trạng thái tồn kho

| Nhãn | Màu chữ / nền | Khi nào |
|---|---|---|
| Còn hàng | success-700 / success-50 | Tổng tồn lớn hơn 0 |
| Sắp hết hàng | warning-700 / warning-50 | Tồn thấp hơn ngưỡng (chờ quyết định số 7 ở mục 1) |
| Hết hàng | neutral-700 / neutral-100 | Tổng tồn bằng 0 |
| Còn hàng tại N Cửa hàng | info-700 / info-50 | Trang chi tiết, theo Biến thể đang chọn |

Nhãn luôn có chữ, không chỉ dựa vào màu.

### Nhãn khuyến mãi

- Mức giảm: nền accent-600, chữ trắng, dạng "−18%".
- Flash sale: nền accent-50, viền accent-600, chữ accent-700, có biểu tượng tia chớp.
- Mới: nền primary-50, viền primary-600, chữ primary-700.
- Đồng hồ đếm ngược: ba ô giờ, phút, giây, số đều cột; có nhãn đọc được cho trình đọc màn hình.

## 10. Ghi chú cho người dựng block và theme

Những điểm bản thiết kế này cần ở block. Nếu ticket 29, 30 không đáp ứng thì báo lại để sửa thiết kế, không tự bỏ:

- Banner khuyến mãi được đặt nhiều lần trên một trang, mỗi vị trí một nhóm ảnh và tỉ lệ ảnh riêng (2:1, 6:1; điện thoại 2:1, 3:1).
- Menu ngành hàng nhiều cấp có hai kiểu hiện: mở sẵn (trang chủ) và xổ từ nút (các trang khác, ngăn kéo trên điện thoại).
- Giỏ hàng thu nhỏ tắt xem nhanh khi đang ở trang giỏ hàng.
- Cửa hàng còn hàng nghe được sự kiện đổi Biến thể ở trang chi tiết.
- Bộ lọc hiện được ở hai dạng: cột bên và ngăn kéo.
- Dải biểu tượng Ngành hàng cấp 1 trên trang chủ điện thoại và thanh đáy là phần của theme, chỉ là liên kết, không chứa nghiệp vụ.

Theme không chứa nghiệp vụ; mọi giá, tồn kho, Suất đến từ module và block.

## 11. Ngoài phạm vi bản này

- Các trang khác của ticket 33 (thanh toán, tra cứu đơn, tài khoản, flash sale, so sánh, hệ thống cửa hàng, trả góp, thu cũ đổi mới, tin tức) dùng lại khung chung, lưới và thành phần ở trên; chưa có bản vẽ riêng.
- Hộp thoại đăng nhập (ticket 34).
- Ảnh banner, ảnh sản phẩm, nội dung cam kết và chính sách: do chủ cửa hàng cung cấp hoặc dùng dữ liệu mẫu.
