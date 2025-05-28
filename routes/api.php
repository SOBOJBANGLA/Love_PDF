<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfMergeController;
use App\Http\Controllers\PdfSplitController;
use App\Http\Controllers\PdfCompressController;
use App\Http\Controllers\PdfConvertController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// PDF Tools API Routes
Route::prefix('pdf')->group(function () {
    // Merge PDF Routes
    Route::post('/merge', [PdfMergeController::class, 'process']);
    
    // Split PDF Routes
    Route::post('/split', [PdfSplitController::class, 'process']);
    
    // Compress PDF Routes
    Route::post('/compress', [PdfCompressController::class, 'process']);
    
    // Convert PDF Routes
    Route::post('/convert', [PdfConvertController::class, 'process']);
}); 