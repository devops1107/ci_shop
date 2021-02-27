<title>Alibaba Nnuts Online</title>
<?php $login_data = $this->site_santry->get_web_auth_data(); ?>


<!-- ****************** Banner Section	****************** -->
<div class="banner">
    <?php

    if(!empty($slider_image) && !empty($banner_image)){
        $sliders = $slider_image[0];
        $banners = $banner_image[0];
    ?>

    <div class="banner_background"
             style="background-image:url('<?= LAND_PATH ?>images/cover.jpg')">
<!--             style="opacity:30%;background-image:url('../images/cover.jpg')">-->
    </div>
    <div class="container fill_height">
        <div class="row fill_height">
            <div class="banner_product_image">
                <!--<img style="width: 134%"
                                                   src="<?/*= UPLOADS_PATH */?>/banners/<?php /*echo $banners['banner_image']; */?>"
                                                   alt="Baaba De - Banner" class="img-responsive"/>-->
            </div>
            <div class="col-lg-5 offset-lg-4 fill_height banner-title">
                <div class="banner_content">
                    <h1 class="banner_text"style="opacity: 100%">Alibaba Nnuts Online</h1>
                    <div class="banner_price" style="opacity: 0%"><span style="opacity: 0%">&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;</div>
                    <div class="banner_product_name"style="opacity: 0%">Alibaba Nnuts Online</div>
                    <div class="button banner_button"><a href="<?= base_url('shop') ?>">Shop Now</a></div>
                </div>
            </div>
        </div>
    </div>
    <?php }
    ?>
</div>

<div class="characteristics">
    <div class="container">
        <div class="row">

            <div class="col-lg-3 col-md-6 char_col">
                <a class="clc" href="<?= base_url('shop') ?>">
                    <img class="box-img box-style" src="<?= LAND_PATH ?>images/fancandy_2.png" alt="">
                </a>
            </div>

            <div class="col-lg-3 col-md-6 char_col">
                <a class="clc" href="<?= base_url('shop') ?>">
                    <img class="box-img box-style" src="<?= LAND_PATH ?>images/dISPLAY-gUMS.png" alt="">
                </a>
            </div>

            <div class="col-lg-3 col-md-6 char_col">
                <a class="clc" href="<?= base_url('shop') ?>">
                <img class="box-img box-style" src="<?= LAND_PATH ?>images/fini-bags.png" alt="">
                </a>
            </div>

            <div class="col-lg-3 col-md-6 char_col">
                <a class="clc" href="<?= base_url('shop') ?>">
                <img class="box-img box-style" src="<?= LAND_PATH ?>images/burger-buns.png" alt="">
                </a>
            </div>
        </div>
    </div>
</div>

