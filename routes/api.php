<?php
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function () {

    //route login
    Route::post('/login', [App\Http\Controllers\Api\Admin\LoginController::class, 'index', ['as' => 'admin']]);

    //group route with middleware "auth:api_admin"
    Route::group(['middleware' => 'auth:api'], function() {

        //data user
        Route::get('/user', [App\Http\Controllers\Api\Admin\LoginController::class, 'getUser', ['as' => 'admin']]);

        //refresh token JWT
        Route::get('/refresh', [App\Http\Controllers\Api\Admin\LoginController::class, 'refreshToken', ['as' => 'admin']]);

        //logout
        Route::post('/logout', [App\Http\Controllers\Api\Admin\LoginController::class, 'logout', ['as' => 'admin']]);
    
    });

});



//route group with middleware cors dan auth:api
Route::post('/register', [App\Http\Controllers\Api\User\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\User\LoginController::class, 'login']);



Route::group(['middleware' => 'auth:api'], function() {

    Route::get('/user', [App\Http\Controllers\Api\User\UserController::class, 'getUser']);
    Route::get('/user/{id}', [App\Http\Controllers\Api\User\UserController::class, 'getUserById']);
    Route::delete('/user/{id}', [App\Http\Controllers\Api\User\UserController::class, 'deleteUser']);
    Route::post('/user/{id}', [App\Http\Controllers\Api\User\UserController::class, 'updateUser']);
    Route::post('/user', [App\Http\Controllers\Api\User\UserController::class, 'createUser']);
    Route::post('/user/{id}/change-image', [App\Http\Controllers\Api\User\UserController::class, 'updateImageUser']);

    Route::resource('category', App\Http\Controllers\Api\Category\CategoryController::class);
    Route::resource('book', App\Http\Controllers\Api\Book\BookController::class);
    Route::resource('transaction', App\Http\Controllers\Api\Transaction\TransactionController::class);

});