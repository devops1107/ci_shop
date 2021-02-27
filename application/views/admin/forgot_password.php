    <div class="sufee-login d-flex align-content-center flex-wrap" style="margin-top:8%">
        <div class="container">
            <div class="login-content">
                <div class="login-logo">
                    <a href="index.html">
                        <img class="align-content" src="images/logo.png" alt="Logo" title="Logo">
                    </a>
                </div>
                <div class="login-form">
					<h3><b>Forgot Password</b></h3><br>
                    <?php $this->load->view('admin/layout/validation-errors');
						$attributes = array('name'=>'signup','id'=>'signup');
						echo form_open('',$attributes);		
					?>
                        <div class="form-group has-feedback">
						<?php
							$data = array(
								  'name'        => 'email',
								  'id'          => 'email',
								  'type'        => 'email',
								  'class'   	=> 'form-control',
								  'placeholder' => 'Email',
								  'required' 	=> 'required',
							);
							echo form_input($data);
						?>
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span> 
						
						<a class="text-left" href="<?=base_url('admin')?>">Login</a>
						</div>
						<?php echo form_submit('login','Submit', 'class="btn btn-primary btn-flat"'); ?>
					<?=form_close();?>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
	<script>
	$(document).ready(function() {
		$('#email').focus();
	});
	</script> 
</body>
</html>