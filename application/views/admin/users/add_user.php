<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Add New User</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/all-users')?>">All Users</a></li>
					<li class="breadcrumb-item active" aria-current="page">Add New User</li>
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
					<strong class="card-title">Add New User</strong>
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
											);
											echo form_input($data);
										?> 
									</div>
									<div class="form-group col-md-6 has-feedback">
										<label>User Name : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'user_name',
											'id'			=> 'user_name',
											'required'		=> "required",
											'placeholder'	=> 'User Name',
											'class'			=>"form-control",
											);
											echo form_input($data);
										?>
									</div>

									<div class="form-group col-md-6 has-feedback">
										<label>Email ID : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'email',
											'id'			=> 'email',
											'type'			=>'email',
											'required'		=> "required",
											'placeholder'	=> 'Email ID',
											'class'			=>"form-control",
											);
											echo form_input($data);
										?> 
									</div>
									<div class="form-group col-md-6 has-feedback">
										<label>Password : <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'password',
											'id'			=> 'password',
											'type'			=>'password',
											'required'		=> "required",
											'placeholder'	=> 'Password',
											'class'			=>"form-control",
											);
											echo form_input($data);
										?> 
									</div>
									<div class="form-group col-md-6 has-feedback">
										<label>Mobile No: <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'mobileno',
											'id'			=> 'mobileno',
											'required'		=> "required",
											'placeholder'	=> 'Mobile Number',
											'class'			=>"form-control",
											);
											echo form_input($data);
										?> 
									</div>
									<div class="form-group col-md-12 col-xs-12 col-sm-12">
										<div class="row">
										<div class=" col-md-5 col-xs-12 col-sm-12"></div>
										<div class=" col-md-7 col-xs-12 col-sm-12">
										<div class=" pull-right">
										<input type="submit" class="btn btn-primary" value="Add User" name="Submit" />
										</div></div></div>
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

