# Spec: Website bán hàng điện máy trên NukeViet 4.6 với module shops hoàn chỉnh

Trạng thái: sẵn sàng triển khai (`ready-for-agent`)
Ngày: 15/11/2026
Thuật ngữ: xem `GLOSSARY.md`. Quyết định nền: xem ADR 0001, 0002, 0003.

## Problem Statement

Chủ cửa hàng muốn mở một website bán lẻ điện máy chạy trên NukeViet, có trải nghiệm ngang các trang bán lẻ lớn trong nước. Module bán hàng duy nhất của NukeViet là shops, nhưng:

- Gói shops trên kho store là bản cho NukeViet 4.5, cài lên bản mới thì lỗi, và còn nhiều lỗi nghiệp vụ tồn đọng (đặt hàng, in đơn, tìm kiếm).
- Shops thiếu những thứ một trang điện máy bắt buộc phải có: cổng thanh toán trong nước, biến thể có giá và tồn kho riêng, flash sale, tồn kho theo cửa hàng, trả góp, lọc theo thông số.
- Số block đi kèm ít, không đủ để dựng trang chủ và trang ngành hàng theo kiểu bán lẻ điện máy.
- Giao diện mặc định không phù hợp bán lẻ điện máy.

Chủ cửa hàng cũng muốn mang module đã hoàn thiện sang các site NukeViet khác bằng chức năng xuất module có sẵn.

## Solution

Một website chạy trong Docker, trên NukeViet 4.6.03, gồm bốn phần giao riêng:

1. **Module shops hoàn chỉnh**: phát triển tiếp từ mã nhánh develop của kho chính thức, sửa hết lỗi, bổ sung tính năng còn thiếu, các cổng thanh toán và 16 block mới. Xuất được thành gói và cài được lên site NukeViet 4.6 khác.
2. **Theme riêng**: thiết kế riêng, tham khảo bố cục và luồng mua hàng của nhiều trang bán lẻ, không sao chép thương hiệu, hình ảnh hay nội dung của trang nào. Tên cửa hàng, logo và bảng màu do bên triển khai đề xuất, chủ cửa hàng duyệt trước khi dựng.
3. **Cải tiến đăng nhập**: dựa trên hệ thống thành viên của NukeViet.
4. **Dữ liệu sản phẩm mẫu**: đủ cây ngành hàng điện máy, nạp bằng một bước riêng.

## User Stories

### Duyệt hàng

1. Là khách, tôi muốn thấy menu ngành hàng nhiều cấp kèm thương hiệu, để đi tới nhóm hàng cần tìm trong một lần bấm.
2. Là khách, tôi muốn trang chủ có banner khuyến mãi, để biết chương trình đang diễn ra.
3. Là khách, tôi muốn trang chủ hiện sản phẩm theo từng ngành hàng dạng thẻ chuyển, để xem nhanh nhiều ngành mà không rời trang.
4. Là khách, tôi muốn thấy sản phẩm bán chạy, mới về và giảm giá nhiều nhất, để có gợi ý khi chưa biết mua gì.
5. Là khách, tôi muốn thấy các thương hiệu nổi bật, để vào thẳng trang hàng của thương hiệu tôi tin dùng.
6. Là khách, tôi muốn lọc sản phẩm trong một ngành hàng theo thương hiệu, khoảng giá và thông số, để thu hẹp còn vài lựa chọn phù hợp.
7. Là khách, tôi muốn thấy số sản phẩm khớp với từng lựa chọn lọc, để không chọn phải bộ lọc cho kết quả rỗng.
8. Là khách, tôi muốn sắp xếp theo giá, mức giảm, độ mới và lượt bán, để xem thứ tôi quan tâm trước.
9. Là khách, tôi muốn bộ lọc đang chọn nằm trong địa chỉ trang, để gửi đường dẫn cho người khác xem đúng kết quả đó.
10. Là khách, tôi muốn ô tìm kiếm gợi ý sản phẩm khi đang gõ, để tới đúng sản phẩm mà không cần gõ hết tên.
11. Là khách, tôi muốn tìm kiếm không phân biệt dấu tiếng Việt, để gõ không dấu vẫn ra kết quả.
12. Là khách dùng điện thoại, tôi muốn mọi trang dùng tốt trên màn hình nhỏ, để mua hàng được bằng điện thoại.

### Xem sản phẩm

