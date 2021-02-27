<?php
	$user_type = $this->site_santry->get_auth_data('user_type');
	$uri2	=	$this->uri->segment(3);
	$uri3	=	$this->uri->segment(4);
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
			<h5 class="font-medium text-uppercase mb-0">Token History</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/all-users')?>">User Management</a></li>
					<li class="breadcrumb-item active" aria-current="page">Token History</li>
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
					<div class="adv-table col-md-12">
						<?php $this->load->view('admin/users/user_details');?>
		            	<table  class="display table table-bordered table-striped" id="tbl_all">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Tokens</th>
									<th>Paid Amount</th>
									<th>Earning Type</th>
									<th>Purchased On</th>
								</tr>
							</thead>
						</table>			            
					</div>
				</section>
			</div>
		</div>   
	</div>
</div>  

<link href="<?=ADMIN_PATH?>extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<script src="<?=ADMIN_PATH?>extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
	function confirm_delete(){
	return confirm("Do you really want to delete this account?");
	}
</script>

<script>
$(document).ready(function() {
	$('#tbl_all').dataTable({
	 	 	
		"sAjaxSource": '<?php echo base_url('admin/user-token-history/'.$this->uri->segment(3))?>',
		"bProcessing": true,
		"bLengthChange":true,
		"bServerSide": true,
		"bAutoWidth": false,
			'aoColumns': [ {"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},],
				
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.ajax( {
				"dataType": 'json', 
				"type": "POST", 
				"url": sSource, 
				"data": aoData, 
				"success": function(data){
					fnCallback(data);
					
				}
			} );
		}
	})
});	
</script>



