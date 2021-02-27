<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}
		
		 $login_key = $this->site_santry->get_auth_data('login_key');
		 $user_id    = $this->site_santry->get_auth_data('id');
		 //echo $login_key; 
		 $lg_user = get_user($user_id);
		 //pr($lg_user,1);
		 if(!empty($lg_user['login_key'])){
			 if($login_key!=$lg_user['login_key']){
				redirect('welcome/logout'); 
			 }
		 }else{
			 redirect('welcome/logout');
		 }
		 
		/*if($user_type=='agent' || $user_type=='customer'){
			redirect('welcome');
		} */
		
		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/user_profile_model");
		$this->load->model("admin/categories_model");
		$this->load->helper("basic");
	}

	public function dashboard(){
		$data['title'] = SITE_NAME.' - Dashboard';
		
		$data['totalProducts'] = $this->user_profile_model->getTotalProducts();
		$data['totalActiveUser'] = $this->user_profile_model->getTotalActiveUser();
		$data['totalInactiveUser'] = $this->user_profile_model->getTotalInactiveUser();

		$this->layout->view("admin/dashboard", $data);
	}
		
	public function profile(){
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();

		$data['title'] = SITE_NAME.' - Update Profile';
		$user_type = $this->site_santry->get_auth_data('user_type');
		$user_id = $this->site_santry->get_auth_data('id');
		if($this->input->post())
		{
			
				$validation_post = array(
					  array('field' => 'first_name', 'label' =>'First Name', 'rules' => 'trim|required'),
					  array('field' => 'last_name', 'label' =>'last Name', 'rules' => 'trim|required'),
					  array('field' => 'contact_number', 'label' =>'Phone No.', 'rules' => 'trim|required'),
					);
			
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$post = $this->input->post();
				if($this->commonmodel->_update('admin_users',$post,array('user_id'=>$user_id)))
				{
					$this->session->set_flashdata('flashSuccess','Profile has been updated Successfully!');
					if(isset($_FILES) && $_FILES['profile_image']['name'] != "")
					{
						$profile_image = $this->site_santry->get_auth_data('profile_image');
						$upload_data = $this->do_upload($referrer,'profile_image');
						$detail['profile_image'] = $upload_data['upload_data']['file_name'];
						$this->site_santry->update_auth_data(array('profile_image'=>$detail['profile_image']));
						$this->commonmodel->_update('admin_users',$detail,array('user_id'=>$user_id));
						unlink(UPLOAD_PHYSICAL_PATH.'users/'.$profile_image);
					}
				}else{
					$this->session->set_flashdata('flashError','An error occurred.Please try again later!');
				}
				redirect($referrer);
			}else{
				$this->session->set_flashdata('flashError',validation_errors());
				redirect($referrer);
			}
		}else{
			$details = $this->commonmodel->_get_data('admin_users',array('user_id'=>$user_id),'*');
			$data['detail'] = $details[0];
			$this->layout->view("admin/users/admin_profile", $data);
			
		}
	}

	public function users_list(){
		$data['title'] = 'View All Users';
		
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		
		if($this->input->post() && $get_hd == 1){
			$data['customer_name'] = $this->session->userdata('customer_name');		
			$data['customer_email'] = $this->session->userdata('customer_email');		
			$data['contact_number'] = $this->session->userdata('contact_number');		
			$data['user_status'] = $this->session->userdata('user_status');		
			//pr($data,1);
			$data['post']	=  $this->input->post();
			$allrecord 		= $this->user_profile_model->getAllUsersdetail($data);
			echo json_encode($allrecord);
		}else {
			$data['customer_name'] = $this->input->post('customer_name');
			$data['customer_email'] = $this->input->post('customer_email');
			$data['contact_number'] = $this->input->post('contact_number');
			$data['user_status'] = $this->input->post('user_status');
			if($this->input->post('devicetype')!='' || $this->input->post('user_status')!='' || $this->input->post('customer_name')!='' || $this->input->post('customer_email')!='' || $this->input->post('contact_number')!=''){
				$filter_session_data['customer_name'] = $this->input->post('customer_name');
				$filter_session_data['customer_email'] = $this->input->post('customer_email');
				$filter_session_data['contact_number'] = $this->input->post('contact_number');
				$filter_session_data['user_status'] = $this->input->post('user_status');
			}else{
				$filter_session_data['customer_name'] = '';
				$filter_session_data['customer_email'] = '';
				$filter_session_data['contact_number'] = '';
				$filter_session_data['user_status'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$data['user_statuses'] 	=   array(''=>'View All','C'=>'Activated','N'=>'Blocked');
			$this->layout->view("admin/users/users_list", $data);
		}
	}


	/*public function users_list(){
		$data['title'] = 'View All Users';
		
		print $get_hd=1;
		if($this->input->post('get_hidden')){
			print $get_hd=0;
		}
		print $get_hd;
		
		if($this->input->post() && $get_hd == 1){
			$data['customer_name'] = $this->session->userdata('customer_name');		
			$data['customer_email'] = $this->session->userdata('customer_email');		
			$data['contact_number'] = $this->session->userdata('contact_number');		
			$data['status'] = $this->session->userdata('status');		
			//pr($data,1);
			$data['post']	=  $this->input->post();
			$allrecord 		= $this->user_profile_model->getAllUsersdetail($data);
			//echo json_encode($allrecord);
		}else {
			$data['customer_name'] = $this->input->post('customer_name');
			$data['customer_email'] = $this->input->post('customer_email');
			$data['contact_number'] = $this->input->post('contact_number');
			$data['status'] = $this->input->post('status');
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$data['user_statuses'] 	=   array(''=>'View All','C'=>'Activated','N'=>'Blocked');
			$this->layout->view("admin/users/users_list", $data);
		}
	}*/
	
	public function edit_user($user_id){
	
		$data['title'] = 'Edit Users';
		$table='tbl_usermaster';
		//$user_id=$this->uri->segment(4);
		$id=base64_decode($user_id);
		$countid = $this->commonmodel->_get_data_row('id',$table,array('id' => $id));
		
		if($countid==0){
			$this->session->set_flashdata('flashError','This Id Does Not Exists Try Again!');
			redirect('admin/users');		
		}
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post()){

			$post=$this->input->post();
			//pr($post,1);
			$validation_post = array(
			  array('field' => 'first_name', 'label' =>'First Name', 'rules' => 'trim|required'),
			  array('field' => 'last_name', 'label' =>'Last Name', 'rules' => 'trim|required'),
			  array('field' => 'mobileno', 'label' =>'Mobile No', 'rules' => 'trim|required'),
			  array('field' => 'emailid', 'label' =>'Email ID', 'rules' => 'trim|required'),
			  array('field' => 'vat_number', 'label' =>'VAT Number', 'rules' => 'trim|required'),
			  array('field' => 'commercial_reg_no', 'label' =>'Commercial Register Number', 'rules' => 'trim|required')
			);
						
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if($this->form_validation->run() === TRUE) {
		        $updatedUser=$this->user_profile_model->editUser($post,$id);
				if($updatedUser){
					$this->session->set_flashdata('flashSuccess','User Have Been Updated Successfully');	
				}else {
					$this->session->set_flashdata('flashError','Oops Something went wrong ! please try again later ');	
				}
				redirect('admin/users');
			} 			
		}
		$data['detail']=$this->user_profile_model->getUsersDetail($id);
		$this->layout->view("admin/users/edit_user", $data);
	}
	
	public function tokenKeyAccess($username)
	{
		$currDate = time(); 
		$accrssToken = $username.$currDate;
		return md5(uniqid($accrssToken,true));
	}
			
	public function check_username(){
		$json=array();
		$user_name = $this->input->post('user_name');
		
		$data = $this->user_profile_model->check_user_name($user_name);
		
		if($data > 0){
				$isAvailable = false; 
				$json=array('valid' => $isAvailable	);
		}else{
				$isAvailable = true; 
				$json=array('valid' => $isAvailable	);
		}
		echo json_encode($json);
		exit;
	}
	
	public function check_password(){
		$json=array();
		$old_password = $this->input->post('old_password');
		$data = $this->user_profile_model->check_password($old_password);
		
		if($data < 1){
				$isAvailable = false; 
				$json=array('valid' => $isAvailable	);
		}else{
				$isAvailable = true; 
				$json=array('valid' => $isAvailable	);
		}
		echo json_encode($json);
		exit;
	}
	
	public function getUserDetailById(){

		$user_id=$this->input->post('user_id');
		$user_id=base64_decode($user_id);
		$getDetail=$this->user_profile_model->getUserDetailById($user_id);	
		echo json_encode($getDetail); 
	}

	public function cms_pages($page_type=""){

		$page_title=$this->uri->segment(3);
		$data['title'] = 'View All '.$page_title.'';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		
		if($this->input->post() && $get_hd == 1){	
			$data['post']	=  $this->input->post();
			$allrecord 		= $this->user_profile_model->getCMSPages($data,$page_type);
			echo json_encode($allrecord);
		}else {
			$this->layout->view("admin/cms_pages/cms_pages", $data);
		}
	}

	public function edit_cms_pages($page_type){

		$title="";
		if($page_type=='terms-conditions'){
			$title='Terms Condition';
		}if($page_type=='privacy-policy'){
			$title='Privacy Policy';
		}if($page_type=='about-us'){
			$title="About Us";
		}if($page_type=='rooms-rules-conditions'){
			$title="Rooms Rules and Conditions";
		}
		if($page_type=='stores-rules-conditions'){
			$title="Stores Rules and Conditions";
		}
		$data['title'] = 'Edit '.$title.'';
		
		$table='cms_pages';
		//$user_id=$this->uri->segment(4);	
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post()){
			$post=$this->input->post();
			//pr($post,1);
				$validation_post = array(
				  array('field' => 'description', 'label' =>'Description', 'rules' => 'trim|required'),
				  array('field' => 'description_gr', 'label' =>'Description(GR)', 'rules' => 'trim|required'),
				);
						
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if($this->form_validation->run() === TRUE) {
		        $updatedUser=$this->user_profile_model->editCMS($post,$page_type);
				if($updatedUser){
					$this->session->set_flashdata('flashSuccess','Details Has Been Updated Successfully!');	
				}else {
					$this->session->set_flashdata('flashError','Oops Something went wrong ! please try again later ');	
				}
				redirect($referrer);
			} // end validations			
		}
		$data['detail']=$this->user_profile_model->getCMSPages($page_type);
		//pr($data['detail'],1);
		$this->layout->view("admin/cms_pages/edit_cms_pages", $data);
	}
	
	public function faq(){
		$data['title'] = 'FAQ List';
		if($this->input->post()) {	
			$data['user_type'] 	        = $this->session->userdata('user_types');    
			$data['type'] 	        	= 'FAQ';    
			$data['post']				=  $this->input->post();
			$allrecord 					= $this->user_profile_model->get_faq($data);
			echo json_encode($allrecord);
		}else{
			$this->layout->view("admin/cms_pages/faq_view", $data);
		}
	}

	public function add_faq(){
		$data['title'] 	= 'Add New Faq';
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		if($this->input->post()) {
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				  array('field' => 'question', 'label' =>'Question', 'rules' => 'trim|required'),
                );
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE){
				$detail	= 	array(
							'question'=> trim($post['question']),
							'answer'=> trim($post['answer']),
						);
				$this->commonmodel->_insert('faqs', $detail);
				$this->session->set_flashdata('flashSuccess','Faq Have added successfully!');
				redirect('admin/faq');
			}
		}
		$this->layout->view("admin/cms_pages/add_faq",$data);
	}

	public function edit_faq($user_id){
		$data['title'] = 'Edit Faq';
		$table='faqs';
		$id=base64_decode($user_id);
		$countid = $this->commonmodel->_get_data_row('faq_id',$table,array('faq_id' => $id));
		if($countid==0){
			$this->session->set_flashdata('flashError','This Id Does Not Exists Try Again!');
			redirect('admin/faqs');		
		}
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		if($this->input->post()){
			$post=$this->input->post();
				$mod_date=date('Y-m-d H:i:s');
				$detail = 	array(
							  'question' => $post['question'],
							  'answer'	 => $post['answer'],
							  'modified_on'	=>	$mod_date
							);
				
				$data=serialize($detail);			
				$table='faqs';
				$this->commonmodel->_update($table, $detail,array('faq_id' => $id));
				$created_date  = date('Y-m-d H:i:s');
				$lg_data 	= 	array(
									'question' => $post['question'],
									'answer'  => $post['answer']
								);
				$this->session->set_flashdata('flashSuccess','Faq Has Been Updated Successfully');
				redirect('admin/faq');			
		}
		$data['detail']=$this->user_profile_model->getFaqDetail($id);
		$this->layout->view("admin/cms_pages/edit_faq", $data);
	}

	public function delete_faq($id){
		$table='faqs';
		$id=base64_decode($id);
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		$condition = array('faq_id' => $id);
		$rows = $this->commonmodel->_get_data_row('',$table,$condition);
		
		if($rows<1) {
			$this->session->set_flashdata('flashError','Please select valid entry.');
			redirect($referrer);
			exit;
		}
		
		$data = array('delete_status'=>'1');
		$this->commonmodel->_update($table, $data, $condition);
		$this->session->set_flashdata('flashSuccess','FAQ Has Been Deleted Successfully');
		redirect($referrer);
	}

	public function do_upload($referrer,$submited_name)
	{
		
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'customers/';
		if(!is_dir($folderPath))
		{
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'jpg|jpeg';
		$config['max_size']             = 1024;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name))
		{
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}
	
	public function driver_do_upload($referrer,$submited_name)
	{
		
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'drivers/';
		if(!is_dir($folderPath))
		{
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'jpg|jpeg';
		$config['max_size']             = 1024;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name))
		{
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}
	
	public function delete_user(){
		
		$data['title'] = 'Delete User';
		$table='users';
		$user_id=$this->uri->segment(4);
		
		$id=base64_decode($user_id);
		
		$condition		= array("user_id"=>$id);
		$detail = array(
						'delete_status'		=> 	 '1',
						);
		$this->commonmodel->_update($table,$detail,$condition);
		/* if(!empty($id)){
		 $lg_key = array(
						'login_key'		=> '',
					);
		 $this->commonmodel->_update($table, $lg_key, array('user_id'=>$id));	
		} */
		
		$user_id    = $this->site_santry->get_auth_data('id');
		$data=serialize($detail);
		$mod_date  = date('Y-m-d H:i:s');
		$lg_user = get_user($user_id);
		
		
		$del_lg_user = $this->user_profile_model->getAllDeleteUsersdetail($id);
		
		$lg_data 	= 	array(
									'user_id'                  => $user_id,
									'from_id'                  => $user_id,
						            'to_id'                    => $id,
									'type'			           => 'delete_user',
									'comment'			       => $lg_user['first_name'].' '.$lg_user['last_name'].'('.$lg_user['role'].') has deleted '.$del_lg_user[0]['first_name'].' '.$del_lg_user[0]['last_name'].'('.$del_lg_user[0]['role'].')',
									'log_content'			   => $data,
									'created_date'             => $mod_date
								);
		$lg_id =  insert_logs($lg_data);		
		/* $this->commonmodel->_insert('log_listing', $detail); */
		
		$this->session->set_flashdata('flashSuccess','User Has Been Deleted Successfully');
		redirect('admin/users-list');
	}
	
	public function check_useremail(){
		$json=array();
		$user_email = $this->input->post('email');
		
		$data = $this->user_profile_model->check_user_email($user_email);
		
		if($data > 0){
				$isAvailable = false; 
				$json=array('valid' => $isAvailable	);
		}else{
				$isAvailable = true; 
				$json=array('valid' => $isAvailable	);
		}
		echo json_encode($json);
		exit;
	}
	
	public function change_activation_status(){
		$id_encode   		= $this->uri->segment(4);
		$id		  		= base64_decode($id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('id' => $id);
		$this->commonmodel->_update('tbl_usermaster',array('status'=>$change_activation_status,'modify'=>date('Y-m-d h:i:s')),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		$referer =  $_SERVER['HTTP_REFERER'];
		redirect($referer);
	}

	public function random_key($length=10)
	{
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$email_token = '';
		for ($i = 0; $i < $length; $i++) {
			  $email_token .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $email_token;
	}	
}