<div class="deals_featured">
    <div class="container">
        <div class="row">
            <div class="col d-flex flex-lg-row flex-column align-items-center justify-content-start">

                <div class="featured" style="width: 100%">
                    <div class="tabbed_container">
                        <div class="tabs">
                            <ul class="clearfix">
                                <li class="active">Featured</li>
                                <li>On Sale</li>
                                <li>Best Rated</li>
                            </ul>
                            <div class="tabs_line"><span></span></div>
                        </div>
                        <div class="product_panel panel active">
                            <div class="featured_slider slider">
                                <?php
                                    foreach ($top_products as $products) {
                                    if ($products['single_product_price'] == 0)
                                        continue;

                                    $rand_float = rand(0, 1);
                                    $star_rating = rand(3, 5);
                                       if ($products['single_product_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['single_product_price'];
                                                $offer_price = $products['single_product_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';

                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['single_product_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Stück (Stk.)';
                                            $prd_price_type = 1;
                                        }
                                       elseif ($products['master_carton_price'] > 0) {
                                            if ($products['master_carton_offer'] > 0) {
                                                $product_old_price = $products['master_carton_price'];
                                                $offer_price = $products['master_carton_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['master_carton_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Umkarton (Kolli)';
                                            $prd_price_type = 2;
                                        }
                                       elseif ($products['palette_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['palette_price'];
                                                $offer_price = $products['palette_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['palette_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Display';
                                            $prd_price_type = 3;
                                        }
                                        ?>
                                    <div class="featured_slider_item" data-productId="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                        <input hidden id="product_<?php echo base64_encode($products['product_id']); ?>" value="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                        <div class="border_active"></div>
                                        <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                              <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                <img src="<?= UPLOADS_PATH ?>/products/<?php echo $products['product_image']; ?>"
                                                     alt=""></div>
                                            <div class="product_content fix-title" style="margin-top: 1.3rem">
                                                <div class="product_price discount">€<?php echo($products['single_product_price'] - $products['single_product_offer'])?><span>&nbsp;&nbsp;&nbsp;€<del><?php echo $products['single_product_price']?></del></span></div>
                                                <div class="product_name">
                                                    <div class="col-1">
                                                        <a href="<?php echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?>">
                                                            <span style="text-align: center">&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[0]; ?><br>&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[1]; ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product_extras">
                                                    <button data-productId="<?php echo base64_encode($products['product_id']); ?>"
                                                            data-productPriceType="<?php echo base64_encode($prd_price_type); ?>"
                                                            class="addProdCart product_cart_button">Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product_fav"><i class="fas fa-heart"></i></div>
                                            <ul class="product_marks">
                                                <?php //echo $is_offer_dis; ?>
                                                <li class="product_mark product_new">new</li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                            <div class="featured_slider_dots_cover"></div>
                        </div>
                        <div class="product_panel panel">
                            <div class="featured_slider slider">
                                <?php
                                   foreach ($sal_products as $products) {
                                    if ($products['single_product_price'] == 0)
                                        continue;

                                    $rand_float = rand(0, 1);
                                    $star_rating = rand(3, 5);
                                    if ($products['single_product_price'] > 0) {
                                        if ($products['single_product_offer'] > 0) {
                                            $product_old_price = $products['single_product_price'];
                                            $offer_price = $products['single_product_offer'];
                                            $product_price = $product_old_price - $offer_price;
                                            $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';

                                        } else {
                                            $offer_price = 0;
                                            $product_price = $products['single_product_price'];
                                            $is_offer_dis = '';
                                        }
                                        $is_offer_pkc = ' / Stück (Stk.)';
                                        $prd_price_type = 1;
                                    }
                                    elseif ($products['master_carton_price'] > 0) {
                                        if ($products['master_carton_offer'] > 0) {
                                            $product_old_price = $products['master_carton_price'];
                                            $offer_price = $products['master_carton_offer'];
                                            $product_price = $product_old_price - $offer_price;
                                            $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                        } else {
                                            $offer_price = 0;
                                            $product_price = $products['master_carton_price'];
                                            $is_offer_dis = '';
                                        }
                                        $is_offer_pkc = ' / Umkarton (Kolli)';
                                        $prd_price_type = 2;
                                    }
                                    elseif ($products['palette_price'] > 0) {
                                        if ($products['single_product_offer'] > 0) {
                                            $product_old_price = $products['palette_price'];
                                            $offer_price = $products['palette_offer'];
                                            $product_price = $product_old_price - $offer_price;
                                            $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                        } else {
                                            $offer_price = 0;
                                            $product_price = $products['palette_price'];
                                            $is_offer_dis = '';
                                        }
                                        $is_offer_pkc = ' / Display';
                                        $prd_price_type = 3;
                                    }
                                    ?>
                                    <div class="featured_slider_item" data-productId="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                        <input hidden id="product_<?php echo base64_encode($products['product_id']); ?>" value="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                        <div class="border_active"></div>
                                        <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                            <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                <img src="<?= UPLOADS_PATH ?>/products/<?php echo $products['product_image']; ?>"
                                                     alt=""></div>
                                            <div class="product_content fix-title" style="margin-top: 1.3rem">
                                                <div class="product_price discount">€<?php echo($products['single_product_price'] - $products['single_product_offer'])?><span>&nbsp;&nbsp;&nbsp;€<del><?php echo $products['single_product_price']?></del></span></div>
                                                <div class="product_name">
                                                    <div class="col-1">
                                                        <a href="<?php echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?>">
                                                            <span style="text-align: center">&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[0]; ?><br>&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[1]; ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product_extras">
                                                    <button data-productId="<?php echo base64_encode($products['product_id']); ?>"
                                                            data-productPriceType="<?php echo base64_encode($prd_price_type); ?>"
                                                            class="addProdCart product_cart_button">Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product_fav"><i class="fas fa-heart"></i></div>
                                            <ul class="product_marks">
                                                <?php //echo $is_offer_dis; ?>
                                                <li class="product_mark product_new">new</li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                            <div class="featured_slider_dots_cover"></div>
                        </div>
                        <div class="product_panel panel">
                            <div class="featured_slider slider">
                                <?php
                                foreach ($best_products as $products) {
                                    if ($products['single_product_price'] == 0)
                                        continue;

                                    $rand_float = rand(0, 1);
                                    $star_rating = rand(3, 5);
                                    if ($products['single_product_price'] > 0) {
                                        if ($products['single_product_offer'] > 0) {
                                            $product_old_price = $products['single_product_price'];
                                            $offer_price = $products['single_product_offer'];
                                            $product_price = $product_old_price - $offer_price;
                                            $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';

                                        } else {
                                            $offer_price = 0;
                                            $product_price = $products['single_product_price'];
                                            $is_offer_dis = '';
                                        }
                                        $is_offer_pkc = ' / Stück (Stk.)';
                                        $prd_price_type = 1;
                                    }
                                    elseif ($products['master_carton_price'] > 0) {
                                        if ($products['master_carton_offer'] > 0) {
                                            $product_old_price = $products['master_carton_price'];
                                            $offer_price = $products['master_carton_offer'];
                                            $product_price = $product_old_price - $offer_price;
                                            $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                        } else {
                                            $offer_price = 0;
                                            $product_price = $products['master_carton_price'];
                                            $is_offer_dis = '';
                                        }
                                        $is_offer_pkc = ' / Umkarton (Kolli)';
                                        $prd_price_type = 2;
                                    }
                                    elseif ($products['palette_price'] > 0) {
                                        if ($products['single_product_offer'] > 0) {
                                            $product_old_price = $products['palette_price'];
                                            $offer_price = $products['palette_offer'];
                                            $product_price = $product_old_price - $offer_price;
                                            $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                        } else {
                                            $offer_price = 0;
                                            $product_price = $products['palette_price'];
                                            $is_offer_dis = '';
                                        }
                                        $is_offer_pkc = ' / Display';
                                        $prd_price_type = 3;
                                    }
                                    ?>
                                    <div class="featured_slider_item" data-productId="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                        <input hidden id="product_<?php echo base64_encode($products['product_id']); ?>" value="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                        <div class="border_active"></div>
                                        <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                            <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                <img src="<?= UPLOADS_PATH ?>/products/<?php echo $products['product_image']; ?>"
                                                     alt=""></div>
                                            <div class="product_content fix-title" style="margin-top: 1.3rem">
                                                <div class="product_price discount">€<?php echo($products['single_product_price'] - $products['single_product_offer'])?><span>&nbsp;&nbsp;&nbsp;€<del><?php echo $products['single_product_price']?></del></span></div>
                                                <div class="product_name">
                                                    <div class="col-1">
                                                        <a href="<?php echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?>">
                                                            <span style="text-align: center">&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[0]; ?><br>&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[1]; ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product_extras">
                                                    <button data-productId="<?php echo base64_encode($products['product_id']); ?>"
                                                            data-productPriceType="<?php echo base64_encode($prd_price_type); ?>"
                                                            class="addProdCart product_cart_button">Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="product_fav"><i class="fas fa-heart"></i></div>
                                            <ul class="product_marks">
                                                <?php //echo $is_offer_dis; ?>
                                                <li class="product_mark product_new">new</li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                            <div class="featured_slider_dots_cover"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="banner_2">
    <?PHP
    if (!empty($slider_image)){
        $index = rand(0,1);
        if (!empty($slider_image[$index]))
            $sliders = $slider_image[1];
    ?>
    <div class="banner_2_background"
         style="background-image:url('<?= LAND_PATH ?>images/cover-2.jpg')">
    </div>
        <!--style="opacity:30%;background-image:url('<?/*= UPLOADS_PATH */?>/slider/<?php /*echo $sliders['slider_image']; */?>')"></div>-->

        <div class="banner_2_container">
        <div class="banner_2_dots"></div>

        <div class="owl-carousel owl-theme banner_2_slider">

            <div class="owl-item">
                <?php
                $rand_img = rand(0, 2);
                $banners = $banner_image[$rand_img];
                ?>
                <div class="banner_2_item">
                    <div class="container fill_height">
                        <div class="row fill_height">
                            <div class="col-lg-4 col-md-6 fill_height">
                                <div class="banner_2_content">
                                    <div class="banner_2_category" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    <div class="banner_2_title" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="banner_2_text" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="button banner_2_button"><a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 fill_height">
                                <div class="banner_2_image_container">
                                    <div class="banner_2_image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="owl-item">
                <?php
                $rand_img = rand(0, 2);
                $banners = $banner_image[$rand_img];
                ?>
                <div class="banner_2_item">
                    <div class="container fill_height">
                        <div class="row fill_height">
                            <div class="col-lg-4 col-md-6 fill_height">
                                <div class="banner_2_content">
                                    <div class="banner_2_category" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    <div class="banner_2_title" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="banner_2_text" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <!--<div class="rating_r rating_r_4 banner_2_rating">
                                        <i></i><i></i><i></i><i></i><i></i>
                                    </div>-->
                                    <div class="button banner_2_button"><a
                                            href="<?= base_url('shop') ?>"><?= $this->lang->line('shop') ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 fill_height">
                                <div class="banner_2_image_container">
                                    <div class="banner_2_image"><img style="width: 114%"
                                                                     src="<?= UPLOADS_PATH ?>/banners/<?php echo $banners['banner_image']; ?>"
                                                                     alt="Baaba De - Banner" class="img-responsive"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="owl-item">
                <?php
                $rand_img = rand(0, 2);
                $banners = $banner_image[$rand_img];
                ?>
                <div class="banner_2_item">
                    <div class="container fill_height">
                        <div class="row fill_height">
                            <div class="col-lg-4 col-md-6 fill_height">
                                <div class="banner_2_content">
                                    <div class="banner_2_category" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    <div class="banner_2_title" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="banner_2_text" style="opacity: 0%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="button banner_2_button"><a
                                            href="<?= base_url('shop') ?>"><?= $this->lang->line('shop') ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6 fill_height">
                                <div class="banner_2_image_container">
                                    <div class="banner_2_image"><img style="width: 114%"
                                                                     src="<?= UPLOADS_PATH ?>/banners/<?php echo $banners['banner_image']; ?>"
                                                                     alt="Baaba De - Banner" class="img-responsive"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php }?>
</div>

<div class="new_arrivals">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="tabbed_container">
                    <div class="tabs clearfix tabs-right">
                        <div class="new_arrivals_title">Hot New Arrivals</div>
                        <ul class="clearfix">
                            <li class="active">Featured</li>
                            <li>Jelly</li>
                            <li>BURGER BUNS</li>
                        </ul>
                        <div class="tabs_line"><span></span></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" style="z-index:1;">
                            <div class="product_panel panel active">
                                <div class="arrivals_slider slider">
                                    <?php
                                        foreach ($top_a_products as $products) {
                                        $rand_float = rand(0, 1);
                                        $star_rating = rand(3, 5);

                                        if($products['single_product_price'] == 0)
                                            continue;

                                        if ($products['single_product_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['single_product_price'];
                                                $offer_price = $products['single_product_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';

                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['single_product_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Stück (Stk.)';
                                            $prd_price_type = 1;
                                        } elseif ($products['master_carton_price'] > 0) {
                                            if ($products['master_carton_offer'] > 0) {
                                                $product_old_price = $products['master_carton_price'];
                                                $offer_price = $products['master_carton_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['master_carton_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Umkarton (Kolli)';
                                            $prd_price_type = 2;
                                        } elseif ($products['palette_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['palette_price'];
                                                $offer_price = $products['palette_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['palette_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Display';
                                            $prd_price_type = 3;
                                        }
                                        ?>
                                        <div class="arrivals_slider_item" data-productId="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                            <div class="border_active"></div>
                                            <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                                <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                    <img src="<?= UPLOADS_PATH ?>/products/<?php echo $products['product_image']; ?>"
                                                         alt=""></div>
                                                <div class="product_content fix-title">
                                                    <div class="product_price discount">€<?php echo($products['single_product_price'] - $products['single_product_offer'])?><span>&nbsp;&nbsp;&nbsp;€<del><?php echo $products['single_product_price']?></del></span></div>
                                                    <div class="product_name">
                                                        <div class="col-1">
                                                            <a href="<?php echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?>">
                                                                <span style="text-align: center">&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[0]; ?><br>&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[1]; ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product_extras">
                                                        <button href="<?php echo base64_encode($products['product_id']); ?>"
                                                                class="product_cart_button">Add to Cart
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="product_fav"><i class="fas fa-heart"></i></div>
                                                <ul class="product_marks">
                                                    <?php
                                                        //echo $is_offer_dis;
                                                    ?>
                                                    <li class="product_mark product_new">new</li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>

                                </div>
                                <div class="arrivals_slider_dots_cover"></div>
                            </div>
                            <div class="product_panel panel ">
                                <div class="arrivals_slider slider">
                                    <?php
                                    foreach ($best_products as $products) {
                                        $rand_float = rand(0, 1);
                                        $star_rating = rand(3, 5);

                                        if($products['single_product_price'] == 0)
                                            continue;

                                        if ($products['single_product_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['single_product_price'];
                                                $offer_price = $products['single_product_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';

                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['single_product_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Stück (Stk.)';
                                            $prd_price_type = 1;
                                        } elseif ($products['master_carton_price'] > 0) {
                                            if ($products['master_carton_offer'] > 0) {
                                                $product_old_price = $products['master_carton_price'];
                                                $offer_price = $products['master_carton_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['master_carton_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Umkarton (Kolli)';
                                            $prd_price_type = 2;
                                        } elseif ($products['palette_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['palette_price'];
                                                $offer_price = $products['palette_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['palette_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Display';
                                            $prd_price_type = 3;
                                        }
                                        ?>
                                        <div class="arrivals_slider_item" data-productId="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                            <div class="border_active"></div>
                                            <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                                <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                    <img src="<?= UPLOADS_PATH ?>/products/<?php echo $products['product_image']; ?>"
                                                         alt=""></div>
                                                <div class="product_content fix-title">
                                                    <div class="product_price discount">€<?php echo($products['single_product_price'] - $products['single_product_offer'])?><span>&nbsp;&nbsp;&nbsp;€<del><?php echo $products['single_product_price']?></del></span></div>
                                                    <div class="product_name">
                                                        <div class="col-1">
                                                            <a href="<?php echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?>">
                                                                <span style="text-align: center">&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[0]; ?><br>&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[1]; ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product_extras">
                                                        <button href="<?php echo base64_encode($products['product_id']); ?>"
                                                                class="product_cart_button">Add to Cart
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="product_fav"><i class="fas fa-heart"></i></div>
                                                <ul class="product_marks">
                                                    <?php
                                                    //echo $is_offer_dis;
                                                    ?>
                                                    <li class="product_mark product_new">new</li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                                <div class="arrivals_slider_dots_cover"></div>
                            </div>
                            <div class="product_panel panel ">
                                <div class="arrivals_slider slider">
                                    <?php
                                    foreach ($sal_products as $products) {
                                        $rand_float = rand(0, 1);
                                        $star_rating = rand(3, 5);

                                        if($products['single_product_price'] == 0)
                                            continue;

                                        if ($products['single_product_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['single_product_price'];
                                                $offer_price = $products['single_product_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';

                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['single_product_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Stück (Stk.)';
                                            $prd_price_type = 1;
                                        } elseif ($products['master_carton_price'] > 0) {
                                            if ($products['master_carton_offer'] > 0) {
                                                $product_old_price = $products['master_carton_price'];
                                                $offer_price = $products['master_carton_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['master_carton_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Umkarton (Kolli)';
                                            $prd_price_type = 2;
                                        } elseif ($products['palette_price'] > 0) {
                                            if ($products['single_product_offer'] > 0) {
                                                $product_old_price = $products['palette_price'];
                                                $offer_price = $products['palette_offer'];
                                                $product_price = $product_old_price - $offer_price;
                                                $is_offer_dis = '<li class="product_mark product_discount"><span>Off<br>€ ' . $offer_price . '</span></li>';
                                            } else {
                                                $offer_price = 0;
                                                $product_price = $products['palette_price'];
                                                $is_offer_dis = '';
                                            }
                                            $is_offer_pkc = ' / Display';
                                            $prd_price_type = 3;
                                        }
                                        ?>
                                        <div class="arrivals_slider_item" data-productId="<?php echo base_url()."product-detail/".base64_encode($products['product_id']); ?>">
                                            <div class="border_active"></div>
                                            <div class="product_item discount d-flex flex-column align-items-center justify-content-center text-center">
                                                <div class="product_image d-flex flex-column align-items-center justify-content-center">
                                                    <img src="<?= UPLOADS_PATH ?>/products/<?php echo $products['product_image']; ?>"
                                                         alt=""></div>
                                                <div class="product_content fix-title">
                                                    <div class="product_price discount">€<?php echo($products['single_product_price'] - $products['single_product_offer'])?><span>&nbsp;&nbsp;&nbsp;€<del><?php echo $products['single_product_price']?></del></span></div>
                                                    <div class="product_name">
                                                        <div class="col-1">
                                                            <a href="<?php echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?>">
                                                                <span style="text-align: center">&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[0]; ?><br>&nbsp;&nbsp;&nbsp;<?php echo str_split($products['product_title'], 20)[1]; ?></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="product_extras">
                                                        <button href="<?php echo base64_encode($products['product_id']); ?>"
                                                                class="product_cart_button">Add to Cart
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="product_fav"><i class="fas fa-heart"></i></div>
                                                <ul class="product_marks">
                                                    <?php
                                                    //echo $is_offer_dis;
                                                    ?>
                                                    <li class="product_mark product_new">new</li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>

                                </div>
                                <div class="arrivals_slider_dots_cover"></div>
                            </div>
                        </div>
<!--                        <div class="col-lg-3">-->
<!--                            --><?php
//                            $rand_deal = rand(0, 2);
//                            $products = $deals_products[$rand_deal]; ?>
<!--                            <div class="arrivals_single clearfix">-->
<!--                                <div class="d-flex flex-column align-items-center justify-content-center">-->
<!--                                    <div class="arrivals_single_image">-->
<!--                                        <img src="--><?//= UPLOADS_PATH ?><!--/products/--><?php //echo $products['product_image']; ?><!--"-->
<!--                                             alt="">-->
<!--                                    </div>-->
<!--                                    <div class="arrivals_single_content">-->
<!--                                        <div class="arrivals_single_category">-->
<!--                                            <a href="--><?php //echo base_url() . "product-detail/" . base64_encode($products['product_id']); ?><!--">-->
<!--                                                <span style="text-align: center">--><?php //echo $products['product_title']; ?><!--</span>-->
<!--                                            </a></div>-->
<!--                                        <div class="arrivals_single_name_container clearfix">-->
<!--                                            <div class="arrivals_single_price text-right">$379</div>-->
<!--                                        </div>-->
<!--                                        <div class="rating_r rating_r_4 arrivals_single_rating">-->
<!--                                            <i></i><i></i><i></i><i></i><i></i></div>-->
<!--                                        <form action="#">-->
<!--                                            <button class="arrivals_single_button">Add to Cart</button>-->
<!--                                        </form>-->
<!--                                    </div>-->
<!--                                    <div class="arrivals_single_fav product_fav active"><i class="fas fa-heart"></i>-->
<!--                                    </div>-->
<!--                                    <ul class="arrivals_single_marks product_marks">-->
<!--                                        <li class="arrivals_single_mark product_mark product_new">new</li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="audio" src="<?php echo base_url().'assets/landing/Trm_Prc6.wav'?>"></audio>
<!--</div>-->
<script>
    var addCart = '<?php echo base_url().'add-to-cart'?>'
</script>
<script src="<?php echo base_url().'assets/web/js/jquery-1.12.4.min.js'; ?>"></script>
<script type="text/javascript">
    function play() {
        var audio = document.getElementById("audio");
        audio.play();
    }
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
                    if(result > 0){
                        play();
                        $('#cartTotalProd').html(result);
                    }
                }
            });
        });
    });

</script>
