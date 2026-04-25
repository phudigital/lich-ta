<?php

declare(strict_types=1);

namespace LichTa;

final class DayFortune
{
    private const STEMS = ['Giap', 'At', 'Binh', 'Dinh', 'Mau', 'Ky', 'Canh', 'Tan', 'Nham', 'Quy'];
    private const BRANCHES = ['Ty', 'Suu', 'Dan', 'Mao', 'Thin', 'Ty.', 'Ngo', 'Mui', 'Than', 'Dau', 'Tuat', 'Hoi'];

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

    private const TRUC = ['Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế'];
    private const LUC_DIEU = ['Đại An', 'Lưu Niên', 'Tốc Hỷ', 'Xích Khẩu', 'Tiểu Cát', 'Không Vong'];
    private const LUC_DIEU_HINTS = [
        'Đại An' => 'Ngày thiên về ổn định, nên chọn việc chắc chắn, cầu bình an.',
        'Lưu Niên' => 'Việc dễ chậm, hợp rà soát, giữ nhịp và tránh quyết định vội.',
        'Tốc Hỷ' => 'Tốt cho tin vui, gặp gỡ, liên hệ, khai mở việc cần tiến nhanh.',
        'Xích Khẩu' => 'Dễ phát sinh lời qua tiếng lại, nên giữ lời nói và giấy tờ rõ ràng.',
        'Tiểu Cát' => 'Cát nhẹ, hợp việc vừa phải, giao dịch nhỏ, thăm hỏi, khởi sự gọn.',
        'Không Vong' => 'Nên thận trọng, hạn chế việc lớn hoặc việc cần kết quả chắc chắn.',
    ];

    private const TWENTY_EIGHT_STARS = [
        'Giác', 'Cang', 'Đê', 'Phòng', 'Tâm', 'Vĩ', 'Cơ',
        'Đẩu', 'Ngưu', 'Nữ', 'Hư', 'Nguy', 'Thất', 'Bích',
        'Khuê', 'Lâu', 'Vị', 'Mão', 'Tất', 'Chủy', 'Sâm',
        'Tỉnh', 'Quỷ', 'Liễu', 'Tinh', 'Trương', 'Dực', 'Chẩn',
    ];

