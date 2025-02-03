<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashbordContorller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(DashbordContorller::class)->group(function()  {
    Route::get('home', 'home');
    Route::get('products-by-brand/{brand}', 'products_by_brand');
    Route::get('product-view/{id}', 'products_view');

    Route::get('filters/{filter}', 'filters');
    Route::get('filters/{brand}/{filter}', 'filters_by_brand');

    Route::get('add-favorite/{product_id}', 'add_favorite')->middleware('auth:sanctum');
    Route::get('fetch-favorite','fetch_favorite')->middleware('auth:sanctum');
    Route::get('favorite-remove/{id}','remove_favorite')->middleware('auth:sanctum');

});

Route::controller(AuthController::class)->group(function()  {
    Route::any('login', 'login');
    Route::post('register', 'register');
});
