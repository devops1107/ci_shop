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

<div><h4 class="title"><?php echo $product_details['product_name']; ?></h4></div>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Sr. No.</th>
      <th scope="col">Type</th>
      <th scope="col">Quantity</th>
      <th scope="col">Discount Price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
<?php

if(!empty($allProductDiscount))
{
	$i = 0;
	foreach ($allProductDiscount as $dvalue) 
	{ 
		$i++;
		$dis_id = $dvalue['id'];
		if($dvalue['price_type'] == 1)
			$product_type = "Stück (Stk.)";
		elseif($dvalue['price_type'] == 2)
			$product_type = "Umkarton (Kolli)";
		elseif($dvalue['price_type'] == 3)
			$product_type = "Display";
		?>
		<tr>
	      <th scope="row"><?php echo $i; ?></th>
	      <td><?php echo $product_type; ?></td>
	      <td><?php echo $dvalue['quantity']; ?></td>
	      <td>€ <?php echo $dvalue['discount_price']; ?></td>
	      <td><a onClick="delete_product_discount('<?php echo base64_encode($dis_id); ?>');" href="#" class="btn btn-color button_icon" title="Delete"><i class="fas fa-trash"></i></a></td>
	    </tr>
		<?php		
	}
}
else
{	?>
	<tr><td>No Discount Found!</td></tr>
	<?php	      
}

?>

</tbody>
</table>