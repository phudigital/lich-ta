<?php

declare(strict_types=1);

namespace LichTa;

final class DongCongCalendar
{
    private const BRANCHES = ['Ty', 'Suu', 'Dan', 'Mao', 'Thin', 'Ty.', 'Ngo', 'Mui', 'Than', 'Dau', 'Tuat', 'Hoi'];
    private const TRUC = ['Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế'];

    private const TERM_MONTHS = [
        'Lap xuan' => 1, 'Vu thuy' => 1,
        'Kinh trap' => 2, 'Xuan phan' => 2,
        'Thanh minh' => 3, 'Coc vu' => 3,
        'Lap ha' => 4, 'Tieu man' => 4,
        'Mang chung' => 5, 'Ha chi' => 5,
        'Tieu thu' => 6, 'Dai thu' => 6,
        'Lap thu' => 7, 'Xu thu' => 7,
        'Bach lo' => 8, 'Thu phan' => 8,
        'Han lo' => 9, 'Suong giang' => 9,
        'Lap dong' => 10, 'Tieu tuyet' => 10,
        'Dai tuyet' => 11, 'Dong chi' => 11,
        'Tieu han' => 12, 'Dai han' => 12,
    ];

    private const QUALITY = [
        1 => ['Kiến' => 'bad', 'Trừ' => 'bad', 'Mãn' => 'bad', 'Bình' => 'bad', 'Định' => 'good', 'Chấp' => 'bad', 'Phá' => 'bad', 'Nguy' => 'mixed', 'Thành' => 'bad', 'Thu' => 'mixed', 'Khai' => 'good', 'Bế' => 'bad'],
        2 => ['Kiến' => 'bad', 'Trừ' => 'bad', 'Mãn' => 'good', 'Bình' => 'mixed', 'Định' => 'mixed', 'Chấp' => 'good', 'Phá' => 'bad', 'Nguy' => 'mixed', 'Thành' => 'good', 'Thu' => 'bad', 'Khai' => 'bad', 'Bế' => 'mixed'],
        3 => ['Kiến' => 'bad', 'Trừ' => 'mixed', 'Mãn' => 'mixed', 'Bình' => 'bad', 'Định' => 'good', 'Chấp' => 'mixed', 'Phá' => 'bad', 'Nguy' => 'mixed', 'Thành' => 'mixed', 'Thu' => 'bad', 'Khai' => 'mixed', 'Bế' => 'bad'],
        4 => ['Kiến' => 'bad', 'Trừ' => 'good', 'Mãn' => 'mixed', 'Bình' => 'bad', 'Định' => 'mixed', 'Chấp' => 'bad', 'Phá' => 'bad', 'Nguy' => 'good', 'Thành' => 'bad', 'Thu' => 'bad', 'Khai' => 'good', 'Bế' => 'bad'],
        5 => ['Kiến' => 'mixed', 'Trừ' => 'mixed', 'Mãn' => 'mixed', 'Bình' => 'bad', 'Định' => 'good', 'Chấp' => 'mixed', 'Phá' => 'bad', 'Nguy' => 'bad', 'Thành' => 'good', 'Thu' => 'bad', 'Khai' => 'good', 'Bế' => 'good'],
        6 => ['Kiến' => 'bad', 'Trừ' => 'good', 'Mãn' => 'mixed', 'Bình' => 'mixed', 'Định' => 'good', 'Chấp' => 'good', 'Phá' => 'bad', 'Nguy' => 'mixed', 'Thành' => 'good', 'Thu' => 'mixed', 'Khai' => 'mixed', 'Bế' => 'mixed'],
        7 => ['Kiến' => 'bad', 'Trừ' => 'mixed', 'Mãn' => 'bad', 'Bình' => 'bad', 'Định' => 'good', 'Chấp' => 'bad', 'Phá' => 'bad', 'Nguy' => 'mixed', 'Thành' => 'mixed', 'Thu' => 'bad', 'Khai' => 'mixed', 'Bế' => 'mixed'],
        8 => ['Kiến' => 'bad', 'Trừ' => 'good', 'Mãn' => 'mixed', 'Bình' => 'mixed', 'Định' => 'mixed', 'Chấp' => 'good', 'Phá' => 'mixed', 'Nguy' => 'good', 'Thành' => 'good', 'Thu' => 'mixed', 'Khai' => 'mixed', 'Bế' => 'good'],
        9 => ['Kiến' => 'mixed', 'Trừ' => 'good', 'Mãn' => 'good', 'Bình' => 'mixed', 'Định' => 'good', 'Chấp' => 'mixed', 'Phá' => 'mixed', 'Nguy' => 'good', 'Thành' => 'good', 'Thu' => 'mixed', 'Khai' => 'mixed', 'Bế' => 'bad'],
        10 => ['Kiến' => 'bad', 'Trừ' => 'bad', 'Mãn' => 'bad', 'Bình' => 'mixed', 'Định' => 'good', 'Chấp' => 'mixed', 'Phá' => 'mixed', 'Nguy' => 'good', 'Thành' => 'mixed', 'Thu' => 'mixed', 'Khai' => 'good', 'Bế' => 'mixed'],
        11 => ['Kiến' => 'mixed', 'Trừ' => 'good', 'Mãn' => 'good', 'Bình' => 'bad', 'Định' => 'bad', 'Chấp' => 'good', 'Phá' => 'mixed', 'Nguy' => 'good', 'Thành' => 'good', 'Thu' => 'mixed', 'Khai' => 'mixed', 'Bế' => 'good'],
        12 => ['Kiến' => 'good', 'Trừ' => 'good', 'Mãn' => 'mixed', 'Bình' => 'mixed', 'Định' => 'mixed', 'Chấp' => 'good', 'Phá' => 'mixed', 'Nguy' => 'mixed', 'Thành' => 'good', 'Thu' => 'mixed', 'Khai' => 'mixed', 'Bế' => 'mixed'],
    ];

