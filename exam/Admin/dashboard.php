<?php 
	require_once 'includes/content/Aheader.php';
  $errors = array();
  if(Input::exists()) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'emailto' => array(
        'required' => true,
        'function' => 'checkEmail',
        ),
      'subject' => array(
        'required' => true,
        'max' => 100
        ),
      'message' => array(
        'required' => true,
        'min' => 10,
        'max' => 5000
        ),
      ));

    if($validation->passed()) {
      $address = Input::get('emailto');
      $subject = Input::get('subject');
      $message = Input::get('message');
      //use mailer to send mail here
      Session::flash('home', "Message sent ");
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
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <?php if(Session::exists('home')) echo "<p class='text text-center'>".Session::flash('home')."</p>" ;?>
    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          
          <!-- quick email widget -->
          <div class="box box-info box-solid">
            <div class="box-header">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick mail</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <?php 
                if($errors) {
                  foreach ($errors as $error) {
                    echo "<p class='text-danger'>$error</p>";
                  }
                }
              ?>
              <form action="" method="post">
                <div class="form-group">
                  <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject">
                </div>
                <div>
                  <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                </div>
              
            </div>
            <div class="box-footer clearfix">
              <input type="submit" class="pull-right btn btn-info" id="sendEmail" value="Send">
                
            </div>
            </form>
          </div>

           <div class="box box-primary box-solid">
            <div class="box-header">
              <i class="fa fa-laptop"></i>

              <h3 class="box-title">How to use application</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <div class="box-body">
              <div class="documentation">
                <h3>Creating test</h3>
                <p>To create a test click on the <strong>Test</strong> tab in the left navigation and enter the test details. Details required include name of test, test date, test time, duration, application period, whether answers can be multiple and how the test is to be written (online or local centre).</p>
                <p>Any error during the test creation can be corrected from the view test page.</p>
                
              </div>
              <div class="documentation">
                <h3>Editing test info</h3>
                <p>To </p>
              </div>
            </div>

        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">

          <!-- Calendar -->
          <div class="box box-solid box-success">
            <div class="box-header">
              <i class="fa fa-calendar"></i>

              <h3 class="box-title">Recent activities</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
            <p>goes here</p>
            </div>
            <!-- /.box-body -->
            
          </div>
          <!-- /.box -->

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