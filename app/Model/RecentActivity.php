<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model {

    protected $table = 'mdl_block_recent_activity';
	protected $primaryKey = 'id';
	public $timestamps = false;
    protected $fillable = [
    	'action', 'timecreated', 'courseid', 'cmid', 'userid'
    ];
}
