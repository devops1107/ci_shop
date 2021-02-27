<?php
	
class Wooapi extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('general');
		$this->load->model('Wooapi_model');
		$this->load->model('User_model');
	}
	
	public function index(){
	
		$site_opt = $this->db->query("Select app_security_key from settingmaster");
		$site_option = $site_opt->result();
		$dbsecuritykey = $site_option[0]->app_security_key ;    
		$appsecuritykey = $this->uri->segment(2);
		$apiname = $this->uri->segment(3);
		if($this->uri->segment(2) == 'appViewCounter')
		{
			$appsecuritykey = $dbsecuritykey;
		}
		$post = $this->input->post();
		if($apiname =='userSignup' || $apiname =='userSignin' || $apiname =='forgotPassword' || $apiname =='downloadFile' || $apiname =='downloadFile' || $apiname =='getCmsPage' || $apiname =='checkVarificationCode'){
			$tokenCheckStatus = true;
		}else{
			if(isset($post['token'])){
				$token = $post['token'];
				if($token!='')
				{
					$this->db->select('*');
					$this->db->from('tbl_usermaster');
					$this->db->where('status',1); 
					$this->db->where('token',$token); 
					$this->db->where('delete_status','0');
					$query = $this->db->get();
					
					if($query->num_rows()>0)
					{
						$tokenCheckStatus = true;
					}else{
						$tokenCheckStatus = false;
					}
				}else{
					$tokenCheckStatus = false;
				}
			}else{
			    $response['status'] = 400;
				$response['error'] = true;
				$response['message'] = 'token missing..';
			echo json_encode($response, JSON_UNESCAPED_SLASHES);die;
				//echo 'login_key missing..';die;
			}
			
		}
		
		
		if(($dbsecuritykey == $appsecuritykey) && $tokenCheckStatus===true)
		{
			if($this->uri->segment(2))
			$serviceCall = $this->uri->segment(3);
			switch ($serviceCall) {
				case "userSignup":
				$this->userSignup();
				break;
				case "userSignin":
				$this->userSignin();
				break;
				case "forgotPassword":
				$this->forgotPassword();
				break;
				case "changePassword":
				$this->changePassword();
				break;
				case "editProfile":
				$this->editProfile();
				break;
				case "addFavourites":
				$this->addFavourites();
				break;
				case "removeFavourites":
				$this->removeFavourites();
				break;
				case "getCategory":
				$this->getCategory();
				break;
				case "getSubCategory":
				$this->getSubCategory();
				break;
				case "getCmsPage":
				$this->getCmsPage();
				break;
				case "testing":
				$this->testing();
				break;
				case "getstatusType":
				$this->getstatusType();
				break;
				case "updateStatusType":
				$this->updateStatusType(); 
				break;
				case "updatePassword":
				$this->updatePassword(); 
				break;
				case "updateTokenkey":
				$this->updateTokenkey(); 
				break;
				case "downloadFile":
				$this->downloadFile(); 
				break;
				case "getNotificationList":
				$this->getNotificationList(); 
				break;
				case "logoutUser":
				$this->logoutUser(); 
				break;
				case "updateToken":
				$this->updateToken(); 
				break;
				case "getUserProfile":
				$this->getUserProfile(); 
				break;
				case "services":
				$this->services(); 
				break;
				case "get_services":
				$this->get_services(); 
				break;
				case "getFavourites":
				$this->getFavourites(); 
				break;
				case "checkVarificationCode":
				$this->checkVarificationCode(); 
				break;
				case "add_user_service":
				$this->add_user_service(); 
				break;
				case "get_all_user_service":
				$this->get_all_user_service(); 
				break;
				case "get_user_service":
				$this->get_user_service(); 
				break;
				case "add_service_ratings":
				$this->add_service_ratings(); 
				break;
				case "add_user_service_ratings":
				$this->add_user_service_ratings(); 
				break;
				default:
				echo "Something went wrong!";
			}
		}
		else 
		echo "Something went wrong!";
	}
	
	public function filter($data) {
		$data = trim(htmlentities(strip_tags($data)));
		$data = stripslashes($data);
		return $data;
	}
	
	public function userSignup(){
		$firstname='';
		$lastname='';
		$username='';
		$emailid='';
		$job_title='';
		$password='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($emailid){
			$this->Wooapi_model->userSignup($firstname,$lastname,$username,$emailid,$job_title,$password);  
		}else{
			$this->something_went_wrong();
		}
	}
	public function addFavourites(){
		$user_id='';
		$category_id='';
		$sub_category_id='';
		$sub_category_detail_id='';
		//$api_type =	'';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id && $category_id && $sub_category_id && $sub_category_detail_id){
			$this->Wooapi_model->addFavourites($user_id,$category_id,$sub_category_id,$sub_category_detail_id);  
		}else{
			$this->something_went_wrong();
		}
	}
	public function getFavourites(){
		$user_id='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id){
			$this->Wooapi_model->getFavourites($user_id);  
		}else{
			$this->something_went_wrong();
		}
	}
	public function removeFavourites(){
		$user_id='';
		$category_id='';
		$sub_category_id='';
		$sub_category_detail_id='';
		//$api_type =	'';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id && $category_id && $sub_category_id && $sub_category_detail_id){
			$this->Wooapi_model->removeFavourites($user_id,$category_id,$sub_category_id,$sub_category_detail_id);  
		}else{
			$this->something_went_wrong();
		}
	}
	
	
	
	public function userSignin(){
	
		$username ='';
		$password='';
		//$fcm_id='';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($password && $username){
			$this->Wooapi_model->userSignin($username,$password); 
			}else{
			$this->something_went_wrong();
		}  
	}
	public function getCmsPage(){
	
		$page_key ='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($page_key){
			$this->Wooapi_model->getCmsPage($page_key); 
			}else{
			$this->something_went_wrong();
		}
	}
	
	public function getUserProfile() {
		
		$user_id='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		//print_r($token);die;
		if($user_id) {
			$data=$this->Wooapi_model->getUserProfile($user_id);
			}else {
			$this->something_went_wrong();
		}
	}
	
	public function downloadFile() {
		
		$user_id='';
		$post = $this->input->get();
		//print_r($user_id);die;
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		//print_r($token);die;
		if($user_id) {
			$data=$this->Wooapi_model->downloadFile($user_id);
			}else {
			$this->something_went_wrong();
		}
	}
	public function getNotificationList() {
		
		$user_id='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		//print_r($token);die;
		if($user_id) {
			$data=$this->Wooapi_model->getNotificationList($user_id);
			}else {
			$this->something_went_wrong();
		}
	}
	
	public function getCategory(){
		$this->Wooapi_model->getCategory();
	}
	public function getSubCategory(){
		$user_id='';
		$category_id='';
		$filter='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id) {
			$data=$this->Wooapi_model->getSubCategory($user_id,$category_id,$filter);
			}else{
			$this->something_went_wrong();
		}
	}
	
	public function changePassword(){
		$user_id='';
		//$emailid='';
		$old_password='';
		$new_password='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id) {
			$data=$this->Wooapi_model->changePassword($user_id,$old_password,$new_password);
			}else{
			$this->something_went_wrong();
		}
	}
	
	public function forgotPassword()
	{
		$emailid= "";
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($emailid) {
			$data=$this->Wooapi_model->forgotPassword($emailid);
			}else{
			$this->something_went_wrong();
		}
	}
	
	public function editProfile()
	{
		$user_id='';
		$firstname='';
		$lastname='';
		$username='';
		$emailid='';
		$job_title='';
		$profileimage = '';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if(isset($_FILES['profileimage']) && $_FILES['profileimage']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['profileimage']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'customer';
				//$config['allowed_types']        = 'jpg|jpeg|png';
				$config['allowed_types']        = 'png';
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
				//print_r('bbbb');die;'data:image/webp;base64,UklGR
				//$image_parts = explode(";base64,", $profileimage);
				//$image_type_aux = explode("image/", $image_parts[0]);
				//if(isset($image_parts[1]) && isset($image_type_aux[1]))
				//{
					$image_type="jpg";
					$file = base64_decode($profileimage);
					$imageName = uniqid().'.'.$image_type;
					$result=$this->Wooapi_model->do_upload($file,$imageName);
					if($result==1){
						$profileimage = $imageName; 
						$profileimageT ='true';
					}else{
						$profileimage = ''; 
					}
				//}else{
				//print_r('cccc');die;
				//	$this->file_upload_error('Base64 encoded image format is not correct.Please provide correct format.');
				//}
			}
	
		if($user_id){
			$this->Wooapi_model->editProfile($user_id,$firstname,$lastname,$username,$emailid,$job_title,$profileimage);
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function getstatusType(){
		$this->Wooapi_model->user_status_type();
	}
	
	public function updateStatusType(){
		$token_key ="";
		$status_type ="";
		if($this->input->post("token_key"))
		$token_key=$this->filter($this->input->post("token_key"));
		if($this->input->post("status_type"))
		$status_type=$this->filter($this->input->post("status_type"));
		if($token_key && $status_type){
			$this->Wooapi_model->user_update_status_type($token_key,$status_type);
		}else{
			$this->something_went_wrong();
		}      
	}
	
	public function userstatusMessage()
	{
		$token_key= "";
		$status_id= "";
		if($this->input->post("access_token"))
		$token_key=$this->filter($this->input->post("access_token"));
		if($this->input->post("status_id"))
		$status_id=$this->filter($this->input->post("status_id"));
		if($token_key && $status_id){
			$this->Wooapi_model->status_message_user($token_key,$status_id);
			}else{
			$this->something_went_wrong();
		}                               
	} 
	
	public function get_error_log($data)
	{
		$myfile = fopen("error_log_".date('d-m-Y H:i:s').".txt", "w") or die("Unable to open file!");
		$data = json_encode($data);
		fwrite($myfile, $data);
		fclose($myfile);
	}   
	
	public function checkVarificationCode()
	{
		$user_id = "";
		$verification_code = "";
		$new_password = "";
		//$business_id = "";
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($user_id && $verification_code){
			$this->Wooapi_model->checkVarificationCode($user_id,$verification_code,$new_password);
			}else{
			$this->something_went_wrong();
		}       
	}
	
	public function updatePassword(){
		$email_id ="";
		$otp_code ="";
		$new_password="";
		if($this->input->post("email_id"))
		$email_id=$this->filter($this->input->post("email_id"));
		if($this->input->post("otp_code"))
		$otp_code=$this->filter($this->input->post("otp_code"));
		if($this->input->post("new_password"))
		$new_password=$this->filter($this->input->post("new_password"));               
		if($email_id && $otp_code){
			$this->Wooapi_model->update_user_password($email_id,$otp_code,$new_password);
			}else{
			$this->something_went_wrong();
		}    
	}
	
	public function updateToken(){
		$token_key ="";
		$new_token_key="";
		if($this->input->post("token_key"))
			$token_key=$this->filter($this->input->post("token_key"));
		if($this->input->post("new_token_key"))
			$new_token_key=$this->filter($this->input->post("new_token_key"));  
		
		if($token_key && $new_token_key){
			$this->Wooapi_model->user_token_update($token_key,$new_token_key);
		}else{
			$this->something_went_wrong();
		}
	}   
	
	/* public function services(){
		$title='';
		$description='';
		$image='';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($title && $description){
			$this->Wooapi_model->services($title,$description,$image);  
		}else{
			$this->something_went_wrong();
		}
	} */
	
	public function get_services(){
	
		$this->Wooapi_model->get_services();  
		
	}
	
	public function get_all_user_service(){
		$service_id =''; 
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($service_id){
			$this->Wooapi_model->get_all_user_service($service_id);  
		}else{
			$this->something_went_wrong();
		}
		
	}
	
	public function logoutUser(){
		$user_id =''; 
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($user_id){
			$this->Wooapi_model->logoutUser($user_id);  
		}else{
			$this->something_went_wrong();
		}
		
	}
	public function get_user_service(){
		$user_service_id =''; 
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($user_service_id){
			$this->Wooapi_model->get_user_service($user_service_id);  
		}else{
			$this->something_went_wrong();
		}
		
	}
	
	public function add_user_service(){
		$service_id='';
		$description='';
		$budget='';
		$location='';
		$image='';
		$user_id='';
		
		
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($service_id && $description && $location){
			$this->Wooapi_model->add_user_service($service_id,$description,$image,$location,$budget,$user_id);  
		}else{
			$this->something_went_wrong();
		}
	}
	public function add_service_ratings(){
		$user_service_id='';
		$user_id='';
		$feedback='';
		$rating_1='';
		$rating_2='';
		$rating_3='';
		$service_provider_id='';
		
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($user_service_id && $user_id){
			$this->Wooapi_model->add_service_ratings($user_service_id,$user_id,$feedback,$rating_1,$rating_2,$rating_3,$service_provider_id);  
		}else{
			$this->something_went_wrong();
		}
	}
	public function add_user_service_ratings(){
		$user_service_id='';
		$user_id='';
		$feedback='';
		$rating_1='';
		$rating_2='';
		$rating_3='';
		$service_provider_id='';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($user_service_id && $user_id){
			$this->Wooapi_model->add_user_service_ratings($user_service_id,$user_id,$feedback,$rating_1,$rating_2,$rating_3,$service_provider_id);  
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function valid_error() {  
		$response['status']='error';
		$response['message']=validation_errors('');
		echo json_encode($response);
		die;
	}  
	
	public function something_went_wrong()
	{
		$response['status']=0;
		$response['error'] = "Oop's something went wrong";
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
}
?>