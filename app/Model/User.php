<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model{
    
    protected $table = 'mdl_user';
	protected $primaryKey = 'id';
	public $timestamps = false;
    protected $fillable = [
    	'username', 'password'
    ];

}
