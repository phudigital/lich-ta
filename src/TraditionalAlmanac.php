<?php

declare(strict_types=1);

namespace LichTa;

final class TraditionalAlmanac
{
    private const BRANCHES = ['Ty', 'Suu', 'Dan', 'Mao', 'Thin', 'Ty.', 'Ngo', 'Mui', 'Than', 'Dau', 'Tuat', 'Hoi'];
    private const BRANCHES_VI = [
        'Ty' => 'Tý',
        'Suu' => 'Sửu',
        'Dan' => 'Dần',
        'Mao' => 'Mão',
        'Thin' => 'Thìn',
        'Ty.' => 'Tỵ',
        'Ngo' => 'Ngọ',
        'Mui' => 'Mùi',
        'Than' => 'Thân',
        'Dau' => 'Dậu',
        'Tuat' => 'Tuất',
        'Hoi' => 'Hợi',
    ];
    private const STEMS_VI = [
        'Giap' => 'Giáp',
        'At' => 'Ất',
        'Binh' => 'Bính',
        'Dinh' => 'Đinh',
        'Mau' => 'Mậu',
        'Ky' => 'Kỷ',
        'Canh' => 'Canh',
        'Tan' => 'Tân',
        'Nham' => 'Nhâm',
        'Quy' => 'Quý',
    ];

    private const SOURCES = [
        'pdf' => 'Thông thư về ngày tốt xấu và các sao tốt thông thư.pdf',
        'crossCheck' => 'Đối chiếu thêm với tư liệu Ngọc Hạp Thông Thư, Nhị Thập Bát Tú và Lục Nhâm công khai trên web.',
    ];

