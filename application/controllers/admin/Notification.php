<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}		
		/*$login_key = $this->site_santry->get_auth_data('login_key');
		$user_id    = $this->site_santry->get_auth_data('id');
		$lg_user = get_user($user_id);
		if(!empty($lg_user['login_key'])){
		    if($login_key!=$lg_user['login_key']){
				redirect('welcome/logout'); 
			}
		}else{
			 redirect('welcome/logout');
		}*/
		/* $user_type = $this->site_santry->get_auth_data('user_type');
		if($user_type=='agent' || $user_type=='customer'){
			redirect('welcome');
		} */
		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/notifications_model");
	}
		
	public function index(){
		
		$data['title'] = 'Notifications';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['post']	=  $this->input->post();
			$allrecord      = $this->notifications_model->getAllNotifications($data);
			echo json_encode($allrecord);
		}else {	
			
			$this->layout->view("admin/notofications/notifications_list", $data);
		}		
	}	
	
}