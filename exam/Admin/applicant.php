<?php 
	require_once 'includes/content/Aheader.php';
   $testInfo = '';
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Applicants
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Applicants</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-10 connectedSortable">
          <?php
            if(Input::exists('get')) {
              if(!empty(decode(Input::get('test_id')))) {
                if(!$testInfo = Test::get(decode(Input::get('test_id')))) {
                  Session::flash('home', "Select a valid test");
                  Redirect::to('viewtest.php');
                } else {
                    if($testInfo->completed != 0) {
                        Session::flash('home', 'Test has been completed');
                        Redirect::to('viewtest.php');
                    }else {
                      $testName = str_replace(' ', '_', $testInfo->test_name);
                      $applicantTable = $testName.'_applicants';
                      $applicant = new Applicant(null, $applicantTable);
                      try {
                        $applicantInfo = $applicant->get(array('1', '=', '1'));  
                      } catch (Exception $e) {
                        print_r($e->getMessage());
                      }
                      $errors = array();
                      //process editing of applicant info 
                      if(Input::exists()) {
                        $validate = new Validate();
                        $validation = $validate->check($_POST, array(
                          'name' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 60,
                          ),
                          'phone' => array(
                            'required' => true,
                            'max' => 11,
                            'min' => 11,
                            'function' => 'checkPhone',
                            'numeric' => 'integer',
                          ),
                          'email' => array(
                            'required' => true,
                            'function' => 'checkEmail',
                          ),
                        ));

                        if($validation->passed()) {
                            try {
                              if(Input::get('new_login') == '1') {
                                //generate new login details and send mail to applicant
                              }
                              $applicant->update(array(
                                'email' => Input::get('email'),
                                'phone' => Input::get('phone'),
                                'name' => Input::get('name'),
                              ), Input::get('applicant_id'));
                              //Activity::add(8, $user->data()->id, $nextId);
                              //sets message to be displayed after registration
                              Session::flash('home', "Changes Saved");
                              Redirect::to("applicant.php?test_id=".encode($testInfo->test_id));

                            } catch (Exception $e) {
                              die($e->getMessage());
                            }
                        } else {
                          foreach ($validation->errors() as $error) {
                            $errors[] = $error;
                          }
                        }   
                      }
                    }
                  }
              } else Redirect::to('viewtest.php');
             
          ?>
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Applicants of <?php echo $testInfo->test_name ?> <i class="fa fa-users"></i></h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <?php
                if($errors) {
                  foreach ($errors as $error) {
                    echo "<p>$error</p>";
                  }
                }
              ?>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Gender</th>
                  <th>Login</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php foreach($applicantInfo as $key => $value) { ?>
                  <tr>
                    <td><?php echo $value->name ?></td>
                    <td><?php echo $value->email ?></td>
                    <td><?php echo $value->phone ?></td>
                    <td><?php echo $value->gender ?></td>
                    <td><?php echo $value->login ?></td>
                    <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="<?php echo "#edit".$key?>">Edit</button></td>
                  </tr>

                  <div id="<?php echo "edit".$key?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Edit Info</h4>
                        </div>
                        <div class="modal-body">
                          <form action="" method="post" role="form" id="<?php echo "edit".$key?>">
                            <input type="hidden" name="applicant_id" value="<?php echo $value->id ?>">
                            <div class="form-group">
                              <label for="name">Name</label>
                              <div class="input-group">
                                <input type="text" name="name" class="form-control" value="<?php echo escape($value->name)?>" required>
                                <div class="input-group-addon">
                                  <i class="fa fa-user"></i>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="email">Email</label>
                              <div class="input-group">
                                <input type="email" name="email" class="form-control" value="<?php echo escape($value->email)?>" required>
                                <div class="input-group-addon">
                                  <i class="fa fa-envelope"></i>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="phone">Phone</label>
                              <div class="input-group">
                                <input type="tel" name="phone" class="form-control" value="<?php echo escape($value->phone)?>" required>
                                <div class="input-group-addon">
                                  <i class="fa fa-phone"></i>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label title="generate new username and password and notify user">Generate new login details</label>
                              <label>
                                <input type="radio" name="new_login" value="0"> NO
                              </label>
                              <label>
                                <input type="radio" name="new_login" value="1"> Yes
                              </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                          <input type="submit" class="btn btn-primary" value="Save Changes">
                        </div>
                          </form>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div> <?php } else {
            $testInfo2 = Test::get(null,null,"test_id,test_name",true);
           ?>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Invalid Entry</h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <p>Select a valid test</p>
            </div>
          </div>
            <?php } ?>
        </section>
        <!-- /.Left col -->
        
        <!-- right col -->
        <section class="col-lg-2 connectedSortable">

          
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