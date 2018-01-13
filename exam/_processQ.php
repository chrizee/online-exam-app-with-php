<?php
	require_once 'core/init.php';
	if(Input::exists()) {
		$testId = Input::get('test_id');
		$testInfo = Test::get($testId);
		$multiple = $testInfo->multiple_answer;
		$testName = str_replace(' ', '_', $testInfo->test_name);    //the same way the table was created in the test class
	    $applicantTable = $testName.'_applicants';
	    $quesTable = $testName.'_questions';
	    $applicant = new Applicant(null, $applicantTable);
	    if(!$applicant->isLoggedIn()) {
	    	Session::flash('home', 'How did you get here');
	    	Redirect::to($_SERVER['HTTP_REFERER']);
	    }
		$ques = new Question($quesTable);
		if(!$ques->checkQ($_POST)) {
			Session::flash('home', 'Ensure the right numbers of questions are submitted');
			Redirect::to($_SERVER['HTTP_REFERER']);
		} else {
			try {
				$ansDb = $ques->ansDb();
				$mark = $ques->mark($multiple);
				$applicant->update(array('answers' => $ansDb, 'score' => $mark, 'status' => 1));
				$applicant->logout();
				Redirect::to('thanks.php');
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

	} else {
		Redirect::to(404);
	}
?>