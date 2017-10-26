<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Context extends Model
{

    protected $table = 'mdl_context';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'contextlevel', 'instanceid', 'depth', 'path'
    ];

}
