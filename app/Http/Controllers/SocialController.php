<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class SocialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Api Controller
    |--------------------------------------------------------------------------
    |
    | Api calls
    |
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function getAuth()
	{
		$user = \Socialize::with('twitter')->user();
		var_dump($user);
	}

	public function getLogout() 
	{
		\Auth::logout();
		\Session::flush();
		return \Redirect::to('/');
	}


    public function getLogin($provider)
    {
		 return \Socialize::with($provider)->redirect();
    }
}

//Take credit for your heroic actions! Use retapp and let everybody know how awesome you are!
