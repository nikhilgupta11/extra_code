<?php

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Project\Models\Project;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/test',function(){
//     $project = Project::findOrFail(3);
//     $certificate = generateCertificateNumber($project);
//     dd($certificate);
// });

// Auth Routes
require __DIR__ . '/auth.php';

// Language Switch
Route::get('language/{language}', [LanguageController::class, 'switch'])->name('language.switch');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/login', 301);
/*
*
* Backend Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'App\Http\Controllers\Backend', 'prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['auth', 'auth.session']], function () {

    /**
     * Backend Dashboard
     * Namespaces indicate folder structure.
     */
    Route::get('/', 'BackendController@index')->name('home');
    Route::get('/dashboard', 'BackendController@index')->name('dashboard');

    /*
     *
     *  Settings Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'settings';
    $controller_name = 'SettingController';
    Route::get("$module_name", "$controller_name@index")->name("$module_name");
    Route::post("$module_name", "$controller_name@store")->name("$module_name.store");
    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UserController';
    Route::get("$module_name/profile/{id}", ['as' => "$module_name.profile", 'uses' => "$controller_name@profile"]);
    Route::get("$module_name/profile/{id}/edit", ['as' => "$module_name.profileEdit", 'uses' => "$controller_name@profileEdit"]);
    Route::patch("$module_name/profile/{id}/edit", ['as' => "$module_name.profileUpdate", 'uses' => "$controller_name@profileUpdate"]);
    Route::get("$module_name/emailConfirmationResend/{id}", ['as' => "$module_name.emailConfirmationResend", 'uses' => "$controller_name@emailConfirmationResend"]);
    Route::delete("$module_name/userProviderDestroy", ['as' => "$module_name.userProviderDestroy", 'uses' => "$controller_name@userProviderDestroy"]);
    Route::delete("$module_name/{id}/force_destroy", ['as' => "$module_name.force_destroy", 'uses' => "$controller_name@force_destroy"]);
    Route::get("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePassword", 'uses' => "$controller_name@changeProfilePassword"]);
    Route::patch("$module_name/profile/changeProfilePassword/{id}", ['as' => "$module_name.changeProfilePasswordUpdate", 'uses' => "$controller_name@changeProfilePasswordUpdate"]);
    Route::get("$module_name/changePassword/{id}", ['as' => "$module_name.changePassword", 'uses' => "$controller_name@changePassword"]);
    Route::patch("$module_name/changePassword/{id}", ['as' => "$module_name.changePasswordUpdate", 'uses' => "$controller_name@changePasswordUpdate"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::resource("$module_name", "$controller_name");
    Route::post("$module_name/store", ['as' => "$module_name.store-user", 'uses' => "$controller_name@store"]);
    Route::get("$module_name/{id}/status/{type}", ['as' => "$module_name.status", 'uses' => "$controller_name@statusChange"]);

    $module_name = 'enquiries';
    $controller_name = 'EnquiryController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/{id}/show", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::delete("$module_name/{id}/destroy", ['as' => "$module_name.destroy", 'uses' => "$controller_name@destroy"]);

    $module_name = 'trips';
    $controller_name = 'TripController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/{id}/show", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);

    $module_name = 'calculations';
    $controller_name = 'TripCalculationController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/{id}/show", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);

    $module_name = 'formbuilder';
    $controller_name = 'TransportFormController';
    Route::get("$module_name/create", ['as' => "$module_name.create", 'uses' => "$controller_name@formBuilder"]);
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::post("$module_name/store", ['as' => "$module_name.store", 'uses' => "$controller_name@store"]);
    Route::get("$module_name.index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/{id}/status/{type}", ['as' => "$module_name.status", 'uses' => "$controller_name@statusChange"]);
    Route::get("$module_name/{id}/edit", ['as' => "$module_name.edit", 'uses' => "$controller_name@edit"]);
    // Route::delete("$module_name/{id}/destroy", ['as' => "$module_name.destroy", 'uses' => "$controller_name@destroy"]);

    $module_name = 'donations';
    $controller_name = 'PaymentController';
    Route::get("$module_name", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/{id}/show", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
    Route::get('/certificate/{certificate_number}/download', [FrontController::class, 'downloadPDF'])->name('certificate.download');

    $module_name = 'coupons';
    $controller_name = 'PromocodeController';
    Route::get('/coupons', ['as' => "$module_name", 'uses' => "$controller_name@coupons"]);
    Route::get('/coupons/edit/{id}', ['as' => "$module_name.edit", 'uses' => "$controller_name@coupons"]);
    Route::delete('/coupons/delete/{id}', ['as' => "$module_name.delete", 'uses' => "$controller_name@coupon_delete"]);
    Route::post('/coupons/create', ['as' => "$module_name.create", 'uses' => "$controller_name@coupon_create"]);
});
