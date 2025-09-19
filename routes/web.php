<?php

use App\Http\Controllers\AddEditAdminProfile;
use App\Http\Controllers\FrontUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticateAdmin;
use App\Http\Controllers\ChangingAuthCredentials;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventBooking;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/',[FrontUserController::class,'home'])->name('home');
Route::get('/about',[FrontUserController::class,'about'])->name('about');
Route::get('/contact',[FrontUserController::class,'contact'])->name('contact');
Route::get('/events',[FrontUserController::class,'events'])->name('events');
Route::post('/contact',[FrontUserController::class,'contactRequest'])->name('contactRequest');
Route::get('/events/{id}',[FrontUserController::class,'eventDetails'])->name('eventDetails');
Route::get('/bookEvent/{id}',[EventBooking::class,'bookEvent'])->name('bookEvent');
Route::get('/download/{id}', [FrontUserController::class, 'downloadDocument'])->name('download');
Route::post('/registraionEvent',[FrontUserController::class,'registraionEvent'])->name('registraionEvent');
Route::get('/termsconditions',[FrontUserController::class,'termsconditions'])->name('terms-conditions');

Route::middleware('authorisedUser')->group(function () {
    Route::get('/adminPanel/dashbord',[AdminController::class,'dashbord'])->name('admin-dashbord');
    Route::get('/adminPanel/events',[EventController::class,'events'])->name('admin-events');
    Route::get('/adminPanel/contactLead',[ContactController::class,'contactLead'])->name('admin-contactLead');
    Route::get('/adminPanel/changePassword',[ChangingAuthCredentials::class,'changePassword'])->name('admin-changePassword');
    Route::post('/adminPanel/setNewPassword',[ChangingAuthCredentials::class,'setNewPassword'])->name('admin-setNewPassword');
    Route::get('/adminPanel/logout',[AuthenticateAdmin::class,'logout'])->name('admin-logout');
    Route::post('/adminPanel/getDescription',[ContactController::class,'getDescription'])->name('getDescription');
    Route::get('/adminPanel/add_event',[EventController::class,'addEvent'])->name('admin-add_event');
    Route::post('/adminPanel/saveEvent',[EventController::class,'saveEvent'])->name('admin-saveEvent');
    Route::get('/adminPanel/eventList',[EventController::class,'eventList'])->name('admin-eventList');
    Route::get('/adminPanel/editEvent/{id}',[EventController::class,'editEvent'])->name('admin-editEvent');
    Route::get('/admin/events/{id}/document', [EventController::class, 'downloadDocument'])->name('admin-download-document');
    Route::post('/adminPanel/updateEvent',[EventController::class,'updateEvent'])->name('admin-updateEvent');
    Route::get('/adminPanel/deleteEvent/{id}',[EventController::class,'deleteEvent'])->name('admin-deleteEvent');
    Route::post('/adminPanel/eventList',[EventController::class,'changeStatus'])->name('admin-eventStatus');
    Route::get('/adminPanel/profile{id}',[AddEditAdminProfile::class,'profile'])->name('admin-profile');
    Route::post('/adminPanel/update_profile',[AddEditAdminProfile::class,'upddate_profile'])->name('admin-update_profile');
});

Route::get('/adminPanel',[AuthenticateAdmin::class,'login'])->name('admin-login');
Route::post('/adminPanel/authenticate',[AuthenticateAdmin::class,'authenticate'])->name('admin-authenticate');
Route::get('/adminPanel/otp_verification',[AuthenticateAdmin::class,'getOTP'])->name('admin-otpForm');
Route::post('/adminPanel/otp_verification',[AuthenticateAdmin::class,'verifyOTP'])->name('admin-verifyOTP');
Route::post('/adminPanel/resend_otp',[AuthenticateAdmin::class,'resendOTP'])->name('admin-resendOTP');

Route::get('/adminPanel/register',[AddEditAdminProfile::class,'register'])->name('admin-register');
Route::post('/adminPanel/register',[AddEditAdminProfile::class,'saveDetails'])->name('admin-saveDetails');

Route::get('/adminPanel/forgotPassword',[ChangingAuthCredentials::class,'forgotPassword'])->name('admin-forgotPassword');
Route::post('/adminPanel/forgotPassword',[ChangingAuthCredentials::class,'sentResetLink'])->name('admin-sentResetLink');
Route::get('/adminPanel/resetPassword/{token}',[ChangingAuthCredentials::class,'resetPassword'])->name('admin-resetPassword');
Route::post('/adminPanel/submitResetPassword',[ChangingAuthCredentials::class,'submitResetPassword'])->name('admin-submitResetPassword');