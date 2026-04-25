# Maphuong Lich Viet Reference Notes

Ngay 2026-04-25 da doc cac file tham khao:

- `https://maphuong.com/apps/lichviet/index.php`
- `https://maphuong.com/apps/lichviet/js/jquery-min2.js`
- `https://maphuong.com/apps/lichviet/js/common.js`
- `https://maphuong.com/apps/lichviet/js/jquery.js`

## Ket luan ky thuat

`index.php` chi la vo HTML cu, nap `jquery-min2.js`, `jquery.js`, `common.js`, stylesheet va goi `printSelectedMonth()`.

`jquery.js` la jQuery 1.4.2. Khong co data lich am rieng can lay.

`common.js` la nhom helper UI/cu phap Joomla cu, gom cac ham toggle, select, request helper. Khong phai nguon du lieu van su.

`jquery-min2.js` la file quan trong: no chua engine lich am, bang chuoi tieng Viet va cac ham tinh thong tin ngay nhu can chi, tiet khi, gio hoang dao, truc, sao nhi thap bat tu, luc dieu, nap am, ngay tot/xau, tuoi xung, nghi/ki.

## Luu y ban quyen

Trang HTML co meta copyright `maphuong.com`, file `jquery-min2.js` bi obfuscated/minified va khong thay license cho phep tai su dung lai. Vi vay repo nay khong vendor/copy nguyen file do, cung khong copy cac doan dien giai dai. Huong di an toan la dung no nhu ban tham chieu taxonomy, roi viet lai thu vien PHP sach bang quy tac lich Viet truyen thong va bo du lieu minh tu quan tri.

## Thu vien minh dang dung

`src/DayFortune.php` la lop mo rong sach cho cac truong co the bo sung dan:

- `truc`
- `lucDieu`
- `lucDieuHint`
- `saoNhiThapBatTu`
- `napAm`
- `hoangHacDao`
- `hoangHacDaoStar`
- `ngayXung`
- `tuoiXung`
- `ngayTot`
- `ngayXau`

Khi can them noi dung dai nhu `Nghi`, `Ki`, y nghia sao, y nghia truc, nen bo sung bang data noi bo co nguon ro rang vao class rieng hoac file PHP config rieng, khong copy nguyen van tu file obfuscated.
