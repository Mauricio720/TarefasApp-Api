<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::get('/ping',function(){
    return ['pong'=>true];
});

Route::any('/401',[AuthController::class,'unauthorized']);
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/get_user',[AuthController::class,'getUser']);
Route::post('/auth/logout',[AuthController::class,'logout']);
Route::post('/auth/refresh',[AuthController::class,'refresh']);
Route::post('/auth/add_user',[AuthController::class,'add']);

Route::post('/update_user',[UserController::class,'updateUser']);
Route::post('/forgot_password',[UserController::class,'forgotPassword']);
Route::post('/verifyTokenRememberPass',[UserController::class,'verifyTokenRememberPass']);
Route::post('/changePass',[UserController::class,'changePass']);

Route::post('/add_task/{idUser}',[TaskController::class,'add']);
Route::post('/update_task/{idUser}',[TaskController::class,'update']);
Route::post('/change_selected_task/{id}/{idUser}',[TaskController::class,'changeSelectTask']);
Route::post('/delete_task/{id}/{idUser}',[TaskController::class,'delete']);
Route::get('/get_task/{idUser}',[TaskController::class,'getTask']);
Route::post('/send_daysRepeat/{idUser}',[TaskController::class,'addTaskRepeat']);

Route::get('/getConquest/{idUser}',[TaskController::class,'getAllConquest']);

Route::get('/get_objective/{idUser}',[ObjectiveController::class,'getObjective']);
Route::post('/add_objective/{idUser}',[ObjectiveController::class,'add']);
Route::post('/update_objective/{idUser}',[ObjectiveController::class,'update']);
Route::post('/delete_objective',[ObjectiveController::class,'delete']);
Route::post('/change_selected_objective/{id}/{idUser}',[ObjectiveController::class,'changeSelectedObjective']);

