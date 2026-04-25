Chương trình âm lịch bằng JavaScript
<<  < 	6/2019 	>  >>
CN 	T2 	T3 	T4 	T5 	T6 	T7
 
 
	
 
 
	
 
 
	
 
 
	
 
 
	
 
 
	1
28/4
2
29
	3
1/5
	4
2
	5
3
	6
4
	7
5
	8
6
9
7
	10
8
	11
9
	12
10
	13
11
	14
12
	15
13
16
14
	17
15
	18
16
	19
17
	20
18
	21
19
	22
20
23
21
	24
22
	25
23
	26
24
	27
25
	28
26
	29
27
30
28
	
 
 
	
 
 
	
 
 
	
 
 
	
 
 
	
 
 
Tháng      Năm  

  

    Chọn một tháng của một năm trong khoảng 1800-2199 và nhấn chuột vào nút Xem lịch tháng để xem lịch của tháng đã chọn.
    Kích chuột vào nút Xem lịch năm sẽ hiển thị lịch của cả năm đã chọn. Lịch năm có thể in vừa trên một trang A4 (sử dụng chức năng in của chương trình duyệt).
    Để xem Can-Chi của một ngày, nhấn chuột vào ô hiển thị ngày đó. Nhấn chuột vào dòng thứ hai (chứa tên các ngày trong tuần: CN, T2...) sẽ hiện thông tin về chương trình.

Sử dụng thư viện JavaScript tính âm lịch trên trang Web của bạn
Bạn có thể hiển thị ngày tháng âm lịch trên trang Web của mình bằng nhiều cách khác nhau.
Hiện ngày tháng trên thanh trạng thái (status bar)
Đây là cách đơn giản nhất. Chỉ cần thêm đoạn mã sau vào bất kỳ vị trí nào trong trang Web của bạn:

    <script type="text/javascript" language="JavaScript" src="http://www.informatik.uni-leipzig.de/~duc/amlich/JavaScript/amlich-hnd.js">
    </script>
    <script language="JavaScript">
    <!--
        showVietCal();
    -->
    </script>

Kết quả sẽ giống như Statusbar của trang bạn đang xem.
Chèn lịch tháng vào trang Web với IFRAME

Chèn đoạn mã sau vào vị trí mà bạn muốn bảng lịch tháng hiện ra:

    <IFRAME src="http://www.informatik.uni-leipzig.de/~duc/amlich/JavaScript/current_month.html" align="right" name="CurentMonth" width="188" height="228" scrolling="no" frameborder=0>
    </IFRAME> 

Kết quả sẽ tương tự như trang sample01.html.
Chèn trực tiếp lịch tháng vào trang Web

Chèn đoạn mã sau vào vị trí mà bạn muốn bảng lịch tháng hiện ra:

    <script type="text/javascript" language="JavaScript" src="http://www.informatik.uni-leipzig.de/~duc/amlich/JavaScript/amlich-hnd.js">
    </script>
    <script language="JavaScript">
    <!--
    setOutputSize("small");
    document.writeln(printSelectedMonth());
    -->
    </script>

Kết quả sẽ tương tự như trang sample02.html. Trang bạn đang xem cũng dùng phương pháp này.
Thông tin về chương trình
Chương trình âm lịch Việt Nam bằng JavaScript do tác giả Hồ Ngọc Đức thực hiện. Hiện tại chương trình chứa dữ liệu cho 4 thế kỷ, từ 1800 đến 2199. Các số liệu này được tính sẵn bằng chương trình Java của cùng tác giả. Dữ liệu cho các thế kỷ khác có thể được tác giả cung cấp theo yêu cầu.

Bạn có thể download thư viện JavaScript tính âm lịch và thêm các chức năng bạn muốn có. Ví dụ, bạn có thể sửa đổi hàm alertDayInfo để chương trình không hiện Can-Chi của ngày vừa chọn mà hiện các thông tin khác như lịch hoạt động trong ngày v.v. 