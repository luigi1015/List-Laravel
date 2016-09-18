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

Route::group(['middleware' => 'throttle:30'], function ()
{
	Route::get('/', 'ListAppController@getRoot')->name('root');
	Route::get('/user/{username}/lists', 'ListAppController@getListsOfUser')->name('userlist');
	Route::get('/user/{username}/list/{id}', 'ListAppController@getUsersList')->name('list');
	Route::get('/user/{username}', 'ListAppController@getUserPage')->name('userpage');
	Route::get('/api/v1/user/{username}/list/{id}', 'ListAppController@getAPIUsersList')->name('userpage');
	Route::get('/api/v1/user/{username}', 'ListAppController@getAPIUserInfo')->name('userinfo');
});

Route::group(['middleware' => ['auth', 'throttle:30']], function ()
{
	Route::get('/home', 'ListAppController@getHome')->name('userhome');
	//Route::get('/list/{id}', 'ListAppController@getList')->name('list');
	Route::post('/additem', 'ListAppController@postAddItem');
	Route::post('/deleteitem', 'ListAppController@postDeleteItem');
	Route::post('/deletetag', 'ListAppController@postDeleteTag');
	Route::post('/addweblist', 'ListAppController@postAddWeblist');
	Route::post('/updateweblist', 'ListAppController@postUpdateWeblist');
	Route::get('/settings', 'ListAppController@getSettings')->name('settings');
	Route::get('/settings/users', 'ListAppController@getUsers');
	Route::get('/settings/user/{username}', 'ListAppController@getUser');
});


/*Show login*/
Route::get('/login', 'Auth\AuthController@getLogin')->name('login');

/*Process login*/
Route::post('/login', 'Auth\AuthController@postLogin');

/*Show registration*/
Route::get('/register', 'Auth\AuthController@getRegister')->name('register');

/*Process registration*/
Route::post('/register', 'Auth\AuthController@postRegister');

/*Process logout*/
Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

/*Show logout confirmation*/
Route::get('/logout/confirm', 'Auth\AuthController@confirmLogout');

if( App::environment('development') || App::environment('local') )
{
	Route::get('/debug', 'ListAppController@getDebug');
	Route::get('/debugbar', function() {
		$data = Array('foo' => 'bar');
		Debugbar::info($data);
		//$weblist = \ListApp\Weblist::where('title','like','List 01')->first();
		//Debugbar::info($weblist);
		Debugbar::error('Error!');
		Debugbar::warning('Watch out…');
		Debugbar::addMessage('Another message', 'mylabel');

		return 'Practice';
	});
}
