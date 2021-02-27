<?php 
	if($this->site_santry->get_auth_data('user_permission') != '1'){
		$this->session->set_flashdata('flashError','This User Has Been Restricted To Access');
		redirect('users');
	}
?>
<style>
#tbl_all_filter {
	display: none;
}
</style>

<section class="content-header">
  <h1> View All User </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header"> </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div role="grid" class="dataTables_wrapper form-inline" id="example2_wrapper">
            <div class="row">
              <?php if($this->site_santry->get_auth_data('user_permission')==1){ ?>
              <div class="col-xs-12"> <a href="<?php echo base_url().'users/add_user'?>"
					
              
                <button  class="btn btn-block-new btn-primary btn-cancel"><i class="fa fa-plus-circle"></i> Add User</button>
                </a> </div>
              <?php } ?>
            </div>
            <table id="tbl_all" class="table table-bordered table-hover  dataTable no-footer customer_list_bg" aria-describedby="dataTables-example_info" >
              <thead>
                <tr class="light-blue">
                  <th> Sr.No.</th>
                  <th> Name </th>
                  <th> Email </th>
                  <th> User Name </th>
                  <th> Action </th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <!-- /.box-body --> 
      </div>
      <!-- /.box --> 
    </div>
    <!-- /.col --> 
  </div>
  <!-- /.row --> 
</section>
<script>

	function showalert(){
		return confirm('Are you sure delete this user?');
	}
	
</script> 
<script>
 $(document).ready(function() {
 
	 $('#tbl_all').dataTable({
			"language": {
			"lengthMenu": " &nbsp;&nbsp; Records per page",
			},
		"sAjaxSource": '<?php echo base_url()?>users/viewalluser',
		"bProcessing": true,
		"bServerSide": true,
		'columns': [ { },{ },{ },{ },{ },],
		
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