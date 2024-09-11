<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegisterControler;
use App\Http\Controllers\UserController;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Output\AnsiColorMode;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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


Route::post('/login', [RegisterControler::class, 'login']);
Route::post('/register', [RegisterControler::class, 'register']);
Route::post('/forgotPassword', [RegisterControler::class, 'forgotPassword']);

Route::group(['middleware' => 'check.token'], function () {
    Route::post('/verifyOtp', [RegisterControler::class, 'verifyOtp']);
    Route::post('/changePassword', [RegisterControler::class, 'changePassword']);
    Route::post('/resendOtp', [RegisterControler::class, 'resendOtp']);

    Route::get('/user', [UserController::class, 'show']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/avatar', [UserController::class, 'avatar']);


    // Route::post('/fileUpload', [LessonController::class, 'fileUpload']);
    Route::delete('/fileUpload/{id}', [FileUploadController::class, 'destroy']);
    Route::post('/upload/avatar', [UserController::class, 'uploadAvatar']);

    Route::post('/addRoleTo', [UserController::class, 'addRoleTo']);
    Route::post('/addPermissonsTo', [UserController::class, 'addPermissonsTo']);
    Route::post('/addManyPermissonsTo', [UserController::class, 'addManyPermissonsTo']);
    
    Route::post('/addPermissonsToRole', [UserController::class, 'addPermissonsToRole']);

    // Route::post('/upload-excel-create-role-permission', [UserController::class, 'createRolePermission']);
    
});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

