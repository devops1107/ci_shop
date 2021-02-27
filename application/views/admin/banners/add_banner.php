<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Add New Banner</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/banners')?>">Banners</a></li>
					<li class="breadcrumb-item active" aria-current="page">Add New Banner</li>
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
							   	<form method="POST" action="<?php echo base_url("admin/add-banner"); ?>" enctype="multipart/form-data">
							   		<div class="row">
								   		<div class="form-group col-md-4 has-feedback">
											<label>Heading (English)</label>
											<?php
												$data = array(
												'name'			=> 'heading',
												'id'			=> 'heading',
												'placeholder'	=> "Heading in English",
												'class'			=>"form-control",
												'rows'			=>"3",
												);
												echo form_textarea($data);
											?> 
										</div>

										<div class="form-group col-md-4 has-feedback">
											<label>Heading (German)</label>
											<?php
												$data = array(
												'name'			=> 'heading_gr',
												'id'			=> 'heading_gr',
												'placeholder'	=> "Heading in German",
												'class'			=>"form-control",
												'rows'			=>"3",
												);
												echo form_textarea($data);
											?> 
										</div>

										<div class="form-group col-md-4 has-feedback">
											<label>Heading (Turkish)</label>
											<?php
												$data = array(
												'name'			=> 'heading_tr',
												'id'			=> 'heading_tr',
												'placeholder'	=> "Heading in Turkish",
												'class'			=>"form-control",
												'rows'			=>"3",
												);
												echo form_textarea($data);
											?> 
										</div>

										<div class="clearfix"></div>

										<div class="form-group col-md-4 has-feedback">
											<label>Sub Heading (English)</label>
											<?php
												$data = array(
												'name'			=> 'sub_heading',
												'id'			=> 'sub_heading',
												'placeholder'	=> "Sub Heading in English",
												'class'			=>"form-control",
												'rows'			=>"3",
												);
												echo form_textarea($data);
											?> 
										</div>

										<div class="form-group col-md-4 has-feedback">
											<label>Sub Heading (German)</label>
											<?php
												$data = array(
												'name'			=> 'sub_heading_gr',
												'id'			=> 'sub_heading_gr',
												'placeholder'	=> "Sub Heading in German",
												'class'			=>"form-control",
												'rows'			=>"3",
												);
												echo form_textarea($data);
											?> 
										</div>

										<div class="form-group col-md-4 has-feedback">
											<label>Sub Heading (Turkish)</label>
											<?php
												$data = array(
												'name'			=> 'sub_heading_tr',
												'id'			=> 'sub_heading_tr',
												'placeholder'	=> "Sub Heading in Turkish",
												'class'			=>"form-control",
												'rows'			=>"3",
												);
												echo form_textarea($data);
											?> 
										</div>

										<div class="clearfix"></div>

										<div class=" col-md-4 col-xs-12 col-sm-12">
											<label class="" for="name">Banner Image :</label>
											<br/><span style="color:#ff0000"><b>size: 1920 x 1080 px</b></span>
										</div>
										<div class=" col-md-8 col-xs-12 col-sm-12">
											<input type="file" name="image" required="required" />
										</div>
									</div>
										
									<div class="row">
									  
										<div class="form-group col-md-12 col-xs-12 col-sm-12"><br/>
											<div class="row">
												<button class="btn btn-primary" name="Add Banner" type="submit">Add Banner</button>
												<a class="btn btn-primary" href="<?php echo base_url().'admin/banners'?>">Back</a>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
							   	</form></div>
						    </div>
			              </div>
	              	</div> 
				<div class="clearfix"> &nbsp;</div>
			</div>
		  </div>
		</div>
	</div>
</div>