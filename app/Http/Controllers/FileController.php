<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller{

	public function getFile(){
		include(app_path() . '/Functions/formslib.php');
		
		$browser = get_file_browser();
		$context = get_system_context();

		$filearea = null;
		$itemid   = null;
		$filename = null;
		if ($fileinfo = $browser->get_file_info($context, $component, $filearea, $itemid, '/', $filename)) {
    // build a Breadcrumb trail
			$level = $fileinfo->get_parent();
			while ($level) {
				$path[] = array('name'=>$level->get_visible_name());
				$level = $level->get_parent();
			}
			$path = array_reverse($path);
			$children = $fileinfo->get_children();
			foreach ($children as $child) {
				if ($child->is_directory()) {
					echo $child->get_visible_name();
            // display contextid, itemid, component, filepath and filename
					var_dump($child->get_params());
				}
			}
		}

		echo $browser;
	}

}
