<?php


Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'MovieController@index')->name('movies.index');
    Route::get('/movies/search', 'MovieController@search')->name('movies.search');
    Route::get('/movie/{id}', 'MovieController@show')->name('movies.show');

    Route::get('/favorites', 'FavoriteController@index')->name('favorites.index');
    Route::post('/favorites', 'FavoriteController@store')->name('favorites.store');
    Route::delete('/favorites/{imdb_id}', 'FavoriteController@destroy')->name('favorites.destroy');
});

Route::get('lang/{locale}', 'LocalizationController@index')->name('lang.switch');
