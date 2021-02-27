<title>Baaba.de - Reset Password</title>
<?php $this->load->view('web/layout/header'); ?>

<div id="content" class="registration">
            
    <section class="inner-content">
        <div class="container">
            <!-- ****************** Login Section   ****************** -->
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="heading-row">
                        <h1>Reset Password</h1>
                        <p>If you have an account with us, please log in.</p>
                    </div>
                    <?php $this->load->view('web/layout/validation-errors');

                    $attributes = array('name'=>'loginform','id'=>'loginform');

					echo form_open('',$attributes);		

					?>
                    <div class="form-content">
                        <div class="form-group">
                            <label><?=$this->lang->line('email')?></label>
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

                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('password')?></label>
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
							</div>
                        <div class="form-group">
                            <label class="label_check" for="checkbox-01">
                                <input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" checked="">Remember Me What's this?
                            </label>
                        </div>
                        <div class="form-group btn-area">
                            <button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('login')?></button>
                            <a href="login" class="btn-link">Click here for Login?</a>
                        </div>
                    </div>

                    <?=form_close();?>

                    <div class="heading-row">
                        <h1>New Here?</h1>
                        <p>Registration is free and easy!</p>
                        <a href="register" class="btn btn-detail btn-large">Create an Account</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</div>

<?php $this->load->view('web/layout/footer'); ?>