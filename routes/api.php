<?php

use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/tickets', [TicketController::class, 'store'])
    ->middleware('throttle:tickets');
Route::get('/tickets/statistics', [StatisticsController::class, 'index'])
    ->middleware('auth:sanctum');
