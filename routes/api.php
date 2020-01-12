<?php

/**
 * Lockers
 */
Route::get('/lockers', 'LockerController@index')->name('lockers.index');
Route::post('/lockers', 'LockerController@store')->name('lockers.store');
Route::get('/lockers/{lockerGuid}', 'LockerController@show')->name('lockers.show');
Route::put('/lockers/{lockerGuid}', 'LockerController@update')->name('lockers.update');
Route::delete('/lockers/{lockerGuid}', 'LockerController@destroy')->name('lockers.destroy');

/**
 * Locker claims
 */
Route::get('/lockers/{lockerGuid}/claims', 'LockerClaimController@index')->name('lockers.claims.index');
Route::post('/lockers/{lockerGuid}/claims', 'LockerClaimController@store')->name('lockers.claims.store');
Route::get('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@show')->name('lockers.claims.show');
Route::put('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@update')->name('lockers.claims.update');
Route::delete('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@destroy')->name('lockers.claims.destroy');

/**
 * Managers
 */
Route::get('/managers', 'ManagerController@index')->name('managers.index');
Route::post('/managers', 'ManagerController@store')->name('managers.store');
Route::get('/managers/{id}', 'ManagerController@show')->name('managers.show');
Route::put('/managers/{id}', 'ManagerController@update')->name('managers.update');
Route::delete('/managers/{id}', 'ManagerController@destroy')->name('managers.destroy');

/**
 * Clients
 */
Route::get('/clients', 'ClientController@index')->name('clients.index');
Route::post('/clients', 'ClientController@store')->name('clients.store');
Route::get('/clients/{id}', 'ClientController@show')->name('clients.show');
Route::put('/clients/{id}', 'ClientController@update')->name('clients.update');
Route::delete('/clients/{id}', 'ClientController@destroy')->name('clients.destroy');

/**
 * Client notes
 */
Route::get('/clients/{clientId}/notes', 'ClientNoteController@index')->name('clients.notes.index');
Route::post('/clients/{clientId}/notes', 'ClientNoteController@store')->name('clients.notes.store');
Route::get('/clients/{clientId}/notes/{id}', 'ClientNoteController@show')->name('clients.notes.show');
Route::put('/clients/{clientId}/notes/{id}', 'ClientNoteController@update')->name('clients.notes.update');
Route::delete('/clients/{clientId}/notes/{id}', 'ClientNoteController@destroy')->name('clients.notes.destroy');
