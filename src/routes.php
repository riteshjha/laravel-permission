<?php
use Illuminate\Support\Facades\Route;


Route::get('permission/roles', 'AbilityController@roles')->name('permission.listRoles');
Route::get('permission/roles/{role}/abilities', 'AbilityController@roleAbilities')->name('permission.roleAbilities');
Route::post('permission/roles/{role}/abilities', 'AbilityController@updateRoleAbility')->name('permission.updateRoleAbility');
Route::get('/permission/abilities', 'AbilityController@index')->name('permission.listAbilities');
Route::get('/permission/abilities/sync', 'AbilityController@sync')->name('permission.syncAbilities');
Route::post('/permission/abilities/{ability}', 'AbilityController@update')->name('permission.updateAbility');
