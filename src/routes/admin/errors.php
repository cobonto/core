<?php

Route::get(403,'ErrorsController@limitedAccess')->name(config('app.admin_url').'.403');
Route::get(404,'ErrorsController@notFound')->name(config('app.admin_url').'.404');