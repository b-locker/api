<?php

// Open routes.
Route::post('/managers/login', 'ManagerController@login')->name('managers.login');

Route::get('/lockers/{lockerGuid}', 'LockerController@show')->name('lockers.show');
Route::post('/lockers/{lockerGuid}/unlock', 'LockerController@unlock')->name('lockers.unlock');
Route::post('/lockers/{lockerGuid}/forgot-key', 'LockerController@forgotKey')->name('lockers.forgot-key');

// Clients routes open because of lacking client-authenticaiton
Route::get('/lockers/{lockerGuid}/claims', 'LockerClaimController@index')->name('lockers.claims.index');
Route::post('/lockers/{lockerGuid}/claims', 'LockerClaimController@store')->name('lockers.claims.store');
Route::get('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@show')->name('lockers.claims.show');
Route::delete('/lockers/{lockerGuid}/claims/{claimId}', 'LockerClaimController@destroy')->name('lockers.claims.destroy');

Route::post('/lockers/{lockerGuid}/claims/{claimId}/setup', 'LockerClaimController@setup')->name('lockers.claims.setup');
Route::post('/lockers/{lockerGuid}/claims/{claimId}/update-key', 'LockerClaimController@updateKey')->name('lockers.claims.update-key');
Route::post('/lockers/{lockerGuid}/claims/{claimId}/end', 'LockerClaimController@end')->name('lockers.claims.end');
Route::post('/lockers/{lockerGuid}/claims/{claimId}/lift-lockdown', 'LockerClaimController@liftLockdown')->name('lockers.claims.lift-lockdown');




// Protected routes, requires manager JWT.
Route::group(['middleware' => ['jwt.verify']], function() {
    // Lockers.
    Route::get('/lockers', 'LockerController@index')->name('lockers.index');
    Route::post('/lockers', 'LockerController@store')->name('lockers.store');
    Route::put('/lockers/{lockerGuid}', 'LockerController@update')->name('lockers.update');
    Route::delete('/lockers/{lockerGuid}', 'LockerController@destroy')->name('lockers.destroy');

    // Client.
    Route::get('/clients', 'ClientController@index')->name('clients.index');
    Route::post('/clients', 'ClientController@store')->name('clients.store');
    Route::get('/clients/{id}', 'ClientController@show')->name('clients.show');
    Route::put('/clients/{id}', 'ClientController@update')->name('clients.update');
    Route::delete('/clients/{id}', 'ClientController@destroy')->name('clients.destroy');

    // Manager.
    Route::post('/managers/register', 'ManagerController@register')->name('managers.register');

    Route::get('/managers', 'ManagerController@index')->name('managers.index');
    Route::post('/managers', 'ManagerController@store')->name('managers.store');
    Route::get('/managers/{id}', 'ManagerController@show')->name('managers.show');
    Route::put('/managers/{id}', 'ManagerController@update')->name('managers.update');
    Route::delete('/managers/{id}', 'ManagerController@destroy')->name('managers.destroy');

    // Notes.
    Route::get('/clients/{clientId}/notes', 'ClientNoteController@index')->name('clients.notes.index');
    Route::post('/clients/{clientId}/notes', 'ClientNoteController@store')->name('clients.notes.store');
    Route::get('/clients/{clientId}/notes/{id}', 'ClientNoteController@show')->name('clients.notes.show');
    Route::put('/clients/{clientId}/notes/{id}', 'ClientNoteController@update')->name('clients.notes.update');
    Route::delete('/clients/{clientId}/notes/{id}', 'ClientNoteController@destroy')->name('clients.notes.destroy');
});
