



    <div class="container">
    	<?php	$attributes = array('name'=>'signup','id'=>'signup','class'=>'form-signin');
     
			echo form_open_multipart('',$attributes); ?>		
		<h2 class="form-signin-heading">Reset Password</h2>
        <div class="login-wrap">
      <?php $this->load->view('admin/layout/validation-errors');?>
        	<?php
				$data = array(
				'name'			=> 'password',
				'id'			=> 'password',
				'required'		=> "required",
				'placeholder'	=> 'New password',
				'class'			=> "form-control",
				'type'          => "password"
				);
				echo form_input($data);
			?>
			<?php
				$data = array(
					'name'			=> 'confirmpassword',
					'id'			=> 'confirmpassword',
					'required'		=> "required",
					'placeholder'	=> 'Confirm password',
					'class'			=> "form-control",
					'type'          => "password"
					);
					echo form_input($data);
				?>
            <!-- <input type="text" class="form-control" placeholder="User ID" autofocus>
            <input type="password" class="form-control" placeholder="Password"> -->
           <?php echo form_submit('login','Reset Password', 'class="btn btn-lg btn-login btn-block"'); ?>
            
          <?=form_close();?>   
        </div>
    </div>


    <script src="<?php echo WEB_PATH; ?>js/jquery.js"></script>
    <script src="<?php echo WEB_PATH; ?>js/bootstrap.bundle.min.js"></script>


  </body>
</html>


