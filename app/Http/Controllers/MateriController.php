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
	
	public function tambahMateri(Request $request){
		
		// Insert Course Module Data
	    $courseModule = new CourseModule();
        $courseModuleData[] = [
            'course' => 3, // ---COURSE ID
            'module' => 20,
            'instance' => 0,
            'visible' => 1,
            'visibleoncoursepage' => 1,
            'visibleold' => 1,
            'idnumber' => '',
            'groupmode' => 0,
            'groupingid' => 0,
            'completion' => 1,
            'completiongradeitemnumber' => NULL,
            'completionview' => 0,
            'completionexpected' => 0,
            'availability' => NULL,
            'showdescription' => 1,
            'added' => time()
        ];
        $courseModule->insert($courseModuleData);

        // Update Course Cacherev
	    $course = new Course();
        $course->where('id', 3)->update(['cacherev' => time()]); // ---COURSE ID

        // Insert Course URL Data
	    $courseUrl = new Url();
        $courseUrlData[] = [
        	'name' => $request->nama,
        	'externalurl' => $request->url,
        	'display' => 0,
        	'course' => 3, // ---COURSE ID
        	'intro' => $request->deskripsi,
        	'introformat' => 1,
        	'parameters' => 'a:0:{}',
        	'displayoptions' => 'a:1:{s:10:"printintro";i:1;}',
        	'timemodified' => time()
        ];
        $courseUrl->insert($courseUrlData);

        // Get Course Module Last Row
        $courseModuleLastRow = $courseModule->orderBy('id', 'desc')->first();
        $courseModuleLastId = $courseModuleLastRow['id'];

        // Update Course Module Instance
        $courseModuleUrl = $courseModule->where('module', 20);  // 20 = Module URL
        $courseModuleMaxInstance = $courseModuleUrl->max('instance') + 1;
        $courseModule->where('id', $courseModuleLastId)->update(['instance' => $courseModuleMaxInstance]); 

        // Insert Context
	    $context = new Context();
        $contextData[] = [
        	'contextlevel' => 70,
        	'instanceid' => $courseModuleLastId,
        	'depth' => 0,
        	'path' => NULL,
        ];
        $context->insert($contextData);

        // Get Context Last Row
        $contextLastRow = $context->orderBy('id', 'desc')->first();
        $contextLastId = $contextLastRow['id'];

        // Update Context Path & Depth
        $context->where('id', $contextLastId)->update([
        	'depth' => 4,
        	'path' => '/1/3/23/'.$contextLastId.'', //--COURSE ID
       	]);

       	// Update Course Section Sequence 
	    $courseSection = Kategori::where('id', 9);
       	$courseSectionRecent = $courseSection->select('sequence')->pluck('sequence')[0]; // ---COURSE SECTION ID
       	$courseSectionSequence = $courseSectionRecent.",".$courseModuleLastId;
       	$courseSection->update(['sequence' => $courseSectionSequence]); 

       	// Update Course Module
       	$courseModule->where('id', $courseModuleLastId)->update(['section' => 9]); // ---COURSE SECTION ID

       	// Update Course Cacherev
        $course->where('id', 3)->update(['cacherev' => time()]); // ---COURSE ID

        // Insert RecentActivity
		$recentActivity = new RecentActivity();
        $recentActivityData [] = [
        	'action' => 0,
        	'timecreated' => time(),
        	'courseid' => 3, // ---COURSE ID
        	'cmid' => $courseModuleLastId,
        	'userid' => 2, // ---USER ID
        ];

        // Insert Log
	    $log = new Log();
        $logData[] = [
            'eventname' => '\\core\\event\\course_module_created',
            'component' => 'core',
            'action' => 'created',
            'target' => 'course_module',
            'objecttable' => 'course_modules',
            'objectid' => $courseModuleLastId,
            'crud' => 'c',
            'edulevel' => 1,
            'contextid' => $contextLastId,
            'contextlevel' => 70,
            'contextinstanceid' => $contextLastId,
            'userid' => 2, // ---USER ID
            'courseid' => 3,
            'relateduserid' => NULL,
            'anonymous' => 0,
            'other' => 'a:3:{s:10:"modulename";s:3:"url";s:10:"instanceid";i:'.$courseModuleMaxInstance.';s:4:"name";s:'.strlen($request->nama).':"'.$request->nama.'";}',
            'timecreated' => time(),
            'origin' => 'web',
            'ip' => '0:0:0:0:0:0:0:1',
            'realuserid' => NULL
        ];

        $log->insert($logData);
	}
}
