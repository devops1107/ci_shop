<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class user_profile_model extends CI_Model{
	
	
	public function getTotalActiveUser(){
		$this->db->select('id')
					->from('tbl_usermaster')
					->where('status','1')
					->where('delete_status','0');
		$data = $this->db->get();
		return $data->num_rows();
	}
	
	public function getTotalInactiveUser(){
		$this->db->select('id')
					->from('tbl_usermaster')
					->where('status','0')
					->where('delete_status','0');
		$data = $this->db->get();
		return $data->num_rows();
	}
	
	public function getTotalProducts(){
		$this->db->select('product_id');
		$this->db->from('tbl_products');
		$this->db->where('delete_status','0');
		$data = $this->db->get();
		return $data->num_rows();
	}

	public function check_user_name($user_name){
		$this->db->select('admin_username')
					->from('admin_users')
					->where('admin_username',$user_name);
		$data = $this->db->get();
		
		if($data->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function check_password($old_password){
		$user_id = $this->site_santry->get_auth_data('id');
		$this->db->select('user_id')
					->from('admin_users')
					->where('user_pass',md5($old_password))
					->where('user_id',$user_id);
		$data = $this->db->get();
		
		if($data->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function getDashboardDetails($data)
	{
        $user_id    = $this->site_santry->get_auth_data('id');
		$user_type = $this->site_santry->get_auth_data('user_type');
		if($user_type=='admin'){
	      $software_where  = 'where 1'; 
		}elseif($user_type=='rsm'){
		 //$software_where = 'where (log_listing.user_id = '.$user_id.' OR log_listing.to_id='.$user_id.') '; 
		 $software_where = 'where (log_listing.user_id = '.$user_id.' OR log_listing.to_id='.$user_id.' OR log_listing.to_id IN (SELECT assign_sale_person.sales_id FROM assign_sale_person where assign_sale_person.u_id = '.$user_id.' group by assign_sale_person.sales_id )) '; 
		}else{
			$software_where = 'where (log_listing.user_id = '.$user_id.' OR log_listing.to_id='.$user_id.')';
		}
		
		 /* if($data['user_type']) {
			$software_where .= " And users.user_type LIKE '%".trim($data['user_type'])."%' "; 
		} */ 
		
		
		$data['where_coloums'] 	= array('serial_number','user_name','comment','date');
        $data['select_order_colum'] = array('serial_number','user_name','comment','date');
			
		$data['table_name']   		= "admin_users";
		$data['indexColumn']  		= "user_id";
		$limit = '';
		if ( isset($data['post']['iDisplayStart'] ) && $data['post']['iDisplayLength'] != '-1' )
		{
			$offset = $data['post']['iDisplayStart'];
			$limit = "LIMIT ".intval($data['post']['iDisplayStart']).", ".intval($data['post']['iDisplayLength']); 	
		}
		/* Ordering */
		if(isset($data['post']['iSortCol_0']))
		{
			$order_by = "ORDER BY  ";
			for ( $i=0 ; $i<intval($data['post']['iSortingCols']); $i++ )
			{
				if ($data['post']['bSortable_'.intval($data['post']['iSortCol_'.$i])]== "true" )
				{
					$order_by .= $data['select_order_colum'][intval($data['post']['iSortCol_'.$i] )]."
									".$data['post']['sSortDir_'.$i]  .", ";
				}
			}
			$order_by = substr_replace( $order_by, "", -2 );
			if ( $order_by == "ORDER BY" )
			{
				$order_by = "";
			}
		}
		$where = "";
		/*
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=1 ; $i<count($data['where_coloums']) ; $i++ )
			{
				$where .= $data['where_coloums'][$i]." LIKE '%".trim($data['post']['sSearch'] )."%' OR ";
			}
			$where = substr_replace( $where, "", -3 );
			$where .= ')';
		}
		
		for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ )
		{
			if ($data['post']['bSearchable_'.$i] == "true" && $data['post']['sSearch_'.$i] != '' )
			{
				if($where == "")
				{
					$where = "WHERE ";
				}
				else
				{
					$where .= " AND ";
				}
				echo $where .= $$data['where_coloums'][$i]." LIKE '%".trim($data['post']['sSearch_'.$i])."%' ";
			}
		}
		*/
		$order_by = " ORDER BY log_id DESC ";
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number, log_id, concat(u.first_name,' ',u.last_name) as user_name, comment,DATE_FORMAT(created_date,'%d/%m/%Y %h:%i %p') as date,link,r_id,cr_id "." FROM "." log_listing LEFT JOIN admin_users u ON u.user_id = log_listing.user_id "." ,(SELECT @a:= 0)AS a ".$software_where." ".$order_by.' '.$limit;
		/*  echo $query;
		  die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_log_count();
		
		/* Output */
		$output = array(
			"sEcho" => intval($data['post']['sEcho']),
			"iTotalRecords" => $total_records,
			"iTotalDisplayRecords" => $display_records,
			"aaData" => array()
			);
		$data['select_order_colum'][] = ' ';
		
		if($offset==0) 
		{
			$j=1;
		}else{
			$j = $offset+1;
		}
		foreach($result as $aRow )
		{
			
			$row = array();
			for ( $i=0 ; $i<count($data['select_order_colum']) ; $i++ )
			{
				
				if ($data['select_order_colum'][$i] != ' ' )
				{
					if($i==0)
					{
						$row[] = $j++;
					}else if($i==1)
					{
						$row[] = ucwords(strtolower($aRow[$data['select_order_colum'][$i]]));
					}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
				
				
					/* if(!empty($aRow['link'])){
						
							$url_link = unserialize(stripslashes($aRow['link']));
							$li_u = '';
							
							foreach($url_link as $link_rec){
								if( in_array($user_type, $link_rec['user_type'] ) ){
								if($link_rec['label'] == 'Report'){
									$title = $link_rec['label'];
									$label = '<i aria-hidden="true" class="fa fa-file-text-o"></i>';
								}else{
									$title = '';
								   $label =  $link_rec['label'];
								}
								$li_u .= '&nbsp;<a title='.$title.' href="'. base_url().$link_rec['url'].'">'.$link_rec['label'].'</a>&nbsp;|&nbsp;';
								}
							}
						
						  
			     } 	 */
					
					
					
				}else
				{
					  
						
				
					
				}
				
				
				
			}
			$li_u_str ='';
			if(!empty($aRow['link'])){
						
							$url_link = unserialize(stripslashes($aRow['link']));
							$li_u = array();
							
							foreach($url_link as $link_rec){
								if( in_array($user_type, $link_rec['user_type'] ) ){
								if($link_rec['label'] == 'Report'){
									$title = $link_rec['label'];
									$label = '<i aria-hidden="true" class="fa fa-file-text-o"></i>';
									$link = '';
									if(!empty($aRow['r_id']) && $aRow['r_id']>0){
									  $query ="SELECT * FROM reports WHERE r_id='".$aRow['r_id']."' AND delete_status='0' ";
	                                  $rResult = $this->db->query($query);
									  $count = $rResult->num_rows();
										if($count>0){
											 $link = '<a title="'.$title.'" href="'. base_url().$link_rec['url'].'">'.$label.'</a>'; 
										}else{
											$link = '';  
										}
						            }
									
									
								}else{
									$title = '';
								    $label =  $link_rec['label'];
									if(!empty($aRow['cr_id']) && $aRow['cr_id']>0){
									  $query ="SELECT * FROM client_revision WHERE cr_id='".$aRow['cr_id']."' AND client_status='pending' ";
	                                  $rResult = $this->db->query($query);
									  $count = $rResult->num_rows();
										if($count>0){
											 $link = '<a title="'.$title.'" href="'. base_url().$link_rec['url'].'">'.$label.'</a>'; 
										}else{
											$link = '';  
										}
						            }
								}
								
								
								
								$li_u[] = '&nbsp;'.$link.'&nbsp;';
								}
							}
						$li_u_str = join("|",$li_u);
						   
			 }
			
			$row[2] = $row[2].'&nbsp;&nbsp;&nbsp;'.$li_u_str;
		
		 	
			$output['aaData'][] = $row;
			
		
			
		}
		
		return $output;
	}
	

	public function addAdminUsers($post) {
		if($post){
			$data=array();
			$data['roleid']='2';	
			$data['firstname']=trim($post['first_name']);	
			$data['lastname']=trim($post['last_name']);	
			$data['username']=trim($post['user_name']);	
			$data['mobileno']=trim($post['mobileno']);	
			$data['emailid']=trim($post['email']);	
			$data['password']=trim(md5($post['password']));	
			$data['created']=date('Y-m-d H:i:s');	
			$data['modify']=date('Y-m-d H:i:s');
			$insertUser=$this->commonmodel->_insert('tbl_usermaster', $data);
			if($insertUser){
				return true;
			}
			else{
				return false;
			}
        }
        else {
        	return false;
        }
    }	

	public function getAllUsersdetail($data)
	{
		//pr($data,1);
	    $software_where  = 'where 1 And delete_status = "0" '; 
		
		if($data['customer_name'] != '') {
			$software_where .= " And concat_ws(' ',first_name,last_name) LIKE '%".trim($data['customer_name'])."%' "; 
		}
		if($data['customer_email'] != '') {
			$software_where .= " And emailid LIKE '%".trim($data['customer_email'])."%' "; 
		}
		
		if($data['contact_number'] != '') {
			$software_where .= " And mobileno LIKE '%".trim($data['contact_number'])."%' "; 
		}

		if($data['user_status'] != '') {
			$software_where .= " AND account_confirm = '".$data['user_status']."' ";
		}
		
		$data['where_coloums'] 	= array('serial_number','first_name','last_name','emailid','mobileno','account_confirm');
        $data['select_order_colum'] = array('serial_number','first_name','last_name','mobileno','emailid','email_varification_status','vat_number','commercial_reg_no');

		$data['table_name']   		= "tbl_usermaster";
		$data['indexColumn']  		= "id";
		$limit = '';
		if ( isset($data['post']['iDisplayStart'] ) && $data['post']['iDisplayLength'] != '-1' )
		{
			$offset = intval($data['post']['iDisplayStart']);
			$limit = "LIMIT ".intval($data['post']['iDisplayStart']).", ".intval($data['post']['iDisplayLength']); 	
		}
		/* Ordering */
		if(isset($data['post']['iSortCol_0']))
		{
			$order_by = "ORDER BY  ";
			for ( $i=0 ; $i<intval($data['post']['iSortingCols']); $i++ )
			{
				if ($data['post']['bSortable_'.intval($data['post']['iSortCol_'.$i])]== "true" )
				{
					$order_by .= $data['select_order_colum'][intval($data['post']['iSortCol_'.$i] )]."
									".$data['post']['sSortDir_'.$i]  .", ";
				}
			}
			$order_by = substr_replace( $order_by, "", -2 );
			if ( $order_by == "ORDER BY" )
			{
				$order_by = "";
			}
		}
		$where = "";
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=1 ; $i<count($data['where_coloums']) ; $i++ )
			{
				$where .= $data['where_coloums'][$i]." LIKE '%".@trim($data['post']['sSearch'] )."%' OR ";
			}
			$where = substr_replace( $where, "", -3 );
			$where .= ')';
		}
		
		for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ )
		{
			if ($data['post']['bSearchable_'.$i] == "true" && $data['post']['sSearch_'.$i] != '' )
			{
				if($where == "")
				{
					$where = "WHERE ";
				}
				else
				{
					$where .= " AND ";
				}
				echo $where .= $data['where_coloums'][$i]." LIKE '%".@trim($data['post']['sSearch_'.$i])."%' ";
			}
		}
		
		
		$findstr_uname = strpos($order_by,"user_name");
		if($findstr_uname!=''){
			if($findstr_uname=='user_name'){
				$order_by = " ORDER BY id DESC";
			}
		}else{
			$order_by= " ORDER BY id DESC";
		}
		
		$query = "SELECT * FROM tbl_usermaster ".$software_where.$where." ".$order_by.' '.$limit;
		//echo $query;die;  
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_users_count($software_where);
		
		if($where!=""){
			$total_records=$display_records;
		}
		
		/* Output */
		$output = array(
			"sEcho" => intval($data['post']['sEcho']),
			"iTotalRecords" => $total_records,
			"iTotalDisplayRecords" => $total_records,
			"aaData" => array()
			);
		$data['select_order_colum'][] = ' ';
		if($offset==0){
			$j = 1;
		}else{
			$j = $offset+1;
		}
		
		foreach($result as $aRow )
		{
			
			$row = array();
			for ( $i=0 ; $i<count($data['select_order_colum']) ; $i++ )
			{
				
				if ($data['select_order_colum'][$i] != ' ' )
				{
					
					if($i==0){
						 $row[] = $j++;
					}elseif($data['select_order_colum'][$i]=='firstname'){
						if(trim($aRow['firstname'])!='')
						{
							$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['id']).'" class="text-dark">'.ucfirst(strtolower($aRow['firstname'])).'</a>';	
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='username'){
						if(trim($aRow['username'])!='')
						{
							$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['id']).'" class="text-dark">'.ucfirst(strtolower($aRow['username'])).'</a>';	
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='gender'){
						$row[] = $aRow['gender']=='1'?'Male':'Female';
					}elseif($data['select_order_colum'][$i]=='device_type'){
						$row[] = $aRow['device_type']=='1'?'IOS':'Android';
					}elseif($data['select_order_colum'][$i]=='account_confirm'){
						if($aRow['status']=="1")
						{
							$row[] = '<button class="btn btn-success btn-sm">Activated</button>';
						}else{
							$row[] = '<button class="btn btn-danger btn-sm">Blocked</button>';
						}
					}elseif($data['select_order_colum'][$i]=='email_varification_status'){
						if($aRow['email_varification_status']=="1")
						{
							$row[] = '<button class="btn btn-info btn-sm">Verified</button>';
						}else{
							$row[] = '<button class="btn btn-warning btn-sm">Not Verified</button>';
						}
					}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
				}else
				{
					 $html = '';
					
					 /* if($this->site_santry->get_auth_data('user_type')=='admin'){
						$html .='&nbsp<span><a title="Change Password" class="btn btn-color button_icon" href="'.base_url().'admin/users-change-password/'.base64_encode($aRow['id']).'"><i class="fa fa-unlock-alt"></i></a></span>';
						
					 } */
					 if($aRow['status']=="1")
					 {
						 $html .='&nbsp<span><a title="Active (Click to deactivate)" class="btn btn-color button_icon deactivate_user_status" href="'.base_url().'admin/users/change_activation_status/'.base64_encode($aRow['id']).'/'.base64_encode($aRow['status']).'" style="color:green;"><i class="fa fa-check"></i></a></span>';
					 }else{
						 $html .='&nbsp<span><a title="Deactive (Click to Activate)" class="btn btn-color button_icon activate_user_status" href="'.base_url().'admin/users/change_activation_status/'.base64_encode($aRow['id']).'/'.base64_encode($aRow['status']).'" style="color:red;"><i class="fa fa-ban"></i></a></span>';
					 }
					 $html .='&nbsp<span><a title="View" class="btn btn-color button_icon" href="'.base_url().'admin/edit-user/'.base64_encode($aRow['id']).'"><i class="fa fa-edit"></i></a></span>';
					 
					 /*$html .='<span><a title="View Trips" class="btn btn-color button_icon btn-info" href="'.base_url().'admin/trips/user/'.base64_encode($aRow['id']).'">View Trips</a></span>';*/
					//$name = $aRow['user_name'];
					 //<span><a href="javascript:void(0)" data-user-id="'.base64_encode($aRow['id']).'" class="button_icon viewDetail" title="view"><i class="fa fa-eye"></i></a></span>&nbsp
					$row[] = '
					<div class="action_box display-action">
						'.$html.'
					</div>';
				}
			}
		
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	public function list_users_count($software_where) {
	    $where = str_replace('where',' ',$software_where);
		$this->db->select('id');
		$this->db->from('tbl_usermaster');
		$this->db->where($where);
		//$this->db->where('account_confirm','C');
		//$this->db->where('email_varification_status','1');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
		 return $result->num_rows();
		}else{
		 return '0';
		}
	}
	
	

	public function cms_pages_count($page_type) {
		$this->db->select('cms_page_id');
		$this->db->from('cms_pages');
		$this->db->where('page_key',$page_type);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
		 return $result->num_rows();
		}else{
		 return '0';
		}
	}

	public function getUserDetailById($user_id="") {
		if($user_id!=''){
			$this->db->select('*');
			$this->db->from('tbl_usermaster');
			$this->db->where('delete_status','0');
			$this->db->where('id',$user_id);
			$get = $this->db->get();
			if($get->num_rows() > 0) {
				$result=$get->result_array();
				return $result[0];
			}else{
				return false;
			}
		}else {
			return false;	
		}
	}
	
	
	public function getAdminDetail($user_id){
		$this->db->where('user_id',$user_id);
		$query=$this->db->get('admin_users');
		return $query->row();
	}
	
	public function check_old_pass($condition){
		$this->db->select('admin_pass')
					->from('admin')
					->where($condition);
		$data = $this->db->get();
		if($data->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	 public function check_user_email($user_email){
		$this->db->select('email')
					->from('users')
					->where('email',$user_email);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$data = $this->db->get();
		
		if($data->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function getUsersDetail($id){
	   $query ="SELECT * FROM tbl_usermaster WHERE id = '".$id."' AND delete_status='0'  ";
	   $rResult = $this->db->query($query);
	   return $result  =  $rResult->row_array();
	}

	public function getCMSPages($page_key){
		
		if($page_key=='terms-conditions'){
			$page_key='TERMS_CONDITIONS';
		}
		if($page_key=='privacy-policy'){
			$page_key='PRIVACY_POLICY';
		}

		if($page_key=='about-us'){
			$page_key='ABOUT_US';
		}
		if($page_key=='rooms-rules-conditions'){
			$page_key='SWEEPSTAKES_RULES_CONDITIONS';
		}
		if($page_key=='stores-rules-conditions'){
			$page_key='STORES_RULES_CONDITIONS';
		}
	   $query ="SELECT * FROM cms_pages WHERE page_key = '".$page_key."' AND delete_status='0'  ";
	   $rResult = $this->db->query($query);
	   $result  =  $rResult->result_array();

	   if(!empty($result)){
		  return $result[0];
	   }else{
		  return '';	
	   }   
	}

	public function getLoggedUsersDetail($id){
	   $query ="SELECT * FROM admin_users WHERE user_id = '".$id."' AND user_delete='0'  ";
	   $rResult = $this->db->query($query);
	   $result  =  $rResult->result_array();
		
	   if($result>0){
		  return $result[0];
	    }else{
		  return '';	
		}   
	}

	public function updateAdminProfile($post,$id){
		if($post){
			$data=array();
		   	$data['first_name']=$post['first_name'];
		   	$data['last_name']=$post['last_name'];
		   	$data['user_email']=$post['user_email'];
		   	$data['contact_number']=$post['contact_number'];
		   	$data['admin_modify_dt']=date('Y-m-d H:i:s');

		   	$this->db->where('user_id',$id);	
		   	$qry=$this->db->update('admin_users',$data);	
	 		if($qry){
	 			return true;
	 		}
	 		else {
	 			return false;
	 		}
		}else {
			return false;
		}
	   	   
	}
	

	public function updateProfileImage()
    {
		$userDetails = $this->site_santry->get_auth_data();
		$id = $userDetails['id'];
		$response = array();
		if($id!="")
		{
			if($_FILES['profile_image']['name']!="")
			{
				$result = $this->do_upload_by_ajax('profile_image');
				if($result['error']=='no')
				{
					$imgName = $result['upload_data']['file_name'];
					$arr = array();
					$arr['profile_image'] = $imgName;
					
					$this->db->where('user_id',$id);
					$this->db->update('admin_users',$arr);
					$response['error'] = 'no';
					$response['msg'] = UPLOAD_URL.'user-images/'.$imgName;
				}else{
					$response['error'] = 'yes';
					$response['msg'] = $result['msg'];
				}
			}
		}else{
			$response['error'] = 'yes';
			$response['msg'] = 'User id can not be blank.';
		}
		return $response;
    }


    public function do_upload_by_ajax($submited_name)
	{
		
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'user-images/';
		if(!is_dir($folderPath))
		{
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'jpg|jpeg|png';
		$config['max_size']             = 1024;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name))
		{
			$data = array('error' => 'yes','msg' => $this->upload->display_errors());
		}else{
			$data = array('error' => 'no','upload_data' => $this->upload->data());
		}
		return $data;
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
	

	public function getLogDetail($id){
	   $query ="SELECT * FROM log_listing WHERE log_id = '".$id."' ";
	   $rResult = $this->db->query($query);
	   $result  =  $rResult->result_array();
		
	   if($result>0){
		  return $result;
	    }else{
		  return '';	
		}   
	}
	
	public function check_edit_useremail($email,$user_id){
		$this->db->select('id');
		$this->db->from('tbl_usermaster');
		$this->db->where('emailid',$email);
		$this->db->where('delete_status','0');
		if($user_id!="")
		{
			$this->db->where_not_in('id',$user_id);
		}
		$result = $this->db->get();
		
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}
	}
	public function user_check_old_pass($condition){
		$this->db->select('user_pass')
					->from('admin_users')
					->where($condition);
			$data = $this->db->get();	
		if($data->num_rows() > 0){
			return 1;
		}else{
			return 0;
		}
	
	}
	
	
	public function getAllDeleteUsersdetail($id){
	   $query ="SELECT * FROM admin_users WHERE user_id = '".$id."' ";
	   $rResult = $this->db->query($query);
	   $result  =  $rResult->result_array();
		
	   if($result>0){
		  return $result;
	    }else{
		  return '';	
		}   
	}

	
	public function editUser($post,$id="") {
		//pr($id,1);
		if($id!=""){
				$mod_date=date('Y-m-d H:i:s');
				$table='tbl_usermaster';
				$data=array();
				$data['first_name']=$post['first_name'];
				$data['last_name']=$post['last_name'];
				$data['mobileno']=$post['mobileno'];
				$data['vat_number']=$post['vat_number'];
				$data['commercial_reg_no']=$post['commercial_reg_no'];
				$data['emailid']=$post['emailid'];
				$data['modify']=$mod_date;
				$updateUser=$this->commonmodel->_update($table, $data,array('id' => $id));
				if($updateUser){
			        return true;
				}else {
					return false;
				}
		}else{
			return false;
		}

	}

	public function editCMS($post,$page_key="") {
		if($page_key=='terms-conditions'){
			$page_key='TERMS_CONDITIONS';
		}
		if($page_key=='about-us'){
			$page_key='ABOUT_US';
		}
		if($page_key=='privacy-policy'){
			$page_key='PRIVACY_POLICY';
		}
		if($page_key=='rooms-rules-conditions'){
			$page_key='SWEEPSTAKES_RULES_CONDITIONS';
		}
		if($page_key=='stores-rules-conditions'){
			$page_key='STORES_RULES_CONDITIONS';
		}
		//pr($page_key,1);
		if($page_key!=""){
			$table='cms_pages';
			$this->db->select('*');
			$this->db->select('cms_page_id');
			$this->db->from('cms_pages');
			$this->db->where('page_key',$page_key);
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			if($query->num_rows() >0){
				$result = $query->row_array();
				//pr($result,1);
				$modified_on = $result['modified_on'];
				if($result['description'] == ""){
					$mod_date				=	date('Y-m-d H:i:s');
					//$data['page_name']	=	$post['page_name'];
					$data['description']	=	$post['description'];
					$data['modified_on']	=	$mod_date;
					//echo "helloo";die;
					$updateUser=$this->commonmodel->_update($table, $data,array('page_key' => $page_key));
					return true;
				}else{
					$data=array();
					$mod_date				=	date('Y-m-d H:i:s');
					//$data['page_name']	=	$post['page_name'];
					$data['description']	=	$post['description'];
					$data['description_gr']	=	$post['description_gr'];
					$data['modified_on']	=	$mod_date;
					//echo "hiiiiii";die;
					$updateUser=$this->commonmodel->_update($table, $data,array('page_key' => $page_key));
					if($updateUser){
						$new_arr = array(
							'cms_page_type'			=>	$page_key,
							'cms_page_description'	=>	$post['description'],
							'cms_page_description_gr'	=>	$post['description_gr'],
							'created_on'			=>	$mod_date,
							'version_changed_on'	=>	date('Y-m-d H:i:s'),
							'version_created_on'	=>	$modified_on,
						);
						$createVersion = $this->commonmodel->_insert('cms_pages_versions',$new_arr);
						return true;
					}else {
						return false;
					}
				}
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
	
	public function get_faq($data)
		{
			//pr($data);die;
			$software_where  = 'where 1 And fq.delete_status = "0" '; 
			
			
			$data['where_coloums']  = array('fq.faq_id','fq.question','fq.answer');
			$data['select_order_colum'] = array('faq_id','question','answer');
			
			
			$data['table_name']       = "faqs";
			$data['indexColumn']      = "faq_id";
			//print_r($data);die();
			// pagination code start 
			$limit = '';
			if ( isset($data['post']['iDisplayStart'] ) && $data['post']['iDisplayLength'] != '-1' )
			{
				$offset = intval($data['post']['iDisplayStart']);
				$limit = "LIMIT ".intval($data['post']['iDisplayStart']).", ".intval($data['post']['iDisplayLength']);  
			}
			// pagination code end 
			
			/* Ordering */
			if(isset($data['post']['iSortCol_0']))
			{
				$order_by = "ORDER BY  ";
				//print_r($order_by);die();
				for ( $i=0 ; $i<intval($data['post']['iSortingCols']); $i++ )
				{
					if ($data['post']['bSortable_'.intval($data['post']['iSortCol_'.$i])]== "true" )
					{
						$order_by .= $data['select_order_colum'][intval($data['post']['iSortCol_'.$i] )]."
						".$data['post']['sSortDir_'.$i]  .", ";
					}
				}
				$order_by = substr_replace( $order_by, "", -2 );
				if ( $order_by == "ORDER BY" )
				{
					$order_by = "";
				}
			}
			$where = "";
			if ($data['post']['sSearch'] != "" ) {
				$where = " and (";
				for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ ) {
					$where .= $data['where_coloums'][$i]." LIKE '%".@mysql_real_escape_string($data['post']['sSearch'] )."%' OR ";
				}
				$where = substr_replace( $where, "", -3 );
				$where .= ')';
			}
			
			for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ ) {
				if ($data['post']['bSearchable_'.$i] == "true" && $data['post']['sSearch_'.$i] != '' ) {
					if($where == "") {
						$where = "WHERE ";
					}
					else {
						$where .= " AND ";
					}
					echo $where .= $data['where_coloums'][$i]." LIKE '%".@mysql_real_escape_string($data['post']['sSearch_'.$i])."%' ";
				}
			}
			$query = "SELECT fq.* FROM faqs as fq ".$software_where.$where." ".$order_by.' '.$limit;
			//echo $query;die;
			$rResult = $this->db->query($query);
			$result  =  $rResult->result_array();
			
			$display_records = count($result);
			
			$sQuery = "SELECT FOUND_ROWS()";
			$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
			$aResultFilterTotal =$rResultFilterTotal->result_array();
			$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
			$total_records = $this->list_faq_count($software_where,$where,strtoupper($data['type']));
			
			$output = array(
			"sEcho" => intval($data['post']['sEcho']),
			"iTotalRecords" => $total_records,
			"iTotalDisplayRecords" => $total_records,
			"aaData" => array()
			);
			$data['select_order_colum'][] = ' ';
			if($offset==0){
				$j = 1;
				}else{
				$j = $offset+1;
			}
			
			foreach($result as $aRow ) {
				$row = array();
				for ( $i=0 ; $i<count($data['select_order_colum']) ; $i++ ) {
					if ($data['select_order_colum'][$i] != ' ' ) {
						if($i==0){
							$row[] = $j++;
							}elseif($data['select_order_colum'][$i]=='question'){
								if(trim($aRow['question'])!='') {
									$row[] = ucfirst(strtolower($aRow['question'])); 
									}else{
									$row[] = '-';
								} 
							}elseif($data['select_order_colum'][$i]=='category_name_cn'){
								if(trim($aRow['category_name_cn'])!='') {
									$row[] = $aRow['category_name_cn']; 
									}else{
									$row[] = '-';
								} 
							}elseif($data['select_order_colum'][$i]=='status'){
								if($aRow['status']=="1") {
									$row[] = 'Activated';
									}else{
									$row[] = 'Blocked';
								}
							}else{
							$row[] = $aRow[$data['select_order_colum'][$i]];
						}
						}else {
							
							$edit_url = base_url('admin/edit-faq/'.base64_encode($aRow['faq_id']).'/'.strtolower($aRow['type']));
							$delete_url = base_url('admin/delete-faq/'.base64_encode($aRow['faq_id']).'/'.strtolower($aRow['type']));
							
							$html = '';
							$row[] = '
							<div class="action_box display-action">
							<span>
								<a href="'. $edit_url.'" class="btn btn-color button_icon text-success" title="Edit"><i class="fa fa-edit"></i></a>
								<a href="'. $delete_url.'" class="btn btn-color btn-lg button_icon text-danger confim_del" title="Delete"><i class="mdi mdi-close"></i></a></span>
							&nbsp
							'.$html.' 
							&nbsp
							'.$html.'          
							</div>';
						}
				}
				$output['aaData'][] = $row;
			}
			return $output;
		}
	
    public function list_faq_count($software_where,$where,$type) {
		//print_r($type);die;
		$this->db->select('fq.faq_id');
		$this->db->from('faqs fq');
		$this->db->where('fq.type',$type);
		$this->db->where('fq.delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			//echo $this->db->last_query();die;
			return $result->num_rows();
		}else{
			//echo $this->db->last_query();die;
			return '0';
		}
	}
	
	public function getFaqDetail($id){
			
		//   echo $id;die();
		$query ="SELECT * FROM faqs WHERE faq_id = '".$id."' AND delete_status='0'  ";
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		// print_r($result);die();
		if($result>0){
			return $result[0];
			}else{
			return '';  
		}   
	}
    
	
}
?>