13. Là khách, tôi muốn xem thư viện ảnh của sản phẩm, để nhìn rõ hàng trước khi mua.
14. Là khách, tôi muốn chọn biến thể theo màu và dung lượng trên cùng một trang, để so giá các lựa chọn của cùng một mẫu máy.
15. Là khách, tôi muốn giá, ảnh và tình trạng còn hàng đổi theo biến thể tôi chọn, để biết chính xác thứ tôi sắp mua.
16. Là khách, tôi muốn biến thể hết hàng vẫn hiện nhưng không chọn mua được, để biết mẫu đó có tồn tại.
17. Là khách, tôi muốn xem bảng thông số đầy đủ, để đánh giá sản phẩm.
18. Là khách, tôi muốn thấy giá gốc, giá bán và mức giảm, để biết mình tiết kiệm bao nhiêu.
19. Là khách, tôi muốn biết cửa hàng nào còn hàng cho biến thể tôi chọn, để tới xem hoặc nhận tại đó.
20. Là khách, tôi muốn đọc đánh giá và điểm trung bình, để tham khảo người đã mua.
21. Là thành viên đã mua sản phẩm, tôi muốn viết đánh giá kèm số sao, để chia sẻ trải nghiệm.
22. Là khách, tôi muốn đặt câu hỏi về sản phẩm và đọc câu trả lời của cửa hàng, để giải toả thắc mắc trước khi mua.
23. Là khách, tôi muốn thấy sản phẩm tương tự và sản phẩm thường được mua cùng, để cân nhắc thêm lựa chọn và phụ kiện.
24. Là khách, tôi muốn thấy lại các sản phẩm đã xem gần đây, để quay lại mà không phải tìm lại.
25. Là khách, tôi muốn thêm tối đa bốn sản phẩm vào thanh so sánh, để đặt các thông số cạnh nhau.
26. Là khách, tôi muốn bảng so sánh làm nổi các dòng thông số khác nhau, để thấy ngay điểm khác biệt.
27. Là thành viên, tôi muốn lưu sản phẩm vào danh sách yêu thích, để mua sau.

### Flash sale

28. Là khách, tôi muốn thấy đợt flash sale đang diễn ra kèm đồng hồ đếm ngược, để biết còn bao lâu.
29. Là khách, tôi muốn thấy số suất còn lại của từng sản phẩm flash sale, để quyết định nhanh.
30. Là khách, tôi muốn thấy các đợt flash sale sắp tới, để quay lại đúng giờ.
31. Là khách, tôi muốn được báo rõ ở bước đặt hàng nếu suất flash sale đã hết và giá trở về giá thường, để không bị tính tiền khác với giá tôi tưởng.
32. Là chủ cửa hàng, tôi muốn mỗi số điện thoại chỉ mua được một suất cho mỗi sản phẩm trong một đợt, để nhiều khách được mua hơn.
33. Là nhân viên, tôi muốn tạo đợt flash sale với khung giờ, danh sách sản phẩm, giá và số suất riêng, để chạy chương trình mà không sửa giá gốc.
34. Là nhân viên, tôi muốn sản phẩm tự về giá thường khi hết giờ hoặc hết suất, để không phải canh giờ sửa tay.

### Giỏ hàng và đặt hàng

35. Là khách, tôi muốn thêm sản phẩm vào giỏ mà không rời trang, để tiếp tục xem hàng.
36. Là khách, tôi muốn xem nhanh giỏ hàng từ đầu trang, để biết mình đã chọn gì.
37. Là khách, tôi muốn đổi số lượng và bỏ sản phẩm trong giỏ, để chỉnh trước khi đặt.
38. Là khách, tôi muốn được báo khi số lượng tôi chọn vượt tồn kho, để không đặt thứ cửa hàng không có.
39. Là khách, tôi muốn nhập mã giảm giá và thấy ngay số tiền được giảm hoặc lý do mã không dùng được, để biết tổng tiền thật.
40. Là khách vãng lai, tôi muốn đặt hàng chỉ với họ tên, số điện thoại và địa chỉ, để mua mà không phải lập tài khoản.
41. Là khách, tôi muốn chọn địa chỉ theo tỉnh thành và phường xã từ danh sách, để không gõ sai.
42. Là khách, tôi muốn chọn giao tận nơi hoặc nhận tại cửa hàng, để nhận hàng theo cách tiện cho tôi.
43. Là khách, tôi muốn thấy phí vận chuyển trước khi xác nhận, để không bị bất ngờ về tổng tiền.
44. Là khách, tôi muốn được miễn phí vận chuyển khi đơn hàng đạt ngưỡng cửa hàng đặt ra, và thấy còn thiếu bao nhiêu để đạt ngưỡng.
45. Là khách, tôi muốn thấy bản tóm tắt đơn hàng gồm từng dòng hàng, giảm giá, phí và tổng tiền trước khi xác nhận.
46. Là khách, tôi muốn nhận email xác nhận sau khi đặt, để có bằng chứng và mã đơn.
47. Là thành viên, tôi muốn thông tin nhận hàng được điền sẵn từ sổ địa chỉ, để đặt hàng nhanh hơn.
48. Là thành viên, tôi muốn dùng điểm tích luỹ để trừ vào đơn hàng, để hưởng quyền lợi khách quen.

