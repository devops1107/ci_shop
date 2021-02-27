<?php $uri2=$this->uri->segment(3); 
	  $page_type=$this->uri->segment(4); ?>
<section id="main-content">
	<section class="wrapper">
        <div class="row">
            <div class="col-sm-12">
           			<section class="card min_height">
	            	<?php $this->load->view('admin/layout/validation-errors'); ?>
					<header class="card-header">
	            		<?=$title;?>
						<div id="open_click"></div>

	            	</header>

	              	<div class="card-body">
			            <div class="adv-table">
			            	<table id="tbl_all" class="table table-striped table-bordered">
								<thead>
								    <tr class="light-blue" role="row">
									  <th>Sr.No.</th>
									  <th>Description</th>
									  <th>Version Created On</th>
									  <th>Version Modified On</th>
									</tr>
								</thead>
							</table>

			            </div>
	              	</div>
              	</section>
            </div>
        </div>   
    </section>
</section>

<script>
	$("table").on('click','.btn_del',function(){
         return confirm('Are you sure remove this  record ?');
   });
</script>


 
<!-- Button to Open the Modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
  Open modal
</button> -->

<!-- The Modal -->



<link href="<?=ADMIN_PATH?>extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<script src="<?=ADMIN_PATH?>extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
	function confirm_delete(){
		return confirm("Do you really want to delete this account?");
	}
</script>


<script>

$(document).ready(function() {
	var page_type='<?=$page_type?>';
	var uri2='<?=$uri2?>';
	 $('#tbl_all').dataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"sAjaxSource": '<?php echo base_url()?>admin/cms-pages/'+uri2+'/'+page_type+'',
		"bProcessing": true,
		"bLengthChange":true,
		"bServerSide": true,
		'columns': [ {"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false },],
		
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


