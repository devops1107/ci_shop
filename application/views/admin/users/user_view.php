<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">User Details</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/all-users')?>">User Management</a></li>
					<li class="breadcrumb-item active" aria-current="page">User Details</li>
				</ol>
			</nav>
		</div>
	</div>
</div>
<div class="content mt-3">
	<div class="animated fadeIn">
		<div class="">
		  <div class="col-lg-12">
			<?php $this->load->view('admin/layout/validation-errors'); ?>
			<div class="card min_height">
				<div class="card-body">
					<div id="category_response_main"></div>
			            <div class="adv-table">
							<?php $this->load->view('admin/users/user_details');?>
						    <div class="col-sm-12 add_categories_box_main">
								<div class="user_details">
									<div class="row">
										<!--<div class="col-md-6 driver_details_box">
											<label>User Name :</label>
											<span>
												<?= $user_detail['username']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Contact Number :</label>
											<span>
												<?= $user_detail['mobileno']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Age :</label>
											<span>
												<?= $user_detail['age']?>
											</span>
										</div>
										
										<div class="col-md-6 driver_details_box">
											<label>City :</label>
											<span>
												<?= $user_detail['city']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>State :</label>
											<span>
												<?= $user_detail['state']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Zip Code :</label>
											<span>
												<?= $user_detail['zip_code']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Address :</label>
											<span>
												<?= ($user_detail['address']!="")?$user_detail['address']:$user_detail['street_address']." ".$user_detail['city']." ".$user_detail['state']." ".$user_detail['zip_code'] ?>
											</span>
										</div>-->
										<!-- <div class="col-md-6 driver_details_box">
											<label>Refferal Code :</label>
											<span>
												<?= ($user_detail['referral_code']!="")?$user_detail['referral_code']:"N/A"?>
											</span>
										</div> -->
										<div class="col-md-6 driver_details_box">
											<label>Total User Credit :</label>
											<span>
												<?= $user_detail['user_tokens']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Total Rooms Joined :</label>
											<span>
												<?= $user_detail['total_rooms_joined']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Total Number of Winning Lifetimes :</label>
											<span>
												<?= $user_detail['total_winning_lifetime']?>
											</span>
										</div>
										<div class="col-md-6 driver_details_box">
											<label>Total Winning of this Year:</label>
											<span>
												<?= $user_detail['total_winning']?>
											</span>
										</div>
									</div>
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
<script type="text/javascript" src="<?=ADMIN_PATH?>daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?=ADMIN_PATH?>daterangepicker/daterangepicker.js"></script>

