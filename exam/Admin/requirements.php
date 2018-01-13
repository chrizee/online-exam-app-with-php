<?php 
	require_once 'includes/content/Aheader.php';
	$delimiter = ',/:';
  if(Input::exists('get')) {
    if(!empty(decode(Input::get('test_id')))) {
      if($testInfo = Test::get(decode(Input::get('test_id')))) {  //gets info abt test to get the test_name hence table to query
        
      } else {
          Session::flash('home', "select a valid test");
          Redirect::to('viewtest.php');
        }
    } else {
        Session::flash('home', "select a valid test");
        Redirect::to('viewtest.php');
      }
  } else {
    Session::flash('home', "select a valid test");
    Redirect::to('viewtest.php');
  }
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Requirements
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">requirements</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-4 connectedSortable">
          <?php   
            if($testInfo->requirements && empty(Input::get('add_more_requirements'))) {
              //use $questions to create view of questions with edit buttons
              //print_r($questions);
              ?>
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Requirements for <?php echo $testInfo->test_name ?></h3>
                  
                </div>
                <div class="box-body">
                  <form method="post" action="" role="form">
                    <div class="form-group">
                      <input class="btn btn-warning" type="submit" name="add_more_requirements" value="Add more Requirements">
                    </div>
                  </form>
                  <div class="well">
                    <ol>
                <?php
                	$arr = explode($delimiter, $testInfo->requirements);
                	array_pop($arr);
                  foreach ($arr as $value) {
                ?>
                    <li><?php echo $value ?></li>
                    
                    <!--<a href="editquestion.php?test_table="><button  class="btn btn-primary">Edit</button></a> -->
                  
                <?php } ?>
                		</ol>
                	</div>
                </div>
              </div>
           		 <?php } elseif(!empty(Input::get('add_more_requirements'))) {  #if more questions are to be added
              ?>
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add more requirements to <?php echo $testInfo->test_name ?> <i class="fa fa-plus"></i></h3>
                    </div>
                    <div class="box-body">
                      <p class="text">Select number of additional requirements to add </p>
                      <form method="post" action="">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="number" name="more_no_of_requirements" min="1" max="100" class="form-control" value="" required>
                              <div class="input-group-addon">
                                <i class="fa fa-plus"></i>
                              </div>
                          </div>
                        </div>
                        <div class="box-footer">
                          <input type="submit" name="add_new" class="btn btn-primary" value="Add">
                        </div>
                      </form>
                    </div>
                  </div>
            <?php } else {  //if no ques has been added, give an option to add ques now
              ?>
              <div class="box box-primary">
                <p class="text text-center">No requirements yet.</p>
                <div class="box-header with-border">
                  <h3 class="box-title">Add requirements to <?php echo $testInfo->test_name ?> <i class="fa fa-plus"></i></h3>
                </div>
                <div class="box-body">
                  <p class="text">Select number of requirements to add now</p>
                  <form method="post" action="">
                    <div class="form-group">
                      <div class="input-group">
                          <input type="number" name="new_no_of_requirements" min="1" max="100" class="form-control" value="" required>
                          <div class="input-group-addon">
                            <i class="fa fa-plus"></i>
                          </div>
                      </div>
                    </div>
                    <div class="box-footer">
                      <input type="submit" name="add_new" class="btn btn-primary" value="Add">
                    </div>
                  </form>
                </div>
              </div>
            <?php }
          ?>
        </section>
        <!-- /.Left col -->
        
        <!-- right col -->
        <section class="col-lg-8 connectedSortable">
          <?php 
            if(Input::exists()) {
              if(!empty(Input::get('new_no_of_requirements')) || !empty(Input::get('more_no_of_requirements'))) {
                $requirementsNo = (!empty(Input::get('new_no_of_requirements'))) ? Input::get('new_no_of_requirements') : Input::get('more_no_of_requirements');
                //print_r($_POST);
                
                //generate form to add questions here
                ?> 
                <div class="box box-warning">
                  <div class="box-header with-border">
                    <h3 class="box-title">Add requirements</h3>
                    <div class="pull-right box-tools">
                      <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                      <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="box-body">
                    <form role="form" method="post" action="_addmeta.php">
                      <input type="hidden" name="test_id" value="<?php echo Input::get('test_id') ?>">
                      <input type="hidden" name="new" value="<?php echo (!empty(Input::get('new_no_of_requirements'))) ? 1 : 2 ?>">
                    <?php for($i = 1; $i <= $requirementsNo; $i++) { ?>
                      <div class="form-group">
                        <label>Requirement </label>
                        <textarea style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" class="textarea" placeholder="Enter requirements here" name="<?php echo "requirement_".$i ?>" required></textarea>
                      </div>

                      <?php } ?>
                      <div class="box-footer">
                        <input class="btn btn-success" type="submit" name="addNewRequirements" value="Send">
                      </div>
                    </form>
                  </div>
                </div> 
              <?php 
              }
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