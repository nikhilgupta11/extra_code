<?php

use AddWeb\CMS\Http\Controller\ComponentAdminController;
use AddWeb\CMS\Http\Controller\PageAdminController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','as' => 'admin.', 'middleware' => config('addWebCms.guard') ], function () {
    Route::group(['prefix' => 'component','as' => 'component.'], function () {
        Route::get('/',[ComponentAdminController::class,'index'])->name('list');
        Route::get('create',[ComponentAdminController::class,'create'])->name('create-get');
        Route::get('edit/{id?}',[ComponentAdminController::class,'edit'])->name('edit-get');
        Route::post('save',[ComponentAdminController::class,'saveComponent'])->name('save-post');
    });
    Route::group(['prefix' => 'page','as' => 'page.'], function () {
        Route::get('/',[PageAdminController::class,'index'])->name('list');
        Route::get('create',[PageAdminController::class,'create'])->name('create-get');
        Route::get('edit/{id}',[PageAdminController::class,'edit'])->name('edit-get');
        Route::post('save',[PageAdminController::class,'savePage'])->name('save-post');
    });
});

//Route::get('/{path?}', [PageController::class,'index']);