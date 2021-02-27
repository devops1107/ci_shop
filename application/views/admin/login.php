        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url(<?=ADMIN_PATH?>images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box">
                <div id="loginform">
                    <div>
                        <span class="db"><center><img class="logo" src="<?=ADMIN_PATH?>images/alibaba_logo.png" alt="logo" /></center></span>
                        <center><h5 class="font-medium mb-3">Sign In to Admin</h5></center>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                        	<?php $this->load->view('admin/layout/validation-errors');
								$attributes = array('name'=>'loginform','class'=>'form-horizontal mt-3','id'=>'loginform');
								echo form_open('',$attributes);		
							?>
							<div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                </div>
	                            <?php
                                    $data = array(
                                          'name'        => 'email',
                                          'id'          => 'email',
                                          'type'        => 'email',
                                          'class'       => 'form-control form-control-lg',
                                          'placeholder' => 'Email',
                                          'aria-label' => 'Username',
                                          'aria-describedby' => 'basic-addon1',
                                          'required'    => 'required',
                                    );
                                    echo form_input($data);
                                ?>
	                        </div>
							<div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                </div>
	                            <?php
									$data = array(
										  'name'        	=> 'password',
										  'id'          	=> 'password',
										  'type'        	=> 'password',
										  'class'   	=> 'form-control form-control-lg',
										  'placeholder' => 'Email',
										  'aria-label' => 'Username',
										  'aria-describedby' => 'basic-addon1',
										  'autocomplete'	=> 'off',
										  'placeholder' 	=> 'Password',
										  'required' 		=> 'required',
									);
									echo form_input($data);
								?>
	                        </div>
	                        <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Remember me</label>
                                            <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i class="fa fa-lock mr-1"></i> Forgot pwd?</a>
                                        </div>
                                    </div>
                                </div>
                            <div class="form-group text-center">
                                <div class="col-xs-12 pb-3">
									<?php echo form_submit('login','Log In', 'class="btn btn-block btn-lg btn-info"'); ?>
								</div>
							</div>
							<?=form_close();?>
                        </div>
                    </div>
                </div>
                <div id="recoverform">
                    <div>
                        <span class="db"><center><img class="logo" src="<?=ADMIN_PATH?>images/logo.png" alt="logo" /></center></span>
                        <center>
                        <h5 class="font-medium mb-3">Recover Password</h5>
                        <span>Enter your Email and instructions will be sent to you!</span>
                        </center>
                    </div>
                    <div class="row mt-3">
                        <!-- Form -->
                        <?php $this->load->view('admin/layout/validation-errors');
                            $attributes = array('name'=>'forgotPassword','class'=>'col-12','id'=>'forgotPassword');
                            echo form_open(base_url('admin/forgot-password'),$attributes);     
                        ?>
                            <!-- email -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <?php
                                    $data = array(
                                          'name'        => 'email1',
                                          'id'          => 'email1',
                                          'type'        => 'email',
                                          'class'       => 'form-control form-control-lg',
                                          'placeholder' => 'Email',
                                          'aria-label' => 'Username',
                                          'aria-describedby' => 'basic-addon1',
                                          'required'    => 'required',
                                    );
                                    echo form_input($data);
                                ?>
                                </div>
                            </div>
                            <!-- pwd -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <?php echo form_submit('reset','Reset', 'class="btn btn-block btn-lg btn-danger"'); ?>
                                </div>
                                <div class="col-12" style="margin-top: 10px;">
                                    <button class="btn btn-block btn-lg btn-info" type="button" name="back" id="backToLogin">Back To Login</button>
                                </div>
                            </div>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="<?=ADMIN_PATH?>libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?=ADMIN_PATH?>libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?=ADMIN_PATH?>libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ============================================================== 
    // Login and Recover Password 
    // ============================================================== 
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $('#backToLogin').on("click", function() {
        $("#recoverform").slideUp();
        $("#loginform").fadeIn();
    });
    </script>
</body>

</html>