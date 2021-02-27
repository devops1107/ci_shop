<?php

//require TWILIO_URL.'vendor/autoload.php';
//use Twilio\Rest\Client;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists('is_post')) {
    function is_post($print_post = null)
    {
        if(!empty($print_post)) {
			pr($print_post);
			//exit;
		}
		return $_SERVER['REQUEST_METHOD'] == 'POST' ? TRUE : FALSE;
    }
}
if (!function_exists('is_ajax')) {
    function is_ajax()
    {
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
				? TRUE : FALSE;
    }
}
if (!function_exists('_input_post')) {
    function _input_post($key = null) {
        $CI = & get_instance();
        return $key !== null ? $CI->input->post($key) : null;
    }
}
if (!function_exists('_input_get')) {
    function _input_get($key = null) {
        $CI = & get_instance();
        return $key !== null ? $CI->input->get($key) : null;
    }
}
if (!function_exists('_input_request')) {
    function _input_request($key = null) {
        $CI = & get_instance();
        return $key !== null ? $CI->input->get_post($key) : null;
    }
}
if (!function_exists('_xss_clean')) {
    function _xss_clean($data = array()) {
        $CI = & get_instance();
        $CI->load->library("security");
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $CI->security->xss_clean($value, false);
            }
        }
        return $data;
    }
}
if (!function_exists('pr')) {
    function pr($data = null, $exit = false, $str = "") {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($exit === TRUE || $exit == 1) {
            die();
		}        
		
		if ($str != "") {
            echo($str);
		}
		
    }
}
if (!function_exists('_ck_empty')) {
    function _ck_empty($value = null) {
        $data = '';
        if (!empty($value) && ($value != null)) {
           $data = $value;
        }
        return $data;
    }
}
if (!function_exists('image_resizer')) {
    function image_resizer($hieght ,$width, $url) {
		$h = 200;
		if($hieght){
			$h = $hieght;
		}
		$w = 200;
		if($width){
			$w = $width;
		}
		
        $image_url =  base_url().'assets/thumb.php?src='.$url.'&w='.$w.'&h='.$h.'&zc=0';
        return $image_url;
    }
}

//Relative Date Function

function relative_date($time) {

	$current_date = strtotime(date('Y-m-d h:i:s')); 
	
	$reldays = (strtotime($time) - $current_date)/86400; 
	
	if ($reldays >= 0 && $reldays < 1) {
		return 'Today';
	} else if ($reldays >= 1 && $reldays < 2) {
		return 'Tomorrow';
	} else if ($reldays >= -1 && $reldays < 0) {
		return 'Yesterday';
	}
	
	if (abs($reldays) < 7) {
	
		if ($reldays > 0) {
		
			$reldays = floor($reldays);
			return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
		} else {
	
			$reldays = abs(floor($reldays));
			return $reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';		
		}	
	}
	
	if (abs($reldays) < 182) {
		return date('l, j F',strtotime($time) ? strtotime($time) : time());
	} else {
		return date('l, j F, Y',strtotime($time) ? strtotime($time) : time());
	}
}

