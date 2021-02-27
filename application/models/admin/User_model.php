<?php
class User_model extends CI_model
{
	public function  __construct()
	{
		parent::__construct();
		$this->load->helper('general');
		$this->load->library('email');
		$this->load->model('Fcm_model');
		$this->load->database();
		$this->roleid = 3;
	}
	
	
	public function tokenKeyAccess($username)
	{
		$currDate = time(); 
		$accrssToken = $username.$currDate;
		return md5(uniqid($accrssToken,true));
	}
	
	public function get_otp_email(){
		return $otp = rand(100000,999999);
	}
	
	/* function _sendmail($email,$token,$mailType)
	{
		$from_email= FROM_EMail;
		$config = Array(
		'protocol' => PROTOCOL,
		'smtp_host' => HOST_NAME,
		//'smtp_host' => 'ssl://smtp.googlemail.com',
		'smtp_port' => SMTP_PORT,
		'smtp_user' => SMTP_USER,
		'smtp_pass' => SMTP_PASSWORD,
		'mailtype' => 'text/html',
		'charset' => 'UTF-8'
		);
		
		$this->load->library('email',$config);
		$this->email->set_newline("\r\n");
		$this->email->from($from_email, SITE_NAME); 
		$this->email->to($email);
		$this->email->subject('Active The Link');   
		$verification_code=$token;
		$otp="";
		if($mailType==1){
			$link = 'Click on this link - <a href='.base_url().'"user/accepts_friends/'.$verification_code.' ">Click Here For Email Confirmation
			</a>';
			}elseif($mailType==2){
			$otp = $this->get_otp_email();
			$link = 'Your Otp Code : ' .$otp;
			}else{
			$link = 'Click on this link - <a href='.base_url().'"mail-verification/'.$email.'/'.$verification_code.' ">Click Here For Email Confirmation
			</a>';
		}                  
		$this->email->message($link); 
		if($this->email->send()){
			if($otp!=""){
				$data=array('otpcode'=>$otp,'modify'=>date('Y-m-d H:i:s'));
				$this->db->where('emailid',$email);
				$this->db->where('roleid',$this->roleid);
				$result = $this->db->update('tbl_usermaster',$data);
				if($result){
					$array = array('otp'=>$otp,'status'=>true);
					return $array;
				}
			}else{
				return true;
			}
		 }else{
			return false;
		} 
	}  */
	
	public function sendemail($to,$subject,$message)
	{
		require_once('smtp/class.phpmailer.php');
		$HOST_NAME 	= HOST_NAME;
		$USER_NAME 	= USER_NAME;
		$PASSWORD 	= SMTP_PASSWORD;
		$PORT_NO 	= SMTP_PORT;
		$FROM_NAME 	= FROM_NAME;
		$FROM 		= FROM;
		$crlf 		= "\n";
		$pos='';
		//echo SMTP_PASSWORD;die;
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
			//$mail->SMTPDebug   		= 	2;
			$mail->Encoding 		= 	'quoted-printable';
			$body            		= 	$message;
			$mail->IsSMTP(); // telling the class to use SMTP
			/*$mail->Host      		= 	"smtp.gmail.com"; // SMTP server
			$mail->Port      		= 	"465"; // SMTP port*/
			$mail->SMTPSecure		= 	SMTP_SECURE; // SMTP secure
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
			//var_dump($mail->Send()); die;
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
				/* print_r($to);
				print_r($subject);
				print_r($message);
				print_r($headers);
				die; */
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
	
	public function send_otp($first_name,$mobile,$otp,$country_code){
	
		// Required if your environment does not handle autoloading

		require TWILIO_URL.'vendor/autoload.php';

		// Your Account SID and Auth Token from twilio.com/console
		$sid = ACCOUNT_SID;
		$token = AUTH_TOKEN;
		$client = new Client($sid, $token);
		$mob =	 $country_code.$mobile;
		//print_r($mob);die;
		// Use the client to do fun stuff like send text messages!
		$response = $client->messages->create(
						// the number you'd like to send the message to
						$mob,
						array(
							// A Twilio phone number you purchased at twilio.com/console
							'from' => '+16139095302',
							// the body of the text message you'd like to send
							'body' => 'Hey '.$first_name.'! Your otp is '.$otp.' for user login'
						)
					);
					$success ='success';
					return $success;
	}
	
	public function getUserData($user_id){
		//$this->db->select('id,firstname,lastname,username,emailid,mobileno,profileimage,address,address_2,city,state,zip_code,referral_code,user_tokens,is_irs_confirm_status');
		$this->db->select('id,emailid,profileimage');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$userData = $query->row_array();
			$profileimage = $userData['profileimage'];
			if(is_file(UPLOAD_PHYSICAL_PATH.'users/'.$profileimage) && $profileimage!='')
			{
				$userData['profileimage'] = UPLOAD_URL.'users/'.$profileimage;
			}else{
				$userData['profileimage'] = UPLOAD_URL.'users/no_image.jpg';
			}
			/*$userData['address']	= ($userData['address']===NULL)?"":$userData['address'];
			$userData['address_2']	= ($userData['address_2']===NULL)?"":$userData['address_2'];
			$userData['firstname']	= ($userData['firstname']===NULL)?"":$userData['firstname'];
			$userData['lastname']	= ($userData['lastname']===NULL)?"":$userData['lastname'];
			$userData['mobileno']	= ($userData['mobileno']===NULL)?"":$userData['mobileno'];
			$userData['city']		= ($userData['city']===NULL)?"":$userData['city'];
			$userData['state']		= ($userData['state']===NULL)?"":$userData['state'];
			$userData['zip_code']	= ($userData['zip_code']===NULL)?"":$userData['zip_code'];
			$userData['referral_code']	= ($userData['referral_code']===NULL)?"":$userData['referral_code'];
			$userData['full_name']	= $userData['firstname']." ".$userData['lastname'];*/
			return $userData;
		}else{
			$userData = '';
			return $userData;
		}
	}
	
	public function userSignup($emailid,$user_name,$phone_number,$password,$signup_type,$refferer_code,$social_id,$device_type,$device_id,$fcm_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','0');
		$this->db->where('delete_status','0');
		$this->db->where('emailid',$emailid);
		$query = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('emailid',$emailid);
		$emailquery = $this->db->get();

		$email_varification_status = '';
		if($query->num_rows() > 0 && $signup_type=="APP")
		{
			$response['status'] = 400;
			$response['message'] = $this->lang->line('account_deactivated');
			$data = $response;
		}elseif($emailquery->num_rows() >0 && $signup_type=="APP")
		{
			$emailDetails = $emailquery->row_array();
			$email_varification_status = $emailDetails['email_varification_status'];
			if($email_varification_status=='1')
			{
				$response['status'] = 400;
				$response['message'] = $this->lang->line('email_already_reqiestered');
				$data = $response;
			}else{
				$userdata = array();
				$userdata['signup_type'] 	= $signup_type;
				$userdata['emailid'] 		= $emailid;
				$userdata['username']   	= $user_name;
				$userdata['mobileno'] 		= $phone_number;
				$userdata['password'] 		= md5($password);
				$userdata['modify'] 		= date('Y-m-d H:i:s');

				$user_id = $emailDetails['id'];

				$verification_code  =  $this->genrate_code($user_id);
				$verification_code 	= hash('sha256', $verification_code);
				$to = $emailid;
				$url = base_url('verifyEmail/'.$to.'/'.$verification_code);
				if($this->language == 'english')
				{
					$subject = SITE_NAME.' account verification email';
					$message =	'Hello '.$user_name.'<br/>';
					$message .= 'Thank you for registering with My Match. Please verify your email by <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
					$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
					$message .= 'We hope you enjoy our services!<br/><br/>';
					$message .= 'Sincerely<br/>';
					$message .= SITE_NAME.' Team';
				}else{
					$subject = SITE_NAME.' Kontobestätigungs-E-Mail';
					$message =	'Hallo '.$user_name.'<br/>';
					$message .= 'Vielen Dank, dass Sie sich bei My Match registriert haben. Bitte bestätigen Sie Ihre E-Mail-Adresse mit <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
					$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
					$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
					$message .= 'Mit freundlichen Grüßen<br/>';
					$message .= SITE_NAME.' Mannschaft';
				}
				$mailConfirm = $this->sendemail($to,$subject,$message);

				$result = $this->commonmodel->_update('tbl_usermaster',$userdata,array('id'=>$user_id));
				$response['status'] 			= 	200;
				$response['message'] 			= 	$this->lang->line('register_successfull');
				$response['user_id'] 			= 	$user_id;
				$data = $response;
			}
		}else 
		{
			
			$userdata = array();
			$userdata['signup_type'] 	= $signup_type;
			$userdata['emailid'] 		= $emailid;
			$userdata['username']   	= $user_name;
			$userdata['mobileno'] 		= $phone_number;
			$userdata['password'] 		= md5($password);
			$userdata['created'] 		= date('Y-m-d H:i:s');
			$userdata['modify'] 		= date('Y-m-d H:i:s');
			$result = $this->db->insert('tbl_usermaster',$userdata);
			
			$user_id = $this->db->insert_id();
			if($user_id)
			{
				$verification_code  =  $this->genrate_code($user_id);
				$verification_code 	= hash('sha256', $verification_code);
				$to = $emailid;
				$url = base_url('verifyEmail/'.$to.'/'.$verification_code);
				if($this->language == 'english')
				{
					$subject = SITE_NAME.' account verification email';
					$message =	'Hello '.$user_name.'<br/>';
					$message .= 'Thank you for registering with My Match. Please verify your email by <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
					$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
					$message .= 'We hope you enjoy our services!<br/><br/>';
					$message .= 'Sincerely<br/>';
					$message .= SITE_NAME.' Team';
				}else{
					$subject = SITE_NAME.' Kontobestätigungs-E-Mail';
					$message =	'Hallo '.$user_name.'<br/>';
					$message .= 'Vielen Dank, dass Sie sich bei My Match registriert haben. Bitte bestätigen Sie Ihre E-Mail-Adresse mit <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
					$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
					$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
					$message .= 'Mit freundlichen Grüßen<br/>';
					$message .= SITE_NAME.' Mannschaft';
				}
				$mailConfirm = $this->sendemail($to,$subject,$message);
				
				//$sms_response = sendemail($first_name,$mobileno,$otp,$country_code);
				$response['status'] 			= 	200;
				$response['message'] 			= 	$this->lang->line('register_successfull');
				$response['user_id'] 			= 	$user_id;
				$data = $response;
			}else{
				$response['status'] 			= 	400;
				$response['message'] 			= 	$this->lang->line('create_account_error');
				$data = $response;
			}
		}
		return $data;
	}
	
	public function getSignupRewards(){
		$this->db->select('refferal_reward_tokens,signup_reward_tokens,daily_bonus_tokens,watch_per_ad_tokens');
		$this->db->from('settingmaster');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	public function getReferralCode(){
		$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$res = "";
		for ($i = 0; $i < 7; $i++) {
			$res .= $chars[mt_rand(0, strlen($chars)-1)];
		}
		if($res!=""){
			$this->db->select('*');
			$this->db->from('tbl_usermaster');
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$this->db->where('referral_code',$res);
			$query = $this->db->get();
			if($query->num_rows()>0){
				$this->getReferralCode();
			}else{
				return $res;
			}
		}
	}
	
	public function dailySigninReward($user_id){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('email_varification_status','1');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$daily_rewards = $this->getSignupRewards();
			//pr($daily_rewards,1);
			$today = date('Y-m-d');
			$this->db->select('*');
			$this->db->from('token_purchases');
			$this->db->where('user_id',$user_id);
			$this->db->where('type','DAILY_REWARD');
			$this->db->where('created_on BETWEEN "'. date('Y-m-d 00:00:00.00',strtotime($today)). '" AND "'. date('Y-m-d 23:59:59.999',strtotime($today)).'" ');
			$query2 = $this->db->get();
			//echo $this->db->last_query();die;
			if($query2->num_rows()>0){
				$response['status'] 			= 	400;
				$response['message'] 			= 	'You have already get daily signin reward for today!';
				$data = $response;
			}else{
				$details = array(
					'user_id'			=>	$user_id,
					'tokens'			=>	$daily_rewards['daily_bonus_tokens'],
					'type'				=>	'DAILY_REWARD',
					'is_claim_allowed'	=>	'1',
					'created_on'		=>	date('Y-m-d H:i:s'),
				);
				//pr($details,1);
				$result = $this->db->insert('token_purchases',$details);
				$token_purchase_id = $this->db->insert_id();
				if($result){
					$response['status'] 			= 	200;
					$response['message'] 			= 	'You have been get daily reward successfully!';
					$response['user_id'] 			= 	$user_id;
					$response['token_purchase_id'] 	= 	strval($token_purchase_id);
					$response['daily_bonus_tokens'] 	= 	strval($daily_rewards['daily_bonus_tokens']);
					$data = $response;
				}else{
					$response['status'] 			= 	400;
					$response['message'] 			= 	$this->lang->line('something_went_wrong');
					$data = $response;
				}
			}
		}else{
			$response['status'] 			= 	400;
			$response['message'] 			= 	$this->lang->line('not_registered');
			$data = $response;
		}
		return $data;
	}
	
