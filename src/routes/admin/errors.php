<?php

Route::get(403,'ErrorsController@limitedAccess')->name('admin.403');
Route::get(404,'ErrorsController@notFound')->name('admin.404');