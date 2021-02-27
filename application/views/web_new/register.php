
<div id="content" class="registration">
        	
    <section class="inner-content">
    	<div class="container">
        	<!-- ****************** Login Section	****************** -->
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                	
                    

                        <?php 
						$this->load->view('web/layout/validation-errors');

						$attributes = array('name'=>'registerform','id'=>'registerform');

						echo form_open('',$attributes);	
						?>

                    
                    <div class="form-content">
					<div class="heading-row">
					<h1><?=$this->lang->line('create_an_account')?></h1>
                        <p><?=$this->lang->line('enter_info_to_create_account')?></p>
</div>
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                            		<label><?=$this->lang->line('first_name')?></label>

                            		<?php

									$data = array(
									  'name'        => 'first_name',
									  'id'          => 'first_name',
									  'type'        => 'text',
									  'class'       => 'form-control',
									  'value'		=>set_value('first_name'),
									  'placeholder' => '',
									  'required'    => 'required',
									);

									echo form_input($data);

									?>

                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                            		<label><?=$this->lang->line('last_name')?></label>

                            		<?php

									$data = array(
									  'name'        => 'last_name',
									  'id'          => 'last_name',
									  'type'        => 'text',
									  'class'       => 'form-control',
									  'value'		=>set_value('last_name'),
									  'placeholder' => '',
									  'required'    => 'required',
									);

									echo form_input($data);

									?>

                                </div>
                            </div>
                        </div>
                    	<div class="row">
                        	<div class="col-sm-6">
                            	<div class="form-group">
                                    <label><?=$this->lang->line('email')?>*</label> 

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
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label><?=$this->lang->line('phone_number')?>*</label> 

                            		<?php
									$data = array(
									  'name'        => 'mobileno',
									  'id'          => 'mobileno',
									  'type'        => 'number',
									  'class'       => 'form-control',
									  'value'		=>set_value('mobileno'),
									  'placeholder' => '',
									  'required'    => 'required',
									);

									echo form_input($data);
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label><?=$this->lang->line('vat_number')?>*</label> 

                            		<?php
									$data = array(
									  'name'        => 'vat_number',
									  'id'          => 'vat_number',
									  'type'        => 'number',
									  'class'       => 'form-control',
									  'value'		=>set_value('vat_number'),
									  'placeholder' => '',
									  'required'    => 'required',
									);

									echo form_input($data);
									?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label><?=$this->lang->line('commercial_register_number')?></label> 

                            		<?php
									$data = array(
									  'name' => 'commercial_reg_no',
									  'id' => 'commercial_reg_no',
									  'type' => 'number',
									  'class' => 'form-control',
									  'value' =>set_value('commercial_reg_no'),
									  'placeholder' => '',
									  'required' => 'required',
									);

									echo form_input($data);
									?>
                                </div>
                            </div>
                        </div>
                        <div class="sub-heading"><?=$this->lang->line('login_information')?></div>
                        <div class="row">
                        	<div class="col-sm-6">
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
                            </div>
                            <div class="col-sm-6">
                            	<div class="form-group">
                                    <label><?=$this->lang->line('confirm_password')?></label>
                                    <?php

									$data = array(
										  'name'        => 'confirm_password',
										  'id'          => 'confirm_password',
										  'type'        => 'password',
										  'class'       => 'form-control',
										  'placeholder' => '',
										  'required'    => 'required',
									);

									echo form_input($data);
									?>
                                </div>
                            </div>
                        </div>
                       <div class="row">
                        	<div class="col-sm-6">
                                 <div class="form-group">
                                    <label class="label_check" for="checkbox-02">
                                        <input name="sample-checkbox-02" id="checkbox-02" value="1" type="checkbox" checked=""><?=$this->lang->line('remember_me')?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group btn-area">
                        	<span class="req-fild">* <?=$this->lang->line('required_fields')?></span>
                        	<button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('register')?></button>
                            <a href="<?= base_url('login'); ?>" class="btn-link"><i class="fa fa-angle-left" aria-hidden="true"></i> <?=$this->lang->line('back')?></a> 
                        </div>
                    </div>   

                </div>
            </div>
    	</div>
    </section>
    
</div>                             
