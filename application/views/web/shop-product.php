<title>Baaba.de</title>
<?php  $login_data = $this->site_santry->get_web_auth_data(); ?>

<script src="assets/web/js/jquery-1.12.4.min.js"></script>

<div id="content">

    <section class="list-page">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="sidebar">
                        <div class="filter-list">
                            <h4><?=$this->lang->line('brands')?></h4>
                            <div class="check-list">
                                <?php 
                                foreach ($product_brands as $brands) { ?>
                                    <label class="label_check" for="checkbox-<?php echo $brands['brand_id']; ?>">
                                        <input name="searchByBrands[]" class="search_by_brands" id="checkbox-<?php echo $brands['brand_id']; ?>" <?php echo ($searchPrdBrand == $brands['brand_id']) ? 'checked' : '';?> value="<?php echo $brands['brand_id']; ?>" type="checkbox"><?php echo $brands['brand_title']; ?> (<?php echo $brands['total_products']; ?>)
                                    </label>
                                    <?php
                                }   ?>
                            </div> 
                        </div>
                        <div class="filter-list">
                            <h4><?=$this->lang->line('categories')?></h4>
                            <div class="check-list">
                                <?php
                                foreach ($product_categories as $productCate) { ?>
                                    <label class="label_check" for="checkbox-<?php echo $productCate['category_id']; ?>">
                                        <input name="searchByCate[]" class="search_by_cate" id="checkbox-<?php echo $productCate['category_id']; ?>" <?php echo ($searchPrdCat == $productCate['category_id']) ? 'checked' : '';?> value="<?php echo $productCate['category_id']; ?>" type="checkbox"><?php echo $productCate['category_title']; ?> (<?php echo $productCate['total_products']; ?>)
                                    </label>
                                    <?php
                                }   ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="filter-section clearfix">
                        <div class="top-left-filter">
                            <div class="filter-group">
                                <label><?=$this->lang->line('short_by')?> : </label>
                                <select id="listShortBy" class="selectpicker show-tick form-control">
                                    <option value="short-popular"><?=$this->lang->line('popular')?></option>
                                    <option value="short-new"><?=$this->lang->line('new')?></option>
                                </select>
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
                    <div id="productsListView">
                        <?php 
                        $this->load->view('web/shop-product-ajax');    
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
</div>

<audio id="audio" src="<?php echo base_url().'assets/landing/Trm_Prc6.wav'?>"></audio>
<script type="text/javascript">

$(document).ready(function(){

    $('.search_by_brands , .search_by_cate').change(function() {

        var i = 0;
        var searchArrCat = [];
        $('.search_by_cate:checked').each(function () {
            searchArrCat[i++] = $(this).val();
        });

        var i = 0;
        var searchArrBrand = [];
        $('.search_by_brands:checked').each(function () {
            searchArrBrand[i++] = $(this).val();
        });

        var prdSubCategory = $('.prdSubCategory').attr('data-subcate');
        //console.log(searchArrBrand);

        var listShortBy = $('#listShortBy').val();
        var perPage = $('#showItemsPerPage').val();

        $.ajax({
            type:"POST",
            url: "search-shop",
            data: {'listShortBy' : listShortBy , 'perPage' : perPage , 'searchArrBrand' : searchArrBrand , 'searchArrCat' : searchArrCat},
            success: function(result) { 
                //console.log(result);
                $('#productsListView').html(result);

            }
        });
        
    });

});

</script>