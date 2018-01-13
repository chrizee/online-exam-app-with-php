<?php
ob_start();
session_start();
ini_set("smtp_port", "25");
ini_set("sendmail_from", "okoroefe16@gmail.com");
ini_set("display_errors", 'on');
ini_set("SMTP", "gmail");

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => 'christo16',
		'db' => 'exam'
	),
	'session' => array(
		'session_name' => 'admin',
		'session_name_applicant' => 'applicant',
		'token_name' => 'token'
	),
	'cookie' => array(
		'cookie_name' => 'cook',
		'remember' => 'remember',
		'expiry_one_day' => 86400,
		'expiry_one_week' => 604800
		),
);

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';	//requires a class only when needed
	}
);
require_once 'functions/sanitize.php'; //includes the function file

//checks if cookie exists and that session is not set for remember me functionality
	if (Cookie::exists(Config::get('cookie/remember')) && !Session::exists(Config::get('session/session_name'))) {
		//get the value of the cookie that is set  when remember me button is checked
		$hash = Cookie::get(Config::get('cookie/remember'));
		//check if that hash exists in the database and grabs it from there
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));
		
		if($hashCheck->count()) {
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}