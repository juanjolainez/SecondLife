<?php 

namespace App\Http\libs\Social;

use App\Models\User as User;

class TwitterManager
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

    public static function getUser($token, $secret)
    {
        $config = \Config::get('hybridauth');
        $socialAuth = new \Hybrid_Auth( $config );

        $socialAuth->storage()->set("hauth_session.twitter.is_logged_in", 1);
        $socialAuth->storage()->set("hauth_session.twitter.token.access_token", $token); 
        $socialAuth->storage()->set("hauth_session.twitter.token.access_token_secret", $secret);

        return SocialLoginManager::getUser('twitter');
    }
}
