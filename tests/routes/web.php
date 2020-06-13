<?php

use Rkj\Permission\Facades\Permission;

Route::get('login', function(){
    return 'Login Required';
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('user', function(){
        return 'User info';
    })->name('user.info')->middleware('can:user.info');

    Route::get('user/profile', function(){
        return 'User Profile';
    })->name('user.profile')->middleware('can:user.profile');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Permission::routes();
});  