    private const NAP_AM = [
        'Giáp Tý' => 'Hải Trung Kim', 'Ất Sửu' => 'Hải Trung Kim',
        'Bính Dần' => 'Lư Trung Hỏa', 'Đinh Mão' => 'Lư Trung Hỏa',
        'Mậu Thìn' => 'Đại Lâm Mộc', 'Kỷ Tỵ' => 'Đại Lâm Mộc',
        'Canh Ngọ' => 'Lộ Bàng Thổ', 'Tân Mùi' => 'Lộ Bàng Thổ',
        'Nhâm Thân' => 'Kiếm Phong Kim', 'Quý Dậu' => 'Kiếm Phong Kim',
        'Giáp Tuất' => 'Sơn Đầu Hỏa', 'Ất Hợi' => 'Sơn Đầu Hỏa',
        'Bính Tý' => 'Giản Hạ Thủy', 'Đinh Sửu' => 'Giản Hạ Thủy',
        'Mậu Dần' => 'Thành Đầu Thổ', 'Kỷ Mão' => 'Thành Đầu Thổ',
        'Canh Thìn' => 'Bạch Lạp Kim', 'Tân Tỵ' => 'Bạch Lạp Kim',
        'Nhâm Ngọ' => 'Dương Liễu Mộc', 'Quý Mùi' => 'Dương Liễu Mộc',
        'Giáp Thân' => 'Tuyền Trung Thủy', 'Ất Dậu' => 'Tuyền Trung Thủy',
        'Bính Tuất' => 'Ốc Thượng Thổ', 'Đinh Hợi' => 'Ốc Thượng Thổ',
        'Mậu Tý' => 'Tích Lịch Hỏa', 'Kỷ Sửu' => 'Tích Lịch Hỏa',
        'Canh Dần' => 'Tùng Bách Mộc', 'Tân Mão' => 'Tùng Bách Mộc',
        'Nhâm Thìn' => 'Trường Lưu Thủy', 'Quý Tỵ' => 'Trường Lưu Thủy',
        'Giáp Ngọ' => 'Sa Trung Kim', 'Ất Mùi' => 'Sa Trung Kim',
        'Bính Thân' => 'Sơn Hạ Hỏa', 'Đinh Dậu' => 'Sơn Hạ Hỏa',
        'Mậu Tuất' => 'Bình Địa Mộc', 'Kỷ Hợi' => 'Bình Địa Mộc',
        'Canh Tý' => 'Bích Thượng Thổ', 'Tân Sửu' => 'Bích Thượng Thổ',
        'Nhâm Dần' => 'Kim Bạch Kim', 'Quý Mão' => 'Kim Bạch Kim',
        'Giáp Thìn' => 'Phú Đăng Hỏa', 'Ất Tỵ' => 'Phú Đăng Hỏa',
        'Bính Ngọ' => 'Thiên Hà Thủy', 'Đinh Mùi' => 'Thiên Hà Thủy',
        'Mậu Thân' => 'Đại Dịch Thổ', 'Kỷ Dậu' => 'Đại Dịch Thổ',
        'Canh Tuất' => 'Thoa Xuyến Kim', 'Tân Hợi' => 'Thoa Xuyến Kim',
        'Nhâm Tý' => 'Tang Đố Mộc', 'Quý Sửu' => 'Tang Đố Mộc',
        'Giáp Dần' => 'Đại Khê Thủy', 'Ất Mão' => 'Đại Khê Thủy',
        'Bính Thìn' => 'Sa Trung Thổ', 'Đinh Tỵ' => 'Sa Trung Thổ',
        'Mậu Ngọ' => 'Thiên Thượng Hỏa', 'Kỷ Mùi' => 'Thiên Thượng Hỏa',
        'Canh Thân' => 'Thạch Lựu Mộc', 'Tân Dậu' => 'Thạch Lựu Mộc',
        'Nhâm Tuất' => 'Đại Hải Thủy', 'Quý Hợi' => 'Đại Hải Thủy',
    ];

