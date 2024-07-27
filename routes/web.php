<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/',[HomeController::class,'index'])->name('home');

Route::group(['prefix'=>'account'],function(){
Route::group(['middleware'=>'guest'],function(){
    Route::get('register',[Account::class,'register'])->name('account.register');
    Route::get('login',[Account::class,'login'])->name('account.login');
    Route::post('register',[Account::class,'processRegister'])->name('account.processRegister');
    Route::post('login',[Account::class,'authenticate'])->name('account.authenticate');
});
Route::group(['middleware'=>'auth'],function(){
    Route::get('profile',[Account::class,'profile'])->name('account.profile');
    Route::get('logout',[Account::class,'logout'])->name('account.logout');
    Route::post('updateprofile',[Account::class,'updateprofile'])->name('account.updateprofile');
    Route::get('books',[BookController::class,'index'])->name('books.index');
    Route::get('books/create',[BookController::class,'create'])->name('books.create');
    Route::post('books',[BookController::class,'store'])->name('books.store');
    Route::get('books/edit/{id}',[BookController::class,'edit'])->name('books.edit');
    Route::post('books/edit/{id}',[BookController::class,'update'])->name('books.update');
    Route::delete('books',[BookController::class,'destroy'])->name('books.destroy');
});    
});
