# E2E flows

Site: `http://127.0.0.1:8080` sau khi cài NukeViet, cài module shops và chạy `php tools/seed-sangnha.php`.
Tài khoản quản trị nằm trong `.env` (`NV_ADMIN_USER`, `NV_ADMIN_PASS`).
Thành viên mẫu: `khach001` / `Mau123a!`.
Chạy: `node e2e/run.mjs` (Chrome headless). Ảnh lưu ở thư mục artifacts `e2e/`.

## home
1. Mở `/index.php/shops/`.
- Expected: tiêu đề cửa hàng, nhiều sản phẩm, chữ "Dữ liệu mẫu", giá có `đ`, liên kết phân trang `page-2`.

## category
1. Mở `/index.php/shops/dien-lanh/`.
- Expected: chữ "Điện lạnh", có ngành con Tủ lạnh, Máy giặt hoặc Máy lạnh, và sản phẩm.

## category-page
1. Mở `/index.php/shops/tu-lanh/page-2/`.
- Expected: chữ "Tủ lạnh" và vẫn còn sản phẩm (phân trang).

## product
1. Mở sản phẩm mốc `/index.php/shops/smartphone/samsung-galaxy-a15-128gb.html`.
- Expected: "Samsung Galaxy A15 128GB", "Dữ liệu mẫu", giá, nút thêm vào giỏ.

## search
1. Mở `/index.php/shops/search/?q=Galaxy+A15`.
- Expected: kết quả có "Galaxy A15", không bị đẩy về trang không còn từ khóa.

## cart
1. Ở trang sản phẩm mốc, bấm nút thêm vào giỏ.
2. Mở `/index.php/shops/cart/`.
- Expected: giỏ có "Samsung Galaxy A15 128GB".

## checkout
1. Từ giỏ, gửi form đặt hàng.
- Expected: trang đặt hàng có họ tên, điện thoại, địa chỉ và tổng tiền.

## order-confirmation
1. Điền Nguyễn Văn An, an.nguyen@example.com, 0912345678, địa chỉ 12 Lê Lợi, Quận 1, TP. Hồ Chí Minh, đồng ý điều khoản, gửi.
- Expected: trang đơn có tên khách, số điện thoại và mã đơn dạng S kèm chữ số.

## admin-products
1. Đăng nhập quản trị.
2. Mở danh sách sản phẩm shops.
- Expected: nhiều dòng sản phẩm, có phân trang hoặc hơn một trang dữ liệu, có "Galaxy" hoặc "Tủ lạnh".

## admin-product-form
1. Mở form thêm sản phẩm.
- Expected: form có ô tiêu đề và giá.

## admin-product-edit
1. Từ danh sách, mở một sản phẩm có sẵn để sửa.
- Expected: form sửa hiện tên sản phẩm đã lưu.

## admin-orders
1. Mở danh sách đơn hàng.
- Expected: nhiều đơn, có mã S, có lọc hoặc phân trang.

## admin-order-detail
1. Mở đơn đầu tiên trong danh sách.
- Expected: tên người nhận, điện thoại và tên sản phẩm trong đơn.

## admin-categories
1. Mở danh sách ngành hàng.
- Expected: "Điện thoại", "Điện lạnh", "Tủ lạnh".

## flashsale
1. Mở `/index.php/shops/flashsale/`.
- Expected: có "Flash sale" và chữ "suất".

## stores
1. Mở `/index.php/shops/stores/`.
- Expected: có "Sáng Nhà Quận 1".

## brand
1. Mở `/index.php/shops/brand/samsung/`.
- Expected: có "Samsung".

## search-plain
1. Mở tìm kiếm `q=tu lanh`.
- Expected: ra "Tủ lạnh" dù gõ không dấu.

## warranty
1. Mở `/index.php/shops/warranty/`.
- Expected: form "Tra cứu".

## installment
1. Mở `/index.php/shops/installment/`.
- Expected: có "Ước tính".

## viewports
Sau các flow, chụp trang chủ, ngành hàng, chi tiết, flash sale, cửa hàng, thương hiệu, tìm kiếm, giỏ, bảo hành, trả góp, thu cũ, quản trị đơn, quản trị sản phẩm và báo cáo ở bề rộng 1366, 768 và 390.
