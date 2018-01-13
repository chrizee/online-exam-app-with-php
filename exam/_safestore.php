<?php
	require_once 'core/init.php';
  if(!Input::exists()) {
    Redirect::to(404);
  }
	$ques_no = Input::get('name');
	$testId = Input::get('test_id');
  $time = Input::get('time');
  $answer = Input::get('name').":".Input::get('value');
  $testInfo = Test::get(Input::get('test_id'));
  $testName = str_replace(' ', '_', $testInfo->test_name);
  $applicantTable = $testName.'_applicants';
  
  $applicant = new Applicant(null, $applicantTable);
  $id = $applicant->data()->id;
  try {
    $conn = DB::getInstance();
    //if record already exist in the temp_ans table update it other wise insert the first set of record
    if($conn->get('temp_answers', array('applicant_id', '=', $id, 'test_id', '=', Input::get('test_id')))->count() == 1) {
      //if record exist, first check if the question number has been answered before and replace it
      $sql1 = "SELECT LOCATE(?, answers) AS location FROM temp_answers WHERE applicant_id = $id AND test_id = $testId";
      if($position = $conn->query($sql1, array($ques_no))->first()->location) {//get the position of the ans (mysql index starts from 1)
        $ans = $conn->get('temp_answers', array('applicant_id', '=', $id, 'test_id', '=', Input::get('test_id')), 'answers')->first()->answers;
        $ansN = substr_replace($ans, $answer, $position - 1, 3); //replace existing ans with most recent choice
        $sql2 = "UPDATE `temp_answers` SET `answers` = ?, `time_remaining` = ?  where applicant_id = $id AND test_id = $testId";
        $conn->query($sql2, array($ansN, $time));

      } else{
        //else if question has not been answerd, concatenate it to the set of answers
        $sql3 = "UPDATE `temp_answers` SET `answers` = CONCAT_WS(',', answers, ?), `time_remaining` = ?  where applicant_id = $id AND test_id = $testId";
        $conn->query($sql3, array($answer, $time));
      }

    } else {
        $conn->insert('temp_answers', array('test_id' => $testId, 'applicant_id' => $id, 'answers' => $answer, 'time_remaining' => $time));    
    }
  } catch(Exception $e) {
      die($e->getMessage());
  }
?>