<?php
$user_type = $this->site_santry->get_auth_data('user_type');

?>
  

<section id="main-content">
	<section class="wrapper">
        <div class="row">
		    <div class="col-md-12">
			    <div class="page-breadcrumb bg-white">
					<div class="row">
						<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
							<h5 class="font-medium text-uppercase mb-0">Payment Settings </h5>
						</div>
						<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
							<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
								<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
									<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Payment Settings</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				
				<div class="content mt-3">
					<div class="animated fadeIn">
						<div class="col-12">
							<div class="material-card card">
								<section class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-md-7 col-xs-12 col-sm-12 height_360 ">
											<?php $this->load->view('admin/layout/validation-errors'); ?>
											<?php
												$attribute = array('name'=>'frmeditvendor','id'=>'frmeditvendor');
												echo form_open_multipart('',$attribute);
											?>
											<div class="form-group has-feedback">
												<label>Admin Commission <small class="text-danger">(required)</small>: </label>
												<?php
													$data = array(
														'name'			=> 'admin_commission',
														'id'			=> 'admin_commission',
														'required'		=> "required",
														'placeholder'	=> "Admin Commission",
														'value'         =>	$detail['admin_commission'],
														'class'			=>	"form-control",
														'type'			=>	'number',
														'step'			=>	'0.01',
													);
													echo form_input($data);
												?>
											</div>
											<!--<div class="form-group has-feedback">
												<label>Partner Commission Percent <small class="text-danger">(required)</small>: </label>
												<?php
													$data = array(
														'name'			=> 'partner_commission_percent',
														'id'			=> 'partner_commission_percent',
														'required'		=> "required",
														'placeholder'	=> "Admin Commission",
														'value'         =>	$detail['partner_commission_percent'],
														'class'			=>	"form-control",
														'type'			=>	'number',
														'step'			=>	'0.01',
													);
													echo form_input($data);
												?>
											</div>-->
											
											<div class="form-group col-md-12 col-xs-12 col-sm-12">
												<div class="row">
												<div class=" col-md-5 col-xs-12 col-sm-12"></div>
												<div class=" col-md-7 col-xs-12 col-sm-12">
												<div class=" pull-right">
												<?php /* <input type="hidden" name="highlight_id" value="<?php echo $detail['highlight_id'];?>"> */ ?>
												<input type="submit" class="btn btn-success" value="Update" name="Submit" />&nbsp;&nbsp;&nbsp;
												<a href="javascript:window.history.go(-1);" class="btn btn-danger pull-right">Back</a>
												</div></div></div>
											</div> 
											<div class="clearfix"></div>
											<?php echo form_close(); ?>
										</div>
										</div>
				</div>
										</section>
							</div>
						</div>   
					</div>
				</div>
				
			</div>
        </div>
    </section>
</section>