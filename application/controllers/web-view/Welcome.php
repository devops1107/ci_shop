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
		
		/* $user_type = $this->site_santry->get_auth_data('user_type');
		if($user_type=='agent' || $user_type=='customer'){
			redirect('welcome');
		} */
	}

	public function index(){
		//echo "hello";die;
		redirect('welcome/login');
	} 

	public function login()
	{
		//echo "hello";die;
		if($this->site_santry->is_login())
		{
			redirect('admin/users');
		}
		
		if($this->input->post()) {
			$validation_post = array(
               	 array('field' => 'email','label' => 'email','rules' => 'trim|required')
				, array('field' => 'password', 'label' =>'password', 'rules' => 'trim|required|callback__validate_user')
                );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$this->session->set_flashdata('flashSuccess','Login Successfully.');
				redirect('admin/dashboard');
			}
		}
		$data['title'] ='Sales Management';
	
		$this->layout->view("admin/login", $data);
	}

	public function _validate_user()
	{
		
		$email 		= _input_post('email');
		$password 	= _input_post('password');
	
		$login_time = date("Y-m-d H:i:s");
		
		$adminResult = $this->db->select("user_id,role,user_email,user_pass,user_type,device_type,device_id,user_delete,view_order_menu")
								->where("`user_email` = '" .addslashes($email). "'")
								->where("`user_delete` = '0' ")
								->get("admin_users");
		if ($adminResult->num_rows() > 0) {
			$AdResult = $adminResult->row();
			
			if ($AdResult->user_pass == md5($password) && $AdResult->user_type=='admin' && $AdResult->user_delete=='0') {
					$data = array(
									'id'				=>	$AdResult->user_id,
									'user_type'			=>	$AdResult->user_type,
									'role'			    =>	$AdResult->role,
									//'user_permission'	=>	$AdResult->admin_permission,
									'view_order_menu'	=>	$AdResult->view_order_menu,
									'device_type'		=>	$AdResult->device_type,
									'device_id'			=>	$AdResult->device_id,
									'email'				=>	$AdResult->user_email,
									
								);
						//print_r($data);die;
					$detail=array(
						'soft_user_id' 		=> $AdResult->user_id,
						'soft_ip'			=> $_SERVER["REMOTE_ADDR"],
						'soft_date_time'	=> date('Y-m-d H:i:s'),
						'soft_log_status'	=> 'login'
					);
					
					
					$this->db->insert('soft_login_logout',$detail);	
					$login_key = $this->random_key();
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);
					
					$this->site_santry->do_login($login_data);
					
					$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key);
					$this->db->where('user_id',$AdResult->user_id);
					$this->db->update('admin_users', $data); 
				
					return TRUE;
						
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
									
					$detail =   array(
						'soft_user_id' 	=> $AdResult->user_id,
						'soft_ip'		=> $_SERVER["REMOTE_ADDR"],
						'soft_date_time'=> date('Y-m-d H:i:s'),
						'soft_log_status'=> 'login'
					);
					
					$this->db->insert('soft_login_logout',$detail);
					$login_key = $this->random_key();
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);				
					$this->site_santry->do_login($login_data);
					
					$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key);
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
					$login_key = $this->random_key();
					$lgkey = array(
					     'login_key' => $login_key,
					);
					$login_data = array();
					$login_data = array_merge($lgkey,$data);				
					$this->site_santry->do_login($login_data);
					$data = array('admin_last_login_dt' => $login_time,'login_key'=>$login_key);
					$this->db->where('user_id',$AdResult->user_id);
					$this->db->update('admin_users', $data); 
					return TRUE;
					
				}else{
					$this->form_validation->set_message('_validate_user', 'Invalid Password');
					return FALSE;
				}
				
			}
			$this->form_validation->set_message('_validate_user', 'Invalid email');
			return FALSE;
		}else{
			$adminResult = $this->db->select("user_id,role,user_email,user_pass,user_type,device_type,device_id,user_delete")
								->where("`user_email` = '" .addslashes($email). "'")
								->where("`user_delete` = '1' ")
								->get("admin_users");
				if($adminResult->num_rows() > 0)
				{
					$this->form_validation->set_message('_validate_user', 'Your account is deleted.Please contact with admin.');
					return FALSE;
				}else{
					$this->form_validation->set_message('_validate_user', 'You are not register.Please contact with admin.');
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
		redirect('admin');
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
					$signup_reward_details = $this->getSignupRewards();
				    // echo 'success'; die;
					$user_tokens = $emailDetails['user_tokens'];
					if($emailDetails['refferer_code']!="" && $emailDetails['refferer_user_id']!=""){
						$user_tokens   		= $signup_reward_details['signup_reward_tokens']+$signup_reward_details['refferal_reward_tokens'];
					}else{
						$user_tokens   		= $signup_reward_details['signup_reward_tokens'];
					}
					$update_data = array('email_varification_status' =>'1','modify'=>date("Y-m-d H:i:s"));
					$this->db->where('emailid',$emailid);
					$result = $this->db->update('tbl_usermaster',$update_data);
					
					$msg = 'user_register_success, Email verification successfull.You can login now.';
					$this->session->set_flashdata('flashSuccess','Email verification successfull.You can login now..');
					redirect('login');
				}else{
				   	$this->session->set_flashdata('flashError','You are already verified.');
					redirect('home/login');
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
	
	public function getSignupRewards(){
		$this->db->select('refferal_reward_tokens,signup_reward_tokens');
		$this->db->from('settingmaster');
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	
	public function verify_forgot_password($email,$token)
	{
		$condition = array('emailid'=>$email,'status'=>'1','delete_status'=>'0');
		$details = $this->db->select('*')->from('tbl_usermaster')->where($condition)->get();
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
						$this->commonmodel->_update('tbl_usermaster',$update_data,array('user_id'=> $emailDetails['user_id']));
						$this->session->set_flashdata('flashSuccess','Password changed successfully.You can login now.');
						redirect('admin');
					}
				}else{
					$data['title'] = 'Reset password';
					$this->layout->view('web/change_forgot_password_user',$data);
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
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */