<?php

// hybridauth-2.x.x/hybridauth/config.php
return
   array(
      // "base_url" the url that point to HybridAuth Endpoint (where index.php and config.php are found)
      "base_url" => "http://dev.retapp.com/social/auth",
 
      "providers" => array (
         // google
            "Google" => array ( // 'id' is your google client id
               "enabled" => false,
               "keys" => array ( "id" => "", "secret" => "" ),
            ),
 
         // facebook
            "Facebook" => array ( // 'id' is your facebook application id
               "enabled" => true,
               "keys" => array ( "id" => "439018326294413", "secret" => "7dd1c7a69c9489475ed7d5ecf8c30872" ),
               "scope" => "email, user_about_me, user_birthday, user_hometown" // optional
            ),
 
         // twitter
            "Twitter" => array ( // 'key' is your twitter application consumer key
               "enabled" => true,
               "keys" => array ( "key" => "8k8yPjqau4NuchM117434Iw0v", "secret" => "3XE91hEGkBS0fqqBLg8ytJGXkDChPLfgifYTZnazkfTcBo1Kao" )
            ),
 
         // and so on ...
      ),
 
      "debug_mode" => false ,
 
      // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file
      "debug_file" => "../storage/logs/laravel.log",
    );