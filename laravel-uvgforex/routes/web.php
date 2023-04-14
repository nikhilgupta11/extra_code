<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::resource('/contact', ContactController::class);

Route::get('/', function () {
    return view('index');
});
Route::post('/exchange_rate', [App\Http\Controllers\AjaxController::class, 'exchange_rate'])->name('exchange_rate');
Route::post('/update_usd_value', [App\Http\Controllers\AjaxController::class, 'update_usd_value'])->name('update_usd_value');
/** CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => '\App\Http\Controllers\PageController@index'])
    ->where(['page' => '^(((?=(?!admin))(?=(?!\/)).))*$', 'subs' => '.*']);