	public function adWatchReward($user_id){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('email_varification_status','1');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$ad_watch_reward = $this->getSignupRewards();
			//pr($ad_watch_reward,1);
			
			$details = array(
				'user_id'			=>	$user_id,
				'tokens'			=>	$ad_watch_reward['watch_per_ad_tokens'],
				'type'				=>	'WATCH_AD',
				'is_claim_allowed'	=>	'1',
				'created_on'		=>	date('Y-m-d H:i:s'),
			);
			//pr($details,1);
			$result = $this->db->insert('token_purchases',$details);
			$token_purchase_id = $this->db->insert_id();
			if($result){
				$response['status'] 			= 	200;
				$response['message'] 			= 	'You have been get ad watch reward successfully!';
				$response['user_id'] 			= 	$user_id;
				$response['token_purchase_id'] 	= 	strval($token_purchase_id);
				$response['ad_watch_tokens'] 	= 	strval($ad_watch_reward['watch_per_ad_tokens']);
				$data = $response;
			}else{
				$response['status'] 			= 	400;
				$response['message'] 			= 	$this->lang->line('something_went_wrong');
				$data = $response;
			}
			
		}else{
			$response['status'] 			= 	400;
			$response['message'] 			= 	$this->lang->line('not_registered');
			$data = $response;
		}
		return $data;
	}
	
	
	
	public function userSignin($emailid,$password,$device_type,$device_id,$fcm_id)
	{
		$pass = md5($password);
		$result =	$this->db->query("SELECT * FROM `tbl_usermaster` WHERE (username='".$emailid."' or emailid='".$emailid."') and password='".$pass."' and delete_status='0' ");
		if($result->num_rows() > 0)
		{
			$userData = $result->row_array();
			$user_id = $userData['id'];
			if($userData['email_varification_status'] =='1'){
				$login_key = $this->getLoginKey($user_id);
				$user_data=array('account_confirm'=>'C','login_key'=>$login_key,'device_type'=>$device_type,'device_id'=>$device_id,'fcm_id'=>$fcm_id,'modify'=>date('Y-m-d H:i:s'));
				$this->db->where('id',$user_id);
				$result 	=   $this->db->update('tbl_usermaster',$user_data);
				
				$user_detail = $this->getUserData($user_id);
				
				/*if($userData['refferer_code']!="" && $userData['refferer_user_id']!=""){
					$user_detail['is_refferal_allowed'] = '0';
				}else{
					$user_detail['is_refferal_allowed'] = '1';
				}*/
				
				$account_details = $this->GetCardDetailsbyUserId($user_id);
				$response['status'] 			= 	200;
				$response['message'] 			= 	$this->lang->line('login_successfull');
				$response['user_id'] 			= 	$user_id;
				$response['user_detail'] 		= 	$user_detail;
				$response['account_details'] 	= 	$account_details;
				$response['login_key'] 			= 	$login_key;
				$data = $response;
			}else{
				$response['status'] 			= 	400;
				$response['message'] 			= 	$this->lang->line('verify_account_first');
				$data = $response;
			}
		}else{
			$response['status'] 			= 	400;	
			$response['message'] 			= 	$this->lang->line('invalid_credentials');
			$data = $response;
		}
		return $data;
	}
	
	public function deleteAccount($user_id){
		//pr($user_id,1);
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$save_detail = array(
				'delete_status'	=>	'1',
				'modify'		=>	date('Y-m-d H:i:s')
			);
			$result 		 			= $this->db->update('tbl_usermaster',$save_detail,array('id'=>$user_id));
			$response['status']  		= 200;
			$response['message'] 		= $this->lang->line('account_deleted');
			$data = $response;
		}else{
			$response['status'] 		= 400; 
			$response['message'] 		= $this->lang->line('something_went_wrong');
			$data = $response;
		}
		return $data;
	}
	
	public function enterRefferalCode($user_id,$referal_code){
		$this->db->select('id');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('referral_code',$referal_code);
		$refferquery = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('id',$user_id);
		$this->db->where('refferer_code !=',"");
		$query = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('id',$user_id);
		$this->db->where('referral_code',$referal_code);
		$ownRefferquery = $this->db->get();
		
		if($refferquery->num_rows()>0){
			if($query->num_rows()>0){
				$response['status'] 			= 	400;
				$response['message'] 			= 	'You have already apllied refferal code!';
				$data = $response;
			}else if($ownRefferquery->num_rows()>0){
				$response['status'] 			= 	400;
				$response['message'] 			= 	'This is your refferal code so you cannot apply it!';
				$data = $response;
			}else{
				$user_detail 	 = $this->getUserData($user_id);
				$reffererUserData = $refferquery->row_array();
				$signup_reward_details = $this->getSignupRewards();
				$updated_user_tokens   			= $user_detail['user_tokens']+$signup_reward_details['refferal_reward_tokens'];
				$userdata['user_tokens']   		= $user_detail['user_tokens']+$signup_reward_details['refferal_reward_tokens'];
				$userdata['refferer_code'] 		= $referal_code;
				$userdata['refferer_user_id'] 	= $reffererUserData['id'];
				$user_data		= array('user_tokens'=>$updated_user_tokens,'refferer_code'=>$referal_code,'refferer_user_id'=>$reffererUserData['id'],'modify'=>date('Y-m-d H:i:s'));
				//pr($user_data,1);
				$this->db->where('id',$user_id);
				$result 	=   $this->db->update('tbl_usermaster',$user_data);
				if($result){
					$user_detail 	 	= $this->getUserData($user_id);
					
					$reffer_userData = $this->getUserData($reffererUserData['id']);
					$reffer_user_tokens = $reffer_userData['user_tokens']+$signup_reward_details['refferal_reward_tokens'];
					$refferer_user_data		= array('user_tokens'=>$reffer_user_tokens,'modify'=>date('Y-m-d H:i:s'));
					$this->db->where('id',$reffererUserData['id']);
					$result 	=   $this->db->update('tbl_usermaster',$refferer_user_data);
					
					$account_details = $this->GetCardDetailsbyUserId($user_id);
					$tokenRefferdata = array('user_id'=>$reffererUserData['id'],'tokens'=>$signup_reward_details['refferal_reward_tokens'],'type'=>'REFER_EARN','created_on'=>date('Y-m-d H:i:s'));
					$result = $this->db->insert('token_purchases',$tokenRefferdata);
					
					$tokenRefferdata = array('user_id'=>$user_id,'tokens'=>$signup_reward_details['refferal_reward_tokens'],'type'=>'REFER_EARN','created_on'=>date('Y-m-d H:i:s'));
					$result = $this->db->insert('token_purchases',$tokenRefferdata);
					$response['status'] 			= 	200;
					$response['message'] 			= 	'You have successfully applied refferal code!';
					$response['user_detail'] 		= 	$user_detail;
					$response['account_details'] 	= 	$account_details;
					$response['user_tokens'] 		=	$updated_user_tokens;
					$data = $response;
				}else{
					$response['status'] 			= 	400;
					$response['message'] 			= 	$this->lang->line('something_went_wrong');
					$data = $response;
				}
			}
		}else{
			$response['status'] 			= 	400;
			$response['message'] 			= 	'You have entered invalid refferal code!';
			$data = $response;
		}
		return $data;
	}
	
	public function resendVerificationEmail($email_id){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('emailid',$email_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$userData	= $query->row_array();
			$user_id 	= $userData['id'];
			$emailid  	= $userData['emailid'];
			$user_name  = $userData['username'];
			
			$verification_code  =  $this->genrate_code($user_id);
			$verification_code = hash('sha256', $verification_code);
			$subject = 'My Match account verification email';
			$to = $emailid;
			$url = base_url('verifyEmail/'.$to.'/'.$verification_code);
				$message =	'Hello '.$user_name.'<br/>';
				$message .= 'Thank you for registering with My Match. Please verify your email by <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
				$message .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
				$message .= 'We hope you enjoy our services!<br/><br/>';
				$message .= 'Sincerely<br/>';
				$message .=  SITE_NAME.' Team';
				$mailConfirm = $this->sendemail($to,$subject,$message);

			$response['status'] 			= 	200;
			$response['message'] 			= 	'Verification mail resent successfully...please click verification link sent to your registered email!';
			$response['user_id'] 			= 	$user_id;
			$data = $response;
		}else{
			$response['status'] 			= 	400;
			$response['message'] 			= 	$this->lang->line('not_registered');
			$data = $response;
		}
		
		return $data;
	}
	
	public function forgotPassword($email_id){
		//pr($email_id,1);
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('emailid',$email_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$userData	= $query->row_array();
			$user_id  	= $userData['id'];
			$user_name  = $userData['username'];
			$token		=	md5($this->get_token(15));
			$to = $email_id;
			$url = base_url('forgot-password/'.$to.'/'.$token);
			if($this->language == 'english')
			{
				$subject 	= 'My Match forgot password email';
				$message =	'Hello '.$user_name.'<br/>';
				$message .= 'We have sent this email in response to your request to reset password on My Match.To reset password please click on this link <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
				$message .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
				$message .= 'We hope you enjoy our services!<br/><br/>';
				$message .= 'Sincerely<br/>';
				$message .=  SITE_NAME.' Team';
			}else{

				$subject 	= SITE_NAME.' Passwort vergessen E-Mail';
				$message =	'Hallo '.$user_name.'<br/>';
				$message .= 'Wir haben diese E-Mail als Antwort auf Ihre Aufforderung zum Zurücksetzen des Passworts in My Match gesendet. Zum Zurücksetzen des Passworts klicken Sie bitte auf diesen Link <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
				$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
				$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
				$message .= 'Mit freundlichen Grüßen<br/>';
				$message .=  SITE_NAME.' Mannschaft';
			}
			$mailConfirm = $this->sendemail($to,$subject,$message);
			if($mailConfirm){
				$user_data=array('forgot_verify_code'=>$token,'modify'=>date('Y-m-d H:i:s'));
				$this->db->where('id',$user_id);
				$result 	=   $this->db->update('tbl_usermaster',$user_data);	
				
				$response['status'] 			= 	200;
				$response['message'] 			= 	$this->lang->link('password_reset_sent');
				$response['user_id'] 			= 	$user_id;
				$data = $response;
			}else{
				$response['status'] 			= 	400;
				$response['message'] 			= 	$this->lang->line('something_went_wrong');
				$data = $response;
			}					
		}else{
			$response['status'] 			= 	400;
			$response['message'] 			= 	$this->lang->line('not_registered');
			$data = $response;
		}
		
		return $data;
	}
	
	public function checkVarificationCode($user_id,$otp)
	{	
		$query = $this->db->select('id,verification_code')->where('id', $user_id)->where('status','1')->where('delete_status','0')->limit(1)->get('tbl_usermaster');
		if($query->num_rows()>0)
		{
			$row = $query->row_array();
			$user_id=$row['id'];
			$db_otp=$row['verification_code'];
			if($db_otp==$otp){
				$login_key = $this->getLoginKey($user_id);
				$user_data=array('account_confirm'=>'C','login_key'=>$login_key,'modify'=>date('Y-m-d H:i:s'));
				$this->db->where('id',$user_id);
				$result 	=   $this->db->update('tbl_usermaster',$user_data);
				$userData	=	$this->getUserData($user_id);
				$userData['login_key'] = $login_key;
				$response['status'] = 200; 
				$response['message'] = 'Otp matched Successfully';
				$response['user_data'][] = $userData;
				
				$data = $response;
			}else{
				$response['status'] = 400;
				$response['message'] = 'Otp not matched!';
				$data = $response;
			}
		}else{
			$response['status'] = 400;
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function resendOtp($user_id)
	{	
		$query = $this->db->select('id,verification_code')->where('id', $user_id)->where('status','1')->where('delete_status','0')->limit(1)->get('tbl_usermaster');
		if($query->num_rows()>0)
		{
			$row = $query->row_array();
			$user_id=$row['id'];
			$otp	=	$this->genrate_code($user_id);
			$response['status'] = 200; 
			$response['message'] = 'Otp resent Successfully!';
			$response['otp'] = $otp;
			$response['user_id'] = $user_id;
			$data = $response;
		}else{
			$response['status'] = 400;
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function completeProfile($user_id,$firstname,$lastname,$emailid,$phone_number,$address,$address2,$zip_code,$city,$state,$password){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			/* $this->db->select('*');
			$this->db->from('tbl_usermaster');
			$this->db->where('mobileno',$phone_number);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$this->db->where_not_in('id',$user_id);
			$check_mobileno = $this->db->get();
			if($check_mobileno->num_rows() > 0){
				$response['status'] = 400; 
				$response['message'] = 'Phone number already register in our system';
				$data = $response;
			}else{ */
				$userData = array(
				/*'firstname'=>$firstname,
				'lastname'=>$lastname,
				'full_name'=>$firstname.' '.$lastname,
				'mobileno'=>$phone_number,
				'address'=>$address,
				'address_2'=>$address2,
				'zip_code'=>$zip_code,
				'city'=>$city,
				'state'=>$state,*/
				'password'=>md5($password),
				//'profile_status'=>'1',
				'modify'=>date('Y-m-d H:i:s')
				);
				$this->db->where('id',$user_id);
				$result = $this->db->update('tbl_usermaster',$userData);
				$userData	=	$this->getUserData($user_id);
				$account_details = $this->GetCardDetailsbyUserId($user_id);
				$response['status']    = 200; 
				$response['message']   = $this->lang->line('profile_updated');
				$response['user_id']   = $user_id;
				$response['user_data'] = $userData;
				$response['account_details'] = $account_details;
				$data = $response;
			/* } */
		}else{
			$response['status'] = 400; 
			$response['error'] = true;
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function saveIRSInformation($user_id,$first_name,$last_name,$email,$contact_number){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$this->db->select('*');
			$this->db->from('user_irs_details');
			$this->db->where('user_id',$user_id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query3 = $this->db->get();
			if($query3->num_rows() > 0)
			{
				$IRS_data = $query3->row_array();
				$user_irs_detail_id = $IRS_data['user_irs_detail_id'];
				$irs_detail = array(
					'first_name'=>$first_name,
					'last_name'=>$last_name,
					'email'=>$email,
					'contact_number'=>$contact_number,
					'modified_on'=>date('Y-m-d H:i:s')
				);
				$result 	= $this->db->update('user_irs_details',$irs_detail,array('user_irs_detail_id'=>$user_irs_detail_id));
				$irs_detail = $this->funGetIRSDetails($user_irs_detail_id);
				$response['status']  			= 200;
				$response['message'] 			= 'Your IRS details have been updated sussfully!';
				$response['user_id'] 			= 	$user_id;
				$response['irs_detail'] 	= 	$irs_detail;
				$data = $response;
			}else{
				$detail = array(
						'user_id'=>$user_id,
						'first_name'=>$first_name,
						'last_name'=>$last_name,
						'email'=>$email,
						'contact_number'=>$contact_number,
						'created_on'=>date('Y-m-d H:i:s')
				);
				$result 	= $this->db->insert('user_irs_details',$detail);
				$user_irs_detail_id = $this->db->insert_id();
				$irs_detail = $this->funGetIRSDetails($user_irs_detail_id);
				$response['status'] 			= 	200;
				$response['message'] 			= 	'Your IRS details have been added successfully!';
				$response['user_id'] 			= 	$user_id;
				$response['irs_detail'] 		= 	$irs_detail;
				$data = $response;
			}			
		}else{
			$response['status'] = 400; 
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function funGetIRSDetails($user_irs_detail_id){
		$this->db->select('*');
		$this->db->from('user_irs_details');
		$this->db->where('user_irs_detail_id',$user_irs_detail_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query2 = $this->db->get();
		$final_response = array();
		if($query2->num_rows()>0){
			$irs_detail = $query2->row_array();
			$final_response['user_irs_detail_id'] 			= ($irs_detail['user_irs_detail_id']===NULL)?"":$irs_detail['user_irs_detail_id'];
			$final_response['first_name'] 					= ($irs_detail['first_name']===NULL)?"":$irs_detail['first_name'];
			$final_response['last_name'] 					= ($irs_detail['last_name']===NULL)?"":$irs_detail['last_name'];
			$final_response['email'] 						= ($irs_detail['email']===NULL)?"":$irs_detail['email'];
			$final_response['contact_number'] 				= ($irs_detail['contact_number']===NULL)?"":$irs_detail['contact_number'];
			
		}
		return $final_response;
	}
	
	public function redeemPrormoCode($user_id,$promocode){
		$user_detail 	 = $this->getUserData($user_id);
		$this->db->select('*');
		$this->db->from('promocodes');
		$this->db->where('promocode',$promocode);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('user_promocodes');
		$this->db->where('user_id',$user_id);
		$this->db->where('promocode',$promocode);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query2 = $this->db->get();
		//pr($query->num_rows(),1);
		if($query->num_rows() > 0){
			$promocode_data = $query->row_array();
			if($query2->num_rows()>=$promocode_data['number_of_times_use']){
				$response['status'] 	= 400; 
				$response['message'] 	= 'You have already used this promotional code!';
				$data = $response;
			}else{
				//pr($promocode_data,1);
				$details = array(
					'user_id' 		=> 	$user_id,
					'promocode_id'	=>	$promocode_data['promocode_id'],
					'promocode_title'=>	$promocode_data['promocode_title'],
					'promocode'		=>	$promocode_data['promocode'],
					'tokens'		=>	$promocode_data['tokens'],
					'created_on'	=>	date('Y-m-d H:i:s'),
				);
				//pr($details,1);
				$saveData = $this->db->insert('user_promocodes',$details);
				$user_promocode_id = $this->db->insert_id();
				if($saveData){
					$updated_user_tokens = $user_detail['user_tokens']+$promocode_data['tokens'];
					$userData		= array('user_tokens'=>$updated_user_tokens,'modify'=>date('Y-m-d H:i:s'));
					$this->db->where('id',$user_id);
					$result 	=   $this->db->update('tbl_usermaster',$userData);
					
					$token_details = array(
						'user_id'		 	=> 	$user_id,
						'tokens'			=>	$promocode_data['tokens'],
						'type'				=>	'PROMOCODE',
						'is_claim_allowed'	=>	'0',
						'created_on'		=>	date('Y-m-d H:i:s'),
					);
					$saveTokenData 	= 	$this->db->insert('token_purchases',$token_details);
					$userData		=	$this->getUserData($user_id);
					$response['status']    = 200; 
					$response['message']   = 'Your Promocode has been redeemed successfully!';
					$response['user_id']   = $user_id;
					$response['user_data'] = $userData;
					$response['user_tokens'] = $updated_user_tokens;
					/* $response['promocode_tokens'] = strval($promocode_data['tokens']); */
					$data = $response;
				}else{
					$response['status'] 	= 400; 
					$response['message'] 	= $this->lang->line('something_went_wrong');
					$data = $response;
				}
			}
		}else{
			$response['status'] 	= 400; 
			$response['message'] 	= 'Invalid Promocode';
			$data = $response;
		}
		return $data;
	}
	
	public function updateProfileImage($user_id,$profileimage){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$userData = array(
				'profileimage'	=>	$profileimage,
				'modify'		=>	date('Y-m-d H:i:s')
				);
			if(is_file(UPLOAD_PHYSICAL_PATH.'users/'.$profileimage) && $profileimage!='')
				{
					$profileimage = UPLOAD_URL.'users/'.$profileimage;
				}else{
					$profileimage = UPLOAD_URL.'users/no_image.png';
				}
		
			$this->db->where('id',$user_id);
			$result = $this->db->update('tbl_usermaster',$userData);
			$response['status'] 		= 200; 
			$response['message'] 		= 'Profile image updated successfully';
			$response['profileimage'] 	= $profileimage;
			$response['user_id'] 		= $user_id;
			$data = $response;
		}else{
			$response['status'] 	= 400; 
			$response['message'] 	= 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function editProfile($user_id,$firstname,$lastname,$emailid,$address,$is_default_address){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$this->db->select('*');
			$this->db->from('tbl_usermaster');
			$this->db->where('emailid',$emailid);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$this->db->where_not_in('id',$user_id);
			$check_email = $this->db->get();
			if($check_email->num_rows() > 0){
				$response['status'] = 400; 
				$response['message'] = 'Email already register in our system';
				$data = $response;
			}else{
				
				$userData = array(
				'firstname'=>$firstname,
				'lastname'=>$lastname,
				'full_name'=>$firstname.' '.$lastname,
				'emailid'=>$emailid,
				'address'=>$address,
				'is_default_address'=>strtoupper($is_default_address),
				'profile_status'=>'1',
				'modify'=>date('Y-m-d H:i:s')
				);
				if(strtoupper($is_default_address) == "HOME"){
					$userData['home_address'] = $address;
				}else if(strtoupper($is_default_address) == "OFFICE"){
					$userData['office_address'] = $address;
				}else if(strtoupper($is_default_address) == "FRIEND"){
					$userData['friend_address'] = $address;
				}
				$this->db->where('id',$user_id);
				$result = $this->db->update('tbl_usermaster',$userData);
				$userData	=	$this->getUserData($user_id);
				$response['status'] = 200; 
				$response['message'] = 'Detail updated successfully';
				$response['user_id'] = $user_id;
				$response['user_data'] = $userData;
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['error'] = true;
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function addCardDetail($user_id,$card_number,$expiry_month,$expiry_year,$cvv_number,$card_holder_name,$security_code,$bank_name){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$this->db->select('*');
			$this->db->from('user_account_details');
			$this->db->where('customer_id',$user_id);
			/* $this->db->where('card_number',$card_number); */
			$this->db->where('acc_status','1');
			$this->db->where('acc_delete_status','0');
			$query3 = $this->db->get();
			if($query3->num_rows() > 0)
			{
				$account_data = $query3->row_array();
				$account_id = $account_data['account_id'];
				$acc_detail = array(
					'bank_name'=>$bank_name,
					'card_number'=>$card_number,
					/*'expiry_month'=>$expiry_month,
					'expiry_year'=>$expiry_year,
					'cvv_number'=>$cvv_number,*/
					'card_holder_name'=>$card_holder_name,
					'security_code'=>$security_code,
					'modified_on'=>date('Y-m-d H:i:s')
				);
				$result 	= $this->db->update('user_account_details',$acc_detail,array('account_id'=>$account_id));
				$account_detail = $this->funGetCardDetails($account_id);
				$response['status']  = 200;
				$response['message'] = $this->lang->line('card_details_updated');
				$response['user_id'] 			= 	$user_id;
				$response['account_detail'] 		= 	$account_detail;
				$data = $response;
			}else{
				$detail = array(
						'customer_id'=>$user_id,
						'bank_name'=>$bank_name,
						'card_number'=>$card_number,
						/*'expiry_month'=>$expiry_month,
						'expiry_year'=>$expiry_year,
						'cvv_number'=>$cvv_number,*/
						'card_holder_name'=>$card_holder_name,
						'security_code'=>$security_code,
						'created_on'=>date('Y-m-d H:i:s')
				);
				$result 	= $this->db->insert('user_account_details',$detail);
				$account_id = $this->db->insert_id();
				$account_detail = $this->funGetCardDetails($account_id);
				$response['status'] 			= 	200;
				$response['message'] 			= 	'Your card details have been added successfully!';
				$response['user_id'] 			= 	$user_id;
				$response['account_detail'] 	= 	$account_detail;
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function funGetCardDetails($account_id){
		$this->db->select('*');
		$this->db->from('user_account_details');
		$this->db->where('account_id',$account_id);
		$this->db->where('acc_status','1');
		$this->db->where('acc_delete_status','0');
		$query2 = $this->db->get();
		$final_response = array();
		if($query2->num_rows()>0){
			$account_detail = $query2->row_array();
			$final_response['account_id'] 			= ($account_detail['account_id']===NULL)?"":$account_detail['account_id'];
			$final_response['bank_name'] 			= ($account_detail['bank_name']===NULL)?"":$account_detail['bank_name'];
			$final_response['account_number'] 			= ($account_detail['card_number']===NULL)?"":$account_detail['card_number'];
			/*$final_response['expiry_month'] 		= ($account_detail['expiry_month']===NULL)?"":$account_detail['expiry_month'];
			$final_response['expiry_year'] 			= ($account_detail['expiry_year']===NULL)?"":$account_detail['expiry_year'];
			$final_response['cvv_number'] 			= ($account_detail['cvv_number']===NULL)?"":$account_detail['cvv_number'];*/
			$final_response['card_holder_name'] 	= ($account_detail['card_holder_name']===NULL)?"":$account_detail['card_holder_name'];
			$final_response['ifsc_code'] 		= ($account_detail['security_code']===NULL)?"":$account_detail['security_code'];
			//$final_response['is_default'] 			= ($account_detail['is_default']===NULL)?"":$account_detail['is_default'];
		}
		return $final_response;
	}
	
	public function GetCardDetailsbyUserId($user_id){
		$this->db->select('*');
		$this->db->from('user_account_details');
		$this->db->where('customer_id',$user_id);
		$this->db->where('acc_status','1');
		$this->db->where('acc_delete_status','0');
		$query2 = $this->db->get();
		$final_response = array();
		if($query2->num_rows()>0){
			$account_detail = $query2->row_array();
			$final_response['account_id'] 			= ($account_detail['account_id']===NULL)?"":$account_detail['account_id'];
			$final_response['bank_name'] 			= ($account_detail['bank_name']===NULL)?"":$account_detail['bank_name'];
			$final_response['account_number'] 			= ($account_detail['card_number']===NULL)?"":$account_detail['card_number'];
			/*$final_response['expiry_month'] 		= ($account_detail['expiry_month']===NULL)?"":$account_detail['expiry_month'];
			$final_response['expiry_year'] 			= ($account_detail['expiry_year']===NULL)?"":$account_detail['expiry_year'];
			$final_response['cvv_number'] 			= ($account_detail['cvv_number']===NULL)?"":$account_detail['cvv_number'];*/
			$final_response['card_holder_name'] 	= ($account_detail['card_holder_name']===NULL)?"":$account_detail['card_holder_name'];
			$final_response['ifsc_code'] 		= ($account_detail['security_code']===NULL)?"":$account_detail['security_code'];
			//$final_response['is_default'] 			= ($account_detail['is_default']===NULL)?"":$account_detail['is_default'];
		}else{
			$final_response['account_id'] 			= "";
			$final_response['bank_name'] 			= "";
			$final_response['account_number'] 			= "";
			/*$final_response['expiry_month'] 		= "";
			$final_response['expiry_year'] 			= "";
			$final_response['cvv_number'] 			= "";*/
			$final_response['card_holder_name'] 	= "";
			$final_response['ifsc_code'] 		= "";
			//$final_response['is_default'] 			= "";
		}
		return $final_response;
	}
	
	
	public function getCardDetails($user_id){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$this->db->select('*');
			$this->db->from('user_account_details');
			$this->db->where('customer_id',$user_id);
			$this->db->where('acc_status','1');
			$this->db->where('acc_delete_status','0');
			$query2 = $this->db->get();
			$finalArr = array();
			$account_detail = array();
			if($query2->num_rows() <= 0)
			{
				$response['status']  = 200;
				$response['error']   = false;
				$response['message'] = $this->lang->line('no_card_details');
				$response['account_detail'] = $account_detail;
				$data = $response;
			}else{
				$accountData = $query2->result_array();
				foreach($accountData as $account)
				{

					$final_response['account_id'] 			= ($account['account_id']===NULL)?"":$account['account_id'];
					$final_response['bank_name'] 			= ($account['bank_name']===NULL)?"":$account['bank_name'];
					$final_response['account_number'] 		= ($account['card_number']===NULL)?"":$account['card_number'];
					/*$final_response['expiry_month'] 		= ($account_detail['expiry_month']===NULL)?"":$account_detail['expiry_month'];
					$final_response['expiry_year'] 			= ($account_detail['expiry_year']===NULL)?"":$account_detail['expiry_year'];
					$final_response['cvv_number'] 			= ($account_detail['cvv_number']===NULL)?"":$account_detail['cvv_number'];*/
					$final_response['card_holder_name'] 	= ($account['card_holder_name']===NULL)?"":$account['card_holder_name'];
					$final_response['ifsc_code'] 		= ($account['security_code']===NULL)?"":$account['security_code'];
					$finalArr[] = $final_response;
				}
				$response['status']  = 200;
				$response['error']   = false;
				$response['message'] = $this->lang->line('card_details');
				$response['account_detail'] = $finalArr;
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['error'] = true;
			$response['message'] = 'User not found';
			$data = $response;
		}
		return $data;
	}
	
	public function userSettings($user_id,$show_notification_status,$is_loot_box_unlocked,$daily_reminder_status){
		$save_detail = array(
			'show_notification_status'=>$show_notification_status,
			'is_loot_box_unlocked'=>$is_loot_box_unlocked,
			'daily_reminder_status'=>$daily_reminder_status,
			/* 'other'=>$other, */
			'modify'=>date('Y-m-d H:i:s')
		);
		$result 		 = $this->db->update('tbl_usermaster',$save_detail,array('id'=>$user_id));
		$settings_detail = $this->funGetUserSettings($user_id);
		$response['status']  		= 200;
		$response['message'] 		= 'Settings has been updated successfully!';
		$response['user_id'] 		= 	$user_id;
		$response['settings_detail'] = 	$settings_detail;
		$data = $response;
		return $data;
	}
	
	public function funGetUserSettings($user_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		$result = $query->row_array();
		$response = array();
		$response['show_notification_status'] 	= $result['show_notification_status'];
		$response['is_loot_box_unlocked'] 		= $result['is_loot_box_unlocked'];
		$response['daily_reminder_status'] 		= $result['daily_reminder_status'];
		$response['other'] 						= $result['other'];
		return $response;
	}
	
	public function contact_us($name,$email,$company,$message,$mobile_no){
		//pr($message,1);
		
		$user_name  = $mobile_no;

		$to_user = $email;
		if($this->language == 'english')
		{
			$subject_user 	= SITE_NAME.' Contact us email';
			$message_user =	'Hello '.$user_name.'<br/>';
			$message_user .= 'Thank you for contact us, we will reach you soon.<br/>';
			//$message_user .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
			$message_user .= 'We hope you enjoy our services!<br/><br/>';
			$message_user .= 'Sincerely<br/>';
			$message_user .=  SITE_NAME.' Team';
		}else{
			$subject_user 	= SITE_NAME.' Kontaktieren Sie uns per E-Mail';
			$message_user =	'Hallo '.$user_name.'<br/>';
			$message_user .= 'Vielen Dank für Ihre Kontaktaufnahme. Wir werden uns bald bei Ihnen melden.<br/>';
			//$message_user .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
			$message_user .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
			$message_user .= 'Mit freundlichen Grüßen<br/>';
			$message_user .=  SITE_NAME.' Mannschaft';
		}
		//pr($message_user);
		$mailConfirm1 = $this->sendemail($to_user,$subject_user,$message_user);
		
		$to_admin 		= CONTACT_US_ADMIN_EMAIL;

		if($this->language == 'english')
		{
			$subject_admin 	= SITE_NAME.' Contact us email';
			$message_admin  = 'Hello Admin <br/>';
			$message_admin .= 'Mobile No. : '.$mobile_no.'<br/>';
			$message_admin .= 'Email :'.$email.'<br/>';
			//$message_admin .= 'Company :'.$company.'<br/>';
			$message_admin .= 'Message :'.$message.'<br/>';
		}else{
			$subject_admin 	= SITE_NAME.' Kontaktieren Sie uns per E-Mail';
			$message_admin  = 'Hallo Admin <br/>';
			$message_admin .= 'Handynummer. : '.$mobile_no.'<br/>';
			$message_admin .= 'Email :'.$email.'<br/>';
			//$message_admin .= 'Company :'.$company.'<br/>';
			$message_admin .= 'Botschaft :'.$message.'<br/>';
		}
		//pr($message_admin,1);
		//$message .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
		$mailConfirm2 = $this->sendemail($to_admin,$subject_admin,$message_admin);
		if($mailConfirm1 && $mailConfirm2){
			$detail = array(
				'mobile_no'=>$mobile_no,
				'email'=>$email,
				//'company'=>$company,
				'message'=>$message,
				'created_on'=>date('Y-m-d H:i:s')
			);
			$result 	= $this->db->insert('contact_us',$detail);
			$contact_us_id = $this->db->insert_id();
			$response['status'] 			= 	200;
			$response['message'] 			= 	'Thanks for reaching out! We’ll get back to you as soon as we can if needed. We appreciate all our users.”';
			//$response['contact_us_id'] 		= 	$contact_us_id;
			$data = $response;
		}else{
			$response['status'] = 400; 
			$response['message'] = $this->lang->line('something_went_wrong');
			$data = $response;
		}
		return $data;
	}
	
	public function getCategories($user_id){
		//$user_details = $this->getUserData($user_id); 
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			$categories_list = $query->result_array();
			$userResponse = array();
			
			//print_r($categories_list);die;
			
			foreach($categories_list as $category){
				$userResponse['category_id'] 		=	 ($category['category_id']===NULL)?'':$category['category_id'];
				$userResponse['category_name'] 	=	 ($category['category_name']===NULL)?'':$category['category_name'];
				/*if(is_file(UPLOAD_PHYSICAL_PATH.'categories/'.$category['category_image']) && $category['category_image']!='')
				{
					$userResponse['category_image'] = UPLOAD_URL.'categories/'.$category['category_image'];
				}else{
					$userResponse['category_image'] = WEB_IMG_URL.'default_category.png';
				}*/
				
				$final_array[] = $userResponse;
			}
			/* if($user_id){
				//$user_details = $this->getUserData($user_id);
				$for_you_cat_image  = $user_details['profileimage'];
				//print_r($user_details['profileimage']);die;
				$caregory_name_for_you = "hello ".$user_details['username'];
				$new_arr = array('category_id'=>'0','category_name'=>$caregory_name_for_you);
				array_unshift($final_array, $new_arr);
			} */
			
			$response['status'] = 200; 
			$response['message'] = 'Category list';
			$response['category_data'] = $final_array;
			$data = $response;
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Category list is not found!';
			$data = $response;
		}
		return $data;
	}
	
	public function getStoreProductsList($user_id,$limit,$offset,$category_id,$sort_by_tokens){
		$where = "";
		$order_by = "";
		if(isset($category_id) && $category_id!=""){
			$where = "AND sp.category_id='".$category_id."' ";
		}
		if(isset($sort_by_tokens) && ($sort_by_tokens=="ASC" || $sort_by_tokens=="DESC")){
			$order_by = "sp.tokens ".$sort_by_tokens."";
		}
		if(isset($order_by) && ($order_by=="" || $order_by==NULL)){
			$sql = 'SELECT sp.*,ct.category_name,ct.category_image FROM store_products sp  LEFT JOIN categories ct ON ct.category_id=sp.category_id ,(SELECT @a:= 0) AS a where 1 AND sp.delete_status = "0" AND ct.delete_status = "0" '.$where.'';
		}else{
			$sql = 'SELECT sp.*,ct.category_name,ct.category_image FROM store_products sp  LEFT JOIN categories ct ON ct.category_id=sp.category_id ,(SELECT @a:= 0) AS a where 1 AND sp.delete_status = "0" AND ct.delete_status = "0" '.$where.' ORDER BY '.$order_by.' ';
		}
		$query = $this->db->query($sql);
		//pr($query->num_rows(),1);
		if($query->num_rows()>0)
		{
			$store_products_list = $query->result_array();
			//pr($room_list,1);
			$userResponse = array();
			foreach($store_products_list as $store_products){
				$userResponse['store_room_id'] 		=	 ($store_products['store_room_id']===NULL)?'':$store_products['store_room_id'];
				$userResponse['category_id'] 				=	 ($store_products['category_id']===NULL)?'':$store_products['category_id'];
				$userResponse['product_title'] 			=	 ($store_products['product_title']===NULL)?'':$store_products['product_title'];
				$userResponse['category_name'] 				=	 ($store_products['category_name']===NULL)?'':$store_products['category_name'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'categories/'.$store_products['category_image']) && $store_products['category_image']!='')
				{
					$userResponse['category_image'] = UPLOAD_URL.'categories/'.$store_products['category_image'];
				}else{
					$userResponse['category_image'] = UPLOAD_URL.'categories/default_category.png';
				}
				$userResponse['tokens'] 	=	 ($store_products['tokens']===NULL)?'':$store_products['tokens'];
				$userResponse['affiliate_link'] 			=	 ($store_products['affiliate_link']===NULL)?'':$store_products['affiliate_link'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'stores/'.$store_products['product_image']) && $store_products['product_image']!='')
				{
					$userResponse['product_image'] = UPLOAD_URL.'stores/'.$store_products['product_image'];
				}else{
					$userResponse['product_image'] = UPLOAD_URL.'stores/default_product.png';
				}
				
				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response['status'] = 200; 
			$response['message'] = 'Store Products list';
			$response['store_data'] = $final_response;
			$data = $response;
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Store list is not found!';
			$data = $response;
		}
		return $data;
	}
	
	public function getStoreProductDetails($user_id,$store_room_id){
		$this->db->select('sp.*,c.category_name,c.category_image');
		$this->db->from('store_products as sp');
		$this->db->join('categories as c',"c.category_id=sp.category_id",'left');
		$this->db->where('sp.store_room_id',$store_room_id);
		$this->db->where('sp.status','1');
		$this->db->where('sp.delete_status','0');
		$query=$this->db->get();
		//echo $this->db->last_query();die;
		if($query->num_rows()>0)
		{
			$store_room_details = $query->row_array();
			//pr($room_details,1);
			
			$userResponse = array();
			$userResponse['store_room_id'] 		=	 ($store_room_details['store_room_id']===NULL)?'':$store_room_details['store_room_id'];
			$userResponse['category_id'] 				=	 ($store_room_details['category_id']===NULL)?'':$store_room_details['category_id'];
			$userResponse['product_title'] 				=	 ($store_room_details['product_title']===NULL)?'':$store_room_details['product_title'];
			$userResponse['product_description'] 		=	 ($store_room_details['product_description']===NULL)?'':$store_room_details['product_description'];
			$userResponse['category_name'] 				=	 ($store_room_details['category_name']===NULL)?'':$store_room_details['category_name'];
			$userResponse['tokens'] 					=	 ($store_room_details['tokens']===NULL)?'':$store_room_details['tokens'];
			$userResponse['affiliate_link'] 			=	 ($store_room_details['affiliate_link']===NULL)?'':$store_room_details['affiliate_link'];
			
			if(is_file(UPLOAD_PHYSICAL_PATH.'categories/'.$store_room_details['category_image']) && $store_room_details['category_image']!='')
			{
				$userResponse['category_image'] = UPLOAD_URL.'categories/'.$store_room_details['category_image'];
			}else{
				$userResponse['category_image'] = UPLOAD_URL.'categories/default_category.png';
			}
			if(is_file(UPLOAD_PHYSICAL_PATH.'stores/'.$store_room_details['product_image']) && $store_room_details['product_image']!='')
			{
				$userResponse['product_image'] = UPLOAD_URL.'stores/'.$store_room_details['product_image'];
			}else{
				$userResponse['product_image'] = UPLOAD_URL.'stores/default_product.png';
			}
			
			$response['status'] = 200; 
			$response['message'] = 'Store Product Details';
			$response['store_product_detail'] = $userResponse;
			$data = $response;
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Store Product data is not found!';
			$data = $response;
		}
		return $data;
	}	
	
	public function getPerformers($user_id,$month,$year)
	{	
		$this->db->select('player_of_month_id,month,year,name,rank,kd,win_rate');
		$this->db->from('player_of_month');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->order_by('player_of_month_id','DESC');
		$query1 = $this->db->get();
		$player_of_the_month = array('player_of_month_id' => '','month' => '','year' => '','name' => '','rank' => '','kd' => '','win_rate' => '');
		if($query1->num_rows()>0)
		{
			$player_of_the_month = $query1->row_array();
		}	

		$this->db->select('team_of_month_id,month,year,name,rank');
		$this->db->from('team_of_month');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->order_by('team_of_month_id','DESC');
		$query = $this->db->get();
		$team_of_the_month = array('team_of_month_id' => '','month' => '','year' => '','name' => '','rank' => '');
		if($query->num_rows()>0)
		{
			$team_of_the_month = $query->row_array();
		}

		$response['status'] = 200; 
		$response['message'] = $this->lang->line('performers_fetched');
		$response['player_of_the_month'] = $player_of_the_month;
		$response['team_of_the_month'] = $team_of_the_month;
		$data = $response;
		return $data;
	}	
	
	public function getRoomsList($user_id,$category_id,$limit,$offset,$sort_by_tickets_remaining,$sort_by_price_value,$sort_by_start_date)
	{
	
		$this->db->select('*');
		$this->db->from('banners');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query1 = $this->db->get();
		$banner_final_array = array();
		if($query1->num_rows()>0)
		{
			$banners_list = $query1->result_array();
			$userResponse = array();
			foreach($banners_list as $banner){
				//echo $banner['banner_image']; die();
				if(is_file(UPLOAD_PHYSICAL_PATH.'banners/'.$banner['banner_image']) && $banner['banner_image']!='')
				{
					$userResponse['banner_img_url'] = UPLOAD_URL.'banners/'.$banner['banner_image'];
				}else{
					$userResponse['banner_img_url'] = UPLOAD_URL.'banners/default_banner_image.jpg';
				}
				
				$banner_final_array[] = $userResponse;
			}
		}

		$where = "";
		$order_by = "c.start_datetime DESC";
		if(isset($category_id) && $category_id!=""){
			$where = "AND c.category_id='".$category_id."' ";
		}
		if(isset($sort_by_tickets_remaining) && ($sort_by_tickets_remaining=="ASC" || $sort_by_tickets_remaining=="DESC")){
			$order_by = "remaining_tickets ".$sort_by_tickets_remaining."";
		}
		if(isset($sort_by_price_value) && ($sort_by_price_value=="ASC" || $sort_by_price_value=="DESC")){
			$order_by = "c.grand_loot_price_value ".$sort_by_price_value."";
		}
		if(isset($sort_by_start_date) && ($sort_by_start_date=="ASC" || $sort_by_start_date=="DESC")){
			$order_by = "c.start_datetime ".$sort_by_start_date."";
		}

		$sql = 'SELECT c.*,ct.category_name,ct.category_image,CONCAT(au.first_name," ",au.last_name) as organized_by,getTotalSoldTickets(c.room_id) as users_purchased_ticket FROM rooms c LEFT JOIN categories ct ON c.category_id=ct.category_id LEFT JOIN admin_users au ON c.user_id=au.user_id where 1 AND c.delete_status = "0" AND ct.delete_status = "0" AND c.event_completed = "0" AND c.start_datetime <= "'.date('Y-m-d H:i:s').'" '.$where.' ORDER BY '.$order_by.' ';
		//echo $sql; die();
		//echo $order_by;die;		
		/*if(isset($order_by) && ($order_by=="" || $order_by==NULL)){
			//echo $order_by;die;
			$sql = 'SELECT sd.*,c.*,ct.category_name,ct.category_image,getTotalSoldTickets(sd.room_drawing_id) as sold_tickets,c.available_tickets-getTotalSoldTickets(sd.room_drawing_id) as remaining_tickets FROM rooms_drawings sd LEFT JOIN rooms c ON c.room_id=sd.room_id LEFT JOIN categories ct ON c.category_id=ct.category_id ,(SELECT @a:= 0) AS a where 1 AND c.delete_status = "0" AND ct.delete_status = "0" AND sd.is_drawing_complete = "0" AND (sd.start_date <= "'.date('Y-m-d').'" AND sd.end_date >= "'.date('Y-m-d').'") '.$where.' group by sd.room_id ORDER BY c.room_id DESC ';
			//echo $sql; die();
		}else{
			//echo "hello";die;		
			$sql = 'SELECT sd.*,c.*,ct.category_name,ct.category_image,getTotalSoldTickets(sd.room_drawing_id) as sold_tickets,c.available_tickets-getTotalSoldTickets(sd.room_drawing_id) as remaining_tickets FROM rooms_drawings sd LEFT JOIN rooms c ON c.room_id=sd.room_id LEFT JOIN categories ct ON c.category_id=ct.category_id ,(SELECT @a:= 0) AS a where 1 AND c.delete_status = "0" AND ct.delete_status = "0" AND sd.is_drawing_complete = "0" AND (sd.start_date <= "'.date('Y-m-d').'" AND sd.end_date >= "'.date('Y-m-d').'") '.$where.' group by sd.room_id ORDER BY '.$order_by.' ';
		}*/
		//echo $sql;die;
		$query = $this->db->query($sql);
		//pr($query->num_rows(),1);
		$final_response = array();
		if($query->num_rows()>0)
		{
			$room_list = $query->result_array();
			//pr($room_list,1);
			foreach($room_list as $room){
				
				$userResponse = array();
				$userResponse['room_id'] 			=	 ($room['room_id']===NULL)?'':$room['room_id'];
				$userResponse['category_id'] 		=	 ($room['category_id']===NULL)?'':$room['category_id'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$room['room_image']) && $room['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$room['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
				if($this->language=='english')
				{
					$roomName = $room['room_name'];
				}else{
					$roomName = $room['room_name_gr'];
				}
				$userResponse['room_name'] 			=	 ($room['room_name']===NULL)?'':$roomName;
				$userResponse['entry_fees'] 		=	 ($room['per_ticket_tokens']===NULL)?'':$room['per_ticket_tokens'];
				$userResponse['start_datetime']		=	 ($room['start_datetime']==="0000-00-00 00:00:00")?'NA':date('d/m/Y h:i',strtotime($room['start_datetime']));
				$userResponse['winning_price_first'] 		=	 ($room['grand_loot_price_value']===NULL)?'0':$room['grand_loot_price_value'];
				$userResponse['winning_price_second'] 		=	 ($room['secoundry_prize_value']===NULL)?'0':$room['secoundry_prize_value'];
				$userResponse['winning_price_third'] 		=	 ($room['third_price_value']===NULL)?'0':$room['third_price_value'];
				$userResponse['room_type'] 			=	 ($room['category_name']===NULL)?'':$room['category_name'];
				$userResponse['total_seats'] 		=	 ($room['available_tickets']===NULL)?'':$room['available_tickets'];
				$userResponse['total_enterd_users'] 		=	 ($room['users_purchased_ticket']===NULL)?'':$room['users_purchased_ticket'];
				$userResponse['remaining_tickets'] 		=	 ($room['users_purchased_ticket']===NULL)?'':$room['available_tickets']-$room['users_purchased_ticket'];
				$userResponse['organized_by'] 		=	 ($room['organized_by']===NULL)?'':$room['organized_by'];
				//$userResponse['available_tickets'] 			=	 ($room['available_tickets']===NULL)?'':$room['available_tickets'];
				//$userResponse['sold_tickets'] 				=	 ($room['sold_tickets']===NULL)?'':$room['sold_tickets'];
				/*if(is_file(UPLOAD_PHYSICAL_PATH.'categories/'.$room['category_image']) && $room['category_image']!='')
				{
					$userResponse['category_image'] = UPLOAD_URL.'categories/'.$room['category_image'];
				}else{
					$userResponse['category_image'] = UPLOAD_URL.'categories/default_category.png';
				}*/
				
				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response['status'] = 200; 
			$response['message'] = $this->lang->line('room_list_fetched');
			$response['banner_images'] = $banner_final_array;
			$response['room_listing'] = $final_response;
			$data = $response;
		}else{
			$response['status'] = 200; 
			$response['message'] = $this->lang->line('no_room_found');
			$response['banner_images'] = $banner_final_array;
			$response['room_listing'] = $final_response;
			$data = $response;
		}
		return $data;
	}
	
	
	public function getNewsList($user_id,$category_id,$limit,$offset)
	{
	
		$where = "";
		$order_by = "n.created_on DESC";
		
		$sql = 'SELECT n.*,CONCAT(au.first_name," ",au.last_name) as uploaded_by,getTotalComments(n.news_id) as total_comments, getTotalLikes(n.news_id) as total_likes FROM news n LEFT JOIN admin_users au ON n.user_id=au.user_id where 1 AND n.delete_status = "0" '.$where.' ORDER BY '.$order_by.' ';
		//echo $sql;die;
		$query = $this->db->query($sql);
		//pr($query->num_rows(),1);
		$final_response = array();
		if($query->num_rows()>0)
		{
			$news_list = $query->result_array();
			//pr($news_list,1);
			foreach($news_list as $news){
				
				$userResponse = array();
				$userResponse['news_id'] 			=	 ($news['news_id']===NULL)?'':$news['news_id'];
				if($this->language=='english')
				{
					$news_title = $news['news_title'];
					$description = $news['description'];
				}else{
					$news_title = $news['news_title_gr'];
					$description = $news['description_gr'];
				}
				if(is_file(UPLOAD_PHYSICAL_PATH.'news/'.$news['news_image']) && $news['news_image']!='')
				{
					$userResponse['news_image'] = UPLOAD_URL.'rooms/'.$news['news_image'];
				}else{
					$userResponse['news_image'] = UPLOAD_URL.'rooms/default_news_image.jpg';
				}
				$userResponse['news_title'] 			=	 ($news['news_title']===NULL)?'':$news_title;
				$userResponse['description'] 			=	 ($news['description']===NULL)?'':$description;
				$userResponse['total_comments'] 		=	 ($news['total_comments']===NULL)?'':$news['total_comments'];
				$userResponse['total_likes'] 		=	 ($news['total_likes']===NULL)?'':$news['total_likes'];
				$userResponse['uploaded_by'] 		=	 ($news['uploaded_by']===NULL)?'':$news['uploaded_by'];
				$userResponse['added_on'] 		=	 ($news['created_on']===NULL)?'':$news['created_on'];
								
				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response['resultCode'] = 200; 
			$response['message'] = $this->lang->line('news_list_fetched');
			$response['news_listing'] = $final_response;
			$data = $response;
		}else{
			$response['status'] = 200; 
			$response['resultCode'] = $this->lang->line('no_news_found');
			$response['news_listing'] = $final_response;
			$data = $response;
		}
		return $data;
	}
		
	public function getCommentsList($user_id,$category_id,$limit,$offset,$news_id)
	{
	
		$where = " AND nc.news_id = '".$news_id."' ";
		$order_by = "nc.created_on DESC";
		
		$sql = 'SELECT nc.*,u.full_name as commented_by,u.profileimage,  getTotalLikesDislikesOnComments(nc.comment_id,"LIKED") as total_likes,  getTotalLikesDislikesOnComments(nc.comment_id,"DISLIKED") as total_dislikes FROM news_comments nc LEFT JOIN tbl_usermaster u ON nc.user_id=u.id where 1 AND nc.delete_status = "0" AND u.delete_status = "0" AND nc.status = "1" AND u.status = "1" '.$where.' ORDER BY '.$order_by.' ';
		// 
		//echo $sql;die;
		$query = $this->db->query($sql);
		//pr($query->num_rows(),1);
		$final_response = array();
		if($query->num_rows()>0)
		{
			$news_list = $query->result_array();
			//pr($news_list,1);
			foreach($news_list as $news){
				
				$userResponse = array();
				$userResponse['comment_id'] 		=	 ($news['comment_id']===NULL)?'':$news['comment_id'];
				$userResponse['news_id'] 			=	 ($news['news_id']===NULL)?'':$news['news_id'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'users/'.$news['profileimage']) && $news['profileimage']!='')
				{
					$userResponse['user_image'] = UPLOAD_URL.'users/'.$news['profileimage'];
				}else{
					$userResponse['user_image'] = UPLOAD_URL.'users/no_image.png';
				}
				$userResponse['commented_by'] 		=	 ($news['commented_by']===NULL)?'':$news['commented_by'];
				$userResponse['commented_on']		=	 ($news['created_on']==="0000-00-00 00:00:00")?'NA':date('d/m/Y h:i',strtotime($news['created_on']));
				$userResponse['comment'] 			=	 ($news['comment']===NULL)?'':$news['comment'];
				
				$userResponse['total_likes'] 		=	 ($news['total_likes']===NULL)?'':$news['total_likes'];
				$userResponse['total_dislikes'] 	=	 ($news['total_dislikes']===NULL)?'':$news['total_dislikes'];
				
								
				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response['resultCode'] = 200; 
			$response['message'] = $this->lang->line('comment_list_fetched');
			$response['news_listing'] = $final_response;
			$data = $response;
		}else{
			$response['resultCode'] = 200; 
			$response['message'] = $this->lang->line('no_comment_found');
			$response['news_listing'] = $final_response;
			$data = $response;
		}
		return $data;
	}
		
	public function addComment($user_id,$comment,$news_id)
	{
		$insertData = array();
		$insertData['user_id'] 		= $user_id;
		$insertData['comment'] 		= $comment;
		$insertData['news_id'] 		= $news_id;
		$insertData['created_on']	= date('Y-m-d H:i:s');
		$insertData['modified_on']	= date('Y-m-d H:i:s');
		if($this->db->insert('news_comments',$insertData))
		{
			$response['resultCode'] = 200; 
			$response['message'] = $this->lang->line('comment_added');
			$data = $response;
		}else{
			$response['resultCode'] = 200; 
			$response['message'] = $this->lang->line('something_went_wrong');
			$data = $response;
		}
		return $data;
	}
		
	public function likeDislikeOnComment($user_id,$comment_id,$type)
	{
		$typeDetails = $this->commonmodel->_get_data('likes_dislikes_on_comments',array('comment_id'=>$comment_id,'user_id'=>$user_id,'delete_status'=>'0'),'type,likes_dislikes_id');
		if($typeDetails===NULL)
		{
			$insertData = array();
			$insertData['type'] 		= $type;
			$insertData['comment_id'] 	= $comment_id;
			$insertData['user_id'] 		= $user_id;
			$insertData['created_on']	= date('Y-m-d H:i:s');
			$insertData['modified_on']	= date('Y-m-d H:i:s');
			if($this->db->insert('likes_dislikes_on_comments',$insertData))
			{
				$response['resultCode'] = 200;
				if($type=='LIKED')
				{
					$response['message'] = $this->lang->line('comment_liked');
				}else{
					$response['message'] = $this->lang->line('comment_disliked');
				}
				$response['likes_dislikes_details'] = $this->getTotalLikesDislikesOnComments($comment_id);
				$data = $response;
			}else{
				$response['resultCode'] = 200; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}else{
			//pr($typeDetails,1);
			
			$likes_dislikes_id = $typeDetails[0]['likes_dislikes_id'];

			$updateData = array();
			$updateData['type'] 		= $type;
			$insertData['modified_on']	= date('Y-m-d H:i:s');
			$this->db->where('likes_dislikes_id',$likes_dislikes_id);
			if($this->db->update('likes_dislikes_on_comments',$updateData))
			{
				$response['resultCode'] = 200;
				if($type=='LIKED')
				{
					$response['message'] = $this->lang->line('comment_liked');
				}else{
					$response['message'] = $this->lang->line('comment_disliked');
				}
				$response['likes_dislikes_details'] = $this->getTotalLikesDislikesOnComments($comment_id);
				$data = $response;
			}else{
				$response['resultCode'] = 200; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}
		return $data;
	}
		
	public function likeOnNews($user_id,$news_id)
	{
		$typeDetails = $this->commonmodel->_get_data('news_likes',array('news_id'=>$news_id,'user_id'=>$user_id,'status'=>'1','delete_status'=>'0'),'news_like_id');
		if($typeDetails===NULL)
		{
			$insertData = array();
			$insertData['user_id'] 		= $user_id;
			$insertData['news_id'] 	= $news_id;
			$insertData['created_on']	= date('Y-m-d H:i:s');
			$insertData['modified_on']	= date('Y-m-d H:i:s');
			if($this->db->insert('news_likes',$insertData))
			{
				$response['resultCode'] = 200;
				$response['message'] = $this->lang->line('news_liked');
				$response['likes_on_news'] = $this->getTotalLikesOnNews($news_id);
				$data = $response;
			}else{
				$response['resultCode'] = 200; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}else{
			$response['resultCode'] = 200; 
			$response['message'] = $this->lang->line('already_news_liked');
			$response['likes_on_news'] = $this->getTotalLikesOnNews($news_id);
			$data = $response;
		}
		return $data;
	}

	public function getTotalLikesOnNews($news_id=0)
	{
		$sql = 'SELECT count(news_like_id) as likes_on_news FROM news_likes where 1 AND delete_status = "0" AND status = "1" AND news_id = "'.$news_id.'" ';
		// 
		//echo $sql;die;
		$query = $this->db->query($sql);
		//pr($query->num_rows(),1);
		$final_response = array();
		if($query->num_rows()>0)
		{
			$likes_details = $query->row_array();
			return $likes_details['likes_on_news'];
		}else{
			return 0;
		}
	}

	public function getTotalLikesDislikesOnComments($comment_id=0)
	{
		$sql = 'SELECT count(likes_dislikes_id) as total_likes_dislikes, getTotalLikesDislikesOnComments(ldc.comment_id,"LIKED") as total_likes,  getTotalLikesDislikesOnComments(ldc.comment_id,"DISLIKED") as total_dislikes FROM likes_dislikes_on_comments ldc where 1 AND ldc.delete_status = "0" AND ldc.status = "1" AND comment_id = "'.$comment_id.'" ';
		// 
		//echo $sql;die;
		$query = $this->db->query($sql);
		//pr($query->num_rows(),1);
		$final_response = array();
		if($query->num_rows()>0)
		{
			$likes_dislikes_details = $query->row_array();
			return $likes_dislikes_details;
		}else{
			$likes_dislikes_details = array();
			$likes_dislikes_details['total_likes_dislikes'] = 0;
			$likes_dislikes_details['total_likes'] = 0;
			$likes_dislikes_details['total_dislikes'] = 0;
			return $likes_dislikes_details;
		}
	}
		
	/*public function getRoomDetail($user_id,$room_id){
		$this->db->select('ss.*,c.category_name,sd.room_drawing_id,sd.start_date,sd.end_date,getTotalSoldTickets(sd.room_drawing_id) as sold_tickets,getTotalPurchasedTicketsOfUser('.$user_id.',sd.room_drawing_id) as user_purchased_tickets');
		$this->db->from('rooms as ss');
		$this->db->join('categories as c',"c.category_id=ss.category_id",'left');
		$this->db->join('rooms_drawings as sd',"sd.room_id=ss.room_id and sd.start_date<='".date('Y-m-d')."' and sd.end_date>='".date('Y-m-d')."' ",'left');
		$this->db->where('ss.room_id',$room_id);
		$query=$this->db->get();
		//echo $this->db->last_query();die;
		if($query->num_rows()>0)
		{
			$room_details = $query->row_array();
			if($room_details['room_drawing_id'] && $room_details['room_drawing_id']!=""){
				//pr($room_details,1);
				$room_rules_and_conditions = $this->getRoomRulesAndconditions($room_id,$room_details['room_name'],$room_details['start_date'],$room_details['end_date']);
				/* $rule = "This is the Rooms for ".$room_details['room_name']." from ".date('d-M-Y',strtotime($room_details['start_date']))." to ".date('d-M-Y',strtotime($room_details['end_date']))." ";
				$final_terms_conditions = $rule.' '.$room_rules_and_conditions; */
				//pr($room_rules_and_conditions,1);
				/*$userResponse = array();
				$userResponse['room_drawing_id'] 		=	 ($room_details['room_drawing_id']===NULL)?'':$room_details['room_drawing_id'];
				$userResponse['room_id'] 				=	 ($room_details['room_id']===NULL)?'':$room_details['room_id'];
				$userResponse['is_purchase_allowed'] 		=	 ($room_details['is_purchase_allowed']===NULL)?'':$room_details['is_purchase_allowed'];
				$userResponse['per_ticket_tokens'] 			=	 ($room_details['per_ticket_tokens']===NULL)?'':$room_details['per_ticket_tokens'];
				$userResponse['direct_purchase_tokens'] 	=	 ($room_details['direct_purchase_tokens']===NULL)?'':$room_details['direct_purchase_tokens'];
				$userResponse['category_id'] 				=	 ($room_details['category_id']===NULL)?'':$room_details['category_id'];
				$userResponse['room_name'] 			=	 ($room_details['room_name']===NULL)?'':$room_details['room_name'];
				$userResponse['short_description'] 			=	 ($room_details['short_description']===NULL)?'':$room_details['short_description'];
				$userResponse['category_name'] 				=	 ($room_details['category_name']===NULL)?'':$room_details['category_name'];
				$userResponse['grand_loot_price_value'] 	=	 ($room_details['grand_loot_price_value']===NULL)?'':$room_details['grand_loot_price_value'];
				$userResponse['per_user_allowed_purchase'] 	=	 ($room_details['per_user_allowed_purchase']===NULL)?'':$room_details['per_user_allowed_purchase'];
				$userResponse['user_purchased_tickets'] 	=	 ($room_details['user_purchased_tickets']===NULL)?'':$room_details['user_purchased_tickets'];
				$userResponse['available_tickets'] 			=	 ($room_details['available_tickets']===NULL)?'':$room_details['available_tickets'];
				$userResponse['sold_tickets'] 				=	 ($room_details['sold_tickets']===NULL)?'':$room_details['sold_tickets'];
				$userResponse['secoundry_prize_tokens'] 	=	 ($room_details['secoundry_prize_tokens']===NULL)?'':$room_details['secoundry_prize_tokens'];
				$userResponse['secoundry_prize_value'] 		=	 ($room_details['secoundry_prize_value']===NULL)?'':$room_details['secoundry_prize_value'];
				$userResponse['affiliate_link'] 			=	 ($room_details['affiliate_link']===NULL)?'':$room_details['affiliate_link'];
				$userResponse['live_start_date'] 			=	 ($room_details['start_date']===NULL)?'':$room_details['start_date'];
				$userResponse['live_end_date'] 				=	 ($room_details['end_date']===NULL)?'':$room_details['end_date'];
				$userResponse['is_terms_conditions_visible']=	 ($room_details['is_terms_conditions_vidible']===NULL)?'':$room_details['is_terms_conditions_vidible'];
				
				$this->db->select('*');
				$this->db->from('rooms_drawings');
				$this->db->where('room_id',$room_id);
				$this->db->where('start_date >',$room_details['end_date']);
				$this->db->where('status','1');
				$this->db->where('delete_status','0');
				$this->db->order_by('start_date','ASC');
				$this->db->limit(1);
				$query2 = $this->db->get();
				//echo $this->db->last_query();die;
				if($query2->num_rows()>0){
					$result = $query2->row_array();
					$userResponse['is_next_drawing'] = "1";
					$userResponse['next_drawing_start_date'] = $result['start_date'];
					$userResponse['is_next_drawing_end_date'] = $result['end_date'];				
				}else{
					$userResponse['is_next_drawing'] = "0";
					$userResponse['next_drawing_start_date'] = "0000-00-00";
					$userResponse['is_next_drawing_end_date'] = "0000-00-00";		
				}
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$room_details['room_image']) && $room_details['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$room_details['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
				if($room_details['is_terms_conditions_vidible']=='1'){
					$userResponse['room_terms_conditions']=	 ($room_rules_and_conditions===NULL)?'':$room_rules_and_conditions;
				}else{
					$userResponse['room_terms_conditions']=	 "";
				}
				$response['status'] = 200; 
				$response['message'] = 'Rooms Details';
				$response['room_detail'] = $userResponse;
				$data = $response;
			}else{
				$response['status'] = 400; 
				$response['message'] = 'No drawings avalibale for this room!';
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Room data is not found!';
			$data = $response;
		}
		return $data;
	}*/
	
	public function getRoomRulesAndconditions($room_id,$room_name,$start_date,$end_date){
		$this->db->select('*');
		$this->db->from('cms_pages');
		$this->db->where('page_key','SWEEPSTAKES_RULES_CONDITIONS');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query=$this->db->get();
		$description = "";
		if($query->num_rows()>0){
			$result = $query->row_array();
			//pr($result,1);
			$this->db->select('*');
			$this->db->from('rooms_drawings');
			$this->db->where('room_id',$room_id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$this->db->order_by('created_on','ASC');
			$query2=$this->db->get();
			if($query2->num_rows()>0){
				$drawings = $query2->result_array();
				//pr($drawings,1);
				$i=1;
				$html = "<table border='1' width='100%' style='text-align:center;'>";
				$html.="<thead style='text-align:center;'><tr><th>Drawing Number</th><th>Start Date</th><th>End Date</th></tr></thead><tbody>";
				foreach($drawings as $drawing){
					$html.="<tr><td>".$i."</td><td>".date('d-M-Y',strtotime($drawing['start_date']))."</td><td>".date('d-M-Y',strtotime($drawing['end_date']))."</td></tr>";
					$i++;
				}
				$html.="</tbody></table>";
				//pr($html,1);
			}
			$start_date = date('d-M-Y',strtotime($start_date));
			$start_date_time_set = date('H:i:s',strtotime($start_date));
			$end_date = date('d-M-Y',strtotime($end_date));
			$after_8_days_end_date = date('d-M-Y',strtotime($end_date.'+8 days'));
			$after_1_month_end_date = date('d-M-Y',strtotime($end_date.'+1 month'));
			$end_date_time_set = date('H:i:s',strtotime($end_date));
			$privacy_policy_link = "<a href='http://development.w3ondemand.com/loot_champs/privacy-policy'>Privacy Pilicy</a>";
			$product_cost = "200";
			$total_entry_number = "0";
			
			$room_rules = $result['description'];
			//pr($room_rules,1);
			$company_name = "Elation LLC";
			$app_name = "My Match";
			$company_app_ss_id = $company_name." - ".$app_name." - ".$room_id."#";
			$healthy = ["&lt;COMPANY NAME - APP NAME - Unique ID#&gt;","&lt;SWEEPSTAKES ID&gt;", "&lt;PRODUCT NAME&gt;","&lt;DATE&gt;","&lt;END DATE&gt&lt;END DATE + 8&gt;","&lt;END DATE + 1 MONTH&gt;","&lt;START TIME EST&gt;","&lt;END TIME EST&gt;","&lt;SWEEPSTAKE_DRAWING_TABLE&gt;","&lt;PRIVACY POLICY LINK&gt;","&lt;PRODUCT COST&gt;","&lt;TOTAL ENTRY NUMBER&gt;"];
			$replace   = [$company_app_ss_id,$room_id,$room_name,$start_date,$end_date,$after_8_days_end_date,$after_1_month_end_date,$start_date_time_set,$end_date_time_set,$html,$privacy_policy_link,$product_cost,$total_entry_number];

			$description = str_replace($healthy, $replace, $room_rules);
			
			//pr($description,1);
			//$description = htmlentities($description);
		}
		return $description;
	}
	
	public function enterRoom($user_id,$room_id,$room_drawing_id,$number_of_tickets,$paid_tokens){
		$user_details = $this->getUserData($user_id);
		//pr($user_details);
		$this->db->select('ss.*,c.category_name,getTotalSoldTickets('.$room_drawing_id.') as sold_tickets,getTotalPurchasedTicketsOfUser('.$user_id.','.$room_drawing_id.') as user_purchased_tickets');
		$this->db->from('rooms as ss');
		$this->db->join('categories as c',"c.category_id=ss.category_id",'left');
		$this->db->join('rooms_drawings as sd',"sd.room_id=ss.room_id",'left');
		$this->db->where('ss.room_id',$room_id);
		$this->db->where('sd.room_drawing_id',$room_drawing_id);
		$query=$this->db->get();
		//echo $this->db->last_query();die;
		//pr($query->num_rows(),1);
		if($query->num_rows()>0){
			$result = $query->row_array();
			//pr($result,1);
			$totl_token_to_paid 		= $number_of_tickets*$result['per_ticket_tokens'];
			//pr($totl_token_to_paid,1);
			$tot_remaining_tickets 	= $result['available_tickets']-$result['sold_tickets'];
			$user_remaining_tickets = $result['available_tickets']-$result['user_purchased_tickets'];
			$total_user_allowed_tickets = $result['user_purchased_tickets']+$number_of_tickets;
			if($tot_remaining_tickets<=0){
				$response['status'] = 400; 
				$response['message'] = 'Sorry You cannot purchase...all tickets have been sold for this drawing !';
				$data = $response;
			}else if($paid_tokens!=$totl_token_to_paid){
				$response['status'] = 400; 
				$response['message'] = 'You have entered wrong tokens! ';
				$data = $response;
			}else if($user_remaining_tickets<$number_of_tickets){
				$response['status'] = 400; 
				$response['message'] = 'You are not allowed to purchase '.$number_of_tickets.' tickets...please select less than or equal to '.$user_remaining_tickets.' ';
				$data = $response;
			}else if($paid_tokens>$user_details['user_tokens']){
				$response['status'] = 400; 
				$response['message'] = 'Not Enough Tokens. Visit the Token Shop to Top Yourself Off!';
				$data = $response;
			}else if($result['per_user_allowed_purchase']<$total_user_allowed_tickets){
				$response['status'] = 400; 
				$response['message'] = '1 user can only purchase '.$result['per_user_allowed_purchase'].' tickets!';
				$data = $response;
			}else{
				//pr($user_remaining_tickets);
				//pr($result,1);
				for($i=1;$i<=$number_of_tickets;$i++){
					$ticket_details = array(
						'user_id'					=>	$user_id,
						'category_id'				=>	$result['category_id'],
						'sweepstack_id'				=>	$result['room_id'],
						'room_drawing_id'		=>	$room_drawing_id,
						'type'						=>	'TOKENS',
						'paid_tokens'				=>	$paid_tokens,
						'room_name'			=>	$result['room_name'],
						'room_image'			=>	$result['room_image'],
						'short_description'			=>	$result['short_description'],
						'grand_loot_price_value'	=>	$result['grand_loot_price_value'],
						'per_ticket_tokens'			=>	$result['per_ticket_tokens'],
						'secoundry_prize_tokens'	=>	$result['secoundry_prize_tokens'],
						'secoundry_prize_value'		=>	$result['secoundry_prize_value'],
						'terms_condition'			=>	$result['terms_condition'],
						'created_on'				=>	date('Y-m-d H:i:s'),
					);
					//pr($ticket_details,1);
					$saveData = $this->db->insert('tickets_purchased',$ticket_details);
					if($saveData){
						$remaining_tickets = $this->getRemainingTickets($room_id,$room_drawing_id);
						//pr($remaining_tickets);
						if($remaining_tickets==0){
							$winner_response = $this->getRandWinner($room_drawing_id);
							if(!empty($winner_response) && $winner_response['user_id']!='')
							{
								$winner_user_id = $winner_response['user_id'];
								$ticket_purchase_id = $winner_response['tickets_purchased_id'];
								//pr($winner_response,1);
								//echo $winner_user_id; die;
								$insertArr = array();
								$insertArr['user_id'] = $winner_user_id;
								$insertArr['ticket_purchase_id'] = $ticket_purchase_id;
								$insertArr['room_drawing_id'] = $room_drawing_id;
								$insertArr['room_id'] = $room_id;
								$insertArr['category_id'] = $result['category_id'];
								$insertArr['room_name'] = $result['room_name'];
								$insertArr['room_image'] = $result['room_image'];
								$insertArr['winner_grand_loot_price_value'] = $result['grand_loot_price_value'];
								//$insertArr['room_winner_id'] = $winner_user_id;
								$insertArr['created_on'] = date('Y-m-d H:i:s');
								$insertArr['modified_on'] = date('Y-m-d H:i:s');
								$result = $this->db->insert('room_winners',$insertArr);
								if($result){
									$noti_response 	= $this->sendPushNotificationOnDrawingComplete($room_drawing_id);
									$result 	= $this->db->update('rooms_drawings',array('is_drawing_complete'=>'1','modified_on'=>date('Y-m-d H:i:s')),array('room_drawing_id'=>$room_drawing_id));
								
									$noti_details = array();
									$noti_details['room_id']=$room_id;
									$noti_details['room_drawing_id']=$room_drawing_id;
									$noti_details['winner_id']=$winner_user_id;
									$noti_details['user_id']=$winner_user_id;
									$noti_details['action']='WINNER_ANNOUNCED';
									$noti_details['description']='';
									$noti_details['created_on']=date('Y-m-d H:i:s');
									
									$irs_required = getTotalWinningPrize($winner_user_id);
									//pr($irs_required,1);
									if($irs_required){
										$details = array();
										$details['room_id']=$room_id;
										$details['room_drawing_id']=$room_drawing_id;
										$details['winner_id']=$winner_user_id;
										$details['user_id']=$winner_user_id;
										$details['action']='IRS_REQUIRED';
										$details['description']='';
										$details['created_on']=date('Y-m-d H:i:s');
										$saveDetails 	= $this->db->insert('admin_notifications',$details);
										
										$title   = "Fill IRS Form";
										$message = "Please fill out the IRS form we emailed you so we can send you your Grand Prize.";
										$type 	 = 'IRS_REQUIRED';
										$result  = sendApnsPushNotification($winner_user_id,$message,$title,$type);
									}
									$noti_saveData 	= $this->db->insert('admin_notifications',$noti_details);
								}	
							}
						}
					}
				}
				$remaining_user_tokens = $user_details['user_tokens']-$totl_token_to_paid;
				$save_detail = array('user_tokens'=>$remaining_user_tokens,'modify'=>date('Y-m-d H:i:s'));
				$result 	= $this->db->update('tbl_usermaster',$save_detail,array('id'=>$user_id));
				$updated_user_details = $this->getUserData($user_id);
				
				$response['status'] 				= 200; 
				$response['message'] 				= 'Entry Successful!';
				$response['user_tokens'] 			= $updated_user_details['user_tokens'];
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Room data is not found!';
			$data = $response;
		}
		return $data;
	}
	
	public function buy_direct_room_tickets($user_id,$room_id,$room_drawing_id,$quantity,$paid_tokens,$firstname,$lastname,$phone_number,$address,$address2,$zip_code,$city,$state){
		$user_details = $this->getUserData($user_id);
		//pr($user_details);
		$this->db->select('ss.*,c.category_name,getTotalSoldTickets('.$room_drawing_id.') as sold_tickets,getTotalPurchasedTicketsOfUser('.$user_id.','.$room_drawing_id.') as user_purchased_tickets');
		$this->db->from('rooms as ss');
		$this->db->join('categories as c',"c.category_id=ss.category_id",'left');
		$this->db->where('ss.room_id',$room_id);
		$query=$this->db->get();
		
		$this->db->select('ss.*');
		$this->db->from('rooms as ss');
		$this->db->where('ss.room_id',$room_id);
		$this->db->where('ss.is_purchase_allowed','1');
		$directPurchaseQuery=$this->db->get();
				
		//echo $this->db->last_query();die;
		if($query->num_rows()>0){
			if($directPurchaseQuery->num_rows()>0)
			{
				$result = $query->row_array();
				//pr($result,1);
				$totl_token_to_paid 		= $quantity*$result['direct_purchase_tokens'];
				$user_remaining_tickets 	= $result['available_tickets']-$result['user_purchased_tickets'];
				$total_user_allowed_tickets = $result['user_purchased_tickets']+$quantity;
				/* if($user_remaining_tickets<$quantity){
					$response['status'] = 400; 
					$response['message'] = 'You are not allowed to purchase '.$quantity.' tickets...please select less than or equal to '.$user_remaining_tickets.' ';
					$data = $response;
				}else  */if($paid_tokens>$user_details['user_tokens']){
					$response['status'] = 400; 
					$response['message'] = 'Not Enough Tokens. Visit the Token Shop to Top Yourself Off!!';
					$data = $response;
				}else if($totl_token_to_paid!=$paid_tokens){
					$response['status'] = 400; 
					$response['message'] = 'Not Enough Tokens. Visit the Token Shop to Top Yourself Off!';
					$data = $response;
				}/* else if($result['per_user_allowed_purchase']<$total_user_allowed_tickets){
					$response['status'] = 400; 
					$response['message'] = '1 user can only purchase '.$result['per_user_allowed_purchase'].' tickets!';
					$data = $response;
				} */else{
					//pr($user_remaining_tickets);
					//pr($result,1);
					$ticket_details = array(
						'user_id'					=>	$user_id,
						'category_id'				=>	$result['category_id'],
						'sweepstack_id'				=>	$result['room_id'],
						'room_drawing_id'		=>	$room_drawing_id,
						'type'						=>	'DIRECT',
						'direct_purchase_quantity'	=>	$quantity,
						'paid_tokens'				=>	$paid_tokens,
						'room_name'			=>	$result['room_name'],
						'room_image'			=>	$result['room_image'],
						'short_description'			=>	$result['short_description'],
						'grand_loot_price_value'	=>	$result['grand_loot_price_value'],
						'per_ticket_tokens'			=>	$result['direct_purchase_tokens'],
						'secoundry_prize_tokens'	=>	$result['secoundry_prize_tokens'],
						'secoundry_prize_value'		=>	$result['secoundry_prize_value'],
						'terms_condition'			=>	$result['terms_condition'],
						'user_first_name' 			=>	$firstname,
						'user_last_name' 			=>	$lastname,
						'user_phone_no' 			=>	$phone_number,
						'user_address' 				=>	$address,
						'user_address2' 			=>	$address2,
						'user_zip_code' 			=>	$zip_code,
						'user_city' 				=>	$city,
						'user_state' 				=>	$state,
						'user_is_address_confirm'	=>	'1',
						'created_on'				=>	date('Y-m-d H:i:s'),
					);
					//pr($ticket_details,1);
					$saveData = $this->db->insert('tickets_purchased',$ticket_details);
					$tickets_purchased_id = $this->db->insert_id();
					
					$remaining_user_tokens = $user_details['user_tokens']-$paid_tokens;
					$save_detail = array('user_tokens'=>$remaining_user_tokens,'modify'=>date('Y-m-d H:i:s'));
					$result 	= $this->db->update('tbl_usermaster',$save_detail,array('id'=>$user_id));
					
					$response['status'] 				= 200; 
					$response['message'] 				= 'You have been buy tickets successfully!';
					$response['user_tokens'] 			= $remaining_user_tokens;
					$data = $response;
				}
			}else{
				$response['status'] = 400; 
				$response['message'] = 'No Direct Purchase allowed for this room!';
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Room data is not found!';
			$data = $response;
		}
		return $data;
	}
	
	public function getRemainingTickets($room_id,$room_drawing_id){
		$this->db->select('ss.*,getTotalSoldTickets('.$room_drawing_id.') as sold_tickets');
		$this->db->from('rooms as ss');
		$this->db->where('ss.room_id',$room_id);
		$sold_query=$this->db->get();
		
		$remaining_tickets = 0;
		if($sold_query->num_rows()>0){
			$data = $sold_query->row_array();
			$remaining_tickets = $data['available_tickets']-$data['sold_tickets'];
		}
		return $remaining_tickets;
	}
	
	public function sendPushNotificationOnDrawingComplete($room_drawing_id){
		$this->db->select('*');
		$this->db->from('tickets_purchased');
		$this->db->where('room_drawing_id',$room_drawing_id);
		$this->db->where('type','TOKENS');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->group_by('user_id');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$result = $query->result_array();
			foreach($result as $record){
				$user_id = $record['user_id'];
				$title = "Winner Announced";
				$message = "The room for '".$record['grand_loot_price_value']."' is over. Check if you won the grand prize!";
				$type = 'WINNER_ANNOUNCED';
				$result  = sendApnsPushNotification($user_id,$message,$title,$type);
			}
		}
		return true;
	}
	
	public function getRandWinner($room_drawing_id='')
	{
		$result = array();
		$user_id = '';
		$this->db->select('user_id,tickets_purchased_id');
		$this->db->from('tickets_purchased');
		$this->db->where('room_drawing_id',$room_drawing_id);
		$this->db->where('type','TOKENS');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->order_by('RAND()');
		$this->db->limit('1');
		$response = $this->db->get();
		//echo $this->db->last_query();die;
		if($response->num_rows()>0)
		{
			$result = $response->row_array();
			$tickets_purchased_id = $result['tickets_purchased_id'];
			$user_id = $result['user_id'];
		}
		return $result;
	}
	
	public function buy_store_product($user_id,$store_room_id,$quantity,$paid_tokens,$firstname,$lastname,$phone_number,$address,$address2,$zip_code,$city,$state){
		$user_details = $this->getUserData($user_id);
		//pr($user_details);
		$this->db->select('sp.*');
		$this->db->from('store_products as sp');
		$this->db->where('sp.store_room_id',$store_room_id);
		$this->db->where('sp.status','1');
		$this->db->where('sp.delete_status','0');
		$query=$this->db->get();
		if($query->num_rows()>0){
			$result = $query->row_array();
			//pr($result,1);
			$tot_paid_tokens = $quantity*$result['tokens'];
			if($paid_tokens>$user_details['user_tokens']){
				$response['status'] = 400; 
				$response['message'] = 'You have insufficient tokens to purchase this store product!';
				$data = $response;
			}else if($tot_paid_tokens!=$paid_tokens){
				$response['status'] = 400; 
				$response['message'] = 'Not Enough Tokens. Visit the Token Shop to Top Yourself Off!';
				$data = $response;
			}else{
				//pr($user_remaining_tickets);
				//pr($result,1);
				$product_details = array(
					'user_id'					=>	$user_id,
					'store_room_id'		=>	$result['store_room_id'],
					'tokens'					=>	$paid_tokens,
					'product_title'				=>	$result['product_title'],
					'product_image'				=>	$result['product_image'],
					'product_description'		=>	$result['product_description'],
					'first_name' 				=>	$firstname,
					'last_name' 				=>	$lastname,
					'phone_no' 					=>	$phone_number,
					'address' 					=>	$address,
					'address2' 					=>	$address2,
					'zip_code' 					=>	$zip_code,
					'city' 						=>	$city,
					'state' 					=>	$state,
					'is_address_confirm'=>	'1',
					'created_on'				=>	date('Y-m-d H:i:s'),
				);
				//pr($product_details,1);
				$saveData = $this->db->insert('store_token_purchases',$product_details);
				$store_token_purchase_id = $this->db->insert_id();
				
				$remaining_user_tokens = $user_details['user_tokens']-$paid_tokens;
				$save_detail = array('user_tokens'=>$remaining_user_tokens,'modify'=>date('Y-m-d H:i:s'));
				$result 	= $this->db->update('tbl_usermaster',$save_detail,array('id'=>$user_id));
				
				$response['status'] 					= 200; 
				$response['message'] 					= 'Product Successfully Ordered. We will send you a tracking number to your registered Email.';
				$response['user_tokens'] 				= $remaining_user_tokens;
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Store Product data is not found!';
			$data = $response;
		}
		return $data;
	}
	
	public function yourLoot($user_id){
		//pr($user_id,1);
		$user_details = $this->getUserData($user_id);
		//pr($user_details,1);
		$this->db->select('tp.*,sw.room_winner_id,sw.is_address_confirm,c.category_name');
		$this->db->from('tickets_purchased tp');
		$this->db->join('room_winners sw','tp.user_id = sw.user_id AND sw.ticket_purchase_id=tp.tickets_purchased_id AND tp.room_drawing_id = sw.room_drawing_id','left');
		$this->db->join('categories c','c.category_id = tp.category_id','left');
		$this->db->join('rooms_drawings sd','sd.room_drawing_id = tp.room_drawing_id AND is_drawing_complete="1"','left');
		$this->db->where('sd.is_drawing_complete','1');
		$this->db->where('tp.user_id',$user_id);
		$this->db->where('tp.type','TOKENS');
		$this->db->where('tp.status','1');
		$this->db->where('tp.delete_status','0');
		$this->db->order_by('sw.created_on','DESC');
		$query2 = $this->db->get();
		//echo $this->db->last_query();die;
		$finalArr = array();
		if($query2->num_rows()>0){
			$result = $query2->result_array();
			$total_grand_loot = 0;
			//pr($result,1);
			foreach($result as $record){
				$userResponse = array();
				$userResponse['tickets_purchased_id'] 		=	 ($record['tickets_purchased_id']===NULL)?'':$record['tickets_purchased_id'];
				$userResponse['user_id'] 					=	 ($record['user_id']===NULL)?'':$record['user_id'];
				$userResponse['room_drawing_id'] 		=	 ($record['room_drawing_id']===NULL)?'':$record['room_drawing_id'];
				$userResponse['sweepstack_id'] 				=	 ($record['sweepstack_id']===NULL)?'':$record['sweepstack_id'];
				$userResponse['category_id'] 				=	 ($record['category_id']===NULL)?'':$record['category_id'];
				$userResponse['category_name'] 				=	 ($record['category_name']===NULL)?'':$record['category_name'];
				$userResponse['room_name'] 			=	 ($record['room_name']===NULL)?'':$record['room_name'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$record['room_image']) && $record['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$record['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
				$userResponse['per_ticket_tokens'] 			=	 ($record['per_ticket_tokens']===NULL)?'':$record['per_ticket_tokens'];
				$userResponse['grand_loot_price_value'] 	=	 ($record['grand_loot_price_value']===NULL)?'':$record['grand_loot_price_value'];
				$userResponse['secoundry_prize_tokens'] 	=	 ($record['secoundry_prize_tokens']===NULL)?'':$record['secoundry_prize_tokens'];
				$userResponse['secoundry_prize_value'] 		=	 ($record['secoundry_prize_value']===NULL)?'':$record['secoundry_prize_value'];
				$userResponse['type'] 						=	 ($record['type']===NULL)?'':$record['type'];
				$userResponse['is_opened'] 					=	 ($record['is_opened']===NULL)?'':$record['is_opened'];
				$userResponse['room_winner_id'] 		=	 ($record['room_winner_id']===NULL)?'':$record['room_winner_id'];
				if($record['room_winner_id'] && $record['room_winner_id']!=null){
					$userResponse['is_winner'] 	=	 "1";
					$irs_required = getTotalWinningPrize($user_id);
					//$total_grand_loot+= $record['grand_loot_price_value'];
					
					if($record['is_opened']=='1'){
						$userResponse['is_open_allowed'] =  '1';
					}else if($irs_required) {
						$userResponse['is_open_allowed'] =  '0';
					}else {
						$userResponse['is_open_allowed'] =  '1';
					}
				}else{
					$userResponse['is_winner'] 	=	 "0";
					$userResponse['is_open_allowed'] =  '1';
				}
				if($userResponse['is_winner']=='1' && $record['is_address_confirm']=='0'){
					$userResponse['is_address_required'] =  '1';
				}else{
					$userResponse['is_address_required'] =  '0';
				}
				$finalArr[] = $userResponse;
			}
			$response['status'] = 200; 
			$response['message'] = 'Your loot box!';
			$response['your_loot'] = $finalArr;
			$data = $response;
		}else{
			$response['status'] = 200; 
			$response['message'] = 'Your loot box is empty!';
			$response['your_loot'] = $finalArr;
			$data = $response;
		}		
		return $data;
	}
	
	public function openLootBox($user_id,$tickets_purchased_id){
		$user_details = $this->getUserData($user_id);
				
		$this->db->select('*');
		$this->db->from('tickets_purchased');
		$this->db->where('tickets_purchased_id',$tickets_purchased_id);
		$this->db->where('is_opened','0');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		
		if($query->num_rows()>0){
			$result = true;
			$detail = array('is_opened'=>'1','opened_on'=>date('Y-m-d H:i:s'));
			$result = $this->db->update('tickets_purchased',$detail,array('tickets_purchased_id'=>$tickets_purchased_id));
			if($result){
				$this->db->select('tp.*,sw.room_winner_id,sw.is_address_confirm,c.category_name');
				$this->db->from('tickets_purchased tp');
				$this->db->where('tickets_purchased_id',$tickets_purchased_id);
				$this->db->join('room_winners sw','tp.user_id = sw.user_id AND sw.ticket_purchase_id=tp.tickets_purchased_id AND tp.room_drawing_id = sw.room_drawing_id','left');
				$this->db->join('categories c','c.category_id = tp.category_id','left');
				$this->db->where('tp.status','1');
				$this->db->where('tp.delete_status','0');
				$query2 = $this->db->get();
				$details = $query2->row_array();
				//pr($details,1);
				if($details['room_winner_id']==""){
					//echo "hello";die;
					/* $userData = $this->getUserData($user_id);
					$user_token = array('user_tokens'=>$userData['user_tokens']+$details['secoundry_prize_tokens'],'modify'=>date('Y-m-d H:i:s'));
					//pr($user_token,1);
					$result = $this->db->update('tbl_usermaster',$user_token,array('id'=>$user_id)); */
					
					$token_detail = array(
						'user_id' => $user_id,
						'tokens' => $details['secoundry_prize_tokens'],
						'token_amount' => $details['secoundry_prize_value'],
						'type' => 'SECONDARY_REWARD',
						'is_claim_allowed' => '1',
						'created_on' => date('Y-m-d H:i:s'),
					);
					$token_purchase = $this->db->insert('token_purchases',$token_detail);
				}
				//pr($details,1);
				$userResponse = array();
				$userResponse['tickets_purchased_id'] 		=	 ($details['tickets_purchased_id']===NULL)?'':$details['tickets_purchased_id'];
				$userResponse['user_id'] 					=	 ($details['user_id']===NULL)?'':$details['user_id'];
				$userResponse['room_drawing_id'] 		=	 ($details['room_drawing_id']===NULL)?'':$details['room_drawing_id'];
				$userResponse['sweepstack_id'] 				=	 ($details['sweepstack_id']===NULL)?'':$details['sweepstack_id'];
				$userResponse['category_id'] 				=	 ($details['category_id']===NULL)?'':$details['category_id'];
				$userResponse['category_name'] 				=	 ($details['category_name']===NULL)?'':$details['category_name'];
				$userResponse['room_name'] 			=	 ($details['room_name']===NULL)?'':$details['room_name'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$details['room_image']) && $details['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$details['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
				$userResponse['per_ticket_tokens'] 			=	 ($details['per_ticket_tokens']===NULL)?'':$details['per_ticket_tokens'];
				$userResponse['grand_loot_price_value'] 	=	 ($details['grand_loot_price_value']===NULL)?'':$details['grand_loot_price_value'];
				$userResponse['secoundry_prize_tokens'] 	=	 ($details['secoundry_prize_tokens']===NULL)?'':$details['secoundry_prize_tokens'];
				$userResponse['secoundry_prize_value'] 		=	 ($details['secoundry_prize_value']===NULL)?'':$details['secoundry_prize_value'];
				$userResponse['type'] 						=	 ($details['type']===NULL)?'':$details['type'];
				$userResponse['is_opened'] 					=	 ($details['is_opened']===NULL)?'':$details['is_opened'];
				$userResponse['room_winner_id'] 		=	 ($details['room_winner_id']===NULL)?'':$details['room_winner_id'];
				if($details['room_winner_id'] && $details['room_winner_id']!=null){
					$userResponse['is_winner'] 	=	 "1";
					//$total_grand_loot+= $details['grand_loot_price_value'];
					$irs_required = getTotalWinningPrize($user_id);
					if($irs_required){
						$userResponse['is_open_allowed'] =  '0';
					}else{
						$userResponse['is_open_allowed'] =  '1';
					}					
					$userResponse['is_winner'] 	=	 "1";
					$userResponse['is_address_confirm'] 	=	 ($details['is_address_confirm']===NULL)?'':$details['is_address_confirm'];
				}else{
					$userResponse['is_winner'] 	=	 "0";
					$userResponse['is_address_confirm'] 	=	 "";
				}
				$response['status']  = 200; 
				$response['message'] = 'Your loot box open successfully!';
				$response['details'] = $userResponse;
				$data = $response;
			}else{
				$response['status'] = 400; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = 'This loot box already opened!';
			$data = $response;
		}
		return $data;
	}
	
	
	/* address confirm for winners and direct purchase  */
	public function addressConfirm($user_id,$type,$id,$firstname,$lastname,$phone_number,$address,$address2,$zip_code,$city,$state){
		if($type=="WINNER")
		{
			$this->db->select('*');
			$this->db->from('room_winners');
			$this->db->where('room_winner_id',$id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			if($query->num_rows()>0){
				$detail = array(
					'first_name' 		=>	$firstname,
					'last_name' 		=>	$lastname,
					'phone_no' 			=>	$phone_number,
					'address' 			=>	$address,
					'address2' 			=>	$address2,
					'zip_code' 			=>	$zip_code,
					'city' 				=>	$city,
					'state' 			=>	$state,
					'is_address_confirm'=>	'1',
					'modified_on' 		=>	date('Y-m-d H:i:s')
				);
				$result = $this->db->update('room_winners',$detail,array('room_winner_id'=>$id));
				if($result){
					$this->db->select('*');
					$this->db->from('room_winners');
					$this->db->where('room_winner_id',$id);
					$this->db->where('status','1');
					$this->db->where('delete_status','0');
					$query2 = $this->db->get();
					$winnersData = $query2->row_array();
					$user_response = array();
					$user_response['first_name'] =($winnersData['first_name']===NULL)?'':$winnersData['first_name'];
					$user_response['last_name'] =($winnersData['last_name']===NULL)?'':$winnersData['last_name'];
					$user_response['phone_no'] =($winnersData['phone_no']===NULL)?'':$winnersData['phone_no'];
					$user_response['address'] =($winnersData['address']===NULL)?'':$winnersData['address'];
					$user_response['address2'] =($winnersData['address2']===NULL)?'':$winnersData['address2'];
					$user_response['zip_code'] =($winnersData['zip_code']===NULL)?'':$winnersData['zip_code'];
					$user_response['city'] =($winnersData['city']===NULL)?'':$winnersData['city'];
					$user_response['state'] =($winnersData['state']===NULL)?'':$winnersData['state'];
					$user_response['is_address_confirm'] =($winnersData['is_address_confirm']===NULL)?'':$winnersData['is_address_confirm'];
					$response['status'] = 200; 
					$response['message'] = 'Your address has been updated successfully!';
					$response['user_id'] = $user_id;
					$response['address_details'] = $user_response;
					$data = $response;
				}else{
					$response['status'] = 400; 
					$response['message'] = $this->lang->line('something_went_wrong');
					$data = $response;
				}
			}else{
				$response['status'] = 400; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}else if($type=="DIRECT"){
			$this->db->select('*');
			$this->db->from('tickets_purchased');
			$this->db->where('tickets_purchased_id',$id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			if($query->num_rows()>0){
				$detail = array(
					'user_first_name' 	=>	$firstname,
					'user_last_name' 	=>	$lastname,
					'user_phone_no' 	=>	$phone_number,
					'user_address' 		=>	$address,
					'user_address2' 	=>	$address2,
					'user_zip_code' 	=>	$zip_code,
					'user_city' 		=>	$city,
					'user_state' 		=>	$state,
					'user_is_address_confirm'=>'1',
					'modified_on' 		=>	date('Y-m-d H:i:s')
				);
				$result = $this->db->update('tickets_purchased',$detail,array('tickets_purchased_id'=>$id));
				$this->db->select('*');
				$this->db->from('tickets_purchased');
				$this->db->where('tickets_purchased_id',$id);
				$this->db->where('status','1');
				$this->db->where('delete_status','0');
				$query2 = $this->db->get();
				$ticketsData = $query2->row_array();
				$user_response = array();
				$user_response['first_name'] =($ticketsData['user_first_name']===NULL)?'':$ticketsData['user_first_name'];
				$user_response['last_name'] =($ticketsData['user_last_name']===NULL)?'':$ticketsData['user_last_name'];
				$user_response['phone_no'] =($ticketsData['user_phone_no']===NULL)?'':$ticketsData['user_phone_no'];
				$user_response['address'] =($ticketsData['user_address']===NULL)?'':$ticketsData['user_address'];
				$user_response['address2'] =($ticketsData['user_address2']===NULL)?'':$ticketsData['user_address2'];
				$user_response['zip_code'] =($ticketsData['user_zip_code']===NULL)?'':$ticketsData['user_zip_code'];
				$user_response['city'] =($ticketsData['user_city']===NULL)?'':$ticketsData['user_city'];
				$user_response['state'] =($ticketsData['user_state']===NULL)?'':$ticketsData['user_state'];
				$user_response['is_address_confirm'] =($ticketsData['user_is_address_confirm']===NULL)?'':$ticketsData['user_is_address_confirm'];
				if($result){
					$response['status'] = 200; 
					$response['message'] = 'Your address has been updated successfully!';
					$response['user_id'] = $user_id;
					$response['address_details'] = $user_response;
					$data = $response;
				}else{
					$response['status'] = 400; 
					$response['message'] = $this->lang->line('something_went_wrong');
					$data = $response;
				}
			}else{
				$response['status'] = 400; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}else if($type=="STORE"){
			$this->db->select('*');
			$this->db->from('store_token_purchases');
			$this->db->where('store_token_purchase_id',$id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			if($query->num_rows()>0){
				$detail = array(
					'first_name' 		=>	$firstname,
					'last_name' 		=>	$lastname,
					'phone_no' 			=>	$phone_number,
					'address' 			=>	$address,
					'address2' 			=>	$address2,
					'zip_code' 			=>	$zip_code,
					'city' 				=>	$city,
					'state' 			=>	$state,
					'is_address_confirm'=>	'1',
					'modified_on' 		=>	date('Y-m-d H:i:s')
				);
				$result = $this->db->update('store_token_purchases',$detail,array('store_token_purchase_id'=>$id));
				if($result){
					$this->db->select('*');
					$this->db->from('store_token_purchases');
					$this->db->where('store_token_purchase_id',$id);
					$this->db->where('status','1');
					$this->db->where('delete_status','0');
					$query2 = $this->db->get();
					$storePurchaseData = $query2->row_array();
					$user_response = array();
					$user_response['first_name'] =($storePurchaseData['first_name']===NULL)?'':$storePurchaseData['first_name'];
					$user_response['last_name'] =($storePurchaseData['last_name']===NULL)?'':$storePurchaseData['last_name'];
					$user_response['phone_no'] =($storePurchaseData['phone_no']===NULL)?'':$storePurchaseData['phone_no'];
					$user_response['address'] =($storePurchaseData['address']===NULL)?'':$storePurchaseData['address'];
					$user_response['address2'] =($storePurchaseData['address2']===NULL)?'':$storePurchaseData['address2'];
					$user_response['zip_code'] =($storePurchaseData['zip_code']===NULL)?'':$storePurchaseData['zip_code'];
					$user_response['city'] =($storePurchaseData['city']===NULL)?'':$storePurchaseData['city'];
					$user_response['state'] =($storePurchaseData['state']===NULL)?'':$storePurchaseData['state'];
					$user_response['is_address_confirm'] =($storePurchaseData['is_address_confirm']===NULL)?'':$storePurchaseData['is_address_confirm'];
					
					$response['status'] 	= 200; 
					$response['message'] 	= 'Your address has been updated successfully!';
					$response['user_id'] 	= $user_id;
					$response['address_details'] = $user_response;
					$data = $response;
				}else{
					$response['status'] = 400; 
					$response['message'] = $this->lang->line('something_went_wrong');
					$data = $response;
				}
			}else{
				$response['status'] = 400; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}
			return $data;
	}
	
	public function unboxHistory($user_id){
		$result['You'] = $this->getOpenedLoot($user_id);
		$result['USA'] = $this->getOpenedLoot();
		//pr($result,1);
		$response['status'] = 200; 
		$response['message'] = 'Your unbox history!';
		$response['unbox_history'] = $result;
		$data = $response;
		return $data;
	}
	
	public function getOpenedLoot($user_id=null){
		$this->db->select('tp.*,sw.room_winner_id,c.category_name,sd.is_drawing_complete');
		$this->db->from('tickets_purchased tp');
		$this->db->join('room_winners sw','tp.user_id = sw.user_id AND sw.ticket_purchase_id=tp.tickets_purchased_id AND tp.room_drawing_id = sw.room_drawing_id','left');
		$this->db->join('categories c','c.category_id = tp.category_id','left');
		$this->db->join('rooms_drawings sd','sd.room_drawing_id = tp.room_drawing_id AND is_drawing_complete="1"','left');
		$this->db->where('sd.is_drawing_complete','1');
		if($user_id && $user_id!="" && $user_id!=0){
			$this->db->where('tp.user_id',$user_id);
		}
		$this->db->where('tp.type','TOKENS');
		$this->db->where('tp.is_opened','1');
		$this->db->where('tp.status','1');
		$this->db->where('tp.delete_status','0');
		$query2 = $this->db->get();
		//echo $this->db->last_query();die; 
		$finalArr = array();
		if($query2->num_rows()>0){
			$result = $query2->result_array();
			//pr($result,1);
			foreach($result as $record){
				$userResponse = array();
				$userResponse['tickets_purchased_id'] 		=	 ($record['tickets_purchased_id']===NULL)?'':$record['tickets_purchased_id'];
				$userResponse['user_id'] 					=	 ($record['user_id']===NULL)?'':$record['user_id'];
				$userResponse['room_drawing_id'] 		=	 ($record['room_drawing_id']===NULL)?'':$record['room_drawing_id'];
				$userResponse['sweepstack_id'] 				=	 ($record['sweepstack_id']===NULL)?'':$record['sweepstack_id'];
				$userResponse['category_id'] 				=	 ($record['category_id']===NULL)?'':$record['category_id'];
				$userResponse['category_name'] 				=	 ($record['category_name']===NULL)?'':$record['category_name'];
				$userResponse['opened_on'] 					=	 ($record['opened_on']===NULL)?'':$record['opened_on'];
				$userResponse['is_opened'] 					=	 ($record['is_opened']===NULL)?'':$record['is_opened'];
				if($record['room_winner_id'] && $record['room_winner_id']!=null){
					$userResponse['is_winner'] 	=	 "1";
					$userResponse['your_prize'] =	 ($record['room_name']===NULL)?'':$record['room_name'];
					$userResponse['top_prize'] =	 ($record['room_name']===NULL)?'':$record['room_name'];
				}else{
					$userResponse['is_winner'] 	=	 "0";
					$userResponse['your_prize'] =	 ($record['secoundry_prize_tokens']===NULL)?'':$record['secoundry_prize_tokens'];
					$userResponse['top_prize'] =	 ($record['room_name']===NULL)?'':$record['room_name'];
				}
				$finalArr[] = $userResponse;
			}
		}
		return $finalArr;
	}
	
	public function tokenShop($user_id){
		//pr($user_id,1);
		$today = date('Y-m-d');
		$this->db->select('*');
		$this->db->from('token_amounts');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->order_by('tokens','ASC');
		$query = $this->db->get();
		
		$user_response['buy'] = array();
		$user_response['subscribe'] = array();
		$user_response['earn'] = array();
		
		$not_types = array('SIGNUP_REWARD','REFER_EARN','PROMOCODE');
		$this->db->select('*');
		$this->db->from('token_purchases');
		$this->db->where('user_id',$user_id);
		/* $this->db->where("(CASE WHEN type = 'DAILY_REWARD' THEN created_on LIKE '%".$today."%' END)"); */
		$this->db->where_not_in('type',$not_types);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->order_by('created_on','DESC');
		$query2 = $this->db->get();
		//echo $this->db->last_query();die;
		if($query->num_rows()>0){
			$result = $query->result_array();
			//pr($result,1);
			foreach($result as $row){
				if($row['type'] == "TOKEN"){
					$token_response = array();
					$token_response['token_amount_id'] 		=	 ($row['token_amount_id']===NULL)?'':$row['token_amount_id'];
					$token_response['type'] 				=	 ($row['type']===NULL)?'':$row['type'];
					$token_response['tokens'] 				=	 ($row['tokens']===NULL)?'':$row['tokens'];
					$token_response['token_amount'] 		=	 ($row['token_amount']===NULL)?'':$row['token_amount'];
					$token_response['image'] 				=	 ($row['image']===NULL)?'':$row['image'];
					$token_response['is_special_offer'] 	=	 ($row['is_special_offer']===NULL)?'':$row['is_special_offer'];
					$token_response['bonus_percentage'] 	=	 ($row['bonus_percentage']===NULL)?'':$row['bonus_percentage'];
					if($row['image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/tokens/'.$row['image'])){
						$token_response['image'] = UPLOAD_URL.'tokens/'.$row['image'].'';
					}else{
						$token_response['image'] = UPLOAD_URL.'tokens/default_token.png';
					}
					$user_response['buy'][]		 =	$token_response;
				}else if($row['type'] == "SUBSCRIPTION"){
					$subscribe_response = array();
					$subscribe_response['token_amount_id'] 		=	 ($row['token_amount_id']===NULL)?'':$row['token_amount_id'];
					$subscribe_response['type'] 				=	 ($row['type']===NULL)?'':$row['type'];
					$subscribe_response['tokens'] 				=	 ($row['tokens']===NULL)?'':$row['tokens'];
					$subscribe_response['token_amount'] 		=	 ($row['token_amount']===NULL)?'':$row['token_amount'];
					$subscribe_response['is_special_offer'] 	=	 ($row['is_special_offer']===NULL)?'':$row['is_special_offer'];
					$subscribe_response['bonus_percentage'] 	=	 ($row['bonus_percentage']===NULL)?'':$row['bonus_percentage'];
					if($row['image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/tokens/'.$row['image'])){
						$subscribe_response['image'] = UPLOAD_URL.'tokens/'.$row['image'].'';
					}else{
						$subscribe_response['image'] = UPLOAD_URL.'tokens/default_token.png';
					}
					$user_response['subscribe'][] = $subscribe_response;
				}
			}
		}
		if($query2->num_rows()>0){
			$result2 = $query2->result_array();
			foreach($result2 as $record){
				if($record['type']=='DAILY_REWARD' && date('Y-m-d',strtotime($record['created_on']))==$today){
					$response = array();
					$response['token_purchase_id'] 		=	 ($record['token_purchase_id']===NULL)?'':$record['token_purchase_id'];
					$response['user_id'] 				=	 ($record['user_id']===NULL)?'':$record['user_id'];
					$response['tokens'] 				=	 ($record['tokens']===NULL)?'':$record['tokens'];
					$response['token_amount'] 			=	 ($record['token_amount']===NULL)?'':$record['token_amount'];
					$response['type'] 					=	 ($record['type']===NULL)?'':str_replace('_',' ',ucwords(strtolower($record['type'])));
					$response['is_claim_allowed'] 		=	 ($record['is_claim_allowed']===NULL)?'':$record['is_claim_allowed'];
					$response['is_claimed_status'] 		=	 ($record['is_claimed_status']===NULL)?'':$record['is_claimed_status'];
				}else if($record['type']!='DAILY_REWARD'){
					$response = array();
					$response['token_purchase_id'] 		=	 ($record['token_purchase_id']===NULL)?'':$record['token_purchase_id'];
					$response['user_id'] 				=	 ($record['user_id']===NULL)?'':$record['user_id'];
					$response['tokens'] 				=	 ($record['tokens']===NULL)?'':$record['tokens'];
					$response['token_amount'] 			=	 ($record['token_amount']===NULL)?'':$record['token_amount'];
					$response['type'] 					=	 ($record['type']===NULL)?'':str_replace('_',' ',ucwords(strtolower($record['type'])));
					$response['is_claim_allowed'] 		=	 ($record['is_claim_allowed']===NULL)?'':$record['is_claim_allowed'];
					$response['is_claimed_status'] 		=	 ($record['is_claimed_status']===NULL)?'':$record['is_claimed_status'];
				}
				$user_response['earn'][] 			=	 $response;
			}	
		}
		//pr($user_response['subscribe'],1);
		$response['status'] = 200; 
		$response['message'] = 'Token Shop';
		$response['token_shop'] = $user_response;
		$data = $response;
		return $data;
	}
	
	public function claimForToken($user_id,$token_purchase_id){
		$userData = $this->getUserData($user_id);
		$this->db->select('*');
		$this->db->from('token_purchases');
		$this->db->where('token_purchase_id',$token_purchase_id);
		$this->db->where('user_id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('token_purchases');
		$this->db->where('token_purchase_id',$token_purchase_id);
		$this->db->where('type !=','PURCHASED');
		$this->db->where('is_claim_allowed','1');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query2 = $this->db->get();
		
		$this->db->select('*');
		$this->db->from('token_purchases');
		$this->db->where('token_purchase_id',$token_purchase_id);
		$this->db->where('is_claimed_status','0');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query3 = $this->db->get();
		if($query->num_rows()>0){
			if($query2->num_rows()<=0){
				$response['status'] = 400; 
				$response['message'] = 'Claim not allowed!';
				$data = $response;
			}else if($query3->num_rows()<=0){
				$response['status'] = 400; 
				$response['message'] = 'You have already claimed for this token!';
				$data = $response;
			}else{
			
				$result = $query->row_array();
				$tokenDatail = array('is_claimed_status'=>'1','claimed_on'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				$updateData = $this->db->update('token_purchases',$tokenDatail,array('token_purchase_id'=>$token_purchase_id));
				if($updateData){
					$tot_tokens = $userData['user_tokens']+$result['tokens'];
					$datail = array('user_tokens'=>$tot_tokens,'modify'=>date('Y-m-d H:i:s'));
					$updateUser = $this->db->update('tbl_usermaster',$datail,array('id'=>$user_id));
					
					$userDataNew = $this->getUserData($user_id);
					
					$response['status']  		= 200; 
					$response['message'] 		= 'Congratulations! You Got Your Tokens.:)';
					$response['user_tokens'] 	= $userDataNew['user_tokens'];
					$data = $response;
					
				}else{
					$response['status'] 	= 400; 
					$response['message'] 	= $this->lang->line('something_went_wrong');
					$data = $response;
				}
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = $this->lang->line('something_went_wrong');
			$data = $response;
		}
		return $data;
	}
	
	public function logout($user_id){
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$user_data	=	array('login_key'=>'','device_id'=>'','fcm_id'=>'','modify'=>date('Y-m-d H:i:s'));
			$this->db->where('id',$user_id);
			$result 	=   $this->db->update('tbl_usermaster',$user_data);
			if($result){
				$response['status']  		= 200; 
				$response['message'] 		= $this->lang->line('logged_out');
				$data = $response;
			}else{
				$response['status'] = 400; 
				$response['message'] = $this->lang->line('something_went_wrong');
				$data = $response;
			}
		}else{
			$response['status'] = 400; 
			$response['message'] = $this->lang->line('user_not_found');
			$data = $response;
		}
		return $data;
	}
	
	public function getFaqsPage($user_id){
		if($user_id){
			$this->db->select('*');
			$this->db->from('faqs');
			$this->db->where('type','FAQ');
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$this->db->order_by('created_on','DESC');
			$query2 = $this->db->get();
			$faqsData = $query2->result_array();
			//echo $this->db->last_query();die;
			$new_arr = array();
			if($faqsData){
				foreach($faqsData as $result){
					//print_r($result);
					$faqResponse = array(); 
					$faqResponse['faq_id'] 						=	 ($result['faq_id']===NULL)?'':$result['faq_id'];
					/* $faqResponse['type'] 					=	 ($result['type']===NULL)?'':$result['type']; */
					$faqResponse['question'] 					=	 ($result['question']===NULL)?'':$result['question'];
					$faqResponse['answer'] 						=	 ($result['answer']===NULL)?'':$result['answer'];
					$faqResponse['created_on'] 					=	 ($result['created_on']===NULL)?'':$result['created_on'];
					$faqResponse['modified_on'] 				=	 ($result['modified_on']===NULL)?'':$result['modified_on'];
					//print_r($faqResponse);
					$new_arr[] = $faqResponse; 
				}
			}
			$response['status'] = 200; 
			$response['message'] = 'Faqs list';
			$response['faqs_data'] = $new_arr;
			$data = $response;
		}else{
			$response['status'] = 400; 
			$response['message'] = $this->lang->line('something_went_wrong');
			$data = $response;
		}
		return $data;
	}
	
	public function get_paging($result,$limit,$offset) {
		if($limit && $offset) {
			$total = count($result);
			$totPages=ceil($total / $limit);
			if($totPages >= $offset) {
				
				$page = max($offset, 1);
				$page = min($offset, $totPages); 
				$offset1 = ($page - 1) * $limit;
				
				
				if( $offset1 < 0 ) $offset1 = 0;
				if($limit==0 && $offset1==0){
					$getResult = $result;
					}else{
					$getResult= array_slice($result,$offset1,$limit);
				} 
			}
			else { $getResult=''; }
			return $getResult;
		}
		else {
			return $result;
		}   
	}
	
	public function user_token_update($token_key,$fcm_id){
		$userId = $this->get_user_from_token($token_key);
		
		if($userId){
			$data=array('modify'=>date('Y-m-d H:i:s'),'deviceid'=>$fcm_id);
			$this->db->where('id',$userId);
			$result = $this->db->update('tbl_usermaster',$data);
			if($result){
				$response['status'] =200;
				$response['error'] = false;
				$response['message'] = 'Token value is successfully update.';
				$response['updatetoken'] =$fcm_id;
				}else{
				$response['status'] =400;
				$response['error'] = true;
				$response['message'] = 'Token value is not update';
			}
			
			}else{
			$response['status'] =401;
			$response['error'] = true;
			$response['message'] = 'Token value is not valid';
		}
		echo json_encode($response, JSON_UNESCAPED_SLASHES);
	}
	
	public function count_row($data)  {
		$data=count($data);
		return $data;
	}
	public function genrate_code($user_id){
		///////////////// for int value //////////////
		$length = 4;
		$numbers = range(0,9);
		shuffle($numbers);
		for($i = 0; $i < $length; $i++){
			global $digits;
			$digits .= $numbers[$i];
		}
			$data=array('email_varification_code'=>$digits,'modify'=>date('Y-m-d H:i:s'));
			$this->db->where('id',$user_id);
			$result = $this->db->update('tbl_usermaster',$data);
		return $digits;
		
		///////////////// for int and text(small and large) //////////////
		
		/* $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString; */
	}
	
	public function getLoginKey($user_id){
		$salt = "23df$#%%^66sd$^%fg%^sjgdk90fdklndg099ndfg09LKJDJ*@##lkhlkhlsa#$%";
		$login_key = hash('sha1',$salt.$user_id);
		//print_r($login_key);die;
		return $login_key;
	}
	
	public function do_upload($file,$imageName){
      $filem = "assets/uploads/users/".$imageName; 
	  if(file_put_contents($filem, $file)) {
		 return True;
	  }else{
		 return False;
	  }
    }
	
	public function get_token($length=10)
	{
		$characters = '0123456789';
		$otp_token = '';
		for ($i = 0; $i < $length; $i++) {
			  $otp_token .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $otp_token;
	}	
	
	public function getPageOffset($limit,$offset,$totalRows) {
		if($limit && $offset) {
			$totPages=ceil($totalRows / $limit);
			$page = max($offset, 1); 
			$page = min($offset, $totPages); 
			$offset = ($page - 1) * $limit;   
			($offset) ? $offset : 0;
		}  else $offset=0;
		return $offset; 
	}	
	
	public function file_upload_error($error='The filetype you are attempting to upload is not allowed.')
	{
		$response['status']=0;
		$response['error'] = $error;
		echo json_encode($response,JSON_UNESCAPED_SLASHES);
		die;
	}
		
	public function getCmsPage($page_key){
		$this->db->select('*');
		$this->db->from('cms_pages');
		$this->db->where('page_key',strtoupper($page_key));
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$cmsData = $query->row_array();
			
			$userResponse =	array();
			$userResponse['cms_page_id'] = ($cmsData['cms_page_id']===NULL)?'':$cmsData['cms_page_id'];
			$userResponse['page_key'] 	 = ($cmsData['page_key']===NULL)?'':$cmsData['page_key'];
			if($this->language == 'english')
			{
				$userResponse['description'] = ($cmsData['description']===NULL)?'':$cmsData['description'];
			}else{
				$userResponse['description'] = ($cmsData['description']===NULL)?'':$cmsData['description_gr'];
			}
			
			$response['status'] = 200; 
			$response['message'] = 'CMS Page';
			$response['cms_page'] = $userResponse;
			$data = $response;
		}else{
			$response['status'] = 400; 
			$response['message'] = 'Something went worng';
			$data = $response;
		}
		return $data;
	}
}
?>