    private const STAR_GLOSSARY = [
        'good' => [
            'Thiên phúc' => ['goodFor' => ['nhận công tác', 'về nhà mới', 'lễ cúng'], 'avoid' => []],
            'Thiên phú' => ['goodFor' => ['làm kho', 'chứa thóc lúa', 'tích trữ đồ dùng'], 'avoid' => []],
            'Thiên hỷ' => ['goodFor' => ['cưới xin', 'xuất hành', 'ăn hỏi'], 'avoid' => []],
            'Thiên xá' => ['goodFor' => ['cầu thần', 'giải oan', 'tha thứ'], 'avoid' => ['săn bắn', 'đâm chém', 'chôn cất']],
            'Thiên y' => ['goodFor' => ['tìm thầy thuốc', 'bốc thuốc', 'chữa bệnh'], 'avoid' => []],
            'Nguyệt không' => ['goodFor' => ['dâng sớ', 'làm nhà', 'đóng giường'], 'avoid' => []],
            'Nguyệt tài' => ['goodFor' => ['mở hàng', 'buôn bán', 'làm kho', 'xuất hành'], 'avoid' => []],
            'Minh tinh' => ['goodFor' => ['việc quan', 'khiếu nại', 'tố tụng', 'dỡ mồ mả'], 'avoid' => []],
            'Sinh khí' => ['goodFor' => ['tu sửa', 'động thổ', 'ăn hỏi', 'cưới xin'], 'avoid' => []],
            'Giải thần' => ['goodFor' => ['giải kiện cáo', 'tắm gội', 'tìm thuốc giải'], 'avoid' => []],
            'Phả hộ' => ['goodFor' => ['làm việc phúc', 'cưới gả', 'xuất hành'], 'avoid' => []],
            'Tục thế' => ['goodFor' => ['hỏi vợ gả chồng', 'dựng con trưởng', 'ăn hỏi'], 'avoid' => []],
            'Ích hậu' => ['goodFor' => ['cưới hỏi', 'cầu tự', 'di chúc'], 'avoid' => []],
            'Địa tài' => ['goodFor' => ['thu nhận tiền tài', 'nhập kho'], 'avoid' => []],
            'Mẫu xương' => ['goodFor' => ['làm kho', 'chăn nuôi', 'trồng trọt'], 'avoid' => []],
            'Lộc khố' => ['goodFor' => ['thu cất thóc lúa', 'nhập tài vật'], 'avoid' => []],
            'Quan Nhật' => ['goodFor' => ['thăng quan', 'tiến chức', 'tặng thưởng'], 'avoid' => []],
            'Dân nhật' => ['goodFor' => ['động thổ', 'đào đắp', 'sửa việc nhỏ'], 'avoid' => []],
            'Dịch mã' => ['goodFor' => ['xuất hành', 'cầu y', 'trị bệnh'], 'avoid' => []],
            'Diệu xương' => ['goodFor' => ['thu nợ', 'mua gia súc'], 'avoid' => []],
        ],
        'bad' => [
            'Thiên Cương' => ['avoid' => ['mọi việc lớn']],
            'Thiên Phùng' => ['avoid' => ['kiện tụng', 'xuất hành']],
            'Thiên hỏa' => ['avoid' => ['lợp nhà', 'mở đường']],
            'Thiên cẩu' => ['avoid' => ['lễ bái', 'cầu cúng']],
            'Thiên tặc' => ['avoid' => ['cất nhà', 'dỡ mả', 'mở kho']],
            'Thiên ôn' => ['avoid' => ['về nhà mới', 'chữa bệnh', 'làm chuồng trại']],
            'Địa hỏa' => ['avoid' => ['trồng cây']],
            'Địa tặc' => ['avoid' => ['làm nhà', 'xuất hành', 'động thổ', 'đào ao']],
            'Thụ tử' => ['avoid' => ['mọi việc lớn']],
            'Sát chủ' => ['avoid' => ['mọi việc lớn']],
            'Hoang vu' => ['avoid' => ['mọi việc lớn']],
            'Đại hao' => ['avoid' => ['mọi việc lớn']],
            'Tiểu hao' => ['avoid' => ['buôn bán', 'cho vay mượn']],
            'Vãng Vong' => ['avoid' => ['xuất hành', 'cầu mưu', 'cưới gả']],
            'Cửu không' => ['avoid' => ['xuất hành', 'cầu tài', 'mở kho', 'trồng cây']],
            'Âm thác' => ['avoid' => ['xuất hành', 'nhận công tác']],
            'Dương thác' => ['avoid' => ['đi xa', 'hôn nhân', 'di chuyển chỗ ở']],
            'Trùng tang' => ['avoid' => ['hôn nhân', 'ma chay', 'cải táng']],
            'Trùng phục' => ['avoid' => ['hôn nhân', 'ma chay', 'cải táng']],
            'Huyết kỵ' => ['avoid' => ['châm cứu', 'mổ xẻ']],
            'Thổ kỵ' => ['avoid' => ['động thổ', 'đắp nền']],
            'Thổ cấm' => ['avoid' => ['đào ao', 'trồng cây', 'đào móng']],
        ],
    ];

