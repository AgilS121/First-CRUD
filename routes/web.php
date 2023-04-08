<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// resource berarti routing yang sudah komplit mulai dari index,
// create, store, edit, destroy, update, jadi menyingkat tidak perlu mendefinisikan routing satu"
// bisa dicek dengan "php artisan route:list"
Route::resource('blog', BlogController::class);