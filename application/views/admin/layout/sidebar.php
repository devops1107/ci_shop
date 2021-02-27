<?php 
//echo "hello";die;
$uri1	=	$this->uri->segment(2);
$uri2	=	$this->uri->segment(3);
$uri3	=	$this->uri->segment(4);
$user_id    = $this->site_santry->get_auth_data('id');
$user_type    = $this->site_santry->get_auth_data('user_type');
 
$all_categories=false;
$dashboard=false;
$banners=false;
$partners=false;
$userslist=false;
$terms_condition=false;
$rooms_rules=false;
$stores_rules=false;
$privacy_policy=false;
$about_us=false;
$contact_details=false;
$faqs=false;
$cms_pages=false;
$sliderlist=false;
$middle_banner=false;
$home_widgets=false;

if($uri1=='slider' || $uri1=='add-slider' || $uri1=='edit-slider' || $uri1=='middle-banner' || $uri1=='banners' || $uri1=='add-banner' || $uri1=='edit-banners'){
    $home_widgets = true;
}

if($uri1=='slider' || $uri1=='add-slider' || $uri1=='edit-slider'){
    $sliderlist = true;
}

if($uri1=='middle-banner'){
    $middle_banner = true;
}

if($uri1=='banners' || $uri1=='add-banner' || $uri1=='edit-banners'){
    $banners = true;
}

if($uri1=='terms-conditions' || $uri1=='edit-terms-conidtion' || ($uri2=="versions" && $uri3=="terms-conditions") ){
    $terms_condition = true;
}
if($uri1=='rooms-rules-conditions' || $uri1=='edit-terms-conidtion' || ($uri2=="versions" && $uri3=="rooms-rules-conditions") ){
    $rooms_rules = true;
}

if($uri1=='stores-rules-conditions' || $uri1=='edit-terms-conidtion' || ($uri2=="versions" && $uri3=="stores-rules-conditions") ){
    $stores_rules = true;
}

if($uri1=='about-us' || $uri1=='edit-about-us' || ($uri2=="versions" && $uri3=="about-us")){
    $about_us = true;
}

if($uri1=='contact-details'){
    $contact_details = true;
}

if($uri1=='faq' || $uri2=='add-faq' || $uri1=='edit-faq'){
    $faqs = true;
}	

if($uri1=='privacy-policy' || $uri1=='edit-privacy-policy' || ($uri2=="versions" && $uri3=="privacy-policy")){
    $privacy_policy = true;
}

if($uri1=='terms-conditions' || $uri1=='privacy-policy' || $uri1=='contact-details' || $uri1=='about-us' || $uri1=='faq' || $uri2=='add-faq' || $uri1=='edit-faq' || $uri2=="versions"){
    $cms_pages = true;
}

if($uri1=='all-users' || $uri1=='add-user' || $uri1=='edit-user' || $uri1=='view-user'){
	$userslist = true;
}


if($uri2=='dashboard'){
    $dashboard = true;
}

?>

