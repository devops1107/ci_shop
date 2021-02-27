<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

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

		 
		$this->layout->set_layout("admin/layout/main");
		
	}

	public function index(){

		redirect('welcome/login');
	} 

	public function login()
	{
		//pr($_SESSION,1);

		if($this->site_santry->is_login())
		{
			redirect('admin/dashboard');
		}
		//echo date('Y-m-d H:i:s'); die;
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		if($this->input->post()) {
			$validation_post = array(
               	 array('field' => 'email','label' => 'email','rules' => 'trim|required')
				, array('field' => 'password', 'label' =>'password', 'rules' => 'trim|required|callback__validate_user')
                );
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			//pr($this->form_validation->run(),1);
			if ($this->form_validation->run() === TRUE) 
			{
				$this->session->set_flashdata('flashSuccess','Login Successfully.');
			
				redirect('admin/dashboard');
			}else{
				redirect('admin/login');
			}
		}
		
		$data['title'] =SITE_NAME.' - Admin Login';
		$this->layout->view("admin/login", $data);
	}
	

	public function _validate_user()
	{
		$email 		= _input_post('email');
		$password 	= _input_post('password');
		
		$login_time = date("Y-m-d H:i:s");
		$login_unsuccess_wait_minutes = 5;
		$long_block_minutes = 24*60;
		$long_block_minutes_message = '1 Day';
		$allowed_attempts =  3;
		$another_allowed_attempts = 5;
		
		$adminResult = $this->db->select("*")
								->where("`user_email` = '" .addslashes($email). "'")
								->where("`user_delete` = '0' ")
								->get("admin_users");
		//pr($adminResult,1);
		if ($adminResult->num_rows() > 0) {
			$AdResult = $adminResult->row();
			//pr($AdResult,1);
			$failure_time = $AdResult->failure_time;
			$failed_login_attempts_count = $AdResult->failed_login_attempts_count;
			$to_time = new \DateTime(date('Y-m-d H:i:s'));
			$failure_time = new \DateTime($failure_time);
		
			$failed_minutes_count = $to_time->diff($failure_time)->format('%i');
			if($failed_login_attempts_count == $allowed_attempts && $failed_minutes_count < $login_unsuccess_wait_minutes)
			{
				$failed_secounds_count = 60 - ($to_time->diff($failure_time)->format('%s'));
				$failed_minutes_count = ($login_unsuccess_wait_minutes - $failed_minutes_count)-1;
				//echo date('Y-m-d H:i:s').' ---- '.$AdResult->failure_time; die;
				if($failed_minutes_count>1)
				{
					$this->session->set_flashdata('flashError', 'You are blocked for login.Please wait '.$failed_minutes_count.' Minutes '.$failed_secounds_count.' Seconds. ');
				}else{					
					$this->session->set_flashdata('flashError', 'You are blocked for login.Please wait '.$failed_minutes_count.' Minute '.$failed_secounds_count.' Seconds. ');
				}
				return FALSE;
			}elseif($failed_login_attempts_count==$another_allowed_attempts && $failed_minutes_count < $long_block_minutes)
			{

				$hours_count = $to_time->diff($failure_time)->format('%H');
				
				$failed_hours_count = (($long_block_minutes/60) - $hours_count)-1;
				$failed_minutes_count = (60 - $failed_minutes_count)-1;
				$failed_secounds_count = 60 - ($to_time->diff($failure_time)->format('%s'));
				
				$this->session->set_flashdata('flashError','You are blocked for '.$long_block_minutes_message.'.Please try again after '.$failed_hours_count.' Hours '.$failed_minutes_count.' Minutes '.$failed_secounds_count.' Seconds. ');
				return FALSE;
			}
	
			if ($AdResult->user_pass == md5($password) && $AdResult->user_type=='admin' && $AdResult->user_delete=='0') {
					$data = array(
									'id'				=>	$AdResult->user_id,
									'first_name'		=>	$AdResult->first_name,
									'last_name'			=>	$AdResult->last_name,
									'user_type'			=>	$AdResult->user_type,
									'role'				=>	$AdResult->role,
									'role1'				=>	$AdResult->role1,
									//'user_permission'	=>	$AdResult->admin_permission,
									'view_order_menu'	=>	$AdResult->view_order_menu,
									'device_type'		=>	$AdResult->device_type,
									'device_id'			=>	$AdResult->device_id,
									'email'				=>	$AdResult->user_email,
									'profile_image'		=>	$AdResult->profile_image,
								);
					$detail=array(
						'soft_user_id' 		=> $AdResult->user_id,
						'soft_ip'			=> $_SERVER["REMOTE_ADDR"],
						'soft_date_time'	=> date('Y-m-d H:i:s'),
						'soft_log_status'	=> 'login'
					);
					
					
					$this->db->insert('soft_login_logout',$detail);	
					//$login_key = $this->random_key();
					$login_key = $AdResult->login_key;
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);
					
					$this->site_santry->do_login($login_data);
					
					//$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key,'failed_login_attempts_count'=>0);
					$data = array('admin_last_login_dt' => $login_time,'failed_login_attempts_count'=>0);
					$this->db->where('user_id',$AdResult->user_id);
					$this->db->update('admin_users', $data); 
				
					return TRUE;
						
			}elseif($AdResult->user_pass == md5($password) && $AdResult->user_type=='partner' && $AdResult->user_delete=='0'){
			    //echo"hello";die;
				if($AdResult->partner_request_status == 'ACCEPTED'){
					$data = array(
									'id'				=>	$AdResult->user_id,
									'user_type'			=>	$AdResult->user_type,
									'you_are'			=>	$AdResult->you_are,
									'social_media_link'	=>	$AdResult->social_media_link,
									//'user_permission'	=>	$AdResult->admin_permission,
									'view_order_menu'	=>	$AdResult->view_order_menu,
									'device_type'		=>	$AdResult->device_type,
									'device_id'			=>	$AdResult->device_id,
									'email'				=>	$AdResult->user_email,
									'profile_image'		=>	$AdResult->profile_image,
									'first_name'		=>	$AdResult->first_name,
									'last_name'			=>	$AdResult->last_name,
								);
									
					$detail=array(
						'soft_user_id' 	=> $AdResult->user_id,
						'soft_ip'		=> $_SERVER["REMOTE_ADDR"],
						'soft_date_time'=> date('Y-m-d H:i:s'),
						'soft_log_status'=> 'login'
					);
					$this->db->insert('soft_login_logout',$detail);
					
					//$login_key = $this->random_key();
					$login_key = $AdResult->login_key;
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);
					//pr($login_data,1);
					$this->site_santry->do_login($login_data);
					
					//$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key,'failed_login_attempts_count'=>0);
					$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key,'failed_login_attempts_count'=>0);
					$this->db->where('user_id',$AdResult->user_id);
					$this->db->update('admin_users', $data); 
					return TRUE;		
				}else{
					$this->session->set_flashdata('flashError','Your Partner request has not been accepted yet by admin!');	
					return FALSE;
				}
			}elseif($AdResult->user_pass == md5($password) && $AdResult->user_type=='rsm' && $AdResult->user_delete=='0'){
				
					$data = array(
									'id'				=>	$AdResult->user_id,
									'user_type'			=>	$AdResult->user_type,
									//'user_permission'	=>	$AdResult->admin_permission,
									'view_order_menu'	=>	$AdResult->view_order_menu,
									'device_type'		=>	$AdResult->device_type,
									'device_id'			=>	$AdResult->device_id,
									'email'				=>	$AdResult->user_email,
								);
									
					$detail=array(
						'soft_user_id' 	=> $AdResult->user_id,
						'soft_ip'		=> $_SERVER["REMOTE_ADDR"],
						'soft_date_time'=> date('Y-m-d H:i:s'),
						'soft_log_status'=> 'login'
					);
					$this->db->insert('soft_login_logout',$detail);
					
					//$login_key = $this->random_key();
					$login_key = $AdResult->login_key;
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);				
					$this->site_santry->do_login($login_data);
					
					//$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key,'failed_login_attempts_count'=>0);
					$data = array('admin_last_login_dt' => $login_time,'failed_login_attempts_count'=>0);
					$this->db->where('user_id',$AdResult->user_id);
					$this->db->update('admin_users', $data); 
					return TRUE;		
				
			}else{
				if($AdResult->user_pass == md5($password) && $AdResult->user_type=='sales_person' && $AdResult->user_delete=='0'){
					$data = array(
									'id'				=>	$AdResult->user_id,
									'user_type'			=>	$AdResult->user_type,
									//'user_permission'	=>	$AdResult->admin_permission,
									'view_order_menu'	=>	$AdResult->view_order_menu,
									'device_type'		=>	$AdResult->device_type,
									'device_id'			=>	$AdResult->device_id,
									'email'				=>	$AdResult->user_email,
								);
									
					$detail=array(
						'soft_user_id' 	=> $AdResult->user_id,
						'soft_ip'		=> $_SERVER["REMOTE_ADDR"],
						'soft_date_time'=> date('Y-m-d H:i:s'),
						'soft_log_status'=> 'login'
					);
					
					
					$this->db->insert('soft_login_logout',$detail);
					//$login_key = $this->random_key();
					$login_key = $AdResult->login_key;
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);				
					$this->site_santry->do_login($login_data);
					//$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key,'failed_login_attempts_count'=>0);
					$data = array('admin_last_login_dt' => $login_time,'failed_login_attempts_count'=>0);
					
					$this->db->where('user_id',$AdResult->user_id);
					$this->db->update('admin_users', $data);
					return TRUE;
					
				}else{
					$this->session->set_flashdata('flashError', 'Invalid Password');
					
					if($failed_login_attempts_count < $allowed_attempts)
					{
						$data = array(
									'failed_login_attempts_count' =>++$failed_login_attempts_count,
									'failure_time' => date("Y-m-d H:i:s"),
							);
						$this->db->where('user_id',$AdResult->user_id)->update('admin_users',$data);
						if($failed_login_attempts_count == $allowed_attempts)
						{
							$this->session->set_flashdata('flashError', 'You are blocked for login.Please wait '.$login_unsuccess_wait_minutes.' Minutes.');
						}
					}elseif($failed_login_attempts_count<=$another_allowed_attempts || $failed_login_attempts_count==$another_allowed_attempts)
					{
						$data = array(
								'failed_login_attempts_count' =>++$failed_login_attempts_count,
								'failure_time' => date("Y-m-d H:i:s"),
						);
						
						$this->db->where('user_id',$AdResult->user_id)->update('admin_users',$data);
						if($failed_login_attempts_count==$another_allowed_attempts)
						{							
							$this->session->set_flashdata('flashError','You are blocked for '.$long_block_minutes_message.'.Please try again. ');
						}
					}
					return FALSE;
				}
			}
			$this->session->set_flashdata('flashError','Invalid email');	
			return FALSE;
		}else{
			
			$adminResult = $this->db->select("user_id,role,user_email,user_pass,user_type,device_type,device_id,user_delete")
				->where("`user_email` = '" .addslashes($email). "'")
				->where("`user_delete` = '1' ")
				->get("admin_users");
			if($adminResult->num_rows() > 0)
			{
				$this->session->set_flashdata('flashError', 'Your account is deleted.Please contact with admin.');
				return FALSE;
			}else{
				$this->session->set_flashdata('flashError', 'You are not register.Please contact with admin.');
				return FALSE;
			}
		}
	}

	public function logout() {

		if(!empty($this->site_santry->get_auth_data('id')))
		{
			$detail=array(
				'soft_user_id' 		=> $this->site_santry->get_auth_data('id'),
				'soft_ip'			=> $_SERVER["REMOTE_ADDR"],
				'soft_date_time'	=> date('Y-m-d H:i:s'),
				'soft_log_status'	=> 'logout'
			);
			$this->db->insert('soft_login_logout',$detail);
		}
		$this->site_santry->do_log_out();
		redirect('admin/login');
	}
	
	public function random_key($length=25)
	{
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$email_token = '';
		for ($i = 0; $i < $length; $i++) {
			  $email_token .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $email_token;
	}

	public function forgot_password()
	{
		if($this->input->post()) {
			$post = $this->input->post();
			$validation_post = array(
				  array('field' => 'email1', 'label' =>'Email', 'rules' => 'trim|required|callback__validate_user_forgot_password'),
			);
			//pr($post,1);
				
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$mod_date	=	date('Y-m-d H:i:s');
				$token		=	md5($this->get_token(15));
				$detail 	= 	array(
							'forgot_verify_code'		=> $token,
							'admin_modify_dt'			=>	$mod_date,
						);
				$email_link = base_url('admin/forgot-password-vefication/'.$post['email1'].'/'.$token);
				$to = trim($post['email1']);
				$subject = SITE_NAME.' Admin Forgot Password';
				$message = 'Hello,<br/>';
				$message .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Your '.SITE_NAME.' forget password url is <a href="'.$email_link.'">'.$email_link.'</a><br/>';
				$message .= 'Thank you<br/>';
				$message .= SITE_NAME.' Team';
				$this->sendemail($to,$subject,$message);
				$this->commonmodel->_update('admin_users', $detail, array('user_email'=>trim($post['email1']),'user_delete'=>'0','user_status'=>'1'));
				$this->session->set_flashdata('flashSuccess','An vefication mail sent on your email id.Please verify to login.');
				
				redirect('admin');
			}
			else {
				$this->session->set_flashdata('flashError', validation_errors());
				redirect('admin');
			
			}
		}
		else{
			redirect('admin');

		}
		
	}

	public function _validate_user_forgot_password()
	{
		$email 	=  _input_post('email1');
	    $condition =  array('admin_users.user_delete' => '0',
							'admin_users.user_status' => '1',
							'admin_users.user_email'=>$email
					  );
		
					  
		$this->db->select('*')->from('admin_users')
					 ->where($condition);
		$getUser = $this->db->get();
		if($getUser->num_rows() > 0){
			
			//$fgdf = $this->session->userdata();
			$getUserData = $getUser->row();

			if($getUserData->user_delete == '1'){
				$this->form_validation->set_message('_validate_user_forgot_password', 'Your account is Deleted.Please contact with administerator.');
				return FALSE;
			}if($getUserData->user_status  == '0' ){
				$this->form_validation->set_message('_validate_user_forgot_password', 'Your account is De-Activated.Please contact with administerator.');
				return FALSE;
			}else{
				/* $b_user_id = $getUserData->b_user_id;
				$update = $this->commonmodel->_update('admin_users',$update_data,array('b_user_id'=> $b_user_id ));						 */
				return TRUE;
			}		
		}else{
	

		   $this->form_validation->set_message('_validate_user_forgot_password', 'You are not registered with us.Please contact with administerator.');
		   return FALSE;	
		}
	}	
	
	public function verify_forgot_password($email,$token)
	{
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
					if($this->input->post('password') != $this->input->post('confirmpassword'))
					{
						$this->load->library('user_agent');
						$this->session->set_flashdata('flashError',"Password doesn't match.Please enter same password.");
						redirect($this->agent->referrer());
					}else{						
						$update_data = array('user_pass'=>md5($this->input->post('password')),'forgot_verify_code'=>'');
						$this->commonmodel->_update('admin_users',$update_data,array('user_id'=> $emailDetails['user_id']));
						$this->session->set_flashdata('flashSuccess','Password changed successfully.You can login now.');
						redirect('admin');
					}
				}else{
					$data['title'] = 'Reset password';
					$this->layout->view('admin/change_forgot_password',$data);
				}
			}else{
				$this->session->set_flashdata('flashError','This link is expired.Please try again by forgot password section.');
				redirect('admin');
			}
		}else{
			$this->session->set_flashdata('flashError','You are not register with us.Please register first.');
			redirect('admin');
		}
	}
	
	public function sendemail($to,$subject,$message)
	{
		require_once('smtp/class.phpmailer.php');
		$HOST_NAME 	= HOST_NAME;
		$USER_NAME 	= USER_NAME;
		$PASSWORD 	= SMTP_PASSWORD;
		$PORT_NO 	= PORT_NO;
		$FROM_NAME 	= FROM_NAME;
		$FROM 		= FROM;
		$crlf 		= "\n";
		$pos='';
		if($pos !=false){
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
	
	public function get_token($length=10)
	{
		$characters = '0123456789';
		$otp_token = '';
		for ($i = 0; $i < $length; $i++) {
			  $otp_token .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $otp_token;
	}	



	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */