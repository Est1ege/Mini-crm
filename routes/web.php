<?php

use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/widget', [WidgetController::class, 'index'])->name('widget');

Route::middleware(['auth', 'manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::patch('/tickets/{id}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::get('/tickets/{ticketId}/download/{mediaId}', [AdminTicketController::class, 'download'])->name('tickets.download');
});
