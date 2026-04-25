# Dong Cong Memory

Nguon doc ngay 2026-04-25:

- Web: `https://nhadatcongminh.com/dong-cong-tuyen-trach-nhat-yeu-lam-trach-cat-than-bi.html`
- PDF local: `Dong cong tuyen trach nhat yeu dung - Le Van Suu dich.pdf`

## Diem can nho

Tai lieu chia theo 12 thang tiet khi, khong nen hieu don thuan la thang am lich. Moi thang bat dau theo tiet khi chinh:

- Thang 1: Lap xuan - Vu thuy, nguyet kien Dan.
- Thang 2: Kinh trap - Xuan phan, nguyet kien Mao.
- Thang 3: Thanh minh - Coc vu, nguyet kien Thin.
- Thang 4: Lap ha - Tieu man, nguyet kien Ty.
- Thang 5: Mang chung - Ha chi, nguyet kien Ngo.
- Thang 6: Tieu thu - Dai thu, nguyet kien Mui.
- Thang 7: Lap thu - Xu thu, nguyet kien Than.
- Thang 8: Bach lo - Thu phan, nguyet kien Dau.
- Thang 9: Han lo - Suong giang, nguyet kien Tuat.
- Thang 10: Lap dong - Tieu tuyet, nguyet kien Hoi.
- Thang 11: Dai tuyet - Dong chi, nguyet kien Ty.
- Thang 12: Tieu han - Dai han, nguyet kien Suu.

He thong Đong Cong xet truc theo nguyet kien cua tiet khi va dia chi ngay. Trong ung dung, `src/DongCongCalendar.php` tinh thang Đong Cong bang tiet khi hien tai, tinh truc tu chi ngay, roi tra ve muc loc rut gon:

- `good`: ngay tot theo bang loc nhanh.
- `mixed`: co ngoai le theo can chi ngay, loai viec, cat tinh/sat tinh; can xem tiep chi tiet.
- `bad`: ngay chua tot, nen tranh viec lon neu khong co ngoai le bo cuu.

## Cach ap dung trong UI

Tab `Nap am ngay` co the loc dong thoi theo:

- Ngu hanh cua nap am: Kim, Moc, Thuy, Hoa, Tho.
- Đong Cong: Tot, Can nhac, Chua tot.

Do tai lieu co nhieu ngoai le rieng theo can chi ngay va loai viec, ban dau chi nen dung nhu bo loc so bo. Khi mo rong sau, tao them bang ngoai le theo can chi ngay trong tung thang/truc, vi du cac cau trong PDF thuong ghi mot so ngay can chi rieng rat tot hoac rat xau trong cung mot truc.
