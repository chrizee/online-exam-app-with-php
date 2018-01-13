<?php
class Input {
	//checks if a field exist and returns true
	public static function exists($type = 'post') {
		switch ($type) {
			case 'post':
				return (!empty($_POST)) ? true : false;
				break;
			case 'get':
				return (!empty($_GET)) ? true : false;
				break;
			default:
				return false;
				break;
		}
	}

	//gets a particular item from the register form
	public static function get($item) {
		if(isset($_POST[$item])) {
			return self::test_input($_POST[$item]);
		} else if(isset($_GET[$item])) {
			return self::test_input($_GET[$item]);
		}	
		return '';
	}

	public static function test_input($data) {
		if(!is_array($data)){
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		} else {
			foreach ($data as $value) {
				$value = trim($value);
				$value = stripslashes($value);
				$value = strip_tags($value);
				$value = htmlspecialchars($value);
			}
			return $data;
		}

	}

	public function newForm($method = 'post', $action = '', $class, $id, $inputs = array()) {
		$form = "<form method=\"$method\" action=\"$action\" id=\"$id\" class=\"$class\" enctype=\"multipart/form-data\" >\n";
		
		foreach ($inputs as $input) {
			$form .= $input."\n";
		}

		$form .= "</form>\n";
		echo $form;
	}

	public function text($type, $name, $label, $class, $placeholder = '', $value = '', $required = true) {
		if($required) {
			$text = "<div class=\"form-group\">
						<label for=\"$name\" class=\"col-md-4 control-label\">$label</label>  
				  		<div class=\"col-md-6 inputGroupContainer\">
					  		<div class=\"input-group\">
								<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user\"></i></span>
								<input  name=\"$name\" placeholder=\"$placeholder\" class=\"$class\" id=\"$name\" type=\"$type\" autocomplete=\"off\" value=\"$value\" required>
							</div>
						</div>
					</div>";
		} else {
			$text = "<div class=\"form-group\">
				  		<label for=\"$name\" class=\"col-md-4 control-label\">$label</label>  
				  		<div class=\"col-md-6 inputGroupContainer\">
					  		<div class=\"input-group\">
								<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user\"></i></span>
								<input  name=\"$name\" placeholder=\"$placeholder\" class=\"$class\" id=\"$name\" type=\"$type\" autocomplete=\"off\" value=\"$value\">
							</div>
						</div>
					</div>";
		}
		return $text;
	}

	public function mark($type, $name, $set = array()) {
		$mark = "<div class=\"form-group\">
	            	<label class=\"control-label col-md-4\">$name</label>
	            	<div class=\"col-md-6\">\n";
	            foreach ($set as $key => $value) {
	            	$mark .= "<div class=\"$type\">
	                    			<label>
	                        			<input type=\"$type\" name=\"$name\" value=\"$value\" /> $key
	                    			</label>
	                			</div>\n";
	            }
	               
	    $mark .= "</div>
	        </div>";
	    return $mark;
	}

	public function select($name, $label, $options = array(), $required = true) {
		$select = "<div class=\"form-group\"> 
						  <label for=\"$name\" class=\"control-label\">$label</label>
						    <div class=\"selectContainer\">
							    <div class=\"input-group\">\n";
		if($required) {
			$select .= "<select id=\"$name\" name=\"$name\" class=\"col-md-11 form-control selectpicker\" required>\n";
		} else {
			$select .= "<select id=\"$name\" name=\"$name\" class=\"col-md-11 form-control selectpicker\">\n";
		}
		$select .= "<option value=\"\">--select--</option>\n";

		foreach ($options as $key => $value) {
			$select .= "<option value=\"$value\">$key</option>\n";
		}
						      		
		$select .= "</select>
						  	</div>
						</div>
					</div>";
	
		return $select;
	}

	public function submit($value, $class) {
		$submit = "<div class=\"form-group\">
			  <div class=\"col-md-4\">
			    <input type=\"submit\" class=\"$class\" name=\"submit\" value=\"$value\">
			  </div>
			</div>";
		return $submit;
	}

}
	//sample of how to use class and methods
	/*
	create a new object
	$input = new Input();

	create an array of the fields you need. also add the submit field
	$inputs = array(
		$input->text('text', 'name', 'Enter your name', 'form-control'),
		$input->text('email', 'email', 'Enter your email', 'form-control', 'email', '', false),
		$input->mark('radio', 'sex', array("female" => 1, "male" => 2)),
		$input->mark('checkbox', 'gender', array("female" => "female", "male" => "male")),
		$input->select("course", "select course", array('CCNA' => 'ccna', 'Java' => 'java', 'Web' => 'web')),
		$input->submit('register', 'btn btn-warning'),
		);

	create the form by calling the newForm method
	$input->newForm('post', '', 'col-md-8', 'register', $inputs);
	*/
 ?>