<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StatusLikeController;
use App\Http\Controllers\StatusCommentController;

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

Route::view('/', 'welcome')->name('home');

Route::get('statuses',  [StatusController::class, 'index'])->name('statuses.index');
Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store')->middleware('auth');

Route::post('statuses/{status}/likes', [StatusLikeController::class, 'store'])->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes', [StatusLikeController::class, 'destroy'])->name('statuses.likes.destroy')->middleware('auth');

Route::post('statuses/{status}/comments', [StatusCommentController::class, 'store'])->name('statuses.comments.store')->middleware('auth');

Auth::routes();