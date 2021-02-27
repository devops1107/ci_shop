<!-- <script src="<?=PLUGIN_PATH?>ckeditor/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
<script src="<?=PLUGIN_PATH?>ckeditor/samples/js/sample.js"></script> -->
<?php 
$page_type = $this->uri->segment(3);
//pr($uri2,1);
?>
<style>
.ck-editor__editable {
    min-height: 150px;
}
</style>

<div class="page-breadcrumb bg-white">
	<div class="row">
		<div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
			<h5 class="font-medium text-uppercase mb-0"><?=$title?></h5>
		</div>
		<div class="col-lg-9 col-md-8 col-xs-12 align-self-center" style="opacity:0;">
			<nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
				<ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
					<li class="breadcrumb-item active" aria-current="page">.</li>
				</ol>
			</nav>
		</div>
	</div>
</div>


<section id="main-content" class="content mt-3">
	<section class="wrapper">
        <div class="">
            <div class="col-sm-12">
                <section class="card min_height">
	            	<?php $this->load->view('admin/layout/validation-errors'); ?>
	              	<div class="card-body">
						<!-- <a href="<?= base_url('admin/cms-pages/versions/').$page_type?>" class="btn btn-primary pull-right">View Old Versions</a> -->
						<div class="clearfix"></div>
	              		<div class="col-sm-12 add_categories_box_main">
	              		<div class="row">
		              		<?php
								$attribute = array('name'=>'frmaddpromotions','id'=>'myForm');
								echo form_open_multipart('',$attribute); ?>
								
								<div class="row">
                                    
									<div class="form-group col-md-12 has-feedback">
										<label>Description : </label>
										<?php
										$data = array(
										'name'			=> 'description',
										'id'			=> 'editor',
										//'required'		=> "required",
										'placeholder'	=> "Description",
										'class'			=>"form-control",
										'value'			=> ($detail!='' &&$detail['description'])?$detail['description']:''	
										);
										echo form_textarea($data);
										?>
									</div>
                                    
									<div class="form-group col-md-12 has-feedback">
										<label>Description(GR) : </label>
										<?php
										$data = array(
										'name'			=> 'description_gr',
										'id'			=> 'editor1',
										//'required'		=> "required",
										'placeholder'	=> "Description (GR)",
										'class'			=>"form-control",
										'value'			=> ($detail!='' &&$detail['description_gr'])?$detail['description_gr']:''	
										);
										echo form_textarea($data);
										?>
									</div>

									<div class="form-group col-md-12 has-feedback">
										<label>Description(TR) : </label>
										<?php
										$data = array(
										'name'			=> 'description_tr',
										'id'			=> 'editor1',
										//'required'		=> "required",
										'placeholder'	=> "Description (TR)",
										'class'			=>"form-control",
										'value'			=> ($detail!='' &&$detail['description_tr'])?$detail['description_tr']:''	
										);
										echo form_textarea($data);
										?>
									</div>
									
									<div class="col-md-12 text-right">
										<label style="opacity:0;">.</label>   <br>
										<input type="submit" class="btn btn-primary" value="Update"  name="Submit" />
									</div>
								</div>
							<?php echo form_close(); ?>
						</div>	
						</div>	
	              	</div>
              	</section>
            </div>
        </div>   
    </section>
</section>

<!--<script src="<?=PLUGIN_PATH?>ckeditor/ckeditor.js"></script>
<script src="<?=PLUGIN_PATH?>ckeditor/samples/js/sample.js"></script> -->
<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
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
</script>
<script>
	/* CKEDITOR.plugins.addExternal( 'colorbutton', '/eezi_cabi/assets/admin/plugins/ckeditor/plugins/colorbutton/', 'plugin.js' );
	CKEDITOR.replace('editor', {
		extraPlugins: 'colorbutton'
    }); */
</script>