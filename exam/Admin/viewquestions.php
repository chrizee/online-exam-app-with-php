<?php 
	require_once 'includes/content/Aheader.php';

  if(Input::exists('get')) {
    if(!empty(decode(Input::get('test_id')))) {
      if($testInfo = Test::get(decode(Input::get('test_id')))) {  //gets info abt test to get the test_name hence table to query
        //print_r($testInfo);
        $testName = str_replace(' ', '_', $testInfo->test_name);
        $quesTable = strtolower($testName.'_questions');
        $ques = new Question($quesTable);
        try {
          $questions = $ques->getQ();  
        } catch (Exception $e) {
          die($e->getMessage());
        }
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
        View Questions
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View questions</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-4 connectedSortable">
          <?php   
            if($ques->noOfQues() != 0 && empty(Input::get('add_more_ques'))) {
              //use $questions to create view of questions with edit buttons
              //print_r($questions);
              ?>
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Questions for <?php echo $testInfo->test_name."(".$ques->noOfQues().")" ?></h3>
                  
                </div>
                <div class="box-body">
                  <form method="post" action="" role="form">
                    <div class="form-group">
                      <input class="btn btn-warning" type="submit" name="add_more_ques" value="Add more questions">
                    </div>
                  </form>
                <?php
                  foreach ($questions as $value) {
                ?>
                  <div class="well">
                    <p class="text"><?php echo $value->id.". ".$value->question?>?</p>
                    <ol type="A">
                    <li><?php echo $value->A ?></li>
                    <li><?php echo $value->B ?></li>
                    <li><?php echo $value->C ?></li>
                    <li><?php echo $value->D ?></li>
                    <li><?php echo $value->E ?></li>
                    </ol>
                    <p class="text">Answer: <?php echo $value->answer ?></p>
                    <a href="editquestion.php?test_table=<?php echo encode($quesTable) ?>&amp;ques_id=<?php echo encode($value->id) ?>&amp;multiple=<?php echo $testInfo->multiple_answer?>"><button  class="btn btn-primary">Edit</button></a> 
                  </div>
                <?php } ?>
                </div>
              </div>
            <?php } elseif(!empty(Input::get('add_more_ques'))) {  #if more questions are to be added
              ?>
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add more questions to <?php echo $testInfo->test_name ?> <i class="fa fa-plus"></i></h3>
                    </div>
                    <div class="box-body">
                      <p class="text">Select number of additional questions to add </p>
                      <form method="post" action="">
                        <div class="form-group">
                          <div class="input-group">
                            <input type="number" name="more_no_of_ques" min="1" max="100" class="form-control" value="" required>
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
                <p class="text text-center">No questions yet.</p>
                <div class="box-header with-border">
                  <h3 class="box-title">Add questions to <?php echo $testInfo->test_name ?> <i class="fa fa-plus"></i></h3>
                </div>
                <div class="box-body">
                  <p class="text">Select number of questions to add now</p>
                  <form method="post" action="">
                    <div class="form-group">
                      <div class="input-group">
                          <input type="number" name="new_no_of_ques" min="1" max="100" class="form-control" value="" required>
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
              if(!empty(Input::get('new_no_of_ques')) || !empty(Input::get('more_no_of_ques'))) {
                $quesNo = (!empty(Input::get('new_no_of_ques'))) ? Input::get('new_no_of_ques') : Input::get('more_no_of_ques');
                //print_r($_POST);
                $start =1;    //question number to start iterating from
                $end = $quesNo;
                if($ques->noOfQues() != 0) {  
                  $start = $ques->noOfQues();  // if question exist before start from where it stops
                  $end += $start;
                  ++$start;
                }
                $type = 'radio';
                $required = 'required';
                if($testInfo->multiple_answer == 1) {
                  $type = 'checkbox';
                  $required = '';
                }
                //generate form to add questions here
                ?> 
                <div class="box box-warning">
                  <div class="box-header with-border">
                    <h3 class="box-title">Add questions</h3>
                    <div class="pull-right box-tools">
                      <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                      <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                    </div>
                  </div>

                  <div class="box-body">
                    <form role="form" method="post" action="_addquestion.php">
                      <input type="hidden" name="start" value="<?php echo $start ?>">
                      <input type="hidden" name="end" value="<?php echo $end ?>">
                      <input type="hidden" name="test_id" value="<?php echo Input::get('test_id') ?>">
                    <?php for($i = $start; $i <= $end; $i++) { ?>
                      <div class="form-group">
                        <label>Question <?php echo $i ?></label>
                        <textarea style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" class="textarea" placeholder="Enter question here" name="<?php echo "question_".$i ?>" required></textarea>
                      </div>

                      <div class="options">
                        <div class="form-group ba">
                          <label for="<?php echo "option_".$i."_A"?>">Option A</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="<?php echo "option_".$i."_A"?>" name="<?php echo "option_".$i."_A"?>" required>
                          </div>
                        </div>
                        <div class="form-group ba">
                          <label for="<?php echo "option_".$i."_B"?>">Option B</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="<?php echo "option_".$i."_B"?>" name="<?php echo "option_".$i."_B"?>" required>
                          </div>
                        </div>
                        <div class="form-group ba">
                          <label for="<?php echo "option_".$i."_C"?>">Option C</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="<?php echo "option_".$i."_C"?>" name="<?php echo "option_".$i."_C"?>" required>
                          </div>
                        </div>
                        <div class="form-group ba">
                          <label for="<?php echo "option_".$i."_D"?>">Option D</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="<?php echo "option_".$i."_D"?>" name="<?php echo "option_".$i."_D"?>" required>
                          </div>
                        </div>
                        <div class="form-group ba">
                          <label for="<?php echo "option_".$i."_E"?>">Option E</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="<?php echo "option_".$i."_E"?>" value="none of the above" name="<?php echo "option_".$i."_E"?>" placeholder="None of the above">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="radio">
                          <?php ($type == 'radio') ? $name = "answer_".$i : $name = "answer_".$i."[]" ?>
                          <label>Select the answer<?php if($testInfo->multiple_answer == 1) echo "(s)"; ?>: &nbsp;</label>
                          <label>
                            <input type="<?php echo $type ?>" name="<?php echo $name?>" value="A" <?php echo $required ?> >A
                          </label>
                          <label>
                            <input type="<?php echo $type ?>" name="<?php echo $name?>" value="B" <?php echo $required ?>>B
                          </label>
                          <label>
                            <input type="<?php echo $type ?>" name="<?php echo $name?>" value="C" <?php echo $required ?>>C
                          </label>
                          <label>
                            <input type="<?php echo $type ?>" name="<?php echo $name?>" value="D" <?php echo $required ?>>D
                          </label>
                          <label>
                            <input type="<?php echo $type ?>" name="<?php echo $name?>" value="E" <?php echo $required ?>>E
                          </label>
                        </div>
                      </div>
                      <?php } ?>
                      <div class="box-footer">
                        <input class="btn btn-success" type="submit" name="addNewQues" value="Send">
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