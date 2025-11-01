<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'API Audiophile Laravel rodando com sucesso 🚀']);
});