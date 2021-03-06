<?php


use App\Http\Controllers\BatchController;
use App\Http\Controllers\ChainController;
use App\Http\Controllers\SimpleQueueController;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('queue')->group(function () {
    Route::get('/start', [SimpleQueueController::class, 'start']);
    Route::get('/logs', [SimpleQueueController::class, 'show']);
    Route::get('/logs/clear', [SimpleQueueController::class, 'clear']);
});

Route::prefix('chain')->group(function () {
    Route::get('/start', [ChainController::class, 'start']);
    Route::get('/result', [ChainController::class, 'result']);
    Route::get('/logs', [ChainController::class, 'show']);
    Route::get('/logs/clear', [ChainController::class, 'clear']);
});

Route::prefix('batch')->group(function () {
    Route::get('/start', [BatchController::class, 'start']);
    Route::get('/result', [BatchController::class, 'result']);
    Route::get('/cancel', [BatchController::class, 'cancel']);
    Route::get('/logs', [BatchController::class, 'show']);
    Route::get('/logs/clear', [BatchController::class, 'clear']);
});

Route::get('/total', function(){
   return QueueService::total();
});
