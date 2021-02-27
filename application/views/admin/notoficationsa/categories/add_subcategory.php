<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Add New Sub Category</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/subcategories')?>">Subcategories</a></li>
					<li class="breadcrumb-item active" aria-current="page">Add New Sub Category</li>
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
			<div class="card min_height">
				<div class="card-body">
			            <div class="adv-table">
						    <div class="col-sm-12 add_categories_box_main">
								<div class="row add_categories_box">
								   <?php
									$attribute = array('name'=>'frmaddpromotions','id'=>'myForm');
									echo form_open_multipart('',$attribute); ?>
									<div class="card_box">
										<h4>Add Categories</h4> 
										<div class="col-md-12 card_box_body">
										<div class="row">
											<input type="hidden" name="get_hidden" value="1">
											<div class="form-group col-md-4 has-feedback">
												<label>Select Category:</label>
												<?php
													$data = array(
													'name'			=> 'category_id',
													'id'			=> 'category_id',
													'required'		=> "required",
													'class'			=> "form-control",
													);
													echo form_dropdown($data, $allcategory);
												?> 
											</div>
											<div class="form-group col-md-4 has-feedback">
												<label>Sub Category Name (English)</label>
												<?php
													$data = array(
													'name'			=> 'subcategory_name_en',
													'id'			=> 'subcategory_name_en',
													'required'		=> "required",
													'placeholder'	=> "Category Name in English",
													'class'			=>"form-control",
													);
													echo form_input($data);
												?> 
											</div>

											<div class="form-group col-md-4 has-feedback">
												<label>Sub Category Name (German)</label>
												<?php
													$data = array(
													'name'			=> 'subcategory_name_gr',
													'id'			=> 'subcategory_name_gr',
													'required'		=> "required",
													'placeholder'	=> "Category Name in German",
													'class'			=>"form-control",
													);
													echo form_input($data);
												?> 
											</div>

											<div class="form-group col-md-4 has-feedback">
												<label>Sub Category Name (Turkish)</label>
												<?php
													$data = array(
													'name'			=> 'subcategory_name_tr',
													'id'			=> 'subcategory_name_tr',
													'required'		=> "required",
													'placeholder'	=> "Category Name in Turkish",
													'class'			=>"form-control",
													);
													echo form_input($data);
												?> 
											</div>
											
											<div class="form-group col-md-6 has-feedback">
												<label>Upload Image <small class="text-danger"><b><?php /* 50 X 50  */?>(PNG/JPG Format)</b></small> </label>
												<input type="file" required="" accept="image/jpeg,image/x-png" name="subcategory_image" class="form-control-file"> 
											</div>
											
											<div class="clearfix"></div>
											<div class="col-md-12">
												<label class="label_hide">.</label>
												<input type="submit" class="btn btn-primary" value="Add" name="Submit" /> 
											</div>
										</div>
										</div>
									</div>
									<?php echo form_close(); ?>
								</div>
						    </div>
			              </div>
	              	</div> 
				<div class="clearfix"> &nbsp;</div>
			</div>
		  </div>
		</div>
	</div>
</div>

