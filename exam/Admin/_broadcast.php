<?php
	require_once 'core/init.php';
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$errors = array();
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'emailto' => array(
					'required' => true,
					),
				'subject' => array(
					'required' => true,
					'max' => 200,
					'min' => 2
					),
				'message' => array(
					'required' => true,
					'min' => 10,
					'max' => 5000,
					),
			));

			if($validation->passed()) {
				$t = Input::get('emailto');
				$applicantTable = str_replace(' ', '_', $t);	//the same way it was created with javascript in broadcast.php
			
				$applicant = new Applicant(null, $applicantTable);
				try {
					$add = $applicant->get(array('1', '=', '1'), 'email');
					$address = "";
					foreach ($add as $value) {
						$address .= ($value->email.",");		//comma separated mail addresses
					}
					$subject = Input::get('subject');
					$message = Input::get('message');
					//use mailer to send message here 

					Session::flash('home', "Message sent to ".$applicant->total." applicants");
					Redirect::to($_SERVER['HTTP_REFERER']);
				} catch (Exception $e) {
					die($e->getMessage());
				}
				
			} else {
				foreach ($validation->errors() as $error) {
					$errors[] = $error;
				}
				$msg = implode("<br>", $errors);
				Session::flash('home', $msg);
				Redirect::to($_SERVER['HTTP_REFERER']);
			}
		}
	}
?>