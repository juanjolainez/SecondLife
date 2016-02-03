<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User as User;
use Illuminate\Support\Facades\Input;
use App\Http\libs\Social\SocialLoginManager;

class ApiController extends Controller
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
		try {
			\Hybrid_Endpoint::process();
		}
		catch (\Exception $e) {
			// redirect back to http://URL/social/
			//return Redirect::to('social');
		}
		return;
	}

	public function getLogout() 
	{
		\Auth::logout();
		\Session::flush();
		return \Redirect::to('/');
	}

	private function getUser($provider)
	{
		try 
		{

			$oauth = new \Hybrid_Auth(app_path('../config/hybridauth.php'));

			$providerAuth = $oauth->authenticate($provider);
			
			$profile = $providerAuth->getUserProfile();

			$user = User::loginWithSocialNetwork($providerAuth, $profile, $oauth->getSessionData(), true);
			$token = $user->setHidden($user->loginHidden);
			return ['user' => $user];
		} catch (\Exception $e) {
			return ['error' => $e->getMessage()];
		}
	}

	function postEmailSignup()
    {
        $email = Input::get('email');
        $password = Input::get('password');

        if (!$email || !$password) {
            return \Response::json(['error' => 'Introduce an email and a password'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return \Response::json(['error' => 'Not a valid email address'], 400);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            return \Response::json(['error' => 'This user has already been taken'], 403);
        } else {
            $user = new User;
            $user->email = $email;
            $user->password = \Hash::make($password);
            $user->token = md5(sha1(\Config::get('app.key').microtime().rand()));
            $user->save();
            $user->setHidden($user->loginHidden);

            return \Response::json(['user' => $user]);
        }
    }

    public function postLogin()
    {
	  // change the following paths if necessary
    	$provider = Input::get('provider');
    	
    	if ($provider == 'traditional') {
    		$email = Input::get('email');
    		$password = Input::get('password');
    		if (\Auth::attempt(['email' => $email, 'password' => $password])) {
    			return ['user' => \Auth::user()];
    		} else {
    			\Response::json(['error' => 'The email and password are not correct'], 401);
    		}
    	} else {
    	 	$token = Input::get('token');
            $exploded = explode(' ', $token);
            $secret = count($exploded) == 2 ? explode(' ', $token)[1]: null;
            $token = explode(' ', $token)[0];

            $result = SocialLoginManager::login($provider, $token, $secret);
    	} 

    	if (array_key_exists('error', $result)) {
    		return \Response::json(['error' => $result['error']], 401);
    	} else {
    		return \Response::json(['user' => $result['user']]);
    	}
    }
}
