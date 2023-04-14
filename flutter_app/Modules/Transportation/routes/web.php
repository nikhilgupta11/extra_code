<?php

use Illuminate\Support\Facades\Route;

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Transportation\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Backend Transportations Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'transportations';
    $controller_name = 'TransportationsController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::get("$module_name/status/{id}/{type}", ['as' => "$module_name.status", 'uses' => "$controller_name@statusChange"]);
    Route::resource("$module_name", "$controller_name");
    Route::get("$module_name/status/{id}/{type}", ['as' => "$module_name.status", 'uses' => "$controller_name@statusChange"]);
    Route::any("$module_name/force_destroy/{id}", ['as' => "$module_name.force_destroy", 'uses' => "$controller_name@force_destroy"]);
});
