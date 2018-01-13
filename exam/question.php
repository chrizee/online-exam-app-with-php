<?php
    require_once 'includes/content/nheader.php';
    if(!$testInfo = Test::get(decode(Input::get('test_id')))) {
    	Session::flash('home', "Test does not exist. Contact your provider for more info");
    	Redirect::to('index.php');
    }
    $testName = str_replace(' ', '_', $testInfo->test_name);    //the same way the table was created in the test class
    $applicantTable = $testName.'_applicants';
    $quesTable = $testName.'_questions';
    $applicant = new Applicant(null, $applicantTable);
    if(!$applicant->isLoggedIn()) {
    	Session::flash('home', 'You need to login to access this page');
    	Redirect::to('index.php');
    	//Redirect::to($_SERVER['HTTP_REFERER']);
    } else if($applicant->data()->status != 0) {
    	Session::flash('home', 'You have taken this test');
    	Redirect::to($_SERVER['HTTP_REFERER']);
    }else{
	    $ques = new Question($quesTable);
    	$showUser = array('name', 'email', 'phone', 'gender');
    	$showTest = array('test_name', 'test_date', 'test_time', 'test_duration');
	    
	    try {
	    	$questions = $ques->getQ();
	    	$preFill = $ques->preFill($testInfo->test_id, $applicant->data()->id);
	    	$ans = array_fill(1, count($questions), "see");
	    	if($preFill) {
	    		$preFill = $preFill[0];	//returning result here to suppress error when no result is found 
	    		if($preFill->answers) {
		    		if($preFill->answers[0] == ",") {
		    			$preFill->answers =  substr_replace($preFill->answers, '', 0, 1);
		    		}
		    		$arr = explode(',', $preFill->answers);	//separate each answers first
			    	foreach ($arr as $value) {
			    		$arr2 = explode(':', $value);	//separate answer and quesNo and form an array with quesNo as key
			    		$ans[$arr2[0]] = $arr2[1];
			    	}
			    }
		    }
	    } catch (Exception $e) {
	    	print_r($e->getMessage());
	    }
	    if(!$ques->noOfQues()) {
	    	die('Question are not ready');
	    }
	}
	if(Session::exists('home')) {
     	echo "<p class='text-danger text-center'>". Session::flash('home'). "</p>";
    }
?>