if (!function_exists('approve_client_count')) {
    function approve_client_count() {
        $data = '';
		$CI = & get_instance();
			$CI->db->select('c_id');
			$CI->db->from('client_revision');
			$CI->db->where('delete_status','0');
		    $CI->db->where('client_status','pending');
			$client_result = $CI->db->get();
			$data  =  $client_result->num_rows();
			if($data > 0) {
				return $data;
			}else{
				return $data = '0';
			}
    }
}
if (!function_exists('insert_logs')) {
    function insert_logs($data) {
		    $CI = &get_instance();
			$in = $CI->commonmodel->_insert('log_listing', $data);
			$login_id = $data['user_id'];
			get_dashboard_urls($login_id,$data,$in);
			if($in > 0) {
				return $in;
			}else{
				return 0;
			}
    }
}
if (!function_exists('get_user')) {
    function get_user($user_id) {
		     $CI = &get_instance();
			 $user = $CI->commonmodel->_get_data('admin_users',array('user_id' => $user_id,'user_status' => '1','user_delete' => '0'));
			if($user) {
				return $user[0];
			}else{
				return array();
			}
    }
}
if (!function_exists('get_client')) {
    function get_client($c_id) {
		    $CI = &get_instance();
			$client = $CI->commonmodel->_get_data('client',array('c_id' => $c_id));
			if($client) {
				return $client[0];
			}else{
				return array();
			}
    }
}
if (!function_exists('get_client_revision')) {
    function get_client_revision($cr_id) {
		    $CI = &get_instance();
			$client_revision = $CI->commonmodel->_get_data('client_revision',array('cr_id' => $cr_id));
			if($client_revision) {
				return $client_revision[0];
			}else{
				return array();
			}
    }
}
if (!function_exists('get_dashboard_urls')) {
    function get_dashboard_urls($login_id,$log_data,$log_id) {
		
		$CI = &get_instance();
		$user		    = get_user($login_id);
		$user_type		= $user['user_type'];
		$type           = $log_data['type'];
			
		switch ($user_type) {
			case "admin":
			
			switch ($type)
			{
				
				
				
			    case "create_report":
				$urls = array();
				$urls[0]['label'] = 'Report';
				$urls[0]['url']   = 'admin/report/report_view/'.base64_encode($log_data['r_id']);
				$urls[0]['user_type']   = array('admin');
				
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break; 
				
				
				case "edit_report":
				$urls = array();
				$urls[0]['label'] = 'Report';
				$urls[0]['url']   = 'admin/report/report_view/'.base64_encode($log_data['r_id']);
				$urls[0]['user_type']   = array('admin');
				
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break;
				
				default:
				
				break;
		    }
			
			
			break;
			case "rsm":
			  
			  	switch ($type)
			{
				/* case "create_client":
				$urls = array();
				$urls[0]['label'] = $user['first_name'].' '.$user['last_name'].' ('.$user['role'].')';
				$urls[0]['url']   = 'admin/users/rsm_sales_person_view/'.base64_encode($login_id);
				$urls[0]['user_type']   = array('admin');
				
				
				$client = get_client($log_data['c_id']);
		
				$urls[1]['label'] =  $client['company_name'];
				$urls[1]['url']   = 'admin/client/client_edit/'.base64_encode($log_data['c_id']);
				$urls[1]['user_type']   = array('admin','rsm');
			
				
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break; */
				
				
				
				case "create_report":
				$urls = array();
				$urls[0]['label'] = 'Report';
				$urls[0]['url']   = 'admin/daily_report/daily_report_view/'.base64_encode($log_data['r_id']);
				$urls[0]['user_type']   = array('admin');
				
				$urls[1]['label'] =  'Report';
				$urls[1]['url']   = 'admin/report/report_view/'.base64_encode($log_data['r_id']);
				$urls[1]['user_type']   = array('rsm'); 
				$update_data['link'] = serialize($urls);
				
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break;
				
			
				
				case "edit_report":
				$urls = array();
				$urls[0]['label'] = 'Report';
				$urls[0]['url']   = 'admin/daily_report/daily_report_view/'.base64_encode($log_data['r_id']);
				$urls[0]['user_type']   = array('admin');
				
				
				$urls[1]['label'] =  'Report';
				$urls[1]['url']   = 'admin/report/report_view/'.base64_encode($log_data['r_id']);
				$urls[1]['user_type']   = array('rsm');
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break;
				
				default:
				
				break;
		    }
			  
			break;
			
			 case "sales_person":
			
			switch ($type)
			{
				case "create_client":
				$urls = array();
				/* $urls[0]['label'] = $user['first_name'].' '.$user['last_name'].' ('.$user['role'].')';
				$urls[0]['url']   = '/admin/users/rsm_sales_person_view/'.base64_encode($login_id);
				$urls[0]['user_type']   = array('admin'); */
				
				$client_revision = get_client_revision($log_data['cr_id']);
				$urls[0]['label'] =  $client_revision['company_name'];
				$urls[0]['url']   = 'admin/client_approval/approval_client_edit/'.base64_encode($log_data['cr_id']);
			    $urls[0]['user_type']   = array('admin','rsm');
				
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break;
				
				/* case "sale_edit_client":
				$urls = array();
				$urls[0]['label'] = $user['first_name'].' '.$user['last_name'].' ('.$user['role'].')';
				$urls[0]['url']   = '/admin/users/rsm_sales_person_view/'.base64_encode($login_id);
				
				$client = get_client($log_data['c_id']);
				$urls[1]['label'] =  $client['company_name'];
				$urls[1]['url']   = '/admin/client_approval/approval_client_edit/'.base64_encode($log_data['c_id']);
				
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break; */
				
				 case "create_report":
				 $urls = array();
				/*$urls[0]['label'] = $user['first_name'].' '.$user['last_name'].' ('.$user['role'].')';
				$urls[0]['url']   = '/admin/users/rsm_sales_person_view/'.base64_encode($login_id); */
				
				
				$urls[0]['label'] =  'Report';
				$urls[0]['url']   = 'admin/daily_report/daily_report_view/'.base64_encode($log_data['r_id']);
				$urls[0]['user_type']   = array('admin','rsm');
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break;
				
				/* case "delete_report":
				$urls = array();
				$urls[0]['label'] = $user['first_name'].' '.$user['last_name'].' ('.$user['role'].')';
				$urls[0]['url']   = '/admin/users/rsm_sales_person_view/'.base64_encode($login_id);
				
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break; */
				
				case "edit_report":
				$urls = array();
				/* $urls[0]['label'] = $user['first_name'].' '.$user['last_name'].' ('.$user['role'].')';
				$urls[0]['url']   = '/admin/users/rsm_sales_person_view/'.base64_encode($login_id); */
				
				
				$urls[0]['label'] =  'Report';
				$urls[0]['url']   = 'admin/daily_report/daily_report_view/'.base64_encode($log_data['r_id']);
				$urls[0]['user_type']   = array('admin','rsm');
				$update_data['link'] = serialize($urls);
				$CI->commonmodel->_update('log_listing',$update_data,array('log_id'=>$log_id));
				break;
				
				default:
				
				break;
		    }
			
			break; 
			
			default:
		}
	}
} 
if (!function_exists('save_dashboard_url')) {
    function save_dashboard_url($log_data,$log_id) {
		
		    /*  echo $log_id;
			die;    */
		    $CI = &get_instance();
		    $user_type		= $CI->site_santry->get_auth_data('user_type');
			$type = array('create_user','edit_user','delete_user','change_user_password','assign_sales_person','delete_assign_sales_person','create_report','edit_report','delete_report','create_client_type','edit_client_type','client_type_delete','create_client','edit_client','edit_concerned_person_detail','delete_concerned_person_detail','client_delete','edit_approval_client'); 
			$label = array();
			$url   = array();
			
		 	switch ($user_type) {
						case "admin":
							
							foreach($type as $type_rec){
								switch($type_rec){
								 case 'create_user':
								    if(!empty($log_data)){
										$user = $CI->commonmodel->_get_data('users',array('user_id' => $log_data['user_id'],'user_status' => '1','user_delete' => '0'));
										if(!empty($user[0])){
											$label = $user[0]['first_name']. ' '.$user[0]['last_name'].'('.$user[0]['role'].')';
											$url = 'admin/users/rsm_sales_person_view/'.base64_encode($log_data['user_id']);
											$link = serialize(array(($label),($url)));
										}
										$condition		= array("log_id"=>$log_id);
									    $detail = array(
													     'link'		=> 	 $link,
													    );
										$CI->commonmodel->_update('log_listing',$detail,$condition);;
									}
								    
									   break;
									   
								     case 'edit_user':
									  // $CI->commonmodel->_update('log_listing', $detail);   
									   break; 
								}
							}
							break;
						case "rsm":
							/* $this->rsm_client_edit($id,$user_type);
							$this->session->set_flashdata('flashSuccess','Your Client Detail Has Been Updated Successfully');
							redirect('admin/client/client_list'); */
							
							break;
						
						default:
							/* $this->admin_client_edit($id,$user_type);
							$this->session->set_flashdata('flashSuccess','Your Client Detail Has Been Updated Successfully');
							redirect('admin/client/client_list'); */
					} 
			return '';
    }
}
	
