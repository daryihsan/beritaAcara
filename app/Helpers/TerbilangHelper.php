<?php

namespace App\Helpers;

class TerbilangHelper
{
    public static function penyebut($nilai)
    {
        $nilai = abs((int) $nilai);
        $huruf = [
            "",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
            "Sebelas"
        ];

        if ($nilai < 12)
            return " " . $huruf[$nilai];
        if ($nilai < 20)
            return self::penyebut($nilai - 10) . " Belas";
        if ($nilai < 100)
            return self::penyebut($nilai / 10) . " Puluh" . self::penyebut($nilai % 10);
        if ($nilai < 200)
            return " Seratus" . self::penyebut($nilai - 100);
        if ($nilai < 1000)
            return self::penyebut($nilai / 100) . " Ratus" . self::penyebut($nilai % 100);
        if ($nilai < 2000)
            return " Seribu" . self::penyebut($nilai - 1000);
        if ($nilai < 1000000)
            return self::penyebut($nilai / 1000) . " Ribu" . self::penyebut($nilai % 1000);

        return "";
    }

    public static function terbilang($nilai)
    {
        if ($nilai == 0)
            return "Nol";
        return ucwords(strtolower(trim(self::penyebut($nilai))));
    }
}
