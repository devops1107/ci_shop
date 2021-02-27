<div id="content">
	<section class="list-page inner-content brands-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading">
                        <span><?=$this->lang->line('brands')?></span>
                    </div>
                    <div class="product-list">
                        <div class="row">
                            <?php 
                            foreach ($product_brands as $brands) 
                            { ?>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <div class="product-box">
                                        <form action="shop" method="post">
                                        <input type="hidden" name="brandId" value="<?=base64_encode($brands['brand_id']);?>">
                                        <button class="btn-cate">
                                        <div class="img">
                                            <img src="<?=UPLOAD_URL?>/brands/<?php echo $brands['brand_image']; ?>" alt="<?php echo $brands['brand_title']; ?>" />
                                        </div>
                                        <div class="product-detail">
                                           <h4><?php echo $brands['brand_title']; ?></h4>
                                        </div>
                                        </button>
                                        </form>
                                    </div>
                                </div>
                                <?php 
                            } 

                            echo (empty($product_brands)) ? '<div class="col-sm-12">No product found !</div>' : '';  ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    
</div>