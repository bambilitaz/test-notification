<?php

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

Route::get('/feed/basic', function () {
    return view('feed-basic');
});

Route::get('/feed/full', function () {
    return view('feed-full');
});

Route::get('/add/basic', function () {
    return view('add-noti-basic');
});

Route::get('/add/full', function () {
    return view('add-noti-full');
});
