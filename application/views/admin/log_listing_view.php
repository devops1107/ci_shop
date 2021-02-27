<section class="content">
  <h1 class="title_page">Log Details</h1>
  <div class="row">
	<div class="col-md-12">
	  <div class="box">                
		<div class="box-body">
		  <div class="row">
			 <div class="col-md-12 col-xs-12 col-sm-12">
			 <?php //echo $comment; pr($log_content); ?>
				<form>
				  <?php if(isset($log_content['user_email'])){ ?>
				  <div class="form-group col-md-12 col-xs-12 col-sm-12">
				   <div class="row">
					<div class=" col-md-5 col-xs-12 col-sm-12">
					<label class="" for="name">Comment</label></div>
					<div class=" col-md-7 col-xs-12 col-sm-12"><?php echo $comment;?></div>
					</div>
				  </div>
				  <?php } if(isset($log_content['user_email'])){ ?>
				  <div class="form-group col-md-12 col-xs-12 col-sm-12">
				   <div class="row">
					<div class=" col-md-5 col-xs-12 col-sm-12">
					<label class="" for="name">Email Id</label></div>
					<div class=" col-md-7 col-xs-12 col-sm-12"><?php echo $log_content['user_email'];?></div>
					</div>
				  </div>
				  <?php } if(isset($log_content['first_name'])){?>
				  <div class="form-group col-md-12 col-xs-12 col-sm-12">
				   <div class="row">
					<div class=" col-md-5 col-xs-12 col-sm-12">
					<label class="" for="name">Dsr Date</label></div>
					<div class=" col-md-7 col-xs-12 col-sm-12"><?php echo ucfirst(strtolower($log_content['first_name'])).' '.ucfirst(strtolower($log_content['last_name']));?></div>
					</div>
				  </div>
				  <?php } if(isset($log_content['contact_number'])){?>
				  <div class="form-group col-md-12 col-xs-12 col-sm-12">
				   <div class="row">
					<div class=" col-md-5 col-xs-12 col-sm-12">
					<label class="" for="name">Contact Number</label></div>
					<div class=" col-md-7 col-xs-12 col-sm-12"><?php echo $log_content['contact_number'];?></div>                                   </div>
				  </div>
				  <?php } ?>
				  <?php if(!isset($log_content['user_email'])&&!isset($log_content['first_name'])&&!isset($log_content['contact_number'])){ ?>
					  
					  <span>No details available in system!</span>
					  
				  <?php } ?>
				  <div class="form-group col-md-12 col-xs-12 col-sm-12">
					<div class="row">
					<div class=" col-md-5 col-xs-12 col-sm-12"></div>
					<div class=" col-md-7 col-xs-12 col-sm-12">
					<div class=" pull-left">
					<a href="<?php echo base_url().'admin/users';?>" class="btn btn-primary">Back</a>
					
					</div></div></div>
				  </div>
				 <div class="clearfix"></div>
			   </form>
			 </div>
		  </div> <!-- /.row -->
		</div><!-- ./box-body -->
	  </div><!-- /.box -->
	</div><!-- /.col -->
  </div>
</section><!-- /.content -->