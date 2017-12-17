<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Kategori;
use App\Model\Log;
use App\Model\Course;

class KategoriController extends Controller{

    // Add course section
    // Params : courseId, userId, title
	public function addCourseSection(Request $request){

        // Initialize Kategori
        $kategori = new Kategori();
        $lastsection = $kategori->max('section') + 1;

        // Insert Kategori Data 
        $kategoriData[] = [
            'course' => $request->courseId,
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

        // Create course updated log
        $this->createLog('\\core\\event\\course_section_created', 'created', $lastId, 'c', $request->courseId, $request->userId, 'a:1:{s:10:"sectionnum";i:'.$lastsection.';}');

        // Update Course Cache
        $this->updateCourseCache($request->courseId);
    }

    // Edit course section name
    // Params : matkulId, kategoriId, kategoriSection, userId, title
    public function editCourseSection(Request $request){
        $courseSection = new Kategori();
        $courseSection = $courseSection->where('id', $request->kategoriId)->update(['name' => $request->title]);

        // Create course updated log
        $this->createLog('\\core\\event\\course_section_updated', 'updated', $request->kategoriId, 'u', $request->matkulId, $request->userId, 'a:1:{s:10:"sectionnum";s:1:"'.$request->kategoriSection.'";}');

        // Update Course cache
        $this->updateCourseCache($request->matkulId);
    }

    // Delete course section name
    // Params : courseId, kategoriId, kategoriSection, userId, title
    public function deleteCourseSection(Request $request){
        $courseSection = Kategori::find($request->kategoriId);
        $courseSection->delete();

        // Create course deleted log
        $this->createLog('\\core\\event\\course_section_deleted', 'deleted', $request->kategoriId, 'd', $request->matkulId, $request->userId, 'a:2:{s:10:"sectionnum";s:1:"'.$request->kategoriSection.'";s:11:"sectionname";s:'.strlen($request->title).':"'.$request->title.'";}');
        
        // Update course cache
        $this->updateCourseCache($request->matkulId);
    }


    // FUNCTION: CREATE LOG FUNCTION
    public function createLog($eventname, $action, $objectid, $crud, $userid, $courseid, $other){
        $log = new Log();
        $logData[] = [
            'eventname' => $eventname,
            'component' => 'core',
            'action' => $action,
            'target' => 'course_section',
            'objecttable' => 'course_sections',
            'objectid' => $objectid,
            'crud' => $crud,
            'edulevel' => 1,
            'contextid' => 21,
            'contextlevel' => 50,
            'contextinstanceid' => $courseid,
            'userid' => $userid,
            'courseid' => $courseid,
            'relateduserid' => NULL,
            'anonymous' => 0,
            'other' => $other,
            'timecreated' => time(),
            'origin' => 'web',
            'ip' => '0:0:0:0:0:0:0:1',
            'realuserid' => NULL
        ];

        $log->insert($logData);
    }

    // FUNCTION: UPDATE COURSE CACHE
    public function updateCourseCache($courseid){
        $cache = new Course();
        $result = $cache->where('id', $courseid)->update(['cacherev' => time()]);
    }
}
