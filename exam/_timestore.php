<?php
	require_once 'core/init.php';
  if(!Input::exists()) {
    Redirect::to(404);
  }

	$testId = Input::get('test_id');
  $time = Input::get('time');

  $testInfo = Test::get($testId);
  $testName = str_replace(' ', '_', $testInfo->test_name);
  $applicantTable = $testName.'_applicants';
  
  $applicant = new Applicant(null, $applicantTable);
  $id = $applicant->data()->id;
  try {
    $conn = DB::getInstance();
    //if record already exist in the temp_ans table update it other wise insert the first set of record
    if($conn->get('temp_answers', array('applicant_id', '=', $id, 'test_id', '=', $testId))->count() == 1) {
        $sql3 = "UPDATE `temp_answers` SET `time_remaining` = ?  where applicant_id = $id AND test_id = $testId";
        $conn->query($sql3, array($time));
    } else {
        $conn->insert('temp_answers', array('test_id' => $testId, 'applicant_id' => $id, 'time_remaining' => $time));    
    }
  } catch(Exception $e) {
      die($e->getMessage());
  }
?>