    private const TWENTY_EIGHT_STARS = [
        'Giác' => ['rating' => 'good', 'animal' => 'Giác Mộc Giao', 'summary' => 'Tốt cho công danh, gặp quý nhân, cưới hỏi; nên tránh mai táng, sửa âm phần.'],
        'Cang' => ['rating' => 'bad', 'animal' => 'Cang Kim Long', 'summary' => 'Nên giữ mình, chôn cất và hôn nhân đều nên tránh.'],
        'Đê' => ['rating' => 'bad', 'animal' => 'Đê Thổ Lạc', 'summary' => 'Xấu cho động thổ, hôn nhân, ký kết, xây cất và xuất hành.'],
        'Phòng' => ['rating' => 'good', 'animal' => 'Phòng Nhật Thỏ', 'summary' => 'Tốt cho điền tài, xây cất, cưới xin và việc gia đình.'],
        'Tâm' => ['rating' => 'bad', 'animal' => 'Tâm Nguyệt Hổ', 'summary' => 'Bất lợi cho ăn hỏi, cưới xin, kinh doanh và kiện tụng.'],
        'Vĩ' => ['rating' => 'good', 'animal' => 'Vĩ Hỏa Hổ', 'summary' => 'Tốt cho làm nhà, cưới gả, xuất ngoại, kinh doanh và thăng tiến.'],
        'Cơ' => ['rating' => 'good', 'animal' => 'Cơ Thủy Báo', 'summary' => 'Tốt cho sự nghiệp, nhà cửa, tiền bạc và âm phần.'],
        'Đẩu' => ['rating' => 'good', 'animal' => 'Đẩu Mộc Giải', 'summary' => 'Tốt cho hôn nhân, chăn nuôi, cấy gặt, làm nhà và ao hồ.'],
        'Ngưu' => ['rating' => 'bad', 'animal' => 'Ngưu Kim Ngưu', 'summary' => 'Dễ hao tài, gây dựng và cưới hỏi nên cẩn trọng.'],
        'Nữ' => ['rating' => 'bad', 'animal' => 'Nữ Thổ Bức', 'summary' => 'Bất lợi cho giao dịch, sinh nở và việc tài chính gia đình.'],
        'Hư' => ['rating' => 'bad', 'animal' => 'Hư Nhật Thử', 'summary' => 'Chủ tai ương, nên tránh việc tình cảm và việc lớn.'],
        'Nguy' => ['rating' => 'bad', 'animal' => 'Nguy Nguyệt Yến', 'summary' => 'Kỵ làm nhà, kinh doanh và việc hiếu.'],
        'Thất' => ['rating' => 'good', 'animal' => 'Thất Hỏa Trư', 'summary' => 'Tốt cho công danh, mở hiệu, làm nhà, hôn nhân.'],
        'Bích' => ['rating' => 'good', 'animal' => 'Bích Thủy Dư', 'summary' => 'Tốt cho cưới hỏi, sinh con, thương mại, kinh doanh, làm nhà.'],
        'Khuê' => ['rating' => 'mixed', 'animal' => 'Khuê Mộc Lang', 'summary' => 'Có phần lành cho vợ chồng, nhưng tránh mở hàng, động thổ, đưa ma, sửa mộ.'],
        'Lâu' => ['rating' => 'good', 'animal' => 'Lâu Kim Cẩu', 'summary' => 'Tốt cho lợp mái, thêm người thêm của, hôn nhân và nhận chức.'],
        'Vị' => ['rating' => 'good', 'animal' => 'Vị Thổ Trĩ', 'summary' => 'Tốt cho mua bán, xây nhà, cưới hỏi và việc lớn.'],
        'Mão' => ['rating' => 'bad', 'animal' => 'Mão Nhật Kê', 'summary' => 'Bất lợi cho chăn nuôi, làm nhà, nhận chức và hôn nhân.'],
        'Tất' => ['rating' => 'good', 'animal' => 'Tất Nguyệt Ô', 'summary' => 'Tốt cho nông trang, nhà cửa, hôn thú và sinh con.'],
        'Chủy' => ['rating' => 'bad', 'animal' => 'Chủy Hỏa Hầu', 'summary' => 'Dễ vướng cửa quan, hao tài, người có chức nên giữ gìn.'],
        'Sâm' => ['rating' => 'mixed', 'animal' => 'Sâm Thủy Viên', 'summary' => 'Tốt cho mưu cầu sự nghiệp, mở hiệu, xây nhà; hôn thú cần cân nhắc.'],
        'Tỉnh' => ['rating' => 'good', 'animal' => 'Tỉnh Mộc Hãn', 'summary' => 'Tốt cho thi cử, trồng trọt, chăn nuôi, làm nhà, hôn thú; tránh việc tang.'],
        'Quỷ' => ['rating' => 'bad', 'animal' => 'Quỷ Kim Dương', 'summary' => 'Xấu cho làm nhà, cưới xin; chỉ lợi việc hiếu tang.'],
        'Liễu' => ['rating' => 'bad', 'animal' => 'Liễu Thổ Chương', 'summary' => 'Nhiều nguy nan, hao tài tổn sức, nên giữ an ổn.'],
        'Tinh' => ['rating' => 'mixed', 'animal' => 'Tinh Nhật Mã', 'summary' => 'Tránh hợp hôn; tốt hơn cho làm nhà và công danh.'],
        'Trương' => ['rating' => 'good', 'animal' => 'Trương Nguyệt Lộc', 'summary' => 'Tốt cho làm nhà, cưới hỏi, mở hàng, nhập học và tang ma.'],
        'Dực' => ['rating' => 'bad', 'animal' => 'Dực Hỏa Xà', 'summary' => 'Kỵ làm nhà và việc nam nữ; cần giữ nề nếp.'],
        'Chẩn' => ['rating' => 'good', 'animal' => 'Chẩn Thủy Dẫn', 'summary' => 'Tốt cho thăng quan, việc hiếu, việc hôn và kinh doanh.'],
    ];

