<?php

namespace App\Jobs;

use App\Services\CurrencyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;
use Exception;

class ParseCurrenciesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $date;
    protected Collection $currencies;

    /**
     * Create a new job instance.
     */
    public function __construct(string $date, Collection $currencies)
    {
        $this->date = $date;
        $this->currencies = $currencies;
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        CurrencyService::addCurrenciesToDatabase($this->date, $this->currencies);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error($exception->getMessage());
    }
}
