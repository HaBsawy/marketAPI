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


    Route::resource('users', 'UserController')->only(['index', 'show', 'update', 'destroy'])->middleware('auth:api');
    Route::resource('categories', 'CategoryController')->except(['create', 'edit'])->middleware('auth:api');
    Route::resource('subcategories', 'SubCategoryController');
    Route::resource('items', 'ItemController');
    Route::resource('checkouts', 'CheckoutController');

    Route::get('users/{user}/categories', 'CategoryController@userCategories')->name('userCategories');
    Route::get('users/{user}/subcategories', 'SubCategoryController@userSubCategory')->name('userSubCategory');
    Route::get('categories/{category}/subcategories', 'SubCategoryController@categorySubCategory')->name('categorySubCategory');
    Route::get('users/{user}/items', 'ItemController@userItem')->name('userItem');
    Route::get('checkouts/{checkout}/items', 'ItemController@checkoutItem')->name('checkoutItem');
    Route::get('subcategories/{subcategory}/items', 'ItemController@subcategoryItem')->name('subcategoryItem');
    Route::get('users/{user}/checkouts', 'CheckoutController@userCheckouts')->name('userCheckouts');
});
