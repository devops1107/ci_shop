<?php
	$user_type = $this->site_santry->get_auth_data('user_type');
?>
<style>
	.search_btn.btn.btn-primary {
    display: block;
    margin-top: 6px;
	}
</style>
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">All Users</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">All Users</li>
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
	            	<?php $this->load->view('admin/layout/validation-errors'); ?>
					<div class="card-body">
						<?=form_open_multipart('',array('class'=>'my-form','id'=>'myForm'))?>
						<div class="adv-table card_box">
						    <h4>Filters</h4> 
							<div class="col-md-12 card_box_body">
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label>User Name :</label>
											<?php
												$data = array(
												'name'			=> 'customer_name',
												'id'			=> 'customer_name',
												//'required'		=> "required",
												'class'			=>"form-control",
												'value'			=>set_value('customer_name'),
												);	
												echo form_input($data);
											?>  
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Email :</label>
											<?php
												$data = array(
												'name'			=> 'customer_email',
												'id'			=> 'customer_email',
												//'required'		=> "required",
												'class'			=>"form-control",
												'value'			=>set_value('customer_email'),
												);	
												echo form_input($data);
											?>  
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Contact Number :</label>
											<?php
												$data = array(
												'name'			=> 'contact_number',
												'id'			=> 'contact_number',
												//'required'		=> "required",
												'class'			=>"form-control",
												'value'			=>set_value('contact_number'),
												);	
												echo form_input($data);
											?>  
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>Status :</label>
											<?php
												$data = array(
												'name'			=> 'user_status',
												'id'			=> 'user_status',
												//'required'		=> "required",
												'class'			=>"form-control",
												);
												
												$selectedUserStatus=isset($_POST['user_status'])?$_POST['user_status']:'';
												echo form_dropdown($data,$user_statuses,$selectedUserStatus);
											?>  
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label></label>
											<input type="hidden" name="get_hidden" value="1"/>
											<?php echo form_submit('','Filter Results', 'class="btn btn-primary search_btn"'); ?>
										</div> 
									</div>
								</div>
							</div>
						</div>
						<?=form_close()?>
					</div>
		            <div class="adv-table col-md-12">
		            	<!-- <div class="text-right"><a href="<?=base_url()?>admin/add-user" class="btn btn-danger">Add User</a></div> -->
		              	<table id="tbl_all" class="table table-striped table-bordered" style="width:100%;">
							<thead>
								<tr class="light-blue" role="row">
									<th>Sr.No.</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Mobile No.</th>
									<th>Email</th>
									<th>Mail Status</th></th>
									<th>VAT Number</th>
									<th>Commercial Register Number</th>
									<th class="hide_on_print">Action</th>
								</tr>
							</thead>
						</table>			            
					</div>
				</section>
			</div>
		</div>   
	</div>
</div>  

<script>
	$('#tbl_all').on('click','.activate_user_status',function(){
		return confirm('Are you sure avtivate this user ?');
	});
	$('#tbl_all').on('click','.deactivate_user_status',function(){
		return confirm('Are you sure deavtivate this user ?');
	});
</script>


<link href="<?=ADMIN_PATH?>extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<script src="<?=ADMIN_PATH?>extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
	function confirm_delete(){
	return confirm("Do you really want to delete this account?");
	}
</script>


<script>
	
	$(document).ready(function() {
		$('#userDetail').modal('hide');
		
		$('#tbl_all').dataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"sAjaxSource": '<?php echo base_url()?>admin/users',
			"bProcessing": true,
			"bLengthChange":true,
			"bServerSide": true,
			'columns': [ {"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false, "class":"hide_on_print" },],
			
			"fnServerData": function ( sSource, aoData, fnCallback ) {
				$.ajax( {
					"dataType": 'json', 
					"type": "POST", 
					"url": sSource, 
					"data": aoData, 
					"success": fnCallback
				} );
			}
		})
	});	
</script>