### Thanh toán

49. Là khách, tôi muốn chọn trả tiền khi nhận hàng, để không phải thanh toán trước.
50. Là khách, tôi muốn thanh toán chuyển khoản bằng mã QR có sẵn số tiền và nội dung, để không phải gõ tay.
51. Là khách, tôi muốn trang đơn hàng tự chuyển sang "đã thanh toán" khi tiền tới, để biết giao dịch thành công mà không cần gọi cửa hàng.
52. Là khách, tôi muốn thanh toán qua VNPAY, MoMo hoặc ZaloPay, để dùng ví hay thẻ tôi sẵn có.
53. Là khách, tôi muốn thấy thời hạn giữ hàng của đơn chờ thanh toán, để biết phải trả trước lúc nào.
54. Là khách, tôi muốn thanh toán lại một đơn còn hạn nếu lần trước thất bại, để không phải đặt lại từ đầu.
55. Là khách, tôi muốn được báo rõ khi thanh toán thất bại hoặc bị huỷ, để biết đơn chưa được trả tiền.
56. Là chủ cửa hàng, tôi muốn đơn chờ thanh toán tự huỷ và trả hàng về kho khi quá hạn, để hàng không bị giữ vô thời hạn.
57. Là nhân viên, tôi muốn đơn nhận tiền sau khi đã huỷ được đánh dấu cần xử lý, để tôi liên hệ khách hoặc hoàn tiền.
58. Là nhân viên, tôi muốn đơn chuyển khoản thiếu vẫn ở trạng thái chờ và ghi số đã nhận, để tôi biết còn thiếu bao nhiêu.
59. Là nhân viên, tôi muốn đơn chuyển khoản thừa được xác nhận và đánh dấu cần hoàn phần dư.
60. Là chủ cửa hàng, tôi muốn một thông báo thanh toán gửi lặp lại không làm đơn bị ghi nhận hai lần.
61. Là chủ cửa hàng, tôi muốn thông báo thanh toán sai chữ ký hoặc sai nguồn bị từ chối, để không ai giả mạo việc trả tiền.
62. Là quản trị, tôi muốn bật, tắt và nhập khoá cho từng cổng thanh toán trong trang quản trị, để không phải sửa mã.
63. Là quản trị, tôi muốn mỗi cổng thanh toán có chế độ thử nghiệm, để kiểm tra trước khi nhận tiền thật.
64. Là nhân viên, tôi muốn xem lịch sử giao dịch của từng đơn hàng, để đối soát.

### Sau khi mua

65. Là khách vãng lai, tôi muốn tra cứu đơn hàng bằng số điện thoại và mã đơn, để theo dõi mà không cần tài khoản.
66. Là thành viên, tôi muốn xem lịch sử đơn hàng và chi tiết từng đơn.
67. Là thành viên, tôi muốn các đơn tôi đặt lúc chưa đăng nhập bằng cùng số điện thoại xuất hiện trong tài khoản, để không mất lịch sử.
68. Là khách, tôi muốn huỷ đơn khi cửa hàng chưa xử lý, để đổi ý mà không phải gọi điện.
69. Là khách, tôi muốn nhận email khi đơn hàng đổi trạng thái.
70. Là khách, tôi muốn tra cứu bảo hành bằng số điện thoại hoặc số máy, để biết hạn bảo hành.
71. Là thành viên, tôi muốn được cộng điểm tích luỹ khi đơn hoàn tất và xem số điểm hiện có.

### Dịch vụ kèm theo

