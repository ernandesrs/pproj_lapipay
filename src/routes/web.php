<?php

use Illuminate\Support\Facades\Route;

Route::post('/lapipay/postback', function (\Illuminate\Http\Request $request) {

    (new \Ernandesrs\Lapipay\Services\Lapipay())->payment()->postback($request);

})->name('lapipay.postback');