<?php 

namespace App\Http\libs\Social;

use App\Http\libs\Social\FacebookManager;
use App\Http\libs\Social\TwitterManager;

use App\Models\User;


class SocialLoginManager
{
    /*
    |--------------------------------------------------------------------------
    | Social Login Manager
    |--------------------------------------------------------------------------
    |
    | Manager using the strategy pattern
    |
    |
    */

    /**
    * Create a new controller instance.
    *
    * @return void
    */
   
    public static function login($provider, $token, $secret = null)
    {
        if ($provider == 'facebook') {
            return FacebookManager::getUser($token);
        } else if ($provider == 'twitter') {
            return TwitterManager::getUser($token, $secret);
        }

        throw new Exception('Provider not correct');
    }

    public static function getUser($provider)
    {
        // try 
        // {
            $oauth = new \Hybrid_Auth(app_path('../config/hybridauth.php'));

            $providerAuth = $oauth->authenticate($provider);
            
            $profile = $providerAuth->getUserProfile();

            $user = User::loginWithSocialNetwork($providerAuth, $profile, $oauth->getSessionData(), true);
            $token = $user->setHidden($user->loginHidden);
            return ['user' => $user];
        // } catch (\Exception $e) {
        //     return ['error' => $e->getMessage()];
        // }
    }
}
