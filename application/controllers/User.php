<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	public $language ="";
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
	//private $language ="";
	public function __construct() {
		parent::__construct();
		/* if($this->site_santry->is_web_login())
		{
			redirect('dashboard');
		} */
		$language = get_site_language();
		$this->language = $language;
		$this->lang->load('all_web_content_lang',$language);
		
		$this->layout->set_layout("web/layout/main");
		$this->load->model('web/User_model');
		
	}
	
	public function my_cart(){
		$data['title'] = "Baaba.de - My Cart";
		$language = get_site_language();
		if($this->site_santry->is_web_login())
        {
			$user_login_details = $this->site_santry->get_web_auth_data();	
			$login_id = $user_login_details['id'];
		}
		else
			$login_id = '';
		$my_cart = $this->User_model->my_cart_details($language,$login_id);
		$data['tax_percent'] = $this->User_model->get_tax_percent();
		$data['contact_details'] = $this->User_model->get_contact_details($language);
		//pr($match_news,1);
		$data['cart_details'] = $my_cart;
		$this->layout->view('web/my-cart',$data);
	}

	public function login(){
		$language = get_site_language();
		$data['title'] = "Baaba.de - Login";
		if($this->site_santry->is_web_login())
		{
			redirect('home');
		}
		
		if($this->input->post()) {
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				array('field' => 'emailid','label' => 'Email','rules' => 'trim|required|callback__validate_user_login')
				, array('field' => 'password', 'label' =>'Password', 'rules' => 'trim|required')
            );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				//$this->session->set_flashdata('flashSuccess','Login Successfull.');
				$this->check_user_order();
				redirect('home');
			}
		}
		$data['contact_details'] = $this->User_model->get_contact_details($language);
		$this->layout->view('web/login',$data);
	}

	public function check_user_order()
	{	
		$user_login_details = $this->site_santry->get_web_auth_data();	
		$login_id = $user_login_details['id'];
		$order_details = $this->session->userdata('session_cart_details');
		if(!empty($order_details))
		{
			for ($i=0; $i < count($order_details); $i++) 
			{ 
				$product_id = $order_details[$i]['product_id'];
				$shop_type = $order_details[$i]['shop_type'];
				$product_qty = $order_details[$i]['quantity'];
				$this->User_model->insert_order_request($login_id,$product_id,$shop_type,$product_qty);
			}
			$this->db->select('order_request_id');
			$this->db->from('tbl_order_request');
			$this->db->where('user_id',$login_id);
			$this->db->where('status','1');
			$query = $this->db->get();
			$this->session->set_userdata("total_cart_products",$query->num_rows());
			$this->session->unset_userdata('session_cart_details');
		}
  	}
	
	public function _validate_user_login()
	{
		$emailid 		= _input_post('emailid');
		$pass 			= _input_post('password');
		$password 		= md5($pass);
		$login_time = date("Y-m-d H:i:s");
		
		$result =	$this->db->query("SELECT * FROM `tbl_usermaster` WHERE (username='".$emailid."' or emailid='".$emailid."') and delete_status='0' ");
		if ($result->num_rows() > 0) {
			$userData = $result->row_array();
			$user_id = $userData['id'];
			$db_password = $userData['password'];
			if($db_password == $password)
			{
				if($userData['email_varification_status'] =='1'){
					$data = array(
						'id'				=>	$user_id,
						'device_type'		=>	$userData['device_type'],
						'device_id'			=>	$userData['device_id'],
						'email'				=>	$userData['emailid'],
						'user_name'			=>	$userData['first_name']." ".$userData['last_name'],
					);
						$login_key = $this->getLoginKey($user_id);
						$lgkey = array(
						     'login_key' => $login_key,
						);
						$login_data = array();
						$login_data = array_merge($lgkey,$data);
						
						$this->site_santry->do_web_login($login_data);
						return TRUE;
				}else{
					$this->form_validation->set_message('_validate_user_login', $this->lang->line('verify_account_first'));
					return FALSE;
				}
			}else{
				$this->form_validation->set_message('_validate_user_login', $this->lang->line('invalid_login_credentials'));
				return FALSE;
			}
		}else{
			$this->form_validation->set_message('_validate_user_login', $this->lang->line('invalid_login_credentials'));
			return FALSE;
		}
	}

	public function register(){
		$data['title'] = "Baaba.de - Register";
		if($this->site_santry->is_web_login())
		{
			redirect('home');
		}
		if($this->input->post()) {
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				array('field' => 'confirm_password', 'label' =>'Confirm Password', 'rules' => 'required|matches[password]'),
				array('field' => 'emailid','label' => 'Email','rules' => 'trim|required|callback__validate_user'),
				array('field' => 'first_name', 'label' =>'First Name', 'rules' => 'trim|required'),
				array('field' => 'mobileno', 'label' =>'Mobile Number', 'rules' => 'trim|required'),
				array('field' => 'vat_number', 'label' =>'VAT Number', 'rules' => 'trim|required'),
				array('field' => 'last_name', 'label' =>'Last Name', 'rules' => 'trim|required'),
				array('field' => 'password', 'label' =>'Password', 'rules' => 'trim|required')
            );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$this->session->set_flashdata('flashSuccess',$this->lang->line('please_verify_and_login'));
				redirect('login');
				
			}
		}

		$language = get_site_language();
		$data['contact_details'] = $this->User_model->get_contact_details($language);
		
		$this->layout->view('web/register',$data);
	}
	
	public function _validate_user()
	{ 
		$emailid 		= _input_post('emailid');
		$first_name 	= _input_post('first_name');
		$last_name 	= _input_post('last_name');
		$mobileno 	= _input_post('mobileno');
		$vat_number 	= _input_post('vat_number');
		$commercial_reg_no 	= _input_post('commercial_reg_no');
		$password 		= _input_post('password');
		$confirm_password 	= _input_post('confirm_password');

		if($password != $confirm_password)
		{
			$this->form_validation->set_message('_validate_user', $this->lang->line('password_do_not_match'));
			return FALSE;
		}
		else
		{		
			$full_name = $first_name." ".$last_name;
			
			$this->db->select('*');
			$this->db->from('tbl_usermaster');
			$this->db->where('status','0');
			$this->db->where('delete_status','0');
			$this->db->where('emailid',$emailid);
			$query = $this->db->get();
			
			$this->db->select('*');
			$this->db->from('tbl_usermaster');
			$this->db->where('delete_status','0');
			$this->db->where('emailid',$emailid);
			$emailquery = $this->db->get();
			if ($query->num_rows() > 0) {
				$this->form_validation->set_message('_validate_user', $this->lang->line('your_account_deactivated'));
				return FALSE;
			}else if($emailquery->num_rows() >0)
			{
				$emailDetails = $emailquery->row_array();
				$email_varification_status = $emailDetails['email_varification_status'];
				if($email_varification_status=='1')
				{
					$this->form_validation->set_message('_validate_user', $this->lang->line('email_already_exist'));
					return FALSE;
				}else{
					
					$user_id = $emailDetails['id'];

					$verification_code  =  $this->genrate_code($user_id);
					$verification_code 	= hash('sha256', $verification_code);
					$to = $emailid;
					$url = base_url('verifyEmail/'.$to.'/'.$verification_code);
					if($this->language == 'english')
					{
						$subject = SITE_NAME.' account verification email';
						$message =	'Hello '.$full_name.'<br/>';
						$message .= 'Thank you for registering with Baaba.de. Please verify your email by <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
						$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
						$message .= 'We hope you enjoy our services!<br/><br/>';
						$message .= 'Sincerely<br/>';
						$message .= SITE_NAME.' Team';
					}elseif($this->language == 'german'){
						$subject = SITE_NAME.' Kontobestätigungs-E-Mail';
						$message =	'Hallo '.$full_name.'<br/>';
						$message .= 'Vielen Dank, dass Sie sich bei Baaba.de registriert haben. Bitte bestätigen Sie Ihre E-Mail-Adresse mit <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
						$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
						$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
						$message .= 'Mit freundlichen Grüßen<br/>';
						$message .= SITE_NAME.' Mannschaft';
					}else{
						$subject = SITE_NAME.' Kontobestätigungs-E-Mail';
						$message =	'Hallo '.$full_name.'<br/>';
						$message .= 'Vielen Dank, dass Sie sich bei Baaba.de registriert haben. Bitte bestätigen Sie Ihre E-Mail-Adresse mit <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
						$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
						$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
						$message .= 'Mit freundlichen Grüßen<br/>';
						$message .= SITE_NAME.' Mannschaft';
					}
					$mailConfirm = $this->sendemail($to,$subject,$message);

					$this->form_validation->set_message('_validate_user', $this->lang->line('register_successfull_resend_email'));
					$this->session->set_flashdata('flashSuccess',$this->lang->line('register_successfull_resend_email'));
				    redirect('register');
					return TRUE;
				}
			}else{    
				$userdata = array();
				$userdata['emailid'] 		= $emailid;
				$userdata['first_name']   	= $first_name;
				$userdata['last_name']   	= $last_name;
				$userdata['mobileno'] 		= $mobileno;
				$userdata['vat_number']   	= $vat_number;
				$userdata['commercial_reg_no'] = $commercial_reg_no;
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
						$message =	'Hello '.$full_name.'<br/>';
						$message .= 'Thank you for registering with Baaba.de. Please verify your email by <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
						$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
						$message .= 'We hope you enjoy our services!<br/><br/>';
						$message .= 'Sincerely<br/>';
						$message .= SITE_NAME.' Team';
					}elseif($this->language == 'german')
					{
						$subject = SITE_NAME.' Kontobestätigungs-E-Mail';
						$message =	'Hallo '.$full_name.'<br/>';
						$message .= 'Vielen Dank, dass Sie sich bei Baaba.de registriert haben. Bitte bestätigen Sie Ihre E-Mail-Adresse mit <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
						$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
						$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
						$message .= 'Mit freundlichen Grüßen<br/>';
						$message .= SITE_NAME.' Mannschaft';
					}else{
						$subject = SITE_NAME.' account verification email';
						$message =	'Hello '.$full_name.'<br/>';
						$message .= 'Thank you for registering with Baaba.de. Please verify your email by <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
						$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
						$message .= 'We hope you enjoy our services!<br/><br/>';
						$message .= 'Sincerely<br/>';
						$message .= SITE_NAME.' Team';
					}
					$mailConfirm = $this->sendemail($to,$subject,$message);

					$result = $this->commonmodel->_update('tbl_usermaster',$userdata,array('id'=>$user_id));
					$this->form_validation->set_message('_validate_user', $this->lang->line('register_successfull'));
					return TRUE;
				}
			}
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
		redirect('home');
	}

	public function verifyEmail()
	{
	    $emailid = $this->uri->segment(2);
		$email_varification_code = $this->uri->segment(3);
		
		$condition = array('emailid'=>$emailid,'status'=>'1','delete_status'=>'0');
		//pr($condition,1);
		$details = $this->db->select('*')->from('tbl_usermaster')->where($condition)->get();
		if($details->num_rows()>0)
		{
			$emailDetails = $details->row_array();
			if(hash('sha256', $emailDetails['email_varification_code'])==$email_varification_code)
			{
			    //echo 's'; die;
				if($emailDetails['email_varification_status']=='0')
				{
					$update_data = array('email_varification_status' =>'1','modify'=>date("Y-m-d H:i:s"));
					$this->db->where('emailid',$emailid);
					$result = $this->db->update('tbl_usermaster',$update_data);
					
					$msg = 'user_register_success, Email verification successfull.You can login now.';
					$this->session->set_flashdata('flashSuccess','Email verification successfull.You can login now..');
					redirect('login');
				}else{
				   $this->session->set_flashdata('flashError','You are already verified.');
					redirect('login');
				}
			}else{
			   	$this->session->set_flashdata('flashError','Your link has expired ! Please try again!');
				redirect('register');
			}
		}else{
			$this->session->set_flashdata('flashError','You are not register with us.Please register first.');
			redirect('register');
		}
	}
	
	public function forgot_password(){
		$data['title'] = "Forgot Password";
		if(!empty($this->input->post())){
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				array('field' => 'emailid','label' => 'Email','rules' => 'trim|required|valid_email'),
			);
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$email_id = $post['emailid'];	
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
					$user_name  = $userData['full_name'];
					$token		=	md5($this->get_token(15));
					//pr($token,1);
					$to = $email_id;
					$url = base_url('forgot-password-vefication/'.$to.'/'.$token);
					if($this->language == 'english')
					{
						$subject 	= 'Baaba.de forgot password email';
						$message =	'Hello '.$user_name.'<br/>';
						$message .= 'We have sent this email in response to your request to reset password on Baaba.de.To reset password please click on this link <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
						$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
						$message .= 'We hope you enjoy our services!<br/><br/>';
						$message .= 'Sincerely<br/>';
						$message .=  SITE_NAME.' Team';
					}elseif($this->language == 'turkish')
					{
						$subject 	= 'Baaba.de forgot password email';
						$message =	'Hello '.$user_name.'<br/>';
						$message .= 'We have sent this email in response to your request to reset password on Baaba.de.To reset password please click on this link <a href='.$url.'>clicking here</a> or by copying and pasting the following URL into your browser ('.$url.') <br/> <br/>';
						$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
						$message .= 'We hope you enjoy our services!<br/><br/>';
						$message .= 'Sincerely<br/>';
						$message .=  SITE_NAME.' Team';
					}else{

						$subject 	= SITE_NAME.' Passwort vergessen E-Mail';
						$message =	'Hallo '.$user_name.'<br/>';
						$message .= 'Wir haben diese E-Mail als Antwort auf Ihre Aufforderung zum Zurücksetzen des Passworts in Baaba.de gesendet. Zum Zurücksetzen des Passworts klicken Sie bitte auf diesen Link <a href='.$url.'>hier klicken</a> oder indem Sie die folgende URL kopieren und in Ihren Browser einfügen ('.$url.') <br/> <br/>';
						$message .= 'Bitte zögern Sie nicht, uns unter zu kontaktieren '.CONTACT_US_ADMIN_EMAIL.' bei Fragen oder Bedenken. <br/>';
						$message .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
						$message .= 'Mit freundlichen Grüßen<br/>';
						$message .=  SITE_NAME.' Mannschaft';
					}
					$mailConfirm = $this->sendemail($to,$subject,$message);
					//pr($mailConfirm,1);
					if($mailConfirm){
						$user_data=array('forgot_verify_code'=>$token,'modify'=>date('Y-m-d H:i:s'));
						$this->db->where('id',$user_id);
						$result 	=   $this->db->update('tbl_usermaster',$user_data);	
						//echo $this->db->last_query();die;
						$this->session->set_flashdata('flashSuccess',$this->lang->line('password_reset_sent'));
						redirect('forgot-password');
						
					}else{
						$this->session->set_flashdata('flashError',$this->lang->line('something_went_wrong'));
						//redirect('home/partner-forgot-password');
					}					
				}else{
					$this->session->set_flashdata('flashError',$this->lang->line('not_registered'));
					//redirect('home/partner-forgot-password');
				}
				
			}
		}
		$language = get_site_language();
		$data['contact_details'] = $this->User_model->get_contact_details($language);
		
		$this->layout->view('web/forgot-password',$data);
	}

	
	public function verify_forgot_password($email,$token)
	{
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
						redirect('forgot-password-vefication/'.$email."/".$token);
					}else{						
						$update_data = array('password'=>md5($this->input->post('password')),'forgot_verify_code'=>'','email_varification_code'=>'','email_varification_status'=>'1','modify'=>date('Y-m-d H:i:s'));
						$this->commonmodel->_update('tbl_usermaster',$update_data,array('id'=> $emailDetails['id']));
						$this->session->set_flashdata('flashSuccess','Password changed successfully.You can login now.');
						redirect('login');
					}
				}else{
					$data['title'] = 'Reset password';
					$this->load->view('web/change-forgot-password',$data);
				}
			}else{
				$this->session->set_flashdata('flashError','This link is expired.Please try again by forgot password section.');
				redirect('forgot-password');
			}
		}else{
			$this->session->set_flashdata('flashError','You are not register with us.Please register first.');
			redirect('forgot-password');
		}
	}
	
	
	public function get_profile(){
		
		if($this->site_santry->is_web_login())
        {
			$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];

			if($this->input->post()) {
				$post = $this->input->post();
				//pr($post,1);
				$validation_post = array(
					array('field' => 'emailid','label' => 'Email','rules' => 'trim|required|callback__update_validate_user'),
					array('field' => 'first_name', 'label' =>'First Name', 'rules' => 'trim|required'),
					array('field' => 'last_name', 'label' =>'Last Name', 'rules' => 'trim|required'),
					array('field' => 'mobileno', 'label' =>'Mobile Number', 'rules' => 'trim|required'),
					array('field' => 'vat_number', 'label' =>'VAT Number', 'rules' => 'trim|required')
	            );
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules($validation_post);
				if ($this->form_validation->run() === TRUE) 
				{
					$this->session->set_flashdata('flashSuccess',$this->lang->line('updated_successfull'));				
				}
			}

			$data['user_details'] = $this->User_model->getUserData($user_id);
			$language = get_site_language();
			$data['contact_details'] = $this->User_model->get_contact_details($language);
			$this->layout->view('web/my-profile',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function _update_validate_user()
	{ 
		if($this->site_santry->is_web_login())
        {
			$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];

			$userdata['first_name'] = _input_post('first_name');
			$userdata['last_name'] = _input_post('last_name');
			$userdata['mobileno'] = _input_post('mobileno');
			$userdata['vat_number'] = _input_post('vat_number');
			$userdata['commercial_reg_no'] = _input_post('commercial_reg_no');

			$result = $this->commonmodel->_update('tbl_usermaster',$userdata,array('id'=>$user_id));
			$this->form_validation->set_message('_validate_user', $this->lang->line('updated_successfull'));
			return TRUE;
		}
		else
		{
			redirect('home');
		}	
	}

	public function change_password(){
		
		if($this->site_santry->is_web_login())
        {
			$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];

			if($this->input->post()) {
				$post = $this->input->post();
				//pr($post,1);
				$validation_post = array(
				  array('field' => 'old_password', 'label' =>'Old Password', 'rules' => 'trim|required'),
				  array('field' => 'password', 'label' =>'Password', 'rules' => 'trim|required'),
				  array('field' => 'confirm_password', 'label' =>'Confirm Password', 'rules' => 'required|matches[password]'),
	            );
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules($validation_post);
				if ($this->form_validation->run() === TRUE) 
				{
					$this->db->where('id',$user_id);
					$this->db->where('password',md5($post['old_password']));
					$check = $this->db->get('tbl_usermaster');
					if($check->num_rows()>0)
					{
						$detail = array(
										'password'		=> md5($post['password']),
									);
						$this->commonmodel->_update('tbl_usermaster', $detail, array('id'=>$user_id));
						$this->session->set_flashdata('flashSuccess','Password Has Been Updated Successfully ');
						redirect('change-password');
					}else{
						$this->session->set_flashdata('flashError','Incorrect Old Password!');
						redirect('change-password');
					}
				}
				else
				{
					$this->session->set_flashdata('flashError',validation_errors());
					redirect('change-password');
				}
			}

			$this->db->select('id,first_name, last_name, emailid, mobileno, vat_number, commercial_reg_no');
			$this->db->from('tbl_usermaster');
			$this->db->where('id',$user_id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			$data['user_details'] = $query->row_array();

			$this->layout->view('web/change-password',$data);
		}
		else
		{
			redirect('home');
		}			
	}

	public function update_product_cart(){

		$language = get_site_language();
		if($this->input->post())
		{
			if(!empty($this->input->post('productId')) && !empty($this->input->post('productPriceType'))  && !empty($this->input->post('prodQuantity')))
			{
				$product_id = base64_decode($this->input->post('productId'));
				$shop_type = base64_decode($this->input->post('productPriceType'));
				$product_qty = $this->input->post('prodQuantity');

				$product_details = $this->User_model->get_product_detail_by_id($product_id,$language);

				if(!empty($product_details))
				{
					$product_title = $product_details['product_title'];
					$product_image = $product_details['product_image'];
					$brand_title = $product_details['brand_title'];
					if($shop_type == 1)
					{
						$product_price = $product_details['single_product_price'];
						$product_discount = $product_details['single_product_offer'];
					}
					elseif($shop_type == 2)
					{
						$product_price = $product_details['master_carton_price'];
						$product_discount = $product_details['master_carton_offer'];
					}
					elseif($shop_type == 3)
					{
						$product_price = $product_details['palette_price'];
						$product_discount = $product_details['palette_offer'];
					}
					$product_discount = ($product_discount) ? $product_discount : 0;			

					$net_prd_price = ($product_discount) ? ($product_price-$product_discount) : $product_price;
					$total_amount = $net_prd_price*$product_qty;

					if($this->site_santry->is_web_login())
                    {
                    	$user_login_details = $this->site_santry->get_web_auth_data();
						$user_id = $user_login_details['id'];

                    	$order_details = $this->User_model->get_product_request_by_id($user_id,$product_id,$shop_type);

                    	if(!empty($order_details))
                    	{
                    		$product_qty = $product_qty+$order_details['quantity'];
                    		$this->User_model->update_order_request($user_id,$product_id,$shop_type,$product_qty);

                    		$total_order_item = $this->User_model->get_total_user_orders($user_id);
                    		$this->session->set_userdata('total_cart_products',$total_order_item);
                    	}
                    	else
                    	{
                    		$is_insert = $this->User_model->insert_order_request($user_id,$product_id,$shop_type,$product_qty);

                    		$total_order_item = $this->User_model->get_total_user_orders($user_id);
                    		$this->session->set_userdata('total_cart_products',$total_order_item);
                    	}
                    }
                    else
                    { 
                    	$order_id = rand(111,999).time();
                    	$order_data = array('order_id' => $order_id , 'product_id' => $product_id , 'shop_type' => $shop_type , 'quantity' => $product_qty, 'price' => $product_price , 'discount' => $product_discount, 'net_amount' => $net_prd_price, 'total_amount' => $total_amount, 'product_title' => $product_title, 'brand_title' => $brand_title , 'product_image' => $product_image);

                    	$is_product_found = 0;
                    	$order_details = $this->session->userdata('session_cart_details');
                    	if(!empty($order_details))
      					{
      						for ($i=0; $i < count($order_details); $i++) 
      						{ 
      							$order_product_id = $order_details[$i]['product_id'];
      							$order_shop_type = $order_details[$i]['shop_type'];
      							$order_quantity = $order_details[$i]['quantity'];
      							if($order_product_id == $product_id && $order_shop_type == $shop_type)
      							{
      								$product_qty = $product_qty+$order_quantity;
		      						$order_details[$i]['quantity'] = $product_qty;
		      						$order_details[$i]['total_amount'] = $net_prd_price*$product_qty;
		      						$is_product_found = 1;
      							}
      						}
      						
      					}

      					if($is_product_found == 0)
      					{
      						$order_details[] = $order_data;
      					}

      					$this->session->set_userdata("session_cart_details" , $order_details);
      					$total_order_item = count($order_details);
      					$this->session->set_userdata("total_cart_products",$total_order_item);
      					//$this->session->unset_userdata('session_cart_details');
                    }
				}
				else
				{
					$total_order_item = 0;
				}
			}
			else
			{
				$total_order_item = 0;
			}
			echo $total_order_item;
		}
	}

	public function update_cart_product_qty(){

		$language = get_site_language();
		$this->layout->set_layout("web/layout/inner");
		$user_order_array = array();
		if($this->input->post())
		{
			if(!empty($this->input->post('caerOrderQty')) && !empty($this->input->post('cartOrderId')))
			{
				$order_tbl_id = $this->input->post('cartOrderId');
				$order_neq_qty = $this->input->post('caerOrderQty');
				$data['tax_percent'] = $this->User_model->get_tax_percent();

				if($this->site_santry->is_web_login())
                {
                	$user_login_details = $this->site_santry->get_web_auth_data();
					$user_id = $user_login_details['id'];

                	$this->User_model->update_product_request_quantity($user_id,$order_tbl_id,$order_neq_qty);

                	$data['order_details'] = $this->User_model->get_order_request_details($user_id,$language);
					$total_order_item = count($data['order_details']);
  					$this->session->set_userdata("total_cart_products",$total_order_item);

                	$this->layout->view('web/my-cart-ajax',$data);
                }
                else
                { 
                	$order_details = array();
                	$order_details = $this->session->userdata('session_cart_details');

                	if(!empty($order_details))
  					{
  						for ($i=0; $i < count($order_details); $i++) 
  						{ 
  							$order_shop_id = $order_details[$i]['order_id'];
  							$product_id = $order_details[$i]['product_id'];
  							$shop_type = $order_details[$i]['shop_type'];

  							if($order_shop_id == $order_tbl_id)
  							{
  								$product_details = $this->User_model->get_product_detail_by_id($product_id,$language);
  								$product_discount = 0;

								if($shop_type == 1)
								{
									$product_price = $product_details['single_product_price'];
									$product_discount = $product_details['single_product_offer'];
								}
								elseif($shop_type == 2)
								{
									$product_price = $product_details['master_carton_price'];
									$product_discount = $product_details['master_carton_offer'];
								}
								elseif($shop_type == 3)
								{
									$product_price = $product_details['palette_price'];
									$product_discount = $product_details['palette_offer'];
								}

								$net_prd_price = $product_price-$product_discount;
								$total_amount = $net_prd_price*$order_neq_qty;

	      						$order_details[$i]['quantity'] = $order_neq_qty;
	      						$order_details[$i]['total_amount'] = $total_amount;
  							}
  						}  						
  					}

  					$this->session->set_userdata("session_cart_details" , $order_details);
  					$total_order_item = count($order_details);
  					$this->session->set_userdata("total_cart_products",$total_order_item);
  					$data['order_details'] = $order_details;
                	$this->layout->view('web/my-cart-ajax',$data);
                }
			}
		}
	}

	public function remove_product_cart(){

		$language = get_site_language();
		$user_order_array = array();
		if($this->input->post())
		{
			if(!empty($this->input->post('remove_order_id')))
			{
				$order_remove_id = base64_decode($this->input->post('remove_order_id'));

				if($this->site_santry->is_web_login())
                {
                	$user_login_details = $this->site_santry->get_web_auth_data();
					$user_id = $user_login_details['id'];

                	$order_details = $this->User_model->remove_product_request_by_id($user_id,$order_remove_id);

                	$total_order_item = count($order_details);
                	$this->session->set_userdata('total_cart_products',$total_order_item);
                }
                else
                { 
                	$order_details = array();
  					$old_order_details = $this->session->userdata('session_cart_details');
                	if(!empty($old_order_details))
  					{
  						for ($i=0; $i < count($old_order_details); $i++) 
  						{ 
  							$order_shop_id = $old_order_details[$i]['order_id'];

  							if($order_shop_id != $order_remove_id)
  							{
  								$order_details[] = $old_order_details[$i];
  							}
  						}  						
  					}

  					$this->session->set_userdata("session_cart_details" , $order_details);
  					$total_order_item = count($order_details);
  					$this->session->set_userdata("total_cart_products",$total_order_item);
                }
			}
		}
		redirect('my-cart');
	}

	public function get_cart_details(){

		$language = get_site_language();
		
		$data['order_details'] = array();
		if($this->site_santry->is_web_login())
        {
        	$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];
			$data['order_details'] = $this->User_model->get_order_request_details($user_id,$language);
			$total_order_item = count($data['order_details']);
  			$this->session->set_userdata("total_cart_products",$total_order_item);
		}
		else
		{
			$order_details = $this->session->userdata('session_cart_details');
			if(!empty($order_details))
      			$data['order_details'] = $order_details;
		}
		$data['tax_percent'] = $this->User_model->get_tax_percent();
		//print_r($data['tax_percent']); die;
		
		$data['contact_details'] = $this->User_model->get_contact_details($language);
		$this->layout->view('web/my-cart',$data);
	}

	public function checkout(){

		$language = get_site_language();
		$user_login_details = $this->site_santry->get_web_auth_data();
		$user_id = $user_login_details['id'];

		$billing_details = $this->session->userdata('session_order_billing_details');
		if(!empty($billing_details))
			$data['billing_details'] = $billing_details;

		$shipping_details = $this->session->userdata('session_order_shipping_details');
		if(!empty($shipping_details))
			$data['shipping_details'] = $shipping_details;

		$data['order_details'] = $this->User_model->get_order_request_details($user_id,$language);
		$data['tax_percent'] = $this->User_model->get_tax_percent();
		$language = get_site_language();
		$data['contact_details'] = $this->User_model->get_contact_details($language);
		$this->layout->view('web/checkout',$data);
	}

	public function update_billing_details(){

	    $language = get_site_language();
	    if($this->input->post())
	    {
			$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];

			$billing_first_name = $this->input->post('billing_first_name');
			$billing_last_name = $this->input->post('billing_last_name');
			$billing_company = $this->input->post('billing_company');
			$billing_email = $this->input->post('billing_email');
			$billing_address_1 = $this->input->post('billing_address_1');
			$billing_address_2 = $this->input->post('billing_address_2');
			$billing_city = $this->input->post('billing_city');
			$billing_zip = $this->input->post('billing_zip');
			$billing_country = $this->input->post('billing_country');
			$billing_telephone = $this->input->post('billing_telephone');
			$billing_mobile = $this->input->post('billing_mobile');
			$billing_fax = $this->input->post('billing_fax');

			$billing_address = $billing_first_name." ".$billing_last_name."<br>".$billing_company."<br>".$billing_address_1."<br>".$billing_address_2."<br>".$billing_city.", ".$billing_country." - ".$billing_zip."<br>E-Mail - ".$billing_email."<br>Telephone - ".$billing_telephone."<br>Mobile - ".$billing_mobile."<br>Fax - ".$billing_fax;

			$this->session->set_userdata("sessionOrderBillingInfo" , $billing_address);

			$billing_details = array('billing_first_name' => $billing_first_name , 'billing_last_name' => $billing_last_name , 'billing_company' => $billing_company , 'billing_email' => $billing_email , 'billing_address_1' => $billing_address_1 , 'billing_address_2' => $billing_address_2 , 'billing_city' => $billing_city , 'billing_zip' => $billing_zip , 'billing_country' => $billing_country , 'billing_telephone' => $billing_telephone , 'billing_mobile' => $billing_mobile , 'billing_fax' => $billing_fax);

			$this->session->set_userdata("session_order_billing_details" , $billing_details);

			//$this->db->where('user_id',$user_id);
			//$this->db->update('tbl_usermaster',$billing_details);

			print $billing_address;
	    }
  	}

	public function update_shipping_details(){

		$language = get_site_language();
		
		$language = get_site_language();
		if($this->input->post())
		{
			$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];

			$shipping_first_name = $this->input->post('shipping_first_name');
			$shipping_last_name = $this->input->post('shipping_last_name');
			$shipping_company = $this->input->post('shipping_company');
			$shipping_email = $this->input->post('shipping_email');
			$shipping_address_1 = $this->input->post('shipping_address_1');
			$shipping_address_2 = $this->input->post('shipping_address_2');
			$shipping_city = $this->input->post('shipping_city');
			$shipping_zip = $this->input->post('shipping_zip');
			$shipping_country = $this->input->post('shipping_country');
			$shipping_telephone = $this->input->post('shipping_telephone');
			$shipping_mobile = $this->input->post('shipping_mobile');
			$shipping_fax = $this->input->post('shipping_fax');

			$shipping_address = $shipping_first_name." ".$shipping_last_name."<br>".$shipping_company."<br>".$shipping_address_1."<br>".$shipping_address_2."<br>".$shipping_city.", ".$shipping_country." - ".$shipping_zip."<br>E-Mail - ".$shipping_email."<br>Telephone - ".$shipping_telephone."<br>Mobile - ".$shipping_mobile."<br>Fax - ".$shipping_fax;

			$this->session->set_userdata("sessionOrderShippingInfo" , $shipping_address);

			$shipping_details = array('shipping_first_name' => $shipping_first_name , 'shipping_last_name' => $shipping_last_name , 'shipping_company' => $shipping_company , 'shipping_email' => $shipping_email , 'shipping_address_1' => $shipping_address_1 , 'shipping_address_2' => $shipping_address_2 , 'shipping_city' => $shipping_city , 'shipping_zip' => $shipping_zip , 'shipping_country' => $shipping_country , 'shipping_telephone' => $shipping_telephone , 'shipping_mobile' => $shipping_mobile , 'shipping_fax' => $shipping_fax);

			$this->session->set_userdata("session_order_shipping_details" , $shipping_details);
			//$this->db->where('user_id',$user_id);
			//$this->db->update('tbl_usermaster',$billing_details);
			print $shipping_address;
		}
	}

	public function place_order(){

		$language = get_site_language();
		$user_login_details = $this->site_santry->get_web_auth_data();
		$user_id = $user_login_details['id'];

		$billing_address = $this->session->userdata("sessionOrderBillingInfo");
		$shipping_address = $this->session->userdata("sessionOrderShippingInfo");

		$order_details = $this->User_model->get_order_request_details($user_id,$language);

		if(!empty($order_details))		
		{
			$order_id = $this->User_model->insert_order($user_id);
			$total_order_amount = 0;
            $total_order_discount = 0;
            $total_order_net_amount = 0;
			foreach ($order_details as $orders) 
            {
            	$quantity = $orders['quantity'];
            	$price = $orders['price'];
            	$net_amount = $orders['net_amount'];
            	$shop_type = $orders['shop_type'];
            	$total_amount = $orders['total_amount'];
            	$product_id = $orders['product_id'];
            	$discount = $orders['discount'];

            	$orderInfo = array('order_id' => $order_id , 'user_id' => $user_id , 'product_id' => $product_id , 'quantity' => $quantity , 'price' => $price , 'discount' => $discount , 'net_amount' => $net_amount , 'total_amount' => $total_amount , 'shop_type' => $shop_type , 'created_on'=>date("Y-m-d H:i:s") , 'modify_date'=>date("Y-m-d H:i:s"));

                $total_order_amount = $total_order_amount+($price*$quantity);
                $total_order_discount = $total_order_discount+($discount*$quantity);
                $total_order_net_amount = $total_order_net_amount+$total_amount;

                $this->User_model->insert_order_details($user_id,$orderInfo);
            }

            $tax_percent = $this->User_model->get_tax_percent();
            $total_tax_amount = $total_order_net_amount*($tax_percent/100); 
            $total_order_grant_total = $total_order_net_amount+$total_tax_amount;

            $payment_mode = 'Cash on Delivery';
            $billing_details = $this->session->userdata("session_order_billing_details");
            extract($billing_details);

            $shipping_details = $this->session->userdata("session_order_shipping_details");
            extract($shipping_details);

            $updateInfo = array('amount' => $total_order_amount , 'discount' => $total_order_discount , 'net_amount' => $total_order_net_amount , 'tax' => $tax_percent , 'tax_amount' => $total_tax_amount , 'order_amount' => $total_order_grant_total , 'payment_mode' => $payment_mode , 'billing_first_name' => $billing_first_name , 'billing_last_name' => $billing_last_name , 'billing_company' => $billing_company , 'billing_email' => $billing_email , 'billing_address_1' => $billing_address_1 , 'billing_address_2' => $billing_address_2 , 'billing_city' => $billing_city , 'billing_zip' => $billing_zip , 'billing_country' => $billing_country , 'billing_telephone' => $billing_telephone , 'billing_mobile' => $billing_mobile , 'billing_fax' => $billing_fax,'shipping_first_name' => $shipping_first_name , 'shipping_last_name' => $shipping_last_name , 'shipping_company' => $shipping_company , 'shipping_email' => $shipping_email , 'shipping_address_1' => $shipping_address_1 , 'shipping_address_2' => $shipping_address_2 , 'shipping_city' => $shipping_city , 'shipping_zip' => $shipping_zip , 'shipping_country' => $shipping_country , 'shipping_telephone' => $shipping_telephone , 'shipping_mobile' => $shipping_mobile , 'shipping_fax' => $shipping_fax);
            $this->User_model->update_order($order_id,$updateInfo);

            $this->User_model->remove_order_request($user_id);

            $orders = $this->User_model->get_order_by_order_id($order_id);

            if(!empty($orders))
            {   
                $message = '<table width="100%">
                    <tbody>
                        <tr>
                        	<td width="100%"> 
                            	<table width="100%">
                                    <tr>
                                        <td colspan="2">
                                            <table width="100%">
                                                <thead>
                                                    <tr>
                                                        <th align="left">'.$this->lang->line('transaction_id').'</th>
                                                        
                                                        <th align="left">'.$this->lang->line('billing_information').'</th>
                                                        <th align="left">'.$this->lang->line('shipping_information').'</th>
                                                        <th align="right">'.$this->lang->line('total_amount').'</th>
                                                        <th align="right">'.$this->lang->line('discount').'</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td width="50%" align="left">
                                           				 <span class="order-id">'.$orders["transaction_id"].'</span>
                                        				</td>
                                        				<td>
                                                            <div><strong>'.$orders['billing_first_name'].' '.$orders['billing_last_name'].'</strong><br>
                                                                    E-mail : '.$orders['billing_email'].'<br>
                                                                    Mobile : '.$orders['billing_mobile'].'<br>
                                                                    '.$orders['billing_address_1'].' '.$orders['billing_address_2'].'<br>'.$orders['billing_city']." ".$orders['billing_country']." - ".$orders['billing_zip'].'
                                                            </div>
                                                        </td>
                                                        <td>
                                                        	<strong>'.$orders['shipping_first_name']." ".$orders['shipping_last_name'].'</strong><br>
                                                                E-mail : '.$orders['shipping_email'].'<br>
                                                                Mobile : '.$orders['shipping_mobile'].'<br>
                                                                '.$orders['shipping_address_1']." ".$orders['shipping_address_2']."<br>".$orders['shipping_city']." ".$orders['shipping_country']." - ".$orders['shipping_zip'].'
                                                        </td>
                                                        <td align="right">$ '. number_format($orders['amount'],2).'</td>
                                                        <td align="right">$ '.number_format($orders['discount'],2).'</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                    <td colspan="2"> 
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>'.$this->lang->line('product').'</th>
                                                    <th>'.$this->lang->line('price').'</th>
                                                    <th>'.$this->lang->line('net_price').'</th><th>'.$this->lang->line('discount').'</th><th>'.$this->lang->line('quantity').'</th><th>'.$this->lang->line('total_amount').'</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                            
                                                foreach ($order_details as $odetails) 
                                                {   
                                                  $message .= '<tr>
                                                    <td>
                                                        <div class="prod-desc">
                                                            <a target="_blank" href="'.base_url('product-detail/'.base64_encode($odetails['product_id'])).'">
                                                            	<img style="width: 100px; height: auto;" src="'.UPLOADS_PATH.'/products/'. $odetails['product_image'].'" alt="'. $odetails['product_title'].'">'.$odetails['product_title'].'
                                                            </div>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    
                                                    <td align="right">$ '.number_format($odetails['price'],2).'</td>
                                                    <td align="right">$ '. number_format($odetails['discount'],2).'</td>
                                                    <td align="right">$ '. number_format($odetails['net_amount'],2).'</td>
                                                    <td align="center">'. $odetails['quantity'].'</td>
                                                    <td align="right">$ '. number_format($odetails['total_amount'],2).'</td>
                                                  </tr>'; 
                                                } 
                                            $message .= '</tbody>
                                        </table>
                                    </td>
                                	</tr>
                                    <tr>
                                        <td>
                                            <label>'.$this->lang->line('date').' :</label>
                                            <span>'. date('D, jS M Y' , strtotime($orders['created_on'])).'</span>
                                        </td>
                                        <td align="right">
                                            <label>'.$this->lang->line('total_order_amount').' :</label>
                                            <span class="price">$ '. number_format($orders['net_amount'],2).'</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>';
            } 

            $subject = "Order Request";
            //print $message;
            $mailConfirm = $this->sendemail($billing_email,$subject,$message);

            $this->session->unset_userdata('session_cart_details');
            $this->session->unset_userdata('session_order_billing_details');
            $this->session->unset_userdata("session_order_shipping_details");
            $this->session->unset_userdata("total_cart_products");
            $this->session->unset_userdata("total_cart_products");
			$this->session->unset_userdata("sessionOrderBillingInfo");
			$this->session->unset_userdata("sessionOrderShippingInfo");

            redirect('my-orders');
		}		
	}
	
	public function get_orders(){

		if($this->site_santry->is_web_login())
        {
			$data['title'] = "Baaba.de - My Orders";

			$user_login_details = $this->site_santry->get_web_auth_data();
			$user_id = $user_login_details['id'];

			$data['order_details'] = $this->User_model->getUserOrders($user_id);
			$language = get_site_language();
			$data['contact_details'] = $this->User_model->get_contact_details($language);
			$this->layout->view('web/my-orders',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function get_order_details($order_id)
	{
		if($this->site_santry->is_web_login())
        {
			$order_id = base64_decode($order_id);
			$language = get_site_language();

			$data['orders'] = $this->User_model->get_order_by_order_id($order_id);
			$data['order_details'] = $this->User_model->get_order_details_by_order_id($language,$order_id);
			$data['contact_details'] = $this->User_model->get_contact_details($language);
			$this->layout->view('web/order-details',$data);
		}
		else
		{
			redirect('home');
		}
	}







	#------------------------- Common Function ------------------------------
	
	public function testemail()
	{
		$to='websofttech.jaipur@gmail.com';
		$subject=SITE_NAME;
		$message='Test mail from '.SITE_NAME;
		$this->sendemail($to,$subject,$message);
	}
	public function sendemail($to,$subject,$upd_msg)
	{
		$message = '<table width="800"><tr><td style="background:#e7e7e7; padding:10px;"><center><a class="navbar-brand" href="'.base_url('home').'"><img style="width:auto; height:100px;" src="'.WEB_PATH.'/images/logo.png" alt="Logo" /></a></center></td></tr><tr><td style="background:#f1f1f1; padding:10px;">'.$upd_msg.'</td></tr></table>';

		require_once('smtp/class.phpmailer.php');

		$mail = new PHPMailer();  // create a new object
	    $mail->IsSMTP(); // enable SMTP
	    $mail->protocol = "mail";
	    $mail->SMTPDebug =1;  // debugging: 1 = errors and messages, 2 = messages only
	    $mail->SMTPAuth = true;  // authentication enabled
	    $mail->SMTPSecure = SMTP_SECURE; // secure transfer enabled REQUIRED for Gmail
	    $mail->Host = HOST_NAME;
	    $mail->CharSet = 'utf-8';
	    $mail->Port = SMTP_PORT;
	    $mail->Username = USER_NAME;
	    $mail->Password = SMTP_PASSWORD;
	    $mail->SetFrom(FROM_MAIL,FROM_NAME);
	    $mail->Subject = $subject;          
	    $mail->Body = $message;
	    $mail->IsHTML(true); 
	    $mail->AddAddress($to);

	    //send the message, check for errors
	    if(!$mail->Send()) {
	      $error = 'Mail error: '.$mail->ErrorInfo;
	      return false;               
	    } else {
	      $error = 'Message sent!';
	      return true;      
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
	
	public function getLoginKey($user_id)
	{
		$salt = "23df$#%%^66sd$^%fg%^sjgdk90fdklndg099ndfg09LKJDJ*@##lkhlkhlsa#$%";
		$login_key = hash('sha1',$salt.$user_id);
		//print_r($login_key);die;
		return $login_key;
	}
	
	/* to change site language  */
	public function change_language($sel_language=""){
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		if($sel_language){
			$selected_lang	 	= change_site_language($sel_language);
			$siteLang		 	= $this->session->userdata('site_lang');
			//print_r($siteLang);die;
			$data['sel_lang'] 	= $selected_lang;
			//$this->layout->view("admin/dashboard", $data);
			
		}
		redirect($referrer);
	}
	/* end of change site language  */
	
}

/* End of file welcome.php */
