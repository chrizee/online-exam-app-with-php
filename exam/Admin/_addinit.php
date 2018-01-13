<?php
	require_once 'core/init.php';

	if(Input::exists()) {
		if(!empty(Input::get('service'))) {
			$conn = DB::getInstance();
			$sql = "UPDATE init set `services` = CONCAT_WS(',',services, ?)";
			$conn->query($sql, array(Input::get('service')));
			echo 'Services Updated';
		}
		if(!empty(Input::get('about'))) {
			$conn = DB::getInstance();
			$sql = "UPDATE init set `about` =  ?";
			$conn->query($sql, array(Input::get('about')));
			echo 'About updated';
		}
	}
?>