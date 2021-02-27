<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		if(!($this->site_santry->is_web_login()))
		{
			redirect('home/login');
		}
		$language = get_site_language();
		$this->language = $language;
		$this->lang->load('all_web_content_lang',$language);
		$this->lang->load('api_messages_lang',$language);
		
		$this->layout->set_layout("web/layout/inner");
		$this->load->model('web/User_model');
		//$this->load->model('User_model','Api_model');
		
	}
	
	public function getUserData($user_id){
		//$this->db->select('id,firstname,lastname,username,emailid,mobileno,profileimage,address,address_2,city,state,zip_code,referral_code,user_tokens,is_irs_confirm_status');
		$this->db->select('id,full_name,username,emailid,profileimage,user_tokens as available_amount,paypal_id');
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
			$userData['available_amount']	= ($userData['available_amount']===NULL)?"0.00":$userData['available_amount'];
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
	
	
	
	public function getAvailableBalance($user_id){
		//pr($user_id,1);
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$userData = $this->getUserData($user_id);
			$available_amount 	=	$userData['available_amount'];
			return $available_amount;
		}else{
			return '0.00';
		}
	}
	
	public function like_news_comment(){
		if($this->input->post()){
			$post = $this->input->post();
			$news_comment_id_ecoded = $post['comment_id'];
			$news_comment_id = base64_decode($news_comment_id_ecoded);
			$type = $post['type'];
			$user_id = $this->site_santry->get_web_auth_data('id');
			//pr($user_id,1);
			$result = $this->User_model->likeDislikeOnComment($user_id,$news_comment_id,$type);
			//pr($result,1);
			echo json_encode($result);
		}
	}
	
	public function recharge_credit(){
		$data['title'] = "Recharge Credit";
		$user_id = $this->site_santry->get_web_auth_data('id');
		
		$user_detail 		= $this->getUserData($user_id);
		$wallet_balance = $user_detail['available_amount'];
		if(!empty($this->input->post())){
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				array('field' => 'amount','label' => 'Credit Amount','rules' => 'trim|required')
            );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$amount = $post['amount'];
				$updated_user_tokens = $user_detail['available_amount']+$amount;
				//pr($updated_user_tokens,1);
				$userData		= array('user_tokens'=>$updated_user_tokens,'modify'=>date('Y-m-d H:i:s'));
				$this->db->where('id',$user_id);
				$result 	=   $this->db->update('tbl_usermaster',$userData);
				
				$token_details = array(
					'user_id'		 	=> 	$user_id,
					'tokens'			=>	$amount,
					'type'				=>	'RECHARGED',
					'is_claim_allowed'	=>	'0',
					'created_on'		=>	date('Y-m-d H:i:s'),
					'modified_on'		=>	date('Y-m-d H:i:s'),
				);
				$saveTokenData 	= 	$this->db->insert('token_purchases',$token_details);
				$userData		=	$this->getUserData($user_id);
				$wallet_balance 		= $userData['available_amount'];
				$this->session->set_flashdata('flashSuccess',$this->lang->line('recharged_successfully'));
				redirect('home/recharge-credit');
			}
		}
		$data['wallet_balance'] = $wallet_balance;
		$this->layout->view('web/recharge-credits',$data);
	}
	
	public function getUserNotifications(){
		$user_id = $this->site_santry->get_web_auth_data('id');
		$user_details = $this->getUserData($user_id);
		$this->db->select('un.*,r.room_image,r.entry_key');
		$this->db->from('user_notifications un');
		$this->db->join('rooms r','un.room_id=r.room_id','left');
		$this->db->where('un.user_id',$user_id);
		$this->db->where('un.status','1');
		$this->db->where('un.delete_status','0');
		$this->db->order_by('un.created_on','DESC');
		//$this->db->group_by('rw.room_id');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			//pr($result,1);
			$final_response = array();
			foreach($result as $row){
				$temp_arr = array();
				//$temp_arr['user_notification_id'] 			= ($row['user_notification_id']===NULL)?"":$row['user_notification_id'];
				$temp_arr['action'] 			= ($row['action']===NULL)?"":$row['action'];
				$temp_arr['user_id'] 			= ($row['user_id']===NULL)?"":$row['user_id'];
				$temp_arr['dateTime'] 			= ($row['created_on']===NULL)?"":$row['created_on'];
				if($row['action']=="ENTRY_KEY_ENTERED"){
					$temp_arr['title'] 				= "Key Generated";
					$temp_arr['description'] 		= "Hello ".$user_details['full_name'].", Key has been generated. The key is ".$row['entry_key']."";
					$temp_arr['url']                = base_url('home/rooms-detail/').base64_encode($row['room_id']);
				}else if($row['action']=="WINNER_ANNOUNCED"){
					$temp_arr['title'] 				= "Winner Announced";
					$temp_arr['description'] 		= "Hello ".$user_details['full_name'].", Winner has been announced";
					$temp_arr['url']                = base_url('home/all-matches');
				}else if($row['action']=="PAYMENT_TRANSFERED"){
					$temp_arr['title'] 				= "Payment Transfered";
					$temp_arr['description'] 		= "Hello ".$user_details['full_name'].", winning request amount has been transfered";
					$temp_arr['url']                = base_url('home/recharge-credit');
				}else if($row['action']=="WALLET_RECHARGED"){
					$temp_arr['title'] 				= "Wallet Recharged";
					$temp_arr['description'] 		= "Hello ".$user_details['full_name'].",your wallet has been recharged please check it.";
					$temp_arr['url'] = base_url('recharge-credit');
				}else{
					$temp_arr['title'] = "";
					$temp_arr['description'] = "";
					$temp_arr['url'] = "";
				}
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$row['room_image']) && $row['room_image']!='')
				{
					$temp_arr['room_image'] = UPLOAD_URL.'rooms/'.$row['room_image'];
				}else{
					$temp_arr['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
			
				$final_response[] =  $temp_arr;
			}
			$data['user_logs'] 				= 	$final_response;
			//$response['result'] 			= 	$final_response;
			
			//print_r($result);
		}else{
			$data['user_logs']	= array();
		}
		$this->layout->view('web/notification',$data);
	}
	
	public function my_profile(){
		$data['title'] = "My Profile";
		$user_id = $this->site_santry->get_web_auth_data('id');
		$emailid = $this->site_santry->get_web_auth_data('email');
		$username = $this->getUserData($user_id)['username'];
		if($this->input->post()){
			$post = $this->input->post();
			if(isset($post['new_password']) && $post['new_password']!=""){
				$validation_post = array(
					array('field' => 'new_password','label' => 'New Password','rules' => 'trim|required'),
					array('field' => 'confirm_new_password','label' => 'Confirm New Password','rules' => 'trim|required|matches[new_password]'),
					array('field' => 'full_name','label' => 'In Game Name','rules' => 'trim|required'),
				);
			}else{
				$validation_post = array(
					array('field' => 'full_name','label' => 'In Game Name','rules' => 'trim|required'),
				);
			}
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$details = array();
				if(isset($post['new_password']) && $post['new_password']!=""){
					$details['password']	=	md5($post['new_password']);
				}
				$details['full_name']	=	$post['full_name'];
				$details['username']	=	$post['full_name'];
				$this->site_santry->update_web_auth_data(array('username'=>$details['username']));
				$details['modify']		=	date('Y-m-d H:i:s');
				//pr($details,1);
				$result = $this->commonmodel->_update('tbl_usermaster',$details,array('id'=>$user_id));
				$this->session->set_flashdata('flashSuccess','Your profile has been updated successfully.');
				redirect('home/my-profile');
			}
		}
		$data['user_email'] = $emailid;
		$data['username'] = $username;
		$this->layout->view('web/my-profile',$data);
	}
	
	public function account_details(){
		$data['title'] = "Account Details";
		$user_id = $this->site_santry->get_web_auth_data('id');
		if($this->input->post()){
			$post = $this->input->post();
			//pr($post,1);
			if($post['type'] == "account"){
				$validation_post = array(
					array('field' => 'card_number','label' => 'Card Number','rules' => 'trim|required'),
					array('field' => 'confirm_card_number','label' => 'Confirm Card Number','rules' => 'trim|required|matches[card_number]'),
					array('field' => 'bank_name','label' => 'Bank Name','rules' => 'trim|required'),
					array('field' => 'security_code','label' => 'IFSC Code','rules' => 'trim|required'),
					array('field' => 'card_holder_name','label' => "Account Holder's Name",'rules' => 'trim|required'),
				);
			}else if($post['type'] == "paypal"){
				$validation_post = array(
					array('field' => 'paypal_id','label' => 'PayPal ID','rules' => 'trim|required'),
				);
			}
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				if($post['type'] == "account"){
					$card_number = $post['card_number'];
					$this->db->select('*');
					$this->db->from('user_account_details');
					$this->db->where('customer_id',$user_id);
					 $this->db->where('card_number',$post['card_number']); 
					$this->db->where('acc_status','1');
					$this->db->where('acc_delete_status','0');
					$query3 = $this->db->get();
					if($query3->num_rows() > 0)
					{
						$account_data = $query3->row_array();
						$account_id = $account_data['account_id'];
						$acc_detail = array(
							'bank_name'=>$post['bank_name'],
							'card_number'=>$post['card_number'],
							/*'expiry_month'=>$expiry_month,
							'expiry_year'=>$expiry_year,
							'cvv_number'=>$cvv_number,*/
							'card_holder_name'=>$post['card_holder_name'],
							'security_code'=>$post['security_code'],
							'modified_on'=>date('Y-m-d H:i:s')
						);
						$result 	= $this->db->update('user_account_details',$acc_detail,array('account_id'=>$account_id));
						$account_detail = $this->funGetCardDetails($account_id);
						$this->session->set_flashdata('flashSuccess',$this->lang->line('card_details_updated'));
						redirect('home/account-details');
						
					}else{
						$detail = array(
								'customer_id'=>$user_id,
								'bank_name'=>$post['bank_name'],
								'card_number'=>$post['card_number'],
								/*'expiry_month'=>$expiry_month,
								'expiry_year'=>$expiry_year,
								'cvv_number'=>$cvv_number,*/
								'card_holder_name'=>$post['card_holder_name'],
								'security_code'=>$post['security_code'],
								'created_on'=>date('Y-m-d H:i:s')
						);
						$result 	= $this->db->insert('user_account_details',$detail);
						$account_id = $this->db->insert_id();
						$account_detail = $this->funGetCardDetails($account_id);
						$this->session->set_flashdata('flashSuccess',$this->lang->line('bank_account_added'));
						redirect('home/account-details');
					}
				}else{
					$paypal_id = $post['paypal_id'];
					$this->db->where('id',$user_id);
					$updateData = $this->db->update('tbl_usermaster',array('paypal_id'=>$paypal_id,'modify'=>date('Y-m-d H:i:s')));
					$this->session->set_flashdata('flashSuccess','Paypal Id have been updated successfully!');
					redirect('home/account-details');
				}
			}
		}
		$user_account_Details = $this->GetCardDetailsbyUserId($user_id);
		$data['user_account_Details']  = $user_account_Details;
		$data['user_details'] = $this->getUserData($user_id);
		//pr($data,1);
		$this->layout->view('web/account-details',$data);
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
			$account_details = $query2->result_array();
			foreach ($account_details as $key => $account_detail) 
			{	
				$tempArr = array();
				$tempArr['account_id'] 			= ($account_detail['account_id']===NULL)?"":$account_detail['account_id'];
				$tempArr['bank_name'] 			= ($account_detail['bank_name']===NULL)?"":$account_detail['bank_name'];
				$length = strlen($account_detail['card_number']);
				$output = substr_replace($account_detail['card_number'], str_repeat('X', $length - 4), 0, $length - 4);
				$tempArr['account_number'] 			= ($account_detail['card_number']===NULL)?"":$output;
				/*$tempArr['expiry_month'] 		= ($account_detail['expiry_month']===NULL)?"":$account_detail['expiry_month'];
				$tempArr['expiry_year'] 			= ($account_detail['expiry_year']===NULL)?"":$account_detail['expiry_year'];
				$tempArr['cvv_number'] 			= ($account_detail['cvv_number']===NULL)?"":$account_detail['cvv_number'];*/
				$tempArr['card_holder_name'] 	= ($account_detail['card_holder_name']===NULL)?"":$account_detail['card_holder_name'];
				$tempArr['ifsc_code'] 		= ($account_detail['security_code']===NULL)?"":$account_detail['security_code'];
				//$tempArr['is_default'] 			= ($account_detail['is_default']===NULL)?"":$account_detail['is_default'];
				$final_response[] = $tempArr;
			}
		}
		/*else{
			$final_response['account_id'] 			= "";
			$final_response['bank_name'] 			= "";
			$final_response['account_number'] 			= "";
			$final_response['expiry_month'] 		= "";
			$final_response['expiry_year'] 			= "";
			$final_response['cvv_number'] 			= "";
			$final_response['card_holder_name'] 	= "";
			$final_response['ifsc_code'] 		= "";
			//$final_response['is_default'] 			= "";
		}*/
		return $final_response;
	}
	
	public function getMyUpcomingMatchList($user_id,$limit,$offset){
		$this->db->select('jm.*,rm.category_id,rm.room_name,rm.room_name_gr,rm.room_image,rm.short_description,rm.start_datetime,rm.grand_loot_price_value,rm.available_tickets,rm.secoundry_prize_value,rm.third_price_value,rm.event_completed,rm.price_name,rm.per_ticket_tokens,getTotalSoldTickets(rm.room_id) as users_purchased_ticket,c.category_name');
		$this->db->from('joined_matches jm');
		$this->db->join('rooms rm','rm.room_id=jm.room_id','left');
		$this->db->join('categories c','c.category_id=rm.category_id','left');
		$this->db->where('jm.user_id',$user_id);
		$this->db->where('rm.event_completed','0');
		$this->db->where('jm.status','1');
		$this->db->where('jm.delete_status','0');
		$this->db->order_by('jm.created_on','DESC');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		$final_response = array();
		if($query->num_rows()>0){
			$my_matches = $query->result_array();
			//pr($my_matches,1);
			foreach($my_matches as $my_match){
				$userResponse = array();
				$userResponse['room_id'] 			=	 ($my_match['room_id']===NULL)?'':$my_match['room_id'];
				$userResponse['category_id'] 		=	 ($my_match['category_id']===NULL)?'':$my_match['category_id'];
				$userResponse['category_name'] 		=	 ($my_match['category_name']===NULL)?'':$my_match['category_name'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$my_match['room_image']) && $my_match['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$my_match['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
				if($this->language=='english')
				{
					$roomName = $my_match['room_name'];
				}else{
					$roomName = $my_match['room_name_gr'];
				}
				$userResponse['room_name'] 			=	 ($my_match['room_name']===NULL)?'':$roomName;
				$userResponse['entry_fees'] 		=	 ($my_match['tokens']===NULL)?'':$my_match['tokens'];
				$userResponse['start_datetime']		=	 ($my_match['start_datetime']==="0000-00-00 00:00:00")?'NA':date('d M Y h:i A',strtotime($my_match['start_datetime']));
				$userResponse['winning_price_first'] =	 ($my_match['grand_loot_price_value']===NULL)?'0':$my_match['grand_loot_price_value'];
				$userResponse['winning_price_second'] =	 ($my_match['secoundry_prize_value']===NULL)?'0':$my_match['secoundry_prize_value'];
				$userResponse['winning_price_third'] =	 ($my_match['third_price_value']===NULL)?'0':$my_match['third_price_value'];
				$userResponse['room_type'] 			=	 ($my_match['category_name']===NULL)?'':$my_match['category_name'];
				$userResponse['total_seats'] 		=	 ($my_match['available_tickets']===NULL)?'':$my_match['available_tickets'];
				$userResponse['total_enterd_users'] =	 ($my_match['users_purchased_ticket']===NULL)?'':$my_match['users_purchased_ticket'];
				$userResponse['remaining_tickets'] 	=	 ($my_match['users_purchased_ticket']===NULL)?'':$my_match['available_tickets']-$my_match['users_purchased_ticket'];
				//$userResponse['organized_by'] 		=	 ($my_match['organized_by']===NULL)?'':$my_match['organized_by'];
				$userResponse['entry_key'] 			=	 '';
				
				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response = $final_response;
		}else{
			$response = $final_response;
			
		}
		return $response;
		
	}
	
	public function getMyCompletedMatchList($user_id,$limit,$offset){
		$this->db->select('jm.*,rm.category_id,rm.room_name,rm.matchVideoURL,rm.room_name_gr,rm.room_image,rm.short_description,rm.start_datetime,rm.grand_loot_price_value,rm.available_tickets,rm.secoundry_prize_value,rm.third_price_value,rm.event_completed,rm.price_name,rm.per_ticket_tokens,getTotalSoldTickets(rm.room_id) as users_purchased_ticket,c.category_name');
		$this->db->from('joined_matches jm');
		$this->db->join('rooms rm','rm.room_id=jm.room_id','left');
		$this->db->join('categories c','c.category_id=rm.category_id','left');
		$this->db->where('jm.user_id',$user_id);
		$this->db->where('rm.event_completed','1');
		$this->db->where('jm.status','1');
		$this->db->where('jm.delete_status','0');
		$this->db->order_by('jm.created_on','DESC');
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		$final_response = array();
		if($query->num_rows()>0){
			$my_matches = $query->result_array();
			//pr($my_matches,1);
			foreach($my_matches as $my_match){
				$userResponse = array();
				$userResponse['room_id'] 			=	 ($my_match['room_id']===NULL)?'':$my_match['room_id'];
				$userResponse['category_id'] 		=	 ($my_match['category_id']===NULL)?'':$my_match['category_id'];
				$userResponse['category_name'] 		=	 ($my_match['category_name']===NULL)?'':$my_match['category_name'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$my_match['room_image']) && $my_match['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$my_match['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
					/*if(is_file(UPLOAD_PHYSICAL_PATH.'match-video/'.$my_match['matchVideoURL']) && $my_match['matchVideoURL']!='')
				{
					$userResponse['matchVideoURL'] = UPLOAD_URL.'match-video/'.$my_match['matchVideoURL'];
				}else{
					$userResponse['matchVideoURL'] = UPLOAD_URL.'match-video/default_room.png';
				}*/
                $userResponse['matchVideoURL'] = ($my_match['matchVideoURL']===NULL)?'':$my_match['matchVideoURL'];
				if($this->language=='english')
				{
					$roomName = $my_match['room_name'];
				}else{
					$roomName = $my_match['room_name_gr'];
				}
				$userResponse['room_name'] 			=	 ($my_match['room_name']===NULL)?'':$roomName;
				$userResponse['entry_fees'] 		=	 ($my_match['tokens']===NULL)?'':$my_match['tokens'];
				$userResponse['start_datetime']		=	 ($my_match['start_datetime']==="0000-00-00 00:00:00")?'NA':date('d M Y h:i A',strtotime($my_match['start_datetime']));
				$userResponse['winning_price_first'] =	 ($my_match['grand_loot_price_value']===NULL)?'0':$my_match['grand_loot_price_value'];
				$userResponse['winning_price_second'] =	 ($my_match['secoundry_prize_value']===NULL)?'0':$my_match['secoundry_prize_value'];
				$userResponse['winning_price_third'] =	 ($my_match['third_price_value']===NULL)?'0':$my_match['third_price_value'];
				$userResponse['room_type'] 			=	 ($my_match['category_name']===NULL)?'':$my_match['category_name'];
				$userResponse['total_seats'] 		=	 ($my_match['available_tickets']===NULL)?'':$my_match['available_tickets'];
				$userResponse['total_enterd_users'] =	 ($my_match['users_purchased_ticket']===NULL)?'':$my_match['users_purchased_ticket'];
				$userResponse['remaining_tickets'] 	=	 ($my_match['users_purchased_ticket']===NULL)?'':$my_match['available_tickets']-$my_match['users_purchased_ticket'];
				//$userResponse['organized_by'] 		=	 ($my_match['organized_by']===NULL)?'':$my_match['organized_by'];
				$userResponse['entry_key'] 			=	 '';
				
				$this->db->select('room_winners.*,u.emailid,u.full_name');
				$this->db->from('room_winners');
				$this->db->join('usermaster u','u.id=room_winners.user_id','left');
				$this->db->where('room_id',$userResponse['room_id']);
				$this->db->order_by('price_type','ASC');
				$query1 = $this->db->get();
				$possitionRank = array();
				if($query1->num_rows()>0){
					$allWinnersLists = $query1->result_array();
					foreach ($allWinnersLists as $key1 => $value1) 
					{
						$tempArr = array();
						$tempArr['kills'] = $value1['kills'];
						$tempArr['rank'] = $value1['rank'];
						$tempArr['is_withdraw'] = $value1['is_withdraw'];
						$price_type 	=	 ($value1['price_type']===NULL)?'0':$value1['price_type'];
						if($price_type=='FIRST')
						{
							$my_possition 	=	 '1';
							$price_amount 	=	 $userResponse['winning_price_first'];
							$rank_image 	=	 WEB_NEW_PATH."images/one.svg";
						}elseif($price_type=='SECOND')
						{
							$my_possition 	=	 '2';
							$price_amount 	=	 $userResponse['winning_price_second'];
							$rank_image 	=	 WEB_NEW_PATH."images/two.svg";
						}elseif($price_type=='THIRD')
						{
							$my_possition 	=	 '3';
							$price_amount 	=	 $userResponse['winning_price_third'];
							$rank_image 	=	 WEB_NEW_PATH."images/three.svg";
						}else{
							$my_possition 	=	 '4';
							$price_amount 	=	 $userResponse['winning_price_third'];
							$rank_image 	=	 WEB_NEW_PATH."images/three.svg";
						}
						$tempArr['prize_possition'] = $my_possition;
						$tempArr['prize_amount'] 	= $price_amount;
						$tempArr['rank_image'] 	= $rank_image;
						$tempArr['full_name'] = $value1['full_name']!=''?$value1['full_name']:$value1['emailid'];
						$possitionRank[] = $tempArr;
					}
				}
				$userResponse['possition_rank'] = $possitionRank;
				
				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response = $final_response;
		}else{
			$response = $final_response;
			
		}
		return $response;
		
	}
	
	public function getMyWonMatchList($user_id,$limit,$offset,$type)
	{
		$this->db->select('rw.room_winner_id,rw.room_id,rw.price_type,rw.winning_amount,rw.room_winner_status,rw. kills,rw.rank,rw.is_withdraw,rw.category_id,rm.room_name,rm.room_name_gr,rm.room_image,rm.short_description,rm.start_datetime,rm.grand_loot_price_value,rm.available_tickets,rm.secoundry_prize_value,rm.third_price_value,rm.event_completed,rm.price_name,rm.per_ticket_tokens,getTotalSoldTickets(rm.room_id) as users_purchased_ticket,c.category_name');
		$this->db->from('room_winners rw');
		$this->db->join('rooms rm','rm.room_id=rw.room_id','left');
		$this->db->join('usermaster u','u.id=rw.user_id','left');
		$this->db->join('categories c','c.category_id=rm.category_id','left');
		$this->db->where('rw.user_id',$user_id);
		$this->db->where('rm.event_completed','1');
		$this->db->where('rm.status','1');
		$this->db->where('rm.delete_status','0');
		$this->db->where('rw.status','1');
		$this->db->where('rw.delete_status','0');
		$this->db->order_by('rw.created_on','DESC');
		$query = $this->db->get();
		$final_response = array();
		if($query->num_rows()>0){
			$my_matches = $query->result_array();
			//pr($my_matches,1);
			foreach($my_matches as $my_match)
			{
				$userResponse = array();
				$userResponse['room_winner_id'] 	=	 ($my_match['room_winner_id']===NULL)?'':$my_match['room_winner_id'];
				$userResponse['room_id'] 			=	 ($my_match['room_id']===NULL)?'':$my_match['room_id'];
				$userResponse['category_id'] 		=	 ($my_match['category_id']===NULL)?'':$my_match['category_id'];
				$userResponse['category_name'] 		=	 ($my_match['category_name']===NULL)?'':$my_match['category_name'];
				if(is_file(UPLOAD_PHYSICAL_PATH.'rooms/'.$my_match['room_image']) && $my_match['room_image']!='')
				{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/'.$my_match['room_image'];
				}else{
					$userResponse['room_image'] = UPLOAD_URL.'rooms/default_room.png';
				}
				if($this->language=='english')
				{
					$roomName = $my_match['room_name'];
				}else{
					$roomName = $my_match['room_name_gr'];
				}
				$userResponse['room_name'] 			=	 ($my_match['room_name']===NULL)?'':$roomName;
				$userResponse['entry_fees'] 		=	 ($my_match['per_ticket_tokens']===NULL)?'':$my_match['per_ticket_tokens'];
				$userResponse['start_datetime']		=	 ($my_match['start_datetime']==="0000-00-00 00:00:00")?'NA':date('d M Y h:i A',strtotime($my_match['start_datetime']));
				$userResponse['winning_price_first'] =	 ($my_match['grand_loot_price_value']===NULL)?'0':$my_match['grand_loot_price_value'];
				$userResponse['winning_price_second'] =	 ($my_match['secoundry_prize_value']===NULL)?'0':$my_match['secoundry_prize_value'];
				$userResponse['winning_price_third'] =	 ($my_match['third_price_value']===NULL)?'0':$my_match['third_price_value'];
				$userResponse['room_type'] 			=	 ($my_match['category_name']===NULL)?'':$my_match['category_name'];
				$userResponse['total_seats'] 		=	 ($my_match['available_tickets']===NULL)?'':$my_match['available_tickets'];
				$userResponse['total_enterd_users'] =	 ($my_match['users_purchased_ticket']===NULL)?'':$my_match['users_purchased_ticket'];
				$userResponse['remaining_tickets'] 	=	 ($my_match['users_purchased_ticket']===NULL)?'':$my_match['available_tickets']-$my_match['users_purchased_ticket'];
				$price_type 	=	 ($my_match['price_type']===NULL)?'0':$my_match['price_type'];
				if($price_type=='FIRST')
				{
					$userResponse['my_possition'] 	=	 '1';
				}elseif($price_type=='SECOND')
				{
					$userResponse['my_possition'] 	=	 '2';
				}elseif($price_type=='THIRD')
				{
					$userResponse['my_possition'] 	=	 '3';
				}else{
					$userResponse['my_possition'] 	=	 '4';
				}

				$room_winner_status = ($my_match['price_type']===NULL)?'0':$my_match['room_winner_status'];
				if($room_winner_status=='PENDING')
				{
					$userResponse['winning_request_status'] 	=	 'PENDING';
				}elseif($room_winner_status=='PROCESSING')
				{
					$userResponse['winning_request_status'] 	=	 'REQUESTED';
				}else{
					$userResponse['winning_request_status'] 	=	 'TRANSFERED';
				}
				//$userResponse['organized_by'] 		=	 ($my_match['organized_by']===NULL)?'':$my_match['organized_by'];
				//$userResponse['entry_key'] 			=	 '';
				
				$this->db->select('room_winners.*,u.emailid,u.full_name');
				$this->db->from('room_winners');
				$this->db->join('usermaster u','u.id=room_winners.user_id','left');
				$this->db->where('room_id',$userResponse['room_id']);
				$this->db->order_by('price_type','ASC');
				$query1 = $this->db->get();
				$possitionRank = array();
				if($query1->num_rows()>0){
					$allWinnersLists = $query1->result_array();
					foreach ($allWinnersLists as $key1 => $value1) 
					{
						$tempArr = array();
						$tempArr['kills'] = $value1['kills'];
						$tempArr['rank'] = $value1['rank'];
						$tempArr['is_withdraw'] = $value1['is_withdraw'];
						$price_type 	=	 ($value1['price_type']===NULL)?'0':$value1['price_type'];
						if($price_type=='FIRST')
						{
							$my_possition 	=	 '1';
							$price_amount 	=	 $userResponse['winning_price_first'];
							$rank_image 	=	 WEB_NEW_PATH."images/one.svg";
						}elseif($price_type=='SECOND')
						{
							$my_possition 	=	 '2';
							$price_amount 	=	 $userResponse['winning_price_second'];
							$rank_image 	=	 WEB_NEW_PATH."images/two.svg";
						}elseif($price_type=='THIRD')
						{
							$my_possition 	=	 '3';
							$price_amount 	=	 $userResponse['winning_price_third'];
							$rank_image 	=	 WEB_NEW_PATH."images/three.svg";
						}else{
							$my_possition 	=	 '4';
							$price_amount 	=	 $userResponse['winning_price_third'];
							$rank_image 	=	 WEB_NEW_PATH."images/three.svg";
						}
						$tempArr['prize_possition'] = $my_possition;
						$tempArr['prize_amount'] 	= $price_amount;
						$tempArr['rank_image'] 	= $rank_image;
						$tempArr['full_name'] = $value1['full_name']!=''?$value1['full_name']:$value1['emailid'];
						$possitionRank[] = $tempArr;
					}
				}
				$userResponse['possition_rank'] = $possitionRank;

				$final_array[] = $userResponse;
			}
			$final_response = $this->get_paging($final_array,$limit,$offset);
			$response['won_match_listing'] = $final_response;
		}else{
			$response['won_match_listing'] = $final_response;
		}
		return $response;
		
	}
	
	public function my_matches(){
		$data['title'] = "My Match";
		$user_id = $this->site_santry->get_web_auth_data('id');
		$limit = "";
		$offset = "";
		$data['my_upcoming_match_list'] 	= $this->getMyUpcomingMatchList($user_id,$limit=5,$offset=0);
		$data['my_completed_match_list'] 	= $this->getMyCompletedMatchList($user_id,$limit=3,$offset=0);
		$data['my_won_match_list'] 			= $this->getMyWonMatchList($user_id,$limit=5,$offset=0,$type="");
		//pr($data,1);
		$this->layout->view('web/my-match',$data);
	}
	
	public function moneyRequest($winning_match_id_encoded){
		$data['title'] = "Money Request";
		$winning_match_id = base64_decode($winning_match_id_encoded);
		//pr($winning_match_id,1);
		$user_id = $this->site_santry->get_web_auth_data('id');
		$user_detail 		= $this->getUserData($user_id);
		$paypal_id          = ($user_detail['paypal_id']===NULL)?"":$user_detail['paypal_id'];
		$account_dropdown = array('paypal'=>$paypal_id);
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$account_details = $this->GetCardDetailsbyUserId($user_id);
		//pr($account_details,1);
		foreach($account_details as $account){
		    $account_dropdown[$account['account_id']] = $account['account_number'];
		}
		//pr($account_dropdown,1);
		$data['account_dropdown'] = $account_dropdown;
		if(!empty($this->input->post())){
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				array('field' => 'room_winner_id','label' => 'Winner Id','rules' => 'trim|required'),
				array('field' => 'account_id','label' => 'Select Account','rules' => 'trim|required'),
				array('field' => 'amount_to_be_credit','label' => 'Credit Amount','rules' => 'trim|required'),
            );
            $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
			    $room_winner_id = base64_decode($post['room_winner_id']);
			    //pr($room_winner_id,1);
			    $alredyExits = $this->commonmodel->_get_data('room_winners',array('user_id' => $user_id,'room_winner_id' => $room_winner_id));
			    //echo $this->db->last_query();die;
			    //pr($alredyExits,1);
    			if($alredyExits==NULL)
    			{
    				$this->session->set_flashdata('flashError','You have already requested for this winning!');
			        redirect('home/my-match');
    			}else if($alredyExits[0]['room_winner_status'] != 'PENDING'){
    				 $this->session->set_flashdata('flashError','You have already requested for this winning!');
			         redirect('home/my-match');
    			}else{
    			    $credit_to_amount = $post['amount_to_be_credit'];
    			    $credit_to_app_balance = $post['credit_to_app_balance'];
    			    if($post['account_id']=='paypal'){
    			        $type = 'PAYPAL';
    			        $account_id = "";
    			        $paypal_id = $paypal_id;
    			    }else{
    			        $type = 'ACCOUNT';
    			        $account_id = $post['account_id'];
    			        $paypal_id = "";
    			    }
    			    $updateStatus = $this->commonmodel->_update('room_winners',array('credit_to_amount'=>$credit_to_amount,'credit_to_app_balance'=>$credit_to_app_balance,'room_winner_status'=>'PROCESSING','account_id'=>$account_id,'type'=>$type,'paypal_id'=>$paypal_id),array('room_winner_id'=>$room_winner_id));
    			    if($updateStatus){
        			    $this->session->set_flashdata('flashSuccess',$this->lang->line('amount_requested_successfully'));
    			        redirect('home/my-match');
    			    }else{
    			        $this->session->set_flashdata('flashError','An error occured.Please try again later!');
    			        redirect('home/my-match');
    			    }
    			}
			}
			
		}
		
	    $data['winning_match_id'] = $winning_match_id;
		$data['match_details'] = $this->User_model->getMyWonMatchDetail($user_id,$winning_match_id);
		//pr($data,1);
		$this->layout->view('web/money-request',$data);
	}
	
	public function joinMatch($match_id_encoded){
	    $user_id = $this->site_santry->get_web_auth_data('id');
	    $this->load->library('user_agent');
		$referrer = $this->agent->referrer();
	    
		if($match_id_encoded!=""){
		    $match_id   =   base64_decode($match_id_encoded);
			$response = $this->User_model->joinMatch($user_id,$match_id);
			if($response['status']=="200"){
			    $this->session->set_flashdata('flashSuccess',$response['message']);
			}else{
			    $this->session->set_flashdata('flashError',$response['message']);
			}
		}else{
		    $this->session->set_flashdata('flashError','Something went wrong...please try again!');
		}
		redirect($referrer);
	}
	
	public function deleteBankAccount(){
		if($this->input->post()){
			$post = $this->input->post();
			$user_id = $this->site_santry->get_web_auth_data('id');
			$account_id = base64_decode($post['account_id']);
			if($account_id!=""){
				$this->db->where('customer_id',$user_id);
				$this->db->where('account_id',$account_id);
				if($this->db->update('user_account_details',array('acc_delete_status'=>'1','modified_on'=>date('Y-m-d H:i:s')))){
					$response['status'] = 'success';
					$response['error'] = false;
					$response['message'] = 'Account deleted successfully!';
				}else{
					$response['status'] = 'error';
					$response['error'] = false;
					$response['message'] = 'Something went wrong...please try again!';
				}
			}else{
				$response['status'] = 'error';
				$response['error'] = true;
				$response['message'] = 'Something went wrong...please try again!';
			}
		}else{
			$response['status'] = 'error';
			$response['error'] = true;
			$response['message'] = 'Something went wrong...please try again!';
		}
		
		echo json_encode($response);
	}
	
	public function getBankAccount(){
	    if($this->input->post()){
			$post = $this->input->post();
			$user_id = $this->site_santry->get_web_auth_data('id');
			$account_id = base64_decode($post['account_id']);
			if($account_id!=""){
				$this->db->select('*');
				$this->db->from('user_account_details');
				$this->db->where('customer_id',$user_id);
				$this->db->where('account_id',$account_id);
				$this->db->where('acc_status','1');
				$this->db->where('acc_delete_status','0');
				$query = $this->db->get();
				if($query->num_rows()>0){
				    $result = $query->row_array();
					$response['status'] = 'success';
				    $response['card_number'] = $result['card_number'];
				    $response['security_code'] = $result['security_code'];
				    $response['card_holder_name'] = $result['card_holder_name'];
				    $response['bank_name'] = $result['bank_name'];
				    $response['account_id'] = base64_encode($result['account_id']);
					$response['message'] = 'Account Details!';
				}else{
					$response['status'] = 'error';
					$response['message'] = 'Something went wrong...please try again!';
				}
			}else{
				$response['status'] = 'error';
				$response['message'] = 'Something went wrong...please try again!';
			}
		}else{
			$response['status'] = 'error';
		    $response['message'] = 'Something went wrong...please try again!';
		}
		
		echo json_encode($response);
	}
	
	public function editBankAccount(){
	   $user_id = $this->site_santry->get_web_auth_data('id');
	   if($this->input->post()){
			$post = $this->input->post();
			$validation_post = array(
				array('field' => 'account_number','label' => 'Card Number','rules' => 'trim|required'),
				array('field' => 'bank_account_name','label' => 'Bank Name','rules' => 'trim|required'),
				array('field' => 'bank_ifsc_code','label' => 'IFSC Code','rules' => 'trim|required'),
				array('field' => 'bank_account_holder_name','label' => "Account Holder's Name",'rules' => 'trim|required'),
			);
			
			//pr($post,1);
		
			$account_id = base64_decode($post['account_id_hidden']);
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
			    if($account_id!=""){
    				$this->db->select('*');
    				$this->db->from('user_account_details');
    				$this->db->where('customer_id',$user_id);
    				$this->db->where('account_id',$account_id);
    				$this->db->where('acc_status','1');
    				$this->db->where('acc_delete_status','0');
    				$query = $this->db->get();
    				if($query->num_rows()>0){
    				    $result = $query->row_array();
    					//pr($result,1);
    					$update_array = array();
    					$update_array['bank_name'] = $post['bank_account_name'];
    					$update_array['security_code'] = $post['bank_ifsc_code'];
    					$update_array['card_number'] = $post['account_number'];
    					$update_array['card_holder_name'] = $post['bank_account_holder_name'];
    					$this->db->where('account_id',$account_id);
    				    if($this->db->update('user_account_details',$update_array)){
    					    $this->session->set_flashdata('flashSuccess','Your Account details updated successfully!');
    					    redirect('home/account-details');
    					}else{
    					    $this->session->set_flashdata('flashError','Your Account details updated successfully!');
    					    redirect('home/account-details');
    					}
    				}else{
    				    $this->session->set_flashdata('flashError','No Account Found!!');
    					redirect('home/account-details');
    				}
    			}else{
    				$this->session->set_flashdata('flashError','Your Account details updated successfully!');
    			    redirect('home/account-details');
    			}
			}
			
			
		}else{
			$this->session->set_flashdata('flashError','Your Account details updated successfully!');
			redirect('home/account-details');
		}
	}
	
	
	public function createPayment(){
	    if($this->input->post()){
	        $data['title'] = 'Payment';
	        
    			$user_id = $this->site_santry->get_web_auth_data('id');
		
        		$user_detail 		= $this->getUserData($user_id);
        		$wallet_balance = $user_detail['available_amount'];
    			
    			$post = $this->input->post();
    			//pr($post,1);
    			if (isset($post['amount'])) 
    			{
    				$amount = $post['amount'];
    				/*$updated_user_tokens = $user_detail['available_amount']+$amount;
    				$userData		= array('user_tokens'=>$updated_user_tokens,'modify'=>date('Y-m-d H:i:s'));
    				$this->db->where('id',$user_id);
    				$result 	=   $this->db->update('tbl_usermaster',$userData);*/
    				//pr($updated_user_tokens,1);
    				
    				$commissionData = $this->commonmodel->_get_data('settingmaster',array('id' => '1'),'admin_commission');
    				$admin_commission = $commissionData[0]['admin_commission'];
    				$payment_amount = $amount + (($amount*$admin_commission)/100);
    				$token_details = array(
    					'user_id'		 	=> 	$user_id,
    					'tokens'			=>	$amount,
    					'token_amount'		=>	$payment_amount,
    					'type'				=>	'RECHARGED',
    					'is_claim_allowed'	=>	'0',
    					'created_on'		=>	date('Y-m-d H:i:s'),
    					'modified_on'		=>	date('Y-m-d H:i:s'),
    				);
    				//pr($token_details,1);
    				$saveTokenData 	= 	$this->db->insert('token_purchases',$token_details);
    				$token_id = $this->db->insert_id();
    				$data['token_id'] = base64_encode($token_id);
    				$data['amount'] = $payment_amount;
    				/*$userData		=	$this->getUserData($user_id);
    				$wallet_balance 		= $userData['available_amount'];*/
    				//$this->session->set_flashdata('flashSuccess',$this->lang->line('recharged_successfully'));
    				
    				$this->layout->view('web/payment',$data);
    			}else{
    			    redirect('home/recharge-credit');
    			}
    			
    		}else{
    		    redirect('home/recharge-credit');
    		}
	  }
	
	public function createApiPayment()
	{
	    $encoded_token_id = $this->uri->segment(4);
	    $encoded_amount = $this->uri->segment(5);
	    if($encoded_token_id!='' && $encoded_amount!='')
	    {
	        $token_id = base64_decode($encoded_token_id);
	        $amount = base64_decode($encoded_amount);
	        $tokenData = $this->commonmodel->_get_data('token_purchases',array('token_purchase_id' => $token_id,'status' => '1','delete_status' => '0'));
	        pr($tokenData,1);
	    	if(isset($post['amount'])) 
			{
				$amount = $post['amount'];
				/*$updated_user_tokens = $user_detail['available_amount']+$amount;
				$userData		= array('user_tokens'=>$updated_user_tokens,'modify'=>date('Y-m-d H:i:s'));
				$this->db->where('id',$user_id);
				$result 	=   $this->db->update('tbl_usermaster',$userData);*/
				//pr($updated_user_tokens,1);
				
				$token_details = array(
					'user_id'		 	=> 	$user_id,
					'tokens'			=>	$amount,
					'type'				=>	'RECHARGED',
					'is_claim_allowed'	=>	'0',
					'created_on'		=>	date('Y-m-d H:i:s'),
					'modified_on'		=>	date('Y-m-d H:i:s'),
				);
				$saveTokenData 	= 	$this->db->insert('token_purchases',$token_details);
				$token_id = $this->db->insert_id();
				$data['token_id'] = base64_encode($token_id);
				$data['amount'] = $amount;
				/*$userData		=	$this->getUserData($user_id);
				$wallet_balance 		= $userData['available_amount'];*/
				//$this->session->set_flashdata('flashSuccess',$this->lang->line('recharged_successfully'));
				
				$this->layout->view('web/payment',$data);
			}else{
			    die('You have entered the incorrect url.Please enter the correct url!');
			}
			
		}else{
		    die('You have entered the incorrect url.Please enter the correct url!');
		}
	  }
	  
	  public function updatePaymentStatus(){
	      if($this->input->is_ajax_request()){
	          $post = $this->input->post();
	          $token_id = base64_decode($post['token_id']);
	          $user_id = $this->site_santry->get_web_auth_data('id');
		        $this->db->select('token_purchases.*');
				$this->db->from('token_purchases');
			    $this->db->where('token_purchase_id',$token_id);
				$query1 = $this->db->get();
				if($query1->num_rows()>0){
				    $tokenData = $query1->row_array();
				    
				    $token_Data		= array('payemnt_status'=>'SUCCESS','modified_on'=>date('Y-m-d H:i:s'));
        			$this->db->where('token_purchase_id',$token_id);
        			$token_result 	=   $this->db->update('token_purchases',$token_Data);
				    
				    $amount = $tokenData['tokens'];
				    $user_detail 		=  $this->getUserData($user_id);
            		$wallet_balance    =  $user_detail['available_amount'];
            		$updated_user_tokens = $user_detail['available_amount']+$amount;
        			$userData		= array('user_tokens'=>$updated_user_tokens,'modify'=>date('Y-m-d H:i:s'));
        			$this->db->where('id',$user_id);
        			$result 	=   $this->db->update('tbl_usermaster',$userData);
        			
        			$tempArr = array();
					$tempArr['user_id'] = $user_id;
					$tempArr['action'] 	= 'WALLET_RECHARGED';
					$tempArr['created_on'] = date('Y-m-d H:i:s');
					$tempArr['modified_on'] = date('Y-m-d H:i:s');
					$saveNotificationData 	= 	$this->db->insert('user_notifications',$tempArr);
    				$noti_id = $this->db->insert_id();
        			
        			$to = $user_detail['emailid'];
					$user_name = $user_detail['full_name'];
					$subject = SITE_NAME.' Payment transfered';
					$message =	'Hello '.$user_name.'<br/>';
					$message .= 'Your wallet has been recharged please check it. <br/> <br/>';
					$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
					$message .= 'We hope you enjoy our services!<br/><br/>';
					$message .= 'Sincerely<br/>';
					$message .= SITE_NAME.' Team';
					$mail_confirm = $this->sendemail($to,$subject,$message);
					
					
            		$message = 'Your wallet has been recharged please check it.';
            		$title = 'Wallet Recharged';
            		$type = 'WALLET_RECHARGED';
            		sendApnsPushNotification($user_id,$message,$title,$type);
            		
        			$response['status'] = 'success';
        			$response['message'] = 'successfull';
				}else{
				    $response['status'] = 'error';
        			$response['message'] = 'failed';
				}
				echo json_encode($response);die;
          }else{
        	  redirect('home/recharge-credit');
          }
	  }
	  
	  public function sendemail($to,$subject,$message)
    	{
    		require_once('smtp/class.phpmailer.php');
    		//echo "hello";die;
    		$HOST_NAME 	= HOST_NAME;
			$USER_NAME 	= USER_NAME;
			$PASSWORD 	= SMTP_PASSWORD;
			$FROM_NAME 	= FROM_NAME;
			$FROM 		= FROM;
			$PORT_NO 	= SMTP_PORT;
			$SMTP_SECURE= SMTP_SECURE;
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
    			$mail->SMTPSecure		= 	$SMTP_SECURE; // SMTP secure
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
	
}

/* End of file welcome.php */
