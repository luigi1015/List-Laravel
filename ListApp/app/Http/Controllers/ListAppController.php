<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;

/*use App;*/
use Session;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ListAppController extends Controller
{
	/**
	 * Responds to GET /debug
	 */
	public function getDebug()
	{
		if( \App::environment('development') || \App::environment('local') )
		{
			if( config('app.debug') )
			{
				$debugging = 'Yes';
			}
			else
			{
				$debugging = 'No';
			}

			try
			{
				$uuid4 = Uuid::uuid4();
			}
			catch( UnsatisfiedDependencyException $e )
			{
				Session::flash( 'error','There was a problem generating the UUID: ' . $e.getMessage() );
				\Log::error('There was a problem generating the UUID: ' . $e.getMessage() .'\n' . $e.getTraceAsString());
				$uuid4 = '';
			}

			try
			{
				$databases = \DB::select('SHOW DATABASES;');
				Session::flash( 'message','Database connection seems to have been established.' );
				//Session::flash( 'message','This is a test message.' );
				//\Log::info('This is an info Log message.');
				//\Log::warning('This is a warning Log message.');
				//\Log::error('This is an error Log message.');
				$connectionEstablished = 'Yes';
				//$this->addFlashMessage( 'Database connection seems to have been established.' );
			}
			catch( Exception $e )
			{
				Session::flash( 'error','There was a problem establishing the database connection: ' . $e.getMessage() );
				\Log::error('There was a problem establishing the database connection: ' . $e.getMessage() .'\n' . $e.getTraceAsString());
				$connectionEstablished = 'No';
				//addFlashError( 'There was a problem establishing the database connection: ' . $e.getMessage() );
				$databases = array();
			}
			
			if( is_null(\Auth::user()) )
			{
				$username = 'Not logged in';
			}
			else
			{
				$username = \Auth::user()->name;
			}
			
			if( \Auth::check() )
			{
				$loggedin = 'Yes';
			}
			else
			{
				$loggedin = 'No';
			}

			return view('debug')
				->with('environment', \App::environment())
				->with('debugging', $debugging)
				->with('databases', $databases)
				->with('connectionEstablished', $connectionEstablished)
				->with('user', $username)
				->with('loggedin', $loggedin)
				->with('uuid', $uuid4);
		}
		else
		{
			return view('accessdenied');
		}
	}

	/**
	 * Responds to GET /home
	 */
	public function getHome()
	{
		$weblistIds = \DB::table('permission_user_weblist')->where('usersid', \Auth::user()->userid)->pluck('weblist_id');
		/*$permissionuserWeblists = \App\Permissionuserweblist::where('usersid',\Auth::user()->userid);*/
		/*return view('home')->with('lists',\App\Weblist::where('userid',\Auth::user()->userid) );*/
		return view('home')->with('lists', \App\Weblist::whereIn('id', $weblistIds)->get());
	}

	/**
	 * Responds to GET /list/{id}
	 */
	public function getList($id)
	{
		$listItemIds = \DB::table('listitem_weblist')->where('weblist_id', $id)->pluck('listItem_id');
		$listItems = \App\Listitem::whereIn('id', $listItemIds)->get();
		$selectedWeblist = \App\Weblist::where('id', $id)->first();
		return view('list')->with('title', $selectedWeblist->title)->with('list', $selectedWeblist)->with('listItems', $listItems);
	}

	/**
	 * Responds to POST /additem
	 */
	public function postAddItem()
	{
		if( Input::has('listId') && Input::has('itemDescription') )
		{
			$selectedWeblist = \App\Weblist::where('id', Input::get('listId'))->first();
			$newListItem = new \App\Listitem();
			$newListItem->description = Input::get('itemDescription');
			$newListItem->save();
			$selectedWeblist->listitems()->attach($newListItem->id);
			//$formInput = Input::all(); listitems()
			\Log::info( 'postAddItem(): userid: ' . \Auth::user()->userid );
			\Log::info( 'postAddItem(): listId: ' . Input::get('listId') );
			\Log::info( 'postAddItem(): itemDescription: ' . Input::get('itemDescription') );
		}
		else
		{
			\Log::info('In postAddItem(), Did not get the required info, listId and itemDescription (maybe more if Ive forgotten to update this message.');
		}
		return view('welcome');
	}

	/**
	 * Pushes a message onto the Session flash message as part of an array.
	 */
	public function addFlashMessage( $flashMessage )
	{
		$this->addFlash( 'messages', $flashMessage );
	}

	/**
	 * Pushes an error onto the Session flash error as part of an array.
	 */
	public function addFlashError( $flashError )
	{
		addFlash( 'errors', $flashError );
	}

	/**
	 * Pushes an string onto the Session flash as part of an array given the flash name (aka key) and string value.
	 */
	private function addFlash( $flashName, $flashValue )
	{
		Session::flash($flashName, array_merge((array)Session::get($flashName), array($flashValue)));
	}

	/**
	 * Generates a UUID that's not in the users table.
	 */
	public static function getUUID()
	{
		$uuid = '';
		$iterations = 1;
		do
		{
			//If have gone through this loop more than a hundred times, something's wrong, give an error and abort.
			if( $iterations >= 100 )
			{
				\Log::error('There was a problem generating the UUID, been through the UUID generation block ' . $iterations . ' times.' );
				abort(500);
			}

			try
			{
					$uuid = Uuid::uuid4();
			}
			catch( UnsatisfiedDependencyException $e )
			{
				\Log::error('There was a problem generating the UUID: ' . $e.getMessage() .'\n' . $e.getTraceAsString());
				abort(500);
			}
			$usersWithSameID = \DB::table('users')->where('userid', $uuid);
			/*
			if( is_null($usersWithSameID) )
			{
				\Log::info('usersWithSameID is null.' . $uuid );
			}
			else
			{
				\Log::info('Count: ' . $usersWithSameID->count() );
			}
			*/
		}
		while( $usersWithSameID->count() > 0 );//If a user with the same id is found, create another one.

		return $uuid;
	}
}
