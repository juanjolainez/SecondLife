<?php 

namespace App\Http\libs\Social;

use App\Models\User as User;

class FacebookManager
{
    /*
    |--------------------------------------------------------------------------
    | Facebook Manager
    |--------------------------------------------------------------------------
    |
    | Manager of facebook logins using the strategy pattern
    |
    |
    */

    public static function getUser($token)
    {
        $config = \Config::get('hybridauth');
        $socialAuth = new \Hybrid_Auth( $config );

        $socialAuth->storage()->set("hauth_session.facebook.is_logged_in", 1);
        $socialAuth->storage()->set("hauth_session.facebook.token.access_token", $token); 

        return SocialLoginManager::getUser('facebook');
    }
}
