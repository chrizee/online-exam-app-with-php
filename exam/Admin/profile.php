<?php 
  require_once 'includes/content/Aheader.php'; 
  $errors = array();
    if(Input::exists() && !empty(Input::get('change_password'))) {
      $validate = new Validate();
      $validation = $validate->check($_POST, array(
        'current_password' => array(
          'required' => true,
        ),
        'password' => array(
          'required' => true,
          'min' => 6
        ),
        'password2' => array(
          'required' => true,
          'matches' => 'password',
          )
      ));
      
      if($validation->passed() ) {
        if($user->data()->password !== Hash::make(Input::get('current_password'), $user->data()->salt)) {
          $errors[] = "Your current password is wrong!";
        } else{
          $salt = Hash::salt(32);
          
          try {
            $user->update(array(
              'password' => Hash::make(Input::get('password'),$salt),
              'salt' => $salt,
            ), $user->data()->id);
            //Activity::add(8, $user->data()->id, $nextId);
            //sets message to be displayed after registration
            Session::flash('home', "password changed successfully");
            Redirect::to($_SERVER['PHP_SELF']);
          } catch (Exception $e) {
            die($e->getMessage());
          }
        }
      } else {
        foreach ($validation->errors() as $error) {
          $errors[] = $error;
        }
      }   
    }
    if(Input::exists() && !empty(Input::get('update'))) {
      $validate = new Validate();
      $validation = $validate->check($_POST, array(
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
        'phone' => array(
          'required' => true,
          'max' => 11,
          'min' => 11,
          'function' => 'checkPhone',
          'numeric' => 'integer'
        ),
        'email' => array(
          'required' => true,
          'function' => 'checkEmail',
        ),
      ));
      if(!empty($_FILES['photo'])) {
        $validation->checkPic('photo');
      }

      if($validation->passed() ) {
        $picname = (!empty($user->data()->pic_src)) ? $user->data()->pic_src : '' ;
        if(!empty($_FILES['photo'])) {
          if($name = $user->movePic('photo')) {
            $picname = $name;
          }
        } 
        
        try {
          $user->update(array(
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'address' => Input::get('address'),
            'name' => Input::get('name'),
            'pic_src' => $picname,
          ), $user->data()->id);
          //Activity::add(8, $user->data()->id, $nextId);
          //sets message to be displayed after registration
          Session::flash('home', "Profile updated successfully");
          Redirect::to($_SERVER['PHP_SELF']);
        } catch (Exception $e) {
          print_r($e->getMessage());
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
        View profile <i class="fa fa-user"></i><?php echo $salt = openssl_random_pseudo_bytes(12)."<br>";
        echo $er = base64_encode($salt)."<br>";
        echo base64_decode($er)."<br>"; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile</li>
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
              <h3 class="box-title">Staff profile </h3>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group has-feedback">
                    <label title="username cannot be changed">Username</label>
                    <input type="text" title="username cannot be changed" class="form-control" name="username" placeholder="Username" value="<?php echo $user->data()->username;?>" disabled>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Full name" value="<?php echo $user->data()->name;?>" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $user->data()->email;?>" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <label>Address</label>
                    <input type="address" class="form-control" name="address" placeholder="Address" value="<?php echo $user->data()->address;?>" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <label>Phone</label>
                    <input type="tel" class="form-control" name="phone" placeholder="08044444444" value="<?php echo $user->data()->phone;?>" required>
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <label for="inputPhoto">Photo</label>
                      <input type="file" class="form-control" id="inputPhoto" name="photo">
                  </div>
                  <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                      <input type="submit" name="update" class="btn btn-primary btn-block btn-flat" value="Update">
                    </div>
                    <!-- /.col -->
                  </div>
                </form>
              </div>
              <!-- /.box-body -->
            <div class="box-footer">
                <button type="button" name="password" class="pull-right btn btn-warning">Change Password</button>
              </div>
          </div>
          <!-- /.box -->

          
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-6 connectedSortable">
          <?php
            if(Session::exists('home')) {
          ?>
            <p class="login-box-msg text-danger"><?php echo Session::flash('home') ?></p>
          <?php 
            }
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
          <div class="box box-primary hidden" id="change_password">
            <div class="box-header with-border">
              <h3 class="box-title">Change password  </h3>
            </div>
            <!-- /.box-header -->
              <div class="box-body">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="current_password" placeholder="Current Password" autocomplete="off" autosave="off" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="New password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password2" placeholder="Retype new password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                      <input type="submit" name="change_password" class="btn btn-primary btn-block btn-flat" value="change">
                    </div>
                    <!-- /.col -->
                  </div>
                </form>
              </div>
              <!-- /.box-body -->
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
      $('div#change_password').addClass('hidden');
      $('button[name=password]').click(function() {
        $('div#change_password').removeClass('hidden');
      });
    });
  </script>
<?php
  require_once 'includes/content/Afooter.php';
?>
