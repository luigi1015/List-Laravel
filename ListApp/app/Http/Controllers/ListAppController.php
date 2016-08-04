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
		return view('home')->with('lists', ListController::getUsersWeblists())->with('activePage', 'userhome')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
	}

	/**
	 * Responds to GET /settings
	 */
	public function getSettings()
	{
		if( ListAppSettingsController::isCurrentUserAdmin() == true || ListAppSettingsController::isCurrentUserRoot() == true )
		{
			return view('settings')->with('activePage', 'settings')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
		else
		{
			Session::flash( 'error', 'You do not have access to that page.' );
			return redirect('/')->with('activePage', 'root')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
	}

	/**
	 * Responds to GET /settings/users
	 */
	public function getUsers()
	{
		if( ListAppSettingsController::isCurrentUserAdmin() == true || ListAppSettingsController::isCurrentUserRoot() == true )
		{
			$users = ListAppSettingsController::getUsers();
			return view('users')->with('users', $users)->with('activePage', 'settings')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
		else
		{
			Session::flash( 'error', 'You do not have access to that page.' );
			return redirect('/')->with('activePage', 'root')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
	}

	/**
	 * Responds to GET /
	 */
	public function getRoot()
	{
		return view('welcome')->with('activePage', 'root')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
	}

	/**
	 * Responds to GET /list/{id}
	 */
	public function getList($id)
	{
		//TODO: Probably want to add some sort of check to make sure $id is a valid id. That way the app can handle the error properly.

		$selectedWeblist = ListController::getWeblistByNameid( $id );
		//\Log::info( 'Got ' . $selectedWeblist->listitems()->count() . ' listitems with id of ' . $id . '.' );
		return view('list')->with('list', $selectedWeblist)->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
	}

	/**
	 * Responds to GET /user/{username}/lists
	 */
	public function getListsOfUser($username)
	{
		$weblists = ListController::getWeblistsOfUser( $username );
		//\Log::info('Got ' . $weblists->count() . ' weblists.');
		return view('listsofuser')->with('lists', $weblists)->with('username', $username)->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
	}

	/**
	 * Responds to GET /user/{username}/list/{id}
	 */
	public function getUsersList($username, $id)
	{
		//TODO: Probably want to add some sort of check to make sure $id is a valid id. That way the app can handle the error properly.

		$user = \ListApp\User::where('username', $username)->first();
		$selectedWeblist = ListController::getWeblistByUseridAndNameid( $user->userid, $id );
		//\Log::info( 'Got ' . $selectedWeblist->listitems()->count() . ' listitems with id of ' . $id . '.' );
		//\Log::info( 'Got request for user ' . $username . ' and list ' . $id . '.' );
		//return view('list');
		return view('list')->with('list', $selectedWeblist)->with('username', $username)->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
	}

	/**
	 * Responds to GET /user/{username}
	 */
	public function getUserPage($username)
	{
		return redirect()->route('userlist', [$username]);
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
			$ownerid = ListController::getWeblistOwner($listId);
			if( !is_null($ownerid) )
			{
				\Log::error( 'In postAddItem(), weblist ' . $listId );
				$owner = \ListApp\User::where('userid', $ownerid)->first();

				return redirect()->route( 'list', [$owner->username, \ListApp\Weblist::where('weblistid', $listId)->first()->nameid] )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
			}
			else
			{
				Session::flash( 'error', 'There was a problem adding the item.' );
				\Log::error( 'In postAddItem(), There was a problem adding the item, can not find the weblist owner for weblist ' . $listId );
				return view('welcome')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
			}

			/*
			\Log::info( 'postAddItem(): userid: ' . \Auth::user()->userid );
			\Log::info( 'postAddItem(): listId: ' . Input::get('listId') );
			\Log::info( 'postAddItem(): itemDescription: ' . Input::get('itemDescription') );
			*/
		}
		else
		{
			Session::flash( 'error', 'There was a problem adding the item.' );
			\Log::error( 'In postAddItem(), Did not get the required info, listId and itemDescription (maybe more if Ive forgotten to update this message.' );
			return view('welcome')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
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
			return \Redirect::route( 'list', array('id' => \ListApp\Weblist::where('weblistid', $listId)->first()->nameid) )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
		else
		{
			Session::flash( 'error', 'There was a problem deleting the item.' );
			\Log::error('In postDeleteItem(), Did not get the required info, itemId and listId (maybe more if Ive forgotten to update this message.');
			//return view('welcome');
			return \Redirect::route( 'list', array('id' => \ListApp\Weblist::where('weblistid', $listId)->first()->nameid) )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
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
			return \Redirect::route( 'list', array('id' => $listId) )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
		else
		{
			Session::flash( 'error', 'There was a problem deleting the item.' );
			\Log::error('In postDeleteTag(), Did not get the required info, itemId and listId and tagId (maybe more if Ive forgotten to update this message.');
			return view('welcome')->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
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
			return \Redirect::route( 'root' )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
		}
		else
		{
			Session::flash( 'error', 'There was a problem adding the item.' );
			\Log::error('In postAddWeblist(), Did not get the required info, listTitle and listId (maybe more if Ive forgotten to update this message.');
			return \Redirect::route( 'root' )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
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

		//TODO: Put in a check so that it won't update anything if the logged in user has no edit rights to the list.

		$listId = $request->input( 'listId' );

		$list = \ListApp\Weblist::where( 'weblistid', $listId )->first();

		//Before the items are set as selected, set all of them as unselected (so if one isn't selected on the screen, it won't be in the database).
		foreach( $list->listitems as $listitem )
		{
			$listitem->checked = false;
			$listitem->save();
		}

		$input = $request->all();

		\Log::info( $input );

		//Go throgh all the inputs
		foreach( $input as $key => $value )
		{
			//Look for deletes
			if( starts_with($key, 'checkbox-delete-') )
			{
				$idToDelete = substr($key, 16);
				\Log::info( 'Got request to delete item with ID: ' . $idToDelete );
				$itemToDelete = \ListApp\Listitem::where( 'listitemid', $idToDelete )->first();
				if( isset($itemToDelete) )
				{
					\Log::info( 'The description is ' . $itemToDelete->description );
					$itemToDelete->delete();
				}
				else
				{
					\Log::error( 'Could not find list item with ID ' . $idToDelete );
				}
			}

			//Look for selects
			if( starts_with($key, 'checkbox-selected-') )
			{
				$idToSelect = substr($key, 18);
				\Log::info( 'Got request to select item with ID: ' . $idToSelect );
				$itemToSelect = \ListApp\Listitem::where( 'listitemid', $idToSelect )->first();
				if( isset($itemToSelect) )
				{
					\Log::info( 'The description is ' . $itemToSelect->description );
					$itemToSelect->checked = true;
					$itemToSelect->save();
				}
				else
				{
					\Log::error( 'Could not find list item with ID ' . $idToSelect );
				}
			}
		}

		//Make the list public or not
		if( $request->has('public') )
		{
			\Log::info( 'The list should be public.' );
			$list = \ListApp\Weblist::where( 'weblistid', $listId )->first();
			if( isset($list) )
			{
				\Log::info( 'The name is ' . $list->nameid );
				$list->public = true;
				$list->save();
			}
			else
			{
				\Log::error( 'Could not find list item with ID ' . $idToSelect );
			}
		}
		else
		{
			\Log::info( 'The list should not be public.' );
			$list = \ListApp\Weblist::where( 'weblistid', $listId )->first();
			if( isset($list) )
			{
				\Log::info( 'The name is ' . $list->nameid );
				$list->public = false;
				$list->save();
			}
			else
			{
				\Log::error( 'Could not find list item with ID ' . $idToSelect );
			}
		}

		Session::flash( 'message', 'Got a request to update listid: ' . Input::get('listId') . ' listnameid: ' . Input::get('listNameId') );
		return \Redirect::route( 'list', array('username' => Input::get('username'), 'id' => Input::get('listNameId')) )->with('isAdmin', ListAppSettingsController::isCurrentUserAdmin())->with('isRoot', ListAppSettingsController::isCurrentUserRoot());
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