<!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                        <li>
                            <!-- User Profile-->
                            <div class="user-profile text-center dropdown p-3">
								<div class="user-pic">
								<?php 
									$profileImg = $this->site_santry->get_auth_data('profile_image');
									if(is_file(UPLOAD_PHYSICAL_PATH.'customers/'.$profileImg) && $profileImg!='')
									{
										$profile_image = UPLOAD_URL.'customers/'.$profileImg;
									}else{
										$profile_image = ADMIN_PATH.'images/users/default_user.jpg';
									} 
								?>
									<img src="<?=$profile_image?>" alt="users" class="rounded-circle" width="50" />
								</div>
								<div class="user-content hide-menu">
									<a href="javascript:void(0)" class="mt-2 dropdown-toggle" id="Userdd" role="button" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<h5 class="mb-0 user-name mt-2"><?=$this->site_santry->get_auth_data('first_name').' '.$this->site_santry->get_auth_data('last_name');?></h5>
									</a><!--
									<div class="dropdown-menu dropdown-menu-left" aria-labelledby="Userdd">
										<a class="dropdown-item" href="<?=base_url('admin/profile')?>"><i class="ti-user m-r-5 m-l-5"></i> <?= $this->lang->line('sidebar_my_profile'); ?></a>
										<a class="dropdown-item" href="javascript:void(0)"><i class="ti-email m-r-5 m-l-5"></i> <?= $this->lang->line('sidebar_inbox'); ?></a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="<?=base_url('logout')?>"><i class="fa fa-power-off m-r-5 m-l-5"></i> <?= $this->lang->line('sidebar_logout'); ?></a>
									</div>-->
								</div>
							</div>
                            <!-- End User Profile-->
                        </li>
                        <li class="sidebar-item <?=($uri1=='dashboard')?'selected':''?>">
							<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/dashboard')?>" aria-expanded="false">
								<i class="mdi mdi-view-dashboard"></i>
								<span class="hide-menu">Dashboard</span>
							</a>
						</li>
						 
						<?php 
						if($user_type=='admin')
						{
							?>
							<li class="sidebar-item <?=($uri1 == 'users' || $uri1 =="edit-user")?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/users')?>" aria-expanded="false">
									<i class="fa fa-users"></i>
									<span class="hide-menu">User Management</span>
								</a>
							</li>
							<li class="sidebar-item <?=($uri1 == 'brands' || $uri1 =="add-brand" || $uri1 =="edit-brand")?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/brands')?>" aria-expanded="false">
									<i class="mdi mdi-format-list-numbers"></i>
									<span class="hide-menu">Brands</span>
								</a>
							</li>
							<li class="sidebar-item <?=($uri1 == 'categories' || $uri1 =="add-category" || $uri1 =="edit-category")?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/categories')?>" aria-expanded="false">
									<i class="mdi mdi-format-list-numbers"></i>
									<span class="hide-menu">Category Management</span>
								</a>
							</li>
							 <li class="sidebar-item <?=($uri1 == 'subcategories' || $uri1 =="add-subcategory" || $uri1 =="edit-subcategory")?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/subcategories')?>" aria-expanded="false">
									<i class="mdi mdi-format-list-numbers"></i>
									<span class="hide-menu">Sub Category Management</span>
								</a>
							</li>
							<li class="sidebar-item <?=($uri1 == 'products' || $uri1 =="edit-product")?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/products')?>" aria-expanded="false">
									<i class="mdi mdi-format-list-numbers"></i>
									<span class="hide-menu">Product Management</span>
								</a>
							</li>

							<li class="sidebar-item <?=($uri1 == 'orders' || $uri1 =="order_details")?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/orders')?>" aria-expanded="false">
									<i class="mdi mdi-format-list-numbers"></i>
									<span class="hide-menu">Order Management</span>
								</a>
							</li>

							<li class="sidebar-item <?=($uri1 == 'contacts')?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/contacts')?>" aria-expanded="false">
									<i class="mdi mdi-format-list-numbers"></i>
									<span class="hide-menu">Contact Management</span>
								</a>
							</li>

							<li class="sidebar-item <?=$home_widgets?'selected':''?>">
								<a class="sidebar-link has-arrow waves-effect waves-dark"  href="javascript:void(0)" aria-expanded="<?=$home_widgets?'true':'false'?>">
									<i class="mdi mdi-newspaper"></i>
									<span class="hide-menu">Home Widgets</span>
								</a>
								
								<ul aria-expanded="false" class="collapse first-level <?=$cms_pages?'in':''?>">
									<li class="sidebar-item <?=($banners)?'active':''?>">
										<a href="<?=base_url('admin/banners')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> Banner</span>
										</a>
									</li>
									<li class="sidebar-item <?=($sliderlist)?'active':''?>">
										<a href="<?=base_url('admin/slider')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> Image Slider</span>
										</a>
									</li>
									<li class="sidebar-item <?=($middle_banner)?'active':''?>">
										<a href="<?=base_url('admin/middle-banner')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> Middile Banner</span>
										</a>
									</li>
								</ul>
							</li>

							<!--  CMS Pages Menu-->
							<li class="sidebar-item <?=$cms_pages?'selected':''?>">
								<a class="sidebar-link has-arrow waves-effect waves-dark"  href="javascript:void(0)" aria-expanded="<?=$cms_pages?'true':'false'?>">
									<i class="mdi mdi-newspaper"></i>
									<span class="hide-menu">CMS Pages</span>
								</a>
								
								<ul aria-expanded="false" class="collapse first-level <?=$cms_pages?'in':''?>">
									<li class="sidebar-item <?=($terms_condition)?'active':''?>">
										<a href="<?=base_url('admin/cms-pages/terms-conditions')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> Terms & Condition </span>
										</a>
									</li>
									<li class="sidebar-item <?=($privacy_policy)?'active':''?>">
										<a href="<?=base_url('admin/cms-pages/privacy-policy')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> Privacy Policy </span>
										</a>
									</li>
									<li class="sidebar-item <?=($about_us)?'active':''?>">
										<a href="<?=base_url('admin/cms-pages/about-us')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> About Us</span>
										</a>
									</li>
									<li class="sidebar-item <?=($contact_details)?'active':''?>">
										<a href="<?=base_url('admin/edit-contact')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> Contact Us</span>
										</a>
									</li>
									<li class="sidebar-item <?=($faqs)?'active':''?>">
										<a href="<?=base_url('admin/faq')?>" class="sidebar-link">
											<i class="fa fa-cog"></i>
											<span class="hide-menu"> FAQ</span>
										</a>
									</li>
								</ul>
							</li>
							<li class="sidebar-item <?=($uri1 == 'tax-setting')?'selected':''?>">
								<a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('admin/tax-setting')?>" aria-expanded="false">
									<i class="fa fa-cog"></i>
									<span class="hide-menu">Tax Setting</span>
								</a>
							</li>
							<!-- End CMS Pages Menu-->
						<?php } ?>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<div class="page-wrapper">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
      