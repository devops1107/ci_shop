<title>Alibaba Nnuts Online<?=$this->lang->line('login')?></title>


<div id="content" class="registration">
            
    <section class="inner-content">
        <div class="container">
            <!-- ****************** Login Section   ****************** -->
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="heading-row">
                        <h1><?=$this->lang->line('already_registered')?></h1>
                        <p><?=$this->lang->line('if_have_an_account')?></p>
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

								  'name'        => 'emailid',

								  'id'          => 'emailid',

								  'type'        => 'email',

								  'class'       => 'form-control',

								  'value'		=>set_value('emailid'),

								  'placeholder' => '',

								  'required'    => 'required',

							);

							echo form_input($data);

							?>

                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('password')?></label>
                            <?php

							$data = array(

								  'name'        => 'password',

								  'id'          => 'password',

								  'type'        => 'password',

								  'class'       => 'form-control',

								  'value'		=>set_value('password'),

								  'placeholder' => '',

								  'required'    => 'required',

							);

							echo form_input($data);

							?>
                        </div>
                        <div class="form-group">
                            <label class="label_check" for="checkbox-01">
                                <input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" checked=""><?=$this->lang->line('remember_me')?>
                            </label>
                        </div>
                        <div class="form-group btn-area">
                            <button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('login')?></button>
                            <a href="<?= base_url('forgot-password'); ?>" class="btn-link"><?=$this->lang->line('forgot_your_password')?></a> 
                        </div>
                    </div>

                    <?=form_close();?>

                    <div class="heading-row">
                        <h1><?=$this->lang->line('new_here')?></h1>
                        <p><?=$this->lang->line('registration_is_free_and_easy')?></p>
                        <a href="<?= base_url('register'); ?>" class="btn btn-detail btn-large"><?=$this->lang->line('create_an_account')?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</div>

