<section class="content">
            <div class="row">
               <div class="col-md-12">
                    <div class="">
                       <h1 class="title_page">Add New Admin/User</h1>
                       <div class="box box-primary">
                           <div class="box-body pad_t30 ">
                             <div class="col-md-7 col-xs-12 col-sm-12 height_360 ">
                                 <?php
									$attribute = array('name'=>'frmaddrsmusers','id'=>'frmaddrsmusers');
									echo form_open_multipart('',$attribute);
				                 ?>
                                  
                                  <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                   <div class="row">
                                    <div class=" col-md-5 col-xs-12 col-sm-12">
                                    <label class="" for="name">Password<span class="starspan">&nbsp;*</span></label></div>
                                    <div class=" col-md-7 col-xs-12 col-sm-12">
									<?php
									$data = array(
									'name'			=> 'password',
									'id'			=> 'password',
									'required'		=> "required",
									'placeholder'	=> 'password',
									'class'			=> "form-control",
									'type'          => "password"
									);
									echo form_input($data);
								  ?>
									
									</div>
									</div>
                                  </div>
                                  
                                  <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                   <div class="row">
                                    <div class=" col-md-5 col-xs-12 col-sm-12">
                                    <label class="" for="name">Confirm Password<span class="starspan">&nbsp;*</span></label></div>
                                    <div class=" col-md-7 col-xs-12 col-sm-12">
										<?php
										$data = array(
										'name'			=> 'confirmpassword',
										'id'			=> 'confirmpassword',
										'required'		=> "required",
										'placeholder'	=> 'Confirm-password',
										'class'			=> "form-control",
										'type'          => "password"
										);
										echo form_input($data);
										?>
									</div>  
									</div>
                                  </div>
                                   
                                  <div class="form-group col-md-12 col-xs-12 col-sm-12">
                                    <div class="row">
                                    <div class=" col-md-5 col-xs-12 col-sm-12"></div>
                                    <div class=" col-md-7 col-xs-12 col-sm-12">
                                    <div class=" pull-right">
                                    <input type="submit" class="btn btn-primary" value="Submit" name="Submit" />
                                    </div></div></div>
                                  </div>
                                 <div class="clearfix"></div>
                               <?php echo form_close(); ?>
                              </div> 
                              <div class="clearfix"> &nbsp;</div>
                           </div>
                       </div>
                    </div>
               </div>
            </div>
</section><!-- /.content -->




<script>
$(document).ready(function() {
    $('#frmaddrsmusers')
        .bootstrapValidator({
            framework: 'bootstrap',
            //excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
            },
            fields: {
            	first_name: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
				last_name: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
				
				user_type: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
				role: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
				
				contact_number: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
						stringLength: {
							max:12,
							min: 06,
                            message: 'The mobile number must be 06 to 12 digits '
                        },
						regexp: {							
							regexp: /^[0-9][0-9]{0,15}$/,
							message: 'Invalid mobile number'
						},
                
                    }
                },
			
				email: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
						regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'Please enter a valid email address'
                        },
						 remote: {
							url: '<?php echo base_url().'admin/users/check_useremail'; ?>',
							type: 'POST',
							message: 'This email id already exists',
						}, 
                    }
                },
				password: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
						identical: {
                            field: 'confirmpassword',
                            message: 'The password and its confirm must be the same'
                        },
                    }
                },
                confirmpassword: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                        identical: {
                            field: 'password',
                            message: 'The password and its confirm must be the same'
                        },
                    }
                },
            }
        })
        .on('error.validator.bv', function(e, data) {
            data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
        });
});
</script> 