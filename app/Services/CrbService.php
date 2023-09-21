<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class CrbService
{
    protected string $url;
    protected array $data;

    public function __construct()
    {
        $this->url = config('crb.url');
    }

    public function parseByDate(?string $date = null): static
    {
        $xmlContent = Http::get($this->url)->body();
        $xml = new SimpleXMLElement($xmlContent);

        foreach ($xml->Valute as $valute)
        {
            $this->data[] = [
                'num_code' => (string) $valute->NumCode,
                'char_code' => (string) $valute->CharCode,
                'nominal' => (int) $valute->Nominal,
                'name' => (string) $valute->Name,
                'value' => (string) $valute->Value
            ];
        }

        return $this;
    }
}
