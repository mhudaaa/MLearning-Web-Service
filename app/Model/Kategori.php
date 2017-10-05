<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model{

    protected $table = 'mdl_course_sections';
    protected $primaryKey = 'id';
    public $timestamps = false;
	protected $fillable = [
		'course', 'section', 'summary', 'summaryformat',  'sequence', 'name', 'visible', 'availability'
	];
}