<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Panduan;
use App\Model\PanduanDetail;

class PanduanController extends Controller{

	// Daftar Panduan
    public function getPanduan($level){
        $panduan = Panduan::where('level', $level)->get();
        return response()->json($panduan);
    }

	// Detail Panduan
    public function getPanduanDetail($id){
        $detail = PanduanDetail::where('id_panduan', $id)->get();
        return response()->json($detail);
    }
}
