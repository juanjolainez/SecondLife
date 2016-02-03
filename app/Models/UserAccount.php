<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Model as Eloquent;

class UserAccount extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $collection = 'user_accounts';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
}
