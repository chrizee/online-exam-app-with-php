<?php 
	require_once 'includes/content/Aheader.php';
  $testInfo = ""; 
  $errors = array();
  if(Input::exists()) {
    $via = array();
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'subject' => array(
        'required' => true,
        'min' => 10,
        'max' => 200
        ),
      'contact_method' => array(
        'required' => true,
        ),
      'message' => array(
        'required' => true,
        'min' => 10,
        'max' => 5000
        ),
      ));

    if($validation->passed()) {
      $operators = array('0', '=', '<', '>', '<=', '>=', '1');
      $table = Input::get('table');
      $filter = Input::get('filter');
      $operator = '';
      
      $method = Input::get('contact_method');
      if(Input::get('operator')) {
        $operator = $operators[Input::get('operator')];
      }
      if($operator == '1') {
        $operator = html_entity_decode(Input::get('otherOperator'));
      }
      $value = Input::get('value');
      $gender = Input::get('gender');
      if($table && $filter && ($value || $gender)) {
        $applicant = new Applicant(null, $table);
        if($gender) {
          $via = $applicant->get(array('gender', '=', $gender), $method.',score');
        } elseif ($value && $operator) {
            try {
              $via = $applicant->get(array('score', $operator, $value), $method.',score');
            } catch (Exception $e) {
              print_r($e->getMessage());
            }
        } else echo "enter a valid combination";
      } else echo "parameters too few to get result";
        } else {
      foreach ($validation->errors() as $error) {
        $errors[] = $error;
      }
    }
    if($via) {
      if(Input::get('contact_method') == 'email') {
        $message = Input::get('message');
        $subject = Input::get('subject');
        foreach ($via as $value) {
          $message .= "score: ";
          echo $message;
        }
      } else{
        $errors[] = "only email has been implemented yet";
      }
    }
  }
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Send broadcast
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Send broadcast</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-4 connectedSortable">
          <div class="callout callout-warning">
            <h4>Notice!</h4>
            only email notification has been enabled.
          </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter applicants</h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form role="form" method="post" name="test">
                <div class="form-group">
                  <label>Contact applicants of</label>
                  <select class="form-control" name="testInfo" required>
                    <option value="">--Select--</option>
                    <?php
                      $tests = Test::get(null,$user->data()->id,"test_id,test_name");
                      foreach ($tests as $value) {
                    ?>
                    <option value="<?php echo $value->test_name?>"><?php echo $value->test_name?></option>
                    <?php } ?>
                  </select>

                  <div class="form-group">
                    <label>Contact via</label>
                    <select class="form-control" name="contact_method" required>
                      <option value="">--Select--</option>
                      <option value="email" selected>Email</option>
                      <option value="phone">Phone (SMS)</option>
                    </select>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </section>
        <!-- /.Left col -->
        
        <!-- right col -->
        <section class="col-lg-8 connectedSortable">
          <div class="box box-danger hidden" id="message">
            <div class="box-header with-border">
              <h3 class="box-title">Message <span class="total"></span></h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form action="_broadcast.php" method="post">
                <div class="form-group">
                  <input type="text" class="form-control" name="emailto" placeholder="Email to:">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                </div>
                <div>
                  <textarea class="textarea" name="message" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required></textarea>
                </div>    
            </div>
            <div class="box-footer clearfix">
              <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
              <input type="submit" class="pull-right btn btn-default" id="sendBroadcast" value="Send">
            </div>
            </form>
          </div>
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
    $('select[name=testInfo]').click(function() {
      $('div#message').addClass('hidden');
      if($('select[name=testInfo]').val() != '') {
        $('div#message').removeClass('hidden');
        $('input[name=emailto]').val($('select[name=testInfo]').val() + " applicants");
      }
      $.post('_gettotal.php', {test: test.testInfo.value }, function(result) {
        $('span.total').text(result + ' applicant(s)');
      });
    });
  });
  </script>
<?php
	require_once 'includes/content/Afooter.php';
?>