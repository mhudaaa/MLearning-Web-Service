<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Kategori;
use App\Model\Log;
use App\Model\Context;
use App\Model\Course;
use App\Model\CourseModule;
use App\Model\RecentActivity;
use App\Model\Url;

class MateriController extends Controller{

	public function FunctionName(Request $request){
		
		// Initialize Kategori
        $kategori = new Kategori();
        $kategori->setConnection('moodle');
	}
}
