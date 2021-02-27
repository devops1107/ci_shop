<?php //pr($detail,1); ?>
<style>
#tbl_all_filter {
	display: none;
}
</style>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
				<h1 class="title_page" style="clear:both;">Change Password</h1>
			</div>
		</div>
	</div>
</div>
<div class="content mt-3" >
	<div class="animated fadeIn">
		<div class="row">
		  <div class="col-lg-12">
			<?php $this->load->view('admin/layout/validation-errors'); ?>
			<div class="card">
				<div class="card-header">
					<strong class="card-title">Change Password</strong>
				</div>
				<div class="card-body">
					  <?php
						$refferer_url = 'admin/users-change-password/'.$this->uri->segment(3);
						$attribute = array('name'=>'changePassword','id'=>'changePassword');
						echo form_open($refferer_url,$attribute);
					  ?>            
					  <div class="form-group col-md-6 col-xs-12 col-sm-12">
					   <div class="row">
						<div class=" col-md-5 col-xs-12 col-sm-12">
						<label class="" for="name">New Password<span class="starspan">&nbsp;*</span></label></div>
						<div class=" col-md-7 col-xs-12 col-sm-12">
								 <?php
									$data = array(
												'name'			=> 'password',
												'id'			=> 'password',
												'required'		=>"required",
												'placeholder'	=> 'New Password',
												'class'			=> "form-control ",
												'type'          => "password"
												);
									echo form_input($data);
								?>
						
						
						</div>                                   </div>
					  </div>
					  <div class="clearfix"></div>
					  <div class="form-group col-md-6 col-xs-12 col-sm-12">
					   <div class="row">
						<div class=" col-md-5 col-xs-12 col-sm-12">
						<label class="" for="name">Confirm Password<span class="starspan">&nbsp;*</span></label></div>
						<div class=" col-md-7 col-xs-12 col-sm-12">
						 <?php
							$data = array(					
										'name'			=> 'confirmpassword',
										'id'			=> 'confirmpassword',
										'required'		=>"required",
										'placeholder'	=> 'Confirm Password',
										'class'			=> "form-control",
										'type'          => "password"
										);
							echo form_input($data);
						?>
						</div>
					   </div>
					  </div>
					  <div class="clearfix"></div>
					  <div class="form-group col-md-6 col-xs-12 col-sm-12">
						<div class="row">
						<div class=" col-md-5 col-xs-12 col-sm-12"></div>
						<div class=" col-md-7 col-xs-12 col-sm-12">
						<div class=" pull-right">
							<?php 
								$class = 'class="btn btn-primary"' ;
								echo form_submit('update','Change Password',$class);
								
							?>
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

<script src="<?php echo WEB_PATH; ?>js/vendor/jquery-2.1.4.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH;?>js/bootstrap_validator.js" type="text/javascript" language="javascript" ></script> 
<script>
$(document).ready(function() {
    $('#changePassword')
        .bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
             },
            fields: {
				
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