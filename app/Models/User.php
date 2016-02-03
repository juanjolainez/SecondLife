<?php 

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use App\Models\UserAccount;
use \Hybrid_User_Profile;
use \Hybrid_Provider_Adapter;

use Jenssegers\Mongodb\Model as Eloquent;

class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $collection = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'remember_token', 'created_at', 'deleted_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'birthday', 'created_at', 'updated_at', 'url',
                         'gender', 'language', 'birthday', 'phone', 'address', 'country', 'region', 'city', 'token'];

    public $loginHidden = ['password', 'remember_token', 'birthday', 'created_at', 'updated_at', 'url',
                         'gender', 'language', 'birthday', 'phone', 'address', 'country', 'region', 'city'];

    /**
     * The attributes appended to the model
     *
     * @var array
     */
    protected $appends = ['avatar'];

    public $incrementing = false;

    public function accounts()
    {
        return $this->hasMany('\App\Models\UserAccount');
    }

    public function getAvatarAttribute($avatar)
    {
        if (!$avatar) {
            $avatar = 'https://www.watch2gether.com/assets/w2guser-default-4cd04e39cfd59017ebad065028b8af9dfca8499a45a7b19ec20b1c478a751a77.png';
        }

        return $avatar;
    }

    private static function getAccount(\Hybrid_Provider_Adapter $provider, Hybrid_User_Profile $profile)
    {
        return UserAccount::where('network', $provider->id)
                          ->where('network_id', $profile->identifier)
                          ->first();
    }

    private static function createUser(Hybrid_User_Profile $profile)
    {
        $user = new User;

        $data = [
            'email'     => ($profile->email) ? $profile->email : null,
            'name'      => $profile->displayName,
            'avatar'    => $profile->photoURL,
            'country'   => $profile->country,
            'gender'    => $profile->gender,
            'region'    => $profile->region,
            'phone'    => $profile->phone,
            'city'    => $profile->city,
            'address'    => $profile->address,
            'birthday'  => $profile->birthYear . '-' . $profile->birthMonth . '-' . $profile->birthDay,
            'language'      => $profile->language,
        ];
        
        $user->fill($data);
        $user->token = md5(sha1(\Config::get('app.key').microtime().rand()));
        $user->save();
        return $user;
    }

    private function createAccountToUser(\Hybrid_Provider_Adapter $provider, Hybrid_User_Profile $profile, $session = null)
    {
        $account = new UserAccount;
        $account->fill([
            'network'      => $provider->id,
            'network_id'    => $profile->identifier,
            'url'           => $profile->profileURL,
            'session_data'  => (is_array($session)) ? serialize($session) : $session,
        ]);
        $this->accounts()->save($account);
        return $account;
    }

    public static function loginWithSocialNetwork(\Hybrid_Provider_Adapter $provider, Hybrid_User_Profile $profile, $session = null)
    {
        //Check if the user has already an account 
        $existingAccount = \App\Models\User::getAccount($provider, $profile);

        if ($existingAccount) {
            return $existingAccount->user;
        }

        //There's no account in the database, so it's a signup

        //First, we need to create the user

        $user = \App\Models\User::createUser($profile);

        $account = $user->createAccountToUser($provider, $profile, $session);

        return $user;
    }


}
