<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Files extends Model{
    
    protected $table = 'mdl_files';
	protected $primaryKey = 'id';
	public $timestamps = false;
    protected $fillable = [
    	'id', 'contenthash', 'pathnamehash', 'contextid', 'component', 'filearea', 'itemid', 'filepath', 'filename', 'userid', 'filesize', 'mimetype', 'status', 'source', 'author', 'license', 'timecreated', 'timemodified', 'sortorder', 'referencefileid', 
    ];

}
