<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct() {
		parent::__construct();
		//redirect('admin');
		/* if($this->site_santry->is_web_login())
		{
			redirect('dashboard');
		} */
		//$this->layout->set_layout("layout/main");
	}

	public function index(){
		if($this->input->post()) {
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				  array('field' => 'email', 'label' =>'Email', 'rules' => 'trim|required|callback__validate_user'),
				  array('field' => 'password', 'label' =>'Password', 'rules' => 'trim|required'),
                );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				//pr($post,1);
				$email 	=  _input_post('email');
				$password 	=  _input_post('password');
				$condition =  array('users.delete_status' => '0',
									'users.email'=>$email,
									'users.password'=>md5($password),
							  );
				//pr($condition,1);
				$this->db->select('*')->from('users')
							 ->where($condition);
				$getUser = $this->db->get();
				if($getUser->num_rows() > 0){
					
					$userDetails = $getUser->result_array();
					$userDetails = $userDetails[0];
					$detail = 	array(
							'user_id'	=> $userDetails['user_id'],
							'name'		=> $userDetails['name'],
							'email'		=> $userDetails['email'],
							'dob'		=> $userDetails['dob'],
							'address'	=> $userDetails['address'],
						);
					//pr($detail,1);
					$this->site_santry->do_web_login($detail);
					$this->session->set_flashdata('flashSuccess','Welcome to '.SITE_NAME.' dashboard :)');
					redirect('dashboard');
				}else{
					$this->session->set_flashdata('flashError','Wrong email id or password.');
					redirect('home');
				}
			}
		} 
		if($this->session->flashdata('flashSuccess'))
		{
			echo '<title>'.SITE_NAME.' - Success</title>';
			echo $this->session->flashdata('flashSuccess');
		}elseif($this->session->flashdata('flashError')){
			echo '<title>'.SITE_NAME.' - Fail</title>';
			echo $this->session->flashdata('flashError');
		}else{
		    //die('asd');
    		redirect('home');
			//die('Site is not ready yet.. Hold on, We will back with best options.');
		}
		//$this->layout->view("login", $data);
	}

	/* public function signup(){
		if($this->input->post()) {
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				  array('field' => 'name', 'label' =>'Name', 'rules' => 'trim|required'),
				  array('field' => 'email', 'label' =>'Email', 'rules' => 'trim|required|callback__validate_user_signup'),
				  array('field' => 'dob', 'label' =>'Date of birth', 'rules' => 'trim|required'),
				  array('field' => 'password', 'label' =>'Password', 'rules' => 'trim|required'),
				  array('field' => 'confirmpassword', 'label' =>'Confirm Password', 'rules' => 'required|matches[password]'),
                );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				if($post['gender']=='male')
				{
					$gender = 1;
				}else{
					$gender = 0;
					
				}
				$mod_date	=	date('Y-m-d H:i:s');
				$token	=	md5($this->get_token(15));
				$detail 	= 	array(
							'name'							=> trim($post['name']),
							'email'							=> trim($post['email']),
							'gender'						=> $gender,
							'dob'							=> date('Y-m-d H:i:s',strtotime($post['dob'])),
							'address'						=> trim($post['address']),
							'password'	        			=> md5($post['password']),
							'user_verification_status'		=> '0',
							'user_verification_code'		=> $token,
							'created_on'		=>	$mod_date,
							'last_updated_on'	=>	$mod_date,
						);
				$email_link = base_url('mail-verification/'.$post['email'].'/'.$token);
				$to = trim($post['email']);
				$subject = SITE_NAME.' Signup';
				$message = 'Hello,<br/>';
				$message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome to '.SITE_NAME.'.Your signup confirmation url is <a href="'.$email_link.'">'.$email_link.'</a><br/>';
				$message .= 'Thank you<br/>';
				$message .= SITE_NAME.' Team';
				$this->sendemail($to,$subject,$message);
				$this->commonmodel->_insert('users', $detail);
				//echo $email_link; die;
				$this->session->set_flashdata('flashSuccess','Account registeration successfull.An vefication mail sent on your email id.Please verify to login.');
				redirect('home');
			}
		}
		$data['title'] = SITE_NAME.' - Signup';
		$this->layout->view("sign-up", $data);
	}

	public function forgot_password(){
		if($this->input->post()) {
			$post = $this->input->post();
			$validation_post = array(
				  array('field' => 'email', 'label' =>'Email', 'rules' => 'trim|required|callback__validate_user_forgot_password'),
			);
			//pr($post,1);
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$mod_date	=	date('Y-m-d H:i:s');
				$token	=	md5($this->get_token(15));
				$detail 	= 	array(
							'forgot_verify_code'		=> $token,
							'last_updated_on'			=>	$mod_date,
						);
				$email_link = base_url('forgot-password-vefication/'.$post['email'].'/'.$token);
				$to = trim($post['email']);
				$subject = SITE_NAME.' Forgot Password';
				$message = 'Hello,<br/>';
				$message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Your '.SITE_NAME.' recover url is <a href="'.$email_link.'">'.$email_link.'</a><br/>';
				$message .= 'Thank you<br/>';
				$message .= SITE_NAME.' Team';
				$this->sendemail($to,$subject,$message);
				$this->commonmodel->_update('users', $detail, array('email'=>trim($post['email']),'delete_status'=>'0','status'=>'1'));
				$this->session->set_flashdata('flashSuccess','An vefication mail sent on your email id.Please verify to login.');
				redirect('home');
			}
		}
		$data['title'] = SITE_NAME.' - Forgot Password';
		$this->layout->view("forgot_password", $data);
	} */

	public function _validate_user_signup()
	{
		$email 	=  _input_post('email');
	    $condition =  array('users.email'=>$email,'users.delete_status'=>'0');
		$this->db->select('*')->from('users')
					 ->where($condition);
					 
		$getUser = $this->db->get();
		if($getUser->num_rows() < 1){					
			return TRUE;
		}else{
		   $this->form_validation->set_message('_validate_user_signup', 'This email is already registerd.Please login or you can click on forgot password to recover your account.');
		   return FALSE;	
		}
		
	}

	public function _validate_user()
	{
		$email 	=  _input_post('email');
	    $condition =  array(
							'users.delete_status' => '0',
							'users.email'=>$email
					  );
					  
		$this->db->select('*')->from('users')
					 ->where($condition);
					 
		$getUser = $this->db->get();
		if($getUser->num_rows() > 0){
			
			//$fgdf = $this->session->userdata();
			$getUserData = $getUser->row();
			
			if($getUserData->delete_status == '1'){
				$this->form_validation->set_message('_validate_user', 'You are not allowed to login.');
				return FALSE;
			}
			if($getUserData->user_verification_status  == '0' ){
				$this->form_validation->set_message('_validate_user', 'Please verify email sent on your email id first.');
				return FALSE;
			}
			if($getUserData->status  == '0' ){
				$this->form_validation->set_message('_validate_user', 'Your account is De-Activated.Please contact with administerator.');
				return FALSE;
			}else{
				/* $b_user_id = $getUserData->b_user_id;
				$update = $this->commonmodel->_update('users',$update_data,array('b_user_id'=> $b_user_id ));						 */
				return TRUE;
			}		
		}else{
		   $this->form_validation->set_message('_validate_user', 'You are not registered with us.Please signup first.');
		   return FALSE;	
		}
	}

	public function _validate_user_forgot_password()
	{
		$email 	=  _input_post('email');
	    $condition =  array('users.delete_status' => '0',
							'users.status' => '1',
							'users.email'=>$email
					  );
					  
		$this->db->select('*')->from('users')
					 ->where($condition);
					 
		$getUser = $this->db->get();
		if($getUser->num_rows() > 0){
			
			//$fgdf = $this->session->userdata();
			$getUserData = $getUser->row();
			
			if($getUserData->delete_status == '1'){
				$this->form_validation->set_message('_validate_user_forgot_password', 'Your account is Deleted.Please contact with administerator.');
				return FALSE;
			}if($getUserData->status  == '0' ){
				$this->form_validation->set_message('_validate_user_forgot_password', 'Your account is De-Activated.Please contact with administerator.');
				return FALSE;
			}else{
				/* $b_user_id = $getUserData->b_user_id;
				$update = $this->commonmodel->_update('users',$update_data,array('b_user_id'=> $b_user_id ));						 */
				return TRUE;
			}		
		}else{
		   $this->form_validation->set_message('_validate_user_forgot_password', 'You are not registered with us.Please signup first.');
		   return FALSE;	
		}
	}
	
	public function mail_verify($email,$token)
	{
		$condition = array('emailid'=>$email,'status'=>'1','delete_status'=>'0');
		//pr($condition,1);
		$details = $this->db->select('*')->from('tbl_usermaster')->where($condition)->get();
		if($details->num_rows()>0)
		{
			$emailDetails = $details->result_array();
			$emailDetails = $emailDetails[0];
			if($emailDetails['token']==$token)
			{
				if($emailDetails['account_confirm']=='N')
				{
					$update_data = array('account_confirm' =>'C','modify'=>date("Y-m-d H:i:s"));
					$this->commonmodel->_update('tbl_usermaster',$update_data,array('id'=> $emailDetails['id']));
					$this->session->set_flashdata('flashSuccess','Email verification successfull.You can login now.');
					redirect('home');
				}else{
					$this->session->set_flashdata('flashError','You are already verified.');
					redirect('home');
				}
			}else{
				$this->session->set_flashdata('flashError','Token mismatch.Please hit correct url.');
				redirect('home');
			}
			redirect('home');
		}else{
			$this->session->set_flashdata('flashError','You are not register with us.Please register first.');
			redirect('home');
		}
	}
	
	public function verify_forgot_password_user($email,$token)
	{
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$condition = array('emailid'=>$email,'status'=>'1','delete_status'=>'0');
		$details = $this->db->select('*')->from('tbl_usermaster')->where($condition)->get();
		if($details->num_rows()>0)
		{
			$emailDetails = $details->result_array();
			$emailDetails = $emailDetails[0];
			//pr($emailDetails,1);
			if($emailDetails['forgot_verify_code']!="")
			{
				if($this->input->post())
				{
					//pr($this->input->post(),1);
					if($this->input->post('password') != $this->input->post('confirmpassword'))
					{
						$this->load->library('user_agent');
						$this->session->set_flashdata('flashError',"Password doesn't match.Please enter same password.");
						redirect($referrer);
					}else{						
						$update_data = array('password'=>md5($this->input->post('password')),'forgot_verify_code'=>'','email_varification_code'=>'','email_varification_status'=>'1','modify'=>date('Y-m-d H:i:s'));
						$this->commonmodel->_update('tbl_usermaster',$update_data,array('id'=> $emailDetails['id']));
						$this->session->set_flashdata('flashSuccess','Password changed successfully.You can login now.');
						redirect('home/login');
					}
				}else{
					$data['title'] = 'Reset password';
					$this->load->view('web/change_forgot_password_user',$data);
				}
			}else{
				$this->session->set_flashdata('flashError','This link is expired.Please try again by forgot password section.');
				redirect('home');
			}
		}else{
			$this->session->set_flashdata('flashError','You are not register with us.Please register first.');
			redirect('home');
		}
	}
	
	public function verify_forgot_password_partner($email,$token)
	{
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$condition = array('user_email'=>$email,'user_status'=>'1','user_delete'=>'0');
		$details = $this->db->select('*')->from('admin_users')->where($condition)->get();
		if($details->num_rows()>0)
		{
			$emailDetails = $details->result_array();
			$emailDetails = $emailDetails[0];
			if($emailDetails['forgot_verify_code']!="")
			{
				if($this->input->post())
				{
					//pr($this->input->post(),1);
					if($this->input->post('password') != $this->input->post('confirmpassword'))
					{
						$this->load->library('user_agent');
						$this->session->set_flashdata('flashError',"Password doesn't match.Please enter same password.");
						redirect($referrer);
					}else{						
						$update_data = array('user_pass'=>md5($this->input->post('password')),'forgot_verify_code'=>'','admin_modify_dt'=>date('Y-m-d H:i:s'));
						$this->commonmodel->_update('admin_users',$update_data,array('user_id'=> $emailDetails['user_id']));
						$this->session->set_flashdata('flashSuccess','Password changed successfully.You can login now.');
						redirect('home/login');
					}
				}else{
					$data['title'] = 'Reset password';
					$this->load->view('web/change_forgot_password_partner',$data);
				}
			}else{
				$this->session->set_flashdata('flashError','This link is expired.Please try again by forgot password section.');
				redirect('home');
			}
		}else{
			$this->session->set_flashdata('flashError','You are not register with us.Please register first.');
			redirect('home');
		}
	}
	
	public function _validate_otp()
	{
		
		$otp_number 	=  _input_post('otp_number');
	    $condition =  array('users.delete_status' => '0',
							'users.b_ut_id' => '1',
							'users.b_mobile_verify_token'=>$otp_number
					  );
					  
		$this->db->select('users.b_id,
							users.b_full_name,
							users.b_profile_image,
							users.b_user_id,
							users.b_mobile_number,
							users.b_mobile_verify,
							users.b_mobile_verify_token,
							users.status,
							users.delete_status,
							users.complated_steps')->from('users')
					 ->join('business_user_types', 'users.b_ut_id = business_user_types.b_ut_id')
					 ->where($condition);
					 
		$getUser = $this->db->get();
		if($getUser->num_rows() > 0){
			
			$getUserData = $getUser->row();
			
			if($getUserData->delete_status == '1'){
				$this->form_validation->set_message('_validate_user', 'You are not allowed to login.');
				return FALSE;
			}if($getUserData->status  == '0' ){
				$this->form_validation->set_message('_validate_user', 'Your account is De-Activated.Please contact with administerator.');
				return FALSE;
			}else{
				
				 $update_data = array("b_mobile_verify_token" =>'',
									  "b_mobile_verify" => '1',
									  "modify_date" => date('Y-m-d H:i:s'),  
								);
				
				$b_user_id = $getUserData->b_user_id;
				$update = $this->commonmodel->_update('users',$update_data,array('b_user_id'=> $b_user_id ));
				
				$login_data =array('b_id'=>$getUserData->b_id,
								   'b_full_name'=>$getUserData->b_full_name,
								   'b_profile_image'=>$getUserData->b_profile_image,
								   'b_user_id'=>$getUserData->b_user_id
								   );
				$this->site_santry->do_web_login($login_data);						
				return TRUE;
			}		
		}else{
		   $this->form_validation->set_message('_validate_user', 'Business account is not found.');
		   return FALSE;	
		}
		
	}
	
	public function logout() {
		if(!empty($this->site_santry->get_web_auth_data('id')))
		{
			$detail=array(
				'soft_user_id' 		=> $this->site_santry->get_web_auth_data('id'),
				'soft_ip'			=> $_SERVER["REMOTE_ADDR"],
				'soft_date_time'	=> date('Y-m-d H:i:s'),
				'soft_log_status'	=> 'logout'
			);
			$this->db->insert('soft_login_logout',$detail);
		}
		$this->site_santry->do_web_log_out();
		redirect('home/business-signin');
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
	
	 public function get_image_path($b_id=0){
		   if($b_id){
			$path = UPLOAD_URL.'businesses/business-'.$b_id.'/';
			return $path;
		   }else{
		  	$path = UPLOAD_URL.'businesses/';
			 return $path;
		   }
	}
	
	public function check_useremail(){
		$json=array();
		$user_email = $this->input->post('email');
		
		$this->db->select('email')
					->from('users')
					->where('email',$user_email);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$details = $this->db->get();
		
		if($details->num_rows() > 0){
			$isAvailable = false; 
			$json=array('valid' => $isAvailable	);
		}else{
			$isAvailable = true; 
			$json=array('valid' => $isAvailable	);
		}
		echo json_encode($json);
		exit;
	}
	
	public function check_user_forgot_email(){
		$json=array();
		$user_email = $this->input->post('email');
		
		$this->db->select('email')
					->from('users')
					->where('email',$user_email);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$details = $this->db->get();
		
		if($details->num_rows() > 0){
			$isAvailable = true; 
			$json=array('valid' => $isAvailable	);
		}else{
			$isAvailable = false; 
			$json=array('valid' => $isAvailable	);
		}
		echo json_encode($json);
		exit;
	}	
	
	public function sendemail($to,$subject,$message)
	{
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */