<!DOCTYPE html>
<html lang="en">
<head>
    <title>Alibaba-nuts.online</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="OneTech shop project">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>styles/bootstrap4/bootstrap.min.css">
    <link href="<?= LAND_PATH ?>plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet"
          type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>plugins/OwlCarousel2-2.2.1/owl.carousel.css">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>plugins/OwlCarousel2-2.2.1/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>plugins/slick-1.8.0/slick.css">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>styles/main_styles.css?v=1.11">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>styles/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?= LAND_PATH ?>styles/custom.css?v= 1.12">
</head>
<body>
<div class="super_container">
    <header class="header">
        <div class="top_bar" style="background-color: #6C0202;">
            <div class="container">
                <div class="row">
                    <div class="col d-flex flex-row">
                        <div class="top_bar_contact_item">
                            <div class="top_bar_icon"><img src="<?= LAND_PATH ?>images/phone.png" alt=""></div>
                            <span style="color: #fefefe"><a style="color: #fefefe" href="tel:<?= $contact_details['mobile_no']; ?>"><?= $contact_details['mobile_no']; ?></span>
                        </div>
                        <div class="top_bar_contact_item">
                            <div class="top_bar_icon"><img src="<?= LAND_PATH ?>images/mail.png" alt=""></div>
                            <span style="color: #fefefe"><a style="color: #fefefe" href="mailto:<?= $contact_details['email']; ?>"><?= $contact_details['email']; ?></a></span>
                        </div>
                        <?php
                        $language = get_site_language();
                        ?>
                        <div class="top_bar_content ml-auto">
                            <div class="top_bar_menu">
                                <ul class="standard_dropdown top_bar_dropdown">
                                    <li>
                                        <a style="color: #fefefe"
                                           href="<?= base_url('home/change-language/english') ?>">English<i
                                                    class="fas fa-chevron-down"></i></a>
                                        <ul>
                                            <li><a href="<?= base_url('home/change-language/english') ?>">English</a>
                                            </li>
                                            <li><a href="<?= base_url('home/change-language/german') ?>">German</a></li>
                                            <li><a href="<?= base_url('home/change-language/turkish') ?>">Turkish</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="top_bar_user">
                                <div class="user_icon"><img src="<?= LAND_PATH ?>images/user.svg" alt=""></div>
                                <div><a style="color: #fefefe"
                                        href="<?php echo base_url(); ?>login"><?= $this->lang->line('login') ?></a>
                                </div>
                                <div><a style="color: #fefefe"
                                        href="<?php echo base_url(); ?>register"><?= $this->lang->line('register') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header_main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-sm-3 col-3 order-1">
                        <div class="logo_container">
                            <div class="logo"><a class="navbar-brand" href="<?= base_url('home') ?>"><img
                                            width="166.1rem" src="<?= WEB_PATH ?>/images/alibaba_logo.png" alt="Logo"/></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 order-lg-2 order-3 text-lg-left text-right">
                        <div class="header_search">
                            <div class="header_search_content">
                                <div class="header_search_form_container">
                                    <form action="<?= base_url('search') ?>" class="header_search_form clearfix">
                                        <input type="search" required="required" class="header_search_input"
                                               placeholder="<?= $this->lang->line('search_term') ?>…">
                                        <div class="custom_dropdown">
                                            <div class="custom_dropdown_list">
                                                <span class="custom_dropdown_placeholder clc">All Categories</span>
                                                <i class="fas fa-chevron-down"></i>
                                                <ul class="custom_list clc">
                                                    <li><a class="clc" href="<?= base_url('shop') ?>"> Bubble Gum</a>
                                                    </li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>"> Jelly</a></li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>"> Nuts & Dried
                                                            Fruits</a></li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>">CHIPS</a></li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>">BURGER BUNS</a>
                                                    </li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>">Nuts & Dried
                                                            Fruits</a></li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>">Turkish
                                                            delight</a></li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>">TOYS</a></li>
                                                    <li><a class="clc" href="<?= base_url('shop') ?>">OLIVES</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <button type="submit" class="header_search_button trans_300" value="Submit"><img
                                                    src="<?= LAND_PATH ?>images/search.png" alt=""></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-9 order-lg-3 order-2 text-lg-left text-right">
                        <div class="wishlist_cart d-flex flex-row align-items-center justify-content-end">
                            <div class="cart">
                                <div class="cart_container d-flex flex-row align-items-center justify-content-end">
                                    <div class="cart_icon">
                                        <img src="<?= LAND_PATH ?>images/cart.png" alt="">
                                        <div class="cart_count">
                                            <span id="cartTotalProd"><?php echo (!empty($this->session->userdata('total_cart_products'))) ? $this->session->userdata('total_cart_products') : 0; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main_nav">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="main_nav_content d-flex flex-row">

                            <div class="cat_menu_container">
                                <div class="cat_menu_title d-flex flex-row align-items-center justify-content-start">
                                    <div class="cat_burger"><span></span><span></span><span></span></div>
                                    <div class="cat_menu_text">categories</div>
                                </div>
                                <ul class="cat_menu">
                                    <li><a class="clc" href="<?= base_url('shop') ?>"> Bubble Gum</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>"> Jelly</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>"> Nuts & Dried Fruits</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>">CHIPS</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>">BURGER BUNS</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>">Nuts & Dried Fruits</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>">Turkish delight</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>">TOYS</a></li>
                                    <li><a class="clc" href="<?= base_url('shop') ?>">OLIVES</a></li>
                                </ul>
                            </div>
                            <div class="main_nav_menu ml-auto">
                                <ul class="standard_dropdown main_nav_dropdown">
                                    <li><a href="<?= base_url('home') ?>"> <?= $this->lang->line('home') ?></a></li>
                                    <li class="hassubs">
                                        <a href="<?= base_url('shop') ?>"> <?= $this->lang->line('shop') ?></a>

                                    </li>
                                    <li class="hassubs">
                                        <a href="<?= base_url('offers') ?>"> <?= $this->lang->line('offers') ?></a>

                                    </li>
                                    <li><a href="<?= base_url('brands') ?>"> <?= $this->lang->line('brands') ?></a></li>
                                    <li>
                                        <a href="<?= base_url('contact-us') ?>"> <?= $this->lang->line('contact_us') ?></a>
                                    </li>
                                </ul>
                            </div>

                            <div class="menu_trigger_container ml-auto">
                                <div class="menu_trigger d-flex flex-row align-items-center justify-content-end">
                                    <div class="menu_burger">
                                        <div class="menu_trigger_text">menu</div>
                                        <div class="cat_burger menu_burger_inner">
                                            <span></span><span></span><span></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="page_menu">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="page_menu_content">
                            <div class="page_menu_search">
                                <form method="post" action="<?= base_url('search') ?>">
                                    <input type="search" required="required" class="page_menu_search_input"
                                           placeholder="<?= $this->lang->line('search_term') ?>…">
                                </form>
                            </div>
                            <ul class="page_menu_nav">
                                <li class="page_menu_item has-children">
                                    <a href="#">Language<i class="fa fa-angle-down"></i></a>
                                    <ul class="page_menu_selection">
                                        <li><a href="<?= base_url('home/change-language/english') ?>">English</a></li>
                                        <li><a href="<?= base_url('home/change-language/german') ?>">German</a></li>
                                        <li><a href="<?= base_url('home/change-language/turkish') ?>">Turkish</a></li>
                                    </ul>
                                </li>
                                <li class="page_menu_item">
                                    <a href="<?= base_url('home') ?>"> <?= $this->lang->line('home') ?></a>
                                </li>
                                <li class="page_menu_item has-children">
                                    <a href="<?= base_url('shop') ?>"> <?= $this->lang->line('shop') ?></a>
                                </li>
                                <li class="page_menu_item has-children">
                                    <a href="<?= base_url('offers') ?>"> <?= $this->lang->line('offers') ?></a>
                                </li>
                                <li class="page_menu_item has-children">
                                    <a href="<?= base_url('brands') ?>"> <?= $this->lang->line('brands') ?></a>
                                </li>
                                <li class="page_menu_item"><a
                                            href="<?= base_url('contact-us') ?>"> <?= $this->lang->line('contact_us') ?></a></a>
                                </li>
                            </ul>
                            <div class="menu_contact">
                                <div class="menu_contact_item">
                                    <div class="menu_contact_icon"><img src="<?= LAND_PATH ?>images/phone_white.png"
                                                                        alt=""></div>
                                    <span style="color: #fefefe"><a style="color: #fefefe" href="tel:<?= $contact_details['mobile_no']; ?>"><?= $contact_details['mobile_no']; ?></span>
                                </div>
                                <div class="menu_contact_item">
                                    <div class="menu_contact_icon"><img src="<?= LAND_PATH ?>images/mail_white.png"
                                                                        alt="">&nbsp;&nbsp;&nbsp;<span style="color: #fefefe"><a style="color: #fefefe" href="mailto:<?= $contact_details['email']; ?>"><?= $contact_details['email']; ?></a></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>