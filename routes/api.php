<?php

use App\Http\Controllers\PaymentController;
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
Route::post('/login', 'UserController@login')->name('login');
Route::post('/register', 'UserController@register');

Route::middleware(['api.auth','throttle:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/charge', [PaymentController::class, 'charge']);

    Route::get('/all-users', 'UserController@index');

    Route::get('/user/{id}', 'UserController@show');



    Route::put('/user/{id}', 'UserController@update');

    Route::delete('/user/{id}', 'UserController@delete');


    // blog post url

    Route::get('/blog-post', 'BlogPostController@index');

    Route::get('/blog-post/{id}', 'BlogPostController@show');

    Route::post('/blog-post', 'BlogPostController@store');

    Route::put('/blog-post/{id}', 'BlogPostController@update');

    Route::delete('/blog-post/{id}', 'BlogPostController@delete');

    // Contact url

    Route::get('/contacts', 'ContactsController@index');

    Route::get('/contacts/{id}', 'ContactsController@show');

    Route::post('/contacts', 'ContactsController@store');

    Route::put('/contacts/{id}', 'ContactsController@update');

    Route::delete('/contacts/{id}', 'ContactsController@delete');

    // Task url

    Route::get('/task', 'TaskController@index');

    Route::get('/task/{id}', 'TaskController@show');

    Route::post('/task', 'TaskController@store');

    Route::put('/task/{id}', 'TaskController@update');

    Route::delete('/task/{id}', 'TaskController@delete');

});