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
                            if(!empty($order_details))
                            {   ?>
                                <table class="table">
                                    <tbody>
                                    <?php 
                                    foreach ($order_details as $orders) 
                                    {   ?>
                                    	<tr>
                                        	<td> 
                                            	<table class="table table-wrapper">
                                                    <tr>
                                                        <td>
                                                            <span class="order-id"><?= $orders['transaction_id']; ?></span>
                                                            <a target="_blank" href="<?=base_url('generate-order-pdf/'.base64_encode($orders['order_id']));?>" class="btn btn-primary"><?=$this->lang->line('download_pdf')?></a>
                                                        </td>
                                                        <td class="text-right">
                                                            <a href="<?=base_url('order-details/'.base64_encode($orders['order_id']));?>" class="btn btn-primary"><?=$this->lang->line('order_details')?></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
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
                                                        <td>
                                                            <label><?=$this->lang->line('date')?> :</label>
                                                            <span class="date"><?= date('D, jS M Y' , strtotime($orders['created_on'])); ?></span>
                                                        </td>
                                                        <td class="text-right">
                                                            <label><?=$this->lang->line('total_order_amount')?> :</label>
                                                            <span class="price">€ <?= number_format($orders['net_amount'],2); ?></span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php 
                                    }   ?>
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