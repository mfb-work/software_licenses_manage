<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EnvatoController;
use App\Http\Controllers\API\LicenseController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\Auth\LoginController;

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


// Public login route (if you want token-based auth)
Route::post('/login', [LoginController::class, 'login']);

// Protected routes (requires Sanctum auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/envato/verify-purchase', [EnvatoController::class, 'verifyPurchase']);
    Route::post('/licenses/generate', [LicenseController::class, 'generate']);
    Route::post('/licenses/activate', [LicenseController::class, 'activate']);
    Route::post('/licenses/deactivate', [LicenseController::class, 'deactivate']);
    Route::post('/verify-domain', [VerificationController::class, 'verifyDomain']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
