<?php

namespace App\Shared\Infrastructure\Helpers;

class NumberToWords
{
    public static function convert($number, $currency = 'SOLES')
    {
        $number = number_format($number, 2, '.', '');
        $splitNumber = explode('.', $number);
        $wholePart = intval($splitNumber[0]);
        $decimalPart = $splitNumber[1];

        $wholePartInWords = self::convertNumber($wholePart);

        return 'SON: ' . mb_strtoupper($wholePartInWords) . ' CON ' . $decimalPart . '/100 ' . mb_strtoupper($currency);
    }

    private static function convertNumber($number)
    {
        if ($number == 0)
            return 'CERO';

        $units = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $tens = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $teens = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];

        if ($number < 10)
            return $units[$number];

        if ($number < 20)
            return $teens[$number - 10];

        if ($number < 30)
            return ($number == 20) ? 'VEINTE' : 'VEINTI' . $units[$number - 20];

        if ($number < 100) {
            $ten = intval($number / 10);
            $unit = $number % 10;
            return $tens[$ten] . ($unit > 0 ? ' Y ' . $units[$unit] : '');
        }

        if ($number < 1000) {
            $hundred = intval($number / 100);
            $remainder = $number % 100;
            $hundreds = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
            if ($number == 100)
                return 'CIEN';
            return $hundreds[$hundred] . ($remainder > 0 ? ' ' . self::convertNumber($remainder) : '');
        }

        if ($number < 1000000) {
            $thousand = intval($number / 1000);
            $remainder = $number % 1000;
            if ($thousand == 1)
                return 'MIL' . ($remainder > 0 ? ' ' . self::convertNumber($remainder) : '');
            return self::convertNumber($thousand) . ' MIL' . ($remainder > 0 ? ' ' . self::convertNumber($remainder) : '');
        }

        if ($number < 1000000000) {
            $million = intval($number / 1000000);
            $remainder = $number % 1000000;
            if ($million == 1)
                return 'UN MILLON' . ($remainder > 0 ? ' ' . self::convertNumber($remainder) : '');
            return self::convertNumber($million) . ' MILLONES' . ($remainder > 0 ? ' ' . self::convertNumber($remainder) : '');
        }

        return 'NUMERO MUY GRANDE';
    }
}
