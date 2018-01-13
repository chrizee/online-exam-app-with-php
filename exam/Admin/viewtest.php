<?php 
	require_once 'includes/content/Aheader.php';
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Tests
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View test</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-4 connectedSortable">
          <?php
            $tests = array_reverse(Test::get(null,$user->data()->id,"test_id,test_name"));
            if(count($tests) > 0) {
          ?>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Test Info</h3>
            </div>
            <div class="box-body">
              <table id="datatable" class="table table-condensed dt-responsive nowrap">
                <thead>
                  <tr>
                    <th>S/N</th>
                    <th>NAME</th>
                    <th></th>       
                  </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($tests as $key => $value) {?>
                    <tr>
                      <td><?php echo $key + 1 ?></td>
                      <td><?php echo $value->test_name ?></td>
                      <td><a href="<?php echo $_SERVER['PHP_SELF']."?test_id=".encode($value->test_id).""?>"><button class="btn btn-success">View <i class="fa fa-arrow-circle-right"></i></button></a></td>
                    </tr>
                  <?php }?>
                 
                </tbody>
              </table>
            </div>
          </div>
          <?php } else {
            echo "No test to display"; 
            }
          ?>
        </section>
        <!-- /.Left col -->
        <!-- middle col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-4 connectedSortable">
          <?php 
          if(Input::exists('get')) {
            if(!empty(decode(Input::get('test_id')))) {
              if($testInfo = @Test::get(decode(Input::get('test_id')))) {
                if($testInfo->owner_id == $user->data()->id) {?>
                  <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title text text-center"><?php echo strtoupper($testInfo->test_name) ?></h3>
                    </div>
                    <div class="box-body">
                      <table id="table" class="table table-condensed dt-responsive nowrap">
                        <tbody>
                          <tr>
                            <th>CATEGORY <i class="fa fa-globe"></i></th>
                            <td><?php echo $testInfo->category ?></td>
                          </tr>
                          <tr>
                            <th>TEST DATE <i class="fa fa-calendar"></i></th>
                            <td><?php echo $testInfo->test_date ?></td>
                          </tr>
                          <tr>
                            <th>TEST TIME <i class="fa fa-clock-o"></i></th>
                            <td><?php echo $testInfo->test_time ?></td>
                          </tr>
                          <tr>
                            <th>TEST DURATION <i class="fa fa-clock-o"></i></th>
                            <td><?php echo $testInfo->test_duration ?></td>
                          </tr>
                          <tr>
                            <th>ANSWER TYPE <i class="fa fa-sort"></i></th>
                            <td><?php echo ($testInfo->multiple_answer == 0) ? 'Single' : 'Multiple'; ?></td>
                          </tr>
                          <tr>
                            <th>APPLICATION STATUS <i class="fa fa-calendar-check-o"></i></th>
                            <td><?php 
                              if(strtotime($testInfo->application_start) >= time()) {
                                echo "starts $testInfo->application_start";
                              } elseif(time() > strtotime($testInfo->application_end)) {
                                echo "closed on $testInfo->application_end";
                              } else {
                                echo "Ongoing";
                              } ?>
                            </td>
                          </tr>
                          <tr>
                            <th>STATUS <i class="fa fa-question-circle"></i></th>
                            <td><?php echo ($testInfo->completed == 0) ? 'Not completed' : 'Completed' ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="box-footer">
                      <?php if($testInfo->completed == 0) { ?>
                      <a href="<?php echo "applicant.php?test_id=".encode($testInfo->test_id)?>"><button class="btn btn-info">View Applicants <i class="fa fa-users"></i></button></a>
                      <a href="<?php echo $_SERVER['PHP_SELF']."?test_id=".encode($testInfo->test_id)."&setting=1"?>"><button class="pull-right btn btn-info">Settings <i class="fa fa-gear"></i></button></a>
                      <?php } else {?>
                        <a href="results.php?test_id=<?php echo encode($testInfo->test_id)?>"><button class="pull-right btn btn-danger">View results <i class="fa fa-book"></i></button></a>
                      <?php } ?>
                    </div>
                  </div>
                  <?php
                    if($testInfo->completed == 0) {
                  ?>
                  <div class="box box-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title text text-center"><?php echo strtoupper($testInfo->test_name) ?></h3>
                    </div>
                    <div class="box-body">
                      <table id="table" class="table table-hover table-striped dt-responsive nowrap">
                        <tbody>
                          <tr>
                            <th>Requirements/Eligibility of applicants<i class="fa fa-user"></i></th>
                            <td><a href="<?php echo "requirements.php?test_id=".encode($testInfo->test_id)?>"><button class="btn btn-warning">View / Add  <i class="fa fa-plus"></i></button></a></td>
                          </tr>
                          <tr>
                            <th>Test Instructions <i class="fa fa-book"></i></th>
                            <td><a href="<?php echo "instructions.php?test_id=".encode($testInfo->test_id)?>"><button class="btn btn-warning">View / Add  <i class="fa fa-plus"></i></button></a></td>
                          </tr>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <?php } ?>
                <?php } else {
                  echo 'You are not authorized to view this test';
                }
              } else {
                echo 'No such test';
              }
            } else {
              echo 'Select a valid test';
            }
          }
        ?>
        </section>
        <!-- middle col -->
        <!-- right col -->
        <section class="col-lg-4 connectedSortable settings">
          <?php
            if(Input::exists('get')) {
              if(!empty(decode(Input::get('test_id'))) && Input::get('setting') == 1) {
                if($testInfo = Test::get(decode(Input::get('test_id')))) {
                  if($testInfo->owner_id == $user->data()->id) { 
                    $category = $testInfo->category;
                    $errors = array();
                    if(Input::exists()) {
                      $validate = new Validate();

                      $validation = $validate->check($_POST, array(
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
                      ));
                      if(!empty($_FILES['photo'])) {
                        $validation->checkPic('photo');
                      }
                      if ($validation->passed()) {
                        $picname = $testInfo->photo;
                        if(!empty($_FILES['photo'])) {
                          if($name = $user->movePic('photo')) {
                            $picname = $name;
                          }
                        }

                        $status = $testInfo->completed;
                        if(!empty(Input::get('application_end_date')) && !empty(Input::get('application_end_time'))) {
                          $end = Input::get('application_end_date').' '.Input::get('application_end_time');
                        } else {
                          $end = $testInfo->application_end;
                        }
                        if(Input::get('status') === 'on') {
                          $status = 1;
                          Test::deleteTempAnswers($testInfo->test_id);
                        }
                        try {
                          Test::update($testInfo->test_id, array(
                            'category' => Input::get('category'),
                            'application_end' => $end,
                            'test_date' => Input::get('test_date'),
                            'test_time' => Input::get('test_time'),
                            'test_duration' => Input::get('test_duration'),
                            'completed' => $status,
                            'photo' => $picname,
                          ));
                          Session::flash('home', 'settings saved');
                          Redirect::to($_SERVER['PHP_SELF']."?test_id=".encode($testInfo->test_id)."");
                        } catch (Exception $e) {
                          print_r($e->getMessage());
                        }
                        
                      } else {
                        foreach ($validation->errors() as $error) {
                          $errors[] = $error;
                        }
                      }
                    } if($errors) {
                        $err = implode('<br>', $errors);
                        Session::flash('home', $err);
                      }
                    ?>
                    <div class="box box-danger">
                      <div class="box-header with-border">
                        <h3 class="box-title">Change settings for <?php echo $testInfo->test_name ?></h3>
                      </div>
                      <div class="box-body">
                        <?php if(Session::exists('home')) {
                          echo "<p class='text text-center'>".Session::flash('home')."</p>";
                          }
                        ?>
                        <form role="form" method="post" action="" enctype="multipart/form-data"> 
                          <button class="btn btn-info view" data-toggle="modal" data-target="#photo">View test pic</button>
                          <div class="form-group">
                            <label>Test category</label>
                            <select class="form-control select2" name="category" style="width: 100%;">
                              <option value="">--select--</option>
                              <option value="scholarship" <?php if($category == 'scholarship') echo 'selected';?>>Scholarship</option>
                              <option value="job" <?php if($category == 'job') echo 'selected';?>>Job Interview</option>
                              <option value="exam" <?php if($category == 'exam') echo 'selected';?>>Exam</option>
                              <option value="test" <?php if($category == 'test') echo 'selected';?>>Test</option>
                              <option value="promotion" <?php if($category == 'promotion') echo 'selected';?>>Promotion</option>
                              <option value="others" <?php if($category == 'others') echo 'selected';?>>others</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label>Test date</label>
                            <div class="input-group">
                                <input type="date" name="test_date" class="form-control" value="<?php echo escape($testInfo->test_date)?>">
                                <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label>Test Time</label>
                            <div class="input-group">
                                <input type="time" name="test_time" class="form-control" value="<?php echo escape($testInfo->test_time)?>">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label>Extend application deadline</label>
                            <div class="input-group">
                                <input type="date" name="application_end_date" class="form-control" value="">
                                <div class="input-group-addon">
                                  <i class="fa fa-calendar-check-o"></i>
                                </div>
                            </div>
                             <div class="input-group">
                                <input type="time" name="application_end_time" class="form-control" value="">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label>Test duration<span> (e.g 2:30)</span></label>
                            <div class="input-group">
                                <input type="text" name="test_duration" class="form-control" value="<?php echo escape($testInfo->test_duration)?>">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label> Change/set Image<span> (optional. displays for applicant to see)</span></label>
                            <div class="input-group">
                                <input type="file" name="photo" class="form-control" >
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="checkbox icheck">
                              <label>
                                <input type="checkbox" name="status"> Completed
                              </label>
                            </div>
                          </div>
                          <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Apply">
                          </div>
                        </form>
                      </div>
                      <!--modal-->
                      <div id="photo" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title">Photo for <?php echo $testInfo->test_name ?></h4>
                            </div>
                            <div class="modal-body">
                            <div class="img" style="margin:0px auto;">
                              <img style="margin:0px auto;" class="img-responsive" src="img/<?php echo $testInfo->photo?>" alt="test pic" />
                            </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      <div class="box-footer">
                        <a href="viewquestions.php?test_id=<?php echo encode($testInfo->test_id)?>"><button class="btn btn-warning">Add / View Questions <i class="fa fa-book"></i></button></a>
                      </div>
                    </div>
                  <?php } else {
                    echo "you are not allowed to view that test";
                  }
                } else {
                  echo 'no such test';
                }
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
<script type="text/javascript">
  $(document).ready(function() {
    $(document).one('mousemove', "section.settings", function() {
      if(!($('body').hasClass('sidebar-collapse'))) {
        $('a.sidebar-toggle').click();                    //close navigation in this page
      }
    }).on('click', 'button.view', function(e) {
      e.preventDefault();
    }).on('click', 'input[type=submit]', function(e) {
      if($('input[name=application_end_date]').val() != '' && $('input[name=application_end_time]').val() == '') {
        e.preventDefault();
        $('input[name=application_end_time]').css('border', '1px solid red');
      }
    })
  })
</script>
<?php
	require_once 'includes/content/Afooter.php';
?>