<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Edit Category</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/categories')?>">Categories</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit Category</li>
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
				             		<h4>Edit Categories</h4> 
									<div class="col-md-12 card_box_body">
										<div class="row">
											<input type="hidden" name="get_hidden" value="1">
											<div class="form-group col-md-4 has-feedback">
												<label>Category (English)</label>
												<?php
													$data = array(
													'name'			=> 'category_name_en',
													'id'			=> 'category_name_en',
													'required'		=> "required",
													'placeholder'	=> "Category Name in English",
													'class'			=>"form-control",
													'value'	=> (!empty($category_details) && $category_details['category_name'])?$category_details['category_name']:''
													);
													echo form_input($data);
												?> 
											</div>
											<div class="form-group col-md-4 has-feedback">
												<label>Category (German)</label>
												<?php
													$data = array(
													'name'			=> 'category_name_gr',
													'id'			=> 'category_name_gr',
													'required'		=> "required",
													'placeholder'	=> "Category Name in German",
													'class'			=>"form-control",
													'value'	=> (!empty($category_details) && $category_details['category_name_gr'])?$category_details['category_name_gr']:''
													);
													echo form_input($data);
												?> 
											</div>
											<div class="form-group col-md-4 has-feedback">
												<label>Category (Turkish)</label>
												<?php
													$data = array(
													'name'			=> 'category_name_tr',
													'id'			=> 'category_name_tr',
													'required'		=> "required",
													'placeholder'	=> "Category Name in Turkish",
													'class'			=>"form-control",
													'value'	=> (!empty($category_details) && $category_details['category_name_tr'])?$category_details['category_name_tr']:''
													);
													echo form_input($data);
												?> 
											</div>
											<div class="form-group col-md-6 has-feedback">
												<div class="row">
													<div class="form-group col-md-7">
													<label>Upload Image <small class="text-danger"><b><?php /* 50 X 50  */?>(PNG/JPG Format)</b></small> </label>
													<input type="file" accept="image/x-png,image/jpeg" name="category_image" class="form-control-file">
												</div>
													
													<div class="form-group col-md-1 has-feedback mt-2">
														<?php 
															 //$image_url=(!empty($category_details) && $category_details['category_image'])?$category_details['category_image']:''?>
														<?php if($category_details['category_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/categories/'.$category_details['category_image'])){ ?>
															<img src="<?=UPLOAD_URL.'categories/'.$category_details['category_image']?>" width="50px" height="50px"/>	
													   <?php } else { ?>
															<img src="<?=UPLOAD_URL.'categories/default_category.png'?>" width="50px" height="50px"/>
													   <?php } ?>
														<!-- <img src="<?=UPLOAD_URL.'categories/'.$image_url?>" width="50px" height="50px"/> --> 
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="col-md-12">
												<label class="label_hide">.</label> 
												<input type="submit" class="btn btn-primary" value="Update" name="Submit" />
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