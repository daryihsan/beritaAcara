<?php

namespace App\Helpers;

use App\Helpers\TerbilangHelper;

class DateHelper
{
    public static function indoLengkap($tgl)
    {
        if (!$tgl)
            return "-";
        $bulan = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];
        [$y, $m, $d] = explode('-', $tgl);
        return $d . ' ' . $bulan[(int) $m - 1] . ' ' . $y;
    }

    public static function teksTanggal($tgl)
    {
        [$y, $m, $d] = explode('-', $tgl);
        $bulan = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];

        return [
            'tgl' => TerbilangHelper::terbilang((int) $d),
            'bln' => $bulan[(int) $m - 1],
            'thn' => TerbilangHelper::terbilang((int) $y),
        ];
    }
}
