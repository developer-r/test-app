<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class GetCurrencyDifferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => Arr::get($this->resource, 'id'),
            'name' => Arr::get($this->resource, 'name'),
            'code' => Arr::get($this->resource, 'code'),
            'value' => Arr::get($this->resource,'value'),
            'differenc' => Arr::get($this->resource, 'differenc'),
        ];
    }
}
