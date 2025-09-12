<?php

use App\Http\Controllers\FrontUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticateAdmin;
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
//bookEvent

Route::middleware('authorisedUser')->group(function () {
    Route::get('/adminPanel/dashbord',[AdminController::class,'dashbord'])->name('admin-dashbord');
    Route::get('/adminPanel/events',[AdminController::class,'events'])->name('admin-events');
    Route::get('/adminPanel/contactLead',[AdminController::class,'contactLead'])->name('admin-contactLead');
    Route::get('/adminPanel/changePassword',[AuthenticateAdmin::class,'changePassword'])->name('admin-changePassword');
    Route::post('/adminPanel/setNewPassword',[AuthenticateAdmin::class,'setNewPassword'])->name('admin-setNewPassword');
    Route::get('/adminPanel/logout',[AuthenticateAdmin::class,'logout'])->name('admin-logout');
    Route::post('/adminPanel/getDescription',[AdminController::class,'getDescription'])->name('getDescription');
    Route::get('/adminPanel/add_event',[EventController::class,'addEvent'])->name('admin-add_event');
    Route::post('/adminPanel/saveEvent',[EventController::class,'saveEvent'])->name('admin-saveEvent');
    Route::get('/adminPanel/eventList',[EventController::class,'eventList'])->name('admin-eventList');
    Route::get('/adminPanel/editEvent/{id}',[EventController::class,'editEvent'])->name('admin-editEvent');
    Route::get('/admin/events/{id}/document', [EventController::class, 'downloadDocument'])->name('admin-download-document');
    Route::post('/adminPanel/updateEvent',[EventController::class,'updateEvent'])->name('admin-updateEvent');
    Route::get('/adminPanel/deleteEvent/{id}',[EventController::class,'deleteEvent'])->name('admin-deleteEvent');

});
Route::get('/adminPanel',[AuthenticateAdmin::class,'login'])->name('admin-login');
Route::post('/adminPanel/authenticate',[AuthenticateAdmin::class,'authenticate'])->name('admin-authenticate');
Route::get('/adminPanel/otp_verification',[AuthenticateAdmin::class,'getOTP'])->name('admin-otpForm');
Route::post('/adminPanel/otp_verification',[AuthenticateAdmin::class,'verifyOTP'])->name('admin-verifyOTP');

Route::get('/adminPanel/register',[AdminController::class,'register'])->name('admin-register');
Route::post('/adminPanel/register',[AdminController::class,'saveDetails'])->name('admin-saveDetails');

Route::get('/adminPanel/forgotPassword',[AuthenticateAdmin::class,'forgotPassword'])->name('admin-forgotPassword');
Route::post('/adminPanel/forgotPassword',[AuthenticateAdmin::class,'sentResetLink'])->name('admin-sentResetLink');
Route::get('/adminPanel/resetPassword/{token}',[AuthenticateAdmin::class,'resetPassword'])->name('admin-resetPassword');
Route::post('/adminPanel/submitResetPassword',[AuthenticateAdmin::class,'submitResetPassword'])->name('admin-submitResetPassword');

