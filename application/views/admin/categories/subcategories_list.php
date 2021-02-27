<?php
$user_type = $this->site_santry->get_auth_data('user_type');
?>
  
 <!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Sub Categories</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Sub Categories</li>
				</ol>
			</nav>
		</div>
	</div>
</div>
<div class="content mt-3">
	<div class="animated fadeIn">
	    <div class="col-12">
            <div class="material-card card">
				<section class="card min_height">
	            	<?php $this->load->view('admin/layout/validation-errors'); ?>
	              	<div class="card-body">
						<a href="<?php echo base_url('admin/add-subcategory')?>" class="btn btn-success pull-right btn-sm  table_add">Add Sub Category</a>
			            <div class="adv-table">
						<table  class="display table table-bordered table-striped" id="tbl_all">
				              	<thead>
					              	<tr>
					                 	<th>Sr No.</th>
					                  	<th>Category Image</th>
					                  	<th>Category Name</th>
					                  	<th>Sub Cate. Name English</th>
					                  	<th>Sub Cate. Name German</th>
					                  	<th>Sub Cate. Name Turkish</th>
					                  	<th>Status</th>
					                  	<th class="hide_on_print">Action</th>
					              	</tr>
				             	</thead>
			              	</table>
			            </div>
	              	</div>
              	</section>
            </div>
        </div>   
    </div>
</div>  
<script>
	$("table").on('click','.btn_dec',function(){
		var status = $(this).data('status');
		if(status=='1')
		{
			status = 'Deactivate';
		}else{
			status = 'Activate';
		}
        return confirm('Are you sure for '+status+' this Sub Category?');
    });

	$("table").on('click','.btn_del',function(){
        return confirm("Do you really want to delete this Sub Category?");
   });
</script>

<link href="<?=ADMIN_PATH?>extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<script src="<?=ADMIN_PATH?>extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>


<script>
$(document).ready(function() {
	$('#tbl_all').dataTable({
	 	 	
		"sAjaxSource": '<?php echo base_url('admin/subcategories')?>',
		"bProcessing": true,
		"bLengthChange":true,
		"bServerSide": true,
		"bAutoWidth": false,
		'aoColumns': [ {"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false, "class":"hide_on_print" },],
		
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