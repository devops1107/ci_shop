<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Tax Setting</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="#">Tax Setting</a></li>
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
					<strong class="card-title">Edit Tax Setting</strong>
				</div>
					<div class="card-body">
	              		<div class="col-sm-12 add_categories_box_main">
	              		<div class="row">
		              		<?php
								$attribute = array('name'=>'frmaddpromotions','id'=>'myForm');
								echo form_open_multipart('',$attribute); ?>
								
								<div class="row">
                                    <div class="form-group col-md-6 has-feedback">
										<label>Tax: <span class="starspan">&nbsp;*</span></label>
										<?php
											$data = array(
											'name'			=> 'tax',
											'id'			=> 'tax',
											'required'		=> "required",
											'placeholder'	=> 'Tax',
											'class'			=>"form-control",
											'value'			=> (isset($detail)&&$detail['tax'])?$detail['tax']:''	
											);
											echo form_input($data);
										?>
									</div>
								</div>
								<div class="row">
									
									<div class="form-group col-md-6 col-xs-6 col-sm-6">
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


