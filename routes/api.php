<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/user', 'UserController@showUser');
Route::middleware('auth:api')->post('/update/user', 'UserController@updateUser');


Route::middleware('auth:api')->get('/categories', 'CategoriesController@showCategories');
Route::middleware('auth:api')->get('/tasks', 'TasksController@loadTasks');
Route::middleware('auth:api')->post('/add-category', 'CategoriesController@addCategory');
Route::middleware('auth:api')->post('/delete/category/{category}', 'CategoriesController@deleteCategory')->middleware('categories');
Route::middleware('auth:api')->get('/cards/{category}', 'CardsController@showCards')->middleware('categories');
Route::middleware('auth:api')->post('/add-card/{category}', 'CardsController@addCard')->middleware('categories');
Route::middleware('auth:api')->post('/delete/card/{card}/{category}', 'CardsController@deleteCard')->middleware('categories', 'cards');
Route::middleware('auth:api')->post('/add-task/{card}/{category}', 'TasksController@addTask')->middleware('categories', 'cards');
Route::middleware('auth:api')->post('/delete/task/{task}/{card}/{category}', 'TasksController@deleteTask')->middleware('categories', 'cards', 'tasks');
Route::middleware('auth:api')->post('/update/task/{task}/{card}/{category}', 'TasksController@updateTask')->middleware('categories', 'cards', 'tasks');
Route::middleware('auth:api')->post('/update/card/{card}/{category}', 'CardsController@updateCard')->middleware('categories', 'cards');
Route::middleware('auth:api')->post('/update/category/{category}', 'CategoriesController@updateCategory')->middleware('categories');
Route::middleware('auth:api')->post('/update/tasks/order/{card}/{category}', 'TasksController@updateTasksOrder')->middleware('categories', 'cards');
Route::middleware('auth:api')->post('/update/task/card/{task}/{card}/{category}', 'TasksController@updateTaskCard')->middleware('categories');
Route::middleware('auth:api')->post('/update/cards/order/{category}', 'CardsController@updateCardsOrder')->middleware('categories');


