<?php

/**
 * Managers
 */
Route::get('/managers', 'ManagerController@index')->name('managers.index');
Route::post('/managers', 'ManagerController@store')->name('managers.store');
Route::get('/managers/{id}', 'ManagerController@show')->name('managers.show');
Route::put('/managers/{id}', 'ManagerController@update')->name('managers.update');
Route::delete('/managers/{id}', 'ManagerController@destroy')->name('managers.destroy');

/**
 * Manager auth
 */
Route::post('/managers/register', 'ManagerController@register')->name('managers.register');
Route::post('/managers/login', 'ManagerController@login')->name('managers.login');

/**
 * Lockers
 */
Route::get('/lockers', 'LockerController@index')->name('lockers.index');
Route::post('/lockers', 'LockerController@store')->name('lockers.store');
Route::get('/lockers/{lockerGuid}', 'LockerController@show')->name('lockers.show');
Route::put('/lockers/{lockerGuid}', 'LockerController@update')->name('lockers.update');
Route::delete('/lockers/{lockerGuid}', 'LockerController@destroy')->name('lockers.destroy');

Route::post('/lockers/{lockerGuid}/unlock', 'LockerController@unlock')->name('lockers.unlock');
Route::post('/lockers/{lockerGuid}/forgot-key', 'LockerController@forgotKey')->name('lockers.forgot.key');

/**
 * Locker claims
 */
Route::get('/lockers/{lockerGuid}/claims', 'LockerClaimController@index')->name('lockers.claims.index');
Route::post('/lockers/{lockerGuid}/claims', 'LockerClaimController@store')->name('lockers.claims.store');
Route::get('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@show')->name('lockers.claims.show');
Route::delete('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@destroy')->name('lockers.claims.destroy');

Route::post('/lockers/{lockerGuid}/claims/{claimId}/setup', 'LockerClaimController@setup')->name('lockers.claims.setup');
Route::post('/lockers/{lockerGuid}/claims/{claimId}/update-key', 'LockerClaimController@updateKey')->name('lockers.claims.update.key');
Route::post('/lockers/{lockerGuid}/claims/{claimId}/end', 'LockerClaimController@end')->name('lockers.claims.end');

// Route::group(['middleware' => ['jwt.verify']], function() {
//     Route::get('/lockers', 'LockerController@index')->name('lockers.index');
// });

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

// Mail tests.
Route::post('/mail/forgot', 'MailController@forgot')->name('mail.forgot');
Route::post('/mail/end', 'MailController@end')->name('mail.end');
Route::post('/mail/lockdown', 'MailController@lockdown')->name('mail.lockdown');
