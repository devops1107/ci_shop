<style>
.small, small {
	font-size: 75%;
	margin-left: 1px;
}
</style>
<!-- Main content -->
<section class="content-header">
  <h1> Add New User </h1>
</section>
<div class="row">
  <div class="col-md-12"> 
    <!-- general form elements -->
    
    <div class="clearfix"></div>
    <div class="box box-primary"> 
      <!-- form start -->
      <?php
			$attribute = array('name'=>'frmadduser','id'=>'frmadduser');
			echo form_open('',$attribute);
		?>
      <div class="box-body box_responsive">
        <div class="form-group col-md-3 col-xs-12 col-sm-12">
          <label for="exampleInputEmail1">Name<span class ="starspan">&nbsp;*</span></label>
          <?php
						$data = array(
									'name'			=> 'admin_name',
									'id'			=> 'admin_name',
									'required'		=>"required",
									'placeholder'	=> 'Name',
									'class'			=> "form-control padding_r0",
									
									);
						echo form_input($data);
					?>
        </div>
        <div class="form-group col-md-3 col-xs-12 col-sm-12">
          <label for="exampleInputEmail1">User Name<span class ="starspan">&nbsp;*</span></label>
          <?php
						$data = array(
									'name'			=> 'user_name',
									'id'			=> 'user_name',
									'required'		=>"required",
									'placeholder'	=> 'User Name',
									'class'			=> "form-control padding_r0",
									'autocomplete'	=>	"off",
									);
						echo form_input($data);
					?>
        </div>
        <div class="form-group col-md-3 col-xs-12 col-sm-12">
          <label for="exampleInputEmail1">Email<span class ="starspan">&nbsp;*</span></label>
          <?php
						$data = array(
									'name'			=> 'admin_email',
									'id'			=> 'admin_email',
									'required'		=> "required",
									'placeholder'	=> 'Email',
									'type'			=> 'email',
									'class'			=> "form-control padding_r0",
									);
						echo form_input($data);
					?>
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-3 col-xs-12 col-sm-12">
          <label for="exampleInputEmail1">Password<span class ="starspan">&nbsp;*</span></label>
          <?php
						$data = array(
									'name'			=> 'password',
									'id'			=> 'password',
									'required'		=>"required",
									'placeholder'	=> 'Password',
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
									'required'		=>	"required",
									'placeholder'	=> 'Confirm Password',
									'class'			=> "form-control padding_r0",
									'type'          => "password"
									);
						echo form_input($data);
					?>
        </div>
        <div class="clearfix"></div>
        <div class="form-group col-md-6 col-xs-12 col-sm-6">
          <?php
						$data = array(
									'name'			=> 'permission',
									'id'			=> 'permission',
									'type'          => "checkbox",
									'class'			=> "float_l",
									'value'			=>	"1"
									);
						echo form_input($data);
					?>
          <label for="exampleInputEmail1"  class="checkbox_text float_l" >Admin Access Permission</label>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 col-xs-12 col-sm-12">
          <?php 
					$js = 'class="btn btn-success"' ;
					echo form_submit('update','Add User',$js);
				?>
          <a href="<?php echo base_url().'users'?>" class = "btn btn-primary btn-cancel">Cancel</a> </div>
        <div class="clearfix"></div>
      </div>
      <?php echo form_close(); ?> </div>
    <!-- /.box --> 
    
  </div>
  <!-- /.col --> 
</div>
<!-- /.row --> 

<script>
$(document).ready(function() {
    $('#frmadduser')
        .bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
             },
            fields: {
            	admin_name: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
				user_name: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
						remote: {
							url: '<?php echo base_url().'users/check_username'; ?>',
							type: 'POST',
							message: 'This user name already exists',
						},
                    }
                },
				admin_email: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
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
