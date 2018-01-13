<?php 
	require_once 'includes/content/Aheader.php';
  if(Input::exists('get')) {
    if(!empty(Input::get('ques_id'))) {
      $quesId = decode(Input::get('ques_id'));
      $quesTable = decode(Input::get('test_table'));
      $multiple = Input::get('multiple');
      $ques = new Question($quesTable);
      try {
        $question = $ques->getQ($quesId);  
      } catch (Exception $e) {
        die($e->getMessage());
      }
      //update question in database when form is submitted
      if(Input::exists()) {
        if(!empty(Input::get('editQues'))) {
          (is_array(Input::get("answer"))) ? $answer = implode(',', Input::get("answer")) : $answer = Input::get("answer");
          try {
            $ques->updateQ(array(
              'question' => trim(Input::get("question")) ,
              'A' => Input::get("option_A"),
              'B' => Input::get("option_B"),
              'C' => Input::get("option_C"),
              'D' => Input::get("option_D"),
              'E' => Input::get("option_E"),
              'answer' => $answer,
              ), $quesId);     
              Session::flash('home', "Question updated successfully");
              Redirect::to("viewquestions.php?test_id=".encode($question->test_id));
          } catch (Exception $e) {
            Session::flash('home', $e->getMessage());    
          }
        }
      }
    }
  }
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Question
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit question</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
           <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Edit question</h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form role="form" method="post" action="">
                <div class="form-group">
                  <label>Question <?php echo $quesId ?></label>
                  <textarea style="width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" class="textarea" placeholder="Enter question here" name="question"><?php echo $question->question ?></textarea>
                </div>

                <div class="options">
                  <div class="form-group ba">
                    <label for="option_A">Option A</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="option_A" name="option_A" value="<?php echo $question->A ?>">
                    </div>
                  </div>
                  <div class="form-group ba">
                    <label for="option_B">Option B</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="option_B" name="option_B" value="<?php echo $question->B ?>">
                    </div>
                  </div>
                  <div class="form-group ba">
                    <label for="option_C">Option C</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="option_C" name="option_C" value="<?php echo $question->C ?>">
                    </div>
                  </div>
                  <div class="form-group ba">
                    <label for="option_D">Option D</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="option_D" name="option_D" value="<?php echo $question->D ?>">
                    </div>
                  </div>
                  <div class="form-group ba">
                    <label for="option_E">Option E</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="option_E" name="option_E" value="<?php echo $question->E ?>">
                    </div>
                  </div>
                  
                </div>
                <div class="form-group">
                  <div class="radio">
                    <?php 
                      $type = 'radio';
                      $name = 'answer';
                      $required = 'required';
                      if($multiple == 1) {
                        $type = 'checkbox';
                        $name = 'answer[]';
                        $required = '';
                      }
                    ?>
                    <label>Select the answer: &nbsp;</label>
                    <label>
                      <input type="<?php echo $type?>" name="<?php echo $name ?>" value="A" <?php if(strstr($question->answer, 'A')) echo 'checked' ?> <?php echo $required ?>>A
                    </label>
                    <label>
                      <input type="<?php echo $type?>" name="<?php echo $name ?>" value="B" <?php if(strstr($question->answer, 'B')) echo 'checked' ?> <?php echo $required ?>>B
                    </label>
                    <label>
                      <input type="<?php echo $type?>" name="<?php echo $name ?>" value="C" <?php if(strstr($question->answer, 'C')) echo 'checked' ?> <?php echo $required ?>>C
                    </label>
                    <label>
                      <input type="<?php echo $type?>" name="<?php echo $name ?>" value="D" <?php if(strstr($question->answer, 'D')) echo 'checked' ?> <?php echo $required ?>>D
                    </label>
                    <label>
                      <input type="<?php echo $type?>" name="<?php echo $name ?>" value="E" <?php if(strstr($question->answer, 'E')) echo 'checked' ?> <?php echo $required ?>>E
                    </label>
                  </div>
                </div>
                <div class="box-footer">
                  <input class="btn btn-success" type="submit" name="editQues" value="Send">
                </div>
              </form>
            </div>
          </div> 
        </section>
        <!-- /.Left col -->
      </div>
      <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
	require_once 'includes/content/Afooter.php';
?>