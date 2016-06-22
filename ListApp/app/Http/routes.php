<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::group(['middleware' => 'auth'], function ()
{
	Route::get('/home', 'ListAppController@getHome');
	Route::get('/list/{id}', 'ListAppController@getList')->name('list');
	Route::post('/additem', 'ListAppController@postAddItem');
	Route::post('/deleteitem', 'ListAppController@postDeleteItem');
	Route::post('/deletetag', 'ListAppController@postDeleteTag');
});

/*Show login*/
Route::get('/login', 'Auth\AuthController@getLogin');

/*Process login*/
Route::post('/login', 'Auth\AuthController@postLogin');

/*Show registration*/
Route::get('/register', 'Auth\AuthController@getRegister');

/*Process registration*/
Route::post('/register', 'Auth\AuthController@postRegister');

/*Process logout*/
Route::get('/logout', 'Auth\AuthController@logout');

/*Show logout confirmation*/
Route::get('/logout/confirm', 'Auth\AuthController@confirmLogout');

if( App::environment('development') || App::environment('local') )
{
	Route::get('/debug', 'ListAppController@getDebug');
	Route::get('/debugbar', function() {
		$data = Array('foo' => 'bar');
		Debugbar::info($data);
		Debugbar::error('Error!');
		Debugbar::warning('Watch out…');
		Debugbar::addMessage('Another message', 'mylabel');

		return 'Practice';
	});
}
