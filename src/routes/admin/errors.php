<?php

Route::get(403,'ErrorsController@limitedAccess')->name('403');
Route::get(404,'ErrorsController@notFound')->name('404');