<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Pengumuman;

class PengumumanController extends Controller{

	public function tambahPengumuman(Request $request){
        $pengumuman = new Pengumuman();
        $pengumuman->matkul = $request->matkul;
        $pengumuman->judul_pengumuman = $request->judul;
        $pengumuman->isi_pengumuman = $request->isi;
        $pengumuman->dosen = $request->dosen;
        $pengumuman->save();
    }

    public function getPengumuman($matkul){.
        $idMatkul = explode("-", $matkul);
        $pengumuman = new Pengumuman();
    	$pengumuman = $pengumuman::whereIn('matkul', $idMatkul)->get();
        return response()->json($pengumuman);
    }

    public function getDetailPengumuman($id){
        $pengumuman = Pengumuman::where('id_pengumuman', $id)->get();
        return response()->json($pengumuman); 
    }

}
