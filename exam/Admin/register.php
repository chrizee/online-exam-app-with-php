<?php
  require_once 'core/init.php';
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
          Session::flash('home', "You have registered successfully as an admin");
          //redirect after registration to faq.php
          Redirect::to('login.php');
        
        } catch (Exception $e) {
          die($e->getMessage());
        }
      } else {
        foreach ($validation->errors() as $error) {
          $errors[] = $error;
        }
      }   
    }
  if($errors) {
    $err = implode('<br>', $errors);
    Session::flash('home', $err);
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ValenceWeb | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="dashboard.php"><b>Valence</b>Exam</a>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Register a new membership</p>
    <p class="login-box-msg text-danger"><?php if(Session::exists('home')) echo Session::flash('home') ?></p>

    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="Username">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="name" placeholder="Full name">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password2" placeholder="Retype password">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="form-group">
        <div class="checkbox icheck">
          <label>
            <input type="radio" name="gender" value="M"> Male
          </label>
        </div>
        <div class="checkbox icheck">
          <label>
            <input type="radio" name="gender" value="F"> Female
          </label>
        </div>
      </div>

      <div class="form-group has-feedback">
        <input type="address" class="form-control" name="address" placeholder="Address">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="tel" class="form-control" name="phone" placeholder="08044444444">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="terms"> I agree to the <a href="#">terms</a>
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

    <a href="login.php" class="text-center">I already have a membership</a>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../scripts/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>