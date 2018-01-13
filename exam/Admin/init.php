<?php 
	require_once 'includes/content/Aheader.php';
  if(!$user->hasPermission('admin')) {
    Session::flash('home', "You do not have permission to view that page");
    Redirect::to('dashboard.php');
  }
	$init = Info::get();
	
?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Front end settings 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">settings</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    	<div class="col-lg-6" style="padding-left:0;">
    		<div class="callout callout-info">
		        <p>These are the values in the footer area on the front-end of the website (visitors area).</p>
		    </div>	
    	</div>
    	 <div class="clearfix"></div>
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Edit/view</h3>
            </div>
            <!-- /.box-header -->
            <form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="init">
              <div class="box-body">
                <div class="form-group">
                  <label for="about">About</label>
                  <div class="input-group">
	                  <textarea style="min-width:500px; width: 100%; height: 90px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" class="textarea" name="about"><?php echo $init->about ?></textarea>
		          		</div>
                </div>
                <div>
                	<button class="btn btn-primary" id="updateAbout">Update About</button>
                </div>

                <h3>Services</h3>
                <ol class="services">
                <?php 
									$services = explode(',', $init->services);
									foreach ($services as $key => $value) {
									echo "<li style='text-transform: capitalize;'>$value</li>";	
									}
								?>
                </ol>
                <button class="btn btn-warning" id="addservices">Add more services</button>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
              	<button class="btn btn-primary" id="add">Add </button>
                <input type="submit" class="btn btn-primary pull-right" value="Refresh">
              </div>
            </form>
            <!-- /.box-body -->

          </div>
          <!-- /.box -->


          
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        <section class="col-lg-5 connectedSortable">
    			<p class="test text text-center text-info"></p>
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
  		$('button#add').hide();
  		$('input.pull-right').hide();
  		$('button#addservices').click(function($e) {
  			$e.preventDefault();
  			$('button#add').show();
  			$('div.box-body').append(
  							"<div>"+
	                "<div class='form-group'>"+
	                  "<label for='services'>Service</label>"+
	                  "<div class='input-group'>"+
		                  "<input type='text' class='form-control' name='services'>"+
			          		"</div>"+
	                "</div>"+
	              "</div>"
	              );
  		});

  		$('button#add').click(function($event) {
  			$event.preventDefault();
  			$.post('_addinit.php', {service: init.services.value }, function($result) {
  				$('p.test').text($result);
  				$('input.pull-right').show();
  			});
  		});

  		$('button#updateAbout').click(function(event) {
  			event.preventDefault();
  			$.post('_addinit.php', {about: init.about.value }, function($result) {
  				$('p.test').text($result);
  			});
  		});

  	})

  </script>
<?php
	require_once 'includes/content/Afooter.php';
?>