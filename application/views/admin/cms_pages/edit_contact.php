<style>
#tbl_all_filter {
	display: none;
}
</style>
<div class="page-breadcrumb bg-white">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
            <h5 class="font-medium text-uppercase mb-0">Edit Contact</h5>
        </div>
        <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
            <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                <ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
                    <li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Contact</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="content mt-3" >
	<div class="animated fadeIn">
		<div class="">
		  <div class="col-lg-12">
			<?php $this->load->view('admin/layout/validation-errors'); ?>
			<div class="card">
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-7 col-xs-12 col-sm-12 height_360 ">
							  <?php echo validation_errors(); ?>
 								<?php echo form_open_multipart(); ?>
							 
							<div class="form-group has-feedback">
								<label>Mobile No: </label>
								<?php
									$data = array(
									'name'			=> 'mobile_no',
									'id'			=> 'mobile_no',
									//'required'		=> "required",
									'placeholder'	=> "Quetion",
									'value'         => $detail['mobile_no'],
									'class'			=>"form-control",
									);
									echo form_input($data);
								?>
							</div>

							<div class="form-group has-feedback">
								<label>Email: </label>
								<?php
									$data = array(
									'name'			=> 'email',
									'id'			=> 'email',
									//'required'		=> "required",
									'placeholder'	=> "Email",
									'value'         => $detail['email'],
									'class'			=>"form-control",
									);
									echo form_input($data);
								?>
							</div>

							<div class="form-group has-feedback">
								<label>Address: </label>
								<?php
									$data = array(
									'name'			=> 'address',
									'id'			=> 'address',
									//'required'		=> "required",
									'placeholder'	=> "Address",
									'value'         => $detail['address'],
									'class'			=>"form-control",
									'rows'		=> '3',
									);
									echo form_textarea($data);
								?>
							</div>

							<div class="form-group has-feedback">
								<label>Address (GR): </label>
								<?php
									$data = array(
									'name'			=> 'address_gr',
									'id'			=> 'address_gr',
									//'required'		=> "required",
									'placeholder'	=> "Address (GR)",
									'value'         => $detail['address_gr'],
									'class'			=>"form-control",
									'rows'		=> '3',
									);
									echo form_textarea($data);
								?>
							</div>

							<div class="form-group has-feedback">
								<label>Address (TR): </label>
								<?php
									$data = array(
									'name'			=> 'address_tr',
									'id'			=> 'address_tr',
									//'required'		=> "required",
									'placeholder'	=> "Address (TR)",
									'value'         => $detail['address_tr'],
									'class'			=>"form-control",
									'rows'		=> '3',
									);
									echo form_textarea($data);
								?>
							</div>

							<div class="form-group col-md-12 col-xs-12 col-sm-12">
								<div class="row">
								<div class=" col-md-5 col-xs-12 col-sm-12"></div>
								<div class=" col-md-7 col-xs-12 col-sm-12">
								<div class=" pull-right">
								<input type="submit" class="btn btn-success" value="Update" name="Submit" />&nbsp;&nbsp;&nbsp;
								<a href="<?php echo base_url('admin/faq')?>" class="btn btn-danger pull-right">Back</a>
								</div></div></div>
							  </div>
							 <div class="clearfix"></div>
						   <?php echo form_close(); ?>
						</div> 
						<div class="clearfix"> &nbsp;</div>
					</div>
				</div> 
				<div class="clearfix"> &nbsp;</div>
			</div>
		  </div>
		</div>
	</div>
</div>
<script src="<?php echo WEB_PATH; ?>js/vendor/jquery-2.1.4.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH;?>js/bootstrap_validator.js" type="text/javascript" language="javascript" ></script> 
<script src="<?php echo PLUGIN_PATH; ?>datepicker/bootstrap-datepicker.js"></script>

<script src="<?=PLUGIN_PATH?>ckeditor/ckeditor.js"></script>
<script src="<?=PLUGIN_PATH?>ckeditor/samples/js/sample.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
<script>
    	ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } ); 
</script><script>
$(document).ready(function() {
    $('#frmeditrsmusers')
        .bootstrapValidator({
            framework: 'bootstrap',
            //excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
            },
            fields: {
            	/* firstname: {
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
				role1: {
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
                }, */
				
				/* emailid: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required',
                        },
						regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'Please enter a valid email address'
                        },
						remote: {
							url: '<?php echo base_url().'admin/users/check_edit_useremail'; ?>',
							data: {
								user_id: <?php echo $detail['id']; ?>
							},
							type: 'POST',
							message: 'This email id already exists',
						},
                    }
                }, */
					/* password: {
                    validators: {
						identical: {
							field: 'confirmpassword',
							message: 'The password and its confirm must be the same',
							
						},
                
                    }
                },
                confirmpassword: {
                    validators: {
                        identical: {
                            field: 'password',
                            message: 'The password and its confirm must be the same'
                        },
                    }
                }, */
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
/* $(function () {
	$('#dob').datetimepicker({
		format: 'd-m-Y',
		autoclose: true
	});
}); */
</script> 

<script>
	/* CKEDITOR.plugins.addExternal( 'colorbutton', '/eezi_cabi/assets/admin/plugins/ckeditor/plugins/colorbutton/', 'plugin.js' );
	CKEDITOR.replace('editor', {
		extraPlugins: 'colorbutton'
    }); */
  </script>