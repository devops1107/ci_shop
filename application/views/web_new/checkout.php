<div id="content">
	<?php 
  if(empty($order_details))
    redirect('shop');
  ?>
  <section class="inner-content">
  	<div class="container">
      <div class="checkout">
      	<div class="heading"><span><?=$this->lang->line('checkout')?></span></div>
          <div class="row">
          	<div class="col-md-8">
              	<div class="checkout-process">
              		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?=$this->lang->line('billing_information')?></a></h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <?php
                                $attribute = array('name'=>'billingForm','id'=>'formBillingInfo');
                                echo form_open_multipart('',$attribute); ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('first_name')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_first_name',
                                              'id'          => 'billing_first_name',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_first_name'])?$billing_details['billing_first_name']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('last_name')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_last_name',
                                              'id'          => 'billing_last_name',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_last_name'])?$billing_details['billing_last_name']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('company')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_company',
                                              'id'          => 'billing_company',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_company'])?$billing_details['billing_company']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('email')?>*</label> 

                                            <?php
                                            $data = array(
                                              'name' => 'billing_email',
                                              'id' => 'billing_email',
                                              'type' => 'email',
                                              'class' => 'form-control',
                                              'placeholder' => '',
                                              'required' => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_email'])?$billing_details['billing_email']:''
                                            );

                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('address')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_address_1',
                                              'id'          => 'billing_address_1',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_address_1'])?$billing_details['billing_address_1']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('address')?> 2 *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_address_2',
                                              'id'          => 'billing_address_2',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_address_2'])?$billing_details['billing_address_2']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('city')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_city',
                                              'id'          => 'billing_city',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_city'])?$billing_details['billing_city']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('zip')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'billing_zip',
                                              'id'          => 'billing_zip',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($billing_details) && $billing_details['billing_zip'])?$billing_details['billing_zip']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('country')?> *</label>

                                            <?php

                                            $data = array(
                                              'name' => 'billing_country',
                                              'id' => 'billing_country',
                                              'type' => 'text',
                                              'class' => 'form-control',
                                              'placeholder' => '',
                                              'required' => 'required',
                                              'value' => (!empty($billing_details) && $billing_details['billing_country'])?$billing_details['billing_country']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('telephone')?> *</label>

                                            <?php

                                            $data = array(
                                              'name' => 'billing_telephone',
                                              'id' => 'billing_telephone',
                                              'type' => 'text',
                                              'class' => 'form-control',
                                              'placeholder' => '',
                                              'required' => 'required',
                                              'value' => (!empty($billing_details) && $billing_details['billing_telephone'])?$billing_details['billing_telephone']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('mobile')?> *</label>

                                            <?php

                                            $data = array(
                                              'name' => 'billing_mobile',
                                              'id' => 'billing_mobile',
                                              'type' => 'text',
                                              'class' => 'form-control',
                                              'placeholder' => '',
                                              'required' => 'required',
                                              'value' => (!empty($billing_details) && $billing_details['billing_mobile'])?$billing_details['billing_mobile']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('fax')?> *</label>

                                            <?php

                                            $data = array(
                                              'name' => 'billing_fax',
                                              'id' => 'billing_fax',
                                              'type' => 'text',
                                              'class' => 'form-control',
                                              'placeholder' => '',
                                              'required' => 'required',
                                              'value' => (!empty($billing_details) && $billing_details['billing_fax'])?$billing_details['billing_fax']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="label_radio" for="radio-03">
                                                <input name="sample-radio-02" id="radio-03" value="2" type="radio"><?=$this->lang->line('ship_this_address')?>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="label_radio" for="radio-04">
                                                <input name="sample-radio-02" id="radio-04" value="2" type="radio" checked=""><?=$this->lang->line('ship_different_address')?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <span>* <?=$this->lang->line('required_fields')?></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <button id="saveBillingInfo" type="submit" class="btn btn-info btn-large"><?=$this->lang->line('continue')?></button>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>

                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <h4 class="panel-title">
                            <a id="collapseTwoTab" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" aria-controls="collapseTwo"><?=$this->lang->line('shipping_information')?>
                            </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <?php
                                $attribute = array('name'=>'billingForm','id'=>'formShippingInfo');
                                echo form_open_multipart('',$attribute); ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('first_name')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_first_name',
                                              'id'          => 'shipping_first_name',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_first_name'])?$shipping_details['shipping_first_name']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('last_name')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_last_name',
                                              'id'          => 'shipping_last_name',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_last_name'])?$shipping_details['shipping_last_name']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('company')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_company',
                                              'id'          => 'shipping_company',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_company'])?$shipping_details['shipping_company']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('email')?>*</label> 

                                            <?php
                                            $data = array(
                                              'name' => 'shipping_email',
                                              'id' => 'shipping_email',
                                              'type' => 'email',
                                              'class' => 'form-control',
                                              'placeholder' => '',
                                              'required' => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_email'])?$shipping_details['shipping_email']:''
                                            );

                                            echo form_input($data);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('address')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_address_1',
                                              'id'          => 'shipping_address_1',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_address_1'])?$shipping_details['shipping_address_1']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('address')?> 2 *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_address_2',
                                              'id'          => 'shipping_address_2',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_address_2'])?$shipping_details['shipping_address_2']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('city')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_city',
                                              'id'          => 'shipping_city',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_city'])?$shipping_details['shipping_city']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?=$this->lang->line('zip')?> *</label>

                                            <?php

                                            $data = array(
                                              'name'        => 'shipping_zip',
                                              'id'          => 'shipping_zip',
                                              'type'        => 'text',
                                              'class'       => 'form-control',
                                              'placeholder' => '',
                                              'required'    => 'required',
                                              'value'   => (!empty($shipping_details) && $shipping_details['shipping_zip'])?$shipping_details['shipping_zip']:''
                                            );

                                            echo form_input($data);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?=$this->lang->line('country')?> *</label>

                                        <?php

                                        $data = array(
                                          'name' => 'shipping_country',
                                          'id' => 'shipping_country',
                                          'type' => 'text',
                                          'class' => 'form-control',
                                          'placeholder' => '',
                                          'required' => 'required',
                                          'value' => (!empty($shipping_details) && $shipping_details['shipping_country'])?$shipping_details['shipping_country']:''
                                        );

                                        echo form_input($data);

                                        ?>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label><?=$this->lang->line('telephone')?> *</label>

                                      <?php

                                      $data = array(
                                        'name' => 'shipping_telephone',
                                        'id' => 'shipping_telephone',
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required' => 'required',
                                        'value' => (!empty($shipping_details) && $shipping_details['shipping_telephone'])?$shipping_details['shipping_telephone']:''
                                      );

                                      echo form_input($data);

                                      ?>
                                    </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label><?=$this->lang->line('mobile')?> *</label>

                                      <?php

                                      $data = array(
                                        'name' => 'shipping_mobile',
                                        'id' => 'shipping_mobile',
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required' => 'required',
                                        'value' => (!empty($shipping_details) && $shipping_details['shipping_mobile'])?$shipping_details['shipping_mobile']:''
                                      );

                                      echo form_input($data);

                                      ?>
                                    </div>
                                  </div>
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <label><?=$this->lang->line('fax')?> *</label>

                                      <?php

                                      $data = array(
                                        'name' => 'shipping_fax',
                                        'id' => 'shipping_fax',
                                        'type' => 'text',
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required' => 'required',
                                        'value' => (!empty($shipping_details) && $shipping_details['shipping_fax'])?$shipping_details['shipping_fax']:''
                                      );

                                      echo form_input($data);

                                      ?>
                                    </div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-sm-6">
                                    <div class="form-group">
                                      <button id="saveShippingInfo" type="submit" class="btn btn-info btn-large"><?=$this->lang->line('continue')?></button>
                                    </div>
                                  </div>
                                </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                      <div class="panel-heading" role="tab" id="headingThree">
                        <h4 class="panel-title">
                        <a id="collapseThreeTab" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" aria-controls="collapseThree"><?=$this->lang->line('payment_information')?>
                        </a>
                        </h4>
                      </div>
                      <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="label_radio" for="radio-09">
                                  <input name="sample-radio-05" checked="checked" id="radio-09" value="5" type="radio"><?=$this->lang->line('cash_on_elivery')?>
                                </label>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <button id="saveDeliveryInfo" type="submit" class="btn btn-info btn-large" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseThree"><?=$this->lang->line('continue')?></button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="panel panel-default">
                      <div class="panel-heading" role="tab" id="headingFive">
                        <h4 class="panel-title">
                        <a id="collapseFiveTab" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" aria-expanded="false" aria-controls="collapseThree">
                       <?=$this->lang->line('oreder_review')?> </a>
                        </h4>
                      </div>
                      <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                        <div class="panel-body">
                          <div class="pro-table">
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
                                  $total_order_amount = $total_order_amount+($orders['price']*$orders['quantity']);
                                  $total_order_discount = $total_order_discount+($orders['discount']*$orders['quantity']);
                                  $total_order_net_amount = $total_order_net_amount+($orders['net_amount']*$orders['quantity']);

                                  if($orders['shop_type'] == 1)
                                      $prd_type = "Single Product";
                                  elseif($orders['shop_type'] == 2)
                                      $prd_type = "Master Carton";
                                  elseif($orders['shop_type'] == 3)
                                      $prd_type = "Palette";

                                  ?>
                                  <tr>
                                    <td>
                                      <div class="thead">Product</div>
                                      <div class="prod-desc">
                                        <a target="_blank" href="<?=base_url('product-detail/'.base64_encode($orders['product_id']));?>">
                                        <div class="prod-img">
                                          <img src="<?=UPLOADS_PATH?>/products/<?php echo $orders['product_image']; ?>" alt="">
                                        </div>
                                        <div class="prod-info">
                                          <div class="name"><strong><?php echo $orders['brand_title']; ?></strong> - <?php echo $orders['product_title']; ?></div>
                                        </div>
                                        </a>
                                      </div>
                                    </td>
                                    <td><?php echo $prd_type; ?></td>
                                    <td>€ <?php echo $orders['price']; ?></td>
                                    <td>€ <?php echo $orders['discount']; ?></td>
                                    <td>€ <?php echo $orders['net_amount']; ?></td>
                                    <td><?php echo $orders['quantity']; ?></td> 
                                    <td>€ <?php echo $orders['total_amount']; ?></td>
                                  </tr>      
                                  <?php
                                }   ?> 
                              </tbody>
                              </table>
                            </div>
                            <div class="cart-table">
                              <table class="table">
                              <tbody>
                                <tr>
                                  <td class="title"><?=$this->lang->line('subtotal')?></td>
                                  <td>€ <?php echo $total_order_amount; ?></td>
                                </tr>
                                <!-- <tr>
                                  <td class="title">Shipping &amp; Handling (Free Shipping - Free)</td>
                                  <td>$ 0.0</td>
                                </tr> -->
                                <tr>
                                  <td class="title"><?=$this->lang->line('discount')?> </td>
                                  <td>€ <?php echo $total_order_discount; ?></td>
                                </tr>
                                <tr class="total">
                                  <td class="title"><?=$this->lang->line('grand_total')?></td>
                                  <td>€ <?php echo $total_order_net_amount; ?></td>
                                </tr>
                              </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="place-order">
                            <a href="<?= base_url('place-order')?>" class="btn btn-info btn-large"><?=$this->lang->line('place_order')?></a>
                            <a href="<?= base_url('my-cart')?>" class="btn-link"><?=$this->lang->line('forgot_an_item')?> <span><?=$this->lang->line('edit_your_cart')?></span></a>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
            	<div class="checkout-progress">
                	<h4><?=$this->lang->line('your_checkout_progress')?></h4>
                    <div class="detail-wrapper">
                    	<div class="title"><?=$this->lang->line('billing_information')?> <a href="#collapseOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?=$this->lang->line('change')?></a></div>
                        <div class="info">
                        	<address id="showBillingAddress">
                           <?php echo (!empty($this->session->userdata("sessionOrderBillingInfo"))) ? $this->session->userdata("sessionOrderBillingInfo") : ""; ?> 
                          </address>
                        </div>
                    </div>
                    <div class="detail-wrapper">
                    	<div class="title"><?=$this->lang->line('shipping_information')?> <a href="#collapseTwo" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><?=$this->lang->line('change')?></a></div>
                        <div class="info">
                        	<address id="showShippingAddress">
                           <?php echo (!empty($this->session->userdata("sessionOrderShippingInfo"))) ? $this->session->userdata("sessionOrderShippingInfo") : ""; ?>  
                          </address>
                        </div>
                    </div>
                    <!-- <div class="detail-wrapper">
                    	<div class="title">Shipping Method <a href="checkout.html#">Change</a></div>
                        <div class="info">
                        	Free Shipping - Free $0.00 
                        </div>
                    </div> -->
                    <div class="detail-wrapper">
                    	<div class="title"><?=$this->lang->line('payment_information')?></div>
                      <div class="info">
                      	<?=$this->lang->line('payment_method')?> - <?=$this->lang->line('cash_on_elivery')?> 
                      </div>
                    </div> 
                    <div class="detail-wrapper">
                      <div class="title"><?=$this->lang->line('order_amount')?></div>
                        <div class="info">
                          <p><?=$this->lang->line('subtotal')?> - <strong>€ <?php echo $total_order_amount; ?></strong></p>
                          <p><?=$this->lang->line('discount')?> - <strong>€ <?php echo $total_order_discount; ?></strong></p>
                          <p><?=$this->lang->line('grand_total')?> - <strong>€ <?php echo $total_order_net_amount; ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>

    
</div>

<script src="<?php echo base_url().'assets/web/js/jquery-1.12.4.min.js'; ?>"></script>
<script type="text/javascript">
 
$(document).ready(function(){    
    
    $("#formBillingInfo").submit(function(event) {
         
        event.preventDefault();
        var form = $(this);
        
        $.ajax({
            type:"POST",
            url: "<?php echo base_url().'save-billing-info'?>",
            data: form.serialize(),
            success: function(result) { 
              //console.log(result);
              $('#showBillingAddress').html(result);
              $("#collapseTwoTab").attr('href',"#collapseTwo");
              $("#collapseTwoTab").click();                
            }
        });
    });

    $("#radio-03").on("click", function() {

      $('#shipping_first_name').val($('#billing_first_name').val());
      $('#shipping_last_name').val($('#billing_last_name').val());
      $('#shipping_company').val($('#billing_company').val());
      $('#shipping_email').val($('#billing_email').val());
      $('#shipping_address_1').val($('#billing_address_1').val());
      $('#shipping_address_2').val($('#billing_address_2').val());
      $('#shipping_city').val($('#shipping_first_name').val());
      $('#shipping_zip').val($('#billing_city').val());
      $('#shipping_country').val($('#billing_country').val());
      $('#shipping_telephone').val($('#billing_telephone').val());
      $('#shipping_mobile').val($('#billing_mobile').val());
      $('#shipping_fax').val($('#billing_fax').val());
    });

    $("#radio-04").on("click", function() {

      $('#shipping_first_name').val('');
      $('#shipping_last_name').val('');
      $('#shipping_company').val('');
      $('#shipping_email').val('');
      $('#shipping_address_1').val('');
      $('#shipping_address_2').val('');
      $('#shipping_city').val('');
      $('#shipping_zip').val('');
      $('#shipping_country').val('');
      $('#shipping_telephone').val('');
      $('#shipping_mobile').val('');
      $('#shipping_fax').val('');
    });

    $("#formShippingInfo").submit(function(event) {
         
        event.preventDefault();
        var form = $(this);
   
        $.ajax({
            type:"POST",
            url: "<?php echo base_url().'save-shipping-info'?>",
            data: form.serialize(),
            success: function(result) { 
                //console.log(result); 
                $('#showShippingAddress').html(result);
                $("#collapseThreeTab").attr('href',"#collapseThree");
                $("#collapseThreeTab").click();
            }
        });
    });

    $("#saveDeliveryInfo").submit(function(event) {
         
        $("#collapseFiveTab").attr('href',"#collapseFives");
        $("#collapseFiveTab").click();
    });
});

</script>