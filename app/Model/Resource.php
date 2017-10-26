<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{

    protected $table = 'mdl_resource';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id', 'course', 'name', 'intro', 'intoformat', 'tobemigrate', 'legacyfiles', 'legacyfileslast', 'display', 'displayoptions', 'filterfiles', 'revision', 'timemodified'
    ];
}