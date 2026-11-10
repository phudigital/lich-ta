# Changelog

## v1.5.0 - 2026-11-10

- Chốt một concept xuyên suốt "Lịch bloc" với bốn thành phần dùng chung cho mọi trang: tờ lịch (gáy màu, lỗ đóng gáy, xếp chồng), đường xé, con dấu và mini-lịch.
- Màu chủ đạo toàn site theo ngũ hành của ngày đang xem: gáy tờ lịch, logo, thanh trên cùng, menu, nút, link, giờ hoàng đạo, ô ngày được chọn và `theme-color` của trình duyệt; tự đổi khi chuyển tháng không tải lại trang.
- Đỏ chỉ còn dùng cho Chủ nhật và ngày lễ; Tốt (xanh ngọc), Cân nhắc (hổ phách), Chưa tốt (tro) là màu ngữ nghĩa cố định.
- Hôm nay: con dấu Đổng Công đóng dưới số ngày. Lịch tháng, Đổi ngày, Thông Thư, Mã nhúng, Giới thiệu, Điều khoản, Bảo mật và widget nhúng đều dùng tờ lịch có gáy; Đổi ngày có cuống kết quả xé rời; ngày đang chọn dùng mini-lịch; logo mới dạng mini-lịch.

## v1.4.0 - 2026-11-10

- Thiết kế lại toàn bộ giao diện theo concept "Tờ lịch bloc": nền giấy ấm, chữ mực, son đỏ làm màu thương hiệu duy nhất; font Be Vietnam Pro và Noto Serif hỗ trợ tiếng Việt đầy đủ.
- Trang Hôm nay thành một tờ lịch: số ngày serif lớn, ngày âm "Mùng/tháng Giêng/Chạp", hàng Can Chi ngày/tháng/năm, nhãn Hoàng/Hắc đạo, Đổng Công và tiết khí; màu tờ lịch vẫn theo ngũ hành ngày.
- Giờ hoàng đạo hiển thị sẵn dạng lưới 12 giờ, giờ tốt được tô vàng.
- Mobile: vuốt trái/phải trên tờ lịch để đổi ngày, menu đáy mới, popup ngày dạng bottom sheet có nút "Xem chi tiết ngày", vùng chạm tối thiểu 44px.
- Lịch tháng: chấm Đổng Công trên từng ô, vạch màu ngũ hành, cột Chủ nhật đỏ, chú giải dưới lịch; bộ lọc gọn hơn trên mobile.
- Gộp `site.css` + `prototype.css` thành một hệ thống style mobile-first duy nhất (index và embed không còn tải `prototype.css`).

## v1.3.3 - 2026-09-06

- Màu thẻ Hôm nay, nhãn nạp âm, icon phong thủy, nút tra cứu và trạng thái hover/focus thay đổi theo ngũ hành của ngày đang xem.
- Dùng bảng màu ngũ hành hiện có, bỏ các màu xanh lá, tím và vàng cố định trên các thành phần của trang Hôm nay.

## v1.3.2 - 2026-09-06

- Thay hình tròn nền trên thẻ Hôm nay bằng icon phong thủy bát quái và âm dương dạng SVG.

## v1.3.1 - 2026-09-06

- Sửa bố cục thực tế theo prototype: header toàn chiều rộng, bốn mục chính, logo LT, font Inter và breakpoint 1024px.
- Tách thẻ ngày, truy cập nhanh và form tra cứu; đưa đổi ngày về một thẻ với kết quả tím và liên kết xem chi tiết.
- Đồng bộ lịch tháng, thẻ Thông Thư, trang bài viết, popup và hover box với màu sắc, khoảng cách và bo góc của thiết kế mẫu.

## v1.3.0 - 2026-09-06

- Đồng bộ toàn bộ giao diện theo prototype: nền giấy ấm, thẻ bo tròn, điều hướng pill và hệ phân cấp màu sắc thống nhất.
- Thiết kế lại trải nghiệm mobile với header gọn, menu đáy cho bốn màn chính và bottom sheet cho Mã nhúng, Giới thiệu cùng các trang chính sách.
- Giữ popup chi tiết ngày trên cảm ứng và hover box trên desktop, đồng thời bổ sung trạng thái focus/active rõ ràng cho điều hướng mới.

## v1.2.5 - 2026-09-06

- Giới hạn cache tháng tự sinh trong cửa sổ 5 năm trước đến 5 năm sau năm hiện tại.
- Script precompute mặc định dọn cache ngoài cửa sổ rồi tạo cache cho toàn bộ 11 năm cần thiết.

## v1.2.2 - 2026-04-30

- Loại bỏ domain `app.pdl.vn/lich-ta` khỏi logic runtime, canonical URL và mã nhúng.
- Tự nhận diện scheme, host, proxy host và base path theo request hiện tại để hỗ trợ đổi domain hoặc chạy ở thư mục con.
- Bỏ `RewriteBase /lich-ta/` khỏi cấu hình Apache/LiteSpeed để package không bị khóa vào một subfolder cố định.
- Cập nhật tài liệu deploy và sitemap/robots cho domain `xemngay.io.vn`.

## v1.1.0 - 2026-04-25

- Gộp màn Nạp âm vào Lịch tháng.
- Thêm bộ lọc Lịch tháng bằng combobox theo Ngũ hành ngày và Đổng Công.
- Tô màu ô ngày và tooltip theo ngũ hành tương ứng.
- Bổ sung form xem ngày ở Trang chủ, hỗ trợ nhập ngày dương hoặc ngày âm.
- Bổ sung đổi ngày hai chiều: dương sang âm và âm sang dương.
- Thêm cache tháng dạng PHP array và script `bin/precompute-cache.php`.
- Hoàn thiện panel thông tin ngày ở Trang chủ cho đầy đủ như Lịch tháng.
- Hiển thị version trên header và thêm cache-busting cho CSS/JS.
