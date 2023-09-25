<?php

namespace App\Repositories;

use App\Models\CurrencyValue;
use App\Services\CurrencyService;
use App\Services\DateService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CurrencyRepository
{
    public static function format(?string $value = null): string|null
    {
        if(is_null($value))
        {
            return null;
        }

        return str_replace(',', '.', $value);
    }

    public static function getByDateAndCode(string $date, string $code): CurrencyValue|null
    {
        $date = DateService::getDateFormat($date);

        return CurrencyValue::query()
            ->with('currency')
            ->where('date', $date)
            ->whereHas('currency', function (Builder $query) use ($code) {
                $query->where('char_code', $code);
            })
            ->first();
    }

    public static function getByDateAndCodeFromCache(string $date, string $code): CurrencyValue|null
    {
        $date = DateService::getDateFormat($date);
        $cacheKey = $code . '_' . $date;

        return cache()->remember($cacheKey, 86400, function () use ($date, $code) {
            return self::getByDateAndCode($date, $code);
        });
    }

    public static function getCurrencyDifferencByDateAndCode(string $date, string $code): array
    {
        $currency = self::getByDateAndCodeFromCache($date, $code);
        if(is_null($currency))
        {
            CurrencyService::parsAndAddCurrencyToDatabase($date);
            $currency = self::getByDateAndCodeFromCache($date, $code);
        }

        $date = Carbon::parse($date)->subDays();
        $currencyDifferenc = self::getByDateAndCodeFromCache($date, $code);
        if(is_null($currencyDifferenc))
        {
            CurrencyService::parsAndAddCurrencyToDatabase($date);
            $currencyDifferenc = self::getByDateAndCodeFromCache($date, $code);
        }

        return [
            'id' => $currency?->currency->id,
            'name' => $currency?->currency->name,
            'code' => $currency?->currency->char_code,
            'value' => self::format($currency?->value),
            'difference' => bcsub(
                self::format($currency?->value),
                self::format($currencyDifferenc?->value),
                4
            ),
        ];
    }
}
