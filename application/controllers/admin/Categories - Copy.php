<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {

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
		$this->load->model("admin/categories_model");
	}
	
	public function index(){
		
		$data['title'] = 'Categories';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['category_name'] = $this->session->userdata('category_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->categories_model->getAllCategories($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['category_name'] = $this->input->post('category_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('category_name')!=''){
				$filter_session_data['category_name'] = $this->input->post('category_name');
			}else{
					$filter_session_data['category_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/categories/categories_list", $data);
		}		
	}	
	
	public function do_upload_csv_file($submited_name)
	{
		
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key;
		$folderPath = UPLOAD_PHYSICAL_PATH.'sub-category-details-csv/';
		if(!is_dir($folderPath))
		{
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'csv';
		$config['max_size']             = 2048;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name))
		{
			return str_replace('</p>','', str_replace('<p>','', $this->upload->display_errors()));
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}	
	
	public function add_category(){	
		$data['title'] = 'Add Category';		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
				
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'category_name', 'label' =>'Category Name', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				$alredyExits = $this->commonmodel->_get_data_row('category_name','categories',array('category_name' => $post['category_name']));
				if(!$alredyExits){
					if(isset($_FILES) && $_FILES['category_image']['name'] != "") {
						 $upload_data = $this->do_upload_category($referrer,'category_image');
						 $post['category_image'] = $upload_data['upload_data']['file_name'];
				 	}
				 	$addrecord = $this->categories_model->addCategories($post);
					if($addrecord) {
						$this->session->set_flashdata('flashSuccess','Category Has Been Added Successfully');
						redirect('admin/categories');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
						redirect($referrer);
					}

				}else {
					$this->session->set_flashdata('flashError','Category Name Already Exits....');
					redirect($referrer);
				}
				
			}
		}	
		$this->layout->view("admin/categories/add_category", $data);
	}

	public function edit_category($category_id){
		$data['title'] = 'Edit Car Type';
		$category_id		  = base64_decode($category_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'category_name', 'label' =>'Category Name', 'rules' => 'trim|required'),
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				
					$alredyExits = $this->commonmodel->_get_data_row('category_name','categories',array('category_name' => $post['category_name'],'category_id != '=>$category_id,'delete_status'=>'0'));
					if(!$alredyExits){
						if(isset($_FILES) && $_FILES['category_image']['name'] != "") {
							 $upload_data = $this->do_upload_category($referrer,'category_image');
							 $post['category_image'] = $upload_data['upload_data']['file_name'];
					 	}
						$updaterecord = $this->categories_model->updateCategories($post,$category_id);
						if($updaterecord) {
							$this->session->set_flashdata('flashSuccess','Category Has Been Updated Successfully');
							redirect('admin/categories');
						}else{
							$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
							redirect($referrer);
						}
					}
					else {
						$this->session->set_flashdata('flashError','Category Name Already Exits');
							redirect($referrer);
					}
			}
		}
		$data['category_details']=$this->categories_model->getCategoryDetailsById($category_id);	
		$this->layout->view("admin/categories/edit_category", $data);
	}

	public function deleteCategories($category_id){
		$table='categories';
		$category_id=base64_decode($category_id);
		
		$condition		= array("category_id"=>$category_id);
		$detail = array('delete_status'		=> 	 '1');
		$deletedType=$this->commonmodel->_update($table,$detail,$condition);
		if($deletedType){
			$this->session->set_flashdata('flashSuccess','Car type Has Been Deleted Successfully');
			redirect('admin/categories');
		}else{
			$this->session->set_flashdata('flashError','Something Went wrong ! please try again later');
			redirect($referrer);
		}	
	}
	
	public function do_upload_category($referrer,$submited_name)
	{
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'categories/';
		if(!is_dir($folderPath)) {
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'png|jpeg|jpg';
		$config['max_size']             = 2014; // 1 mb
		/* $config['max_width']            = 50;
		$config['max_height']           = 50;
		$config['min_width']            = 50;
		$config['min_height']           = 50; */
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name)) {
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}

	public function do_upload($referrer,$submited_name) {
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'categories/';
		if(!is_dir($folderPath)) {
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = '*';
		//$config['max_size']             = 1024;
		//$config['max_width']            = 1024;
		//$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name)) {
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
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
	
	public function change_activation_status(){
		$category_id_encode   		= $this->uri->segment(4);
		$category_id		  		= base64_decode($category_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('category_id' => $category_id);
		$this->commonmodel->_update('categories',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect('admin/categories');
	}

	public function manage_tokens(){
		$data['title'] = 'Manage Tokens';
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'daily_bonus_tokens', 'label' =>'Daily Bonus Tokens', 'rules' => 'trim|required'),
				  array('field' => 'watch_per_ad_tokens', 'label' =>'Watch ads Tokens(Tokens Per Ad)', 'rules' => 'trim|required'),
				  array('field' => 'renewal_days', 'label' =>'Renewal Days', 'rules' => 'trim|required'),
				  //array('field' => 'tokens_in_one_usd', 'label' =>'1 USD In Tokens', 'rules' => 'trim|required'),
				  array('field' => 'signup_reward_tokens', 'label' =>'Signup Reward Tokens', 'rules' => 'trim|required'),
				  array('field' => 'refferal_reward_tokens', 'label' =>'Refferal Reward Tokens', 'rules' => 'trim|required'),
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{	
				$alredyExits = $this->commonmodel->_get_data_row('id','settingmaster',array('id' => '1'));
				if($alredyExits){
					$updaterecord = $this->categories_model->updateTokendetails($post);
					if($updaterecord) {
						$this->session->set_flashdata('flashSuccess','Details Has Been Updated Successfully');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
					}
				}else {
					$this->session->set_flashdata('flashError','Settingmaster is not available in database.Please contact with developer!');
				}
				redirect($referrer);
			}
		}
		$data['details']=$this->categories_model->getTokenDetails();
		//pr($data,1);
		$this->layout->view("admin/tokens/manage_tokens", $data);
	}
	
	public function token_amount_listing()
	{	
		$data['title'] ="Token Amounts";
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['post']	=  $this->input->post();
			$data['type']	=  'TOKEN';
			//pr($data,1);
			$allrecord      = $this->categories_model->getTokenAmountListing($data);
			echo json_encode($allrecord);
		}else{
			$this->layout->view("admin/tokens/token_amounts_list", $data);
		}			
	}
	public function subscription_amount_listing()
	{	
		$data['title'] ="Subscription Amounts";
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['post']	=  $this->input->post();
			$data['type']	=  'SUBSCRIPTION';
			//pr($this->input->post(),1);
			$allrecord      = $this->categories_model->getTokenAmountListing($data);
			echo json_encode($allrecord);
		}else{
			$this->layout->view("admin/tokens/subscriptions_amount_list", $data);
		}			
	}
	
	public function change_token_amount_activation_status()
	{
		
		$token_amount_id_encode   		= $this->uri->segment(4);
		$token_amount_id		  		= base64_decode($token_amount_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('token_amount_id' => $token_amount_id);
		$this->commonmodel->_update('token_amounts',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect($referrer);
	}	
	
	
	public function add_token(){	

		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
				
		if($this->input->post()){
			$post = $this->input->post();	
			//pr($post,1);die;
			$validation_post = array(
				  array('field' => 'tokens', 'label' =>'Token Quantity', 'rules' => 'trim|required'),
				  array('field' => 'token_amount', 'label' =>'Token Amount', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			//var_dump($this->form_validation->run()); die();
			if ($this->form_validation->run() === TRUE) {
				if(isset($_FILES) && $_FILES['image']['name'] != "") {
					 $upload_data = $this->do_upload_tokens($referrer,'image');
					 $post['image'] = $upload_data['upload_data']['file_name'];
				}
			 	$addrecord = $this->categories_model->addToken($post);
				if($addrecord) {
					$this->session->set_flashdata('flashSuccess','Token Has Been Added Successfully');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}
			redirect($referrer);
		}
		$data['details']=$this->categories_model->getTokenDetails();
		//pr($data,1);
		$this->layout->view("admin/tokens/manage_tokens", $data);
	}
	
	public function add_subscription(){	
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
				
		if($this->input->post()){
			$post = $this->input->post();	
			//pr($post,1);die;
			$validation_post = array(
				  array('field' => 'tokens1', 'label' =>'Subscription Token Quantity', 'rules' => 'trim|required'),
				  array('field' => 'token_amount1', 'label' =>'Subscription Token Amount', 'rules' => 'trim|required'),
			);
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			//var_dump($this->form_validation->run()); die();
			if ($this->form_validation->run() === TRUE) {
				if(isset($_FILES) && $_FILES['image1']['name'] != "") {
					 $upload_data = $this->do_upload_tokens($referrer,'image1');
					 $post['image'] = $upload_data['upload_data']['file_name'];
				}
				//pr($post,1);die;
			 	$addrecord = $this->categories_model->addSubscription($post);
				if($addrecord) {
					$this->session->set_flashdata('flashSuccess','Subscription Has Been Added Successfully');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}
			redirect($referrer);
		}
		$data['details']=$this->categories_model->getTokenDetails();
		//pr($data,1);
		$this->layout->view("admin/tokens/manage_tokens", $data);
	}

	public function edit_token($token_amount_id){
		$data['title'] = 'Update Token Details';
		$token_amount_id		  = base64_decode($token_amount_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();
			//pr($post,1);			
			$validation_post = array(
				  array('field' => 'tokens', 'label' =>'Token Quantity', 'rules' => 'trim|required'),
				  array('field' => 'token_amount', 'label' =>'Token Amount', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				
				$alredyExits = $this->commonmodel->_get_data_row('token_amount_id','token_amounts',array('token_amount_id'=>$token_amount_id,'delete_status'=>'0'));
				if($alredyExits){
					if(isset($_FILES) && isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
						 $upload_data = $this->do_upload_tokens($referrer,'image');
						 $post['image'] = $upload_data['upload_data']['file_name'];
					}
					$updaterecord = $this->categories_model->updateTokenAmountDetails($post,$token_amount_id);
					if($updaterecord) {
						$this->session->set_flashdata('flashSuccess','Token Amount Details Has Been Updated Successfully');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
					}
				}else {
					$this->session->set_flashdata('flashError','Token not found in system!');
				}
				redirect($referrer);
			}
		}
		$data['token_details']=$this->categories_model->getTokenDetailsById($token_amount_id);	
		$this->layout->view("admin/tokens/edit_token", $data);
	}
	
	public function edit_subscription($token_amount_id){
		$data['title'] = 'Update Token Details';
		$token_amount_id		  = base64_decode($token_amount_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();
			//pr($post,1);			
			$validation_post = array(
				  array('field' => 'tokens', 'label' =>'Token Quantity', 'rules' => 'trim|required'),
				  array('field' => 'token_amount', 'label' =>'Token Amount', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				
				$alredyExits = $this->commonmodel->_get_data_row('token_amount_id','token_amounts',array('token_amount_id'=>$token_amount_id,'delete_status'=>'0'));
				if($alredyExits){
					if(isset($_FILES) && isset($_FILES['image1']['name']) && $_FILES['image1']['name'] != "") {
						 $upload_data = $this->do_upload_tokens($referrer,'image1');
						 $post['image'] = $upload_data['upload_data']['file_name'];
					}
					$updaterecord = $this->categories_model->updateTokenAmountDetails($post,$token_amount_id);
					if($updaterecord) {
						$this->session->set_flashdata('flashSuccess','Subscription Amount Details Has Been Updated Successfully');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
					}
				}else {
					$this->session->set_flashdata('flashError','Subscription not found in system!');
				}
				redirect($referrer);
			}
		}
		$data['token_details']=$this->categories_model->getTokenDetailsById($token_amount_id);	
		$this->layout->view("admin/tokens/edit_subscription", $data);
	}
	
	public function do_upload_tokens($referrer,$submited_name)
	{
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'tokens/';
		if(!is_dir($folderPath)) {
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'png';
		$config['max_size']             = 2048; // 2 mb
		$config['max_width']            = 50;
		$config['max_height']           = 50;
		$config['min_width']            = 50;
		$config['min_height']           = 50;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name)) {
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}
	
	public function user_subscriptions()
	{	
		$data['title'] = 'User Subscriptions';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['category_name'] = $this->session->userdata('category_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->categories_model->getAllUserSubscriptions($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['category_name'] = $this->input->post('category_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('category_name')!=''){
				$filter_session_data['category_name'] = $this->input->post('category_name');
			}else{
					$filter_session_data['category_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/subscriptions/subscriptions_list", $data);
		}		
	}	
	
	public function change_user_subscription_activation_status()
	{
		
		$subscription_id_encode   		= $this->uri->segment(4);
		$subscription_id		  		= base64_decode($subscription_id_encode);
		$activation_status				= base64_decode($this->uri->segment(5));
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('subscription_id' => $subscription_id);
		$this->commonmodel->_update('subscriptions',array('status'=>$change_activation_status),$condition);
		//echo $this->db->last_query();die;
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect($referrer);
	}	
	
	public function user_tokens($user_id='')
	{	
		$data['title'] = 'User Tokens';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['user_name_email'] 	= $this->session->userdata('user_name_email');	
			$data['purchase_date'] 		= $this->session->userdata('purchase_date');	
			$data['earning_type'] 		= $this->session->userdata('earning_type');	
			$data['post']		=  $this->input->post();
			$data['user_id']	=  "";
			$allrecord      = $this->categories_model->getAllUserTokens($data);
			echo json_encode($allrecord);
		}else{
			$data['user_name'] = '';
			$data['emailid'] = '';
			if($this->uri->segment(3)!='')
			{
				$user_id = base64_decode($this->uri->segment(3));
				$userDetails = $this->commonmodel->_get_data('tbl_usermaster',array('id'=>$user_id),'full_name,emailid');
				$data['user_name'] = ucwords(strtolower($userDetails[0]['full_name']));
				$data['emailid'] = $userDetails[0]['emailid'];
			}
			
			$data['user_name_email'] 		= $this->input->post('user_name_email');
			$data['purchase_date'] 			= $this->input->post('purchase_date');
			$data['earning_type'] 			= $this->input->post('earning_type');
			
			if($this->input->post('user_name_email')!='' || $this->input->post('purchase_date')!='' || $this->input->post('earning_type')!=''){
				$filter_session_data['user_name_email'] = $this->input->post('user_name_email');
				$filter_session_data['purchase_date'] = $this->input->post('purchase_date');
				$filter_session_data['earning_type'] = $this->input->post('earning_type');
			}else{
				$filter_session_data['user_name_email'] = '';
				$filter_session_data['purchase_date'] = '';
				$filter_session_data['earning_type'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$data['filter_earning_types']=array(''=>'View All','DAILY_REWARD'=>'Daily Rewards','WATCH_AD'=>'Watch Ad','PURCHASED'=>'Purchased','REFER_EARN'=>'Reffer Earn','SIGNUP_REWARD'=>'Signup Reward','SECONDARY_REWARD'=>'Secondary Reward');
			$this->layout->view("admin/tokens/user_tokens_list", $data);
		}		
	}	
}
