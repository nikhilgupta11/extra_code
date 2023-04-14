<?php

use App\Http\Controllers\API\{
    FrontController,
    LoginController,
    RegisterController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/lang', function (Request $request) {
    $data = [
        'lang' => $request->header('X-localization'),
        'data' => __($request->header('X-localization'))
    ];
    return response()->json($data, 200);
})->middleware('localization');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
Route::post('/reset-password', [RegisterController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        $profile = $request->user();
        $profile->profile;
        return $profile;
    });
    Route::post('/change-password', [LoginController::class, 'changePassword']);
    Route::post('/profile-update', [LoginController::class, 'update']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout.api');
    // ========== Trip APIs ==========
    Route::post('/create-trip', [FrontController::class, 'createTrip']);
    Route::get('/transports', [FrontController::class, 'transports']);
    Route::get('/transport-form', [FrontController::class, 'transportForm']);
    Route::post('/trip/{trip_id}/transport-form/save', [FrontController::class, 'transportFormSave']);
    Route::get('/trip/{trip_id}/transport-list/{transport_id}', [FrontController::class, 'transportList']);
    Route::delete('/trip/{trip_id}/transport-list/{id}/delete', [FrontController::class, 'transportListDelete']);
    Route::get('/trip/{trip_id}/calculations', [FrontController::class, 'tripCalculation']);
    Route::get('/trip/{trip_id}/history-save', [FrontController::class, 'tripSaveToHistory']);
    // ========== Calculation History APIs ==========
    Route::get('/calculation-history/list', [FrontController::class, 'calculationHistory']);
    Route::get('/calculation-history/{trip_id}/details', [FrontController::class, 'calculationDetails']);
    Route::delete('/calculation-history/{trip_id}/delete', [FrontController::class, 'calculationHistoryDelete']);
    // ========== Project APIs ==========
    Route::get('/view-projects', [FrontController::class, 'projects']);
    Route::get('/project/details', [FrontController::class, 'projectDetails']);
    // ========== Page APIs ==========
    Route::get('/view-pages', [FrontController::class, 'pages']);
    Route::get('/page/details', [FrontController::class, 'pageDetails']);
    Route::get('/faq/list', [FrontController::class, 'faq']);
    // ========== Contact Us APIs ==========
    Route::post('/contact/query/save', [FrontController::class, 'contactStore']);
    // ========== Tips & Suggestion APIs ==========
    Route::get('/tips-suggestions', [FrontController::class, 'blogs']);
    Route::get('/tip-suggestion/details', [FrontController::class, 'blogDetails']);
    // ========== Payment Method Store API ==========
    Route::post('/payment-method/store', [FrontController::class, 'paymentMethod']);
    Route::get('/payment-method/show', [FrontController::class, 'paymentMethodShow']);
    Route::post('/billing-details/save', [FrontController::class, 'storeBillingDetails']);
    Route::get('/certificate/{certificate_number}/download', [FrontController::class, 'downloadPDF']);
    // =========== Coupons API ============
    Route::post('/coupon-code', [FrontController::class, 'calculateDiscount']);

    Route::get('/dashboard', [FrontController::class, 'dashboard']);
});
