# AGENTS.md

Mặc định, sau mỗi phiên làm việc có chỉnh sửa code, hãy tự động hoàn tất quy trình Git nếu repo đã được cấu hình:

1. Kiểm tra `git status`
2. Chạy test/build phù hợp nếu có và nếu thay đổi cần xác minh
3. `git add` các thay đổi liên quan
4. Tạo `git commit` với message ngắn gọn, rõ nghĩa
5. `git push` lên remote/branch hiện tại

Kết thúc mỗi task có sửa code, luôn báo lại:

- Branch hiện tại
- Commit hash mới nhất
- Trạng thái push thành công hay chưa

Nếu repo chưa có remote GitHub nhưng có thể publish bằng credential sẵn có, hãy tự publish repo rồi push.
Nếu chưa thể publish hoặc thiếu quyền, hãy nói rõ đang thiếu gì.
Không bỏ qua bước commit/push trừ khi người dùng yêu cầu rõ ràng là không commit hoặc không push.
Tự động tạo repo và push code github.com; đảm bảo có commit Git trước mỗi lần chỉnh sửa code.
