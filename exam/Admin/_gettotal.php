<?php
	require_once 'core/init.php';
	$operators = array('0', '=', '<', '>', '<=', '>=', '1');
	$table = Input::get('table');
	$filter = Input::get('filter');
	$operator = '';
	if(Input::get('operator')) {
		$operator = $operators[Input::get('operator')];
	}
	if($operator == '1') {
		$operator = html_entity_decode(Input::get('otherOperator'));
	}
	$value = Input::get('value');
	$gender = Input::get('gender');
	if($table && $filter && ($value || $gender)) {
		$applicant = new Applicant(null, $table);
		if($gender) {
			$applicant->get(array('gender', '=', $gender));
			echo $applicant->total." applicant";
		} elseif ($value && $operator) {
				try {
					$applicant->get(array('score', $operator, $value));
					echo $applicant->total." applicant";	
				} catch (Exception $e) {
					print_r($e->getMessage());
				}
		} else echo "enter a valid combination";
	} elseif(Input::get('test')) {
		$testName = str_replace(' ', '_', Input::get('test'));
        $applicantTable = $testName.'_applicants';
        $applicant = new Applicant(null, $applicantTable);
        $applicant->get(array('1', '=', '1'), 'email');
        echo $applicant->total;
	} else echo "parameters too few to get result";

?>