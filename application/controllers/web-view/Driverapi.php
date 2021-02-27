<?php
	
class Driverapi extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('general');
		//$this->load->model('Business_model');
		$this->load->model('Driver_model');
	}
	
	public function index(){
		//print_r('adf');die;
	
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
		if($apiname =='driverSignup' || $apiname =='driverSignin' || $apiname =='checkVarificationCode' || $apiname =='driverLicence' || $apiname =='getCarMakes' || $apiname =='getCmsPage' || $apiname =='getCarTypes' || $apiname =='getCarModels' || $apiname =='getVehicleColors' || $apiname =='carRegistration' || $apiname =='resendOtp'){
			$tokenCheckStatus = true;
		}else{
			if(isset($post['driver_id'])){
				$driver_id 	= $post['driver_id'];
				if($driver_id!='')
				{
					$this->db->select('*');
					$this->db->from('drivers');
					$this->db->where('driver_status','1');
					$this->db->where('driver_id',$driver_id);
					$this->db->where('driver_delete','0');
					$this->db->where('driver_acceptance_status','APPROVED');
					$query = $this->db->get();
					if($query->num_rows()>0)
					{
						$tokenCheckStatus = true;
					}else{
						$tokenCheckStatus = false;
					}
					//var_dump($tokenCheckStatus);die;
				}else{
					$tokenCheckStatus = false;
				}
			}else{
				$response['status'] = 400;
				$response['error'] = true;
				$response['message'] = 'Driver id missing..';
				echo json_encode($response, JSON_UNESCAPED_SLASHES);die;
				//echo 'login_key missing..';die;
			} 
		}
		if(($dbsecuritykey == $appsecuritykey) && $tokenCheckStatus===true)
		{
			if($this->uri->segment(2))
			$serviceCall = $this->uri->segment(3);
			switch ($serviceCall) {
				case "driverSignup":
				$this->driverSignup();
				break;
				case "driverSignin":
				$this->driverSignin();
				break;
				case "checkVarificationCode":
				$this->checkVarificationCode();
				break;
				case "editProfile":
				$this->editProfile();
				break;
				case "updateProfileImage":
				$this->updateProfileImage();
				break;
				case "driverLicence":
				$this->driverLicence();
				break;
				case "getCarMakes":
				$this->getCarMakes();
				break;
				case "getCarTypes":
				$this->getCarTypes();
				break;
				case "getCarModels":
				$this->getCarModels();
				break;
				case "getVehicleColors":
				$this->getVehicleColors();
				break;
				case "carRegistration":
				$this->carRegistration();
				break;
				case "addAccountDetail":
				$this->addAccountDetail();
				break;
				case "getCmsPage":
				$this->getCmsPage();
				break;
				case "updateDriverStatus":
				$this->updateDriverStatus();
				break;
				case "updateTripStatus":
				$this->updateTripStatus();
				break;
				case "getTripDetail":
				$this->getTripDetail();
				break;
				case "getTripHistory":
				$this->getTripHistory();
				break;
				case "resendOtp":
				$this->resendOtp();
				break;
				case "getNotificationList":
				$this->getNotificationList();
				break;
				case "balanceByFilter":
				$this->balanceByFilter();
				break;
				case "markAsAllReadNotifications":
				$this->markAsAllReadNotifications();
				break;
				default:
				//print_r('asdfadfasdf');die;
				echo "Something went wrong!";
			}
		}else 
		if($tokenCheckStatus===false){
			$response['status']=400;
			$response['error'] = true;
			$response['message'] = "You are not authorized for next step";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else{
			//echo "Something went wrong!";
			$response['status']=400;
			$response['error'] = true;
			$response['message'] = "Something went wrong!";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}
	}
	
	public function filter($data) {
		$data = trim(htmlentities(strip_tags($data)));
		$data = stripslashes($data);
		return $data;
	}
	
	public function driverSignup(){
		$first_name='';
		$last_name='';
		$driver_email='';
		$contact_number='';
		$country_code='';
				
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		$error = 1;
		$error_msg = array();
		if($contact_number == '' || $first_name == '' || $last_name == '' || $country_code == ''){
			$error =0;
			$error_msg = 'Missing parameters';
		}
		if($error == 1){
			$this->Driver_model->driverSignup($first_name,$last_name,$driver_email,$contact_number,$country_code);  
		}else{
			$response['status'] = 400;
			$response['error'] = true;
			$response['message'] = $error_msg;
			echo json_encode($response, JSON_UNESCAPED_SLASHES); 
		}
	}
	
	public function driverSignin(){
		$contact_number='';
		$country_code='';
		$device_id='';
		$device_type='';
				
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		$error = 1;
		$error_msg = array();
		if($contact_number == '' || $country_code == ''){
			$error =0;
			$error_msg = 'Missing parameters';
		}
		if($error == 1){
			$this->Driver_model->driverSignin($contact_number,$country_code,$device_type,$device_id);  
		}else{
			$response['status'] = 400;
			$response['error'] = true;
			$response['message'] = $error_msg;
			echo json_encode($response, JSON_UNESCAPED_SLASHES); 
		}
	}
	
	
	public function checkVarificationCode()
	{
		$driver_id = "";
		$otp = "";
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($driver_id  && $otp){
			$this->Driver_model->checkVarificationCode($driver_id,$otp);
		}else{
			$this->something_went_wrong();
		}       
	}
	public function editProfile(){
		$driver_id='';
		$first_name='';
		$last_name='';
		$driver_email='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($first_name == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please Enter first name";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($last_name == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please Enter last name";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($driver_id){
			$this->Driver_model->editProfile($driver_id,$first_name,$last_name,$driver_email);  
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function updateProfileImage(){
		$driver_id='';
		$profile_image='';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if(isset($_FILES['profile_image']) && $_FILES['profile_image']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['profile_image']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'drivers';
				$config['allowed_types']        = 'jpg|jpeg|png';
				//$config['allowed_types']        = 'png';
				$config['max_size']             = 1024;
				$config['max_width']            = 1024;
				$config['max_height']           = 768;

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('profile_image'))
				{
						$error = array('error' => $this->upload->display_errors());
						$result = 0;
						$this->file_upload_error($error);
				}else{
						$result = 1;
						$profile_image = $file_name;
						$data = array('upload_data' => $this->upload->data());
				}
			}else if($profile_image!=""){
				$image_type="png";
					$file = base64_decode($profile_image);
					$imageName = uniqid().'.'.$image_type;
					$result=$this->Driver_model->do_upload($file,$imageName);
					if($result==1){
						$profile_image = $imageName; 
						$profileimageT ='true';
					}else{
						$profile_image = ''; 
					}
				}
			
		if($driver_id){
			$this->Driver_model->updateProfileImage($driver_id,$profile_image);  
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function carRegistration(){
		
		$driver_id						=	'';
		$car_type						=	'';
		$make_id						=	'';
		$model_id						=	'';
		$color							=	'';
		$car_registration_number		=	'';
		$fuel_type						=	'';
		$manufacture_date				=	'';
		$registration_date				=	'';
		$vehicle_image					=	'';
		$car_register_cetificate_front	=	'';
		$car_register_cetificate_back	=	'';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if(isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['vehicle_image']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'vehicles';
				$config['allowed_types']        = 'jpg|jpeg|png';
				//$config['allowed_types']        = 'png';
				$config['max_size']             = 1024;
				$config['max_width']            = 1024;
				$config['max_height']           = 768;

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('vehicle_image'))
				{
						$error = array('error' => $this->upload->display_errors());
						$result = 0;
						$this->file_upload_error($error);
				}else{
						$result = 1;
						$vehicle_image = $file_name;
						$data = array('upload_data' => $this->upload->data());
						//print_r($vehicle_image);die;
				}
			}
			
			if(isset($_FILES['car_register_cetificate_front']) && $_FILES['car_register_cetificate_front']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['car_register_cetificate_front']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'vehicles/car-register-certificate-images';
			//	print_r($config['upload_path']);die;
				$config['allowed_types']        = 'jpg|jpeg|png';
				//$config['allowed_types']        = 'png';
				$config['max_size']             = 1024;
				$config['max_width']            = 1024;
				$config['max_height']           = 768;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				
				if ( ! $this->upload->do_upload('car_register_cetificate_front'))
				{
						$error = array('error' => $this->upload->display_errors());
						$result = 0;
						$this->file_upload_error($error);
				}else{
						$result = 1;
						$car_register_cetificate_front = $file_name;
						$data = array('upload_data' => $this->upload->data());
						//print_r($car_register_cetificate_front);die;
				}
			}
			
			if(isset($_FILES['car_register_cetificate_back']) && $_FILES['car_register_cetificate_back']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['car_register_cetificate_back']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'vehicles/car-register-certificate-images';
				$config['allowed_types']        = 'jpg|jpeg|png';
				//$config['allowed_types']        = 'png';
				$config['max_size']             = 1024;
				$config['max_width']            = 1024;
				$config['max_height']           = 768;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload('car_register_cetificate_back'))
				{
						$error = array('error' => $this->upload->display_errors());
						$result = 0;
						$this->file_upload_error($error);
				}else{
						$result = 1;
						$car_register_cetificate_back = $file_name;
						$data = array('upload_data' => $this->upload->data());
				}
			}
			
		if($driver_id){
			$this->Driver_model->carRegistration($driver_id,$car_type,$make_id,$model_id,$color,$car_registration_number,$fuel_type,$car_register_cetificate_front,$manufacture_date,$registration_date,$vehicle_image,$car_register_cetificate_back);  
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function driverLicence(){
		$driver_id						=	'';
		$license_number					=	'';
		$license_document_image_front	=	'';
		$license_document_image_back	=	'';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if(isset($_FILES['license_document_image_front']) && $_FILES['license_document_image_front']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['license_document_image_front']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'drivers/license-documents';
				$config['allowed_types']        = 'jpg|jpeg|png';
				//$config['allowed_types']        = 'png';
				$config['max_size']             = 1024;
				$config['max_width']            = 1024;
				$config['max_height']           = 768;

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('license_document_image_front'))
				{
						$error = array('error' => $this->upload->display_errors());
						$result = 0;
						$this->file_upload_error($error);
				}else{
						$result = 1;
						$license_document_image_front = $file_name;
						$data = array('upload_data' => $this->upload->data());
				}
			}
			
		if(isset($_FILES['license_document_image_back']) && $_FILES['license_document_image_back']['name']!='')
			{
				//print_r('aaaa');die;
				$filename = $_FILES['license_document_image_back']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$file_name = uniqid().'.'.$ext;
				
				$config['file_name']            = $file_name;
				$config['upload_path']          = UPLOAD_PHYSICAL_PATH.'drivers/license-documents';
				$config['allowed_types']        = 'jpg|jpeg|png';
				//$config['allowed_types']        = 'png';
				$config['max_size']             = 1024;
				$config['max_width']            = 1024;
				$config['max_height']           = 768;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ( ! $this->upload->do_upload('license_document_image_back'))
				{
						$error = array('error' => $this->upload->display_errors());
						$result = 0;
						$this->file_upload_error($error);
				}else{
						$result = 1;
						$license_document_image_back = $file_name;
						$data = array('upload_data' => $this->upload->data());
				}
			}
			
		if($driver_id == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Driver id missing";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($license_number == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please Enter licence number";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($license_document_image_front == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please Enter licence front image";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($license_document_image_back == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please Enter licence back image";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}elseif($driver_id){
			$this->Driver_model->driverLicence($driver_id,$license_number,$license_document_image_front,$license_document_image_back);  
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function addAccountDetail(){
		$driver_id				=	'';
		$bank_name				=	'';
		$driver_account_number	=	'';
		$sort_code				=	'';
		$account_holder_name	=	'';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
			
		if($driver_id == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Driver id missing";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($bank_name == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please enter bank name";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($driver_account_number == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please enter driver account number";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($sort_code == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please enter sort code";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}else if($account_holder_name == ''){
			$response['status']=400;
			$response['error']=true;
			$response['message'] = "Please enter account holder name";
			echo json_encode($response,JSON_UNESCAPED_SLASHES);
			die;
		}elseif($driver_id){
			$this->Driver_model->addAccountDetail($driver_id,$bank_name,$driver_account_number,$sort_code,$account_holder_name);  
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
			$this->Driver_model->getCmsPage($page_key); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function updateDriverStatus(){
		$driver_id				=	'';
		$driver_status			=	'';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($driver_id){
			$this->Driver_model->updateDriverStatus($driver_status,$driver_id); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function updateTripStatus(){
		$driver_id				=	'';
		$trip_id				=	'';
		$trip_status			=	'';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($trip_id && $driver_id && $trip_status){
			$this->Driver_model->updateTripStatus($trip_id,$driver_id,$trip_status); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function getTripDetail(){
		$driver_id				=	'';
		$trip_id			=	'';
		//$filter_name			=	'';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($driver_id){
			$this->Driver_model->getTripDetail($driver_id,$trip_id); 
		}else{
			$this->something_went_wrong();
		}
	}
	public function getTripHistory(){
		$driver_id				=	'';
		//$trip_id			=	'';
		$filter_name			=	'';
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		
		if($driver_id){
			$this->Driver_model->getTripHistory($driver_id,$filter_name); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function resendOtp(){
		$driver_id = '';
		$driver_id = '';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($driver_id){
			$this->Driver_model->resendOtp($driver_id); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function getNotificationList(){
		$driver_id = '';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($driver_id){
			$this->Driver_model->getNotificationList($driver_id); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function balanceByFilter(){
		$driver_id 		= 	'';
		$filter_type	=	'';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($driver_id && $filter_type){
			$this->Driver_model->balanceByFilter($driver_id,$filter_type); 
		}else{
			$this->something_went_wrong();
		}
	}
	
	public function markAsAllReadNotifications(){
		$driver_id 		= 	'';
		//$filter_type	=	'';
		
		$post = $this->input->post();
		foreach($post as $key => $fileds)
		{
			${$key}=$this->filter($post[$key]);
		}
		if($driver_id){
			$this->Driver_model->markAsAllReadNotifications($driver_id); 
		}else{
			$this->something_went_wrong();
		}
	}
		
	public function getCarMakes()
	{
		$this->Driver_model->getCarMakes();
	}  
	
	public function getCarTypes()
	{
		$this->Driver_model->getCarTypes();
	} 
	
	public function getCarModels()
	{
		$this->Driver_model->getCarModels();
	} 
	
	public function getVehicleColors()
	{
		$this->Driver_model->getVehicleColors();
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
}
?>