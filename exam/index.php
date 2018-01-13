<?php
	require_once 'includes/content/nheader.php';
	$route = new Route();
	$route->add('/');
	$route->add('/about');
	$route->add('/contact');
	//echo '<pre>';
	//print_r($route);
	//$route->submit();
	$tests = array_reverse(Test::get(null, null,"*"));
	$errors = false;
	if(Session::exists('home')) {
        echo "<p style='background: #118DF0 ;color: #FFF; text-transform: capitalize; z-index: 999;' class='text text-center'>".Session::flash('home')."</p>";
		$errors = true;
  	}
  //$errors = Session::flash('home');
?>	
	<aside id="fh5co-hero" class="js-fullheight">
		<div class="flexslider js-fullheight">
			<ul class="slides">
		   	<li style="background-image: url(img/1.jpg);">
		   		
		   	</li>
		   	<li style="background-image: url(img/3.jpg);">
		   		
		   	</li>
			<li style="background-image: url(img/4.jpg);">
		   		
		   	</li>
		   	
		  	</ul>
	  	</div>
	</aside>
	<div id="best-deal">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center fh5co-heading animate-box" data-animate-effect="fadeIn" id="apply">
					<h2>Upcoming Exams</h2>
					<?php if($tests)  { ?>
					<p>Select the exam you want to apply for.</p>
					<?php } else { ?>
					<p>No Upcoming exam</p>
					<?php } ?>
				</div>
				<?php 
					foreach ($tests as $key => $value) { 
						if(strtotime($value->application_start) <= time() && time() <= strtotime($value->application_end) ) { ?>
							<div class="col-md-4 item-block animate-box" data-animate-effect="fadeIn">
								<div class="fh5co-property">
									<figure style="height:260px;">
										<img src="admin/img/<?php echo $value->photo?>" alt="application image" class="img-responsive">
										<a href="<?php echo "register.php?test_id=".encode($value->test_id) ?>" class="tag">Apply</a>
									</figure>
									<div class="fh5co-property-innter">
										<h3 class="head"><a href="<?php echo "register.php?test_id=".encode($value->test_id) ?>"><?php echo $value->test_name ?></a></h3>
										<div class="price-status">
					                 		<span class="price"> </span>
						               	</div>
						               <p>Description goes here.</p>
					            	</div>
					            	<p class="fh5co-property-specification">
					            		<span><strong>Deadline </strong> <?php $date = new dateTime($value->application_end); echo $date->format('d-M-Y H:i A') ?></span>  
					            	</p>
								</div>	
							</div>
		                <?php } elseif(time() > strtotime($value->application_end)) { ?>
	                    	<div class="col-md-4 item-block animate-box" data-animate-effect="fadeIn">
								<div class="fh5co-property">
									<figure style="height:260px;">
										<img src="admin/img/<?php echo $value->photo?>" alt="application image" class="img-responsive">
									</figure>
									<div class="fh5co-property-innter">
										<h3 class="head"><?php echo $value->test_name ?></h3>
										<div class="price-status">
					                 		<span class="price"> </span>
						               	</div>
						               <p>Description goes here.</p>
					            	</div>
					            	<p class="fh5co-property-specification">
					            		<span><strong>Closed On: </strong> <?php $date = new dateTime($value->application_end); echo $date->format('d-M-Y H:i A');  ?></span>  
					            	</p>
								</div>	
							</div>
		                <?php } else { ?>
                            <div class="col-md-4 item-block animate-box" data-animate-effect="fadeIn">
								<div class="fh5co-property">
									<figure style="height:260px;">
										<img src="admin/img/<?php echo $value->photo?>" alt="application image" class="img-responsive">
									</figure>
									<div class="fh5co-property-innter">
										<h3 class="head"><?php echo $value->test_name ?></h3>
										<div class="price-status">
					                 		<span class="price"> </span>
						               	</div>
						               <p>Description goes here.</p>
					            	</div>
					            	<p class="fh5co-property-specification">
				            			<span><strong>Starts: </strong> <?php $date = new dateTime($value->application_start); echo $date->format('d-M-Y H:i A');  ?></span>  
				            		</p>
								</div>	
							</div>
		                <?php   } } ?>
			</div>
		</div>
	</div>

	<div id="fh5co-testimonial">
		<div class="container" id="status">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center fh5co-heading animate-box" data-animate-effect="fadeIn">
					<h2>Ongiong Exam</h2>
					<p>Select the exam to Start</p>
				</div>
				<?php 
					$scheduled = false;
					foreach($tests as $test) {
						$dateTime = $test->test_date." ".$test->test_time;
						$duration = timeToStamp($test->test_duration);
						$interval = strtotime($dateTime) + $duration;	//using test duration as limit to remove start button
						if( strtotime($test->test_date) - mktime(0,0,0) <= 0  && time() < strtotime($dateTime) ) { $scheduled = true; ?>
							<div class="col-md-4 text-center item-block animate-box" data-animate-effect="fadeIn">
								<blockquote>
									<h3><?php echo $test->test_name; ?></h3>
									<p><?php echo $test->test_name; ?> starts by <?php echo $test->test_time;?> today.</p>
								</blockquote>
							</div>

						<?php } elseif(time() >= strtotime($dateTime) && $interval >= time()) { $scheduled = true; ?>
							<div class="col-md-4 text-center item-block animate-box" data-animate-effect="fadeIn">
								<blockquote class="start">
									<h3><?php echo $test->test_name; ?></h3>
									<p>Start <?php $test->test_name ?> Now. Portal will be open till <?php $d = getdate($interval); echo $d['mday']."/".$d['mon']."/".$d['year']." ". $d['hours'].":".$d['minutes']; ?> </p>
									<p>
										<a href="login.php?test_id=<?php echo encode($test->test_id) ?>"><button class='btn btn-danger'>start</button></a>
									</p>

								</blockquote>
							</div>
						<?php } else {
							continue;
						}
					} if(!$scheduled) {
				?>
						<div class="col-md-4 col-md-offset-4 text-center item-block animate-box" data-animate-effect="fadeIn">
							<blockquote>
								<h2>No Exam scheduled for today</h2>
							</blockquote>
						</div>
				<?php } ?>
			</div>
		</div>
	</div>	

	<div id="fh5co-blog">
		<div class="container" id="contact">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 text-center fh5co-heading animate-box" data-animate-effect="fadeIn">
					<h2>Have any <em>questions</em> </h2>
					<p>Send us a quick mail any we'll get back to you ASAP </p>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-3 col-md-offset-2 animate-box" data-animate-effect="fadeIn">
					<a class="fh5co-entry" href="#">
						<figure>
						<img src="img/contact.jpg" alt="contact image" class="img-responsive">
						</figure>
						<div class="fh5co-copy">
							<h3>Reach us on:</h3>
							<p><i class="fa fa-phone"></i> 08182642340 </p>
							<p><i class="fa fa-envelope"></i> valence@webmaster.com </p>
						</div>
					</a>
				</div>
				<div class="col-md-6 animate-box" data-animate-effect="fadeIn">
					<div class="fh5co-entry" style="width:100%;">
						<div class="fh5co-copy">
							<h3>Contact Us</h3>
							<form class="form-horizontal contact-form" action="_contact.php" method="post"  id="contact">
								<fieldset>
									<?php
									if($errors) {
											echo "<p style='color: #a94442; text-transform: capitalize;'>".Session::flash('home')."</p>";
									}
									?>
									<div class="form-group">
									  <label for="name" class="col-md-3 control-label">Name</label>  
									  <div class="col-md-9 inputGroupContainer">
										  <div class="input-group">
											  <span class="input-group-addon"><i class="fa fa-user"></i></span>
											  <input  name="name" placeholder="Name" class="form-control" id="name" type="text" autocomplete="off" required>
										  </div>
									  </div>
									</div>

									<div class="form-group">
									  <label for="email" class="col-md-3 control-label">Email</label>  
									  <div class="col-md-9 inputGroupContainer">
										  <div class="input-group">
										  	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											  <input  name="email" placeholder="Email" class="form-control" id="username" type="email" autocomplete="off" required>
										  </div>
									  </div>
									</div>

							 		<div class="form-group">
					                    <label for="email" class="col-md-3 control-label">Message</label>
					                    <div class="col-md-9 inputGroupContainer">
										  <div class="input-group">
										  	<span class="input-group-addon"><i class="fa fa-comment"></i></span>
											  <textarea style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" class="textarea form-control" placeholder="Your message " name="message" required></textarea>
										  </div>
									  </div>
                    
					                </div>
						       		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
												<!-- Button -->
									<div class="form-group">
									  <label class="col-md-4 control-label"></label>
									  <div class="col-md-4">
									    <input type="submit" class="btn btn-primary" name="submit" value="Send">
									  </div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
<?php
	require_once 'includes/content/nfooter.php';
?>