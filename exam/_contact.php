<?php
	require_once 'core/init.php';
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$errors = array();
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'name' => array(
					'required' => true,
					'max' => 100
					),
				'email' => array(
					'required' => true,
					'function' => 'checkEmail',
					'max' => 100
					),
				'message' => array(
					'required' => true,
					),
				));
			if($validation->passed()) {
				try {
					Message::put(array(
						'name' => Input::get('name'),
						'email' => Input::get('email'),
						'message' => trim(Input::get('message'))
						));
				Session::flash('home', "Your message has been recieved and will be processed shortly");
				Redirect::to($_SERVER['HTTP_REFERER']."#contact");	
				} catch (Exception $e) {
					die($e->getMessage());
				}
			} else {
				foreach ($validation->errors() as $error) {
					$errors[] = $error;
				}
				$message = implode('<br />', $errors);
				Session::flash('home', $message);
				Redirect::to($_SERVER['HTTP_REFERER']."#contact");
			}
		}
	} else {
    	Redirect::to(404);
	}
?>