if (!function_exists('change_site_language')) {
	function change_site_language($language="") {
		$CI = & get_instance();
		$sel_language = ($language != "") ? $language : "english";
		$CI->session->set_userdata('site_lang', $sel_language);
		if($sel_language){
			return $sel_language;
		}else{
			return false;
		}
	}
}


if (!function_exists('get_site_language')) {
	function get_site_language() {
		$CI = & get_instance();
		if($CI->session->userdata('site_lang')){
			return $CI->session->userdata('site_lang');
		}else{
			return $CI->config->item('language');
		}
		
	}
}


if (!function_exists('get_footer_about')) {
	function get_footer_about() {
		$CI = & get_instance();
		if($CI->session->userdata('site_lang')){
			$lang = $CI->session->userdata('site_lang');
		}else{
			$lang = $CI->config->item('language');
		}
		//$query = $CI->db->select('*')->from('cms_pages')->where('page_key','ABOUT_US')->get();
		//$data = $query->row_array();
		//pr($data,1);
		if($lang=='english')
		{
			echo 'We MyMatch are a German company that was founded in 2019 with the vision of creating the best possible gaming experience with the help of professional online tournaments.';
		}else{
			echo 'Wir MyMatch sind ein deutsches Unternehmen, welches 2019 mit der Vision gegründet wurde, das bestmögliche Spielerlebnis mithilfe von Professionellen Online Turnieren zu schaffen.';	
		}
	}
}
?>