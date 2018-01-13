<?php
	require_once 'includes/content/nheader.php';
		$delimiter = ',/:';
		$testInfo = Test::exists();
		$testName = str_replace(' ', '_', $testInfo->test_name);	//the same way the table was created in the test class
		$applicantTable = $testName.'_applicants';
		$errors = array();
		if(Input::exists()) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'login' => array(
					'required' => true,
					'min' => 2,
					'max' => 20,
					'unique' => $applicantTable
				),
				'password' => array(
					'required' => true,
					'min' => 6
				),
				'password2' => array(
					'required' => true,
					'matches' => 'password',
					),
				'name' => array(
					'required' => true,
					'min' => 2,
					'max' => 60
				),
				'phone' => array(
					'required' => true,
					'max' => 11,
					'min' => 11,
					'unique' => $applicantTable,
					'function' => 'checkPhone',
					'numeric' => 'integer'
				),
				'email' => array(
					'required' => true,
					'function' => 'checkEmail',
					'unique' => $applicantTable
				),
				'agree' => array(
					'required' => true,
				),
			));

			if($validation->passed()) {
				$applicant = new Applicant(null, $applicantTable);
					$salt = Hash::salt(32);
					$password = Hash::random_password();
					try {
						$applicant->create(array(
							'login' => Input::get('login'),
							'email' => Input::get('email'),
							'phone' => Input::get('phone'),
							'password' => Hash::make(Input::get('password'),$salt),
							'salt' => $salt,
							'name' => Input::get('name'),
							'gender' => Input::get('gender'),
							//'status' => 1,
						));
						//Activity::add(8, $user->data()->id, $nextId);
						//sets message to be displayed after registration
						Session::flash('home', "You have registered successfully for the test");
						//redirect after registration to faq.php
						Redirect::to('index.php');

					} catch (Exception $e) {
						die($e->getMessage());
					}
			} else {
				foreach ($validation->errors() as $error) {
					$errors[] = $error;
				}
			}		
		}	
?>
<div class="fh5co-page-title" style="background-image: url(img/4.jpg);">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12 animate-box">
				<h1><span class="colored">Register</span> Now</h1>
			</div>
		</div>
	</div>
</div>

<div id="best-deal">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center fh5co-heading animate-box" data-animate-effect="fadeIn">
				<h2>Apply</h2>
				<p>Read the eligibility and apply if you meet the requirements.</p>
			</div>
			<div class="col-md-5 item-block animate-box" data-animate-effect="fadeIn">
				<div class="fh5co-property">
					<figure>
						<img src="admin/img/<?php echo $testInfo->photo?>" alt="Free Website Templates FreeHTML5.co" class="img-responsive">
						<a href="#" class="tag">Read</a>
					</figure>
					<div class="fh5co-property-innter">
						<h3>Requirements/Eligibility</h3>
			          <ol>
			          <?php
		            		$arr = explode($delimiter, $testInfo->requirements);
		            		array_pop($arr);
		                	foreach ($arr as $value) {
		                ?>
		                    <li><?php echo $value ?></li>
		                <?php } ?>
			          </ol>
		          	</div>
				</div>
			</div>
			<div class="col-md-6 item-block animate-box" data-animate-effect="fadeIn">
				<div class="fh5co-property">
					<div class="fh5co-property-innter">
						<form class="form-horizontal" action="" method="post"  id="register_form">
							<fieldset>
								<legend>Register for <i class="text text-danger"><?php echo $testInfo->test_name?></i></legend>
								<?php
								if($errors) {
									foreach ($errors as $error) {
										echo "<p style='color: #a94442; text-transform: capitalize;'>$error</p>";
									}
								}
								?>
								<input type="hidden" name="action" value="4" />
								<div class="form-group">
								  <label for="username" class="col-md-3 control-label">Login<small>(username)</small></label>  
								  <div class="col-md-9 inputGroupContainer">
									  <div class="input-group">
									  	<span class="input-group-addon"><i class="fa fa-user"></i></span>
										  <input  name="login" placeholder="choose a username" class="form-control" id="username" type="text" autocomplete="off" value="<?php echo escape(Input::get('login'))?>" required>
									  </div>
								  </div>
								</div>

								<div class="form-group">
								  <label for="name" class="col-md-3 control-label">Name</label>  
								  <div class="col-md-9 inputGroupContainer">
									  <div class="input-group">
										  <span class="input-group-addon"><i class="fa fa-user"></i></span>
										  <input  name="name" placeholder="Name" class="form-control" id="name" type="text" autocomplete="off" value="<?php echo escape(Input::get('name'))?>" required>
									  </div>
								  </div>
								</div>

						 		<div class="form-group">
								  <label for="email" class="col-md-3 control-label">E-Mail</label>  
								  <div class="col-md-9 inputGroupContainer">
								    <div class="input-group">
								       <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								  		<input name="email" placeholder="E-Mail Address" id="email" class="form-control"  type="email" autocomplete="off" value="<?php echo escape(Input::get('email'))?>" required>
								    </div>
								  </div>
								</div>


								<div class="form-group">
				            <label class="control-label col-md-3">Gender</label>
				            <div class="col-md-9">
				                <div class="radio">
				                    <label>
				                        <input type="radio" name="gender" value="M" checked/> male
				                    </label>
				                </div>
				                <div class="radio">
				                    <label>
				                        <input type="radio" name="gender" value="F" /> female
				                    </label>
				                </div>
				        		</div>
				       		 </div>

								<div class="form-group">
								  <label for="phone" class="col-md-3 control-label">Phone #</label>  
								  <div class="col-md-9 inputGroupContainer">
								    <div class="input-group">
								    	<span class="input-group-addon"><i class="fa fa-phone-square"></i></span>
										  <input name="phone" placeholder="08080008009" id="phone" class="form-control" type="tel" autocomplete="off" value="<?php echo escape(Input::get('phone'))?>" required>
								    </div>
								  </div>
								</div>

								<div class="form-group">
								  <label  for="password" class="col-md-3 control-label">Password</label>  
								  <div class="col-md-9 inputGroupContainer">
								    <div class="input-group">
								    	<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										  <input name="password" placeholder="" id="password" class="form-control" type="password" autocomplete="off" value="">
								    </div>
								  </div>
								</div>

								<div class="form-group">
								  <label  for="password2" class="col-md-3 control-label">Re-enter password</label>  
								  <div class="col-md-9 inputGroupContainer">
								    <div class="input-group">
								    	<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										  <input name="password2" placeholder="" id="password2" class="form-control" type="password" autocomplete="off" value="">
								    </div>
								  </div>
								</div>

								<div class="form-group">
				                <div class="radio">
				                    <label>
				                        <input type="checkbox" name="agree" required/> <span> By clicking this box, you agree that the information provided above are correct and that you are eligible for the test. Any discrepancy will lead to dis-qualification without prior notice.</span>
				                    </label>
				                </div>
						       		 </div>
											<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label"></label>
								  <div class="col-md-4">
								    <input type="submit" class="btn btn-success" name="submit" value="Register">
								  </div>
								</div>
							</fieldset>
						</form>
	        </div>
      	</div>
      	<p class="fh5co-property-specification">
      		<span><strong>Application Closes:</strong> <?php echo $testInfo->application_end; ?></span>  
      	</p>
			</div>
		</div>
	</div>
</div>

<?php
	require_once 'includes/content/nfooter.php';
?>