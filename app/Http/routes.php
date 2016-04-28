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

Route::group(['middleware' => 'api', 'prefix' => 'api'], function () {
    Route::get('/campaigns', 'CampaignsController@indexJson');
    Route::post('/campaigns/join', 'CampaignsController@joinCampaign');
    Route::post('/campaigns/{campaign}/snapshots', 'CampaignsController@addSnapshots');
    Route::get('/campaigns/{campaign}', 'CampaignsController@showJson');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::group(['middleware' => 'login'], function () {
        Route::get('/home', 'HomeController@index');
        Route::get('campaigns', 'CampaignsController@index');
        Route::get('/campaigns/create', 'CampaignsController@create');
        Route::post('/campaigns', 'CampaignsController@store');
        Route::get('/campaigns/{campaign}', 'CampaignsController@show');
        Route::post('/campaigns/{campaign}', 'QuestionsController@changeOrder');
        Route::get('/campaigns/{campaign}/questions/create', 'QuestionsController@create');
        Route::post('/campaigns/{campaign}/questions', 'QuestionsController@store');
        Route::get('/campaigns/{campaign}/snapshots', 'SnapshotsController@index');
    });
});

