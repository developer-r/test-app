<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetCurrencyDifferenceRequest;
use App\Http\Resources\Api\GetCurrencyDifferenceResource;
use App\Repositories\CurrencyRepository;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    /**
     * @param GetCurrencyDifferenceRequest $request
     * @return JsonResponse
     */
    public function getCurrencyDifference(GetCurrencyDifferenceRequest $request): JsonResponse
    {
        $currencyDifference = CurrencyRepository::getCurrencyDifferencByDateAndCode(
            $request->input('date'),
            $request->input('code')
        );

        return response()->json(new GetCurrencyDifferenceResource($currencyDifference));
    }
}
