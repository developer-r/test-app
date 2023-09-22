<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class DateService
{
    public static function getDateFormat(?string $date = null): string
    {
        if(is_null($date))
        {
            $date = now();
        }

        $dateFormat = config('date.default_format');

        return Carbon::parse($date)->format($dateFormat);
    }
}
