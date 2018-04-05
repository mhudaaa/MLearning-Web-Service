<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    protected $table = 'mdl_logstore_standard_log';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'eventname', 'component', 'action', 'target', 'objecttable', 'objectid', 'crud', 'edulevel', 'contextid', 'contextlevel', 'contextinstanceid', 'userid', 'courseid', 'relateduserid', 'anonymous', 'other', 'timecreated', 'origin', 'ip', 'realuserid'
    ];
}