<div id="fh5co-blog">
		
		<div class="container">
			<div class="row">				
				<div class="col-md-9 animate-box" data-animate-effect="fadeIn">
					<div class="fh5co-entry" style="width:100%;">
						<div class="fh5co-copy">
							<form method="post" action="_processQ.php" id="test" name="ques">
								<legend><?php echo $testInfo->test_name ?></legend>
								<div class="ques">
								  	<ul id="customtab" class="nav nav-tabs">
								  		<?php foreach ($questions as $key => $value) {

								  		?>
								  		<li><a data-toggle="tab" href="<?php echo "#".$key ?>"><span><?php echo $key + 1?></span></a></li>
								  		<?php } ?>
								  	</ul>   
									<input type="hidden" name="test_id" value="<?php echo $testInfo->test_id?>" />
									<input type="hidden" name="forced" value="0" />
									<input type="hidden" name="time" value="" />
									<div class="tab-content">
										<?php 
											foreach ($questions as $key => $value) {
										?>
										<div class="form-group tab-pane fade" id="<?php echo $key ; ?>">
									        <label class="control-label col-md-12"><?php echo $key+1 .") ".$value->question." ?" ?></label>
									        
								            <div class="radio">
								              <label>
								                <span>A .</span> <input type="radio" name="<?php echo $value->id?>" value="A" <?php if(strstr($ans[$key+1], 'A')) echo 'checked'?> /><?php echo $value->A?> 
								              </label>
								            </div>
								            <div class="radio">
								              <label>
								                <span>B .</span> <input type="radio" name="<?php echo $value->id?>" value="B" <?php if(strstr($ans[$key+1], 'B')) echo 'checked'?>/><?php echo $value->B?> 
								              </label>
								            </div>
								            <div class="radio">
								              <label>
								                <span>C .</span> <input type="radio" name="<?php echo $value->id?>" value="C" <?php if(strstr($ans[$key+1], 'C')) echo 'checked'?>/><?php echo $value->C?> 
								              </label>
								            </div>
								            <div class="radio">
								              <label>
								                <span>D .</span> <input type="radio" name="<?php echo $value->id?>" value="D" <?php if(strstr($ans[$key+1], 'D')) echo 'checked'?>/><?php echo $value->D?> 
								              </label>
								            </div>
								            <?php if(isset($value->E)) { ?>
								            <div class="radio">
								              <label>
								                <span>E .</span> <input type="radio" name="<?php echo $value->id?>" value="E" <?php if(strstr($ans[$key+1], 'E')) echo 'checked'?>/><?php echo $value->E?> 
								              </label>
								            </div>
									            <?php } ?>
									    		<ul id="mynav" class="nav nav-tabs" style="border-bottom: none;">
									    			<?php if($key != 0) { ?>
											    	<li class="previous" style="border:2px solid #ddd;"><a data-toggle="tab" href="#<?php echo ($key-1) ?>">Previous</a></li>
											    	<?php } 
											    		if($key < (count($questions)-1)) {
											    	?>
											    	<li class="next" style="float:right; border: 2px solid #ddd;"><a data-toggle="tab" href="#<?php echo ($key+1) ?>">Next</a></li>
											    	<?php } ?>
											    </ul>	
										</div>
											    
											    <?php
												} 
											    ?>
									</div><!--end of tab-content -->  
								</div><!--end of ques-->
								<input class="btn btn-success" type="submit" name="Submit" value="Submit" />
							</form>	
						</div>
					</div>
				</div>

				<div class="col-md-3 animate-box" data-animate-effect="fadeIn">
					<div>
						<h3 class="text">Timer <i class="fa fa-clock-o text-danger"></i></h3>
						<p id="counter" class="text text-danger text-center" style="font-size:1.2em"></p>
					</div>
					<table class="table table-striped table-bordered table-hover">
						<caption>Test Info</caption>
						<tbody>
						<?php foreach($showTest as $value) { ?>
							<tr>
								<th><?php echo str_replace('_', ' ', $value) ?></th>
								<td><?php echo $testInfo->$value ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered table-hover">
						<caption>User's Info</caption>
						<tbody>
						<?php foreach($showUser as $value) { ?>
							<tr>
								<th><?php echo $value?></th>
								<td><?php echo $applicant->data()->$value ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
</div>
<script type="text/javascript">
	window.onload = function() {
		var $counter = <?php echo ($preFill) ?  $preFill->time_remaining : timeToStamp($testInfo->test_duration); ?>;
		function secondsToHms(d) {
		    d = Number(d);
		    var h = Math.floor(d / 3600);
		    var m = Math.floor(d % 3600 / 60);
		    var s = Math.floor(d % 3600 % 60);

		    var hDisplay = h > 0 ? h + (h == 1 ? " hour: " : " hours: ") : "";
		    var mDisplay = m >= 0 ? m + (m == 1 ? " min: " : " mins: ") : "";
		    var sDisplay = s >= 0 ? s + (s == 1 ? " sec" : " secs") : "";
		    return hDisplay + mDisplay + sDisplay; 
		}
		var $interval = setInterval(function() {
			$('ul.nav li.previous').add('ul.nav li.next').removeClass('active');
			if($counter <= 0) {
				$('input[name=forced]').val(1);
				$('input[type=radio]').removeAttr('required');
				$('form#test').submit();
				clearInterval($interval);
			}
			$counter--;
			$.post('_timestore.php', { time: $counter, test_id: ques.test_id.value }, function($re) {
				//$('p.test').text($re);
			});
			$('input[name=time]').val($counter);
			$('p#counter').text(secondsToHms($counter));	//process the timer to HH:MM:SS using javascript
			
		}, 1000);

		$("ul#mynav li a[href^='#']").click(function() {	//clicking of next or previous shud emulate clicking if the num tab
			var hash = this.hash;
			$("ul#customtab li a[href='" + hash + "']").click();
		});
		$('ul.nav li').filter(':first-child').addClass('active');	//make the first tab display after load
		$('div.form-group').filter(':first-child').addClass('in active');
		$('input[type=radio]').attr('required', 'required');
		$('input[name=forced]').val(0);

		$('input[type=radio]').click(function() {	//send answer to database immediately it is clicked
			$.post('_safestore.php', {name: this.name, value: this.value, test_id: ques.test_id.value , time: ques.time.value},  function(result) {
                //$('p.test').text(result);
			});
			var num = this.name;
			num--;	//change ques background to green when ques is answered
			$("ul#customtab li a[href='#" + num + "']").parents('ul#customtab li').css('backgroundColor', 'greenyellow');
		});
		$('ul#customtab li').css('backgroundColor', 'gray');
		//loop thru each answer initially and change background of answered ques to green
		var $ini;
		$('input[type=radio]').each(function() {
			if(this.checked) {
				var $ini = this.name;
				$ini--;
				$("ul#customtab li a[href='#" + $ini + "']").parents('ul#customtab li').css('backgroundColor', 'greenyellow').removeClass('active');
			}
		}); 
		$("input[type=submit]").click(function(e) {
			var passT = confirm("Do you really want to submit?");
			if(!passT) {e.preventDefault();}
		});		
	};
</script>
<?php
    require_once 'includes/content/nfooter.php';
?>