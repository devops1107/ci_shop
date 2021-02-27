<title>Baaba.de</title>
<?php  $login_data = $this->site_santry->get_web_auth_data(); ?>


<div id="content">

    <?php 
    $prd_single_price = $pdetails['single_product_price']-$pdetails['single_product_offer'];
    $prd_master_carton_price = $pdetails['master_carton_price']-$pdetails['master_carton_offer'];
    $prd_palette_price = $pdetails['palette_price']-$pdetails['palette_offer'];

    $prd_full_single_price = $pdetails['single_product_price'];
    $prd_full_master_carton_price = $pdetails['master_carton_price'];
    $prd_full_palette_price = $pdetails['palette_price'];

    if($pdetails['single_product_price'] > 0)
    {
        if($pdetails['single_product_offer'] > 0)
        {
            $product_old_price = $pdetails['single_product_price'];
            $offer_price = $pdetails['single_product_offer'];
            $product_price = $product_old_price-$offer_price;
            $is_offer_dis = '<span class="discount">€ '.$offer_price.' </span>';
        }
        else
        {
            $offer_price = 0;
            $product_price = $pdetails['single_product_price'];
            $is_offer_dis = '';
        }
        $is_offer_pkc = ' / Single Product';
        $prd_price_type = 1;
    }
    elseif($pdetails['master_carton_price'] > 0)
    {
        if($pdetails['master_carton_offer'] > 0)
        {
            $product_old_price = $pdetails['master_carton_price'];
            $offer_price = $pdetails['master_carton_offer'];
            $product_price = $product_old_price-$offer_price;
            $is_offer_dis = '<span class="discount">€ '.$offer_price.' </span>';
        }
        else
        {
            $offer_price = 0;
            $product_price = $pdetails['master_carton_price'];
            $is_offer_dis = '';
        }
        $is_offer_pkc = ' / Master Carton';
        $prd_price_type = 2;
    }
    elseif($pdetails['palette_price'] > 0)
    {
        if($pdetails['single_product_offer'] > 0)
        {
            $product_old_price = $pdetails['palette_price'];
            $offer_price = $pdetails['palette_offer'];
            $product_price = $product_old_price-$offer_price;
            $is_offer_dis = '<span class="discount">€ '.$offer_price.' </span>';
        }
        else
        {
            $offer_price = 0;
            $product_price = $pdetails['palette_price'];
        }
        $is_offer_pkc = ' / Display';
        $prd_price_type = 3;
    }
    ?>
            
            
    <section class="inner-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- ****************** Product Detail Section  ****************** -->
                    <div class="product-detail-section">
                        <div class="product-detail">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="product-view">
                                        <div class="product-img">
                                            <img src="<?=UPLOADS_PATH?>/products/<?php echo $pdetails['product_image']; ?>" alt="<?php echo $pdetails['product_title']; ?>" />
                                        </div>
                                        <a href="<?=UPLOADS_PATH?>/products/<?php echo $pdetails['product_image']; ?>" class="example-image-link view-btn" data-lightbox="example-1">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="product-info">
                                        <h3><?php echo $pdetails['brand_title']; ?> - <?php echo $pdetails['product_title']; ?></h3>
                                        <div class="availability">Availability: In stock</div>
                                        <div class="price"><span id="discountedPrice" data-product-title="<?php echo $pdetails['product_title']; ?>" data-prd-price1="<?php echo $prd_single_price; ?>" data-prd-price2="<?php echo $prd_master_carton_price; ?>" data-prd-price3="<?php echo $prd_palette_price; ?>" data-prd-dsc1="<?php echo $prd_full_single_price; ?>" data-prd-dsc2="<?php echo $prd_full_master_carton_price; ?>" data-prd-dsc3="<?php echo $prd_full_palette_price; ?>">€ <?php echo $product_price; ?></span>
                                            <?php 
                                            if($offer_price > 0)
                                            {   ?>
                                                <span id="productOldPrice" class="old-price">€ <?php echo $product_old_price; ?></span>
                                                <?php 
                                            }   ?>
                                        </div>
                                        <div>


                                            <div class="input-group" id="prdPriceDetails"></div>
                                            <div class="clearfix"></div>

                                            <div class="input-group pull-left">
                                                <input type="hidden" id="productId" value="<?php echo base64_encode($pdetails['product_id']); ?>">
                                                <select id="productPriceType" class="form-control">
                                                <?php
                                                if($pdetails['single_product_price'] > 0)
                                                {   ?>
                                                    <option value="<?php echo base64_encode(1); ?>">Single Product</option>
                                                    <?php
                                                }
                                                if($pdetails['master_carton_price'] > 0)
                                                {   ?>    
                                                    <option value="<?php echo base64_encode(2); ?>">Master Carton</option>>
                                                    <?php
                                                }
                                                if($pdetails['palette_price'] > 0)
                                                {   ?> 
                                                    <option value="<?php echo base64_encode(3); ?>">Display</option>>
                                                    <?php
                                                }   ?> 
                                                </select>
                                            </div>

                                            <div class="input-group qty-btn pull-left">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-number btn-minus" data-type="plus" data-field="quant[1]">
                                                    
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                                <input id="prodQuantity" type="text" name="quant" class="form-control input-number" value="1" >
                                                <span class="input-group-btn"> 
                                                    <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="quant[1]">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <a id="addProdCart" href="javascript:void(0);" class="btn btn-info btn-large">Add to cart</a>
                                        </div>
                                    </div>

                                    <?php
                                    if(!empty($allProductDiscount))
                                    {   ?>                                         
                                        <div class="table-responsive">
                                            <h3>Discount</h3>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sr. No.</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Discount Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach ($allProductDiscount as $dvalue) 
                                                    { 
                                                        $i++;
                                                        if($dvalue['price_type'] == 1)
                                                            $product_type = "Single";
                                                        elseif($dvalue['price_type'] == 2)
                                                            $product_type = "Master Carton";
                                                        elseif($dvalue['price_type'] == 3)
                                                            $product_type = "Display";
                                                        ?>
                                                        <tr>
                                                          <td class="text-left"><?php echo $i; ?></td>
                                                          <td><?php echo $product_type; ?></td>
                                                          <td><?php echo $dvalue['quantity']; ?></td>
                                                          <td>€ <?php echo $dvalue['discount_price']; ?></td>
                                                        </tr>
                                                        <?php       
                                                    }   ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php 
                                    }   ?>
                                </div>
                            </div>
                        </div>
                        <div class="product-desc  section-block">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="product-detail.html#description" aria-controls="description" role="tab" data-toggle="tab">Description</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="description">
                                    <?php echo $pdetails['product_description']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ****************** Similar Products Section    ****************** -->
                    <div class="similar-products section-block">
                        <div class="heading"><span><?=$this->lang->line('similar_products')?></span></div>
                        <div class="product-list similar-product-slider">
                            <?php 
                            foreach ($similar_product as $products) 
                            {
                                $rand_float = rand(0,1);
                                $star_rating = rand(3, 5);
                                if($products['single_product_price'] > 0)
                                {
                                    if($products['single_product_offer'] > 0)
                                    {
                                        $product_old_price = $products['single_product_price'];
                                        $offer_price = $products['single_product_offer'];
                                        $product_price = $product_old_price-$offer_price;
                                        $is_offer_dis = '<div class="new-label"><span>Off<br>€ '.$offer_price.'</span></div>';
                                    }
                                    else
                                    {
                                        $offer_price = 0;
                                        $product_price = $products['single_product_price'];
                                        $is_offer_dis = '';
                                    }
                                    $is_offer_pkc = ' / Single Product';
                                    $prd_price_type = 1;
                                }
                                elseif($products['master_carton_price'] > 0)
                                {
                                    if($products['master_carton_offer'] > 0)
                                    {
                                        $product_old_price = $products['master_carton_price'];
                                        $offer_price = $products['master_carton_offer'];
                                        $product_price = $product_old_price-$offer_price;
                                        $is_offer_dis = '<div class="new-label"><span>Off<br>€ '.$offer_price.'</span></div>';
                                    }
                                    else
                                    {
                                        $offer_price = 0;
                                        $product_price = $products['master_carton_price'];
                                        $is_offer_dis = '';
                                    }
                                    $is_offer_pkc = ' / Master Carton';
                                    $prd_price_type = 2;
                                }
                                elseif($products['palette_price'] > 0)
                                {
                                    if($products['single_product_offer'] > 0)
                                    {
                                        $product_old_price = $products['palette_price'];
                                        $offer_price = $products['palette_offer'];
                                        $product_price = $product_old_price-$offer_price;
                                        $is_offer_dis = '<div class="new-label"><span>Off<br>€ '.$offer_price.'</span></div>';
                                    }
                                    else
                                    {
                                        $offer_price = 0;
                                        $product_price = $products['palette_price'];
                                        $is_offer_dis = '';
                                    }
                                    $is_offer_pkc = ' / Display';
                                    $prd_price_type = 3;
                                }
                                ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="product-box">
                                        <?php echo $is_offer_dis; ?>
                                        <div class="img"><img src="<?=UPLOADS_PATH?>/products/<?php echo $products['product_image']; ?>" alt="<?php echo $products['product_title']; ?>" />
                                        </div>
                                        <div class="product-detail">
                                            <div class="name"><strong><?php echo $products['product_category']; ?> - </strong><?php echo $products['product_title']; ?></div>
                                                <?php
                                                if($star_rating == 4)
                                                {   ?>
                                                    <a href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                    <?php 
                                                }
                                                elseif($star_rating == 5)
                                                {   ?>
                                                    <a href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0);"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                    <?php 
                                                }
                                                if($rand_float == 1 && $star_rating < 5)
                                                {   ?>
                                                    <a href="javascript:void(0);"><i class="fa fa-star-half-full" aria-hidden="true"></i></a>
                                                    <?php 
                                                }   ?>
                                                
                                                
                                            </div> -->
                                            <div class="price">
                                                <span>€ <?php echo $product_price.$is_offer_pkc; ?></span>
                                                <?php 
                                                if($offer_price > 0)
                                                {
                                                    ?>
                                                    <br>
                                                    <span class="old-price">€ <?php echo $product_old_price; ?></span>
                                                    <?php 
                                                }   ?>
                                            </div>
                                        </div>
                                        <div class="hover-block">
                                            <ul class="list-inline">
                                                <li><a class="add-cart addProdCart" data-productId="<?php echo base64_encode($products['product_id']); ?>" data-productPriceType="<?php echo base64_encode($prd_price_type); ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Cart"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                
                                                <li><a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>" data-toggle="tooltip" data-placement="top" title="Quickview"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }   ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
