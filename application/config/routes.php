<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/


$route['default_controller'] = 'Landing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['home/change-language/(:any)'] = 'home/change_language/$1';


$route['verifyEmail/(:any)/(:any)'] = 'user/verifyEmail/$1/$2';

################################################################################################
###################################### For Admin ############################################### 
##############################################################################################
################Web Panel#############
$route['home'] = 'landing/index';
$route['homes'] = 'landing/index';
//$route['about-us'] = 'home/about_us';
$route['(about-us|privacy-policy|terms-and-conditions)'] = 'home/get_web_page_info/$1';
$route['contact-us'] = 'home/contact_us';

$route['faq'] = 'home/get_faqs';

$route['search'] = 'home/get_web_search_products';

$route['shop'] = 'home/get_products';
$route['search-shop'] = 'home/search_products';

$route['category/(:any)'] = 'home/search_category/$1';

$route['shop/(:any)'] = 'home/search_products/$1';
$route['shop/(:any)/(:any)'] = 'home/get_products/$1/$2';
$route['shop/(:any)/(:any)/(:any)'] = 'home/get_products/$1/$2/$3';

$route['product-detail/(:any)'] = 'home/get_product_detail/$1';
$route['get-product-price'] = 'home/get_product_price';

$route['brands'] = 'home/get_brands';
$route['brands/(:any)'] = 'home/search_products/$1';

$route['offers'] = 'home/get_offer_products';


#--------------- User Panel --------------------------

$route['login'] = 'user/login';
$route['register'] = 'user/register';

$route['forgot-password'] = 'user/forgot_password';
$route['forgot-password-vefication/(:any)/(:any)'] = 'user/verify_forgot_password/$1/$2';

$route['place-order'] = 'user/place_order';
$route['my-profile'] = 'user/get_profile';
$route['change-password'] = 'user/change_password';
$route['my-orders'] = 'user/get_orders';
$route['order-details/(:any)'] = 'user/get_order_details/$1';

$route['logout'] = 'user/logout';

$route['add-to-cart'] = 'user/update_product_cart';
$route['remove-from-cart'] = 'user/remove_product_cart';
$route['update-cart-qty'] = 'user/update_cart_product_qty';
$route['my-cart'] = 'user/get_cart_details';
$route['checkout'] = 'user/checkout';
$route['generate-order-pdf/(:any)'] = 'user/generate_order_pdf/$1';

$route['save-billing-info'] = 'user/update_billing_details';
$route['save-shipping-info'] = 'user/update_shipping_details';


$route['testemail'] = 'user/testemail';

#-------------------- User Panel Ends------------------------------




#-------------------- Admin Panel Start------------------------------
# admin profile : - 

$route['admin'] = 'admin/welcome/login';
$route['admin/login'] = 'admin/welcome/login';

$route['admin/edit-profile'] = 'admin/users/admin_edit_profile';
$route['admin/dashboard'] = 'admin/users/dashboard';


$route['mail-verification/(:any)/(:any)'] = 'home/mail_verify/$1/$2';
$route['admin/forgot-password'] = 'admin/welcome/forgot_password';
$route['admin/forgot-password-vefication/(:any)/(:any)'] = 'admin/welcome/verify_forgot_password/$1/$2';

$route['admin/change-password'] = 'admin/users/change_password';
$route['admin/change-password'] = 'admin/users/change_password';
$route['admin/profile'] = 'admin/users/profile';

# Brands : -
$route['admin/brands'] = 'admin/Brands';
$route['admin/add-brand'] = 'admin/Brands/add_brand';
$route['admin/edit-brand/(:any)'] = 'admin/Brands/edit_brand/$1';
$route['admin/delete-brand/(:any)'] = 'admin/Brands/deleteBrands/$1';

# Categories : -
$route['admin/categories'] = 'admin/Categories';
$route['admin/add-category'] = 'admin/Categories/add_category';
$route['admin/edit-category/(:any)'] = 'admin/Categories/edit_category/$1';
$route['admin/delete-category/(:any)'] = 'admin/Categories/deleteCategories/$1';

# Sub Categories : -
$route['admin/subcategories'] = 'admin/Categories/subCategories';
$route['admin/add-subcategory'] = 'admin/Categories/add_subcategory';
$route['admin/edit-subcategory/(:any)'] = 'admin/Categories/edit_subcategory/$1';
$route['admin/delete-subcategory/(:any)'] = 'admin/Categories/deleteSubcategory/$1';

# Products : -
$route['admin/products'] = 'admin/Products';
$route['admin/add-product'] = 'admin/Products/add_product';
$route['admin/edit-product/(:any)'] = 'admin/Products/edit_product/$1';
$route['admin/delete-product/(:any)'] = 'admin/Products/deleteProduct/$1';
$route['admin/subcategory-list'] = 'admin/Categories/get_sub_category_list';

#product Discount
$route['admin/get-product-discount'] = 'admin/Products/get_product_discount';
$route['admin/add-product-discount'] = 'admin/Products/add_product_discount';
$route['admin/delete-product-discount'] = 'admin/Products/delete_product_discount';

# User Management :-
$route['admin/users'] = 'admin/Users/users_list';
$route['admin/edit-user/(:any)'] = 'admin/Users/edit_user/$1';

# Order Management :-
$route['admin/orders'] = 'admin/Orders';
$route['admin/order-details/(:any)'] = 'admin/Orders/get_order_details/$1';

# Cms pages : -
$route['admin/cms-pages/(:any)'] = 'admin/Website/edit_cms_pages/$1';
$route['admin/cms-pages/versions/(:any)'] = 'admin/Users/versions_list/$1/$2';


$route['admin/faq'] = 'admin/Website/faq/faq';
$route['admin/faq/add-faq'] = 'admin/Website/add_faq/faq';
$route['admin/edit-faq/(:any)'] = 'admin/Website/edit_faq/$1/faq';
$route['admin/delete-faq/(:any)'] = 'admin/Website/delete_faq/$1/faq';


$route['admin/edit-contact'] = 'admin/Website/edit_contact';

# Customer Reviews
$route['admin/contacts'] = 'admin/Contact';

# Banners
$route['admin/banners'] = 'admin/banners/index';
$route['admin/add-banner'] = 'admin/banners/add_banner';
$route['admin/edit-banner/(:any)'] = 'admin/banners/edit_banner/$1';
$route['admin/delete-banner/(:any)'] = 'admin/banners/delete_banner/$1';

# Banners
$route['admin/slider'] = 'admin/slider/index';
$route['admin/add-slider'] = 'admin/slider/add_slider';
$route['admin/edit-slider/(:any)'] = 'admin/slider/edit_slider/$1';
$route['admin/delete-slider/(:any)'] = 'admin/slider/delete_slider/$1';

$route['admin/middle-banner'] = 'admin/banners/edit_middle_banner';
$route['admin/tax-setting'] = 'admin/Website/tax_setting';


$route['admin/logout'] = 'admin/welcome/logout';

###################### For Web View START ######################

$route['privacy-policy'] = 'web-view/home/privacyPolicyWebView';
$route['terms-conditions'] = 'web-view/home/termsConditionsWebView';
/*$route['forgot-password/(:any)/(:any)'] = 'home/verify_forgot_password_user/$1/$2';
$route['forgot-password'] = 'home/verify_forgot_password_user';
$route['partner-forgot-password/(:any)/(:any)'] = 'home/verify_forgot_password_partner/$1/$2';
$route['partner-forgot-password'] = 'home/verify_forgot_password_partner';*/
/* 
$route['series/(:any)/(:any)'] = 'series/index';
 */