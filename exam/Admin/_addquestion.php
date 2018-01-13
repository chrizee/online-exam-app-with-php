<?php
	require_once "core/init.php";
	if(!empty(Input::get("addNewQues"))) {
		if(isset($_POST["_wysihtml5_mode"])) unset($_POST["_wysihtml5_mode"]);
		$testId = decode(Input::get("test_id"));
		$testInfo = Test::get($testId);
		$testName = str_replace(' ', '_', $testInfo->test_name);
		$quesTable = strtolower($testName.'_questions');
		$ques = new Question($quesTable);
		$start = Input::get('start');
		$end = Input::get('end');
		for ($i = $start; $i <= $end; $i++) {
			(is_array(Input::get("answer_".$i))) ? $answer = implode(',', Input::get("answer_".$i)) : $answer = Input::get("answer_".$i);
			try {
			  $ques->putQ(array(
			    'test_id' => $testId,
			    'question' => trim(Input::get("question_".$i)) ,
			    'A' => Input::get("option_".$i."_A"),
			    'B' => Input::get("option_".$i."_B"),
			    'C' => Input::get("option_".$i."_C"),
			    'D' => Input::get("option_".$i."_D"),
			    'E' => Input::get("option_".$i."_E"),
			    'answer' => $answer,
			    ));     
			    Session::flash('home', " Questions added successfully to ".$testInfo->test_name);       
			} catch (Exception $e) {
			  Session::flash('home', $e->getMessage());    
			}
		}                    
    }
	Redirect::to($_SERVER['HTTP_REFERER']);
?>