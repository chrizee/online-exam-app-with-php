<?php
	class Test {
		private static $_table = 'tests';
		public static $_testInfo;

		//used to create a new test in the test table  and also creates new tables for the test
		public static function create($fields = array(), $testName) {
			$testName = str_replace(' ', '_', $testName);
			$applicant = $testName.'_applicants';
			$ques = $testName.'_questions';
			$sql = "CREATE TABLE IF NOT EXISTS $applicant LIKE `applicants`; CREATE TABLE IF NOT EXISTS $ques LIKE `questions`;";
			if($fields['multiple_answer'] == 1) {
				$sql .= "ALTER TABLE $ques CHANGE `answer` `answer` VARCHAR(5) NOT NULL";
			}
			if(!DB::getInstance()->insert(self::$_table, $fields)) {
				throw new Exception("Error creating test");
			}
			if(DB::getInstance()->query($sql)->error()) {
				throw new Exception("Error creating new tables");
			}
		}

		public static function update($testId, $fields = array()) {
			if(!DB::getInstance()->updateTest(self::$_table, $testId, $fields)) {
				throw new Exception("Error updating test");
			}
		}

		//used to get info about test like name duration and date
		public static function get($testId = null,$ownerId = null, $fields= null, $completed = false) {
			if($testId) {
				if(!$test = DB::getInstance()->get(self::$_table, array('test_id', '=', $testId))) {
					return false;
				}
				return $test->first();
			} else if($ownerId && $fields && $completed) {
				if(!$test = DB::getInstance()->get(self::$_table, array('owner_id', '=', $ownerId,'completed', '=', '1'), $fields)) {
					return false;
				}
				return $test->results();
			} else if($ownerId && $fields) {
				if(!$test = DB::getInstance()->get(self::$_table, array('owner_id', '=', $ownerId), $fields)) {
					return false;
				}
				return $test->results();
			} else if($completed) {
				if(!$test = DB::getInstance()->get(self::$_table, array('completed', '=', '1'), $fields)) {
					return false;
				}
				return $test->results();
			} else {
				if(!$test = DB::getInstance()->get(self::$_table, array('completed', '=', 0), $fields)) {
					return false;
				}
				return $test->results();
			}
		}

		//to determine if test exist in test table or not
		public static function exists() {
			if(!Input::exists('get')) {
		        Redirect::to(404);
		    }
		    $testId = decode(Input::get('test_id'));
		    if(!self::$_testInfo = self::get($testId)) {
		        Session::flash('home', "Test does not exist. Contact admin for further info");
		        Redirect::to(404);
		    }
		    if(self::$_testInfo->completed == 1) {
		    	Session::flash('home', "Test is completed");
		        Redirect::to('index.php');	
		    }
		    return self::$_testInfo;
		}

		public static function deleteTempAnswers($testId) {
			if(!DB::getInstance()->delete('temp_answers', array('test_id', '=', $testId))) {
				throw new Exception("Error deleting records from temporary answers");
			}
		}
	}
?>