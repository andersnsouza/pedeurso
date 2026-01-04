<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Todas as rotas da API devem estar dentro dos grupos de middleware
| auth:sanctum e tenant, exceto rotas públicas justificadas.
|
*/

// Rotas públicas (exemplo: login, registro)
// Route::post('/login', [AuthController::class, 'login']);

// Rotas autenticadas
Route::middleware(['auth:sanctum'])->group(function (): void {
    // Rotas com tenant
    Route::middleware(['tenant'])->group(function (): void {
        // Customers
        Route::prefix('customers')->group(function (): void {
            Route::get('/', [CustomerController::class, 'index']);
            Route::post('/', [CustomerController::class, 'store']);
            Route::get('/{customer}', [CustomerController::class, 'show']);
            Route::put('/{customer}', [CustomerController::class, 'update']);
            Route::delete('/{customer}', [CustomerController::class, 'destroy']);
        });
    });
});
