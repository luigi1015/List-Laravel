<?php

namespace ListApp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use ListApp\Http\Requests;

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
				Session::flash( 'error', 'There was a problem establishing the database connection: ' . $e.getMessage() );
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
		return view('home')->with('lists', ListController::getUsersWeblists())->with('activePage', 'userhome');
	}

	/**
	 * Responds to GET /settings
	 */
	public function getSettings()
	{
		return view('settings')->with('activePage', 'settings');
	}

	/**
	 * Responds to GET /
	 */
	public function getRoot()
	{
		return view('welcome')->with('activePage', 'root');
	}

	/**
	 * Responds to GET /list/{id}
	 */
	public function getList($id)
	{
		//TODO: Probably want to add some sort of check to make sure $id is a valid id. That way the app can handle the error properly.

		$selectedWeblist = ListController::getWeblistByNameid( $id );
		//\Log::info( 'Got ' . $selectedWeblist->listitems()->count() . ' listitems with id of ' . $id . '.' );
		return view('list')->with('list', $selectedWeblist);
	}

	/**
	 * Responds to GET {username}/list/{id}
	 */
	public function getUsersList($username, $id)
	{
		//TODO: Probably want to add some sort of check to make sure $id is a valid id. That way the app can handle the error properly.

		$selectedWeblist = ListController::getWeblistByNameid( $id );
		//\Log::info( 'Got ' . $selectedWeblist->listitems()->count() . ' listitems with id of ' . $id . '.' );
		return view('list')->with('list', $selectedWeblist);
	}

	/**
	 * Responds to POST /additem
	 */
	public function postAddItem()
	{
		//TODO: Probably want to put in a Laravel Validator here like in postAddWeblist(Request $request)

		if( Input::has('listId') && Input::has('itemDescription') )
		{

			$listId = Input::get('listId');
			ListController::addItemToWeblist( $listId, Input::get('itemDescription') );

			return redirect()->route( 'list', [\ListApp\Weblist::where('weblistid', $listId)->first()->nameid] );

			/*
			\Log::info( 'postAddItem(): userid: ' . \Auth::user()->userid );
			\Log::info( 'postAddItem(): listId: ' . Input::get('listId') );
			\Log::info( 'postAddItem(): itemDescription: ' . Input::get('itemDescription') );
			*/
		}
		else
		{
			Session::flash( 'error', 'There was a problem adding the item.' );
			\Log::error('In postAddItem(), Did not get the required info, listId and itemDescription (maybe more if Ive forgotten to update this message.');
			return view('welcome');
		}
	}

	/**
	 * Responds to POST /deleteitem
	 */
	public function postDeleteItem()
	{
		//TODO: Probably want to put in a Laravel Validator here like in postAddWeblist(Request $request)

		if( Input::has('itemId') && Input::has('listId') )
		{
			$itemId = Input::get('itemId');
			$listId = Input::get('listId');
			$deleteWorked = ListController::deleteItemFromWeblist( $itemId );
			if( $deleteWorked == FALSE )
			{
				Session::flash( 'error', 'Could not delete that item.' );
				\Log::error('In postDeleteItem(), could not delete list item for itemId ' . $itemId . ' and listId ' . $listId . ' (maybe more if Ive forgotten to update this message.');
			}
			//return \Redirect::route( 'list', array('id' => $listId) );
			return \Redirect::route( 'list', array('id' => \ListApp\Weblist::where('weblistid', $listId)->first()->nameid) );
		}
		else
		{
			Session::flash( 'error', 'There was a problem deleting the item.' );
			\Log::error('In postDeleteItem(), Did not get the required info, itemId and listId (maybe more if Ive forgotten to update this message.');
			//return view('welcome');
			return \Redirect::route( 'list', array('id' => \ListApp\Weblist::where('weblistid', $listId)->first()->nameid) );
		}
	}

	/**
	 * Responds to POST /deletetag
	 */
	public function postDeleteTag()
	{
		//TODO: Probably want to put in a Laravel Validator here like in postAddWeblist(Request $request)

		if( Input::has('itemId') && Input::has('listId') && Input::has('tagId') )
		{
			$itemId = Input::get('itemId');
			$listId = Input::get('listId');
			$tagId = Input::get('tagId');
			$deleteWorked = ListController::deleteTagFromItem( $tagId );
			if( $deleteWorked == FALSE )
			{
				Session::flash( 'error', 'Could not delete that item.' );
				\Log::error('In postDeleteTag(), could not delete list item for itemId ' . $itemId . ' and listId ' . $listId . ' and tagId ' . $tagId . ' (maybe more if Ive forgotten to update this message.');
			}
			return \Redirect::route( 'list', array('id' => $listId) );
		}
		else
		{
			Session::flash( 'error', 'There was a problem deleting the item.' );
			\Log::error('In postDeleteTag(), Did not get the required info, itemId and listId and tagId (maybe more if Ive forgotten to update this message.');
			return view('welcome');
		}
	}

	/**
	 * Responds to POST /addweblist
	 */
	public function postAddWeblist(Request $request)
	{
		$this->validate($request, [
			'listTitle' => 'required|min:3',
			'listId' => 'required|min:3|alpha_dash',
		]);

		if( Input::has('listTitle') && Input::has('listId') )
		{
			ListController::addWeblist( Input::get('listTitle'), Input::get('listId'), $request->user()->userid );
			return \Redirect::route( 'home' );
		}
		else
		{
			Session::flash( 'error', 'There was a problem adding the item.' );
			\Log::error('In postAddWeblist(), Did not get the required info, listTitle and listId (maybe more if Ive forgotten to update this message.');
			return \Redirect::route( 'home' );
		}
	}

	/**
	 * Responds to POST /updateweblist
	 */
	public function postUpdateWeblist(Request $request)
	{
		$this->validate($request, [
			'listId' => 'required',
			'listNameId' => 'required',
		]);

		Session::flash( 'message', 'Got a request to update listid: ' . Input::get('listId') . ' listnameid: ' . Input::get('listNameId') );
		return \Redirect::route( 'list', array('id' => Input::get('listNameId')) );
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
	 * Generates a UUID that's not in the $columnName column of the $tableName table.
	 * 
	 * $tableName - the name of the table to check for duplicates
	 * $columnName - the name of the column within the table to check for duplicates
	 */
	public static function getUUID( $tableName, $columnName )
	{
		if( empty($tableName) || empty($columnName) )
		{
			\Log::error('In getUUID( $tableName, $columnName ), either $tableName, "' . $tableName . '", is empty or $columnName, "' . $columnName . '", is empty.' );
			abort(500);
		}
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
				$uuid = ListAppController::getUUIDNoChecks();
			}
			catch( UnsatisfiedDependencyException $e )
			{
				\Log::error('There was a problem generating the UUID: ' . $e.getMessage() .'\n' . $e.getTraceAsString());
				abort(500);
			}
			$usersWithSameID = \DB::table($tableName)->where($columnName, $uuid);
		}
		while( $usersWithSameID->count() > 0 );//If a user with the same id is found, create another one.

		return $uuid;
	}

	/**
	 * Generates a UUID.
	 */
	public static function getUUIDNoChecks()
	{
		$uuid = '';

		try
		{
			$uuid = Uuid::uuid4();
		}
		catch( UnsatisfiedDependencyException $e )
		{
			\Log::error('In getUUIDNoChecks(), There was a problem generating the UUID: ' . $e.getMessage() .'\n' . $e.getTraceAsString());
			throw $e;
		}

		return $uuid;
	}
}