72. Là khách, tôi muốn ước tính khoản trả góp hằng tháng theo số tiền trả trước và kỳ hạn, để biết có đủ khả năng mua.
73. Là khách, tôi muốn gửi đăng ký trả góp cho một sản phẩm, để nhân viên liên hệ làm hồ sơ.
74. Là khách, tôi muốn ước tính giá thu máy cũ theo dòng máy và tình trạng, để biết cần bù bao nhiêu khi lên đời.
75. Là khách, tôi muốn gửi đăng ký thu cũ đổi mới, để nhân viên liên hệ thẩm định.
76. Là khách, tôi muốn xem danh sách cửa hàng theo tỉnh thành kèm địa chỉ, giờ mở cửa và bản đồ.
77. Là khách, tôi muốn đọc tin tức và bài tư vấn công nghệ, có liên kết tới sản phẩm được nhắc tới.

### Tài khoản

78. Là khách, tôi muốn đăng nhập và đăng ký trong hộp thoại ngay trên trang đang xem, để không mất chỗ đang đọc.
79. Là khách, tôi muốn đăng nhập bằng tài khoản Google, để không phải nhớ thêm mật khẩu.
80. Là thành viên, tôi muốn đăng nhập bằng số điện thoại và mật khẩu thay cho tên đăng nhập.
81. Là thành viên, tôi muốn quản lý sổ địa chỉ nhận hàng và chọn một địa chỉ mặc định.

### Quản trị hàng hoá

82. Là nhân viên, tôi muốn quản lý cây ngành hàng nhiều cấp, có ảnh và thứ tự.
83. Là nhân viên, tôi muốn quản lý thương hiệu có logo, để hiển thị và lọc theo thương hiệu.
84. Là nhân viên, tôi muốn định nghĩa bộ thông số cho từng ngành hàng, để sản phẩm cùng ngành nhập cùng một bộ và so sánh được.
85. Là nhân viên, tôi muốn chọn thông số nào được dùng làm bộ lọc.
86. Là nhân viên, tôi muốn tạo sản phẩm với nhiều biến thể, mỗi biến thể có mã hàng, giá, ảnh và tồn kho riêng.
87. Là nhân viên, tôi muốn nhập và xuất danh sách sản phẩm bằng bảng tính, để cập nhật hàng loạt.
88. Là nhân viên, tôi muốn đặt giá khuyến mãi có thời hạn cho sản phẩm hoặc cả ngành hàng.
89. Là nhân viên, tôi muốn tạo mã giảm giá có giới hạn lượt dùng, thời hạn, giá trị đơn tối thiểu và phạm vi sản phẩm áp dụng.
90. Là nhân viên, tôi muốn duyệt, ẩn và trả lời đánh giá cùng câu hỏi của khách.
91. Là nhân viên, tôi muốn khai báo các sản phẩm thường mua cùng cho một sản phẩm.

### Quản trị kho và cửa hàng

92. Là quản trị, tôi muốn quản lý danh sách cửa hàng với địa chỉ, toạ độ, giờ mở cửa và số điện thoại.
93. Là nhân viên, tôi muốn nhập và điều chỉnh tồn kho của từng biến thể tại từng cửa hàng, có ghi lý do.
94. Là nhân viên, tôi muốn xem nhật ký thay đổi tồn kho, để truy nguyên chênh lệch.
95. Là quản trị, tôi muốn chọn kho mặc định cho đơn giao tận nơi.
96. Là chủ cửa hàng, tôi muốn tồn kho được trừ đúng cửa hàng khi khách nhận tại cửa hàng, và trả lại khi đơn bị huỷ.
97. Là chủ cửa hàng, tôi muốn hai khách không cùng mua được món hàng cuối cùng, để không bán quá số có.
98. Là nhân viên, tôi muốn danh sách sản phẩm sắp hết hàng, để nhập thêm kịp lúc.

### Quản trị đơn hàng

