Cách xác định 24 tiết khí
Tiết khí là các thời điểm mà kinh độ mặt trời (KĐMT) có các giá trị 0°, 15°, 30°, 45°, 60°, ..., 345°. (0° là Xuân Phân, 15° là Thanh Minh v.v.). Như vậy để xác định tiết khí ta cần tìm xem vào khoảng thời gian nào thì kinh độ mặt trời có các giá trị này.
Tìm ngày chứa tiết khí
Thường thì ta chỉ quan tâm tới tiết khí rơi vào ngày nào chứ không cần chính xác tới giờ/phút. Ngày chứa một tiết khí nhất định có thể được xác định như sau:

    Chọn một ngày có khả năng chứa tiết khí cần xác định. Ngày có tiết khí chỉ xê dịch trong khoảng 1-2 ngày nên ta có thể chọn khá sát.
    Tính kinh độ mặt trời lúc 0h sáng ngày hôm đó và 0h sáng ngày hôm sau
    Nếu kinh độ mặt trời tương ứng với tiết khí cần xác định nằm giữa hai giá trị này thì ngày đã chọn chính là ngày chứa tiết khí, nếu không ta lặp lại việc tìm kiếm này với ngày trước hoặc sau đó.

Tìm thời điểm tiết khí
Để tìm thời điểm chính xác của một tiết khí, sau khi xác định được ngày chứa tiết khí đó ta có thể thực hiện một phép tìm kiếm nhị phân đơn giản để tìm ra ngày giờ của tiết khí này.

    Chọn mốc trên và dưới là 0h và 24h (tức 0h sáng ngày hôm sau). Tính điểm giữa 2 mốc (12h trưa) và tính KĐMT tại điểm đó.
    Nếu KĐMT này nhỏ hơn KĐMT của tiết khí, tìm tiếp trong khoảng từ 0h đến 12h, nếu không sẽ tìm trong khoảng từ 12h đến 24h.
    Lặp lại việc tìm kiếm đến khi KĐMT của hai điểm mốc cách nhau không quá 0.001 độ.

Bước tính toán quan trọng nhất trong việc xác định tiết khí là tìm kinh độ mặt trời tại một thời điểm bất kỳ. Việc tính toán này được thực hiện với 2 bước:

    Tính niên kỷ Julius của thời điểm đã cho
    Tính kinh độ mặt trời cho thời điểm đó

Ngày và niên kỷ Julius
Số ngày Julius (Julian Day Number) của một ngày trong lịch Gregory có thể tính bởi các công thức sau, sử dụng năm thiên văn (1 TCN là 0, 2 TCN là −1, 4713 TCN là −4712):

    a = [(14 - tháng)/ 12]
    y = năm + 4800 - a
    m= tháng + 12a - 3
    JDN = ngày + [(153m + 2)/5] + 365y + [y/4] - [y/100] + [y/400] - 32045

Trong các công thức trên [x/y] là phần nguyên của phép chia x/y.

Để tính niên kỷ Julius (Julian date), thêm giờ, phút, giây theo UT (Universal Time):

    JD = JDN + (giờ - 12)/24 + phút/1440 + giây/86400

Nếu giờ, phút, giây được tính theo giờ Hà Nội (UTC+7:00) thì kết quả phải trừ đi 7/24 ngày.
Tính kinh độ mặt trời tại một thời điểm
Để tính kinh độ mặt trời tại thời điểm, trước hết tìm niên kỷ Julius JD của thời điểm đó theo phương pháp trên. Sau đó thực hiện các bước sau:

T = (JD - 2451545.0) / 36525
L0 = 280°.46645 + 36000°.76983*T + 0°.0003032*T2
M = 357°.52910 + 35999°.05030*T - 0°.0001559*T2 - 0°.00000048*T3
C = (1°.914600 - 0°.004817*T - 0°.000014*T2) * sin M + (0°.01993 - 0°.000101*T) * sin 2M + 0°.000290 * sin 3M
theta = L0 + C
lambda = theta - 0.00569 - 0.00478 * sin(125°.04 - 1934°.136*T)
lambda = lambda - 360 * [lambda/360]

Kết quả lambda là kinh độ mặt trời cần tìm. Đó là một góc (tính bằng độ) trong khoảng (0,360).
Ví dụ
Chọn ngày giờ (giờ Hà Nội, UTC+7:00) và nhấn OK để tính kinh độ mặt trời tại thời điểm đó:

  Ngày:   tháng   năm    

Kết quả:
Tìm ngày Đông Chí năm 2008. Kinh độ mặt trời ứng với Đông Chí là 270°. Ngày Đông Chí thường rơi vào khoảng 20/12-22/12 hàng năm. Như vậy trước hết ta thử ngày 20/12/2008. KĐMT lúc 0h sáng ngày 20/12/2008 là 268°.17811 và lúc 0h sáng 21/12/2008 là 269°.19634. Góc 270° nằm sau cả hai giá trị này, như vậy ta phải thử ngày hôm sau. KĐMT lúc 0h sáng ngày 22/12/2008 là 270°.21471, như thế điểm Đông Chí nằm trong ngày 21/12/2008.

Để xác định thời điểm Đông Chí, ta tính KĐMT lúc 12h ngày 21/12/2008, được kết quả 269°.70551, nhỏ hơn 270°, như vậy điểm Đông Chí nằm trong khoảng từ 12h đến 24h. Chọn 18h00 ngày 21/12/2008 sẽ tìm thấy KĐMT 269°.96010, như vậy ta phải tìm tiếp trong khoảng 18h đến 24h. Vào lúc 21h, KĐMT là 270°.08741, như thế khoảng tìm kiếm bây giờ là 18h đến 21h. Lặp lại việc tìm kiếm này thêm khoảng 7 bước nữa sẽ tìm được thời điểm Đông Chí là 18h56. (Kết quả 'chính xác' tính theo lý thuyết VSOP87 là 19h04). 