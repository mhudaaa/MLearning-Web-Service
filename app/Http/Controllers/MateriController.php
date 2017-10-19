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
use App\Model\Files;
use App\Model\Resource;

class MateriController extends Controller{

	public function tambahMateri(Request $request){
		// Insert Course Module Data
	    $courseModule = new CourseModule();
        $courseModuleData[] = [
            'course' => $request->courseid, // ---COURSE ID
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
        $course->where('id', $request->courseid)->update(['cacherev' => time()]); // ---COURSE ID

        // Insert Course URL Data
	    $courseUrl = new Url();
        $courseUrlData[] = [
        	'name' => $request->title,
        	'externalurl' => $request->url,
        	'display' => 0,
        	'course' => $request->courseid, // ---COURSE ID
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
	    $courseSection = Kategori::where('id', $request->sectionid);
       	$courseSectionRecent = $courseSection->select('sequence')->pluck('sequence')[0]; // ---COURSE SECTION ID
       	$courseSectionSequence = $courseSectionRecent.",".$courseModuleLastId;
       	$courseSection->update(['sequence' => $courseSectionSequence]); 

       	// Update Course Module
       	$courseModule->where('id', $courseModuleLastId)->update(['section' => $request->sectionid]); // ---COURSE SECTION ID

       	// Update Course Cacherev
        $course->where('id', $request->courseid)->update(['cacherev' => time()]); // ---COURSE ID

        // Insert RecentActivity
		$recentActivity = new RecentActivity();
        $recentActivityData [] = [
        	'action' => 0,
        	'timecreated' => time(),
        	'courseid' => $request->courseid, // ---COURSE ID
        	'cmid' => $courseModuleLastId,
        	'userid' => $request->userid, // ---USER ID
        ];
        $recentActivity->insert($recentActivityData);

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
            'userid' => $request->userid, // ---USER ID
            'courseid' => $request->courseid,
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

   public function tambahDokumen(){

        $temp_name = $_FILES['file']['tmp_name'];
        $real_name = $_FILES['file']['name'];

        $moodledata = '/Applications/MAMP/moodledata/';
        $filedir = $moodledata.'filedir/';
        $target_dir = $moodledata. 'temp/';
        $target_file = $target_dir . basename($real_name);
        $ext = pathinfo($target_file, PATHINFO_EXTENSION);

        // var_dump($_POST);
        $userid = $_POST['info'][0];
        $author = $_POST['info'][1];
        $courseid = $_POST['info'][2];
        $coursesectionid = $_POST['info'][3];
        $materi = $_POST['info'][4];
        $deskripsi = $_POST['info'][5];

        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'zip' || $ext == 'doc' || $ext == 'docx' || $ext == 'pdf') {
            if (move_uploaded_file($temp_name, $target_file)) {
                $contenthash = sha1_file($target_dir. "/" .$real_name);
                $firstdir = substr($contenthash, 0, 2);
                $scnddir = substr($contenthash, 2, 2);
                echo $contenthash;                

                // Make dir
                if (!file_exists($filedir . $firstdir)) {
                    mkdir($filedir.$firstdir, 0777, true);
                }

                if (!file_exists($filedir . $firstdir . '/'. $scnddir)) {
                    mkdir($filedir . $firstdir. '/'. $scnddir, 0777, true);
                } 

                rename($target_file, $filedir.$firstdir.'/'.$scnddir.'/'.$contenthash);
                echo "<br>File uploaded";
                
            } else {
                echo "Sorry, there was an error uploading your file.";
            }

        } else {
            echo "Sorry, your file was not uploaded. file extension not allowed";  
        }

        // Insert course module
        $courseModule = new CourseModule();
        $courseModuleData [] = [
           'course' => $courseid,
           'module' => '17',
           'instance' => '0',
           'visible' => '1',
           'visibleoncoursepage' => '1',
           'visibleold' => '1',
           'idnumber' => '',
           'groupmode' => '0',
           'groupingid' => '0',
           'completion' => '1',
           'completiongradeitemnumber' => NULL,
           'completionview' => '0',
           'completionexpected' => '0',
           'availability' => NULL,
           'showdescription' => '1',
           'added' => time(),
        ];
        $courseModule->insert($courseModuleData);

        // Update Course Cacherev
        $course = new Course();
        $course->where('id', $courseid)->update(['cacherev' => time()]); // ---COURSE ID

        // Insert mdl resource
        $resource = new Resource();
        $resourceData [] = [
           'name' => $materi,
           'display' => '0',
           'filterfiles' => '0',
           'course' => '2',
           'revision' => '1',
           'intro' => $deskripsi,
           'introformat' => '1',
           'timemodified' => time(),
           'displayoptions' => 'a:1:{s:10:"printintro";i:1;}'
        ];
        $resource->insert($resourceData);

        // Get course module Last Row
        $courseModuleLastRow = $courseModule->orderBy('id', 'desc')->first();
        $courseModuleLastId = $courseModuleLastRow['id'];

        // Get resource last row
        $resourceLastRow = $resource->orderBy('id', 'desc')->first();
        $resourceLastId = $resourceLastRow['id'];

        // Update course module
        $courseModule->where('id', $courseModuleLastId)->update(['instance' => $resourceLastId]); 

        // Insert mdl context
        $context = new Context();
        $contextData [] = [
           'contextlevel' => '70',
           'instanceid' => $courseModuleLastId,
           'depth' => '0',
           'path' => NULL
        ]; 
        $context->insert($contextData);

        // Get context last row
        $contextLastRow = $context->orderBy('id', 'desc')->first();
        $contextLastId = $contextLastRow['id'];

        // Update mdl context
        $context->where('id', $contextLastId)->update([
           'contextlevel' => '70',
           'instanceid' => $courseModuleLastId,
           'depth' => '4',
           'path'=> '/1/3/21/'.$contextLastId
        ]); 

        // Insert mdl file
        $file = new Files();

        // get file last id
        // $fileLastRow = $file->orderBy('id', 'desc')->first();
        // $fileLastId = $fileLastRow['id'];
        // $itemid = $fileLastId + 1;


        $fileData [] = [
           'contenthash' => $contenthash,
           'pathnamehash' => sha1('/'.$contextLastId.'/mod_resource/content/0/'.$_FILES['file']['name']),
           'contextid' => $contextLastId,
           'component' => 'mod_resource',
           'filearea' => 'content',
           'itemid' => 0,
           'filepath' => '/',
           'filename' => $_FILES['file']['name'],
           'userid' => $userid,
           'filesize' => $_FILES['file']['size'],
           'mimetype' => $_FILES['file']['type'],
           'status' => '0',
           'source' => $_FILES['file']['name'],
           'author' => $author,
           'license' => 'allrightsreserved',
           'timecreated' => time(),
           'timemodified' => time(),
           'sortorder' => '1',
           'referencefileid' => NULL
        ];
        $file->insert($fileData);

        // Insert mdl file again
        $fileData2 [] = [
           'contextid' => $contextLastId,
           'component' => 'mod_resource',
           'filearea' => 'content',
           'itemid' => '0',
           'filepath' => '/',
           'filename' => '.',
           'contenthash' => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
           'filesize' => '0',
           'timecreated' => time(),
           'timemodified' => time(),
           'mimetype' => NULL,
           'userid' => $userid,
           'pathnamehash' => sha1('/'.$contextLastId.'/mod_resource/content/0/.'),
           'status' => '0',
           'source' => NULL,
           'author' => NULL,
           'license' => NULL,
           'sortorder' => '0',
           'referencefileid' => NULL
        ];
        $file->insert($fileData2);

        // Update course module
        // $courseModule->where('id', $courseModuleLastId)->update(['instance' => $resourceLastId]);
        $courseModuleDoc = $courseModule->where('module', 17);  // 17 = Resource
        $courseModuleMaxInstance = $courseModuleDoc->max('instance');
        $courseModule->where('id', $courseModuleLastId)->update(['instance' => $courseModuleMaxInstance]); 

        // Update Course Section Sequence 
        $courseSection = Kategori::where('id', $coursesectionid);
        $courseSectionRecent = $courseSection->select('sequence')->pluck('sequence')[0]; // ---COURSE SECTION ID
        $courseSectionSequence = $courseSectionRecent.",".$courseModuleLastId;
        $courseSection->update(['sequence' => $courseSectionSequence]); 

        // Update course module
        $courseModule->where('id', $courseModuleLastId)->update(['section' => $coursesectionid]); // ---COURSE SECTION ID

        // Update cacherev
        $course->where('id', $courseid)->update(['cacherev' => time()]);
        $course->where('id', $courseid)->update(['cacherev' => time()]);

        // Insert block recent activity
        $recentActivity = new RecentActivity();
        $recentActivityData [] = [
           'action' => '0',
           'timecreated' => time(),
           'courseid' => $courseid,
           'cmid' => $courseModuleLastId,
           'userid' => $userid,
        ];
        $recentActivity->insert($recentActivityData);

        // Insert logstore
        $log = new Log();
        $logData [] = [
          'eventname' => '\\core\\event\\course_module_created',
          'component' => 'core',
          'action' => 'created',
          'target' => 'course_module',
          'objecttable' => 'course_modules',
          'objectid' => $courseModuleLastId,
          'crud' => 'c',
          'edulevel' => '1',
          'contextid' => $contextLastId,
          'contextlevel' => '70',
          'contextinstanceid' => $courseModuleLastId,
          'userid' => $userid,
          'courseid' => $courseid,
          'relateduserid' => NULL,
          'anonymous' => '0',
          'other' => 'a:3:{s:10:"modulename";s:8:"resource";s:10:"instanceid";i:'.$courseModuleMaxInstance.';s:4:"name";s:'.strlen($materi).':"'.$materi.'";}',
          'timecreated' => time(),
          'origin' => 'web',
          'ip' => '0:0:0:0:0:0:0:1',
          'realuserid' => NULL,
        ];
        $log->insert($logData);
    }
}
