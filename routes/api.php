<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DedicationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\AuthMiddleware;
use App\Services\ProviderService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


Route::group(['middleware' => 'api'], function ($router) {
    
    Route::group(['prefix' => 'auth'], function(){

        Route::get('{provider}/callback', [ProviderService::class, 'handleProviderCallback']);
        Route::controller(AuthController::class)->group(function(){
            Route::post('login', 'login')->middleware('throttle:10,5');
            Route::middleware(AuthMiddleware::class)->group(function () {
                Route::post('logout', 'logout');
                Route::post('refresh', 'refresh');
            });
        });

        Route::controller(RegisterController::class)->group(function () {
            Route::post('register/client', 'registerClient')->name('register.client');
            Route::post('register/artist', 'registerArtist')->name('register.artist');
        });

    });

    Route::middleware(AuthMiddleware::class)->group(function () {

        Route::controller(UserController::class)->group(function(){
            Route::group(['prefix' => 'user'], function () {
                Route::get('/', 'index');
                Route::get('{alias}', 'me');
                Route::get('/artist', 'getAllArtist');
                Route::get('/artist/{alias}', 'showArtist');
                Route::get('/artist/categories', 'getAllArtistByCategries');
                Route::get('/artist/category/{slug}', 'getArtistByCategry');
                Route::get('/artist/show/{artist}', 'showArtist')->name('artist.show');
                Route::get('/client', 'getAllClient')->middleware('role:admin');
                Route::get('/client/{alias}', 'showClient')->middleware('role:admin');
            });
        });        

        Route::controller(CategoryController::class)->group(function(){
            Route::group(['prefix' => 'category'], function(){
                Route::get('', 'index');
                Route::post('/', 'store')->middleware('role:admin');
                Route::put('category/{alias}', 'update')->middleware('role:admin');
            });
        });

        Route::controller(VoteController::class)->group(function(){
            Route::group(['prefix' => 'vote'], function(){
                Route::get('', 'index')->middleware('role:admin');
                Route::post('/', 'create')->middleware('role:client');
                Route::get('{alias}', 'getVotesByArtist')->middleware('role:admin|client');
            });
        });

        Route::controller(TransactionController::class)->group(function(){
            Route::group(['prefix' => 'transaction'], function(){
                Route::get('', 'index')->middleware('role:admin');
                Route::post('/bill/{billId}', 'show')->middleware('role:admin');
                Route::put('', 'updateStatus')->middleware('role:admin');
            });
        });

        Route::controller(DedicationController::class)->group(function(){
            Route::group(['prefix' => 'dedication'], function(){
                Route::get('', 'index');
                Route::post('/', 'store')->name('dedication.store')->middleware('role:client');
                Route::get('/{slug}', 'show')->name('dedication.show');
                Route::get('/{slug}/accept', 'accept')->name('dedication.accept')->middleware('role:artist');;
                Route::post('/{slug}/reject', 'reject')->name('dedication.reject')->middleware('role:artist');;
                Route::post('/{slug}/shiped', 'ship')->name('dedication.ship')->middleware('role:artist');;
            });
        });

    });

});
