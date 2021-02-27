<title>Baaba.de - My Profile</title>

        
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
                            	<li class="active"><a href="<?=base_url('my-profile')?>"><?=$this->lang->line('my_profile')?></a></li>
                                <li ><a href="change-password" ><?=$this->lang->line('change_password')?></a></li>
                                <li><a href="<?=base_url('my-orders')?>"><?=$this->lang->line('my_orders')?></a></li>
                                <li><a href="<?=base_url('privacy-policy')?>"><?=$this->lang->line('privacy_policy')?></a></li>
                                <!-- <li><a href="<?=base_url('my-orders')?>">Update Email/Mobile</a></li> -->
                                <li><a href="<?=base_url('logout')?>"><?=$this->lang->line('logout')?></a></li>
                                
                            </ul>
                        </div>
                   
                    </div>
                </div> 
                <div class="col-md-9">
                    <!-- ****************** My Dashboard Section	****************** -->
                   
                    <div class="dashboard">
                    	<div class="form-content">
                            <div class="sub-heading">Personal Information</div>

                            <?php 
                            $this->load->view('web/layout/validation-errors');

                            $attributes = array('name'=>'profileform','id'=>'profileform');

                            echo form_open('',$attributes); 
                            ?>

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
                                          'placeholder' => '',
                                          'required'    => 'required',
                                          'value'           =>  ($user_details['first_name']!="")?$user_details['first_name']:set_value('first_name')
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
                                          'placeholder' => '',
                                          'required'    => 'required',
                                          'value'           =>  ($user_details['last_name']!="")?$user_details['last_name']:set_value('last_name')
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
                                          'placeholder' => '',
                                          'readonly' => 'readonly',
                                          'required'    => 'required',
                                          'value'           =>  ($user_details['emailid']!="")?$user_details['emailid']:set_value('emailid')
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
                                          'placeholder' => '',
                                          'required'    => 'required',
                                          'value'           =>  ($user_details['mobileno']!="")?$user_details['mobileno']:set_value('mobileno')
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
                                          'placeholder' => '',
                                          'required'    => 'required',
                                          'value'           =>  ($user_details['vat_number']!="")?$user_details['vat_number']:set_value('vat_number')
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
                                          'placeholder' => '',
                                          'required' => 'required',
                                          'value'           =>  ($user_details['commercial_reg_no']!="")?$user_details['commercial_reg_no']:set_value('commercial_reg_no')
                                        );

                                        echo form_input($data);
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group btn-area">
                                <button type="submit" class="btn btn-info btn-large"><?=$this->lang->line('update')?></button>
                            </div>

                            <?=form_close();?>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    
</div>
