<?php
	if(!class_exists('Question')) {
		class Question {
			private $_db,
					$_table,
					$_ques = false,		//questions from database
					$_ans = false,		//answers from database
					$_noOfQues = 0,		//no of questions returned from the table
					$_score = 0,			//applicants score after marking
					$_ansDb,				//ans to store in applicant database
					$_answers = array(),	//answers from user
					$_ansFromDb;			//stores the correct answers from DB to use when marking

			public function __construct($tableName = 'questions') {	//initialized for testing purpose
				$this->_db = DB::getInstance();
				$this->_table = $tableName;
			}

			//method to get questions from the specified question table in the construtor
			public function getQ($id = false) {
				if(!$id) {
					if($this->_table) {
						if(!$this->_ques = $this->_db->get($this->_table, array(1, '=', 1))) {
							throw new Exception("Error getting questions");
						}
						$this->_noOfQues = $this->_ques->count();
						foreach ($this->_ques->results() as $value) {
							$this->_ansFromDb[$value->id] = $value->answer;
						}
						return $this->_ques->results();
					}
				}
				//id is passed to get info of particular ques for the editing functionality 
				if($id) {
					if(!$this->_ques = $this->_db->get($this->_table, array('id', '=', $id))) {
						throw new Exception("Error getting question for editing");
					}
					$this->_noOfQues = $this->_ques->count();
					return $this->_ques->first();
				}
				return false;
			}

			//method to add questions to database
			public function putQ($ques = array()) {
				if(!$this->_db->insert($this->_table, $ques)) {
					throw new Exception("Error adding question to DB");					
				}
			}
			//method tp update question in question table
			public function updateQ($fields = array(), $id) {
				if(!$this->_db->update($this->_table, $id, $fields)) {
					throw new Exception("Error updating questions");
				}
			}

			//check if no of ques submitted == no of ques from DB and arranges the submitted answer to a string to be stored in db
			public function checkQ($values = array()) {
				$this->getQ();
				$keys = array_keys($values);
				foreach ($keys as $key => $value) {
					if (is_numeric($value)) {
						$this->_answers[$value] = $values[$value];

					}
				}
				if($values['forced'] == 0) {	//if form is not submitted by force(javascript), check for equality of ques
					if(count($this->_answers) == $this->_noOfQues) {
						$this->_ansDb = implode(',', $this->_answers);
						return true;
					}
				} elseif($values['forced'] == 1) {		//if form is submitted by force(javascript), dont check for equality of ques
					$this->_ansDb = implode(',', $this->_answers);
					return true;
				}
				return false;
			}

			//compares the two set of answers and returns the %score of the student for both types of answers
			public function mark($multiple = false) {
				if($multiple) {			//if mulitple options can be the answer
					foreach ($this->_answers as $key => $value) {
						if(strstr($this->_ansFromDb[$key], $value)) {
							++$this->_score;
						}
					}
					return ($this->_score/$this->_noOfQues) * 100 ;
				} else {
					$markedCorrect = array_intersect_assoc($this->_ansFromDb, $this->_answers);
					$this->_score = count($markedCorrect);
					$markedIncorrect = array_diff_assoc($this->_answers, $markedCorrect);
					$incorrectScore = count($markedIncorrect);
					return ($this->_score/$this->_noOfQues) * 100 ;
				}
			}

			public function preFill($testId, $applicantId) {
				if(!$fill = $this->_db->get('temp_answers', array('applicant_id', '=', $applicantId, 'test_id', '=', $testId),'answers, time_remaining')){
					return false;
				}
				return $fill->results();
			}

			public function ansDb() {
				return $this->_ansDb;
			}

			public function noOfQues() {
				return $this->_noOfQues;
			}
		}
	}
?>
