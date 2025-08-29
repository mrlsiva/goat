<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', 'App\Http\Controllers\api\authController@register');
Route::post('login', 'App\Http\Controllers\api\authController@login');
Route::get('activate_user/{id}', 'App\Http\Controllers\api\authController@activate_user');

Route::middleware('auth:sanctum')->group(function () {

	Route::prefix('categories')->group(function () {

		Route::get('list','App\Http\Controllers\api\categoryController@list');
		Route::get('active_list','App\Http\Controllers\api\categoryController@active_list');
		Route::post('store','App\Http\Controllers\api\categoryController@store');
		Route::post('update','App\Http\Controllers\api\categoryController@update');

	});

	Route::prefix('genders')->group(function () {

		Route::get('list','App\Http\Controllers\api\genderController@list');

	});

	Route::prefix('products')->group(function () {

		Route::get('list','App\Http\Controllers\api\productController@list');
		Route::get('{id}/view','App\Http\Controllers\api\productController@view');
		Route::get('{id}/delete','App\Http\Controllers\api\productController@delete');
		Route::get('{id}/detail_delete','App\Http\Controllers\api\productController@detail_delete');
		Route::post('store','App\Http\Controllers\api\productController@store');
		Route::post('update','App\Http\Controllers\api\productController@update');

	});

	Route::get('logout', 'App\Http\Controllers\api\authController@logout');

});