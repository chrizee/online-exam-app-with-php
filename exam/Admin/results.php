<?php 
	require_once 'includes/content/Aheader.php';
  $testInfo = ""; 
  $errors = array();
  if(Input::exists()) {
    $via = array();
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'filter' => array(
        'required' => true,
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
        View Results
      </h1>
      <?php if(Session::exists('home')) {
        echo "<p class='text text-center'>".Session::flash('home')."</p>";
        }
      ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">View Results</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-8 connectedSortable">
          <?php
            if(Input::exists('get')) {
              if(!empty(decode(Input::get('test_id')))) {
                if(!$testInfo = Test::get(decode(Input::get('test_id')))) {
                  Session::flash('home', "Select a valid test");
                  Redirect::to('viewtest.php');
                } else {
                    if($testInfo->completed != 1) {
                        Session::flash('home', 'Test has not been completed');
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
                    }
                  }
              } else Redirect::to('viewtest.php');
             
          ?>
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Results of <?php echo $testInfo->test_name ?></h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Gender</th>
                  <th>Score</th>
                </tr>
                </thead>
                <tbody>
                  <?php foreach($applicantInfo as $key => $value) { ?>
                  <tr>
                    <td><?php echo $value->name ?></td>
                    <td><?php echo $value->email ?></td>
                    <td><?php echo $value->phone ?></td>
                    <td><?php echo $value->gender ?></td>
                    <td><?php echo $value->score ?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div> <?php } else {
            $testInfo2 = Test::get(null,$user->data()->id,"test_id,test_name",true);
            if(count($testInfo2) > 0) {
           ?>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Completed test </h3>
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <table class="table table-bordered table-striped dt-responsive nowrap">
                <thead>
                <tr>
                  <th>S/N</th>
                  <th>Name</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($testInfo2 as $key => $value) {
                  ?>
                  <tr>
                    <td><?php echo $key + 1 ?></td>
                    <td><?php echo $value->test_name?></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF']."?test_id=".encode($value->test_id)?>"><button class="btn btn-info">View <i class="fa fa-arrow-circle-right"></i></button></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
            <?php } else{
                echo "You have no result to display ";
              } 
            } ?>
        </section>
        <!-- /.Left col -->
        
        <!-- right col -->
        <section class="col-lg-4 connectedSortable">
        
          <?php
            if($testInfo) { ?>
              <div class="callout callout-warning">
                <h4>Notice!</h4>
                only email notification has been enabled.
              </div>
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Contact <?php echo $testInfo->test_name ?> Applicants </h3>
                  <div class="pull-right box-tools">
                    <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                      <i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <p class="result"></p>
                  <form action="" method="post" role="form" name="contact">
                    <?php
                      if($errors) {
                        foreach ($errors as $error) {
                          echo "<p>$error</p>";
                        }
                      }
                    ?>
                    <input type="hidden" name="table" value="<?php echo $applicantTable ?>">  
                    <div class="form-group">
                      <label>Filter By</label>
                      <select class="form-control" name="filter" required>
                        <option value="">--Select--</option>
                        <option value="score">Score</option>
                        <option value="gender">Gender</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>operator</label>
                      <select class="form-control" name="operator">
                        <option value="">--Select--</option>
                        <option value="1">equal</option>
                        <option value="2">less than</option>
                        <option value="3">greater than</option>
                        <option value="4">less than inclusive</option>
                        <option value="5">greater than inclusive</option>
                        <option value="6">others</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="other_operators">Enter Operator</label>
                      <div class="input-group">
                        <input type="text" name="other_operators" class="form-control" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="value">Value</label>
                      <div class="input-group">
                        <input type="text" name="value" class="form-control" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label>Gender</label>
                      <select class="form-control" name="gender">
                        <option value="">--Select--</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                      </select>
                    </div>

                    <div id="gettotal">
                      <button type="button" class="pull-right btn btn-primary" id="sendEmail">Get number
                      <i class="fa fa-arrow-circle-right"></i></button>
                    </div>

                    <div class="form-group">
                      <label>Contact via</label>
                      <select class="form-control" name="contact_method" required>
                        <option value="">--Select--</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone (SMS)</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control" name="subject" placeholder="Subject">
                    </div>

                    <div>
                      <textarea class="textare" name="message" min="10" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required></textarea>
                    </div>
                    
                </div>
                <div class="box-footer clearfix">
                  <div class="form">
                      <input type="submit" class="btn btn-primary pull-right" name="subject" value="send">
                    </div>
                  </form>
                </div>
              </div>
            <?php }
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
      $("div.form-group").eq(0).siblings('.form-group').hide();
      $('div#gettotal').hide();
      
      $('select[name=filter]').click(function() {
        $('div#gettotal').hide();
        $('p.result').text(" ");
        $("div.form-group").eq(0).siblings('.form-group').hide().removeAttr('required');
        if($('select[name=filter]').val() == 'score') {
          $('div.form-group').eq(1).show();
          $('select[name=operator]').attr('required', 'required');
          $('select[name=gender]').removeAttr('required')
        }
        if($('select[name=filter]').val() == 'gender') {
          $('div.form-group').eq(4).show();
          $('select[name=gender]').attr('required', 'required');
          $('select[name=operator]').removeAttr('required');
          $('select[name=gender]').click(function() {
            $('div.form-group').eq(5).show();
          });
        }  
      });
      
      $('select[name=operator]').click(function() {
        $('div.form-group').eq(2).hide().next().hide();
        if($('select[name=operator]').val() == '6') {
          $('div.form-group').eq(2).show();
          $('div.form-group').eq(5).show();
          $('div.form-group').eq(3).show();
          $('input[name=other_operators]').attr('required', 'required');
          $('input[name=value]').removeAttr('required')
        } else{
          $('div.form-group').eq(3).show();
          $('div.form-group').eq(5).show();
          $('input[name=value]').attr('required', 'required');
          $('input[name=contact_method]').attr('required', 'required');
        }
      });

      $('select[name=contact_method]').click(function() {
        $('div.form-group').eq(6).hide();
       
        if($('select[name=contact_method]').val() == 'email') {
          $('div.form-group').eq(6).show();
          $('input[name=subject]').attr('required', 'required');
        } else {
          $('input[name=subject]').removeAttr('required'); 
        }
      });

      $('input[name=value]').keyup(function() {
        $('div#gettotal').show();
      });

      $('select[name=gender]').click(function() {
        $('div#gettotal').show();
      });

      $('button#sendEmail').click(function() {
        $.post('_gettotal.php', { table: contact.table.value, filter: contact.filter.value, otherOperator: contact.other_operators.value, operator: contact.operator.value, value: contact.value.value, gender: contact.gender.value }, 
            function(result) {
                $('p.result').text(result);
            });
      });
    });

  </script>
<?php
	require_once 'includes/content/Afooter.php';
?>