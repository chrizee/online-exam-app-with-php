<?php 
	require_once 'includes/content/Aheader.php';
	$success = false;
	$errors = array();
	if(Input::exists()) {
		$validate = new Validate();

		$validation = $validate->check($_POST, array(
			'test_name' => array(
				'required' => true,
				'min' => 2,
				'max' => 200,
				'unique' => 'tests'
				),
			'category' => array(
				'required' => true,
				'min' => 2,
				'max' => 100,
				),
			'test_date' => array(
				'required' => true,
				),
			'test_time' => array(
				'required' => true,
				),
			'test_duration' => array(
				'required' => true,
				),
			'application_period' => array(
				'required' => true,
				),
		));
		if(!empty($_FILES['photo'])) {
	    	$validation->checkPic('photo');
	    }
		if ($validation->passed()) {
			$picname = 'default.jpg' ;
	        if(!empty($_FILES['photo'])) {
	          if($name = $user->movePic('photo')) {
	            $picname = $name;
	          }
	        }
			try {
				$period =explode('- ', Input::get('application_period'));
	        	$start = cleanDate($period[0]);
	        	$end = cleanDate($period[1]);
				
				Test::create(array(
					'test_name' => Input::get('test_name'),
					'category' => Input::get('category'),
					'owner_id' => $user->data()->id,
					'application_start' => $start,
					'application_end' => $end,
					'test_date' => Input::get('test_date'),
					'test_time' => Input::get('test_time'),
					'test_duration' => Input::get('test_duration'),
					'multiple_answer' => Input::get('multiple_answer'),
					'online' => Input::get('location'),
					'photo' => $picname,
				),Input::get('test_name'));
				$success = true;
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
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Create new test <i class="fa fa-plus-square"></i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">create test</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    	<div class="callout callout-warning">
	        <h4>Notice!</h4>
	        Ensure to select the right type of answer as it cannot be changed after creating the test.<br />
	        Online test has not been implemented yet.
	    </div> 
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
           <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Test Info <small>(only multiple choice type test is available in this version)</small></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="testname">Test Name</label>
                  <div class="input-group">
	                  <input type="text" class="form-control" id="testname" name="test_name" value="<?php echo escape(Input::get('test_name'))?>" required>
	                  <div class="input-group-addon">
		               	<i class="fa fa-database"></i>
		               </div>
		          </div>
                </div>

                <div class="form-group">
	                <label>Test category</label>
	                <select class="form-control select2" name="category" style="width: 100%;" required>
	                  <option value="">--select--</option>
	                  <option value="scholarship">Scholarship</option>
	                  <option value="job">Job Interview</option>
	                  <option value="exam">Exam</option>
	                  <option value="promotion">Promotion</option>	                  
	                  <option value="test">Test</option>
	                  <option value="others">others</option>
	                </select>
	            </div>

                <div class="form-group">
	                <label>Test date</label>
	                <div class="input-group">
	                    <input type="date" name="test_date" class="form-control" value="<?php echo escape(Input::get('test_date'))?>" required>
	                    <div class="input-group-addon">
	                    	<i class="fa fa-calendar"></i>
	                    </div>
	                </div>
                </div>

                <div class="form-group">
	                <label>Test Time</label>
	                <div class="input-group">
	                    <input type="time" name="test_time" class="form-control" value="<?php echo escape(Input::get('test_time'))?>" required>
	                    <div class="input-group-addon">
	                    	<i class="fa fa-clock-o"></i>
	                    </div>
	                </div>
                </div>

                <div class="form-group">
	                <label title="period of time application will be open">Application period <small title="can be AM or PM">(MM/DD/YYYY HH:MM AM - MM/DD/YYYY HH:MM PM)</small></label>
	                <div class="input-group">
	                    <input type="text" name="application_period" class="form-control" value="<?php echo escape(Input::get('application_period'))?>" required>
	                    <div class="input-group-addon">
	                    	<i class="fa fa-calendar"></i>
	                    </div>
	                </div>
                </div>

                <div class="form-group">
	                <label>Test duration<span> (e.g 02:30)</span></label>
	                <div class="input-group">
	                    <input type="text" name="test_duration" class="form-control" value="<?php echo escape(Input::get('test_duration'))?>" data-inputmask="'alias': 'hh:mm'" data-mask required>
	                    <div class="input-group-addon">
	                    	<i class="fa fa-clock-o"></i>
	                    </div>
	                </div>
                </div>

                <div class="form-group">
	                <label>Test Image<span> (optional. displays for applicant to see)</span></label>
	                <div class="input-group">
	                    <input type="file" name="photo" class="form-control" >
	                    <div class="input-group-addon">
	                    	<i class="fa fa-clock-o"></i>
	                    </div>
	                </div>
                </div>

                <div class="form-group">
	                <label title="multiple options can be correct answer to particular question">Answer can be multiple &nbsp;</label>
	                <label>
	                	<input type="radio" name="multiple_answer" value="0" required> NO
	                </label>
	                <label>
	                	<input type="radio" name="multiple_answer" value="1" required> Yes
	                </label>
                </div>

                <div class="form-group">
	                <label title="">To be written? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	                <label>
	                	<input type="radio" name="location" value="0" required> In the centre
	                </label>
	                <label>
	                	<input type="radio" name="location" value="1" required> Online
	                </label>
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="submit" class="btn btn-primary" value="Create">
              </div>
            </form>
          </div>
          <!-- /.box -->

          
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
    	<?php 
    		if($success) {?>
    			<div class="box box-success">
		            <div class="box-header with-border">
		              <h3 class="box-title">Success <i class="text-success fa fa-check"></i></h3>
		            </div>
		        <?php 
    			echo "<P class='text-center'>Test created successfully</p>";
    			echo "<P class='text-center'>To add questions and adjust settings click <i class='fa fa-long-arrow-down'></i></p>";
    			echo "<p class='text-center'><a href='viewtest.php'><button style='margin-bottom:1em;' class='btn btn-success'>Settings <i class='fa fa-cog'></i></button></a></p></div>";
    		}
    		if ($errors) {
    			$err =str_replace('_', ' ', implode('<br>', $errors));
    	?>
    			<div class="box box-danger">
		            <div class="box-header with-border">
		              <h3 class="box-title">Error(s) <i class="text-danger fa fa-exclamation"></i></h3>
		            </div>
	        <?php
    			echo "<p class='text-center'>$err</p></div>";
    		}
    	?>
    		
        </section>
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
	require_once 'includes/content/Afooter.php';
?>