    private const LUC_NHAM = [
        ['name' => 'Đại An', 'level' => 'good', 'element' => 'Mộc', 'summary' => 'Bình an, hợp việc chắc chắn, cầu ổn định và gặp quý nhân.'],
        ['name' => 'Lưu Niên', 'level' => 'bad', 'element' => 'Thủy', 'summary' => 'Việc dễ chậm, tin tức muộn, nên giữ nhịp và tránh vội vàng.'],
        ['name' => 'Tốc Hỷ', 'level' => 'good', 'element' => 'Hỏa', 'summary' => 'Có tin vui, hợp gặp gỡ, cầu tài, xuất hành việc cần nhanh.'],
        ['name' => 'Xích Khẩu', 'level' => 'bad', 'element' => 'Kim', 'summary' => 'Dễ sinh tranh cãi, nên cẩn trọng lời nói, giấy tờ và kiện tụng.'],
        ['name' => 'Tiểu Cát', 'level' => 'good', 'element' => 'Thủy', 'summary' => 'Cát lành vừa phải, hợp giao dịch nhỏ, gặp người, tìm tin.'],
        ['name' => 'Không Vong', 'level' => 'bad', 'element' => 'Thổ', 'summary' => 'Việc dễ rỗng kết quả, hạn chế khởi sự lớn hoặc cầu chắc thắng.'],
    ];

    private const DUONG_CONG = [
        1 => [13], 2 => [11], 3 => [9], 4 => [7], 5 => [5], 6 => [3],
        7 => [8, 29], 8 => [27], 9 => [25], 10 => [23], 11 => [21], 12 => [19],
    ];

    private const BRANCH_TABOOS = [
        'vangVong' => [
            1 => 'Dan', 2 => 'Ty.', 3 => 'Than', 4 => 'Hoi', 5 => 'Mao', 6 => 'Ngo',
            7 => 'Dau', 8 => 'Ty', 9 => 'Thin', 10 => 'Mui', 11 => 'Tuat', 12 => 'Suu',
        ],
        'thienHoa' => [
            1 => 'Ty', 2 => 'Mao', 3 => 'Ngo', 4 => 'Dau', 5 => 'Ty', 6 => 'Mao',
            7 => 'Ngo', 8 => 'Dau', 9 => 'Ty', 10 => 'Mao', 11 => 'Ngo', 12 => 'Dau',
        ],
        'diaHoa' => [
            1 => 'Ty.', 2 => 'Ngo', 3 => 'Dan', 4 => 'Than', 5 => 'Mao', 6 => 'Dau',
            7 => 'Thin', 8 => 'Tuat', 9 => 'Ty.', 10 => 'Hoi', 11 => 'Ty', 12 => 'Ngo',
        ],
        'hoaTai' => [
            1 => 'Suu', 2 => 'Mui', 3 => 'Dan', 4 => 'Than', 5 => 'Mao', 6 => 'Dau',
            7 => 'Thin', 8 => 'Tuat', 9 => 'Ty.', 10 => 'Hoi', 11 => 'Ty', 12 => 'Ngo',
        ],
        'thienCuong' => [
            1 => 'Ty.', 2 => 'Ty', 3 => 'Mui', 4 => 'Dan', 5 => 'Dau', 6 => 'Thin',
            7 => 'Hoi', 8 => 'Ngo', 9 => 'Suu', 10 => 'Than', 11 => 'Mao', 12 => 'Tuat',
        ],
    ];

    private const NGUYET_DUC = [
        1 => 'Hoi', 2 => 'Tuat', 3 => 'Dau', 4 => 'Than', 5 => 'Mui', 6 => 'Ngo',
        7 => 'Ty.', 8 => 'Thin', 9 => 'Mao', 10 => 'Dan', 11 => 'Suu', 12 => 'Ty',
    ];

