<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Add New Slider</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/slider')?>">Slider</a></li>
					<li class="breadcrumb-item active" aria-current="page">Add New Slider</li>
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
							   	<form method="POST" action="<?php echo base_url("admin/add-slider"); ?>" enctype="multipart/form-data">
								  	  <div class="form-group col-md-6 col-xs-12 col-sm-12">
									   <div class="row">
										<div class=" col-md-5 col-xs-12 col-sm-12">
											<label class="" for="name">Slider Image :</label>
											<br/><span style="color:#ff0000"><b>size: 500 X 700 px</b></span>
										</div>
										<div class=" col-md-7 col-xs-12 col-sm-12">
											<input type="file" name="image" />
										</div>
										</div></div>
										
									  <div class="clearfix"></div>
									  
									  <div class="form-group col-md-12 col-xs-12 col-sm-12"><br/>
										<div class="row">
											<button class="btn btn-primary" name="Add Slider" type="submit">Add Slider</button>
											&nbsp;<a class="btn btn-primary" href="<?php echo base_url().'admin/slider'?>">Back</a>
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