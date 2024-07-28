<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/api/documentation', function () {
    return response()->json(json_decode(file_get_contents(storage_path('api-docs/api-docs.json'))));
});

require __DIR__.'/auth.php';
