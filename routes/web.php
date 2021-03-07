<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StatusLikeController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\CommentLikeController;
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

// Statuses routes
Route::get('statuses',  [StatusController::class, 'index'])->name('statuses.index');
Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store')->middleware('auth');


// Statuses Like routes
Route::post('statuses/{status}/likes', [StatusLikeController::class, 'store'])->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes', [StatusLikeController::class, 'destroy'])->name('statuses.likes.destroy')->middleware('auth');

// Statuses Comments routes
Route::post('statuses/{status}/comments', [StatusCommentController::class, 'store'])->name('statuses.comments.store')->middleware('auth');

// Comments Like routes
Route::post('comments/{comment}/likes', [CommentLikeController::class, 'store'])->name('comments.likes.store')->middleware('auth');
Route::delete('comments/{comment}/likes', [CommentLikeController::class, 'destroy'])->name('comments.likes.destroy')->middleware('auth');

// Users routes
Route::get('@{user}', [UserController::class, 'show'])->name('users.show');

// Users Statuses routes
Route::get('user/{user}/statuses',  [UserStatusController::class, 'index'])->name('users.statuses.index');

Auth::routes();