<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Course extends Model{

	protected $table = 'mdl_course';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = [
		'cacherev'
	];

}
