<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0">Edit Product</h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?=base_url('admin/products')?>">Products</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit Product</li>
				</ol>
			</nav>
		</div>
	</div>
</div>
<div class="content mt-3" >
	<div class="animated fadeIn">
		<div class="">
		  <div class="col-lg-12">
			<?php $this->load->view('admin/layout/validation-errors'); ?>
			<div class="card min_height">
				<div class="card-body">
		            <div class="adv-table">
					    <div class="col-sm-12 add_categories_box_main">
							<div class="row add_categories_box">
							   <?php
								$attribute = array('name'=>'frmaddpromotions','id'=>'myForm');
								echo form_open_multipart('',$attribute); ?>
								<div class="card_box">
			             		<h4>Edit Sub Categories</h4> 
								<div class="col-md-12 card_box_body">

									<div class="row">
										<input type="hidden" name="get_hidden" value="1">
										<div class="form-group col-md-6 has-feedback">
											<label>Select Brand:</label>
											<?php
											$data = array(
											'name' => 'brand_id',
											'id' => 'brand_id',
											'required' => "required",
											'class' => "form-control",
											);
											echo form_dropdown($data, $allBrands,$product_details['brand_id']);
											?> 
										</div>
										<div class="form-group col-md-6 has-feedback">
											<label>Select Category:</label>
											<?php
												$data = array(
												'name'			=> 'category_id',
												'id'			=> 'category_id',
												'required'		=> "required",
												'class'			=> "form-control",
												);
												echo form_dropdown($data, $allcategory,$product_details['category_id']);
											?> 
										</div>
										<div class="clearfix"></div>
										<div class="form-group col-md-4 has-feedback">
											<label>Product Name (English)</label>
											<?php
												$data = array(
												'name'			=> 'product_name',
												'id'			=> 'product_name',
												'required'		=> "required",
												'placeholder'	=> "Product Name in English",
												'class'			=>"form-control",
												'value'	=> (!empty($product_details) && $product_details['product_name'])?$product_details['product_name']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-4 has-feedback">
											<label>Product Name (German)</label>
											<?php
												$data = array(
												'name'			=> 'product_name_gr',
												'id'			=> 'product_name_gr',
												'required'		=> "required",
												'placeholder'	=> "Product Name in German",
												'class'			=>"form-control",
												'value'	=> (!empty($product_details) && $product_details['product_name_gr'])?$product_details['product_name_gr']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-4 has-feedback">
											<label>Product Name (Turkish)</label>
											<?php
												$data = array(
												'name'			=> 'product_name_tr',
												'id'			=> 'product_name_tr',
												'required'		=> "required",
												'placeholder'	=> "Product Name in Turkish",
												'class'			=>"form-control",
												'value'	=> (!empty($product_details) && $product_details['product_name_tr'])?$product_details['product_name_tr']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-2 has-feedback">
											<label>Price (Stück (Stk.))</label>
											<?php
												$data = array(
												'name'			=> 'single_product_price',
												'id'			=> 'single_product_price',
												'placeholder'	=> "Price Stück (Stk.)",
												'class'			=>"form-control",
												'type'			=>"number",
												'step' 			=>"any",
												'value'	=> (!empty($product_details) && $product_details['single_product_price'])?$product_details['single_product_price']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-2 has-feedback">
											<label>Offer (Stück (Stk.))</label>
											<?php
												$data = array(
												'name'			=> 'single_product_offer',
												'id'			=> 'single_product_offer',
												//'required'		=> "required",
												'placeholder'	=> "Offer Stück (Stk.)",
												'class'			=>"form-control",
												'type'			=>"number",
												'step' 			=>"any",
												'value'	=> (!empty($product_details) && $product_details['single_product_offer'])?$product_details['single_product_offer']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-2 has-feedback">
											<label>Price (Umkarton (Kolli))</label>
											<?php
												$data = array(
												'name'			=> 'master_carton_price',
												'id'			=> 'master_carton_price',
												//'required'		=> "required",
												'placeholder'	=> "Price Umkarton (Kolli)",
												'class'			=>"form-control",
												'type'			=>"number",
												'step' 			=>"any",
												'value'	=> (!empty($product_details) && $product_details['master_carton_price'])?$product_details['master_carton_price']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-2 has-feedback">
											<label>Offer (Umkarton (Kolli))</label>
											<?php
												$data = array(
												'name'			=> 'master_carton_offer',
												'id'			=> 'master_carton_offer',
												//'required'		=> "required",
												'placeholder'	=> "Offer Umkarton (Kolli)",
												'class'			=>"form-control",
												'type'			=>"number",
												'step' 			=>"any",
												'value'	=> (!empty($product_details) && $product_details['master_carton_offer'])?$product_details['master_carton_offer']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-2 has-feedback">
											<label>Price (Display)</label>
											<?php
												$data = array(
												'name'			=> 'palette_price',
												'id'			=> 'palette_price',
												//'required'		=> "required",
												'placeholder'	=> "Price Display",
												'class'			=>"form-control",
												'type'			=>"number",
												'step' 			=>"any",
												'value'	=> (!empty($product_details) && $product_details['palette_price'])?$product_details['palette_price']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-2 has-feedback">
											<label>Offer (Display)</label>
											<?php
												$data = array(
												'name'			=> 'palette_offer',
												'id'			=> 'palette_offer',
												//'required'		=> "required",
												'placeholder'	=> "Offer Display",
												'class'			=>"form-control",
												'type'			=>"number",
												'step' 			=>"any",
												'value'	=> (!empty($product_details) && $product_details['palette_offer'])?$product_details['palette_offer']:''
												);
												echo form_input($data);
											?> 
										</div>

										<div class="form-group col-md-12 has-feedback">
											<label>Description (English) : </label>
											<?php
												$data = array(
												'name' => 'description',
												'id' => 'editor',
												//'required'		=> "required",
												'placeholder'	=> "Description",
												'value'			=> ($product_details!='' && $product_details['description'])?$product_details['description']:''	
												);
												echo form_textarea($data);
											?>
										</div>
                                    
										<div class="form-group col-md-12 has-feedback">
											<label>Description (German) : </label>
											<?php
												$data = array(
												'name' => 'description_gr',
												'id' => 'editor1',
												//'required'		=> "required",
												'placeholder' => "Description (GR)",
												'value' => ($product_details!='' && $product_details['description_gr'])?$product_details['description_gr']:''	
												);
												echo form_textarea($data);
											?>
										</div>

										<div class="form-group col-md-12 has-feedback">
											<label>Description (Turkish) : </label>
											<?php
												$data = array(
												'name' => 'description_tr',
												'id' => 'editor2',
												//'required'		=> "required",
												'placeholder'	=> "Description (GR)",
												'value' => ($product_details!='' && $product_details['description_tr'])?$product_details['description_tr']:''	
												);
												echo form_textarea($data);
											?>
										</div>
										
										<div class="form-group col-md-6 has-feedback">
											<label>Upload Image <small class="text-danger"><b><?php /* 50 X 50  */?>(PNG/JPG Format)</b></small> </label>
											<input type="file" accept="image/jpeg,image/x-png" name="product_image" class="form-control-file"> 
										</div>
										
										<div class="clearfix"></div>
										<div class="col-md-12">
											<label class="label_hide">.</label>
											<input type="submit" class="btn btn-primary" value="Update" name="Submit" /> 
										</div>
									</div>

								</div>
								</div>
								<?php echo form_close(); ?>
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


<script src="<?=PLUGIN_PATH?>ckeditor/ckeditor.js"></script>
<script>
    
	ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
	ClassicEditor
        .create( document.querySelector( '#editor1' ) )
        .catch( error => {
            console.error( error );
        } );
    ClassicEditor
        .create( document.querySelector( '#editor2' ) )
        .catch( error => {
            console.error( error );
        } );

</script>

<script type="text/javascript">
 
$(document).ready(function(){

	$.ajax({          
      url : '<?php echo base_url();?>'+'admin/subcategory-list',
      type : 'post',
      data : { "category_id" : $('#category_id').val() , 'selected_id' : '<?php echo $product_details['subcategory_id']; ?>'},
      success : function( response ) {
      	$('#subcategory_id').html(response);
      }
    }); 

    $("#category_id").change(function(){ 
    	$.ajax({          
	      url : '<?php echo base_url();?>'+'admin/subcategory-list',
	      type : 'post',
	      data : { "category_id" : $('#category_id').val() , 'selected_id' : '<?php echo $product_details['subcategory_id']; ?>'},
	      success : function( response ) {
	      	$('#subcategory_id').html(response);
	      }
	    }); 

    });

});


</script>
