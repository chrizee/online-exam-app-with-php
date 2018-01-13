<?php 
  require_once 'includes/content/Aheader.php'; 
  if(!$user->hasPermission('admin')) {
    Session::flash('home', "You do not have permission to view that page");
    Redirect::to('dashboard.php');
  }
  $errors = array();
    if(Input::exists()) {
      $validate = new Validate();
      $validation = $validate->check($_POST, array(
        'username' => array(
          'required' => true,
          'min' => 2,
          'max' => 20,
          'unique' => 'users'
        ),
        'password' => array(
          'required' => true,
          'min' => 6
        ),
        'password2' => array(
          'required' => true,
          'matches' => 'password',
          ),
        'name' => array(
          'required' => true,
          'min' => 2,
          'max' => 60
        ),
        'address' => array(
          'required' => true,
          'min' => 3,
          'max' => 100
          ),
        'gender' => array(
          'required' => true,
          ),
        'phone' => array(
          'required' => true,
          'max' => 11,
          'min' => 11,
          'unique' => 'users',
          'function' => 'checkPhone',
          'numeric' => 'integer'
        ),
        'email' => array(
          'required' => true,
          'function' => 'checkEmail',
          'unique' => 'users'
        ),
      ));
      
      if($validation->passed() ) {
        if(Input::get('terms') !== 'on') {
          Session::flash('home', 'You need to agree with our terms and conditions before registering');
          Redirect::to($_SERVER['PHP_SELF']);
        }
        $user = new User();
        $salt = Hash::salt(32);
        
        try {
          $user->create(array(
            'username' => Input::get('username'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'address' => Input::get('address'),
            'password' => Hash::make(Input::get('password'),$salt),
            'salt' => $salt,
            'name' => Input::get('name'),
            'gender' => Input::get('gender'),
            //'status' => 1,
          ));
          //Activity::add(8, $user->data()->id, $nextId);
          //sets message to be displayed after registration
          Session::flash('home', "Admin registered successfully");
          //redirect after registration to faq.php
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
        Register new admin <i class="fa fa-plus-square"></i>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Register</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
       
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-6 connectedSortable">
           <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Register new staff </h3>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                  <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo escape(Input::get('username'))?>" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="name" placeholder="Full name" value="<?php echo escape(Input::get('name'))?>" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo escape(Input::get('email'))?>" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password2" placeholder="Retype password" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="form-group">
                    <div class="checkbox icheck">
                      <label>
                        <input type="radio" name="gender" value="M" required> Male
                      </label>
                    </div>
                    <div class="checkbox icheck">
                      <label>
                        <input type="radio" name="gender" value="F" required> Female
                      </label>
                    </div>
                  </div>

                  <div class="form-group has-feedback">
                    <input type="address" class="form-control" name="address" placeholder="Address" value="<?php echo escape(Input::get('address'))?>" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="tel" class="form-control" name="phone" placeholder="08044444444" value="<?php echo escape(Input::get('phone'))?>" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="row">
                    <div class="col-xs-8">
                      <div class="checkbox icheck">
                        <label>
                          <input type="checkbox" name="terms" required> I agree to the <a href="#">terms</a>
                        </label>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                      <input type="submit" name="register" class="btn btn-primary btn-block btn-flat" value="Register">
                    </div>
                    <!-- /.col -->
                  </div>
                </form>
              </div>
              <!-- /.box-body -->
            </form>
          </div>
          <!-- /.box -->

          
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
          <p class="login-box-msg text-danger"><?php if(Session::exists('home')) echo Session::flash('home') ?></p>
          <?php 
            
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
