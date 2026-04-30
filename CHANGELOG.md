# Changelog

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
