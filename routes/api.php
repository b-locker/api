<?php

// Locker.
Route::get('/lockers', 'LockerController@index')->name('lockers');
Route::post('/lockers', 'LockerController@store')->name('lockers.store');
Route::get('/lockers/{id}', 'LockerController@show')->name('lockers.show');
Route::put('/lockers/{id}', 'LockerController@update')->name('lockers.update');
Route::delete('/lockers/{id}', 'LockerController@destroy')->name('lockers.destroy');
