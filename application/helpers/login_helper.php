<?php

/*
 * Here you can Manage Login permissions and Left side menu listing.
 */
?>
<?php
if(!function_exists('get_admin_menus')){

    function get_admin_menus($user_id,$menu_type) {
		
		$CI = &get_instance();
		$menuArray = array();
		$CI->load->model('admin/left_side_menu_model');
		$menu_items = $CI->left_side_menu_model->get_menu_items($menu_type,$user_id);
		if($menu_items)
		{
			$i = 0;
			foreach($menu_items as $key => $menu_item)
			{ //pr($menu_item,1);
				$menuArray[$i]['id'] = $menu_item->menu_i_id;
				$menuArray[$i]['menu_type'] = $menu_item->menu_type;
				$menuArray[$i]['mt_id'] = $menu_item->mt_id;
				$menuArray[$i]['user_id'] = $menu_item->user_id;
				$menuArray[$i]['label'] = $menu_item->label;
				$menuArray[$i]['label_lang'] = $menu_item->label;
				$menuArray[$i]['fa_icons'] = $menu_item->fa_icons;
				$menuArray[$i]['slug'] = $menu_item->slug;
				$menuArray[$i]['edit_permission'] = $menu_item->edit_permission;
				$menuArray[$i]['view_permission'] = $menu_item->view_permission;
				$menuArray[$i]['delete_permission'] = $menu_item->delete_permission;
				$menuArray[$i]['sub_menu_items'] = array();
				
				if($menu_item->menu_i_id){
				 	$menuArray[$i]['sub_menu_items'] = get_sub_menu_items($user_id,$menu_item->menu_i_id,$menu_type);
				}
				$i++;
			}
			
		}
		//print_r($menuArray); die;
	   return $menuArray;
    }
}

function get_sub_menu_items($user_id,$parent_id,$menu_type){
	$CI = &get_instance();
	$menuArray = array();
	$CI->load->model('admin/left_side_menu_model');
	$menu_items = $CI->left_side_menu_model->get_sub_menu_items($user_id,$parent_id,$menu_type);
	
	if($menu_items){
		$i = 0;
		foreach($menu_items as $key => $menu_item)
		{
			$menuArray[$i]['id'] = $menu_item->menu_i_id;
			$menuArray[$i]['menu_type'] = $menu_item->menu_type;
			$menuArray[$i]['mt_id'] = $menu_item->mt_id;
			$menuArray[$i]['user_id'] = $menu_item->user_id;
			$menuArray[$i]['label'] = $menu_item->label;
			$menuArray[$i]['label_lang'] = $menu_item->label;
			$menuArray[$i]['fa_icons'] = $menu_item->fa_icons;
			$menuArray[$i]['slug'] = $menu_item->slug;
			$menuArray[$i]['edit_permission'] = $menu_item->edit_permission;
			$menuArray[$i]['view_permission'] = $menu_item->view_permission;
			$menuArray[$i]['delete_permission'] = $menu_item->delete_permission;
			$menuArray[$i]['sub_menu_items'] = array();
			
			/*if($menu_item->id){
				$menuArray[$i]['sub_menu_items'] = get_sub_menu_items($user_id,$menu_item->id,$menu_type);
			}*/
			$i++;
		}//pr($menuArray,1);
		return $menuArray;
	}else{
		return array();	
	}
}

if(!function_exists('get_user_details')){	

    function get_user_details($data){
		
		$CI = &get_instance();
		$CI->load->model('admin/left_side_menu_model');
		$userDetails = $CI->left_side_menu_model->get_user_details($data);
		if($userDetails)
		{
			return $userDetails['0'];
		}else{
			return false;
		}
    }
}
	
if(!function_exists('activate_menu')) {
  function activate_menu($menu) {
	// Getting CI class instance.
	$uri = get_slug();
	// Getting router class to active.
	$sub_menu_items = $menu['sub_menu_items'];
	$thisMenuSlugs = array_column($sub_menu_items, 'slug');
	$thisActive = '';
	if(in_array($uri,$thisMenuSlugs))
	{
		$thisActive = 'active';
	}
	return $thisActive;
  }
}

if (!function_exists('get_slug')) {

    function get_slug(){
		$CI = get_instance();
		$uri2	=	$CI->uri->segment(2);
		$uri3	=	$CI->uri->segment(3);
		$uri4	=	$CI->uri->segment(4);
		$slug = '';
		if($uri2!='' && $uri3!='' && $uri4!='')
		{
			$slug = $uri2.'/'.$uri3.'/'.$uri4;
		}elseif($uri2!='' && $uri3!=''){
			$slug = $uri2.'/'.$uri3;
		}else{
			$slug = $uri2;
		}
        return $slug;
    }

}
	
	
	
if(!function_exists('activate_sub_menu')) {
  function activate_sub_menu($slug){
	// Getting CI class instance.
	$uri = get_slug();
	// Getting router class to active.
	$thisActive = '';
	if($uri==$slug)
	{
		$thisActive = 'active';
	}
	return $thisActive;
  }
}
	
/* if(!function_exists('activate_sub_menu')) {
  function activate_sub_menu($menu_items) {
	// Getting CI class instance.
	$CI = get_instance();
	// Getting router class to active.
	$method = $CI->router->fetch_method();
	$controller = $CI->router->fetch_class();
	$active_item = $controller.'/'.$method;
	return ($active_item == $menu_items) ? 'active' : '';
  }
} */

?>