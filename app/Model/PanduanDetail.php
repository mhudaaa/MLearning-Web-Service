<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PanduanDetail extends Model{

	protected $connection = 'mlearning';
	protected $table = 'tb_panduan_detail';
    protected $primaryKey = 'id_panduan_detail';
    public $timestamps = false;
	
	protected $fillable = [
		'id_panduan', 'judul', 'isi'
	];
}
