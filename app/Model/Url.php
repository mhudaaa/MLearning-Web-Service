<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Url extends Model{
    
    protected $table = 'mdl_url';
	protected $primaryKey = 'id';
	public $timestamps = false;
    protected $fillable = [
    	'name', 'externalurl', 'display', 'course', 'intro', 'introformat', 'parameters', 'displayoptions', 'timemodified'
    ];

}
