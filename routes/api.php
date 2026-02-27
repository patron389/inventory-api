<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\RoleController;

/*
|--------------------------------------------------------------------------
| Public Routes (no login required)
|--------------------------------------------------------------------------
*/

// health check endpoint (for testing server)
// Route::get('/ping', function () {
//     return response()->json([
//         'message' => 'API working'
//     ]);
// });

// user login
Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Routes 
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // get currently authenticated user
    Route::get('/me', [AuthController::class, 'me']);

    // logout current session
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:user.view');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user.delete');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:user.create');
        
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:user.view');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:user.update');

    /*
    |--------------------------------------------------------------------------
    | Warehouse Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:warehouse.view')
        ->get('/warehouses', [WarehouseController::class, 'index']);

    Route::middleware('permission:warehouse.create')
        ->post('/warehouses', [WarehouseController::class, 'store']);

    Route::middleware('permission:warehouse.update')
        ->put('/warehouses/{warehouse}', [WarehouseController::class, 'update']);

    Route::middleware('permission:warehouse.delete')
        ->delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Product Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:product.view')
        ->get('/products', [ProductController::class, 'index']);

    Route::middleware('permission:product.create')
        ->post('/products', [ProductController::class, 'store']);

    Route::middleware('permission:product.update')
        ->put('/products/{product}', [ProductController::class, 'update']);

    Route::middleware('permission:product.delete')
        ->delete('/products/{product}', [ProductController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Stock Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:stock.add')
        ->post('/stocks/deduct', [StockController::class, 'deduct']);

    Route::middleware('permission:stock.transfer')
        ->post('/transfers', [TransferController::class, 'store']);

    Route::middleware('permission:stock.view')
        ->get('/stock-movements', [StockMovementController::class, 'index']);

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    /*
    |--------------------------------------------------------------------------
    | Dashboard Summary/Report
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/reports/stock-per-warehouse', [DashboardController::class, 'stockPerWarehouse']);
    Route::get('/reports/low-stock', [DashboardController::class, 'lowStock']);
});
