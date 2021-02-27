<?php
$user_type = $this->site_santry->get_auth_data('user_type');
?>
  
 <!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Products</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Products</li>
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
						<a href="<?php echo base_url('admin/add-product')?>" class="btn btn-success pull-right btn-sm  table_add">Add Product</a>
			            <div class="adv-table">
						<table  class="display table table-bordered table-striped" id="tbl_all">
				              	<thead>
					              	<tr>
					                 	<th>Sr No.</th>
					                  	<th>Image</th>
					                  	<th>Brand</th>
					                  	<th>Category</th>
					                  	<th>Product Name</th>
					                  	<th>Price</th>
					                  	<th>Discount</th>
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

<div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Discount List </h5>
        <button id="productDescription" type="button" style="float: right; margin:  auto" class="btn btn-primary pull-right" data-product-desc="" data-toggle="modal" data-target="#addDiscountModal" title="Add Discount" data-dismiss="modal">Add Discount</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="productDiscounts"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addDiscountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Discount</h5>
        <button type="button" style="float: right; margin:  auto" class="btn btn-primary pull-right" onClick="getProductDiscount();" data-toggle="modal" data-target="#discountModal" title="Discount List">Discount List</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<?php
		$attribute = array('name'=>'frmaddpromotions','id'=>'myAddDis','action'=>'add-product-discount');
		echo form_open_multipart('',$attribute); ?>
		<div class="card_box">
			<div class="col-md-12 card_box_body">
			<div class="row">
								


				<input type="hidden" id="addProductDescId" name="product_id" value="">
				<div class="form-group col-md-12 has-feedback">
					<label>Select Type:</label>
					<?php
					$allPriceType[1]='Stück (Stk.)';
					$allPriceType[2]='Umkarton (Kolli)';
					$allPriceType[3]='Display';

					$data = array(
					'name' => 'price_type',
					'id' => 'price_type',
					'required' => "required",
					'class' => "form-control",
					);
					echo form_dropdown($data, $allPriceType);
					?> 
				</div>

				<div class="form-group col-md-12 has-feedback">
					<label>Quantity</label>
					<?php
						$data = array(
						'name'			=> 'quantity',
						'id'			=> 'quantity',
						'required'		=> "required",
						'placeholder'	=> "Product Quantity",
						'class'			=>"form-control",
						'type'			=>"number",
						);
						echo form_input($data);
					?> 
				</div>

				<div class="form-group col-md-12 has-feedback">
					<label>Price</label>
					<?php
						$data = array(
						'name'			=> 'discount_price',
						'id'			=> 'discount_price',
						'required'		=> "required",
						'placeholder'	=> "Product Discount",
						'class'			=>"form-control",
						'type'			=>"number",
						'step' 			=>"any",
						);
						echo form_input($data);
					?> 
				</div>
				
				<div class="clearfix"></div>
				<div class="col-md-12">
					<label class="label_hide">.</label>
					<input type="submit" class="btn btn-primary" value="Add" name="Submit" /> 
				</div>
			</div>
			</div>
		</div>
		<?php echo form_close(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        return confirm('Are you sure for '+status+' this Product?');
    });

	$("table").on('click','.btn_del',function(){
        return confirm("Do you really want to delete this Product?");
   	});

   	$("#myAddDis").submit(function(e) {

    	e.preventDefault(); 

	    var form = $(this);
	    var url = form.attr('action');
	    
	    $.ajax({
           type: "POST",
           url: 'add-product-discount',
           data: form.serialize(), 
           success: function(data)
           {
				$('#myAddDis').trigger("reset");
				$('#addDiscountModal').modal('hide');
				$('#discountModal').modal('show'); 
				$('#productDiscounts').html(data);

               	window.setTimeout(function() {
		            $(".alert").fadeTo(500, 0).slideUp(500, function(){
		                $(this).remove();
		            });
		        }, 5000);
           }
        });
    });

    function delete_product_discount(disId)
	{
		var x = confirm("Are you sure want to delete this discount?");
		if (x)
		{
			$.ajax({
				type: "POST",
				url: 'delete-product-discount',
				data: {'discount_id' : disId}, 
				success: function(data)
				{
					if(data  != '')
						$('#productDiscounts').html(data);

	               	window.setTimeout(function() {
			            $(".alert").fadeTo(500, 0).slideUp(500, function(){
			                $(this).remove();
			            });
			        }, 5000);
	           	}
	        });
		}
	}

	function getProductDiscount(prdId)
	{
		$('#productDescription').attr('data-product-desc',prdId);
		$('#addProductDescId').val(prdId);
		//console.log(prdId);
		$.ajax({
			"type": "POST", 
			"url": "get-product-discount", 
			"data": {'product_id' : prdId}, 
			"success": function(data){
				$('#productDiscounts').html(data);
				$(".alert").remove();
			}
		});
	}
</script>

<link href="<?=ADMIN_PATH?>extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<script src="<?=ADMIN_PATH?>extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>


<script>
$(document).ready(function() {
	$('#tbl_all').dataTable({
	 	 	
		"sAjaxSource": '<?php echo base_url('admin/products')?>',
		"bProcessing": true,
		"bLengthChange":true,
		"bServerSide": true,
		"bAutoWidth": false,
		'aoColumns': [ {"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false},{"bSortable":false, "class":"hide_on_print" },],
		
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