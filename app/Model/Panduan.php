<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Panduan extends Model{

	protected $connection = 'mlearning';
    protected $table = 'tb_panduan';
    protected $primaryKey = 'id_panduan';
    public $timestamps = false;
	
	protected $fillable = [
		'nama', 'ikon', 'deskripsi', 'level'
	];
}
