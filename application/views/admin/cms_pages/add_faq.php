<style>
.ck-editor__editable {
    min-height: 150px;
}
#tbl_all_filter {
	display: none;
}
</style>
<div class="page-breadcrumb bg-white">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
            <h5 class="font-medium text-uppercase mb-0">FAQ List</h5>
        </div>
        <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
            <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                <ol class="breadcrumb mb-0 justify-content-end p-0 bg-white">
                    <li class="breadcrumb-item"><a href="<?=base_url('admin/dashboard')?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ List</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="content mt-3" >
	<div class="animated fadeIn">
		<div class="">
		  <div class="col-lg-12">
			<?php //$this->load->view('admin/layout/validation-errors'); ?>
			
			<div class="card">
				<div class="card-body">
					<div class="row">
						
						<div class="col-md-7 col-xs-12 col-sm-12 height_360 ">	  
							 <?php echo validation_errors(); ?>
 								<?php echo form_open_multipart('admin/faq/add-faq'); ?>	 
							<div class="form-group has-feedback">
                                <label>Question: </label>
                                 <input type="text" class="form-control" name="question" placeholder="Add Question">
                            </div>
                            <div class="form-group has-feedback">
                                <label>Question (GR): </label>
                                 <input type="text" class="form-control" name="question_gr" placeholder="Add Question (GR)">
                            </div>
                            <div class="form-group has-feedback">
                                <label>Question (TR): </label>
                                 <input type="text" class="form-control" name="question_tr" placeholder="Add Question (TR)">
                            </div>

							<div class="form-group has-feedback">
                                <label>Answer : </label>
                                <?php
                                    $data = array(
                                    'name'          => 'answer',
                                    'id'            => 'editor',
                                    //'required'        => "required",
                                    'placeholder'   => "Banner Description",
                                    'value'         => '',
                                    'class'         =>"form-control",
                                    );
                                    echo form_textarea($data);
                                ?>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Answer (GR) : </label>
                                <?php
                                    $data = array(
                                    'name'          => 'answer_gr',
                                    'id'            => 'editor2',
                                    //'required'        => "required",
                                    'placeholder'   => "Answer (GR)",
                                    'value'         => '',
                                    'class'         =>"form-control",
                                    );
                                    echo form_textarea($data);
                                ?>
                            </div>
                            <div class="form-group has-feedback">
                                <label>Answer (TR) : </label>
                                <?php
                                    $data = array(
                                    'name'          => 'answer_tr',
                                    'id'            => 'editor3',
                                    //'required'        => "required",
                                    'placeholder'   => "Answer (TR)",
                                    'value'         => '',
                                    'class'         =>"form-control",
                                    );
                                    echo form_textarea($data);
                                ?>
                            </div>
							 
							<div class="form-group col-md-12 col-xs-12 col-sm-12">
								<div class="row">
								<div class=" col-md-5 col-xs-12 col-sm-12"></div>
								<div class=" col-md-7 col-xs-12 col-sm-12">
								<div class=" pull-right">
									 
								<input type="submit" class="btn btn-success" value="Submit" name="Submit" />&nbsp;&nbsp;&nbsp;
								<a href="<?php echo base_url('admin/faq')?>" class="btn btn-danger pull-right">Back</a>
								</div></div></div>
							</div>
							<div class="clearfix"></div>
						   <?php echo form_close(); ?>
						</div> 
						<div class="clearfix"> &nbsp;</div>
					</div>
				</div> 
				<div class="clearfix"> &nbsp;</div>
			</div>
		  </div>
		</div>
	</div>
</div>
<script src="<?php echo WEB_PATH; ?>js/vendor/jquery-2.1.4.min.js"></script>
<script src="<?php echo BOOTSTRAP_PATH;?>js/bootstrap_validator.js" type="text/javascript" language="javascript" ></script> 
<script src="<?php echo PLUGIN_PATH; ?>datepicker/bootstrap-datepicker.js"></script>

<!-- <script src="<?=PLUGIN_PATH?>ckeditor/ckeditor.js"></script>
<script src="<?=PLUGIN_PATH?>ckeditor/samples/js/sample.js"></script> -->

<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
<script>
    
	 ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } ); 
</script>
<script>
    /* CKEDITOR.replace('editor', {
      extraPlugins: 'placeholder',
      height: 220
    }); */
  </script>
<script>
$(document).ready(function() {
    $('#frmAddHotDeal')
        .bootstrapValidator({
            framework: 'bootstrap',
            //excluded: [':disabled'],
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
            },
            fields: {
            	hot_deal_name: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
            	description: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
            	banner: {
                    validators: {
                        notEmpty: {
                            message: 'This field is required'
                        },
                    }
                },
            }
        })
        .on('error.validator.bv', function(e, data) {
            data.element
                .data('bv.messages')
                // Hide all the messages
                .find('.help-block[data-bv-for="' + data.field + '"]').hide()
                // Show only message associated with current validator
                .filter('[data-bv-validator="' + data.validator + '"]').show();
        });
});
</script>

<script>
	/*CKEDITOR.plugins.addExternal( 'colorbutton', '/eezi_cabi/assets/admin/plugins/ckeditor/plugins/colorbutton/', 'plugin.js' );
	CKEDITOR.replace('editor', {
		extraPlugins: 'colorbutton'
    });*/
  </script>