    private const HOANG_DAO_STARS = ['Thanh Long', 'Minh Đường', 'Kim Quỹ', 'Thiên Đức', 'Ngọc Đường', 'Tư Mệnh'];
    private const HAC_DAO_STARS = ['Thiên Hình', 'Chu Tước', 'Bạch Hổ', 'Thiên Lao', 'Nguyên Vũ', 'Câu Trận'];
    private const MONTH_QUALITY = [
        1 => ['Ty' => 'Thanh Long', 'Suu' => 'Minh Đường', 'Thin' => 'Kim Quỹ', 'Ty.' => 'Thiên Đức', 'Mui' => 'Ngọc Đường', 'Tuat' => 'Tư Mệnh'],
        2 => ['Dan' => 'Thanh Long', 'Mao' => 'Minh Đường', 'Ngo' => 'Kim Quỹ', 'Mui' => 'Thiên Đức', 'Dau' => 'Ngọc Đường', 'Ty' => 'Tư Mệnh'],
        3 => ['Thin' => 'Thanh Long', 'Ty.' => 'Minh Đường', 'Than' => 'Kim Quỹ', 'Dau' => 'Thiên Đức', 'Hoi' => 'Ngọc Đường', 'Dan' => 'Tư Mệnh'],
        4 => ['Ngo' => 'Thanh Long', 'Mui' => 'Minh Đường', 'Tuat' => 'Kim Quỹ', 'Hoi' => 'Thiên Đức', 'Suu' => 'Ngọc Đường', 'Thin' => 'Tư Mệnh'],
        5 => ['Than' => 'Thanh Long', 'Dau' => 'Minh Đường', 'Ty' => 'Kim Quỹ', 'Suu' => 'Thiên Đức', 'Mao' => 'Ngọc Đường', 'Ngo' => 'Tư Mệnh'],
        6 => ['Tuat' => 'Thanh Long', 'Hoi' => 'Minh Đường', 'Dan' => 'Kim Quỹ', 'Mao' => 'Thiên Đức', 'Ty.' => 'Ngọc Đường', 'Than' => 'Tư Mệnh'],
        7 => ['Ty' => 'Thanh Long', 'Suu' => 'Minh Đường', 'Thin' => 'Kim Quỹ', 'Ty.' => 'Thiên Đức', 'Mui' => 'Ngọc Đường', 'Tuat' => 'Tư Mệnh'],
        8 => ['Dan' => 'Thanh Long', 'Mao' => 'Minh Đường', 'Ngo' => 'Kim Quỹ', 'Mui' => 'Thiên Đức', 'Dau' => 'Ngọc Đường', 'Ty' => 'Tư Mệnh'],
        9 => ['Thin' => 'Thanh Long', 'Ty.' => 'Minh Đường', 'Than' => 'Kim Quỹ', 'Dau' => 'Thiên Đức', 'Hoi' => 'Ngọc Đường', 'Dan' => 'Tư Mệnh'],
        10 => ['Ngo' => 'Thanh Long', 'Mui' => 'Minh Đường', 'Tuat' => 'Kim Quỹ', 'Hoi' => 'Thiên Đức', 'Suu' => 'Ngọc Đường', 'Thin' => 'Tư Mệnh'],
        11 => ['Than' => 'Thanh Long', 'Dau' => 'Minh Đường', 'Ty' => 'Kim Quỹ', 'Suu' => 'Thiên Đức', 'Mao' => 'Ngọc Đường', 'Ngo' => 'Tư Mệnh'],
        12 => ['Tuat' => 'Thanh Long', 'Hoi' => 'Minh Đường', 'Dan' => 'Kim Quỹ', 'Mao' => 'Thiên Đức', 'Ty.' => 'Ngọc Đường', 'Than' => 'Tư Mệnh'],
    ];

    /**
     * @return array<string,mixed>
     */
    public static function forSolarDate(int $day, int $month, int $year): array
    {
        $lunar = LunarCalendar::solarToLunar($day, $month, $year);
        $canChi = LunarCalendar::canChiForSolarDate($day, $month, $year);
        $dayParts = self::splitCanChi($canChi['day']);
        $monthParts = self::splitCanChi($canChi['month']);
        $dayName = self::viCanChi($canChi['day']);
        $branchIndex = self::branchIndex($dayParts['branch']);
        $monthBranchIndex = self::branchIndex($monthParts['branch']);
        $truc = self::TRUC[($branchIndex - $monthBranchIndex + 12) % 12];
        $lucDieu = self::LUC_DIEU[((int) $lunar['month'] + (int) $lunar['day'] - 2) % 6];
        $quality = self::MONTH_QUALITY[(int) $lunar['month']][$dayParts['branch']] ?? null;
        $isHoangDao = $quality !== null && in_array($quality, self::HOANG_DAO_STARS, true);
        $isHacDao = $quality !== null && in_array($quality, self::HAC_DAO_STARS, true);

        return [
            'julianDay' => (int) $lunar['julianDay'],
            'truc' => $truc,
            'lucDieu' => $lucDieu,
            'lucDieuHint' => self::LUC_DIEU_HINTS[$lucDieu],
            'saoNhiThapBatTu' => self::TWENTY_EIGHT_STARS[((int) $lunar['julianDay'] + 11) % 28],
            'napAm' => self::NAP_AM[$dayName] ?? '',
            'napAmElement' => self::elementFromNapAm(self::NAP_AM[$dayName] ?? ''),
            'hoangHacDao' => $quality === null ? 'Bình thường' : ($isHoangDao ? 'Hoàng đạo' : ($isHacDao ? 'Hắc đạo' : 'Bình thường')),
            'hoangHacDaoStar' => $quality,
            'ngayXung' => self::oppositeBranchLabel($dayParts['branch']),
            'tuoiXung' => self::conflictAges($dayParts['stem'], $dayParts['branch']),
            'ngayTot' => self::goodDayMarkers((int) $lunar['day'], (int) $lunar['month'], $truc, $isHoangDao),
            'ngayXau' => self::badDayMarkers((int) $lunar['day'], $truc, $isHacDao),
            'sourceNote' => 'Bộ trường được thiết kế lại từ các quy tắc lịch Việt truyền thống; không copy dữ liệu JS obfuscated của bên thứ ba.',
        ];
    }

