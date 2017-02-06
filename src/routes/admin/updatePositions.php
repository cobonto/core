<?php

// route for filter list admin
Route::post('list/positions/update',function(\Illuminate\Http\Request $request){
    $controllerName = $request->input('controller');
    $module = $request->input('module');
    $positions = $request->input('positions');
    $controller = new $controllerName;

    return response()->json($controller->updatePositions($positions));

})->name(config('app.admin_url').'.list.positions.update');