<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('snapshot', 'SnapshotController');

// Google Cloud Messaging
Route::get('gcm/notifyAll/{msg?}', 'GCMController@notifyAll');
Route::post('gcm/registerDevice', 'GCMController@registerDevice');

// Debugging route for phpinfo
Route::get('phpinfo', function () {
    return phpinfo();
});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
    Route::get('/campaigns/create', 'CampaignsController@create');
    Route::post('/campaigns', 'CampaignsController@store');
    Route::get('/campaigns/{campaign}', 'CampaignsController@show');
    Route::get('/campaigns/{campaign}/add-question', 'QuestionsController@add');
    Route::post('/campaigns/{campaign}/add-question', 'QuestionsController@store');
});
