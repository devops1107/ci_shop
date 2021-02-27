<style type="text/css">
    .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
.col-xs-12 {
    width: 100%;
}
.col-xs-6 {
    float: left;
    width: 100%;
}
</style>

<table style="width: 100%;">
    <tr>
        <td></td>
        <td style="text-align: right; line-height: 30px;"><h3 class="pull-right"><?=$this->lang->line('order')?> # <?= $orders['transaction_id']; ?></h3></td>
    </tr>
    <tr>
        <td>
            <a class="navbar-brand" href="<?=base_url('home')?>"><img style="height: 70px; width: auto; max-width: 120px;" width="120" height="70" src="<?=WEB_PATH?>/images/logo.png" alt="Logo" /></a>
            <h2>Baaba De</h2>
        </td>
        <td style="float: right; width: 49%; text-align: right;">

            <address>
                <strong>Order Date:</strong><br>
                <?php echo date('M d, Y' , strtotime($orders['created_on'])); ?><br><br>
            </address>

            <address>
                <strong>Payment Method:</strong><br>
                <?php echo $orders['payment_mode']; ?><br>
                <!-- jsmith@email.com -->
            </address>
        </td>
    </tr>
    <tr>
        <td colspan="2" height="20"></td>
    </tr>
    <tr>
        <td colspan="2" height="20" style="padding-top: 20px;"><hr></td>
    </tr>
    <tr>
        <td style="float: left; width: 50%; line-height: 20px;">
            <address>
            <strong>Billed To:</strong><br>
                <?= $orders['billing_first_name']." ".$orders['billing_last_name']; ?><br>
                <?= $orders['billing_address_1']." ".$orders['billing_address_2']."<br>".$orders['billing_city']." ".$orders['billing_country']." - ".$orders['billing_zip']; ?><br>
                E-mail : <?= $orders['billing_email']; ?><br>
                Mobile : <?= $orders['billing_mobile']; ?>
                
            </address>
        </td>
        <td style="float: right; width: 50%; line-height: 20px; text-align: right;">
            <address>
                <strong>Shipped To:</strong><br>
                <?= $orders['shipping_first_name']." ".$orders['shipping_last_name']; ?><br>
                <?= $orders['shipping_address_1']." ".$orders['shipping_address_2']."<br>".$orders['shipping_city']." ".$orders['shipping_country']." - ".$orders['shipping_zip']; ?><br>
                E-mail : <?= $orders['shipping_email']; ?><br>
                Mobile : <?= $orders['shipping_mobile']; ?>
            </address>
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    
</table>

<table style="border: solid 1px #ddd; padding: 5px; font-size: 13px;">
    <tr>
        <td style="background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; width: 100%; height: 35px; line-height: 35px; font-size: 18px; font-weight: bold; padding-left: 10px;">&nbsp;Order summary
        </td>
    </tr>
    <tr>
        <td>
            <table>
            <thead>
                <tr>
                    <td style="width:25%; color: #000; line-height: 40px; height: 30px; text-align: center; border-bottom: solid 1px #ddd;"><strong><?=$this->lang->line('product')?></strong></td>
                    <td style="width:15%; color: #000; line-height: 40px; height: 30px; text-align: center; border-bottom: solid 1px #ddd;"><strong><?=$this->lang->line('shop_type')?></strong></td>
                    <td style="width:15%; color: #000; line-height: 40px; height: 30px; text-align: center; border-bottom: solid 1px #ddd;"><strong><?=$this->lang->line('net_price')?></strong></td>
                    <td style="width:15%; color: #000; line-height: 40px; height: 30px; text-align: center; border-bottom: solid 1px #ddd;"><strong><?=$this->lang->line('discount')?></strong></td>
                    <td style="width:15%; color: #000; line-height: 40px; height: 30px; text-align: center; border-bottom: solid 1px #ddd;"><strong><?=$this->lang->line('quantity')?></strong></td>
                    <td style="width:15%; color: #000; line-height: 40px; height: 30px; text-align: center; border-bottom: solid 1px #ddd;"><strong><?=$this->lang->line('total_amount')?></strong></td>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($order_details as $odetails) 
                {
                    if($odetails['shop_type'] == 1)
                        $prd_type = "Single Product";
                    elseif($odetails['shop_type'] == 2)
                        $prd_type = "Master Carton";
                    elseif($odetails['shop_type'] == 3)
                        $prd_type = "Palette";
                   ?>
                    <tr>
                        <td style="color: #333; line-height: 35px; text-align: center; border-bottom: solid 1px #ddd;">
                            <img style="height: 28px; width: auto; padding-top: 5px;" src="<?=UPLOADS_PATH?>/products/<?php echo $odetails['product_image']; ?>" alt="<?php echo $odetails['product_title']; ?>"><br><?php echo $odetails['product_title']; ?>
                        </td>
                        <td style="color: #333; line-height: 40px; height: 25px; text-align: center; border-bottom: solid 1px #ddd;"><?php echo $prd_type; ?></td>
                        <td style="color: #333; line-height: 40px; height: 25px; text-align: center; border-bottom: solid 1px #ddd;">€ <?php echo number_format($odetails['price'],2); ?></td>
                        <td style="color: #333; line-height: 40px; height: 25px; text-align: center; border-bottom: solid 1px #ddd;">€ <?php echo $odetails['discount']; ?></td>
                        <td style="color: #333; line-height: 40px; height: 25px; text-align: center; border-bottom: solid 1px #ddd;"><?php echo $odetails['quantity']; ?></td>
                        <td style="color: #333; line-height: 40px; height: 25px; text-align: center; border-bottom: solid 1px #ddd;">€ <?php echo number_format($odetails['total_amount'],2); ?></td>
                    </tr>
                    <?php 
                }   ?>

                <tr>
                    <td class="no-line" colspan="2"></td>
                    <td colspan="2" class="thick-line text-center" style="height: 30px; line-height: 30px;"><strong><?=$this->lang->line('total_order_amount')?></strong></td>
                    <td class="thick-line text-right" style="text-align: right;height: 30px; line-height: 30px;">€ <?= number_format($orders['amount'],2); ?></td>
                </tr>
                <tr>
                    <td class="no-line" colspan="2"></td>
                    <td colspan="2" class="no-line text-center" style="height: 30px; line-height: 30px;"><strong><?=$this->lang->line('discount')?></strong></td>
                    <td class="no-line text-right" style="text-align: right;height: 30px; line-height: 30px;">€ <?= number_format($orders['discount'],2); ?></td>
                </tr>
                <tr>
                    <td class="no-line" colspan="2"></td>
                    <td colspan="2" class="no-line text-center" style="height: 30px; line-height: 30px;"><strong><?=$this->lang->line('total_amount')?></strong></td>
                    <td class="no-line" style="text-align: right; height: 30px; line-height: 30px;">€ <?= number_format($orders['net_amount'],2); ?></td>
                </tr>
            </tbody>
            </table>
        </td>
    </tr>
</table>