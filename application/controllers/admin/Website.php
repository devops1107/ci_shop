<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Website extends CI_Controller {

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
		$this->load->model("admin/Website_model");
		$this->load->model("admin/categories_model");
		$this->load->helper("basic");
	}
		
	public function index(){
		
		$data['title'] = SITE_NAME.' - Dashboard';
		if($this->input->post())
		{	
			$data['user_type'] 	        = $this->session->userdata('user_types');    
			$data['post']	=  $this->input->post();
			$allrecord 		= $this->Website_model->getDashboardDetails($data);
			echo json_encode($allrecord);
		}else{
			/*$data['totalCategories'] = $this->Website_model->getTotalCategories();
			$data['totalTemplates'] = $this->Website_model->getTotalTemplates();*/ 
			//pr($data,1);
			$this->layout->view("admin/dashboard", $data);
		}
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

	public function add_user(){
		$data['title'] 	= 'Add New User';
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
	
		if($this->input->post()) {
			$post = $this->input->post();
			$validation_post = array(
				  array('field' => 'first_name', 'label' =>'First Name', 'rules' => 'trim|required'),
				  array('field' => 'last_name', 'label' =>'Last Name', 'rules' => 'trim|required'),
				  array('field' => 'user_name', 'label' =>'User Name', 'rules' => 'trim|required|is_unique[tbl_usermaster.username]'),
				  array('field' => 'email', 'label' =>'Email', 'rules' => 'trim|required|is_unique[tbl_usermaster.emailid]'),
				  array('field' => 'mobileno', 'label' =>'Mobile No', 'rules' => 'trim|required'),
				  array('field' => 'password', 'label' =>'Mobile No', 'rules' => 'trim|required'),
			    );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {

				$addedUser = $this->Website_model->addAdminUsers($post);
				if($addedUser){
					$this->session->set_flashdata('flashSuccess','User have added Successfully');
					redirect('admin/all-users');	
				}else{
					$this->session->set_flashdata('flashError','Oops Something Went wrong ! please try again later');
					redirect($referrer);	
				}
			}
		}
		$this->layout->view("admin/users/add_user",$data);
	}
	
	public function tokenKeyAccess($username)
	{
		$currDate = time(); 
		$accrssToken = $username.$currDate;
		return md5(uniqid($accrssToken,true));
	}
		
	public function users_list(){
		$data['title'] = 'View All Users';
		
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		
		if($this->input->post() && $get_hd == 1){
			$data['devicetype'] = $this->session->userdata('devicetype');	
			$data['customer_name'] = $this->session->userdata('customer_name');		
			$data['customer_email'] = $this->session->userdata('customer_email');		
			$data['contact_number'] = $this->session->userdata('contact_number');		
			$data['user_status'] = $this->session->userdata('user_status');		
			//pr($data,1);
			$data['post']	=  $this->input->post();
			$allrecord 		= $this->Website_model->getAllUsersdetail($data);
			echo json_encode($allrecord);
		}else {
			$data['devicetype'] = $this->input->post('devicetype');
			$data['customer_name'] = $this->input->post('customer_name');
			$data['customer_email'] = $this->input->post('customer_email');
			$data['contact_number'] = $this->input->post('contact_number');
			$data['user_status'] = $this->input->post('user_status');
			if($this->input->post('devicetype')!='' || $this->input->post('user_status')!='' || $this->input->post('customer_name')!='' || $this->input->post('customer_email')!='' || $this->input->post('contact_number')!=''){
				$filter_session_data['devicetype'] = $this->input->post('devicetype'); 
				$filter_session_data['customer_name'] = $this->input->post('customer_name');
				$filter_session_data['customer_email'] = $this->input->post('customer_email');
				$filter_session_data['contact_number'] = $this->input->post('contact_number');
				$filter_session_data['user_status'] = $this->input->post('user_status');
			}else{
				$filter_session_data['devicetype'] = '';
				$filter_session_data['customer_name'] = '';
				$filter_session_data['customer_email'] = '';
				$filter_session_data['contact_number'] = '';
				$filter_session_data['user_status'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$data['device_types'] 	=   array(''=>'View All','0'=>'Andoid','1'=>'IOS');
			$data['user_statuses'] 	=   array(''=>'View All','C'=>'Activated','N'=>'Blocked');
			$this->layout->view("admin/users/users_list", $data);
		}
	}

	public function check_username(){
		$json=array();
		$user_name = $this->input->post('user_name');
		
		$data = $this->Website_model->check_user_name($user_name);
		
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
		$data = $this->Website_model->check_password($old_password);
		
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
	
	public function edit_user($user_id){
	
		$data['title'] = 'Edit Users';
		$table='tbl_usermaster';
		//$user_id=$this->uri->segment(4);
		$id=base64_decode($user_id);
		$countid = $this->commonmodel->_get_data_row('id',$table,array('id' => $id));
		
		if($countid==0){
			$this->session->set_flashdata('flashError','This Id Does Not Exists Try Again!');
			redirect('admin/all-users');		
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
				);
						
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if($this->form_validation->run() === TRUE) {
		        $updatedUser=$this->Website_model->editUser($post,$id);
				if($updatedUser){
					$this->session->set_flashdata('flashSuccess','User Have Been Updated Successfully');	
				}else {
					$this->session->set_flashdata('flashError','Oops Something went wrong ! please try again later ');	
				}
				redirect('admin/all-users');	

			} // end validations			
		}
		$data['detail']=$this->Website_model->getUsersDetail($id);
		$this->layout->view("admin/users/edit_user", $data);
	}
		
	public function view_user($user_id_encoded){
		$data['title'] = 'View User';
		$table='tbl_usermaster';
		//$user_id=$this->uri->segment(4);
		$user_id=base64_decode($user_id_encoded);
		$countid = $this->commonmodel->_get_data_row('id',$table,array('id' => $user_id));
		
		if($countid==0){
			$this->session->set_flashdata('flashError','This Id Does Not Exists Try Again!');
			redirect('admin/all-users');		
		}
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$data['user_detail']=$this->Website_model->getUsersDetail($user_id);
		//pr($data['user_detail'],1);
		$this->layout->view("admin/users/user_view", $data);
	}
	
	public function getUserDetailById(){
		$user_id=$this->input->post('user_id');
		$user_id=base64_decode($user_id);
		$getDetail=$this->Website_model->getUserDetailById($user_id);	
		echo json_encode($getDetail); 

	}

	public function changeProfileImage()
	{
		$response = array();
		$response['error'] = 'yes';
		$response['msg'] = "You can't hit this url directly";
		if($_FILES)
		{
			$response = $this->Website_model->updateProfileImage();
		}
		echo json_encode($response,true);
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
			$allrecord 		= $this->Website_model->getCMSPages($data,$page_type);
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
		        $updatedUser=$this->Website_model->editCMS($post,$page_type);
				if($updatedUser){
					$this->session->set_flashdata('flashSuccess','Details Has Been Updated Successfully!');	
				}else {
					$this->session->set_flashdata('flashError','Oops Something went wrong ! please try again later ');	
				}
				redirect($referrer);
			} // end validations			
		}
		$data['detail']=$this->Website_model->getCMSPages($page_type);
		//pr($data['detail'],1);
		$this->layout->view("admin/cms_pages/edit_cms_pages", $data);
	}
	
	public function faq(){
		$data['title'] = 'FAQ List';
		if($this->input->post()) {	
			$data['user_type'] 	        = $this->session->userdata('user_types');    
			$data['type'] 	        	= 'FAQ';    
			$data['post']				=  $this->input->post();
			$allrecord 					= $this->Website_model->get_faq($data);
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
							'question_gr'=> trim($post['question_gr']),
							'answer_gr'=> trim($post['answer_gr']),
							'question_tr'=> trim($post['question_tr']),
							'answer_tr'=> trim($post['answer_tr']),
						);
				$this->commonmodel->_insert('tbl_faqs', $detail);
				$this->session->set_flashdata('flashSuccess','Faq Have added successfully!');
				redirect('admin/faq');
			}
		}
		$this->layout->view("admin/cms_pages/add_faq",$data);
	}

	public function edit_faq($user_id){
		$data['title'] = 'Edit Faq';
		$table='tbl_faqs';
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
								'question_gr'=> trim($post['question_gr']),
								'answer_gr'=> trim($post['answer_gr']),
								'question_tr'=> trim($post['question_tr']),
								'answer_tr'=> trim($post['answer_tr']),
								'modified_on'	=>	$mod_date
							);
				
				$data=serialize($detail);			
				$table='tbl_faqs';
				$this->commonmodel->_update($table, $detail,array('faq_id' => $id));
				$created_date  = date('Y-m-d H:i:s');
				$lg_data 	= 	array(
									'question' => $post['question'],
									'answer'  => $post['answer']
								);
				$this->session->set_flashdata('flashSuccess','Faq Has Been Updated Successfully');
				redirect('admin/faq');			
		}
		$data['detail']=$this->Website_model->getFaqDetail($id);
		$this->layout->view("admin/cms_pages/edit_faq", $data);
	}

	public function delete_faq($id){
		$table='tbl_faqs';
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

	public function edit_contact(){
		$data['title'] = 'Edit Contact';
		$table='tbl_contact';
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		if($this->input->post()){
			$post=$this->input->post();
				$mod_date=date('Y-m-d H:i:s');
				$detail = 	array(
								'mobile_no' => $post['mobile_no'],
								'email'	 => $post['email'],
								'address'=> trim($post['address']),
								'address_gr'=> trim($post['address_gr']),
								'address_tr'=> trim($post['address_tr']),
								'modified_on'	=>	$mod_date
							);
				
				$data=serialize($detail);			
				$table='tbl_contact';
				$this->commonmodel->_update($table, $detail,array('id' => 1));
				$created_date  = date('Y-m-d H:i:s');
				$lg_data 	= 	array(
									'mobile_no' => $post['mobile_no'],
									'email'  => $post['email']
								);
				$this->session->set_flashdata('flashSuccess','Contact Details Has Been Updated Successfully');
				redirect('admin/edit-contact');			
		}
		$data['detail']=$this->Website_model->getContactDetail();
		$this->layout->view("admin/cms_pages/edit_contact", $data);
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
		
		
		$del_lg_user = $this->Website_model->getAllDeleteUsersdetail($id);
		
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
		
		$data = $this->Website_model->check_user_email($user_email);
		
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
	
	function unsetsearchsession(){
	
		$this->session->unset_userdata('user_types');
		$html = 1;
		echo $html;
		die();
	}
	
	public function rsm_sales_person_view(){
		$data['title']     = 'Rsm Sales Person Detail';
		$table			   = 'users';
		$user_id		   = $this->uri->segment(4);
		$id				   = base64_decode($user_id);
		
		$data['users']         =   $this->Website_model->getUsersDetail($id);
		$this->layout->view("admin/rsm_sales_person/rsm_sales_person_view", $data);
			
	}
	
	public function check_edit_useremail(){
		$json=array();
		$email 	= $this->input->post('emailid');
		
		$user_id = '';
		if($this->input->post('user_id'))
		{
			$user_id = $this->input->post('user_id');
		}
		$count = $this->Website_model->check_edit_useremail($email,$user_id);
		
		if($count > 0){
				$isAvailable = false; 
				$json=array('valid' => $isAvailable	);
		}else{
				$isAvailable = true; 
				$json=array('valid' => $isAvailable	);
		}
		echo json_encode($json);
		exit;
	}
	
	public function users_change_password(){
		$data['title'] 		= 'Change Password User';
		$user_id		    = $this->uri->segment(3);
		$u_id			    = base64_decode($user_id);
	
		if($this->input->post())
		{
			
			$table	='users';
			$userid = $this->site_santry->get_auth_data('id');
			$post =$this->input->post();
			
			
				$detail = array(
									'password'		=> md5($post['password']),
								);
						
				$this->commonmodel->_update($table, $detail, array('user_id'=>$u_id));
				/* if($u_id>0){
					$lg_key = array(
									'login_key'		=> '',
								);
				 
				}
				$this->commonmodel->_update($table, $lg_key, array('user_id'=>$u_id));	 */
				$mod_date  = date('Y-m-d H:i:s');
				$user_id    = $this->site_santry->get_auth_data('id');
				$data=serialize($detail);
				$lg_user = get_user($u_id);
		        $detail 	= 	array(
									'user_id'                  => $user_id,
									'from_id'                  => $user_id,
						            'to_id'                    => $u_id,
									'type'			           => 'change_user_password',
									'comment'			       => $lg_user['first_name'].' '.$lg_user['last_name'].'('.$lg_user['role'].') has changed password',
									'log_content'			   => $data,
									'created_date'             => $mod_date
								);
				
		        $this->commonmodel->_insert('log_listing', $detail);
				$this->session->set_flashdata('flashSuccess','Password Has Been Updated Successfully ');
			    redirect('admin/profile');
		}else{
			redirect('admin/profile');
		}			
		//$this->layout->view("admin/user_profile/users_change_password", $data);
	}
	
	public function change_password(){
		$data['title'] 		= 'Change Password';
		/* $user_id		    = $this->uri->segment(3);
		$u_id			    = base64_decode($user_id); */
	
		if($this->input->post())
		{
			$validation_post = array(
				  array('field' => 'old_password', 'label' =>'Old Password', 'rules' => 'trim|required'),
				  array('field' => 'password', 'label' =>'Password', 'rules' => 'trim|required'),
				  array('field' => 'confirmpassword', 'label' =>'Confirm Password', 'rules' => 'required|matches[password]'),
                );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$table	='admin_users';
				$userid = $this->site_santry->get_auth_data('id');
				//pr($this->site_santry->get_auth_data(),1);
				//echo $userid; die;
				$post =$this->input->post();
				
				$this->db->where('user_id',$userid);
				$this->db->where('user_pass',md5($post['old_password']));
				$check = $this->db->get('admin_users');
				if($check->num_rows()>0)
				{
					$detail = array(
									'user_pass'		=> md5($post['password']),
								);
					$this->commonmodel->_update($table, $detail, array('user_id'=>$userid));
					$this->session->set_flashdata('flashSuccess','Password Has Been Updated Successfully ');
					$this->session->set_flashdata('changePasswordTab','true');
					redirect('admin/profile');
				}else{
					$this->session->set_flashdata('flashError','Incorrect Old Password!');
					$this->session->set_flashdata('changePasswordTab','true');
					redirect('admin/profile');
				}
			}else{
				$this->session->set_flashdata('flashError',validation_errors());
				$this->session->set_flashdata('changePasswordTab','true');
				redirect('admin/profile');
			}
		}else{
			redirect('admin');
		}
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
	
	public function sendemail($to,$subject,$message){
		require_once('smtp/class.phpmailer.php');
		$HOST_NAME 	= HOST_NAME;
		$USER_NAME 	= USER_NAME;
		$PASSWORD 	= PASSWORD;
		$PORT_NO 	= PORT_NO;
		$FROM_NAME 	= FROM_NAME;
		$FROM 		= FROM;
		$crlf 		= "\n";
		$pos='';
		if($pos !=false){
		    // echo $headers; die;
			$headers = "From: ". $FROM."\r\nReply-To: ". $FROM."\r\n";
			$headers  .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			//$headers .=$FROM;
			$to =$to;
			$subject = $subject;
			$message = $message;
			$from = $FROM;
			//$headers .= "From:" . $FROM; 
			mail($to,$subject,$message,$headers);
			//echo $headers; die;
		}else{
			$from_name=$FROM_NAME; 
			$from=$FROM;
			$mail             		= 	new PHPMailer();
			$mail->CharSet  		= 	'UTF-8';
			$mail->Encoding 		= 	'quoted-printable';
			$body            		= 	$message;
		    $mail->IsSMTP(); // telling the class to use SMTP
			/*$mail->Host      		= 	"smtp.gmail.com"; // SMTP server
			$mail->Port      		= 	"465"; // SMTP port*/
			$mail->SMTPSecure		= 	"ssl"; // SMTP secure
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host      		= 	$HOST_NAME; // SMTP server
			$mail->Port      		= 	$PORT_NO; // SMTP port
			//$mail->SMTPSecure		= 	"ssl"; // SMTP secure
			$mail->From      		= 	$from;
			$mail->FromName   		= 	$from_name;
			$mail->IsHTML(true); 
			//if ($auth)
		//	{
				$mail->SMTPAuth    = true;
				$mail->Username    = $USER_NAME; // in some servers @ will be replaced with +
				$mail->Password    = $PASSWORD;
		//	}
			$mail->Subject    = $subject;
			$body;
			$mail->MsgHTML($body);
			$mail->AddAddress($to);
			if(!empty($cc_mail_str)){
				$addr = explode(',',$cc_mail_str);
				foreach ($addr as $ad) {
					$mail->AddAddress( trim($ad) );       
				}
			}
			if(!$mail->Send()) {
				$headers ='';
				$headers .= "Reply-To: ".$FROM_NAME." <".$FROM.">\r\n";
				$headers .= "Return-Path: ".$FROM_NAME." <".$FROM.">\r\n";
				$headers .= "From: ".$FROM_NAME." <".$FROM.">\r\n"; 
				$headers .= "Organization: ".$FROM_NAME." \r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "X-Priority: 3\r\n";
				$headers .= "X-Mailer: PHP". phpversion() ."\r\n" ;
				if (mail($to, $subject,$message,$headers)) {
					return true; 
				} else {
					if (stripos($mail->ErrorInfo, 'Data not accepted') === false)
					echo "Mailer Error: " . $mail->ErrorInfo;
					return false;
				}
			} else {
					return true;
			}
		} 
	}

	public function dashboard(){
		$data['title'] = SITE_NAME.' - Dashboard';
		
		$data['totalProducts'] = $this->Website_model->getTotalProducts();
		$data['totalActiveUser'] = $this->Website_model->getTotalActiveUser();
		$data['totalInactiveUser'] = $this->Website_model->getTotalInactiveUser();

		$this->layout->view("admin/dashboard", $data);
	}


	public function admin_edit_profile(){
		$admin_user_id=$this->site_santry->get_auth_data('id');
		$data['title'] = SITE_NAME.' - Edit Profile';
		$post=$this->input->post();
		if($post){
			$updatedProfile=$this->Website_model->updateAdminProfile($post,$admin_user_id);
			if($updatedProfile){
				$this->session->set_flashdata('flashSuccess','Profile Has Been Updated Successfully');
			}
			else {
				$this->session->set_flashdata('flashError',"Profile couldn't Updated ");
			}	
		}
		$data['detail']=$this->Website_model->getLoggedUsersDetail($admin_user_id);

		$this->layout->view("admin/user_profile/admin_profile", $data);
	}

	public function tax_setting(){
		
		$data['title'] = SITE_NAME.' - Edit Tax Setting';
		$post=$this->input->post();
		if($post){
			$condition = array('id' => 1);
			$this->commonmodel->_update('tbl_tax_setting',array('tax'=>$post['tax']),$condition);
			$this->session->set_flashdata('flashSuccess','Tax Has Been Updated Successfully');
		}
		$data['detail']=$this->Website_model->getTaxSetting();

		$this->layout->view("admin/tax_setting", $data);
	}
	
}