99. Là nhân viên, tôi muốn danh sách đơn hàng lọc được theo trạng thái, ngày, cách thanh toán và cửa hàng.
100. Là nhân viên, tôi muốn chuyển trạng thái đơn hàng theo đúng trình tự (chờ thanh toán, chờ xử lý, đang giao, hoàn tất, đã huỷ) và có ghi chú.
101. Là nhân viên, tôi muốn in đơn hàng và phiếu giao hàng.
102. Là nhân viên, tôi muốn danh sách các đơn cần xử lý thủ công (tiền về sau khi huỷ, chuyển thừa, chuyển thiếu).
103. Là nhân viên, tôi muốn danh sách đăng ký trả góp và thu cũ đổi mới kèm trạng thái xử lý.
104. Là quản trị, tôi muốn cấu hình bảng phí vận chuyển theo tỉnh thành và ngưỡng miễn phí.
105. Là quản trị, tôi muốn cấu hình thời gian giữ hàng cho đơn chờ thanh toán.
106. Là chủ cửa hàng, tôi muốn báo cáo doanh thu theo ngày, ngành hàng và sản phẩm bán chạy.
107. Là quản trị, tôi muốn cấp quyền cho nhân viên theo nhóm chức năng (hàng hoá, đơn hàng, kho, cấu hình).

### Block và giao diện

108. Là quản trị, tôi muốn đặt các block của shops vào bất kỳ vị trí nào của theme bằng trang quản lý block của NukeViet.
109. Là quản trị, tôi muốn cấu hình từng block (số sản phẩm, ngành hàng nguồn, hiển thị dạng lưới hay trượt).
110. Là quản trị, tôi muốn có đủ 16 block mới: flash sale, menu ngành hàng, sản phẩm theo ngành hàng dạng thẻ chuyển, bán chạy, mới về, giảm giá nhiều nhất, thương hiệu nổi bật, banner khuyến mãi, bộ lọc, đã xem gần đây, thường mua cùng, thanh so sánh, cửa hàng còn hàng, giỏ hàng thu nhỏ, tìm kiếm có gợi ý, tra cứu đơn nhanh.
111. Là chủ cửa hàng, tôi muốn site có thương hiệu, logo và bảng màu riêng, không giống trang bán lẻ nào đang hoạt động.
112. Là chủ cửa hàng, tôi muốn trang sản phẩm có dữ liệu có cấu trúc cho máy tìm kiếm, để hiện giá và đánh giá trên kết quả tìm kiếm.

### Đóng gói và vận hành

113. Là quản trị, tôi muốn xuất module shops bằng chức năng đóng gói của NukeViet và cài gói đó lên một site NukeViet 4.6 khác mà không lỗi.
114. Là quản trị site khác, tôi muốn shops chạy đúng trên theme mặc định, để không phải cài theme riêng.
115. Là quản trị, tôi muốn gỡ module thì các bảng của nó được xoá sạch.
116. Là quản trị, tôi muốn xuất theme riêng thành gói theme.
117. Là người triển khai, tôi muốn dựng toàn bộ site bằng Docker Compose với một lệnh.
118. Là người triển khai, tôi muốn nạp dữ liệu sản phẩm mẫu bằng một bước riêng, và site vẫn chạy đúng khi không nạp.
119. Là quản trị, tôi muốn module hỗ trợ đủ tiếng Việt và tiếng Anh ở các chuỗi giao diện.

## Implementation Decisions

### Nền tảng và ranh giới

- Nền là NukeViet 4.6.03; chưa chuyển lên NukeViet 5 (ADR 0001).
- Module giữ tên `shops`, phát triển tiếp từ mã nhánh develop của kho chính thức, giữ cấu trúc bảng sẵn có và chỉ mở rộng thêm (ADR 0002).
- Không sửa mã lõi NukeViet và không sửa module khác để shops chạy được. Mọi thứ shops cần nằm trong các đường dẫn chuẩn của module để chức năng đóng gói gom đủ (ADR 0003).
- Phần viết mới tách xử lý nghiệp vụ khỏi lớp hiển thị: nghiệp vụ nằm trong các lớp thuộc không gian tên riêng của module, không phụ thuộc bộ dựng template; template chỉ nhận dữ liệu đã tính sẵn.
- Mã theo quy chuẩn NukeViet: dữ liệu vào chỉ lấy qua đối tượng yêu cầu của hệ thống, truy vấn dùng câu lệnh chuẩn bị sẵn, dữ liệu ra được mã hoá HTML, thao tác ghi có kiểm tra chống giả mạo yêu cầu, không ghi cứng tiền tố bảng.
- Việc đầu tiên sau khi cài là đọc mã chức năng đóng gói module của 4.6 để biết chính xác nó gom những thư mục nào; vị trí đặt template, tệp tĩnh và block mới theo kết quả đó.