    /**
     * @return array{month:int,truc:string,level:string,label:string,note:string}
     */
    public static function evaluate(int $day, int $month, int $year, string $dayCanChi): array
    {
        $term = LunarCalendar::solarTermName($day, $month, $year);
        $dongMonth = self::TERM_MONTHS[$term] ?? LunarCalendar::solarToLunar($day, $month, $year)['month'];
        $branch = self::dayBranch($dayCanChi);
        $monthBranchIndex = ((int) $dongMonth + 1) % 12;
        $truc = self::TRUC[(self::branchIndex($branch) - $monthBranchIndex + 12) % 12];
        $level = self::QUALITY[(int) $dongMonth][$truc] ?? 'mixed';

        return [
            'month' => (int) $dongMonth,
            'truc' => $truc,
            'level' => $level,
            'label' => self::label($level),
            'note' => self::note((int) $dongMonth, $truc, $level),
        ];
    }

    private static function dayBranch(string $canChi): string
    {
        $parts = explode(' ', str_replace(' nhuan', '', $canChi), 2);

        return $parts[1] ?? 'Ty';
    }

    private static function branchIndex(string $branch): int
    {
        $index = array_search($branch, self::BRANCHES, true);

        return $index === false ? 0 : (int) $index;
    }

    private static function label(string $level): string
    {
        return match ($level) {
            'good' => 'Tốt',
            'bad' => 'Chưa tốt',
            default => 'Cân nhắc',
        };
    }

    private static function note(int $month, string $truc, string $level): string
    {
        $prefix = 'Đổng Công tháng ' . $month . ', trực ' . $truc . ': ';

        return $prefix . match ($level) {
            'good' => 'ưu tiên hơn khi lọc ngày cát theo lược bảng.',
            'bad' => 'nên tránh cho việc lớn nếu không có cát tinh/ngoại lệ bổ cứu.',
            default => 'có ngoại lệ theo can chi ngày hoặc loại việc, cần xem thêm chi tiết.',
        };
    }
}
