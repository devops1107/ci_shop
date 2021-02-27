<title>Baaba.de - <?=$this->lang->line('forgot_your_password')?></title>


<div id="content" class="registration">
            
    <section class="inner-content">
        <div class="container">
            <!-- ****************** Login Section   ****************** -->
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="heading-row">
                        <h1><?=$this->lang->line('forgot_your_password')?></h1>
                        <p><?=$this->lang->line('if_you_forgot_password')?></p>
                    </div>
                    <?php $this->load->view('web/layout/validation-errors');

					$attributes = array('name'=>'forgotPassform','id'=>'forgotPassform');

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
                        <div class="form-group btn-area">
                            <button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('submit')?></button>
                            <a href="login" class="btn-link"><?=$this->lang->line('click_here_for_login')?></a> 
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

