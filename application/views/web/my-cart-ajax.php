<?php 
if(!empty($order_details))
{   ?>
    <div class="table-responsive product-table">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-left"><?=$this->lang->line('product')?> (<?= count($order_details); ?>)</th>
                    <th><?=$this->lang->line('shop_type')?></th>
                    <th><?=$this->lang->line('price')?></th>
                    <th><?=$this->lang->line('discount')?></th>
                    <th><?=$this->lang->line('net_price')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th><?=$this->lang->line('total_price')?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total_order_discount = $total_order_amount = $total_order_net_amount = 0;
            foreach ($order_details as $orders) 
            {
                $orders['discount'] = ($orders['discount'] == '') ? $orders['discount'] : 0;
                $total_order_amount = $total_order_amount+($orders['price']*$orders['quantity']);
                $total_order_discount = $total_order_discount+($orders['discount']*$orders['quantity']);
                $total_order_net_amount = $total_order_net_amount+($orders['net_amount']*$orders['quantity']);

                if($orders['shop_type'] == 1)
                    $prd_type = "Stück (Stk.)";
                elseif($orders['shop_type'] == 2)
                    $prd_type = "Umkarton (Kolli)";
                elseif($orders['shop_type'] == 3)
                    $prd_type = "Palette";
                
                ?>
                <tr>
                    <td>
                        <div class="thead"><?=$this->lang->line('product')?></div>
                        <div class="prod-desc">
                            <a target="_blank" href="<?=base_url('product-detail/'.base64_encode($orders['product_id']));?>">
                            <div class="prod-img">
                                <img src="<?=UPLOADS_PATH?>/products/<?php echo $orders['product_image']; ?>" alt="">
                            </div>
                            <div class="prod-info">
                                <div class="name"><strong><?php echo $orders['brand_title']; ?></strong> - <?php echo $orders['product_title']; ?></div>
                                <div>
                                    <form method="post" action="remove-from-cart">
                                        <input type="hidden" name="remove_order_id" value="<?php echo base64_encode($orders['order_id']); ?>">
                                        <button class="btn-link" type="submit"><?=$this->lang->line('remove')?></button>
                                    </form>
                                </div>
                            </div>
                            </a>
                        </div>
                    </td>
                    <td><?php echo $prd_type; ?></td>
                    <td>€ <?php echo sprintf("%0.2f",$orders['price']); ?></td>
                    <td>€ <?php echo sprintf("%0.2f",$orders['discount']); ?></td>
                    <td>€ <?php echo sprintf("%0.2f",$orders['net_amount']); ?></td>
                    <td>
                        <div class="wrap quantity-input">
                            <div data-updOrderId="<?php echo $orders['order_id']; ?>" class="minus"data-type="minus"><i class="fa fa-minus"></i></div>  
                            <input id="prdOrderQty<?php echo $orders['order_id']; ?>" class="couont" type="text" value="<?php echo $orders['quantity']; ?>" min="1" max="100" />
                            <div data-updOrderId="<?php echo $orders['order_id']; ?>" class="plus" data-type="plus"><i class="fa fa-plus"></i></div>
                        </div>
                    </td> 
                    <td>€ <?php echo sprintf("%0.2f",$orders['total_amount']); ?></td>
                </tr>      
                <?php
            }   ?> 
            </tbody>
        </table>
    </div>
    <div class="cart-info clearfix">
        <div class="row">
            <div class="col-sm-7">
                <div class="note"><?=$this->lang->line('delivery_payment_options_later')?></div>
                
            </div>
            <div class="col-sm-5">
                <div class="cart-table">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="title"><?=$this->lang->line('subtotal')?></td>
                                <td>€ <?php echo sprintf("%0.2f",$total_order_amount); ?></td>
                            </tr>
                            <tr>
                                <td class="title"><?=$this->lang->line('discount')?> </td>
                                <td>€ <?php echo sprintf("%0.2f",$total_order_discount); ?></td>
                            </tr>
                            <tr>
                                <td class="title"><?=$this->lang->line('tax')?> (<?php echo $tax_percent; ?>%)</td>
                                <td>
                                	<?php $total_order_tax = $total_order_net_amount*($tax_percent/100); ?>
                                	€ <?php echo sprintf("%0.2f",$total_order_tax, 2); ?></td>
                            </tr>
                            <tr class="total">
                                <td class="title"><?=$this->lang->line('grand_total')?></td>
                                <td>€ <?php echo sprintf("%0.2f",($total_order_net_amount+$total_order_tax)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix links-btn">
        <a href="<?php echo base_url();?>shop" class="btn btn-info btn-large"><i class="fa fa-chevron-left" aria-hidden="true"></i>&nbsp;&nbsp;<?=$this->lang->line('continue_shopping')?></a>
        <a href="<?php echo ($this->site_santry->is_web_login()) ? base_url().'checkout' : base_url().'login'; ?>" class="btn btn-info btn-large pull-right"><?=$this->lang->line('checkout')?>&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i></a>
    </div>
    <?php
}
else 
{
    print $this->lang->line('no_product_in_cart');
}   ?>


<script src="assets/web/js/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    
$(document).ready(function(){

    

    $(".minus , .plus").on("click", function() {
        
        var orderId = $(this).attr('data-updOrderId');
        var orderQty = $('#prdOrderQty'+orderId).val();
        var oldValue = $('#prdOrderQty'+orderId).val();

        if ($(this).attr('data-type') == "plus") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below one
            if (oldValue > 1) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
              newVal = 1;
            }
        }

        $('#prdOrderQty'+orderId).val(newVal);

        $.ajax({
            type:"POST",
            url: "<?php echo base_url('update-cart-qty');?>",
            data: {'cartOrderId' : orderId , 'caerOrderQty' : newVal},
            success: function(result) {
                if(result)
                    $('#myCartDetails').html(result);
            }
        });
    });

});

</script>