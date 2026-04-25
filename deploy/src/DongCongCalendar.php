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

    private const RULES = [
        1 => [
            'Kiến' => ['default' => 'bad'],
            'Trừ' => ['default' => 'bad'],
            'Mãn' => ['default' => 'bad', 'canChi' => ['Giap Thin' => 'bad', 'Mau Thin' => 'bad']],
            'Bình' => ['default' => 'bad'],
            'Định' => ['default' => 'good'],
            'Chấp' => ['default' => 'bad', 'canChi' => ['At Mui' => 'bad']],
            'Phá' => ['default' => 'bad', 'canChi' => ['Canh Than' => 'bad']],
            'Nguy' => ['default' => 'bad', 'canChi' => ['Dinh Dau' => 'good', 'Tan Dau' => 'bad']],
            'Thành' => ['default' => 'bad', 'canChi' => ['Binh Tuat' => 'bad', 'Mau Tuat' => 'bad', 'Canh Tuat' => 'bad', 'Nham Tuat' => 'bad']],
            'Thu' => ['default' => 'bad', 'canChi' => ['Tan Hoi' => 'mixed']],
            'Khai' => ['default' => 'bad', 'canChi' => ['Mau Ty' => 'good', 'Binh Ty' => 'good', 'Canh Ty' => 'good', 'Giap Ty' => 'bad', 'Nham Ty' => 'bad']],
            'Bế' => ['default' => 'bad'],
        ],
        2 => [
            'Kiến' => ['default' => 'bad'],
            'Trừ' => ['default' => 'bad', 'canChi' => ['Giap Thin' => 'bad', 'Mau Thin' => 'bad']],
            'Mãn' => ['default' => 'good'],
            'Bình' => ['default' => 'mixed'],
            'Định' => ['default' => 'bad', 'canChi' => ['Quy Mui' => 'good', 'At Mui' => 'bad']],
            'Chấp' => ['default' => 'good', 'canChi' => ['Canh Than' => 'bad']],
            'Phá' => ['default' => 'bad', 'canChi' => ['Tan Dau' => 'bad']],
            'Nguy' => ['default' => 'mixed', 'canChi' => ['Binh Tuat' => 'bad', 'Nham Tuat' => 'bad']],
            'Thành' => ['default' => 'good', 'canChi' => ['Tan Hoi' => 'good', 'Quy Hoi' => 'good']],
            'Thu' => ['default' => 'bad'],
            'Khai' => ['default' => 'bad', 'canChi' => ['Dinh Suu' => 'bad', 'Quy Suu' => 'bad']],
            'Bế' => ['default' => 'mixed'],
        ],
        3 => [
            'Kiến' => ['default' => 'bad', 'canChi' => ['Giap Thin' => 'bad', 'Mau Thin' => 'bad']],
            'Trừ' => ['default' => 'bad', 'canChi' => ['Dinh Ty.' => 'good', 'Ky Ty.' => 'good', 'At Ty.' => 'bad', 'Tan Ty.' => 'bad', 'Quy Ty.' => 'bad']],
            'Mãn' => ['default' => 'mixed', 'canChi' => ['Nham Ngo' => 'mixed', 'Canh Ngo' => 'bad', 'Mau Ngo' => 'bad']],
            'Bình' => ['default' => 'bad', 'canChi' => ['At Mui' => 'bad']],
            'Định' => ['default' => 'mixed', 'canChi' => ['Giap Than' => 'good', 'Binh Than' => 'good', 'Nham Than' => 'good', 'Mau Than' => 'bad', 'Canh Than' => 'bad']],
            'Chấp' => ['default' => 'mixed', 'canChi' => ['At Dau' => 'good', 'Quy Dau' => 'good', 'Dinh Dau' => 'mixed', 'Tan Dau' => 'bad']],
            'Phá' => ['default' => 'bad', 'canChi' => ['Binh Tuat' => 'bad', 'Nham Tuat' => 'bad']],
            'Nguy' => ['default' => 'mixed', 'canChi' => ['Ky Hoi' => 'mixed', 'At Hoi' => 'mixed', 'Tan Hoi' => 'bad', 'Dinh Hoi' => 'bad', 'Quy Hoi' => 'bad']],
            'Thành' => ['default' => 'mixed', 'canChi' => ['Nham Ty' => 'mixed']],
            'Thu' => ['default' => 'bad', 'canChi' => ['Dinh Suu' => 'bad', 'Quy Suu' => 'bad']],
            'Khai' => ['default' => 'mixed', 'canChi' => ['Mau Dan' => 'good', 'Nham Dan' => 'good']],
            'Bế' => ['default' => 'bad'],
        ],
        4 => [
            'Kiến' => ['default' => 'bad'],
            'Trừ' => ['default' => 'mixed', 'canChi' => ['Giap Ngo' => 'good', 'Nham Ngo' => 'good', 'Canh Ngo' => 'mixed', 'Binh Ngo' => 'bad', 'Mau Ngo' => 'bad']],
            'Mãn' => ['default' => 'mixed', 'canChi' => ['Tan Mui' => 'mixed', 'Ky Mui' => 'mixed']],
            'Bình' => ['default' => 'bad', 'canChi' => ['Giap Than' => 'bad', 'Canh Than' => 'bad']],
            'Định' => ['default' => 'mixed'],
            'Chấp' => ['default' => 'bad', 'canChi' => ['Giap Tuat' => 'mixed', 'Binh Tuat' => 'bad', 'Nham Tuat' => 'bad']],
            'Phá' => ['default' => 'bad', 'canChi' => ['Quy Hoi' => 'bad']],
            'Nguy' => ['default' => 'bad', 'canChi' => ['Binh Ty' => 'good', 'Mau Ty' => 'good', 'Canh Ty' => 'good', 'Giap Ty' => 'bad', 'Nham Ty' => 'bad']],
            'Thành' => ['default' => 'bad', 'canChi' => ['Dinh Suu' => 'bad', 'Quy Suu' => 'bad']],
            'Thu' => ['default' => 'bad'],
            'Khai' => ['default' => 'mixed', 'canChi' => ['Quy Mao' => 'good', 'At Mao' => 'good', 'Tan Mao' => 'mixed']],
            'Bế' => ['default' => 'bad', 'canChi' => ['Mau Thin' => 'bad', 'Giap Thin' => 'bad', 'Canh Thin' => 'bad']],
        ],
        5 => [
            'Kiến' => ['default' => 'bad', 'canChi' => ['Giap Ngo' => 'mixed']],
            'Trừ' => ['default' => 'mixed', 'canChi' => ['At Mui' => 'bad']],
            'Mãn' => ['default' => 'mixed', 'canChi' => ['Giap Than' => 'mixed', 'Binh Than' => 'mixed', 'Mau Than' => 'mixed', 'Canh Than' => 'bad']],
            'Bình' => ['default' => 'bad'],
            'Định' => ['default' => 'good', 'canChi' => ['Giap Tuat' => 'good', 'Canh Tuat' => 'good', 'Mau Tuat' => 'good', 'Binh Tuat' => 'mixed', 'Nham Tuat' => 'mixed']],
            'Chấp' => ['default' => 'mixed', 'canChi' => ['At Hoi' => 'mixed', 'Dinh Hoi' => 'mixed', 'Ky Hoi' => 'mixed', 'Quy Hoi' => 'bad']],
            'Phá' => ['default' => 'bad', 'canChi' => ['Nham Ty' => 'bad']],
            'Nguy' => ['default' => 'bad', 'canChi' => ['Dinh Suu' => 'bad', 'Quy Suu' => 'bad']],
            'Thành' => ['default' => 'good', 'canChi' => ['Binh Dan' => 'good', 'Canh Dan' => 'good', 'Mau Dan' => 'good', 'Giap Dan' => 'good', 'Nham Dan' => 'mixed']],
            'Thu' => ['default' => 'bad'],
            'Khai' => ['default' => 'good', 'canChi' => ['Binh Thin' => 'good', 'Canh Thin' => 'good', 'Nham Thin' => 'good', 'Mau Thin' => 'bad', 'Giap Thin' => 'bad']],
            'Bế' => ['default' => 'bad', 'canChi' => ['At Ty.' => 'good', 'Tan Ty.' => 'good']],
        ],
        6 => [
            'Kiến' => ['default' => 'bad', 'canChi' => ['At Mui' => 'bad']],
            'Trừ' => ['default' => 'good', 'canChi' => ['Giap Than' => 'good', 'Binh Than' => 'bad', 'Canh Than' => 'mixed']],
            'Mãn' => ['default' => 'mixed', 'canChi' => ['At Dau' => 'mixed', 'Tan Dau' => 'mixed']],
            'Bình' => ['default' => 'mixed', 'canChi' => ['Giap Tuat' => 'mixed']],
            'Định' => ['default' => 'mixed', 'canChi' => ['Dinh Hoi' => 'good', 'At Hoi' => 'good', 'Ky Hoi' => 'good', 'Tan Hoi' => 'bad', 'Quy Hoi' => 'bad']],
            'Chấp' => ['default' => 'mixed', 'canChi' => ['Binh Ty' => 'good', 'Canh Ty' => 'good', 'Mau Ty' => 'mixed', 'Giap Ty' => 'bad', 'Nham Ty' => 'bad']],
            'Phá' => ['default' => 'bad', 'canChi' => ['Dinh Suu' => 'bad', 'Quy Suu' => 'bad']],
            'Nguy' => ['default' => 'mixed', 'canChi' => ['Giap Dan' => 'good']],
            'Thành' => ['default' => 'good', 'canChi' => ['At Mao' => 'good', 'Tan Mao' => 'good']],
            'Thu' => ['default' => 'mixed', 'canChi' => ['Giap Thin' => 'mixed', 'Binh Thin' => 'mixed', 'Nham Thin' => 'mixed', 'Canh Thin' => 'bad', 'Mau Thin' => 'bad']],
            'Khai' => ['default' => 'bad', 'canChi' => ['At Ty.' => 'mixed', 'Quy Ty.' => 'mixed']],
            'Bế' => ['default' => 'bad', 'canChi' => ['Giap Ngo' => 'mixed', 'Binh Ngo' => 'mixed', 'Nham Ngo' => 'mixed', 'Canh Ngo' => 'mixed', 'Mau Ngo' => 'bad']],
        ],
        7 => [
            'Kiến' => ['default' => 'bad', 'canChi' => ['Mau Than' => 'mixed', 'Canh Than' => 'bad', 'Binh Than' => 'bad']],
            'Trừ' => ['default' => 'mixed', 'canChi' => ['At Dau' => 'mixed']],
            'Mãn' => ['default' => 'bad', 'canChi' => ['Binh Tuat' => 'bad', 'Nham Tuat' => 'bad']],
            'Bình' => ['default' => 'bad'],
            'Định' => ['default' => 'mixed', 'canChi' => ['Binh Ty' => 'good']],
            'Chấp' => ['default' => 'bad'],
            'Phá' => ['default' => 'bad', 'canChi' => ['Giap Dan' => 'bad', 'Canh Dan' => 'bad', 'Mau Dan' => 'bad', 'Binh Dan' => 'bad']],
            'Nguy' => ['default' => 'mixed', 'canChi' => ['Quy Mao' => 'good', 'Dinh Mao' => 'good', 'At Mao' => 'bad']],
            'Thành' => ['default' => 'mixed', 'canChi' => ['Nham Thin' => 'mixed', 'Canh Thin' => 'mixed', 'Binh Thin' => 'mixed']],
            'Thu' => ['default' => 'bad'],
            'Khai' => ['default' => 'mixed', 'canChi' => ['Nham Ngo' => 'mixed', 'Binh Ngo' => 'mixed', 'Mau Ngo' => 'mixed']],
            'Bế' => ['default' => 'mixed', 'canChi' => ['Quy Mui' => 'mixed']],
        ],
        8 => [
            'Kiến' => ['default' => 'bad'],
            'Trừ' => ['default' => 'good', 'canChi' => ['Canh Tuat' => 'good', 'Mau Tuat' => 'good', 'Giap Tuat' => 'good']],
            'Mãn' => ['default' => 'mixed'],
            'Bình' => ['default' => 'mixed', 'canChi' => ['Binh Ty' => 'mixed']],
            'Định' => ['default' => 'mixed', 'canChi' => ['Tan Suu' => 'mixed', 'Quy Suu' => 'mixed', 'At Suu' => 'mixed', 'Dinh Suu' => 'mixed', 'Ky Suu' => 'bad']],
            'Chấp' => ['default' => 'good', 'canChi' => ['Canh Dan' => 'good']],
            'Phá' => ['default' => 'mixed', 'canChi' => ['Quy Mao' => 'mixed', 'At Mao' => 'mixed']],
            'Nguy' => ['default' => 'good', 'canChi' => ['Nham Thin' => 'good', 'Binh Thin' => 'good']],
            'Thành' => ['default' => 'good', 'canChi' => ['At Ty.' => 'good', 'Ky Ty.' => 'good']],
            'Thu' => ['default' => 'mixed', 'canChi' => ['Nham Ngo' => 'mixed']],
            'Khai' => ['default' => 'mixed', 'canChi' => ['Dinh Mui' => 'mixed', 'Ky Mui' => 'mixed', 'Tan Mui' => 'mixed', 'Quy Mui' => 'mixed']],
            'Bế' => ['default' => 'good', 'canChi' => ['Mau Than' => 'mixed', 'Canh Than' => 'good', 'Binh Than' => 'good']],
        ],
        9 => [
            'Kiến' => ['default' => 'bad', 'canChi' => ['Binh Tuat' => 'good']],
            'Trừ' => ['default' => 'good', 'canChi' => ['At Hoi' => 'good', 'Dinh Hoi' => 'good']],
            'Mãn' => ['default' => 'good', 'canChi' => ['Binh Ty' => 'good']],
            'Bình' => ['default' => 'mixed'],
            'Định' => ['default' => 'good', 'canChi' => ['Binh Dan' => 'good', 'Canh Dan' => 'good', 'Mau Dan' => 'good']],
            'Chấp' => ['default' => 'mixed'],
            'Phá' => ['default' => 'mixed'],
            'Nguy' => ['default' => 'good', 'canChi' => ['At Ty.' => 'good']],
            'Thành' => ['default' => 'good', 'canChi' => ['Binh Ngo' => 'good']],
            'Thu' => ['default' => 'mixed', 'canChi' => ['Tan Mui' => 'mixed', 'Quy Mui' => 'mixed']],
            'Khai' => ['default' => 'mixed', 'canChi' => ['Mau Than' => 'mixed']],
            'Bế' => ['default' => 'bad'],
        ],
        10 => [
            'Kiến' => ['default' => 'bad'],
            'Trừ' => ['default' => 'bad'],
            'Mãn' => ['default' => 'bad', 'canChi' => ['Dinh Suu' => 'bad', 'Quy Suu' => 'bad']],
            'Bình' => ['default' => 'mixed', 'canChi' => ['Giap Dan' => 'good']],
            'Định' => ['default' => 'good', 'canChi' => ['At Mao' => 'good', 'Tan Mao' => 'good', 'Ky Mao' => 'good']],
            'Chấp' => ['default' => 'mixed'],
            'Phá' => ['default' => 'bad', 'canChi' => ['At Ty.' => 'mixed']],
            'Nguy' => ['default' => 'good', 'canChi' => ['Giap Ngo' => 'good']],
            'Thành' => ['default' => 'mixed', 'canChi' => ['At Mui' => 'bad']],
            'Thu' => ['default' => 'mixed', 'canChi' => ['Giap Than' => 'mixed']],
            'Khai' => ['default' => 'good', 'canChi' => ['At Dau' => 'good']],
            'Bế' => ['default' => 'mixed', 'canChi' => ['Giap Tuat' => 'mixed']],
        ],
        11 => [
            'Kiến' => ['default' => 'mixed', 'canChi' => ['Giap Ty' => 'mixed']],
            'Trừ' => ['default' => 'good', 'canChi' => ['At Suu' => 'good']],
            'Mãn' => ['default' => 'good'],
            'Bình' => ['default' => 'bad', 'canChi' => ['Tan Mao' => 'bad']],
            'Định' => ['default' => 'bad'],
            'Chấp' => ['default' => 'good', 'canChi' => ['At Ty.' => 'good', 'Quy Ty.' => 'good', 'Ky Ty.' => 'good']],
            'Phá' => ['default' => 'mixed', 'canChi' => ['Nham Ngo' => 'mixed']],
            'Nguy' => ['default' => 'good', 'canChi' => ['Dinh Mui' => 'good', 'Ky Mui' => 'mixed']],
            'Thành' => ['default' => 'good', 'canChi' => ['Nham Than' => 'good']],
            'Thu' => ['default' => 'mixed'],
            'Khai' => ['default' => 'mixed'],
            'Bế' => ['default' => 'good', 'canChi' => ['At Hoi' => 'good', 'Ky Hoi' => 'good']],
        ],
        12 => [
            'Kiến' => ['default' => 'mixed', 'canChi' => ['At Suu' => 'good', 'Ky Suu' => 'good']],
            'Trừ' => ['default' => 'good', 'canChi' => ['Canh Dan' => 'good', 'Giap Dan' => 'good', 'Binh Dan' => 'good', 'Nham Dan' => 'good']],
            'Mãn' => ['default' => 'mixed'],
            'Bình' => ['default' => 'mixed', 'canChi' => ['Nham Thin' => 'good']],
            'Định' => ['default' => 'mixed'],
            'Chấp' => ['default' => 'good', 'canChi' => ['Canh Ngo' => 'good']],
            'Phá' => ['default' => 'mixed', 'canChi' => ['Quy Mui' => 'good', 'Dinh Mui' => 'mixed']],
            'Nguy' => ['default' => 'mixed', 'canChi' => ['Canh Than' => 'mixed']],
            'Thành' => ['default' => 'good', 'canChi' => ['At Dau' => 'good', 'Quy Dau' => 'good']],
            'Thu' => ['default' => 'mixed', 'canChi' => ['Canh Tuat' => 'mixed']],
            'Khai' => ['default' => 'mixed', 'canChi' => ['At Hoi' => 'mixed']],
            'Bế' => ['default' => 'mixed', 'canChi' => ['Canh Ty' => 'mixed']],
        ],
    ];

    /**
     * @return array{month:int,truc:string,level:string,label:string,note:string,matched:string}
     */
    public static function evaluate(int $day, int $month, int $year, string $dayCanChi): array
    {
        $term = LunarCalendar::solarTermName($day, $month, $year);
        $dongMonth = self::TERM_MONTHS[$term] ?? LunarCalendar::solarToLunar($day, $month, $year)['month'];
        $branch = self::dayBranch($dayCanChi);
        $monthBranchIndex = ((int) $dongMonth + 1) % 12;
        $truc = self::TRUC[(self::branchIndex($branch) - $monthBranchIndex + 12) % 12];
        $rule = self::RULES[(int) $dongMonth][$truc] ?? ['default' => 'mixed'];
        $level = $rule['canChi'][$dayCanChi] ?? $rule['default'] ?? 'mixed';
        $matched = isset($rule['canChi'][$dayCanChi]) ? 'canChi' : 'default';

        return [
            'month' => (int) $dongMonth,
            'truc' => $truc,
            'level' => $level,
            'label' => self::label($level),
            'note' => self::note((int) $dongMonth, $truc, $level, $matched),
            'matched' => $matched,
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

    private static function note(int $month, string $truc, string $level, string $matched): string
    {
        $prefix = 'Đổng Công tháng ' . $month . ', trực ' . $truc . ': ';
        $suffix = $matched === 'canChi' ? ' Mức này có xét ngoại lệ can chi ngày.' : ' Mức này là đánh giá mặc định của trực, chưa có ngoại lệ can chi riêng.';

        return $prefix . match ($level) {
            'good' => 'ưu tiên hơn khi lọc ngày cát theo lược bảng.',
            'bad' => 'nên tránh cho việc lớn nếu không có cát tinh/ngoại lệ bổ cứu.',
            default => 'có ngoại lệ theo can chi ngày hoặc loại việc, cần xem thêm chi tiết.',
        } . $suffix;
    }
}
