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

$route['default_controller'] = 'landing';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

################################################################################################
######################################## For Api ############################################### 
################################################################################################

$route['Wooapi/(:any)'] = "Wooapi";
$route['Wooapi/(:any)/(:any)'] = "Wooapi";
$route['Wooapi/(:any)/(:any)/(:any)'] = "Wooapi";
$route['Wooapi/(:any)/(:any)/(:any)'] = "Wooapi";

$route['Driverapi/(:any)'] = "Driverapi";
$route['Driverapi/(:any)/(:any)'] = "Driverapi";
$route['Driverapi/(:any)/(:any)/(:any)'] = "Driverapi";

/* $route['Userapi/(:any)'] = "Userapi";
$route['Userapi/(:any)/(:any)'] = "Userapi";
$route['Userapi/(:any)/(:any)/(:any)'] = "Userapi"; */
//$route['verify-user/(:any)/(:any)'] = "Userapi/verify_user/$1/$2";

$route['Businessapi/(:any)'] = "Businessapi";
$route['Businessapi/(:any)/(:any)'] = "Businessapi";
$route['Businessapi/(:any)/(:any)/(:any)'] = "Businessapi";

$route['verifyEmail/(:any)/(:any)'] = 'welcome/verifyEmail/$1/$2';
$route['forgot-password-vefication/(:any)/(:any)'] = 'admin/welcome/verify_forgot_password/$1/$2';
################################################################################################
###################################### For Admin ############################################### 
##############################################################################################
################Web Panel#############
$route['home'] = 'web/landing/index';
$route['home/about-us'] = 'web/home/about_us';
$route['home/privacy-policy'] = 'web/home/privacy_policy';
$route['home/terms-conditions'] = 'web/home/terms';
$route['home/news'] = 'web/home/my_match_news';
$route['home/news-detail/(:any)'] = 'web/home/my_match_news_page/$1';
$route['home/login'] = 'web/home/login';
$route['home/register'] = 'web/home/register';
$route['home/all-matches'] = 'web/home/all_matches';
$route['home/logout'] = 'web/home/logout';
$route['home/add-news-comment'] = 'web/home/add_news_comments';
$route['contact-us'] = 'web/home/contact_us';
$route['home/player-team-of-the-month/(:any)'] = 'web/home/player_team_of_the_month/$1';
$route['home/team-of-the-month'] = 'web/home/team_of_the_month';
$route['home/player-of-the-month'] = 'web/home/player_of_the_month';
$route['home/rooms-list'] = 'web/home/rooms_list';
$route['home/rooms-detail/(:any)'] = 'web/home/rooms_detail/$1';
$route['like-news-comment'] = 'web/dashboard/like_news_comment';
$route['home/change-language/(:any)'] = 'web/home/change_language/$1';
$route['home/become-partner'] = 'web/home/become_partner';
$route['home/partner-login'] = 'web/home/partner_login';
$route['home/partner-forgot-password'] = 'web/home/partner_forgot_password';
$route['home/recharge-credit'] = 'web/dashboard/recharge_credit';
$route['home/my-profile'] = 'web/dashboard/my_profile';
$route['home/account-details'] = 'web/dashboard/account_details';
$route['home/my-match'] = 'web/dashboard/my_matches';
$route['home/money-request/(:any)'] = 'web/dashboard/moneyRequest/$1';

# admin profile : - 

$route['admin'] = 'admin/welcome/login';
$route['partner-login'] = 'admin/welcome/partner_login';
//$route['verifyEmail'] = 'welcome/verifyEmail';

$route['admin/choose-winner'] = 'admin/winner/choose_winner';

$route['admin/notifications'] = 'admin/notification';

$route['admin/edit-profile'] = 'admin/users/admin_edit_profile';
$route['admin/dashboard'] = 'admin/users/dashboard';
$route['admin/updateCatchupConfiguration'] = 'admin/users/updateCatchupConfiguration';
$route['logout'] = 'admin/welcome/logout';
$route['mail-verification/(:any)/(:any)'] = 'home/mail_verify/$1/$2';
$route['admin/forgot-password'] = 'admin/welcome/forgot_password';
$route['admin/forgot-password-vefication/(:any)/(:any)'] = 'admin/welcome/verify_forgot_password/$1/$2';
$route['admin/change-password'] = 'admin/users/change_password';
$route['admin/change-password'] = 'admin/users/change_password';
$route['admin/updateOrderCollectStatus'] = 'admin/Business/updateOrderCollectStatus';
$route['admin/updateCatchupRewardsStatus'] = 'admin/Business/updateCatchupRewardsStatus';
$route['admin/updatepublishStatus'] = 'admin/Business/updatepublishStatus';
$route['admin/updateBusinessStatus'] = 'admin/Business/updateBusinessStatus';
$route['admin/addRemoveFacilityFromShop'] = 'admin/Business/addRemoveFacilityFromShop';

