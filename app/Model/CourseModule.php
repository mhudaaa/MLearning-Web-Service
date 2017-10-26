<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{

    protected $table = 'mdl_course_modules';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'course', 'module', 'instance', 'visible', 'visibleoncoursepage', 'visibleold', 'idnumber', 'groupmode', 'groupingid', 'completion', 'completiongradeitemnumber', 'completionview', 'completionexpected', 'availability', 'showdescription', 'added'
    ];
}