    private const NGOC_HAP_RITUAL = [
        'Giap Ty' => ['level' => 'good', 'summary' => 'Các thần đều ở dưới đất, tốt cho lễ Phật, cầu thần, cầu tự, cầu phúc.'],
        'At Suu' => ['level' => 'good', 'summary' => 'Các thần đều ở dưới đất, tốt cho lễ Phật, cầu thần, cầu tự, cầu phúc.'],
        'Binh Dan' => ['level' => 'bad', 'summary' => 'Thần ở trên trời, không nên lễ bái cầu phúc, tế tự lớn.'],
        'Dinh Mao' => ['level' => 'good', 'summary' => 'Bách thần ở dưới đất, tốt cho lễ bái, thượng biểu, tế tự.'],
        'Mau Thin' => ['level' => 'good', 'summary' => 'Bách thần ở dưới đất, tốt cho lễ bái, thượng biểu, tế tự.'],
        'Ky Ty.' => ['level' => 'good', 'summary' => 'Bách thần ở dưới đất, tốt cho lễ bái, thượng biểu, tế tự.'],
        'Canh Ngo' => ['level' => 'bad', 'summary' => 'Chư thần ở trên trời, không nên tế tự lễ bái.'],
        'Tan Mui' => ['level' => 'bad', 'summary' => 'Chư thần ở trên trời, không nên tế tự lễ bái.'],
        'Nham Than' => ['level' => 'good', 'summary' => 'Các thần chuyển về địa phủ, tốt cho cầu phúc, thượng biểu, cầu trai gái.'],
        'Quy Dau' => ['level' => 'mixed', 'summary' => 'Tốt riêng cho tế tự thủy quan, các lễ bái khác nên tránh.'],
        'Binh Ty' => ['level' => 'bad', 'summary' => 'Rất xấu cho tế lễ, dễ chiêu tai họa theo bản Ngọc Hạp đang tham khảo.'],
        'Dinh Suu' => ['level' => 'bad', 'summary' => 'Rất xấu cho tế lễ, dễ chiêu tai họa theo bản Ngọc Hạp đang tham khảo.'],
        'Mau Dan' => ['level' => 'bad', 'summary' => 'Rất xấu cho tế lễ, dễ chiêu tai họa theo bản Ngọc Hạp đang tham khảo.'],
        'Ky Mao' => ['level' => 'good', 'summary' => 'Các thần tại địa phủ, cầu phúc được lợi cho con cháu.'],
        'Canh Thin' => ['level' => 'good', 'summary' => 'Các thần tại địa phủ, cầu phúc được lợi cho con cháu.'],
        'Tan Ty.' => ['level' => 'bad', 'summary' => 'Xấu cho lễ bái cầu phúc, nguồn tham khảo ghi dễ sinh họa.'],
        'Giap Ngo' => ['level' => 'good', 'summary' => 'Tốt cho lễ tạ thổ công, thổ địa và tiến biểu.'],
        'At Mui' => ['level' => 'mixed', 'summary' => 'Lễ bái tạm được, tốt nhỏ.'],
        'Canh Than' => ['level' => 'good', 'summary' => 'Ngày mở đường ngũ phúc, tốt cho làm chay, thượng biểu.'],
        'Tan Dau' => ['level' => 'bad', 'summary' => 'Chư thần hầu Ngọc Hoàng, lễ bái tế tự rất xấu.'],
        'Nham Tuat' => ['level' => 'bad', 'summary' => 'Lục thần cùng nhật, cầu phúc không lợi.'],
        'Quy Hoi' => ['level' => 'bad', 'summary' => 'Lục thần cùng nhật, cầu phúc không lợi.'],
    ];

    private const NAP_AM_NOTES = [
        'sourceStatus' => 'verified',
        'summary' => 'Bảng 30 cặp nạp âm đang tham khảo trùng với hệ lục thập hoa giáp app đang dùng.',
    ];

