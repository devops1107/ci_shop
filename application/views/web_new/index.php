<title>Baaba.de</title>
<?php  $login_data = $this->site_santry->get_web_auth_data(); ?>


<!-- ****************** Banner Section	****************** -->
<section id="banner">
    <div class="banner-slider">
    <?php 
    foreach ($banner_image as $banners) 
    {   ?>
        <div class="slide-div">
        	<div class="banner-content">
        		<div class="container">
                    <div class="row">
                    	<div class="row-md-height">
                            <div class="col-md-6 col-md-push-6 col-md-height">
                                <img src="<?=UPLOADS_PATH?>/banners/<?php echo $banners['banner_image']; ?>" alt="Baaba De - Banner" class="img-responsive" />
                            </div>
                            <div class="col-md-6 col-md-pull-6 col-md-height">
                                <h1><?=$banners['banner_heading']?></h1>
                                <p><?=$banners['banner_sub_heading']?></p>
                                <a href="<?php echo base_url('shop');?>" class="btn btn-default mt-30"><?=$this->lang->line('buy_now')?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
    }   ?>
    </div>
</section>

<div id="content">
    <!-- ****************** Offers Section ****************** -->
    <section class="offers-first section-block">
        <div class="container">
            <div class="offers-slider">
                <?php 
                foreach ($slider_image as $sliders) 
                {   ?>
                    <div class="col-md-3">
                        <div class="offer-box">
                            <img src="<?=UPLOADS_PATH?>/slider/<?php echo $sliders['slider_image']; ?>" alt="Baaba De" />
                        </div>
                    </div>
                    <?php 
                }   ?>
            </div>
           
        </div>
        
    </section> 
    <!-- ****************** Offers Section ****************** -->
    <section class="offers section-block mt-0">
        <div class="container">
            <div class="heading"><span><?=$this->lang->line('offer_item')?></span></div>
            <div class="offers-slider-1">
                <?php 
                foreach ($recent_products as $products) 
                {
                    if($products['single_product_offer'] > 0)
                    {
                        $product_price = $products['single_product_price']-$products['single_product_offer'];
                        $is_offer_dis = '';
                    }
                    elseif($products['master_carton_offer'] > 0)
                    {
                        $product_price = $products['master_carton_price']-$products['master_carton_offer'];
                        $is_offer_dis = ' / Master Carton';
                    }
                    elseif($products['palette_price'] > 0)
                    {
                        $product_price = $products['palette_price']-$products['palette_offer'];
                        $is_offer_dis = ' / Palette';
                    }
                    ?>
                    <div class="col-md-4">
                        <div class="offer-box">
                            <a href="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                            <img src="<?=UPLOADS_PATH?>/products/<?php echo $products['product_image']; ?>" alt="<?php echo $products['product_title']; ?>" />
                            <div class="hover-block">
                                <h3 class="offer-title"><?php echo $products['product_title']; ?> <div>( € <?= $product_price.$is_offer_dis; ?> )</div> </h3>
                            </div>
                            </a>
                        </div>
                    </div>
                    <?php 
                }   ?>
            </div>
            <div class="text-center mt-40">
            <a href="<?php echo base_url('offers');?>" class="btn btn-info"><?=$this->lang->line('view_all')?> ></a>
        </div>
        </div>
        
    </section> 
    <!-- ****************** Upcoming Offers Section	****************** -->
    <section class="upcoming-offers">
    	<div class="overlay">
            <?php 
            if(!empty($middle_banner))
            {   ?>
                <img class="middle-banner" src="<?=UPLOADS_PATH?>/banners/<?php echo $middle_banner['banner_image']; ?>" alt="Baaba De" />
                <?php
            }
            else
            {   ?>
                <div class="black-overlay"></div>
                <div class="container">
                    <div class="detail">
                        <span class="title"><?=$this->lang->line('upcoming')?></span>
                        <h2><?=$this->lang->line('looking_for_gift')?></h2>
                        <p><?=$this->lang->line('gift_for_everyone')?></p>
                        <a href="<?php echo base_url('shop');?>" class="btn btn-detail"><?=$this->lang->line('gift_for_everyone')?></a>
                    </div>
                </div>
                <?php
            }   ?>
    	</div>
    </section>
    <!-- ****************** Featured Products Section ****************** -->
    <section class="feature-products section-block">
        <div class="container">
            <div class="heading"><span><?=$this->lang->line('recently_added')?></span></div>
            <div class="product-list">
                <div class="recent-slider">
                    <?php 
                    foreach ($recent_products as $products) 
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
                        <div class="col-md-3 col-sm-6">
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
                                
                            </div>
                            </a>
                        </div>
                        <?php
                    }   ?>
                </div>
            </div>
        </div>
    </section>
    <!-- ****************** Best Sellers Section ****************** -->
    <section class="category-sec section-block">
        <div class="container">
            <div class="heading"><span><?=$this->lang->line('categories')?></span></div>
            <div class="product-list categories-slider">
                <?php 
                foreach ($product_categories as $prdCate) 
                { ?>
                    <div class="product-box">
                        <form action="shop" method="post">
                        <input type="hidden" name="catId" value="<?=base64_encode($prdCate['category_id']);?>">
                        <button class="btn-cate">
                        <div class="img">
                            <img src="<?=UPLOAD_URL?>/categories/<?php echo $prdCate['category_image']; ?>" alt="" />
                        </div>
                        <div class="product-detail">
                           <h4><?php echo $prdCate['category_title']; ?></h4>
                        </div>
                        </button>
                        </form>
                    </div>
                    <?php 
                }   ?>
            </div>
        </div>
    </section> 
    <!-- ****************** Our Brands Section ****************** -->

    <section class="our-brands section-block">
        <div class="container">
            <div class="heading"><span><?=$this->lang->line('our_brand')?></span></div>
            <div class="brands-slider">
                <?php 
                foreach ($product_brands as $brands) 
                { ?>
                    <form action="shop" method="post">
                    <input type="hidden" name="brandId" value="<?=base64_encode($brands['brand_id']);?>">
                    <button class="btn-cate">
                    <div class="img">
                        <img class="brand-img" src="<?=UPLOAD_URL?>/brands/<?php echo $brands['brand_image']; ?>" alt="<?php echo $brands['brand_title']; ?>" />
                    </div>
                    <div class="product-detail">
                       <h4><?php echo $brands['brand_title']; ?></h4>
                    </div>
                    </button>
                    </form>
                    <?php 
                }   ?>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url().'assets/web/js/jquery-1.12.4.min.js'; ?>"></script>
<script type="text/javascript">
 
$(document).ready(function(){    
    
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

$(document).ready(function(){
        $("body").removeClass("inner-page");
    });
</script>
<style> 
#content { padding-bottom: 0px;}

</style>