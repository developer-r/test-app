<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\CurrencyValue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CurrencyService
{
    public static function addCurrenciesToDatabase(string $date, Collection $currencies): void
    {
        $date = DateService::getDateFormat($date);

        foreach ($currencies as $currency)
        {
            self::addCurrencyToDatabase($date, $currency);
        }
    }

    public static function addCurrencyToDatabase(string $date, array $currency): void
    {
        $date = DateService::getDateFormat($date);

        DB::transaction(function () use ($date, $currency) {
            $currencyModel = Currency::query()
                ->updateOrCreate(
                    [
                        'num_code' => Arr::get($currency, 'num_code'),
                        'char_code' => Arr::get($currency, 'char_code'),
                    ],
                    [
                        'nominal' => Arr::get($currency, 'nominal'),
                        'name' => Arr::get($currency, 'name'),
                    ]
                );

            CurrencyValue::query()
                ->updateOrCreate(
                    [
                        'date' => $date,
                        'currency_id' => $currencyModel->id,
                    ],
                    [
                        'value' => Arr::get($currency, 'value')
                    ]
                );
        }, 3);
    }

    public static function parsAndAddCurrencyToDatabase(string $date): void
    {
        $currencies = app(CrbService::class)->setDate($date)->parse();
        static::addCurrenciesToDatabase($date, $currencies);
    }
}