    /**
     * @return array<string,mixed>
     */
    public static function evaluate(array $lunar, string $dayCanChi, string $starName, string $napAm): array
    {
        $parts = self::splitCanChi($dayCanChi);
        $lunarMonth = (int) $lunar['month'];
        $lunarDay = (int) $lunar['day'];

        return [
            'starGlossary' => self::STAR_GLOSSARY,
            'kyNgay' => self::tabooDays($lunarDay, $lunarMonth, $parts['stem'], $parts['branch']),
            'nhiThapBatTu' => self::TWENTY_EIGHT_STARS[$starName] ?? ['rating' => 'unknown', 'animal' => '', 'summary' => 'Chưa có diễn giải trong bộ dữ liệu.'],
            'lucNhan' => self::lucNhan($lunarDay, $lunarMonth),
            'ngocHap' => self::ngocHap($lunarMonth, $parts['branch'], $dayCanChi),
            'napAmReference' => self::NAP_AM_NOTES + ['name' => $napAm],
            'sources' => self::SOURCES,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public static function library(): array
    {
        return [
            'sources' => self::SOURCES,
            'starGlossary' => self::STAR_GLOSSARY,
            'twentyEightStars' => self::TWENTY_EIGHT_STARS,
            'lucNhan' => self::LUC_NHAM,
            'lucNhanMonthStarts' => [
                1 => 'Đại An', 2 => 'Lưu Niên', 3 => 'Tốc Hỷ', 4 => 'Xích Khẩu', 5 => 'Tiểu Cát', 6 => 'Không Vong',
                7 => 'Đại An', 8 => 'Lưu Niên', 9 => 'Tốc Hỷ', 10 => 'Xích Khẩu', 11 => 'Tiểu Cát', 12 => 'Không Vong',
            ],
            'kyNgayRules' => [
                ['name' => 'Nguyệt kỵ', 'rule' => 'Mùng 5, 14, 23 âm lịch hằng tháng', 'avoid' => ['cầu tài', 'xuất hành', 'giá thú', 'nhập trạch', 'cất nóc', 'hạ móng'], 'confidence' => 'high'],
                ['name' => 'Tam nương', 'rule' => 'Mùng 3, 7, 13, 18, 22, 27 âm lịch hằng tháng', 'avoid' => ['khởi sự', 'cưới hỏi', 'xuất hành', 'việc lớn'], 'confidence' => 'high'],
                ['name' => 'Dương Công kỵ nhật', 'rule' => 'Theo bảng ngày kỵ riêng từng tháng âm: ' . self::monthDayTable(self::DUONG_CONG), 'avoid' => ['mọi việc lớn'], 'confidence' => 'medium'],
                ['name' => 'Vãng Vong', 'rule' => self::branchRuleText(self::BRANCH_TABOOS['vangVong']), 'avoid' => ['xuất hành', 'giá thú', 'cầu mưu'], 'confidence' => 'medium'],
                ['name' => 'Thiên hỏa', 'rule' => self::branchRuleText(self::BRANCH_TABOOS['thienHoa']), 'avoid' => ['lợp nhà', 'mở đường', 'cất nhà'], 'confidence' => 'medium'],
                ['name' => 'Địa hỏa', 'rule' => self::branchRuleText(self::BRANCH_TABOOS['diaHoa']), 'avoid' => ['trồng cây', 'động thổ'], 'confidence' => 'medium'],
                ['name' => 'Hỏa tai', 'rule' => self::branchRuleText(self::BRANCH_TABOOS['hoaTai']), 'avoid' => ['việc nhà cửa', 'việc liên quan lửa bếp'], 'confidence' => 'medium'],
                ['name' => 'Thiên Cường', 'rule' => self::branchRuleText(self::BRANCH_TABOOS['thienCuong']), 'avoid' => ['việc lớn', 'khởi sự'], 'confidence' => 'medium'],
                ['name' => 'Thổ cấm', 'rule' => 'Tháng 1-3 kỵ Hợi; tháng 4-6 kỵ Dần; tháng 7-9 kỵ Thân. Đoạn tháng 10-12 cần đối chiếu thêm với nguồn rõ hơn.', 'avoid' => ['động thổ', 'đào giếng', 'chôn cất'], 'confidence' => 'medium'],
                ['name' => 'Trùng tang/Trùng phục', 'rule' => 'Xét theo can ngày và tháng âm, dùng bảng can kỵ từng tháng trong thông thư truyền thống.', 'avoid' => ['hôn nhân', 'ma chay', 'cải táng'], 'confidence' => 'medium'],
            ],
            'ngocHap' => [
                'nguyetDuc' => self::branchRuleText(self::NGUYET_DUC),
                'ritual' => self::readableNgocHapRitual(),
                'coverage' => 'partial',
                'note' => 'Bảng Ngọc Hạp đang được bổ sung dần. Các can chi chưa đủ chắc sẽ được ghi chú để người dùng biết cần đối chiếu thêm.',
            ],
            'napAmReference' => self::NAP_AM_NOTES,
        ];
    }

    /**
     * @return list<array{name:string,level:string,appliesTo:list<string>,note:string,confidence:string}>
     */
    private static function tabooDays(int $lunarDay, int $lunarMonth, string $stem, string $branch): array
    {
        $items = [];
        if (in_array($lunarDay, [5, 14, 23], true)) {
            $items[] = self::item('Nguyệt kỵ', 'bad', ['cầu tài', 'xuất hành', 'giá thú', 'nhập trạch', 'cất nóc', 'hạ móng'], 'Ngày mùng 5, 14, 23 âm lịch.', 'high');
        }
        if (in_array($lunarDay, [3, 7, 13, 18, 22, 27], true)) {
            $items[] = self::item('Tam nương', 'bad', ['khởi sự', 'cưới hỏi', 'xuất hành', 'việc lớn'], 'Các ngày 3, 7, 13, 18, 22, 27 âm lịch.', 'high');
        }
        if (in_array($lunarDay, self::DUONG_CONG[$lunarMonth] ?? [], true)) {
            $items[] = self::item('Dương Công kỵ nhật', 'bad', ['mọi việc lớn'], 'Bảng 13 ngày Dương Công kỵ nhật theo tháng âm.', 'medium');
        }
        if ((self::BRANCH_TABOOS['vangVong'][$lunarMonth] ?? '') === $branch) {
            $items[] = self::item('Vãng Vong', 'bad', ['xuất hành', 'giá thú', 'cầu mưu'], 'Chi ngày trùng bảng Vãng Vong theo tháng âm.', 'medium');
        }
        foreach ([
            'thienHoa' => ['Thiên hỏa', ['lợp nhà', 'mở đường', 'cất nhà']],
            'diaHoa' => ['Địa hỏa', ['trồng cây', 'động thổ']],
            'hoaTai' => ['Hỏa tai', ['việc liên quan nhà cửa', 'lửa bếp']],
            'thienCuong' => ['Thiên Cường', ['việc lớn', 'khởi sự']],
        ] as $key => [$name, $avoid]) {
            if ((self::BRANCH_TABOOS[$key][$lunarMonth] ?? '') === $branch) {
                $items[] = self::item($name, 'bad', $avoid, 'Chi ngày trùng bảng ' . $name . ' theo tháng âm trong thông thư.', 'medium');
            }
        }
        if (in_array($lunarMonth, [1, 2, 3], true) && $branch === 'Hoi') {
            $items[] = self::item('Thổ cấm', 'bad', ['động thổ', 'đào giếng', 'chôn cất'], 'Xuân kỵ ngày Hợi theo bảng Thổ cấm.', 'medium');
        } elseif (in_array($lunarMonth, [4, 5, 6], true) && $branch === 'Dan') {
            $items[] = self::item('Thổ cấm', 'bad', ['động thổ', 'đào giếng', 'chôn cất'], 'Hạ kỵ ngày Dần theo bảng Thổ cấm.', 'medium');
        } elseif (in_array($lunarMonth, [7, 8, 9], true) && $branch === 'Than') {
            $items[] = self::item('Thổ cấm', 'bad', ['động thổ', 'đào giếng', 'chôn cất'], 'Thu kỵ ngày Thân theo bảng Thổ cấm.', 'medium');
        }
        $trungTangStems = [
            1 => ['Giap', 'Canh'], 2 => ['At', 'Tan'], 3 => ['Mau', 'Ky'], 4 => ['Binh', 'Nham'],
            5 => ['Dinh', 'Quy'], 6 => ['Ky', 'Mau'], 7 => ['Canh', 'Giap'], 8 => ['Tan', 'At'],
            9 => ['Ky'], 10 => ['Nham', 'Binh'], 11 => ['Quy', 'Dinh'], 12 => ['Mau'],
        ];
        if (in_array($stem, $trungTangStems[$lunarMonth] ?? [], true)) {
            $items[] = self::item('Trùng tang/Trùng phục', 'bad', ['hôn nhân', 'ma chay', 'cải táng'], 'Can ngày trùng bảng Trùng tang, Trùng phục theo tháng âm.', 'medium');
        }

        return $items;
    }

    /**
     * @return array<string,mixed>
     */
    private static function lucNhan(int $lunarDay, int $lunarMonth): array
    {
        $start = ($lunarMonth - 1) % 6;
        $dayIndex = ($start + $lunarDay - 1) % 6;
        $hours = [];
        foreach (self::BRANCHES as $index => $branch) {
            $hours[] = [
                'branch' => self::BRANCHES_VI[$branch],
                'result' => self::LUC_NHAM[($dayIndex + $index) % 6],
            ];
        }

        return [
            'dayResult' => self::LUC_NHAM[$dayIndex],
            'goodHours' => array_values(array_filter($hours, static fn (array $hour): bool => $hour['result']['level'] === 'good')),
            'hours' => $hours,
            'method' => 'Tháng 1/7 khởi Đại An; 2/8 Lưu Niên; 3/9 Tốc Hỷ; 4/10 Xích Khẩu; 5/11 Tiểu Cát; 6/12 Không Vong. Tính thuận theo ngày âm rồi theo giờ.',
            'confidence' => 'high',
        ];
    }

    /**
     * @return array<string,mixed>
     */
    private static function ngocHap(int $lunarMonth, string $branch, string $dayCanChi): array
    {
        $active = [];
        if ((self::NGUYET_DUC[$lunarMonth] ?? '') === $branch) {
            $active[] = ['name' => 'Nguyệt Đức', 'level' => 'good', 'summary' => 'Sao tốt, trừ tố tụng thì đa số việc khác đều lợi.'];
        }
        $ritual = self::NGOC_HAP_RITUAL[$dayCanChi] ?? [
            'level' => 'unknown',
            'summary' => 'Chưa đủ dòng Ngọc Hạp cho can chi này; cần bổ sung nếu muốn luận tế lễ/cầu phúc chi tiết.',
        ];

        return [
            'activeStars' => $active,
            'ritual' => $ritual + ['canChi' => self::viCanChi($dayCanChi)],
            'coverage' => 'partial',
            'note' => 'Đã nhập phần Nguyệt Đức và một phần Hám Chân Quân Ngọc Hạp theo lục thập hoa giáp; cần đối chiếu thêm bản rõ để đủ 60 ngày.',
        ];
    }

    /**
     * @return array{name:string,level:string,appliesTo:list<string>,note:string,confidence:string}
     */
    private static function item(string $name, string $level, array $appliesTo, string $note, string $confidence): array
    {
        return compact('name', 'level', 'appliesTo', 'note', 'confidence');
    }

    /**
     * @return array{stem:string,branch:string}
     */
    private static function splitCanChi(string $name): array
    {
        [$stem, $branch] = explode(' ', str_replace(' nhuan', '', $name), 2);

        return ['stem' => $stem, 'branch' => $branch];
    }

    private static function viCanChi(string $asciiName): string
    {
        $parts = self::splitCanChi($asciiName);

        return (self::STEMS_VI[$parts['stem']] ?? $parts['stem']) . ' ' . (self::BRANCHES_VI[$parts['branch']] ?? $parts['branch']);
    }

    /**
     * @param array<int,list<int>> $table
     */
    private static function monthDayTable(array $table): string
    {
        $parts = [];
        foreach ($table as $month => $days) {
            $parts[] = 'tháng ' . $month . ': ngày ' . implode(', ', $days);
        }

        return implode('; ', $parts);
    }

    /**
     * @param array<int,string> $table
     */
    private static function branchRuleText(array $table): string
    {
        $parts = [];
        foreach ($table as $month => $branch) {
            $parts[] = 'tháng ' . $month . ': ngày ' . (self::BRANCHES_VI[$branch] ?? $branch);
        }

        return implode('; ', $parts);
    }

    /**
     * @return array<string,array{level:string,summary:string}>
     */
    private static function readableNgocHapRitual(): array
    {
        $items = [];
        foreach (self::NGOC_HAP_RITUAL as $canChi => $rule) {
            $items[self::viCanChi($canChi)] = $rule;
        }

        return $items;
    }
}
