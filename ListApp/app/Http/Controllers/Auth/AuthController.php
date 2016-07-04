<?php

namespace ListApp\Http\Controllers\Auth;

use ListApp\User;
use Validator;
use ListApp\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use ListApp\Http\Controllers\ListAppController;

class AuthController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

	/**
	 * Where to the user should be redirected to after login fails.
	 *
	 * @var string
	 */
	protected $loginPath = '/login';

	/**
	 * Where to the user should be redirected to after logout.
	 *
	 * @var string
	 */
	protected $redirectAfterLogout = '/logout/confirm';

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	protected $username = 'username';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware($this->guestMiddleware(), ['except' => 'logout']);
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'username' => 'required|min:4|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'userid' => '',
			'active' => '',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		$uuid = ListAppController::getUUID( 'users', 'userid' );

		return User::create([
			'userid' => $uuid,
			'name' => $data['name'],
			'username' => $data['username'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'active' => 1
		]);
	}

	/**
	 * Show a logut confirmation to the user
	 *
	 */
	public function confirmLogout()
	{
		/*Set a logout confirmation message.*/
		\Session::flash('message','You have been logged out.');

		return redirect( '/' );
	}

	/*
	 * Code to disable registration. Leave it commented out to allow registration. Uncomment it to disable registration.
	 */
/*
    public function showRegistrationForm()
	{
		return redirect('login');
	}

	public function register()
	{
	}
*/
}
