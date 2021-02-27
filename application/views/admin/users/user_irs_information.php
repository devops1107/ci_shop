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
			<h5 class="font-medium text-uppercase mb-0">IRS Information</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/all-users')?>">User Management</a></li>
					<li class="breadcrumb-item active" aria-current="page">IRS Information</li>
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
						<div id="irs_response_main"></div>
						<div class="adv-table card_box mb-3">
						<h4>Update IRS Documents</h4> 
					    <div class="col-sm-12">
							<div class="row card_box_body">
							   <?php
								$attribute = array('name'=>'frmaddpromotions','id'=>'myForm');
								echo form_open_multipart('',$attribute); ?>
								<div class="row">
									<input type="hidden" name="get_hidden" value="1">
									<div class=" col-md-10 has-feedback">
										<div class="row">
											<div class="form-group col-md-12 has-feedback">
											  <label>Upload IRS Documents<small class="text-danger">(Maximum of 1 MB)</small> </label><br>
											   <input type="file" name="irs_document[]" id="irs_document" accept="" multiple>
											</div>
										</div>
									</div>
									<div class="form-group col-md-2 has-feedback">
									    <label class="label_hide">.</label> 
										<input type="submit" class="btn btn-primary" value="Upload" name="Submit" />
									</div>
								</div>
								<?php echo form_close(); ?>
							</div>
					    </div>
		            </div>
						
		            	<table  class="display table table-bordered table-striped" id="tbl_all">
							<thead>
								<tr>
									<th>Sr No.</th>
									<th>Document</th>
									<th>Uploaded On</th>
									<th>Action</th>
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
	$('#tbl_all').on('click','.btn_del',function(){
		//alert("hello");
		if (confirm('Are you sure delete this IRS document?')) {
			return true;
		}else{
			return false;
		}
	});
</script>

<script>
$(document).ready(function() {
	var tableIrsInformation = $('#tbl_all').dataTable({
	 	 	
		"sAjaxSource": '<?php echo base_url('admin/user-irs-information/'.$this->uri->segment(3))?>',
		"bProcessing": true,
		"bLengthChange":true,
		"bServerSide": true,
		"bAutoWidth": false,
			'aoColumns': [ {"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false,"class":"hide_on_print"},],
				
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
	
	$('#myForm').submit(function(e){
		e.preventDefault();
		//alert(is_confirm_status);
		var updateData = new FormData(this);
		$.ajax( {
			"dataType": 'json',
			"url": '<?=base_url('admin/add-irs-information/'.$this->uri->segment(3))?>', 
			"type": "POST",
			"data": updateData,
			cache:false,
			contentType: false,
			processData: false,
			"success": function(data){
				if(data.status=='success')
				{
					$('#myForm')[0].reset();
					$('#irs_response_main').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> '+data.message+'</div>');
					tableIrsInformation.fnClearTable();
					tableIrsInformation.dataTable();
				}else if(data.status=='error'){
					$('#irs_response_main').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong> '+data.message+'</div>');
					//window.setTimeout(function(){location.reload()},2000)
				}
			}
		});
	});
	
	
});	
</script>



