<?php
    require_once 'includes/content/nheader.php';
    $testInfo = Test::exists();
    $testName = str_replace(' ', '_', $testInfo->test_name);    //the same way the table was created in the test class
    $applicantTable = $testName.'_applicants';
    $applicant = new Applicant(null, $applicantTable);
    $delimiter = ',/:';
    if($applicant->isLoggedIn()) {
        $applicant->logout();
    }
    $errors = array();
    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            
            $validate = new Validate();

            $validation = $validate->check($_POST, array(
                'username' => array('required' => true),
                'password' => array('required' => true),
            ));

            if($validation->passed()) {

                $login = $applicant->login(Input::get('username'), Input::get('password'));

                if($login) {
                   // Session::flash('home', 'start test');
                    Redirect::to("question.php?test_id=".encode($testInfo->test_id));
                } else {
                    foreach ($applicant->errors() as $error) {
                        $errors[] = $error;
                        //echo $error. '<br>';
                    }
                }
            } else {
                foreach($validation->errors() as $error) {
                    $errors[] = $error;
                    //echo $error. '<br>';
                }
            }
        } 
    }

    if($errors) {
        $err = implode('<br>', $errors);
        Session::flash('home', $err);
    }
?>
      <!-- Top content -->
        <div class="top-content backstretch">
        	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">

                            <h1>Valence solutions</h1>
                            <?php 
                                if(Session::exists('home')) {
                                    echo "<p style='background:red;' class='text-center'>". Session::flash('home'). "</p>";
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box form">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Login to start <?php echo $testInfo->test_name?></h3>
                            		<p>Enter your username and password to log on:</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-lock"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
			                    <form role="form" action="" method="post" class="login-form">
			                    	<div class="form-group">
			                    		<label class="sr-only" for="username">Username</label>
			                        	<input type="text" name="username" placeholder="Username...(case sensitive)" class="form-username form-control" id="username" autocomplete="off" autofocus="on">
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="password">Password</label>
			                        	<input type="password" name="password" placeholder="Password..." class="form-password form-control" id="password" autocomplete="off">
			                        </div>
                                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                    <input type="hidden" name="testId" value="<?php echo $testInfo->test_id ?>">
			                        <input style="width:100%;" type="submit" class="btn btn-success" name="signIn" value="Sign in">
			                    </form>
		                    </div>
                        </div>
                        <div class="col-sm-6 col-sm-offset-3 form-box instruction">
                            <div class="form-top">
                                <div class="form-top-left">
                                    <h3>Instructions</h3>
                                    <p class="text text-danger">Please read carefully</p>
                                </div>
                                <div class="form-top-right">
                                    <i class="fa fa-lock"></i>
                                </div>
                            </div>
                            <div class="form-bottom">
                                <ol>
                                    <li>The duration of this test is <?php echo $testInfo->test_duration; ?>.</li>
                                    <?php 
                                    $arr = explode($delimiter, $testInfo->instructions);
                                    array_pop($arr);
                                    foreach ($arr as $value) {?>
                                    <li><?php echo $value ?></li> 
                                <?php } ?>
                                </ol>
                                <button class="btn btn-success continue">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
    window.onload = function() {
        $('div.row div.form').slideUp('slow');
        $('div.instruction').on('click', 'button.continue', function(e) {
            $('div.instruction').slideUp('slow');
            $('div.row div.form').slideDown('slow');
        });
    };
</script>
 <?php
    require_once 'includes/content/nfooter.php';
?>