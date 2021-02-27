<title>Baaba.de</title>
<?php  $login_data = $this->site_santry->get_web_auth_data(); ?>

<div id="productsListView">
<div id="content">

    <section class="list-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                   
                    
                    <div class="filter-section clearfix">
                        <div class="top-left-filter">
                           
                            <div class="heading">
                                <span><?=$this->lang->line('offers')?></span>
                            </div> 
                        </div>
                        <div class="top-right-filter">
                            <div class="filter-group">
                                <label><?=$this->lang->line('show')?> :</label>
                                <select id="showItemsPerPage" class="selectpicker show-tick form-control">
                                    <option value="<?php echo MAX_WEB_RECORD; ?>"><?php echo MAX_WEB_RECORD; ?></option>
                                    <option value="15">15</option>
                                    <option value="21">21</option>
                                    <option value="30">30</option> 
                                </select>
                            </div>
                            
                        </div>
                    </div>
                    

                    <div class="product-list">
                        <div class="row">
                            <?php 
                            foreach ($products_list as $products) 
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
                                        $is_offer_dis = '<div class="new-label"><span>Off € '.$offer_price.'</span></div>';
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
                                        $is_offer_dis = '<div class="new-label"><span>Off € '.$offer_price.'</span></div>';
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
                                        $is_offer_dis = '<div class="new-label"><span>Off € '.$offer_price.'</span></div>';
                                    }
                                    else
                                    {
                                        $offer_price = 0;
                                        $product_price = $products['palette_price'];
                                        $is_offer_dis = '';
                                    }
                                    $is_offer_pkc = ' / Palette';
                                    $prd_price_type = 3;
                                }
                                ?>
                                <div class="col-md-4 col-sm-6 col-xs-6">
                                    <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>" data-toggle="tooltip" data-placement="top" title="Quickview">
                                    <div class="product-box">
                                        <?php echo $is_offer_dis; ?>
                                        <div class="img"><img src="<?=UPLOADS_PATH?>/products/<?php echo $products['product_image']; ?>" alt="<?php echo $products['product_title']; ?>" />
                                        </div>
                                        <div class="product-detail">
                                            <div class="name cut-text"><strong><?php echo $products['product_category']; ?> - </strong><?php echo $products['product_title']; ?></div>
                                            <!-- <div class="rating">
                                                <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                <?php 
                                                if($star_rating == 4)
                                                {   ?>
                                                    <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                    <?php 
                                                }
                                                elseif($star_rating == 5)
                                                {   ?>
                                                    <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                    <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                    <?php 
                                                }
                                                if($rand_float == 1 && $star_rating < 5)
                                                {   ?>
                                                    <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>"><i class="fa fa-star-half-full" aria-hidden="true"></i></a>
                                                    <?php 
                                                }   ?>
                                                
                                                
                                            </div> -->
                                            <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                            <div class="price">
                                                <span>€ <?php echo $product_price.$is_offer_pkc; ?></span>
                                                <?php 
                                                if($offer_price > 0)
                                                {   ?><br>
                                                    <span class="old-price">€ <?php echo $product_old_price; ?></span>
                                                    <?php 
                                                }   ?>
                                            </div>
                                            </a>
                                        </div>
                                        <div class="hover-block">
                                            <ul class="list-inline">
                                                <li><a class="add-cart addProdCart" data-productId="<?php echo base64_encode($products['product_id']); ?>" data-productPriceType="<?php echo base64_encode($prd_price_type); ?>" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Cart"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></li>
                                                
                                                <!-- <li><a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>" data-toggle="tooltip" data-placement="top" title="Quickview"><i class="fa fa-search" aria-hidden="true"></i></a></li> -->
                                            </ul>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                <?php 
                            } 

                            echo (empty($products_list)) ? '<div class="col-sm-12">No product found !</div>' : '';  ?>

                        </div>
                    </div>

                    <?php

                    $current_page = isset($current_page) ? $current_page : 1;
                    $total_pages = (($total_products_count%$perPage) > 0) ? (floor($total_products_count/$perPage)+1) : floor($total_products_count/$perPage);

                    if($total_products_count > 0)
                    {
                        ?>
                        <div class="bottom-pagination clearfix">
                            <ul class="pagination">
                                <?php
                                if($current_page > 1)
                                {   ?>
                                    <li><a class="searchPrdPagination" href="javascript:void(0);" data-current-page="<?php echo ($current_page-1); ?>" aria-label="Previous"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
                                    <?php 
                                } 
                                for($i = 1; $i <= $total_pages; $i++)
                                {
                                    if($i == $current_page)
                                    {   ?>
                                        <li class="active"><a href="javascript:void(0);"><?php echo $i; ?><span class="sr-only">(current)</span></a></li>
                                        <?php
                                    }
                                    else
                                    {   ?>
                                        <li><a class="searchPrdPagination" href="javascript:void(0);" data-current-page="<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                        <?php
                                    }                
                                }
                                if($total_pages > $current_page)
                                {   ?>
                                    <li><a class="searchPrdPagination" href="javascript:void(0);" data-current-page="<?php echo ($current_page+1); ?>" aria-label="Previous"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                    <?php 
                                }   ?>
                            </ul>
                        </div>
                        <?php 
                    }   ?>



                </div>
            </div>
        </div>
    </section>    
</div>
</div>


<script src="assets/web/js/jquery-1.12.4.min.js"></script>

<script type="text/javascript">
    
$(document).ready(function(){

    $('#listShortBy , #showItemsPerPage').change(function() {

        var listShortBy = $('#listShortBy').val();
        var perPage = $('#showItemsPerPage').val();
        $.ajax({
            type:"POST",
            url: "offers",
            data: {'listShortBy' : listShortBy , 'perPage' : perPage},
            success: function(result) { 
                //console.log(result); 
                $('#productsListView').html(result);
            }
        });
        
    });

    $('.searchPrdPagination').on("click", function() {

        var prdSubCategory = $('.prdSubCategory').attr('data-subcate');
        var currentPage = $(this).attr('data-current-page');
        //console.log(searchArrBrand);
        
        var listShortBy = $('#listShortBy').val();
        var perPage = $('#showItemsPerPage').val();
        $.ajax({
            type:"POST",
            url: "offers",
            data: {'listShortBy' : listShortBy , 'perPage' : perPage , 'currentPage' : currentPage},
            success: function(result) { 
                //console.log(result); 
                $('#productsListView').html(result);
            }
        });
        
    });


    $(".addProdCart").on("click", function() {
        
        var productId = $(this).attr('data-productId');
        var productPriceType = $(this).attr('data-productPriceType');

        $.ajax({
            type:"POST",
            url: "<?php echo base_url().'add-to-cart'?>",
            data: {'productId' : productId , 'productPriceType' : productPriceType , 'prodQuantity' : 1},
            success: function(result) { 
                //console.log(result); 
                if(result > 0)
                    $('#cartTotalProd').html(result);
            }
        });
    });

});

</script>