$route['admin/profile'] = 'admin/users/profile';

$route['admin/mark-notification-read'] = 'admin/Users/markNotificationsRead';

# Categories : -
$route['admin/categories'] = 'admin/Categories';
$route['admin/add-category'] = 'admin/Categories/add_category';
$route['admin/edit-category/(:any)'] = 'admin/Categories/edit_category/$1';

#Partners
$route['admin/all-partners-request/(:any)'] = 'admin/Partners/partner_register_request_list/$1';
$route['admin/accept-partner-request/(:any)'] = 'admin/Partners/accept_partner_register_request/$1';
$route['admin/reject-partner-request/(:any)'] = 'admin/Partners/reject_partner_register_request/$1';
$route['admin/all-partners'] = 'admin/Partners/partners_list';
# Promocodes : -
$route['admin/promocodes'] = 'admin/promocodes';
$route['admin/add-promocode'] = 'admin/promocodes/add_promocode';
$route['admin/edit-promocode/(:any)'] = 'admin/promocodes/edit_promocode/$1';
$route['admin/user-promocodes/(:any)'] = 'admin/promocodes/user_promocodes/$1';
$route['admin/promocode-export'] = 'admin/promocodes/promocodeExportExcelFormat';

$route['admin/manage-tokens'] = 'admin/Categories/manage_tokens';
$route['admin/token-amount-listing'] = 'admin/Categories/token_amount_listing';
$route['admin/subscription-amount-listing'] = 'admin/Categories/subscription_amount_listing';
$route['admin/add-token'] = 'admin/Categories/add_token';
$route['admin/add-subscription'] = 'admin/Categories/add_subscription';
$route['admin/edit-token-amount/(:any)'] = 'admin/Categories/edit_token/$1';
$route['admin/edit-subscription-amount/(:any)'] = 'admin/Categories/edit_subscription/$1';

$route['admin/user-subscriptions'] = 'admin/Categories/user_subscriptions';
$route['admin/user-tokens/?(:any)?'] = 'admin/Categories/user_tokens/$1';

# Rooms : -
$route['admin/rooms/(:any)'] = 'admin/Rooms/index/$1';
$route['admin/add-room'] = 'admin/Rooms/add_room';
$route['admin/copy-room/(:any)'] = 'admin/Rooms/copy_room/$1';
$route['admin/edit-room/(:any)'] = 'admin/Rooms/edit_room/$1';
$route['admin/room-ticket-purchase/(:any)/?(:any)?'] = 'admin/Rooms/roomsTicketsPurchased/$1/$2';
$route['admin/room-direct-purchase/(:any)'] = 'admin/Rooms/roomsDirectPurchased/$1';
$route['admin/room-winner/(:any)'] = 'admin/Rooms/roomsWinner/$1';
$route['admin/room-secondary-winners/(:any)'] = 'admin/Rooms/roomsSecondaryWinner/$1';
$route['admin/room-prize-distribution-status/(:any)'] = 'admin/Rooms/roomsPrizeDistributionStatus/$1';
$route['admin/send-entry-key/(:any)'] = 'admin/Rooms/send_entry_key/$1';
$route['admin/complete-event/(:any)'] = 'admin/Rooms/complete_event/$1';
$route['admin/completed-event-details/(:any)'] = 'admin/Rooms/completed_event_details/$1';
$route['admin/search-user-name/(:any)'] = 'admin/Rooms/search_user_name/$1';
$route['admin/joined-users/(:any)'] = 'admin/Rooms/roomsTicketsPurchased/$1';

$route['admin/room-purchased/(:any)'] = 'admin/Rooms/roomPurchased/$1';
$route['admin/direct-purchase-user-details/(:any)'] = 'admin/Rooms/roomPurchaseUserDetails/$1';
$route['admin/process-direct-purchase-prize/(:any)'] = 'admin/Rooms/process_direct_purchase_prize/$1';
$route['admin/deliver-direct-purchase-prize/(:any)'] = 'admin/Rooms/deliver_direct_purchase_prize/$1';

#Stores
$route['admin/stores'] = 'admin/Stores';
$route['admin/add-store-product'] = 'admin/Stores/addStoreProduct';
$route['admin/edit-store-product/(:any)'] = 'admin/Stores/updateStoreProduct/$1';

#Store Purchase
$route['admin/store-purchases'] = 'admin/Stores/store_purchases';
$route['admin/store-purchase-details/(:any)'] = 'admin/Stores/store_purchase_details/$1';
$route['admin/process-store-purchase/(:any)'] = 'admin/Stores/process_store_purchase/$1';
$route['admin/deliver-store-purchase/(:any)'] = 'admin/Stores/deliver_store_purchase/$1';

