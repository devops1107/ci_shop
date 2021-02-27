<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="format-detection" content = "telephone=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Alibaba-nuts.online</title>
    
    <!-- favicon icon -->
    <link rel="icon" href="<?=WEB_PATH?>/images/alibaba_logo.png">
    <!-- Ioons -->
    <link href="<?=WEB_PATH?>/css/font-awesome.min.css" rel="stylesheet"> <!-- font-awesome.min css -->
    
    <!-- CSS Stylesheet -->
    <link href="<?=WEB_PATH?>/css/bootstrap.min.css" rel="stylesheet"> <!-- bootstrap.min css -->
    <link href="<?=WEB_PATH?>/css/slick.css" rel="stylesheet"> <!-- slick css -->
    <link href="<?=WEB_PATH?>/css/slick-theme.css" rel="stylesheet"> <!-- slick-theme css -->
    <link href="<?=WEB_PATH?>/css/style.css" rel="stylesheet"> <!-- style css -->
    <link href="<?=WEB_PATH?>/css/css3.css" rel="stylesheet"> <!-- css3 style -->
</head>
<body class="inner-page">
    <div id="wrapper">
    
        <!-- ****************** Header  Section ****************** -->
        <header id="header">
            <div class="top-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="header-top-left">
                                <!-- <ul class="list-inline">
                                    <?php
                                    $language = get_site_language();

                                    ?>
                                    <li>
                                        <select class="selectpicker" data-width="fit" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                            <option <?=($language=='english')?"selected":""; ?> value="<?=base_url('home/change-language/english')?>">English</option>
                                            <option <?=($language=='german')?"selected":""; ?> value="<?=base_url('home/change-language/german')?>">German</option>
                                            <option <?=($language=='turkish')?"selected":""; ?> value="<?=base_url('home/change-language/turkish')?>">Turkish</option>             
                                        </select>
                                    </li>
                                </ul> -->
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="header-top-right">
                                <ul class="list-inline">
                                    <?php
                                    if($this->site_santry->is_web_login())
                                    { 
                                        $user_login_details = $this->site_santry->get_web_auth_data();
                                        ?>
                                        <li><a></i>Welcome <?php print $user_login_details['user_name']." (".$user_login_details['email'].")"; ?></a></li>
                                        <li>
                                            <div class="btn-group" role="group">
                                                <a href="#" id="loginlinks" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i> <?=$this->lang->line('my_profile')?></a>
                                                <div class="dropdown-menu" aria-labelledby="loginlinks">
                                                    <a class="dropdown-item" href="<?=base_url('my-profile')?>"><?=$this->lang->line('my_profile')?></a>
                                                    <a class="dropdown-item" href="<?=base_url('my-cart')?>"><?=$this->lang->line('my_cart')?></a>
                                                    <a class="dropdown-item" href="<?=base_url('my-orders')?>"><?=$this->lang->line('my_orders')?></a>
                                                    <a class="dropdown-item" href="<?=base_url('logout')?>"><?=$this->lang->line('logout')?></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="cart-itemsd">
                                            <a href="<?=base_url()?>my-cart" class="cart-icon"><i class="fa fa-shopping-basket" aria-hidden="true"></i><span class="badge" id="cartTotalProd"><?php echo (!empty($this->session->userdata('total_cart_products'))) ? $this->session->userdata('total_cart_products') : 0;?></span></a>
                                        </li>
                                        <?php
                                    }
                                    else
                                    {   ?>
                                        <li>
                                            <a href="<?=base_url()?>my-cart" class="cart-icon"><i class="fa fa-shopping-basket" aria-hidden="true"></i><span class="badge" id="cartTotalProd"><?php echo (!empty($this->session->userdata('total_cart_products'))) ? $this->session->userdata('total_cart_products') : 0;?></span></a>
                                        </li>
                                        <li><a href="<?php echo base_url();?>login"><i class="fa fa-user" aria-hidden="true"></i><?=$this->lang->line('login')?>/<?=$this->lang->line('register')?></a></li>
                                        <?php
                                    }

                                    $language = get_site_language();

                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="navbar navbar-default">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?=base_url('home')?>"><img src="<?=WEB_PATH?>/images/alibaba_logo.png" alt="Logo" /></a>
                    </div> 
                    <div class="navbar-collapse collapse">-
                        <ul class="nav navbar-nav">
                            <li><a href="<?=base_url('home')?>"> <?=$this->lang->line('home')?></a> </li>
                            <li><a href="<?=base_url('shop')?>"> <?=$this->lang->line('shop')?></a> </li>
                            <li><a href="<?=base_url('offers')?>"> <?=$this->lang->line('offers')?></a> </li>
                            <li><a href="<?=base_url('brands')?>"> <?=$this->lang->line('brands')?></a> </li>
                            <li><a href="<?=base_url('contact-us')?>"> <?=$this->lang->line('contact_us')?></a> </li>
                            <li class="cart-items">
                                <a href="javascript:void(0);" class="cart-icon"><i class="fa fa-language" aria-hidden="true"></i></a>
                                <div class="cart-table">
                    
                                    <div class="checkout">
                                        <a href="<?=base_url('home/change-language/english')?>" class="btn btn-primary btn-block">English</a>
                                        <a href="<?=base_url('home/change-language/german')?>" class="btn btn-primary btn-block">German</a>
                                        <a href="<?=base_url('home/change-language/turkish')?>" class="btn btn-primary btn-block">Turkish</a>
                                    </div>
                                </div> 
                            </li>
                            
                            <li class="searchBox">
                                <a href="javascript:void(0);" class="search-boxSmall"><i class="fa fa-search"></i> <?=$this->lang->line('search')?></a>
                                <div class="search-box">
                                    <div class="icon"><i class="icon icon-search"></i></div>
                                    <div class="search-view">
                                        <form method="post" action="<?=base_url('search')?>">
                                        <input type="text" name="search_key" placeholder="<?=$this->lang->line('search_term')?>…" />
                                        <button type="submit" value=""><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </li> 
                        </ul>
                    </div>
                </div>
            </div>
        </header>

