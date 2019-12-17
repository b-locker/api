<?php

// Locker.
Route::get('/lockers', 'LockerController@index')->name('lockers.index');
Route::post('/lockers', 'LockerController@store')->name('lockers.store');
Route::get('/lockers/{id}', 'LockerController@show')->name('lockers.show');
Route::put('/lockers/{id}', 'LockerController@update')->name('lockers.update');
Route::delete('/lockers/{id}', 'LockerController@destroy')->name('lockers.destroy');

// Manager.
Route::get('/managers', 'ManagerController@index')->name('managers.index');
Route::post('/managers', 'ManagerController@store')->name('managers.store');
Route::get('/managers/{id}', 'ManagerController@show')->name('managers.show');
Route::put('/managers/{id}', 'ManagerController@update')->name('managers.update');
Route::delete('/managers/{id}', 'ManagerController@destroy')->name('managers.destroy');

// Role.
Route::get('/roles', 'RoleController@index')->name('roles.index');
Route::post('/roles', 'RoleController@store')->name('roles.store');
Route::get('/roles/{id}', 'RoleController@show')->name('roles.show');
Route::put('/roles/{id}', 'RoleController@update')->name('roles.update');
Route::delete('/roles/{id}', 'RoleController@destroy')->name('roles.destroy');

// Client.
Route::get('/clients', 'ClientController@index')->name('clients.index');
Route::post('/clients', 'ClientController@store')->name('clients.store');
Route::get('/clients/{id}', 'ClientController@show')->name('clients.show');
Route::put('/clients/{id}', 'ClientController@update')->name('clients.update');
Route::delete('/clients/{id}', 'ClientController@destroy')->name('clients.destroy');

// Client notes.
Route::get('/clients/{clientId}/notes', 'ClientNotesController@index')->name('clients.notes.index');
Route::post('/clients/{clientId}/notes', 'ClientNotesController@store')->name('clients.notes.store');
Route::get('/clients/{clientId}/notes/{id}', 'ClientNotesController@show')->name('clients.notes.show');
Route::put('/clients/{clientId}/notes/{id}', 'ClientNotesController@update')->name('clients.notes.update');
Route::delete('/clients/{clientId}/notes/{id}', 'ClientNotesController@destroy')->name('clients.notes.destroy');
