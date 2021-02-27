<div class="">
<?php if(validation_errors()) { ?>
<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<span><b> Error - </b> <?php echo validation_errors(); ?></span>
</div>
<?php } ?>


<?php if($this->session->flashdata('flashSuccess')):?>
<div class="alert alert-success alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <?php echo $this->session->flashdata('flashSuccess');?> </div>
<?php endif?>
<?php if($this->session->flashdata('flashError')):?>
<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <?php echo $this->session->flashdata('flashError');?> </div>
<?php endif?>
</div>