### Các khối nghiệp vụ mới trong module shops

Mỗi khối là một module sâu, giao diện hẹp:

- **Biến thể**: một sản phẩm có nhiều biến thể, xác định bởi tổ hợp giá trị lựa chọn (màu, dung lượng). Mỗi biến thể có mã hàng, giá, ảnh và tồn kho riêng; dùng chung trang chi tiết, đánh giá và bảng thông số. Giá hiển thị ở danh sách là giá biến thể thấp nhất. Dòng đơn hàng ghi biến thể cùng tên và giá tại thời điểm đặt. Sản phẩm không có biến thể được coi là có đúng một biến thể mặc định.
- **Định giá**: một nơi duy nhất trả lời "biến thể này, với số lượng này, cho khách này, lúc này giá bao nhiêu", theo thứ tự flash sale, giá khuyến mãi có thời hạn, giá thường; sau đó áp mã giảm giá và điểm ở mức đơn hàng. Giỏ hàng, bước đặt hàng và các block đều hỏi cùng nơi này.
- **Flash sale**: đợt có giờ bắt đầu và kết thúc; mỗi sản phẩm trong đợt có giá và số suất. Suất chỉ trừ khi đơn hàng được tạo thành công, không trừ khi bỏ vào giỏ. Hết suất hoặc hết giờ thì về giá thường. Mỗi số điện thoại một suất cho mỗi sản phẩm trong một đợt. Đơn bị huỷ thì trả suất nếu đợt còn hiệu lực.
- **Tồn kho theo cửa hàng**: tồn được ghi theo cặp biến thể và cửa hàng. Nhận tại cửa hàng trừ tồn cửa hàng đó; giao tận nơi trừ kho mặc định. Còn hàng khi tổng tồn lớn hơn 0. Trừ tồn và trừ suất flash sale diễn ra nguyên tử cùng lúc tạo đơn để không bán quá. Mọi thay đổi tồn đều ghi nhật ký có lý do. Chưa có điều chuyển tự động giữa cửa hàng.
- **Vòng đời đơn hàng**: các trạng thái chờ thanh toán, chờ xử lý, đang giao, hoàn tất, đã huỷ, cùng cờ "cần xử lý thủ công". Đơn thanh toán trực tuyến được tạo ở trạng thái chờ thanh toán và giữ hàng trong thời gian cấu hình được (mặc định 30 phút); quá hạn thì tự huỷ và trả tồn. Việc tự huỷ chạy bằng tác vụ định kỳ của NukeViet do module đăng ký, và được kiểm lại mỗi khi đơn được mở.
- **Cổng thanh toán**: mỗi cổng là một bộ điều hợp riêng theo cùng một giao kèo: tạo yêu cầu thanh toán cho một đơn, xác thực và đọc thông báo kết quả từ cổng, trả về kết quả đã chuẩn hoá (đơn nào, số tiền, mã giao dịch, thành công hay thất bại). Phần ghi nhận vào đơn hàng là chung cho mọi cổng. Các cổng: trả tiền khi nhận hàng, SePay (chuyển khoản QR, xác nhận qua thông báo tự động), VNPAY, MoMo, ZaloPay. Mỗi cổng bật tắt riêng, có chế độ thử nghiệm, khoá nhập trong quản trị và không nằm trong mã nguồn.
- **Ghi nhận thanh toán**: thông báo phải qua kiểm tra chữ ký hoặc khoá của cổng; thông báo trùng mã giao dịch chỉ được ghi một lần; đủ tiền thì đơn sang chờ xử lý; thiếu thì giữ chờ thanh toán và ghi số đã nhận; thừa thì xác nhận và bật cờ cần hoàn phần dư; tiền về sau khi đơn đã huỷ thì không khôi phục đơn, chỉ bật cờ cần xử lý. Mọi thông báo nhận được đều lưu lại để đối soát.
- **Lọc theo thông số**: bộ thông số định nghĩa theo ngành hàng, có đánh dấu thông số nào dùng để lọc. Bộ lọc kết hợp thương hiệu, khoảng giá và thông số, trả về số kết quả cho từng lựa chọn, và trạng thái lọc nằm trong địa chỉ trang.
- **Thương hiệu**: là một khái niệm riêng có logo và trang riêng, không gộp vào nhóm thuộc tính sẵn có.
- **Hỏi đáp sản phẩm**: câu hỏi của khách và câu trả lời của nhân viên, có duyệt trước khi hiện.
- **Trả góp và thu cũ đổi mới**: bảng tính ước lượng theo tham số do quản trị cấu hình, kèm form đăng ký tạo yêu cầu để nhân viên xử lý. Không kết nối công ty tài chính.
- **Tra cứu bảo hành**: tra theo số điện thoại hoặc số máy, dựa trên đơn hàng đã hoàn tất và thời hạn bảo hành khai trên sản phẩm.
- **Hệ thống cửa hàng**: danh sách cửa hàng có địa chỉ, toạ độ, giờ mở cửa; dùng chung cho trang hệ thống cửa hàng, nhận tại cửa hàng và tồn kho.
- **Vận chuyển**: bảng phí theo tỉnh thành và ngưỡng miễn phí, thêm lựa chọn nhận tại cửa hàng (phí bằng 0). Không gọi API hãng vận chuyển.
- **Nhập xuất sản phẩm**: bằng bảng tính, gồm cả biến thể và thông số.

