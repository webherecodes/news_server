<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsUtilController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::get('/', function () {
//     return 'Hello World!';
// });

// Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' =>['auth:sanctum']], function () {

});


Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/news',[NewsController::class,'getData']);
    Route::get('/paginate_news',[NewsController::class,'getPaginateData']);
    Route::post('/news',[NewsController::class,'postData']);
    Route::post('/news/edit/{id}',[NewsController::class,'editData']);
    Route::delete('/news/delete/{id}',[NewsController::class,'deleteData']);
    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/news_utils/{news}/like', [NewsUtilController::class, 'likeNews']);
    Route::post('/news_utils/{news}/dislike', [NewsUtilController::class, 'dislikeNews']);
});