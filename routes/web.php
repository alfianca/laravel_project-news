<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/test-cache', function () {
    $value = Cache::remember('my_key', 10, function () {
        return 'Halo ini dari cache! (' . now() . ')';
    });

    return response()->json(['cached_value' => $value]);
});