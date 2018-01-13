<?php
	require_once "core/init.php";
	if(!empty(Input::get("addNewRequirements"))) {
		if(isset($_POST["_wysihtml5_mode"])) unset($_POST["_wysihtml5_mode"]);
		$testId = decode(Input::get("test_id"));
		$testInfo = Test::get($testId);
	
		$string = "";
		foreach ($_POST as $key => $value) {
			if(preg_match("/^requirement/", $key)) {
				$string .= Input::get($key).",/:";
			}
		}
		if(Input::get('new') == 1) {
			try { 
			  Test::update($testId, array(
			  	'requirements' => $string
			  	));     
			    Session::flash('home', "Requirements added successfully to ".$testInfo->test_name);       
			} catch (Exception $e) {
			  Session::flash('home', $e->getMessage());    
			}	
		} else {
			echo 2;
			$sql = "UPDATE `tests` SET `requirements` = CONCAT(requirements, ?) where test_id = $testId";
			DB::getInstance()->query($sql, array($string));
			Session::flash('home', "Requirements added successfully to ".$testInfo->test_name);
		}            
    }
    if(!empty(Input::get("addNewInstructions"))) {
		if(isset($_POST["_wysihtml5_mode"])) unset($_POST["_wysihtml5_mode"]);
		$testId = decode(Input::get("test_id"));
		$testInfo = Test::get($testId);
	
		$string = "";
		foreach ($_POST as $key => $value) {
			if(preg_match("/^instructions/", $key)) {
				$string .= Input::get($key).",/:";
			}
		}
		if(Input::get('new') == 1) {
			try { 
			  Test::update($testId, array(
			  	'instructions' => $string
			  	));     
			    Session::flash('home', "Instructions added successfully to ".$testInfo->test_name);       
			} catch (Exception $e) {
			  Session::flash('home', $e->getMessage());    
			}	
		} else {
			echo 2;
			$sql = "UPDATE `tests` SET `instructions` = CONCAT(instructions, ?) where test_id = $testId";
			DB::getInstance()->query($sql, array($string));
			Session::flash('home', "Instructions added successfully to ".$testInfo->test_name);
		}            
    }
	Redirect::to($_SERVER['HTTP_REFERER']);
?>