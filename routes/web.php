<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\productController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login',[loginController::class, 'login'])->name('login');
Route::post('/sign_in',[loginController::class, 'sign_in'])->name('sign_in');

Route::get('products/{id}/view',[productController::class, 'view'])->name('product.view');

Route::group(['middleware' => ['web','auth']], function (){

    //Category
    Route::prefix('categories')->group(function () {
        Route::name('category.')->group(function () {
            Route::get('/index',[categoryController::class, 'index'])->name('index');
            Route::post('/store',[categoryController::class, 'store'])->name('store');
            Route::post('/update',[categoryController::class, 'update'])->name('update');
        });
    });

    //Product
    Route::prefix('products')->group(function () {
        Route::name('product.')->group(function () {
            Route::get('/index',[productController::class, 'index'])->name('index');
            Route::get('/create',[productController::class, 'create'])->name('create');
            Route::post('/store',[productController::class, 'store'])->name('store');
            Route::get('/{id}/edit',[productController::class, 'edit'])->name('edit');
            Route::post('/update',[productController::class, 'update'])->name('update');
            Route::get('/{id}/delete',[productController::class, 'delete'])->name('delete');
            Route::get('/{id}/detail_delete',[productController::class, 'detail_delete'])->name('detail_delete');
            Route::get('/{id}/download',[productController::class, 'download'])->name('download');
            Route::get('/download_all',[productController::class, 'download_all'])->name('download_all');
            Route::get('/{id?}/download_excel',[productController::class, 'download_excel'])->name('download_excel');
        });
    });

    Route::post('/logout',[loginController::class, 'logout'])->name('logout');

});
