<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Edit User</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/users')?>">All Users</a></li>
				</ol>
			</nav>
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
					<strong class="card-title">Edit User</strong>
				</div>
					<div class="card-body">
	              		<div class="col-sm-12 add_categories_box_main">
	              		<div class="row">
		              		<?php
								$attribute = array('name'=>'frmaddpromotions','id'=>'myForm');
								echo form_open_multipart('',$attribute); ?>
								
								<div class="row">
                                    <div class="form-group col-md-6 has-feedback">
										<label>First Name: <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'first_name',
											'id'			=> 'name',
											'required'		=> "required",
											'placeholder'	=> 'First Name',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['first_name'])?$detail['first_name']:''	
											);
											echo form_input($data);
										?>
									</div>

									<div class="form-group col-md-6 has-feedback">
										<label>Last Name: <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'last_name',
											'id'			=> 'name',
											'required'		=> "required",
											'placeholder'	=> 'Last Name',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['last_name'])?$detail['last_name']:''
											);
											echo form_input($data);
										?> 
									</div>
									<div class="form-group col-md-6 has-feedback">
										<label>Mobile Number : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'mobileno',
											'id'			=> 'mobileno',
											'required'		=> "required",
											'placeholder'	=> 'User Name',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['mobileno'])?$detail['mobileno']:''
											);
											echo form_input($data);
										?>
									</div>

									<div class="form-group col-md-6 has-feedback">
										<label>Email ID : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'emailid',
											'id'			=> 'emailid',
											'type'			=>'email',
											'required'		=> "required",
											'readonly'		=> 'readonly',
											'placeholder'	=> 'Email ID',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['emailid'])?$detail['emailid']:''
											);
											echo form_input($data);
										?> 
									</div>

									<div class="form-group col-md-6 has-feedback">
										<label>VAT Number : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'vat_number',
											'id'			=> 'vat_number',
											'required'		=> "required",
											'placeholder'	=> 'User Name',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['vat_number'])?$detail['vat_number']:''
											);
											echo form_input($data);
										?>
									</div>

									<div class="form-group col-md-6 has-feedback">
										<label>Commercial Register Number : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'commercial_reg_no',
											'id'			=> 'commercial_reg_no',
											'required'		=> "required",
											'placeholder'	=> 'User Name',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['commercial_reg_no'])?$detail['commercial_reg_no']:''
											);
											echo form_input($data);
										?>
									</div>

									<div class="form-group col-md-12 col-xs-12 col-sm-12">
										<input type="submit" class="btn btn-primary" value="Update User" name="Submit" />
									</div>
								</div>
								<?php echo form_close(); ?>

							</div>	
						</div>	
	              	</div> 
				<div class="clearfix"> &nbsp;</div>
			</div>
		  </div>
		</div>
	</div>
</div>


