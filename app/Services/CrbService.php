<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class CrbService
{
    protected string $url;
    protected string $date;
    protected string $currencyCode;

    public function __construct()
    {
        $this->url = config('crb.url');
    }

    public function setDate(string $date): static
    {
        $this->date = Carbon::parse($date)->format('d/m/Y');
        return $this;
    }

    public function setCurrencyCode(string $currencyCode): static
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    public function parse(): Collection
    {
        $content = $this->getContent();
        $xml = $this->toXml($content);

        $currencies = collect([]);
        foreach ($xml->Valute as $valute)
        {
            $currencies->push([
                'num_code' => (string) $valute->NumCode,
                'char_code' => (string) $valute->CharCode,
                'nominal' => (int) $valute->Nominal,
                'name' => (string) $valute->Name,
                'value' => (string) $valute->Value
            ]);
        }

        return $currencies;
    }

    public function parseByCode(): array
    {
        return $this->parse()
            ->filter(function ($item) {
                return Arr::get($item, 'char_code') == $this->currencyCode;
            })
            ->first() ?? [];
    }

    protected function getContent(): string
    {
        return Http::retry(3, 100)->get($this->url, [
            'date_req' => $this->date
        ])->body();
    }

    protected function toXml(string $content): SimpleXMLElement
    {
        return new SimpleXMLElement($content);
    }
}
