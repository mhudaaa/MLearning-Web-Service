<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Kategori;
use App\Model\Log;
use App\Model\Course;

class KategoriController extends Controller{

	public function tambahKategori(Request $request){

        // Initialize Kategori
        $kategori = new Kategori();
        $lastsection = $kategori->max('section') + 1;

        // Insert Kategori Data 
        $kategoriData[] = [
            'course' => $request->courseid,
            'section' => $lastsection,
            'summary' => "",
            'summaryformat' => 1,
            'sequence' => "",
            'name' => $request->title,
            'visible' => 1,
            'availability' => NULL,
        ];
        $kategori->insert($kategoriData);

        // Get latest Id
        $newKategori = new Kategori();
        $lastRow = $newKategori->orderBy('id', 'desc')->first();
        $lastId = $lastRow['id'];

        // Insert Log
        $log = new Log();

        $logData[] = [
            'eventname' => '\\core\\event\\course_section_created',
            'component' => 'core',
            'action' => 'created',
            'target' => 'course_section',
            'objecttable' => 'course_sections',
            'objectid' => $lastId,
            'crud' => 'c',
            'edulevel' => 1,
            'contextid' => 21,
            'contextlevel' => 50,
            'contextinstanceid' => 2,
            'userid' => 2,
            'courseid' => $request->courseid,
            'relateduserid' => NULL,
            'anonymous' => 0,
            'other' => 'a:1:{s:10:"sectionnum";i:'.$lastsection.';}',
            'timecreated' => time(),
            'origin' => 'web',
            'ip' => '0:0:0:0:0:0:0:1',
            'realuserid' => NULL
        ];

        $log->insert($logData);

        // Update Course Cache
        $cache = new Course();
        $result = $cache->where('id', $request->courseid)->update(['cacherev' => time()]);

    }

}
