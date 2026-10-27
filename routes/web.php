<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
Route::get('/quotes/{quote}', [QuoteController::class, 'show'])->name('quotes.show');
Route::get('/trending', [DiscoverController::class, 'trending'])->name('trending');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/u/{user}', [ProfileController::class, 'show'])->name('profile.show');

Route::middleware('guest')->group(function () {
    Route::get('/signup', [SignupController::class, 'show'])->name('signup');
    Route::post('/signup', [SignupController::class, 'signup']);
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::put('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');

    Route::post('/quotes/{quote}/like', [EngagementController::class, 'toggleLike'])->name('quotes.like');
    Route::post('/quotes/{quote}/bookmark', [EngagementController::class, 'toggleBookmark'])->name('quotes.bookmark');
    Route::post('/u/{user}/follow', [EngagementController::class, 'toggleFollow'])->name('users.follow');

    Route::post('/quotes/{quote}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/feed', [DiscoverController::class, 'feed'])->name('feed');
    Route::get('/bookmarks', [DiscoverController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
