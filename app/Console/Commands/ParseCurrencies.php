<?php

namespace App\Console\Commands;

use App\Services\CrbService;
use App\Services\CurrencyService;
use Illuminate\Console\Command;

class ParseCurrencies extends Command
{
    protected $signature = 'parse:currencies';

    public function handle(): void
    {
        $parseDays = config('parse.days');

        for ($subDays = 0; $subDays < $parseDays; $subDays++)
        {
            $date = now()->subDays($subDays);
            $currencies = app(CrbService::class)->setDate($date)->parse();

            CurrencyService::addCurrenciesToDatabase($date, $currencies);
        }
    }
}
