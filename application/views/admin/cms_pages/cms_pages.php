<?php $page_type=$this->uri->segment(3); ?>
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
									  <th>Page Name</th>
									  <th>Description</th>
									  <th class="hide_on_print">Action</th>
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



<link rel="<?=WEB_PATH;?>stylesheet" href="assets/data-tables/DT_bootstrap.css" />
<link href="<?=WEB_PATH;?>assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
<script src="<?=WEB_PATH;?>assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
<script  src="<?=WEB_PATH;?>assets/data-tables/DT_bootstrap.js"></script>
<script src="<?=WEB_PATH;?>js/dynamic_table_init.js"></script>

<script>
	function confirm_delete(){
		return confirm("Do you really want to delete this account?");
	}
</script>


<script>

$(document).ready(function() {
	var page_type='<?=$page_type?>';
	 $('#tbl_all').dataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"sAjaxSource": '<?php echo base_url()?>admin/cms-pages/'+page_type+'',
		"bProcessing": true,
		"bLengthChange":true,
		"bServerSide": true,
		'columns': [ {"bSortable":false },{"bSortable":false },{"bSortable":false },{"bSortable":false, "class":"hide_on_print" },],
		
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


