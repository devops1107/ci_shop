<title>Baaba.de - Change Password</title>

<div id="content">
            
    <section class="inner-content">
        <div class="container">
            <div class="heading"><span><?=$this->lang->line('my_dashboard')?></span></div>
            <div class="row">
                <div class="col-md-3">
                    <div class="sidebar">
                        <div class="quick-links">
                            <h4 class="title"><?=$this->lang->line('settings')?></h4>
                            <ul class="list-unstyled">
                                <li><a href="<?=base_url('my-profile')?>"><?=$this->lang->line('my_profile')?></a></li>
                                <li class="active"><a href="change-password" ><?=$this->lang->line('change_password')?></a></li>
                                <li><a href="<?=base_url('my-orders')?>"><?=$this->lang->line('my_orders')?></a></li>
                                <li><a href="<?=base_url('privacy-policy')?>"><?=$this->lang->line('privacy_policy')?></a></li>
                                <!-- <li><a href="<?=base_url('my-orders')?>">Update Email/Mobile</a></li> -->
                                <li><a href="<?=base_url('logout')?>"><?=$this->lang->line('logout')?></a></li>
                                
                            </ul>
                        </div>
                   
                    </div>
                </div> 
                <div class="col-md-9">
                    <!-- ****************** My Dashboard Section    ****************** -->
                    <div class="dashboard">
                        <?php $this->load->view('web/layout/validation-errors');

                        $attributes = array('name'=>'loginform','id'=>'loginform');

                        echo form_open('',$attributes);     

                        ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label><?=$this->lang->line('old_password')?></label>
                                    <?php

                                    $data = array(
                                    'name'          => 'old_password',
                                    'id'            => 'old_password',
                                    'required'      => "required",
                                    'placeholder'   => '',
                                    'class'         => "form-control",
                                    'type'          => "password"
                                    );
                                    echo form_input($data);

                                    ?>
                                </div>
                                <div class="form-group">
                                    <label><?=$this->lang->line('new_password')?></label>
                                    <?php

                                    $data = array(
                                    'name'          => 'password',
                                    'id'            => 'password',
                                    'required'      => "required",
                                    'placeholder'   => '',
                                    'class'         => "form-control",
                                    'type'          => "password"
                                    );
                                    echo form_input($data);

                                    ?>
                                </div>
                                <div class="form-group">
                                    <label><?=$this->lang->line('confirm_password')?></label>
                                    <?php

                                    $data = array(
                                        'name'          => 'confirm_password',
                                        'id'            => 'confirm_password',
                                        'required'      => "required",
                                        'placeholder'   => '',
                                        'class'         => "form-control",
                                        'type'          => "password"
                                        );
                                        echo form_input($data);
                                    ?>
                                </div>

                            <div class="form-group btn-area">
                                <button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('login')?></button>
                            </div>

                            <?=form_close();?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    
</div>

<div id="content" class="registration">
            
    <section class="inner-content">
        <div class="container">
            <!-- ****************** Login Section   ****************** -->
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="heading-row">
                        <h1>Change Password</h1>
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
