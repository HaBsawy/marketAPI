<?php

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

Route::namespace('API')->group(function () {
    Route::get('login', 'UserController@loginPage')->name('login');
    Route::post('login', 'UserController@login')->name('login');
    Route::post('register', 'UserController@register')->name('register');

    Route::middleware('auth:api')->group(function () {
        Route::resource('users', 'UserController')->only(['index', 'show', 'update', 'destroy']);
        Route::resource('categories', 'CategoryController')->except(['create', 'edit']);
        Route::resource('subcategories', 'SubCategoryController')->except(['create', 'edit']);
        Route::resource('items', 'ItemController')->except(['create', 'edit']);
        Route::resource('checkouts', 'CheckoutController')->except(['create', 'edit']);

        Route::get('users/{user}/categories', 'CategoryController@userCategories')->name('userCategories');
        Route::get('users/{user}/subcategories', 'SubCategoryController@userSubCategory')->name('userSubCategory');
        Route::get('categories/{category}/subcategories', 'SubCategoryController@categorySubCategory')->name('categorySubCategory');
        Route::get('users/{user}/items', 'ItemController@userItem')->name('userItem');
        Route::get('checkouts/{checkout}/items', 'ItemController@checkoutItem')->name('checkoutItem');
        Route::get('subcategories/{subcategory}/items', 'ItemController@subcategoryItem')->name('subcategoryItem');
        Route::get('users/{user}/checkouts', 'CheckoutController@userCheckouts')->name('userCheckouts');
    });
});
