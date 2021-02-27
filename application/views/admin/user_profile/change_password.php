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
						$attribute = array('name'=>'frmchange','id'=>'frmchange');
						echo form_open('admin/change-password',$attribute);
					?>
					<div class="box-body box_responsive">
					  
					  <div class="form-group col-md-3 col-xs-12 col-sm-12">
						<label for="exampleInputEmail1">Old Password<span class ="starspan">&nbsp;*</span></label>
						<?php
								$data = array(
											'name'			=> 'old_password',
											'id'			=> 'old_password',
											'required'		=>"required",
											'placeholder'	=> 'Old Password',
											'class'			=> "form-control padding_r0",
											'type'          => "password"
											);
								echo form_input($data);
							?>
					  </div>
					  <div class="form-group col-md-3 col-xs-12 col-sm-12">
						<label for="exampleInputEmail1">New Password<span class ="starspan">&nbsp;*</span></label>
						<?php
								$data = array(
											'name'			=> 'password',
											'id'			=> 'password',
											'required'		=>"required",
											'placeholder'	=> 'New Password',
											'class'			=> "form-control padding_r0",
											'type'          => "password"
											);
								echo form_input($data);
							?>
					  </div>
					  <div class="form-group col-md-3 col-xs-12 col-sm-12">
						<label for="exampleInputEmail1">Confirm Password<span class ="starspan">&nbsp;*</span></label>
						<?php
								$data = array(					
											'name'			=> 'confirmpassword',
											'id'			=> 'confirmpassword',
											'required'		=>"required",
											'placeholder'	=> 'Confirm Password',
											'class'			=> "form-control padding_r0",
											'type'          => "password"
											);
								echo form_input($data);
							?>
					  </div>
					  <div class="clearfix"></div>
					  <div class="col-md-12 col-xs-12 col-sm-12">
						<?php 
								$js = 'class="btn btn-success"' ;
								echo form_submit('update','Change Password',$js);
							?>
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
    $('#frmchange')
        .bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
             },
            fields: {
				
				old_password: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
						remote: {
							url: '<?php echo base_url().'admin/users/check_password'; ?>',
							type: 'POST',
							message: 'Wrong old password',
						},	
                    },
					
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