#Room Winners
$route['admin/winners'] = 'admin/Winner/winners_list';
$route['admin/winner-details/(:any)'] = 'admin/Winner/winners_details/$1';
$route['admin/process-winner/(:any)'] = 'admin/Winner/process_winner/$1';
$route['admin/deliver-winner/(:any)'] = 'admin/Winner/deliver_winner/$1';


# Admin Users Managment: -
$route['admin/all-users'] = 'admin/Users/users_list';
$route['admin/add-user'] = 'admin/Users/add_user';
$route['admin/view-user/(:any)'] = 'admin/Users/view_user/$1';
$route['admin/edit-user/(:any)'] = 'admin/Users/edit_user/$1';
$route['admin/edit-subcategory-details/(:any)'] = 'admin/Categories/edit_subcategoryDetails/$1';
$route['admin/edit-subcategory-details/(:any)/(:any)'] = 'admin/Categories/edit_subcategoryDetails/$1/$2';

$route['admin/user-purchase-ticket/(:any)/?(:any)?'] = 'admin/Users/user_purchase_tickets/$1/$2';
$route['admin/user-direct-purchase/(:any)/?(:any)?'] = 'admin/Users/user_purchase_tickets/$1/$2';
$route['admin/user-token-history/(:any)'] = 'admin/Users/user_token_history/$1';
$route['admin/user-irs-information/(:any)'] = 'admin/Users/user_irs_information/$1';
$route['admin/add-irs-information/(:any)'] = 'admin/Users/add_irs_document/$1';
$route['admin/update-irs-status/(:any)'] = 'admin/Users/update_irs_status/$1';
$route['admin/delete-irs-document/(:any)'] = 'admin/Users/delete_irs_document/$1';
$route['admin/user-joined-rooms/(:any)'] = 'admin/Users/user_joined_rooms/$1';
$route['admin/user-winnings/(:any)'] = 'admin/Users/userWinnings/$1';
$route['admin/winning-amount-request/(:any)'] = 'admin/WinningRequests/index/$1';


$route['admin/irs-required-users'] = 'admin/Winner/irsRequiredUsersList';


# Cms pages : -
$route['admin/cms-pages/(:any)'] = 'admin/Users/edit_cms_pages/$1';
$route['admin/cms-pages/versions/(:any)'] = 'admin/Users/versions_list/$1/$2';
// /$route['admin/cms-pages/(:any)'] = 'admin/Users/users_list/$1/$2';
$route['admin/add-user'] = 'admin/Users/add_user';
$route['admin/edit-user/(:any)'] = 'admin/Users/edit_user/$1';

$route['admin/faq'] = 'admin/Users/faq/faq';
$route['admin/faq/add-faq'] = 'admin/Users/add_faq/faq';
$route['admin/edit-faq/(:any)/faq'] = 'admin/Users/edit_faq/$1/faq';
$route['admin/delete-faq/(:any)/faq'] = 'admin/Users/delete_faq/$1/faq';


# Customer Reviews
$route['admin/customer-reviews'] = 'admin/reviews/allReviewsList';

# Banners
$route['admin/banners'] = 'admin/banners/index';
$route['admin/add-banner'] = 'admin/banners/add_banner';
$route['admin/edit-banner/(:any)'] = 'admin/banners/edit_banner/$1';
$route['admin/delete-banner/(:any)'] = 'admin/banners/delete_banner/$1';

# Performer Of The Month
$route['admin/performers'] = 'admin/performers/index';
$route['admin/add-performer'] = 'admin/performers/add_performer';
$route['admin/edit-performer/(:any)'] = 'admin/performers/edit_performer/$1';
$route['admin/delete-performer/(:any)'] = 'admin/performers/delete_performer/$1';

# Performer Of The Month
$route['admin/performers-month'] = 'admin/PerformersMonth/index';
$route['admin/add-performer-month'] = 'admin/PerformersMonth/add_performer';
$route['admin/edit-performer-month/(:any)'] = 'admin/PerformersMonth/edit_performer/$1';
$route['admin/delete-performer-month/(:any)'] = 'admin/PerformersMonth/delete_performer/$1';

# News
$route['admin/news'] = 'admin/news/index';
$route['admin/add-news'] = 'admin/news/add_news';
$route['admin/edit-news/(:any)'] = 'admin/news/edit_news/$1';
$route['admin/delete-news/(:any)'] = 'admin/news/delete_news/$1';
$route['admin/news-comments/(:any)'] = 'admin/news/news_comments/$1';
$route['admin/news-likes/(:any)'] = 'admin/news/news_likes/$1';

###################### For Web View START ######################

$route['privacy-policy'] = 'web-view/home/privacyPolicyWebView';
$route['terms-conditions'] = 'web-view/home/termsConditionsWebView';
$route['forgot-password/(:any)/(:any)'] = 'home/verify_forgot_password_user/$1/$2';
$route['forgot-password'] = 'home/verify_forgot_password_user';
/* 
$route['series/(:any)/(:any)'] = 'series/index';
 */