    <?php

    use App\Http\Controllers\AdminController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\ProvinceCityController;
    use App\Http\Controllers\UMKMController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\ProductController;

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

    Route::post('/v1/fetch-umkm-public', [UMKMController::class, 'fetch']);
    Route::post('/v1/take-umkm-public', [UMKMController::class, 'getById']);
    Route::post('/v1/login-admin', [AdminController::class, 'login']);
    Route::post('/v1/fetch-product-by-umkm-id', [ProductController::class, 'fetchByUmkmId']);
    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/v1/get-city-provence', [ProvinceCityController::class, 'getCitiesAndProvinces']);
        
        Route::post('/v1/data-dashboard', [DashboardController::class, 'getDashboard']);
        
        Route::post('/v1/add-umkm', [UMKMController::class, 'store']);
        Route::post('/v1/fetch-umkm', [UMKMController::class, 'fetch']);
        Route::post('/v1/take-umkm', [UMKMController::class, 'getById']);
        Route::post('/v1/edit-umkm', [UMKMController::class, 'editData']);
        Route::post('/v1/delete-umkm', [UMKMController::class, 'deleteData']);
            
        Route::post('/v1/add-product', [ProductController::class, 'add']);
        Route::post('/v1/fetch-products', [ProductController::class, 'fetch']);
        Route::post('/v1/take-product', [ProductController::class, 'getById']);
        Route::post('/v1/delete-product', [ProductController::class, 'delete']);
        Route::post('/v1/edit-product', [ProductController::class, 'edit']);
            
        Route::post('/v1/add-admin', [AdminController::class, 'add']);
        Route::post('/v1/delete-admin', [AdminController::class, 'delete']);
        Route::post('/v1/fetch-admin', [AdminController::class, 'fetch']);
        Route::post('/v1/take-profile', [AdminController::class, 'take']);
        
    });