### Sửa lỗi mã gốc

- Trước khi thêm tính năng: cài bản gốc, đi hết các chức năng ngoài site và quản trị, lập danh sách lỗi, rồi sửa. Các lỗi đã biết trên kho chính thức về đặt hàng, in đơn và tìm kiếm phải được tái hiện và sửa.
- Thay đổi cấu trúc bảng đi kèm bước nâng cấp để site đang dùng bản shops cũ cập nhật được mà không mất dữ liệu.

### Block

- 16 block mới nằm trong module và đi theo gói xuất. Mỗi block có cấu hình riêng và lấy dữ liệu qua các khối nghiệp vụ ở trên, không tự truy vấn giá hay tồn kho.

### Theme và đăng nhập

- Theme riêng là một thư mục theme mới; không sửa theme mặc định. Theme chỉ ghi đè phần hiển thị của shops, không chứa nghiệp vụ. Một theme dùng cho cả máy tính và điện thoại.
- Trước khi dựng theme: viết bản mô tả thiết kế (tên, logo, bảng màu, kiểu chữ, bố cục các trang) và được chủ cửa hàng duyệt. Bảng màu không trùng màu nhận diện của các nhà bán lẻ lớn.
- Cải tiến đăng nhập (hộp thoại, Google, số điện thoại, gắn đơn của khách vãng lai vào tài khoản theo số điện thoại) không thuộc gói shops. Việc gắn đơn chỉ thực hiện khi số điện thoại của tài khoản đã được xác nhận thuộc về thành viên đó theo cách hệ thống thành viên hỗ trợ; không dùng OTP qua SMS.
- Đăng nhập Google dùng cơ chế đăng nhập bên thứ ba sẵn có của NukeViet; khoá do chủ cửa hàng cấp sau.

### Dữ liệu mẫu và môi trường

- Dữ liệu mẫu: cây ngành hàng điện máy đầy đủ, khoảng 150 đến 200 sản phẩm. Tên và thông số theo sản phẩm thật trên thị trường; giá là giá giả định; ảnh là ảnh minh hoạ tự tạo; có dấu hiệu nhận biết đây là dữ liệu mẫu. Thông tin liên hệ của cửa hàng để dạng chỗ trống cần điền.
- Dữ liệu mẫu nạp bằng một bước riêng, không nằm trong gói module.
- Môi trường: Docker Compose gồm máy chủ web, PHP và MariaDB; mật khẩu chỉ nằm trong tệp môi trường không đưa vào git.
- Git: commit đầu là NukeViet và shops nguyên bản, các thay đổi là commit sau; không đẩy lên kho từ xa khi chưa được yêu cầu.

## Testing Decisions

