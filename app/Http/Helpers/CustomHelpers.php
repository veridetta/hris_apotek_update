<?php 
if (!function_exists('getMonthName')) {
    function getMonthName($monthNumber) {
        // Pastikan input selalu dalam bentuk integer
        $monthNumber = (int)$monthNumber;
        
        // Validasi agar input berada dalam rentang 1-12
        if ($monthNumber < 1 || $monthNumber > 12) {
            return 'Bulan tidak valid';
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$monthNumber] ?? '';
    }
}
if (!function_exists('convertToIndonesianDate')) {
    function convertToIndonesianDate($date) {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];
        
        // Ubah format tanggal dari "Y-m-d" ke "d M Y"
        $formattedDate = date('d F Y', strtotime($date));
        $formattedDateParts = explode(' ', $formattedDate);

        if (count($formattedDateParts) === 3) {
            $day = $formattedDateParts[0];
            $month = $months[$formattedDateParts[1]];
            $year = $formattedDateParts[2];

            return $day . ' ' . $month . ' ' . $year;
        }

        return 'Format tanggal tidak valid';
    }
}



?>