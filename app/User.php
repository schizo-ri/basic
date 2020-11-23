<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cartalyst\Sentinel\Users\EloquentUser;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /*
	* The Eloquent designingModel model name
	* 
	* @var string
	*/
	protected static $designingModel = 'App\Models\Designing'; 
    
    /*
	* Returns the equipmentList relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasmany
	*/
	
	public function designins()
	{
		return $this->hasMany(static::$designingModel,'designer_id');
	}


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
