<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





///// Auth Routes /////
Route::group(
    [
        'middleware' => ['cors'],
        'prefix' => 'auth',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
    }
);







///// Employee  Routes /////
Route::group(
    [
        'middleware' => ['auth:sanctum'],
        'prefix' => 'employee',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('/', 'EmployeeController@all');
        Route::post('/create', 'EmployeeController@create');
        Route::post('/edit', 'EmployeeController@edit');
        Route::get('/delete/{id}', 'EmployeeController@delete');
        Route::get('/restore/{id}', 'EmployeeController@restore');
        Route::get('/show/{id}', 'EmployeeController@show');

    }
);



///// Project  Routes /////
Route::group(
    [
        'middleware' => ['auth:sanctum'],
        'prefix' => 'project',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('/', 'ProjectController@all');
        Route::post('/create', 'ProjectController@create');
        Route::post('/edit', 'ProjectController@edit');
        Route::get('/delete/{id}', 'ProjectController@delete');
        Route::get('/restore/{id}', 'ProjectController@restore');
        Route::get('/show/{id}', 'ProjectController@show');

    }
);



///// Project  Routes /////
Route::group(
    [
        'middleware' => ['auth:sanctum'],
        'prefix' => 'dashboard',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('/summary', 'DashboardController@summary');
        Route::get('/projects', 'DashboardController@projects');
        Route::get('/paginated-employee', 'DashboardController@paginated_employee');


        
    }
);





