<?php
require APPPATH.'config/rest.php';
require APPPATH.'libraries/REST_Controller.php';
class Userapi extends REST_Controller{
	
	private $user_id ="";
	private $paypal_id ="";
	public $language ="";
	/*
	|--------------------------------|
	|HTTP Response Codes
	|--------------------------------|
	| 400 : Bad request
	| 403 : Validation Errors
	| 204 : No data Found
	| 200 : Success
	| 308 : Try Again
	| 203 : information doesnt match
	| 304 : duplicate entry;
	| 405 : Wrong Access token!
	| 406 : Invalid Login Key for this user or Login Key is required
	|--------------------------------|
	*/
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Commonmodel');
		$this->load->helper('general');
		$this->load->helper('basic');
		//$this->load->model('Wooapi_model');
		$this->load->model('User_model');
		$this->secretkey = 'mymatch457d869';
		
		$headers=array();
		foreach (getallheaders() as $name => $value) {
			$headers[$name] = $value;
		}
		//pr($headers,1);
		
		//$this->get_error_log($this->input->post());
		//$this->get_error_log($headers);
		$language ="";
		$login_key ="";
		$access_token ="";
		if(isset($headers['Login-Key']) && $headers['Login-Key']!=""){
			$login_key = $headers['Login-Key'];
		}
		if(isset($headers['Lang']) && $headers['Lang']!=""){
			$language = $headers['Lang'];
			$langArr = array('en','gr','EN','GR');
			$lang = $headers['Lang'];
			if(in_array($lang,$langArr))
			{
				if($lang == 'GR' || $lang == 'gr'){
					$language = 'german';
				}else{
					$language = 'english';
				}
				$this->language = $language;
				//pr($language,1);
				$this->lang->load("api_messages_lang",$language);
			}else{
				$response['status'] = 400;
				$response['error'] = true;
				$response['message'] = 'This language is not allowed!';
				echo json_encode($response, JSON_UNESCAPED_SLASHES);die;
			}
		}else{
			$response['status'] = 400;
			$response['error'] = true;
			$response['message'] = 'Lang is missing';
			echo json_encode($response, JSON_UNESCAPED_SLASHES);die;
		}
		if(isset($headers['Access-Token']) && $headers['Access-Token']!=""){
			$access_token = $headers['Access-Token'];
		}else{
			$post['message'] = "Access token is Required!";
			$post['resultCode'] = '403';
			$data = $post;
			$this->response($data);
		}
		//pr($access_token,1);
		$tokenCheckStatus = false;
		$apiname = $this->uri->segment(2);
		//pr($apiname,1);
		if($apiname!=""){
			if($access_token != "" && $access_token == $this->secretkey){
				if(!(($apiname =='userSignin') || ($apiname =='dailySigninReward') || ($apiname =='testPushNotification') || ($apiname =='contact_us') || ($apiname =='userSignup') || ($apiname =='resendVerificationEmail') || ($apiname =='forgotPassword') || ($apiname =='resendOtp') || ($apiname =='checkVarificationCode') || ($apiname =='getCmsPage')))
				{
					//echo $login_key;die;
					if($login_key!='')
					{
						$this->db->select('id,paypal_id');
						$this->db->from('tbl_usermaster');
						$this->db->where('status',1); 
						$this->db->where('login_key',$login_key); 
						$this->db->where('delete_status','0');
						$query = $this->db->get();
						
						if($query->num_rows()>0)
						{
							$userData 	=  $query->row_array();
							$this->user_id 	= $userData['id'];
							$this->paypal_id 	= $userData['paypal_id'];
							//echo $user_id;die;
						}else{
							$post['message'] = $this->lang->line('invalid_login_key');
							$post['resultCode'] = '406';
							$data = $post;
							$this->response($data);
						}
					}else{
						$post['message'] = $this->lang->line('login_key_required');
						$post['resultCode'] = '406';
						$data = $post;
						$this->response($data);
					}
				}
			}else{
				$post['message'] = "Wrong Access token!";
				$post['resultCode'] = '405';
				$data = $post;
				$this->response($data);
			}
		}else{
			$post['message'] = "Something went wrong!";
			$post['resultCode'] = '403';
			$data = $post;
			$this->response($data);
			
		}
	}
	
	public function userSignup(){
		$validation_messge = array();
		$status      = true;
		
		$emailid='';
		$full_name='';
		$phone_number='';
		$password='';
		$confirm_password='';
		//$signup_type='';
		$refferer_code='';
		//$social_id='';
		$device_type='';
		$device_id='';
		$fcm_id='';
		
		$postData = $this->post();
		//pr($postData,1);
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		/*if($signup_type == ''){
		    $validation_messge[] = ' signup_type is missing';
			$status = false;
		}
		if($signup_type!="APP"){
			if($social_id==""){
				$validation_messge[] = 'social_id is missing';
				$status = false;
			}
		}
		if($signup_type=="APP"){*/
			if($emailid == ''){
				$validation_messge[] = $this->lang->line('email_id_required');
				$status = false;
			}
			if($full_name == ''){
				$validation_messge[] = ' full_name is missing';
				$status = false;
			}
			/*if($user_name == ''){
				$validation_messge[] = ' user_name is missing';
				$status = false;
			}
			if($phone_number == ''){
				$validation_messge[] = ' phone_number is missing';
				$status = false;
			}*/
			if($password == ''){
				$validation_messge[] = $this->lang->line('password_required');
				$status = false;
			}
			if($confirm_password == ''){
				$validation_messge[] = 'confirm_password is missing';
				$validation_messge[] = $this->lang->line('confirm_password_required');
				$status = false;
			}else{
				if($password != $confirm_password){
					$validation_messge[] = $this->lang->line('confirm_password_not_matched');
					$status = false;
				}
			}
		/*}*/
		/*if($refferer_code !=""){
			$this->db->select('id');
			$this->db->from('tbl_usermaster');
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$this->db->where('referral_code',$refferer_code);
			$refferquery = $this->db->get();
			if($refferquery->num_rows()<=0){
				$validation_messge[] = 'Invalid Refferal Code!';
				$status = false;
			}
		}*/
		if($status){
			//$result = $this->User_model->userSignup($emailid,$full_name,$phone_number,$password,$signup_type,$refferer_code,$social_id,$device_type,$device_id,$fcm_id);
			$signup_type = 'APP';
			$social_id = '0';
			$result = $this->User_model->userSignup($emailid,$full_name,$phone_number,$password,$signup_type,$refferer_code,$social_id,$device_type,$device_id,$fcm_id);
			if($result['status'] =="200")
			{
				$post['message'] 		= $result['message'];
				$post['resultCode'] 	= $result['status'];
				$post['user_id'] 		= $result['user_id'];
				if($signup_type!="APP"){
					$post['user_detail'] 	= $result['user_detail'];
					$post['account_details']= $result['account_details'];
					$post['login_key'] 		= $result['login_key'];
				}
				$data = $post;
			}else{
				$post['message'] 	= $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
			
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function dailySigninReward(){
		$validation_messge = array();
		$status      = true;
		
		$user_id = '';
		$postData = $this->post();
		//pr($postData,1);
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->dailySigninReward($user_id);
			if($result['status'] =="200")
			{
				$post['message'] 			=   $result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['token_purchase_id'] 	= 	$result['token_purchase_id'];
				$post['daily_bonus_tokens'] = 	$result['daily_bonus_tokens'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
			
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function adWatchReward(){
		$validation_messge = array();
		$status      = true;
		
		$postData = $this->post();
		//pr($postData,1);
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->adWatchReward($user_id);
			if($result['status'] =="200")
			{
				$post['message'] 			=   $result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['token_purchase_id'] 	= 	$result['token_purchase_id'];
				$post['ad_watch_tokens'] 	= 	$result['ad_watch_tokens'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
			
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function userSignin(){
		$validation_messge = array();
		$status      = true;
		
		$emailid='';
		$password='';
		$device_id='';
		$fcm_id='';
		$device_type='';
				
		$postData = $this->post();
		//pr($postData,1);
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		//pr($country_code,1);
		if($emailid == ''){
			$validation_messge[] = ' emailid is missing';
			$status = false;
		}
		
		if($password == ''){
		    $validation_messge[] = ' password is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->userSignin($emailid,$password,$device_type,$device_id,$fcm_id);
			if($result['status'] =="200")
			{
				$post['message'] 			=   $result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['user_detail'] 		= 	$result['user_detail'];
				$post['account_details'] 	= 	$result['account_details'];
				$post['login_key'] 			= 	$result['login_key'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
			
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function deleteBankAccount(){
		$validation_messge 	= array();
		$status      		= true;
		
		$postData = $this->post();
		$account_id = '';
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($account_id == ''){
			$validation_messge[] = 'account_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->deleteBankAccount($user_id,$account_id);  
			if($result['status'] =="200")
			{
				$post['message'] 	= 	$result['message'];
				$post['resultCode'] = 	$result['status'];				
				$data = $post;
			}else{
				$post['message'] 	= $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function deleteAccount(){
		$validation_messge 	= array();
		$status      		= true;
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->deleteAccount($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function enterRefferalCode(){
		$validation_messge 	= array();
		$status      		= true;
		$referal_code = "";
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($referal_code == ''){
			$validation_messge[] = 'referal_code is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->enterRefferalCode($user_id,$referal_code);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_detail'] 		= 	$result['user_detail'];
				$post['account_details'] 	= 	$result['account_details'];	
				$post['user_tokens'] 		= 	$result['user_tokens'];	
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function resendVerificationEmail(){
		$validation_messge = array();
		$status      = true;
		
		$email_id = '';
		
		$post = $this->input->post();
		foreach($post as $key => $fields)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($email_id == ''){
			$validation_messge[] = ' email_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->resendVerificationEmail($email_id); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getProfile(){
		$validation_messge 	= array();
		$status      		= true;
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($status){
			$result = $this->User_model->getProfile($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_detail'] 		= 	$result['user_data'];
				$post['account_details'] 	= 	$result['account_details'];
				$post['user_id'] 			= 	$result['user_id'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}	
	
	public function completeProfile(){
		$validation_messge 	= array();
		$status      		= true;
		
		$user_name='';
		$full_name='';
		$lastname='';
		$emailid='';
		$phone_number='';
		$address='';
		$address2='';
		$zip_code='';
		$city='';
		$state='';
		$password='';
		$postData = $this->post();
		//pr($this->user_id,1);
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		/*if($user_name == ''){
			$validation_messge[] = ' Please Enter User name!';
			$status = false;
		}*/
        if($full_name == ''){
			$validation_messge[] = ' Please Enter full_name';
			$status = false;
		}
		/*
		if($lastname == ''){
			$validation_messge[] = 'Please Enter last name';
			$status = false;
		}
		if($emailid == ''){
			$validation_messge[] = 'Please Enter email id';
			$status = false;
		}
		if($phone_number == ''){
			$validation_messge[] = 'Please Enter phone number';
			$status = false;
		}
		if($address == ''){
			$validation_messge[] = 'Please Enter address';
			$status = false;
		}
		if($zip_code == ''){
			$validation_messge[] = 'Please Enter zip_code';
			$status = false;
		}
		if($city == ''){
			$validation_messge[] = 'Please Enter city';
			$status = false;
		}
		if($state == ''){
			$validation_messge[] = 'Please Enter state';
			$status = false;
		}*/
		if($status){
			$result = $this->User_model->completeProfile($user_id,$user_name,$full_name,$lastname,$emailid,$phone_number,$address,$address2,$zip_code,$city,$state,$password);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_detail'] 		= 	$result['user_data'];
				$post['account_details'] 	= 	$result['account_details'];
				$post['user_id'] 			= 	$result['user_id'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function requestWinningAmount(){
		$validation_messge 	= array();
		$status      		= true;
		
		$type=''; //ACCOUNT|PAYPAL
		$account_id='';
		$room_winner_id='';
		$credit_to_amount='';
		$credit_to_app_balance='';
		$postData = $this->post();
		//pr($this->user_id,1);
		$user_id = $this->user_id;
		$paypal_id = $this->paypal_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($type == ''){
			$validation_messge[] = ' type is missing!';
			$status = false;
		}else{
			if(!($type=='ACCOUNT'||$type=='PAYPAL'))
			{
				$validation_messge[] = ' invalid type.Two types allowed - ACCOUNT|PAYPAL!';
				$status = false;
			}
		}
		if($type=='ACCOUNT')
		{
			if($account_id == ''){
				$validation_messge[] = ' account_id is missing!';
				$status = false;
			}
		}else{
			if($this->paypal_id == ''){
				$validation_messge[] = ' Paypal details are blank. Please fill paypal details first!';
				$status = false;
			}
		}
		
		if($credit_to_amount == ''){
			$validation_messge[] = ' credit_to_amount is missing!';
			$status = false;
		}
		if($credit_to_app_balance == ''){
			$validation_messge[] = ' credit_to_app_balance is missing!';
			$status = false;
		}

		if($room_winner_id == ''){
			$validation_messge[] = ' room_winner_id is missing!';
			$status = false;
		}else{
			$alredyExits = $this->commonmodel->_get_data('room_winners',array('user_id' => $user_id,'room_winner_id' => $room_winner_id));
			if($alredyExits==NULL)
			{
				$validation_messge[] = ' room_winner_id is incorrect for this user!';
				$status = false;
			}else{
				if($alredyExits[0]['room_winner_status'] != 'PENDING')
				{
					$validation_messge[] = ' You have already requested for this winning!';
					$status = false;	
				}else{
					$winning_amount = $alredyExits[0]['winning_amount'];
					$totalRequestedAmount = $credit_to_amount+$credit_to_app_balance;
					if($totalRequestedAmount!=$winning_amount)
					{
						$validation_messge[] = ' sum of credit_to_amount and credit_to_app_balance is not matching with winning amount!';
						$status = false;
					}
				}
			}
		}
	
		if($status){
			$result = $this->User_model->requestWinningAmount($user_id,$type,$account_id,$paypal_id,$room_winner_id,$credit_to_amount,$credit_to_app_balance);
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function changePassword(){
		$validation_messge 	= array();
		$status      		= true;
		
		$password='';
		$postData = $this->post();
		//pr($this->user_id,1);
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		if($password == ''){
			$validation_messge[] = ' Please Enter password';
			$status = false;
		}
		if($status){
			$result = $this->User_model->changePassword($user_id,$password);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_data'] 			= 	$result['user_data'];
				$post['account_details'] 	= 	$result['account_details'];
				$post['user_id'] 			= 	$result['user_id'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function saveIRSInformation(){
		$validation_messge 	= array();
		$status      		= true;
		
		$first_name='';
		$last_name='';
		$email='';
		$contact_number='';
		$postData = $this->post();
		//pr($this->user_id,1);
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		if($first_name == ''){
			$validation_messge[] = ' Please Enter first name';
			$status = false;
		}
		if($last_name == ''){
			$validation_messge[] = 'Please Enter last name';
			$status = false;
		}
		if($email == ''){
			$validation_messge[] = 'Please Enter email id';
			$status = false;
		}
		if($contact_number == ''){
			$validation_messge[] = 'Please Enter contact number';
			$status = false;
		}
		if($status){
			$result = $this->User_model->saveIRSInformation($user_id,$first_name,$last_name,$email,$contact_number);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['irs_detail'] 		= 	$result['irs_detail'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function redeemPrormoCode(){
		$validation_messge 	= array();
		$status      		= true;
		
		$promocode = "";
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($promocode == ''){
			$validation_messge[] = ' promocode is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->redeemPrormoCode($user_id,$promocode);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_data'] 			= 	$result['user_data'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['user_tokens'] 		= 	$result['user_tokens'];
				/* $post['promocode_tokens'] 	= 	$result['promocode_tokens']; */
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function updateProfileImage(){
		$validation_messge 	= array();
		$status      		= true;
		
		$profileimage='';
		$post = $this->post();
		foreach($post as $key => $fields)
		{
			${$key}=$this->filter($post[$key]);
		}
		$user_id = $this->user_id;
		
		if(isset($_FILES['profileimage']) && $_FILES['profileimage']['name']!='')
		{
			//print_r('aaaa');die;
			$filename = $_FILES['profileimage']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$file_name = uniqid().'.'.$ext;
			
			$config['file_name']            = $file_name;
			$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'users';
			$config['allowed_types']        = 'jpg|jpeg|png';
			//$config['allowed_types']        = 'png';
			$config['max_size']             = 1024;
			$config['max_width']            = 1024;
			$config['max_height']           = 768;

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('profileimage'))
			{
					$error = array('error' => $this->upload->display_errors());
					$result = 0;
					$this->file_upload_error($error);
			}else{
					$result = 1;
					$profileimage = $file_name;
					$data = array('upload_data' => $this->upload->data());
			}
		}else if($profileimage!=""){
			$image_type="png";
			$file = base64_decode($profileimage);
			$imageName = uniqid().'.'.$image_type;
			$result=$this->User_model->do_upload($file,$imageName);
			if($result==1){
				$profileimage = $imageName; 
				$profileimageT ='true';
			}else{
				$profileimage = ''; 
			}
		}
			
		if($status){
			$result = $this->User_model->updateProfileImage($user_id,$profileimage);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= $result['status'];
				$post['profileimage'] 		= 	$result['profileimage'];
				$post['user_id'] 			= 	$result['user_id'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);   
	}
	
	public function checkVarificationCode()
	{
		$validation_messge = array();
		$status      = true;
		
		$user_id = "";
		$otp = "";
		$postData = $this->input->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($otp == ''){
		    $validation_messge[] = ' otp is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->checkVarificationCode($user_id,$otp);
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= $result['status'];
				$post['result'] 			= 	$result['user_data'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);    
	}
	
	public function forgotPassword(){
		$validation_messge = array();
		$status      = true;
		
		$email_id = '';
		
		$postData = $this->input->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($email_id==''){
			$validation_messge[] = 'email_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->forgotPassword($email_id); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data); 
	}
	
	public function resendOtp(){
		$validation_messge = array();
		$status      = true;
		
		$user_id = '';
		
		$post = $this->input->post();
		foreach($post as $key => $fields)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->resendOtp($user_id); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= $result['status'];
				$post['otp'] 				= 	$result['otp'];
				$post['user_id'] 			= 	$result['user_id'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);   
	}
	
	public function editProfile(){
		$validation_messge 	= array();
		$status      		= true;
		
		//$user_id='';
		$firstname='';
		$lastname='';
		$emailid='';
		$address='';
		$is_default_address='';
		$postData = $this->post();
		//pr($this->user_id,1);
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		if($firstname == ''){
			$validation_messge[] = ' Please Enter first name';
			$status = false;
		}
		if($lastname == ''){
			$validation_messge[] = 'Please Enter last name';
			$status = false;
		}
		if($emailid == ''){
			$validation_messge[] = 'Please Enter email id';
			$status = false;
		}
		if($address == ''){
			$validation_messge[] = 'Please Enter address';
			$status = false;
		}
		if($is_default_address == ''){
			$validation_messge[] = 'Please Select address type';
			$status = false;
			
		}
		if($status){
			$result = $this->User_model->editProfile($user_id,$firstname,$lastname,$emailid,$address,$is_default_address);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= $result['status'];
				$post['user_data'] 			= 	$result['user_data'];
				$post['user_id'] 			= 	$result['user_id'];
				
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);   
	}
	
	public function addBankAccount(){
		$validation_messge 	= array();
		$status      		= true;
		
		$card_number			=	'';
		$expiry_month			=	'';
		$expiry_year			=	'';
		$cvv_number				=	'';
		$card_holder_name		=	'';
		$ifsc_code		=	'';
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;	
		if($bank_name == ''){
			$validation_messge[] = 'Please enter bank_name !';
			$status = false;
		}
		if($card_number == ''){
			$validation_messge[] = 'Please enter card_number !';
			$status = false;
		}
		/*if($expiry_month == ''){
			$validation_messge[] = 'Please enter expiry_month of your card !';
			$status = false;
		}
		if($expiry_year == ''){
			$validation_messge[] = 'Please enter expiry_year of your card !';
			$status = false;
		}
		if($cvv_number == ''){
			$validation_messge[] = 'Please enter cvv_number !';
			$status = false;
		}*/
		if($card_holder_name == ''){
			$validation_messge[] = 'Please enter card_holder_name !';
			$status = false;
		}
		if($ifsc_code == ''){
			$validation_messge[] = 'Please enter ifsc_code !';
			$status = false;
		}
		if($status){
			$result = $this->User_model->addBankAccount($user_id,$card_number,$expiry_month,$expiry_year,$cvv_number,$card_holder_name,$ifsc_code,$bank_name);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['account_id'] 		= 	$result['account_detail']['account_id'];
				$post['bank_name'] 		 	= 	$result['account_detail']['bank_name'];
				$post['account_number'] 		= 	$result['account_detail']['account_number'];
				/*$post['expiry_month'] 		= 	$result['account_detail']['expiry_month'];
				$post['expiry_year'] 		= 	$result['account_detail']['expiry_year'];
				$post['cvv_number'] 		= 	$result['account_detail']['cvv_number'];*/
				$post['card_holder_name'] 	= 	$result['account_detail']['card_holder_name'];
				$post['ifsc_code'] 			= 	$result['account_detail']['ifsc_code'];
				//$post['is_default'] 		= 	$result['account_detail']['is_default'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function updateBankAccount(){
		$validation_messge 	= array();
		$status      		= true;
		
		$account_id			=	'';
		$card_number			=	'';
		$expiry_month			=	'';
		$expiry_year			=	'';
		$cvv_number				=	'';
		$card_holder_name		=	'';
		$ifsc_code		=	'';
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;	
		if($account_id == ''){
			$validation_messge[] = 'Please enter account_id !';
			$status = false;
		}
		if($bank_name == ''){
			$validation_messge[] = 'Please enter bank_name !';
			$status = false;
		}
		if($card_number == ''){
			$validation_messge[] = 'Please enter card_number !';
			$status = false;
		}
		/*if($expiry_month == ''){
			$validation_messge[] = 'Please enter expiry_month of your card !';
			$status = false;
		}
		if($expiry_year == ''){
			$validation_messge[] = 'Please enter expiry_year of your card !';
			$status = false;
		}
		if($cvv_number == ''){
			$validation_messge[] = 'Please enter cvv_number !';
			$status = false;
		}*/
		if($card_holder_name == ''){
			$validation_messge[] = 'Please enter card_holder_name !';
			$status = false;
		}
		if($ifsc_code == ''){
			$validation_messge[] = 'Please enter ifsc_code !';
			$status = false;
		}
		if($status){
			$result = $this->User_model->updateBankAccount($user_id,$card_number,$expiry_month,$expiry_year,$cvv_number,$card_holder_name,$ifsc_code,$bank_name,$account_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['account_id'] 		= 	$result['account_detail']['account_id'];
				$post['bank_name'] 		 	= 	$result['account_detail']['bank_name'];
				$post['account_number'] 		= 	$result['account_detail']['account_number'];
				/*$post['expiry_month'] 		= 	$result['account_detail']['expiry_month'];
				$post['expiry_year'] 		= 	$result['account_detail']['expiry_year'];
				$post['cvv_number'] 		= 	$result['account_detail']['cvv_number'];*/
				$post['card_holder_name'] 	= 	$result['account_detail']['card_holder_name'];
				$post['ifsc_code'] 			= 	$result['account_detail']['ifsc_code'];
				//$post['is_default'] 		= 	$result['account_detail']['is_default'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function updatePaypalDetails(){
		$validation_messge 	= array();
		$status      		= true;
		
		$paypal_id			=	'';
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($paypal_id == ''){
			$validation_messge[] = 'Please enter paypal_id!';
			$status = false;
		}
		if($status){
			$result = $this->User_model->updatePaypalDetails($user_id,$paypal_id);  
			if($result['status'] =="200"){
				$post['message'] 		= 	$result['message'];
				$post['resultCode'] 	= 	$result['status'];
				$post['user_id'] 		= 	$result['user_id'];
				$post['paypal_id'] 		= 	$result['paypal_id'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function getContactDetails(){
		$validation_messge 	= array();
		$status      		= true;
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing!';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getContactDetails($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['contact_detail'] 	= 	$result['contact_detail'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function getBankAccounts(){
		$validation_messge 	= array();
		$status      		= true;
		
		$postData = $this->get();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing!';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getBankAccounts($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['account_detail'] 	= 	$result['account_detail'];
				$post['paypal_id'] 	= 	$result['paypal_id'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function userSettings(){
		$validation_messge 	= array();
		$status      		= true;
		
		$show_notification_status = "";
		$is_loot_box_unlocked = "";
		$daily_reminder_status = "";
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing!';
			$status = false;
		}
		if($status){
			$result = $this->User_model->userSettings($user_id,$show_notification_status,$is_loot_box_unlocked,$daily_reminder_status);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['settings_detail'] 	= 	$result['settings_detail'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function getNotificationList(){
		$validation_messge 	= array();
		$status      		= true;
						
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing!';
			$status = false;
		}
		if($status){
			$data = $this->User_model->getNotificationList($user_id);  
			
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);  
	}
	
	public function contact_us(){
		$validation_messge 	= array();
		$status      		= true;
		
		$name = "";
		$email = "";
		$company = "";
		$message = "";
		$mobile_no = "";
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		if($mobile_no == ''){
			$validation_messge[] = 'mobile_no is missing';
			$status = false;
		}
		if($email == ''){
			$validation_messge[] = 'email is missing';
			$status = false;
		}
		/* if($company == ''){
			$validation_messge[] = 'company is missing';
			$status = false;
		} */
		if($message == ''){
			$validation_messge[] = 'message is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->contact_us($name,$email,$company,$message,$mobile_no);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= $result['status'];
				//$post['category_data'] 			= 	$result['category_data'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getCategories(){
		$validation_messge 	= array();
		$status      		= true;
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getCategories($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= $result['status'];
				$post['category_data'] 			= 	$result['category_data'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getStoreProductsList(){
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$category_id = "";
		$sort_by_tokens = '';
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getStoreProductsList($user_id,$limit,$offset,$category_id,$sort_by_tokens);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['store_data'] 		= 	$result['store_data'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getStoreProductDetails(){
		$validation_messge 	= array();
		$status      		= true;
		
		$store_room_id = "";		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($store_room_id == ''){
			$validation_messge[] = 'store_room_id is missing';
			$status = false;
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getStoreProductDetails($user_id,$store_room_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['store_product_detail'] 	= 	$result['store_product_detail'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getRoomsList(){
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$category_id = "";
		$sort_by_tickets_remaining = '';
		$sort_by_price_value = '';
		$sort_by_start_date = '';
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getRoomsList($user_id,$category_id,$limit,$offset,$sort_by_tickets_remaining,$sort_by_price_value,$sort_by_start_date);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['banner_images'] 	= 	$result['banner_images'];
				$post['room_listing'] 	= 	$result['room_listing'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['banner_images'] 	= 	$result['banner_images'];
				$post['room_listing'] 	= 	$result['room_listing'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getPerformers(){
		$validation_messge 	= array();
		$status      		= true;
		$month = '';
		$year = '';
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($month == ''){
			$validation_messge[] = 'month is missing';
			$status = false;
		}
		if($year == ''){
			$validation_messge[] = 'year is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getPerformers($user_id,$month,$year);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['player_of_the_month']= 	$result['player_of_the_month'];
				$post['team_of_the_month'] 	= 	$result['team_of_the_month'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['player_of_the_month']= 	$result['player_of_the_month'];
				$post['team_of_the_month'] 	= 	$result['team_of_the_month'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}

	public function getNewsList(){
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$category_id = "";
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getNewsList($user_id,$category_id,$limit,$offset);  
			$data = $result;
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getCommentsList(){
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$category_id = "";
		$news_id = "";
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($news_id == ''){
			$validation_messge[] = 'news_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getCommentsList($user_id,$category_id,$limit,$offset,$news_id);  
			$data = $result;
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function addComment(){
		$validation_messge 	= array();
		$status      		= true;
		$news_id = "";
		$comment = "";
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($comment == ''){
			$validation_messge[] = 'comment is missing';
			$status = false;
		}
		if($news_id == ''){
			$validation_messge[] = 'news_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->addComment($user_id,$comment,$news_id);  
			$data = $result;
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function likeDislikeOnComment(){
		$validation_messge 	= array();
		$status      		= true;
		$comment_id = "";
		$type = "";
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($comment_id == ''){
			$validation_messge[] = 'comment_id is missing';
			$status = false;
		}
		if($type == ''){
			$validation_messge[] = 'type is missing';
			$status = false;
		}elseif(!($type=='LIKED' || $type=='DISLIKED'))
		{
			$validation_messge[] = 'type value should be only LIKED or DISLIKED';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->likeDislikeOnComment($user_id,$comment_id,$type);  
			$data = $result;
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function likeOnNews(){
		$validation_messge 	= array();
		$status      		= true;
		$news_id = "";
		$type = "";
		
		$user_id = $this->user_id;
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($news_id == ''){
			$validation_messge[] = 'news_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->likeOnNews($user_id,$news_id);  
			$data = $result;
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getRoomDetail(){
		$validation_messge 	= array();
		$status      		= true;
		
		$room_id = "";		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($room_id == ''){
			$validation_messge[] = 'room_id is missing';
			$status = false;
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getRoomDetail($user_id,$room_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['room_detail'] 	= 	$result['room_detail'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getMyMatchList(){
		
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$type = "";
		
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($type == ''){
			$validation_messge[] = 'type is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getMyMatchList($user_id,$limit,$offset,$type);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['my_match_listing'] 	= 	$result['my_match_listing'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['my_match_listing'] 	= 	$result['my_match_listing'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getMyWonMatchList(){
		
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$type = "";
		
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getMyWonMatchList($user_id,$limit,$offset,$type);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['won_match_listing'] 	= 	$result['won_match_listing'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['my_match_listing'] 	= 	$result['my_match_listing'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getMatchResults(){
		
		$validation_messge 	= array();
		$status      		= true;
		$limit = "";
		$offset = "";
		$type = "";
		
		
		$postData = $this->post();
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		
		if($status){
			$result = $this->User_model->getMatchResults($user_id,$limit,$offset,$type);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['match_listing'] 	= 	$result['match_listing'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['match_listing'] 	= 	$result['match_listing'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function tokenShop(){
		$validation_messge 	= array();
		$status      		= true;
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->tokenShop($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['token_shop'] 		= 	$result['token_shop'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function claimForToken(){
		$validation_messge 	= array();
		$status      		= true;
		
		$token_purchase_id = '';
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($token_purchase_id == ''){
			$validation_messge[] = 'token_purchase_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->claimForToken($user_id,$token_purchase_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_tokens'] 		= 	$result['user_tokens'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function enterRoom(){
		$validation_messge 	= array();
		$status      		= true;
		
		$room_id = "";		
		$room_drawing_id = "";
		$number_of_tickets ="";
		$paid_tokens = "";	 
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($room_id == ''){
			$validation_messge[] = 'room_id is missing';
			$status = false;
		}
		if($room_drawing_id == ''){
			$validation_messge[] = 'room_drawing_id is missing';
			$status = false;
		}		
		if($number_of_tickets == ''){
			$validation_messge[] = 'number_of_tickets is missing';
			$status = false;
		}else if($number_of_tickets<1){
			$validation_messge[] = 'Minimum 1 ticket is required!';
			$status = false;
		}
		if($paid_tokens == ''){
			$validation_messge[] = 'paid_tokens is missing';
			$status = false;
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->enterRoom($user_id,$room_id,$room_drawing_id,$number_of_tickets,$paid_tokens);  
			if($result['status'] =="200"){
				$post['message'] 				= 	$result['message'];
				$post['resultCode'] 			= 	$result['status'];
				$post['user_tokens'] 			= 	$result['user_tokens'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function buy_direct_room_tickets(){
		$validation_messge 	= array();
		$status      		= true;
		
		$room_id = "";		
		$room_drawing_id = "";		
		$quantity = "";		
		$paid_tokens = "";
		$firstname='';
		$lastname='';
		$phone_number='';
		$address='';
		$address2='';
		$zip_code='';
		$city='';
		$state='';	
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($room_id == ''){
			$validation_messge[] = 'room_id is missing';
			$status = false;
		}
		if($room_drawing_id == ''){
			$validation_messge[] = 'room_drawing_id is missing';
			$status = false;
		}
		if($quantity == ''){
			$validation_messge[] = 'quantity is missing';
			$status = false;
		}else if($quantity<1){
			$validation_messge[] = 'Minimum 1 ticket is required!';
			$status = false;
		}
		if($paid_tokens == ''){
			$validation_messge[] = 'paid_tokens is missing';
			$status = false;
		}
		if($firstname == ''){
			$validation_messge[] = ' Please Enter first name';
			$status = false;
		}
		if($lastname == ''){
			$validation_messge[] = 'Please Enter last name';
			$status = false;
		}
		if($phone_number == ''){
			$validation_messge[] = 'Please Enter email id';
			$status = false;
		}
		if($address == ''){
			$validation_messge[] = 'Please Enter address';
			$status = false;
		}
		if($zip_code == ''){
			$validation_messge[] = 'Please Enter zip_code';
			$status = false;
		}
		if($city == ''){
			$validation_messge[] = 'Please Enter city';
			$status = false;
		}
		if($state == ''){
			$validation_messge[] = 'Please Enter state';
			$status = false;
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->buy_direct_room_tickets($user_id,$room_id,$room_drawing_id,$quantity,$paid_tokens,$firstname,$lastname,$phone_number,$address,$address2,$zip_code,$city,$state);  
			if($result['status'] =="200"){
				$post['message'] 				= 	$result['message'];
				$post['resultCode'] 			= 	$result['status'];
				$post['user_tokens'] 	= 	$result['user_tokens'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function buy_store_product(){
		$validation_messge 	= array();
		$status      		= true;
		
		$store_room_id = "";		
		$quantity = "";		
		$paid_tokens = "";	
		$firstname='';
		$lastname='';
		$phone_number='';
		$address='';
		$address2='';
		$zip_code='';
		$city='';
		$state='';	
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($store_room_id == ''){
			$validation_messge[] = 'store_room_id is missing';
			$status = false;
		}		
		if($quantity == ''){
			$validation_messge[] = 'quantity is missing';
			$status = false;
		}else if($quantity<1){
			$validation_messge[] = 'Minimum 1 ticket is required!';
			$status = false;
		}
		if($paid_tokens == ''){
			$validation_messge[] = 'paid_tokens is missing';
			$status = false;
		}
		if($firstname == ''){
			$validation_messge[] = ' Please Enter first name';
			$status = false;
		}
		if($lastname == ''){
			$validation_messge[] = 'Please Enter last name';
			$status = false;
		}
		if($phone_number == ''){
			$validation_messge[] = 'Please Enter email id';
			$status = false;
		}
		if($address == ''){
			$validation_messge[] = 'Please Enter address';
			$status = false;
		}
		if($zip_code == ''){
			$validation_messge[] = 'Please Enter zip_code';
			$status = false;
		}
		if($city == ''){
			$validation_messge[] = 'Please Enter city';
			$status = false;
		}
		if($state == ''){
			$validation_messge[] = 'Please Enter state';
			$status = false;
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->buy_store_product($user_id,$store_room_id,$quantity,$paid_tokens,$firstname,$lastname,$phone_number,$address,$address2,$zip_code,$city,$state);  
			if($result['status'] =="200"){
				$post['message'] 				= 	$result['message'];
				$post['resultCode'] 			= 	$result['status'];
				//$post['store_token_purchase_id']= 	$result['store_token_purchase_id'];
				$post['user_tokens'] 			= 	$result['user_tokens'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function yourLoot(){
		$validation_messge 	= array();
		$status      		= true;
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->yourLoot($user_id);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['your_loot'] 			= 	$result['your_loot'];
				$data = $post;
			}else{
				$post['message'] = $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function openLootBox(){
		$validation_messge 	= array();
		$status      		= true;
		
		$tickets_purchased_id ="";
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($tickets_purchased_id == ''){
			$validation_messge[] = 'tickets_purchased_id is missing';
			$status = false;
		}
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->openLootBox($user_id,$tickets_purchased_id);  
			if($result['status'] =="200"){
				$post['message'] 				= 	$result['message'];
				$post['resultCode'] 			= 	$result['status'];
				$post['details'] 				= 	$result['details'];
				$data = $post;
			}else{
				$post['message'] 	= $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function addressConfirm(){
		$validation_messge 	= array();
		$status      		= true;	
		
		$id='';
		$firstname='';
		$lastname='';
		$phone_number='';
		$address='';
		$address2='';
		$zip_code='';
		$city='';
		$state='';
		$type = "";
		$postData = $this->post();
		//pr($this->user_id,1);
		$user_id = $this->user_id;
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		
		if($id == ''){
			$validation_messge[] = 'id is missing';
			$status = false;
		}
		
		if($type == ''){
			$validation_messge[] = 'type is missing';
			$status = false;
		}
		if($type != 'WINNER'){
			$validation_messge[] = 'This type is not allowed';
			$status = false;
		}
		if($firstname == ''){
			$validation_messge[] = ' Please Enter first name';
			$status = false;
		}
		if($lastname == ''){
			$validation_messge[] = 'Please Enter last name';
			$status = false;
		}
		if($phone_number == ''){
			$validation_messge[] = 'Please Enter email id';
			$status = false;
		}
		if($address == ''){
			$validation_messge[] = 'Please Enter address';
			$status = false;
		}
		if($zip_code == ''){
			$validation_messge[] = 'Please Enter zip_code';
			$status = false;
		}
		if($city == ''){
			$validation_messge[] = 'Please Enter city';
			$status = false;
		}
		if($state == ''){
			$validation_messge[] = 'Please Enter state';
			$status = false;
		}
		if($status){
			$result = $this->User_model->addressConfirm($user_id,$type,$id,$firstname,$lastname,$phone_number,$address,$address2,$zip_code,$city,$state);  
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['user_id'] 			= 	$result['user_id'];
				$post['address_details'] 	= 	$result['address_details'];
				$data = $post;
			}else{
				$post['message'] 	= $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function unboxHistory(){
		$validation_messge 	= array();
		$status      		= true;
		
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->unboxHistory($user_id);  
			if($result['status'] =="200"){
				$post['message'] 				= 	$result['message'];
				$post['resultCode'] 			= 	$result['status'];
				$post['unbox_history'] 			= 	$result['unbox_history'];
				$data = $post;
			}else{
				$post['message'] 	= $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function logout(){
		$validation_messge 	= array();
		$status      		= true;		
		
		//$post1 = $this->get();
		//$post1['headers'] = getallheaders();
	    //$post1['api_name'] = 'logout';
		//$this->eror_log_file($post1,'eror_log_file_');

		/*$postData = $this->input->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}*/
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = 'user_id is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->logout($user_id);  
			if($result['status'] =="200"){
				$post['message'] 				= 	$result['message'];
				$post['resultCode'] 			= 	$result['status'];
				$data = $post;
			}else{
				$post['message'] 	= $result['message'];
				$post['resultCode'] = $result['status'];
				$data = $post;
			}
		}else{
			$post['message'] = implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getFaqsPage(){
		$validation_messge 	= array();
		$status      		= true;		
		
		$postData = $this->input->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		//print_r($type);die;
		if($status){
			$result = $this->User_model->getFaqsPage($user_id); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['faqs_data'] 			= 	$result['faqs_data'];
				$data = $post;
			}else{
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$data = $post;
			}
		}else{
			$post['message'] 	= implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function getAvailableBalance(){
		$validation_messge 	= array();
		$status      		= true;		
		
		$postData = $this->input->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;		
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		//print_r($type);die;
		if($status){
			$result = $this->User_model->getAvailableBalance($user_id); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['available_amount'] 	= 	$result['available_amount'];
				$data = $post;
			}else{
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$data = $post;
			}
		}else{
			$post['message'] 	= implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function becomePartner()
	{
		$validation_messge 	= array();
		$status      		= true;		

		$full_name = '';
		$user_email = '';
		$contact_number = '';
		$social_media_link = '';
		$description = '';
		$you_are = '';
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($full_name == ''){
			$validation_messge[] = ' full_name is missing';
			$status = false;
		}
		if($user_email == ''){
			$validation_messge[] = ' user_email is missing';
			$status = false;
		}
		if($contact_number == ''){
			$validation_messge[] = ' contact_number is missing';
			$status = false;
		}
		/*if($social_media_link == ''){
			$validation_messge[] = ' social_media_link is missing';
			$status = false;
		}*/
		if($description == ''){
			$validation_messge[] = ' description is missing';
			$status = false;
		}
		if($you_are == ''){
			$validation_messge[] = ' you_are is missing';
			$status = false;
		}else if(!($you_are =='1' || $you_are =='2')){
			$validation_messge[] = ' you_are value is not allowed. Allowed value is 1 and 2';
			$status = false;
		}
		//print_r($type);die;
		if($status){
			$result = $this->User_model->becomePartner($user_id,$full_name,$user_email,$contact_number,$social_media_link,$description,$you_are); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		=   $result['status'];
				$data = $post;
			}else{
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$data = $post;
			}
		}else{
			$post['message'] 	= implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function joinMatch()
	{
		$validation_messge 	= array();
		$status      		= true;		

		$match_id = $this->post('match_id');
		$postData = $this->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($match_id == ''){
			$validation_messge[] = ' match_id is missing';
			$status = false;
		}
		
		$available_tickets = $this->commonmodel->_get_data('rooms',array('room_id' => $match_id,'status'=>'1','delete_status'=>'0'),'available_tickets');
		if($available_tickets===NULL)
		{
		    $validation_messge[] = ' No match available';
			$status = false;
		}else{
		    $match_count = $this->commonmodel->_get_data_row('*','joined_matches',array('room_id'=>$match_id,'status'=>'1','delete_status'=>'0'));
		    if(!($match_count<$available_tickets[0]['available_tickets']))
		    {
		        $validation_messge[] = $this->lang->line('room_already_full');
			    $status = false;
		    }
		}
		//print_r($type);die;
		if($status){
			$result = $this->User_model->joinMatch($user_id,$match_id);
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['available_amount'] 	=   $result['available_amount'];
				$data = $post;
			}else{
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$post['available_amount'] 	=   $result['available_amount'];
				$data = $post;
			}
		}else{
			$post['message'] 	= implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function rechargeAppCredit()
	{
		$validation_messge 	= array();
		$status      		= true;		
		
		$amount = (int)$this->post('amount');
		$postData = $this->input->post();
		foreach($postData as $key => $fields)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$user_id = $this->user_id;
		if($user_id == ''){
			$validation_messge[] = ' user_id is missing';
			$status = false;
		}
		if($amount == ''){
			$validation_messge[] = ' amount is missing';
			$status = false;
		}else if($amount < 1){
			$validation_messge[] = ' amount is not allowed.You are trying to add '.$amount;
			$status = false;
		}
		//print_r($type);die;
		if($status){
			$result = $this->User_model->rechargeAppCredit($user_id,$amount); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['available_amount'] 	= 	$result['available_amount'];
				$post['payment_gateway_url']        = 	$result['payment_url'];
				$data = $post;
			}else{
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$data = $post;
			}
		}else{
			$post['message'] 	= implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function get_error_log($data)
	{
		$myfile = fopen("error_log_".date('d-m-Y H:i:s').".txt", "w") or die("Unable to open file!");
		$data = json_encode($data);
		fwrite($myfile, $data);
		fclose($myfile);
	}   
	
	
	
	public function valid_error() {  
		$response['status']='error';
		$response['message']=validation_errors('');
		echo json_encode($response);
		die;
	}  
	
	public function something_went_wrong()
	{
		$response['status'] = 400;
		$response['error'] = true;
		$response['message'] = "Oop's something went wrong";
		echo json_encode($response,JSON_UNESCAPED_SLASHES);
		die;
	} 
	
	public function file_upload_error($error='The filetype you are attempting to upload is not allowed.')
	{
		$response['status']=0;
		$response['error'] = $error;
		echo json_encode($response,JSON_UNESCAPED_SLASHES);
		die;
	}
	
	public function profileImageNotUploadOnServer(){
		$response['status'] = 'true';
		$response['error'] = false;
		$response['message'] = 'Your profile image size is big. Recommended size is 1024*768';
		echo json_encode($response,JSON_UNESCAPED_SLASHES);
		die;
	}
	
	public function validate_encrypt_data($value,$encrypted_data)
	{
        
		$encryp = hash_hmac('sha256', $value, $this->secretkey); 
		
		if($encryp != $encrypted_data)
        {
           return false;
        }else{
		   return true;
		}
        
	}
	
	public function filter($data) {
		$data = trim(htmlentities(strip_tags($data)));
		$data = stripslashes($data);
		return $data;
	}
		
	public function getCmsPage(){
		$validation_messge 	= array();
		$status      		= true;		
		
		$page_key ='';
		$postData = $this->input->post();
		//$this->get_error_log($postData);
		foreach($postData as $key => $fileds)
		{
			${$key}=$this->filter($postData[$key]);
		}
		$page_key = 'ABOUT_US';
		if($page_key == ''){
			$validation_messge[] = ' page_key is missing';
			$status = false;
		}
		if($status){
			$result = $this->User_model->getCmsPage($page_key); 
			if($result['status'] =="200"){
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		=   $result['status'];
				$post['cms_page'] 			= 	$result['cms_page'];
				$data = $post;
			}else{
				$post['message'] 			= 	$result['message'];
				$post['resultCode'] 		= 	$result['status'];
				$data = $post;
			}
		}else{
			$post['message'] 	= implode(',',$validation_messge);
			$post['resultCode'] = '403';
			$data = $post;
		}
	    $this->response($data);
	}
	
	public function testPushNotification()
	{
		$user_id = '13';
		$message = 'test by server';
		$title = 'test title by server';
		$type = 'test';
		sendApnsPushNotification($user_id,$message,$title,$type);
        echo 'sent';
	}
}
?>