<?php

use Illuminate\Http\Request;
use Illuminate\Http\Route;
use Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\API\VehicleController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\RiderController;
use App\Http\Controllers\API\EmailTemplateController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\SmtpController;
use App\Http\Controllers\API\ManageCommissionController;
use App\Http\Controllers\API\PortalContentController;
use App\Http\Controllers\API\ReviewRattingController;
use App\Http\Middleware\AccessToken;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [AuthController::class, 'signin']);
//Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('register', [AuthController::class, 'signup']);
Route::post('driver-register', [DriverController::class, 'signup']);

Route::post('otp-verify', [DriverController::class, 'verifyOtp']);
Route::post('rider-otp-verify', [RiderController::class, 'verifyOtp']);
Route::post('driver-document-upload', [DriverController::class, 'uploadDocument']);
Route::any('reset/password/{token}/{user_type}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('forget/password', [ForgotPasswordController::class, 'submitForgetPassword']);
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::middleware([AccessToken::class])->group(function () {
    //Route::Post('register',[RegisterController::class]);
    Route::post('create-vehicle-type', [VehicleController::class, 'createVehicleType']);
    Route::post('update-vehicle-type/{id}', [VehicleController::class, 'updateVehicleType']);
    Route::get('vehicle-type-list', [VehicleController::class, 'vehicleTypeList']);
    Route::patch('delete-vehicle-type/{id?}', [VehicleController::class, 'deleteVehicleType']);
    Route::get('vehicle-data/{id}', [VehicleController::class, 'vehicleTypeData']);

    Route::post('create-vehicle-type-option', [VehicleController::class, 'createVehicleTypeOption']);
    Route::get('vehicle-type-option-list', [VehicleController::class, 'vehicleTypeOptionList']);
    Route::patch('delete-vehicle-type-option/{id}', [VehicleController::class, 'deleteVehicleTypeOption']);
    Route::get('vehicle-type-option-data/{id}', [VehicleController::class, 'vehicleTypeOptionData']);
    Route::post('update-vehicle-type-option/{id}', [VehicleController::class, 'updateVehicleTypeOption']);
    Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify');
    Route::get('vehicle-option/{id}', [VehicleController::class, 'getVehicleTypeOptionName']);

    Route::post('create-banner', [BannerController::class, 'createBanner']);
    Route::get('banner-list', [BannerController::class, 'bannerList']);
    Route::post('update-banner/{id}', [BannerController::class, 'updateBanner']);
    Route::patch('delete-banner/{id?}', [BannerController::class, 'deleteBanner']);
    Route::get('banner-data/{id}', [BannerController::class, 'bannerData']);

    Route::post('create-faq', [FaqController::class, 'createFaq']);
    Route::get('faq-list', [FaqController::class, 'faqList']);
    Route::post('update-faq/{id}', [FaqController::class, 'updateFaq']);
    Route::get('faq-data/{id}', [FaqController::class, 'faqData']);
    Route::patch('delete-faq/{id?}', [FaqController::class, 'deleteFaq']);



    Route::post('create-eamiltemplate', [EmailTemplateController::class, 'createEmailTemplate']);
    Route::get('eamiltemplate-list', [EmailTemplateController::class, 'emailTemplateList']);
    Route::post('update-eamiltemplate/{id}', [EmailTemplateController::class, 'updateEmailTemplate']);
    Route::get('emailtemplate-data/{id}', [EmailTemplateController::class, 'emailTemplateData']);
    Route::patch('delete-eamiltemplate/{id?}', [EmailTemplateController::class, 'deleteEmailTemplate']);


    Route::post('create-contact', [ContactController::class, 'createContact']);
    Route::get('contact-list', [ContactController::class, 'ContactList']);
    Route::post('update-contact/{id}', [ContactController::class, 'updateContact']);
    Route::get('contact-data/{id}', [ContactController::class, 'contactData']);
    Route::patch('delete-contact/{id?}', [ContactController::class, 'deleteContact']);

    Route::get('dashboard-list', [DashboardController::class, 'dashboardList']);

    Route::post('create-setting', [SettingController::class, 'createSetting']);
    Route::get('setting-data/{id}', [SettingController::class, 'settingData']);

    Route::post('update-setting/{id}', [SettingController::class, 'updateSetting']);

    Route::post('create-smtp', [SmtpController::class, 'createSmtp']);
    Route::get('smtp-data/{id}', [SmtpController::class, 'smtpData']);

    Route::post('update-smtp/{id}', [SmtpController::class, 'updateSmtp']);

    Route::post('create-managecommisstion', [ManageCommissionController::class, 'createManageCommission']);
    Route::get('managecommission-data/{id}', [ManageCommissionController::class, 'manageCommissioData']);
    Route::post('update-managecommisstion/{id}', [ManageCommissionController::class, 'updateManageCommission']);

    Route::post('create-portalcontent', [PortalContentController::class, 'createPortalContent']);
    Route::get('portalContent-data/{id}', [PortalContentController::class, 'portalContentData']);
    Route::get('portal-list', [PortalContentController::class, 'portalList']);
    Route::post('update-portalcontent/{slug}', [PortalContentController::class, 'updateportalcontent']);



    Route::get('driver-list', [DriverController::class, 'driverList']);

    Route::post('driver-lat-long', [DriverController::class, 'saveLatLong']);
    Route::post('rider-lat-long', [RiderController::class, 'saveLatLong']);

    Route::post('check-email-phone', [DriverController::class, 'checkEmailMobile']);
    Route::get('view-driver/{id}', [DriverController::class, 'viewDriver']);
    Route::post('update-profile', [DriverController::class, 'updateProfile']);
    Route::post('update-email', [DriverController::class, 'updateEmail']);
    Route::post('update-phone', [DriverController::class, 'updatePhone']);
    Route::post('add-bank-detail', [DriverController::class, 'addBankDetail']);
    Route::post('manage-vehicle-info', [DriverController::class, 'createManageVehicleInformation']);
    Route::post('update-vehicle-info/{id}', [DriverController::class, 'updateManageVehicleInformation']);
    Route::post('driver-availibe-create', [DriverController::class, 'driverAvailibility']);
    Route::post('driver-availibe-status', [DriverController::class, 'isDriverAvailable']);

    Route::get('driver-availibility-list/{id}', [DriverController::class, 'getDriverAvailibility']);

    Route::get('rider-list', [RiderController::class, 'riderList']);
    Route::get('rider-data/{id}', [RiderController::class, 'riderData']);
    Route::patch('delete-rider/{id?}', [RiderController::class, 'deleteRider']);
    Route::post('update-rider/{id}', [RiderController::class, 'updateRider']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::post('create-review-ratting', [ReviewRattingController::class, 'createReviewRatting']);
    Route::get('manage-vehicle-list', [DriverController::class, 'manageVehicleList']);
    Route::post('show-lat-long', [RiderController::class, 'showNearByLocation']);
});
Route::post('review-ratting-list', [ReviewRattingController::class, 'reviewRatingList']);

Route::post('rider-register', [RiderController::class, 'signup']);
Route::get('account/verify/{token}/{type}', [AuthController::class, 'emailverifyAccount'])->name('email.verifyaccount');

Route::get('manage-vehicle-data/{id}', [DriverController::class, 'getManageVehicle']);
