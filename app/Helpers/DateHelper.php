<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function generateArrYear($startDate, $duration)
    {
        if (!$startDate) {
            return [];
        }

        $startYear = $startDate->format("Y");
        $endYear = $startDate->addMonth($duration - 1)->format("Y");

        $arrYear = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $arrYear[] = $year;
        }

        return $arrYear;
    }

    public static function isInRange($startDate, $endDate, $year, $month)
    {
        $dateToCheck = Carbon::parse($year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01");

        return $dateToCheck->gte($startDate) && $dateToCheck->lte($endDate);
    }

    public static function calcCompletionDate(Carbon $startDate, int $monthDuration)
    {
        return $startDate->addMonths($monthDuration);
    }

    public static function calcDateByQuarter($year, $quarter)
    {
        $month = 1 + (($quarter - 1) * 3);
        return $year . "-" . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
    }
}