    public static function elementFromNapAm(string $napAm): string
    {
        foreach (['Kim', 'Mộc', 'Thủy', 'Hỏa', 'Thổ'] as $element) {
            if (str_ends_with($napAm, $element)) {
                return $element;
            }
        }

        return '';
    }

    /**
     * @return array{stem:string, branch:string}
     */
    private static function splitCanChi(string $name): array
    {
        $clean = str_replace(' nhuan', '', $name);
        [$stem, $branch] = explode(' ', $clean, 2);

        return ['stem' => $stem, 'branch' => $branch];
    }

    private static function viCanChi(string $asciiName): string
    {
        $parts = explode(' ', str_replace(' nhuan', '', $asciiName));

        return (self::STEMS_VI[$parts[0]] ?? $parts[0]) . ' ' . (self::BRANCHES_VI[$parts[1]] ?? $parts[1]);
    }

    private static function branchIndex(string $branch): int
    {
        $index = array_search($branch, self::BRANCHES, true);

        return $index === false ? 0 : (int) $index;
    }

    private static function oppositeBranchLabel(string $branch): string
    {
        return self::BRANCHES_VI[self::BRANCHES[(self::branchIndex($branch) + 6) % 12]];
    }

    /**
     * @return list<string>
     */
    private static function conflictAges(string $stem, string $branch): array
    {
        $stemIndex = array_search($stem, self::STEMS, true);
        $branchIndex = self::branchIndex($branch);
        $oppositeBranch = self::BRANCHES[($branchIndex + 6) % 12];
        $firstStem = self::STEMS[((int) $stemIndex + 2) % 10];
        $secondStem = self::STEMS[((int) $stemIndex + 8) % 10];

        return [
            self::STEMS_VI[$firstStem] . ' ' . self::BRANCHES_VI[$oppositeBranch],
            self::STEMS_VI[$secondStem] . ' ' . self::BRANCHES_VI[$oppositeBranch],
        ];
    }

    /**
     * @return list<string>
     */
    private static function goodDayMarkers(int $lunarDay, int $lunarMonth, string $truc, bool $isHoangDao): array
    {
        $markers = [];
        if ($isHoangDao) {
            $markers[] = 'Hoàng đạo';
        }
        if (in_array($truc, ['Thành', 'Khai', 'Định'], true)) {
            $markers[] = 'Trực ' . $truc;
        }
        if ($lunarDay === 1 || $lunarDay === 15) {
            $markers[] = $lunarDay === 1 ? 'Sóc' : 'Vọng';
        }
        if ($lunarMonth === 1 && $lunarDay <= 3) {
            $markers[] = 'Tết Nguyên Đán';
        }

        return $markers;
    }

    /**
     * @return list<string>
     */
    private static function badDayMarkers(int $lunarDay, string $truc, bool $isHacDao): array
    {
        $markers = [];
        if ($isHacDao) {
            $markers[] = 'Hắc đạo';
        }
        if (in_array($lunarDay, [5, 14, 23], true)) {
            $markers[] = 'Nguyệt kỵ';
        }
        if (in_array($lunarDay, [3, 7, 13, 18, 22, 27], true)) {
            $markers[] = 'Tam nương';
        }
        if (in_array($truc, ['Phá', 'Nguy', 'Bế'], true)) {
            $markers[] = 'Trực ' . $truc;
        }

        return $markers;
    }
}
