<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CrbService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function show()
    {
        $r = app(CrbService::class)->parse();

        dd($r);
    }
}