</div>

<audio id="audio" src="http://dev.interactive-creation-works.net/1/1.ogg"></audio>
<script src="<?php echo base_url().'assets/web/js/jquery-1.12.4.min.js'; ?>"></script>
<script type="text/javascript">

function number_format(val){
    //Parse the value as a float value
    val = parseFloat(val);
    //Format the value w/ the specified number
    //of decimal places and return it.
    return val.toFixed(2);
}
 
$(document).ready(function(){    
    $(".btn-number").on("click", function() {

      var oldValue = $('.input-number').val();

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

      var productId = $('#productId').val();
      var productPriceType = $('#productPriceType').val();
      var productPriceSingle = $('#discountedPrice').attr('data-prd-price'+atob(productPriceType));
      var totalPrdAmount = number_format(productPriceSingle*newVal);

      if(atob(productPriceType) == 1)
        var prodTitle = 'Single Product';
      else if(atob(productPriceType) == 2)
        var prodTitle = 'Master Carton';
      else if(atob(productPriceType) == 3)
        var prodTitle = 'Display';

      var productDiscSingle = $('#discountedPrice').attr('data-prd-dsc'+atob(productPriceType));
      var totalFullPrdAmount = number_format(productDiscSingle*newVal);

      if(totalFullPrdAmount > 0)
        $('#productOldPrice').html('€ '+totalFullPrdAmount);
      //console.log(atob(productPriceType)+' '+productPriceSingle+' '+newVal);

      $.ajax({
        type:"POST",
        url: "<?php echo base_url().'get-product-price'?>",
        data: {'productId' : productId , 'productPriceType' : productPriceType , 'prodQuantity' : newVal},
        success: function(result) { 
            //console.log(result); 
            if(result > 0)
            {
                $('#discountedPrice').html('€ '+number_format(result*newVal));
                var productDesDetails = newVal+' '+ prodTitle +'* €'+ result +' = € '+number_format(result*newVal)+'<br>';
                $('#prdPriceDetails').html(productDesDetails);
            }
            else
            {
                $('#discountedPrice').html('€ '+totalPrdAmount);
                $('#prdPriceDetails').html('');
            }
        }
      });

      $('.input-number').val(newVal);

      /*Change price*/
      var productPriceAmnt = <?php echo $product_price ?>;
      console.log(productPriceAmnt);
    });

    
    $("#productPriceType").change(function() {

      var productId = $('#productId').val();
      var productPriceType = $('#productPriceType').val();
      var prodQuantity = $('#prodQuantity').val();
      if(atob(productPriceType) == 1)
        var prodTitle = 'Single Product';
      else if(atob(productPriceType) == 2)
        var prodTitle = 'Master Carton';
      else if(atob(productPriceType) == 3)
        var prodTitle = 'Display';

      var productPriceSingle = $('#discountedPrice').attr('data-prd-price'+atob(productPriceType));
      var totalPrdAmount = number_format(productPriceSingle*prodQuantity);

      var productDiscSingle = $('#discountedPrice').attr('data-prd-dsc'+atob(productPriceType));
      var totalFullPrdAmount = number_format(productDiscSingle*prodQuantity);

      if(totalFullPrdAmount > 0)
        $('#productOldPrice').html('€ '+totalFullPrdAmount);      

      $.ajax({
        type:"POST",
        url: "<?php echo base_url().'get-product-price'?>",
        data: {'productId' : productId , 'productPriceType' : productPriceType , 'prodQuantity' : prodQuantity},
        success: function(result) { 
            console.log(result); 
            if(result > 0)
            {
                $('#discountedPrice').html('€ '+number_format(result*prodQuantity));
                var productDesDetails = prodQuantity+' '+prodTitle+'* €'+result+' = € '+number_format(result*prodQuantity)+'<br>';
                $('#prdPriceDetails').html(productDesDetails);
            }
            else
            {
                $('#discountedPrice').html('€ '+totalPrdAmount);
                $('#prdPriceDetails').html('');
            }
        }
      });

    });

    $("#prodQuantity").keyup(function() {

      var productId = $('#productId').val();
      var productPriceType = $('#productPriceType').val();
      var prodQuantity = $('#prodQuantity').val();
      if(atob(productPriceType) == 1)
        var prodTitle = 'Single Product';
      else if(atob(productPriceType) == 2)
        var prodTitle = 'Master Carton';
      else if(atob(productPriceType) == 3)
        var prodTitle = 'Display';

      var productPriceSingle = $('#discountedPrice').attr('data-prd-price'+atob(productPriceType));
      var totalPrdAmount = number_format(productPriceSingle*prodQuantity);

      var productDiscSingle = $('#discountedPrice').attr('data-prd-dsc'+atob(productPriceType));
      var totalFullPrdAmount = number_format(productDiscSingle*prodQuantity);

      if(totalFullPrdAmount > 0)
        $('#productOldPrice').html('€ '+totalFullPrdAmount);      

      $.ajax({
        type:"POST",
        url: "<?php echo base_url().'get-product-price'?>",
        data: {'productId' : productId , 'productPriceType' : productPriceType , 'prodQuantity' : prodQuantity},
        success: function(result) { 
            console.log(result); 
            if(result > 0)
            {
                $('#discountedPrice').html('€ '+number_format(result*prodQuantity));
                var productDesDetails = prodQuantity+' '+prodTitle+'* €'+result+' = € '+number_format(result*prodQuantity)+'<br>';
                $('#prdPriceDetails').html(productDesDetails);
            }
            else
            {
                $('#discountedPrice').html('€ '+totalPrdAmount);
                $('#prdPriceDetails').html('');
            }
        }
      });

    });
    function play() {
        var audio = document.getElementById("audio");
        audio.play();
    }
    $("#addProdCart").on("click", function() {
        
        var productId = $('#productId').val();
        var productPriceType = $('#productPriceType').val();
        var prodQuantity = $('#prodQuantity').val();

        $.ajax({
            type:"POST",
            url: "<?php echo base_url().'add-to-cart'?>",
            data: {'productId' : productId , 'productPriceType' : productPriceType , 'prodQuantity' : prodQuantity},
            success: function(result) { 
                //console.log(result); 
                if(result > 0){

                    play()
                    $('#cartTotalProd').html(result);
                }
            }
        });
    });
});

</script>