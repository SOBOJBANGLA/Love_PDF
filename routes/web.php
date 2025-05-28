<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfMergeController;
use App\Http\Controllers\PdfSplitController;
use App\Http\Controllers\PdfCompressController;
use App\Http\Controllers\PdfConvertController;

Route::get('/', function () {
    return view('welcome');
});

// PDF Tools Routes
Route::prefix('tools')->group(function () {
    // Merge PDF Routes
    Route::get('/merge', [PdfMergeController::class, 'show'])->name('tools.merge');
    Route::post('/merge/process', [PdfMergeController::class, 'process'])->name('tools.merge.process');
    
    // Split PDF Routes
    Route::get('/split', [PdfSplitController::class, 'show'])->name('tools.split');
    Route::post('/split/process', [PdfSplitController::class, 'process'])->name('tools.split.process');
    
    // Compress PDF Routes
    Route::get('/compress', [PdfCompressController::class, 'show'])->name('tools.compress');
    Route::post('/compress/process', [PdfCompressController::class, 'process'])->name('tools.compress.process');
    
    // Convert PDF Routes
    Route::get('/convert', [PdfConvertController::class, 'show'])->name('tools.convert');
    Route::post('/convert/process', [PdfConvertController::class, 'process'])->name('tools.convert.process');
});