- **Một điểm kiểm thử duy nhất: ranh giới HTTP của site đang chạy trong Docker.** Bài kiểm thử đóng vai người dùng bên ngoài (trình duyệt tự động hoặc yêu cầu HTTP), chỉ khẳng định điều người dùng hoặc cổng thanh toán quan sát được: nội dung trang, giá, trạng thái đơn hàng, tồn kho hiển thị, email gửi đi. Không gọi thẳng hàm PHP, không khẳng định trên bảng dữ liệu hay cấu trúc mã.
- Bài kiểm thử tốt mô tả một hành vi theo lời của user story, vẫn đúng khi mã bên trong được viết lại, và thất bại khi hành vi đó hỏng.
- Mỗi lần chạy bắt đầu từ một bản cơ sở dữ liệu đã biết, khôi phục từ tệp sao lưu, để kết quả lặp lại được.
- Phạm vi kiểm thử:
  - Luồng mua hàng của khách vãng lai và thành viên: duyệt ngành hàng, lọc, chọn biến thể, giỏ hàng, mã giảm giá, đặt hàng, tra cứu đơn.
  - Định giá: giá thường, giá khuyến mãi có thời hạn, flash sale còn suất, hết suất, hết giờ, giới hạn một suất mỗi số điện thoại.
  - Tồn kho: trừ đúng cửa hàng, trả lại khi huỷ, hai đơn đồng thời cho món cuối cùng chỉ một đơn thành công.
  - Cổng thanh toán: với từng cổng, gửi thông báo giả lập cho các trường hợp đủ tiền, thiếu, thừa, sai chữ ký, gửi trùng, tiền về sau khi đơn huỷ; rồi đọc trạng thái đơn.
  - Đơn chờ thanh toán quá hạn: đặt thời gian giữ hàng ngắn, chờ, đọc lại trạng thái đơn và tồn kho.
  - Block: mở trang có block, kiểm nội dung theo cấu hình.
  - Đóng gói: xuất gói qua trang quản trị, cài lên một bản NukeViet 4.6 sạch ở môi trường thứ hai dùng theme mặc định, chạy lại luồng mua hàng, rồi gỡ module và kiểm tra không còn bảng sót.
  - Quản trị: tạo sản phẩm có biến thể, tạo đợt flash sale, chỉnh tồn kho, chuyển trạng thái đơn.
- Chưa có bài kiểm thử nào trong dự án để làm mẫu; bộ kiểm thử này là bộ đầu tiên và đặt quy ước cho các bài sau.
- Giới hạn đã biết: các cổng thanh toán chỉ được kiểm bằng thông báo giả lập và môi trường thử nghiệm của cổng. Kiểm trọn vòng với SePay cần một địa chỉ công khai để cổng gọi về và tài khoản của chủ cửa hàng.

## Out of Scope

- Chuyển module lên NukeViet 5.
- Sao chép thương hiệu, logo, hình ảnh hoặc nội dung của bất kỳ trang bán lẻ nào.
- Đăng nhập bằng OTP qua SMS.
- Gọi API hãng vận chuyển (tính phí tự động, tạo vận đơn, theo dõi hành trình).
- Kết nối trực tiếp công ty tài chính cho trả góp; thẩm định tự động cho thu cũ đổi mới.
- Điều chuyển hàng tự động giữa các cửa hàng.
- Đồng bộ với phần mềm kế toán, bán hàng tại quầy hoặc quản lý kho bên ngoài.
- Nhiều người bán trên cùng một site.
- Triển khai lên máy chủ thật, tên miền, chứng chỉ và tài khoản merchant của các cổng thanh toán.
- Ảnh và giá thật của sản phẩm; chủ cửa hàng tự thay qua trang quản trị.
- Sửa lỗi của lõi NukeViet hoặc của các module khác.

## Further Notes

- Trạng thái lúc viết spec: thư mục dự án có cấu hình Docker, bảng thuật ngữ và ba ADR. Lần cài NukeViet đầu tiên bị dừng giữa chừng và giải nén sai thư mục nên web root chưa đúng; phải giải nén lại toàn bộ mã nguồn rồi mới chạy trình cài đặt. Chưa có mã shops, chưa có commit.
- Danh sách tính năng shops gốc "đã có" và "chưa có" trong spec này được suy ra từ việc đọc mã nguồn trên kho chính thức, chưa qua chạy thử. Bước lập danh sách lỗi sẽ xác nhận lại; chỗ nào khác với spec thì cập nhật spec.
- Thứ tự triển khai đề xuất: môi trường và cài đặt; cài shops gốc và lập danh sách lỗi; sửa lỗi và kiểm tra đóng gói; biến thể, định giá, tồn kho theo cửa hàng; vòng đời đơn hàng và cổng thanh toán; flash sale và các dịch vụ kèm theo; block; duyệt thiết kế và dựng theme; cải tiến đăng nhập; dữ liệu mẫu; kiểm thử trọn luồng và đóng gói lần cuối.
- Khi NukeViet 5 phát hành chính thức, việc chuyển module là một spec riêng.
