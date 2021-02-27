<div id="content">
	
    
    <section class="inner-content">
    	<div class="container">
            <div class="heading"><span><?=$this->lang->line('my_dashboard')?></span></div>
            <div class="row">
            	<div class="col-md-3">
                    <div class="sidebar">
                    	<div class="quick-links">
                        	<h4 class="title"><?=$this->lang->line('settings')?></h4>
                            <ul class="list-unstyled">
                            	<li><a href="<?=base_url('my-profile')?>"><?=$this->lang->line('my_profile')?></a></li>
                                <li ><a href="change-password" ><?=$this->lang->line('change_password')?></a></li>
                                <li class="active"><a href="<?=base_url('my-orders')?>"><?=$this->lang->line('my_orders')?></a></li>
                                <li><a href="<?=base_url('privacy-policy')?>"><?=$this->lang->line('privacy_policy')?></a></li>
                                <!-- <li><a href="<?=base_url('my-orders')?>">Update Email/Mobile</a></li> -->
                                <li><a href="<?=base_url('logout')?>"><?=$this->lang->line('logout')?></a></li>
                                
                            </ul>
                        </div>
                   
                    </div>
                </div> 
                <div class="col-md-9">
                    <!-- ****************** My Dashboard Section	****************** -->
                   
                    <div class="dashboard">
                    	<div class="my-orders-table">
                            <div class="table-responsive product-table">
                            <?php 
                            if(!empty($orders))
                            {   ?>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                        	<td> 
                                            	<table class="table table-wrapper">
                                                    <tr>
                                                        <td class="p-0">
                                                            <span class="order-id"><?= $orders['transaction_id']; ?></span>
                                                        </td>
                                                        <td colspan="2" class="text-right p-0">
                                                            <a href="<?=base_url('my-orders');?>" class="btn btn-primary"><?=$this->lang->line('Back')?><?=$this->lang->line('back')?></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="p-0">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-left"><?=$this->lang->line('billing_information')?></th>
                                                                        <th class="text-left"><?=$this->lang->line('shipping_information')?></th>
                                                                        <th><?=$this->lang->line('total_amount')?></th>
                                                                        <th><?=$this->lang->line('discount')?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="name"><strong><?= $orders['billing_first_name']." ".$orders['billing_last_name']; ?></strong><br>
                                                                                    <?= $orders['billing_address_1']." ".$orders['billing_address_2']."<br>".$orders['billing_city']." ".$orders['billing_country']." - ".$orders['billing_zip']; ?><br>
                                                                                E-mail : <?= $orders['billing_email']; ?><br>
                                                                                Mobile : <?= $orders['billing_mobile']; ?>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="name">
                                                                                <strong><?= $orders['shipping_first_name']." ".$orders['shipping_last_name']; ?></strong><br>
                                                                                <?= $orders['shipping_address_1']." ".$orders['shipping_address_2']."<br>".$orders['shipping_city']." ".$orders['shipping_country']." - ".$orders['shipping_zip']; ?><br>
                                                                                E-mail : <?= $orders['shipping_email']; ?><br>
                                                                                Mobile : <?= $orders['shipping_mobile']; ?>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-right">€ <?= number_format($orders['amount'],2); ?></td>
                                                                        <td class="text-right">€ <?= number_format($orders['discount'],2); ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="p-0"> 
                                                            <table class="table table-wrapper">
                                                                <tr>
                                                                    <td colspan="2" class="p-0">
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th><?=$this->lang->line('product')?></th>
                                                                                    <th><?=$this->lang->line('shop_type')?></th>
                                                                                    <th><?=$this->lang->line('price')?></th>
                                                                                    <th><?=$this->lang->line('discount')?></th>
                                                                                    <th><?=$this->lang->line('net_price')?></th>
                                                                                    <th><?=$this->lang->line('quantity')?></th>
                                                                                    <th><?=$this->lang->line('total_amount')?></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php 
                                                                                foreach ($order_details as $odetails) 
                                                                                {
                                                                                    if($odetails['shop_type'] == 1)
                                                                                        $prd_type = "Stück (Stk.)";
                                                                                    elseif($odetails['shop_type'] == 2)
                                                                                        $prd_type = "Umkarton (Kolli)";
                                                                                    elseif($odetails['shop_type'] == 3)
                                                                                        $prd_type = "Palette";
                                                                                   ?>
                                                                                  <tr>
                                                                                    <td>
                                                                                        <div class="prod-desc">
                                                                                            <a target="_blank" href="<?=base_url('product-detail/'.base64_encode($odetails['product_id']));?>">
                                                                                            <div class="prod-img">
                                                                                                <img src="<?=UPLOADS_PATH?>/products/<?php echo $odetails['product_image']; ?>" alt="<?php echo $odetails['product_title']; ?>">
                                                                                            </div>
                                                                                            <div class="prod-info">
                                                                                                <?php echo $odetails['product_title']; ?>
                                                                                            </div>
                                                                                            </a>
                                                                                        </div>
                                                                                    </td>
                                                                                    
                                                                                    <td class="text-left"><?php echo $prd_type; ?></td>
                                                                                    <td class="text-right">€ <?php echo number_format($odetails['price'],2); ?></td>
                                                                                    <td class="text-right">€ <?php echo number_format($odetails['discount'],2); ?></td>
                                                                                    <td class="text-right">€ <?php echo number_format($odetails['net_amount'],2); ?></td>
                                                                                    <td class="text-center"><?php echo $odetails['quantity']; ?></td>
                                                                                    <td class="text-right">€ <?php echo number_format($odetails['total_amount'],2); ?></td>
                                                                                  </tr>
                                                                                    <?php 
                                                                                }   ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label><?=$this->lang->line('date')?> :</label>
                                                            <span class="date"><?= date('D, jS M Y' , strtotime($orders['created_on'])); ?></span>
                                                        </td>
                                                        <?php if(!empty($orders['tax']) && $orders['tax'] > 0)
                                                        {   ?>
                                                        <td class="text-right">
                                                            <label><?=$this->lang->line('tax')?> (<?= $orders['tax']; ?>%) :</label>
                                                            <span class="price">€ <?= number_format($orders['tax_amount'],2); ?></span>
                                                        </td>
                                                        <?php 
                                                        }       ?>
                                                        <td class="text-right">
                                                            <label><?=$this->lang->line('total_order_amount')?> :</label>
                                                            <span class="price">€ <?= number_format($orders['order_amount'],2); ?></span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php 
                            } 
                            else
                            {
                                print "No Order found!";
                            }  ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    
</div>