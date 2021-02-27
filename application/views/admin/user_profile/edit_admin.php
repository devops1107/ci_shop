<?php 
	if($this->site_santry->get_auth_data('user_permission') != '1'){
		$this->session->set_flashdata('flashError','This User Has Been Restricted To Access');
		redirect('users');
	}
?>
<style>
.small, small {
	font-size: 75%;
	margin-left: 1px;
}
</style>
<!-- Main content -->
<div class="row">
  <div class="col-md-12">
    <section class="content-header">
      <h1> Edit User </h1>
    </section>
    <div class="clearfix"></div>
    <!-- general form elements -->
    
    <section class="content">
      <div class="box box-primary "> 
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
									'value'			=> $detail->admin_name
									);
						echo form_input($data);
					?>
          </div>
          <div class="form-group col-md-3 col-xs-12 col-sm-12">
            <label for="exampleInputEmail1">User Name</label>
            <?php
						$data = array(
									'name'			=> 'user_name',
									'id'			=> 'user_name',
									'required'		=>"required",
									'placeholder'	=> 'User Name',
									'class'			=> "form-control padding_r0",
									'value'			=> $detail->admin_username,
									'disabled'		=> "disabled"
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
									'required'		=>"required",
									'placeholder'	=> 'Email',
									'type'			=> 'email',
									'class'			=> "form-control padding_r0",
									'value'			=> $detail->admin_email
									);
						echo form_input($data);
					?>
          </div>
          <div class="clearfix"></div>
          <div class="form-group col-md-6 col-xs-12 col-sm-6">
            <input class="float_l" type="checkbox" name="permission" value="1" <?php if($detail->admin_permission==1)
						echo 'checked="checked"'?>  />
            <label class="checkbox_text float_l" for="exampleInputEmail1">Admin Access Permission</label>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-6 col-xs-12 col-sm-6">
            <?php
						$data = array(
									'name'			=> 'admin_id',
									'id'			=> 'admin_id',
									'type'			=> 'hidden',
									'value'			=> $detail->admin_id
									);
						echo form_input($data);
					?>
            <?php 
					$js = 'class="btn btn-success"' ;
					echo form_submit('update','Update User',$js);
				?>
            <a href="<?php echo base_url().'users'?>" class = "btn btn-primary btn-cancel">Cancel</a> </div>
          <div class="clearfix"></div>
        </div>
        <?php echo form_close(); ?> </div>
      <!-- /.box --> 
    </section>
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
