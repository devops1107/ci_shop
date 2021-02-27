<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Partner_profile_model extends CI_Model{
	
	public function getAllPartnersList($data)
	{
		//pr($data,1);
	    $software_where  = 'where 1 And user_delete = "0" AND user_type ="partner"  AND partner_request_status ="ACCEPTED" '; 
		
		if($data['filter_partner_name'] != '') {
			$software_where .= " And first_name LIKE '%".trim($data['filter_partner_name'])."%' "; 
		}
		
		if($data['filter_partner_email'] != '') {
			$software_where .= " And user_email LIKE '%".trim($data['filter_partner_email'])."%' "; 
		}
		
		if($data['filter_contact_number'] != '') {
			$software_where .= " And contact_number LIKE '%".trim($data['filter_contact_number'])."%' "; 
		}
		
		if($data['filter_status'] != '') {
			$software_where .= " AND user_status = '".$data['filter_status']."' ";
		}
		
		$data['where_coloums'] 	= array('serial_number','first_name','user_email','contact_number','user_status');
        $data['select_order_colum'] = array('serial_number','first_name','user_email','contact_number','you_are','social_media_link','description','user_status');
			
		$data['table_name']   		= "admin_users";
		$data['indexColumn']  		= "user_id";
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
		//echo $where;die;
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
				echo $where .= 	$data['where_coloums'][$i]." LIKE '%".@trim($data['post']['sSearch_'.$i])."%' ";
			}
		}
		
		
		$findstr_uname = strpos($order_by,"user_name");
		if($findstr_uname!=''){
			if($findstr_uname=='user_name'){
				$order_by = " ORDER BY user_id DESC";
			}
		}else{
			$order_by= " ORDER BY user_id DESC";
		}
		
		$query = "SELECT * FROM admin_users ".$software_where.$where." ".$order_by.' '.$limit;
		//echo $query; die;  
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		//pr($result,1);
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_software_count($software_where);
		
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
					}elseif($data['select_order_colum'][$i]=='full_name'){
						if(trim($aRow['full_name'])!='')
						{
							$row[] = '<a href="'. base_url().'admin/edit-driver/'.base64_encode($aRow['driver_id']).'"> '.ucfirst(strtolower($aRow['full_name'])).'</a>'; 	
						}else if(trim($aRow['first_name'])!='' || trim($aRow['last_name'])!=''){
							$row[] = '<a href="'. base_url().'admin/edit-driver/'.base64_encode($aRow['driver_id']).'"> '.ucfirst(strtolower($aRow['first_name'])).'</a>'; 	
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='driver_email'){
						if(trim($aRow['driver_email'])!='')
						{
							$row[] = '<a href="'. base_url().'admin/edit-driver/'.base64_encode($aRow['driver_id']).'"> '.trim($aRow['driver_email']).'</a>'; 	
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='user_status'){
						if($aRow['user_status']=="1")
						{
							$row[] = 'Activated';
						}else{
							$row[] = 'Blocked';
						}
					}/* elseif($data['select_order_colum'][$i]=='login_status'){
						if($aRow['login_status']=="1")
						{
							$row[] = '<button class="btn btn-success btn-sm">Online</button>';
						}else{
							$row[] = '<button class="btn btn-danger btn-sm">Offline</button>';
						}
					} */elseif($data['select_order_colum'][$i]=='average_ratings'){
						$row[] = '<div class="star_rating">
									<span style="width:'.($aRow['average_ratings']*20).'%;" class="star_ratings_sprite"></span>
								</div>';
					}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
				}else
				{
					 $html = '';
					 /* if($this->site_santry->get_auth_data('user_type')=='admin'){
						$html .='&nbsp<span><a title="Change Password" class="btn btn-color button_icon" href="'.base_url().'admin/users-change-password/'.base64_encode($aRow['id']).'"><i class="fa fa-unlock-alt"></i></a></span>';
						
					 } */
					 if($aRow['user_status']=="1")
					 {
						 $html .='&nbsp<span><a title="Active (Click to deactivate)" class="btn btn-color button_icon" href="'.base_url('admin/Partners/change_activation_status/').base64_encode($aRow['user_id']).'/'.base64_encode($aRow['user_status']).'"><i class="fa fa-check"></i></a></span>';
					 }else{
						 $html .='&nbsp<span><a title="Deactive (Click to Activate)" class="btn btn-color button_icon" href="'.base_url('admin/Partners/change_activation_status/').base64_encode($aRow['user_id']).'/'.base64_encode($aRow['user_status']).'"><i class="fa fa-ban"></i></a></span>';
					 }
					 $html .='&nbsp<span><a title="Reject Partner" class="btn btn-danger reject_Partner_request_confirm" href="'.base_url().'admin/reject-partner-request/'.base64_encode($aRow['user_id']).'"><i class="mdi mdi-unlock-alt"></i>Reject</a>
							</span>';
					//$name = $aRow['user_name'];
					/* $row[] = '
					<div class="action_box display-action" style="width: max-content;">
					
						<span><a href="'.base_url('admin/edit-driver/').base64_encode($aRow['driver_id']).'" data-driver_id="'.base64_encode($aRow['driver_id']).'" class="button_icon viewDetail" title="Edit"><i class="fa fa-edit"></i></a></span>
						&nbsp
						'.$html.'
					</div>'; */
					/* &nbsp
						<span class="pad_0"><a href="javascript:void(0);" onclick=deleteuser('.$aRow['id'].'); class="btn del-color btn-lg delate_but button_icon" title="Delete"><i class="fa fa-fw fa-trash"></i></a></span>
						
					   &nbsp 
						
						<span><a href="'. base_url().'admin/users/rsm_sales_person_view/'.base64_encode($aRow['id']).'" class="btn btn-color button_icon" title="View"><i class="fa fa-file-text-o"></i></a></span>
						&nbsp  */
					$row[] = $html ;
				}
			}
			$output['aaData'][] = $row;
		}
		return $output;
	}
	
	public function list_software_count($software_where) {
		$where = str_replace('where',' ',$software_where);
		$this->db->select('user_id');
		$this->db->from('admin_users');
		$this->db->where($where);
		$result = $this->db->get();
		
		return $result->num_rows();
	}

	public function getAllPartnersRequests($data)
	{
		//pr($data,1);
	    $software_where  = 'where 1 And user_delete = "0" And user_type = "partner" And partner_request_status = "'.strtoupper($data['partner_request_status']) .'" '; 
		
		/* if($data['user_type']) {
			$software_where .= " And admin_users.user_type LIKE '%".trim($data['user_type'])."%' "; 
		}  */
		$data['where_coloums'] 	= array('serial_number','contact_number','user_email','first_name','partner_request_status');
        $data['select_order_colum'] = array('serial_number','contact_number','user_email','first_name','you_are','social_media_link','description','partner_request_status');
			
		$data['table_name']   		= "admin_users";
		$data['indexColumn']  		= "user_id";
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
				echo $where .= $$data['where_coloums'][$i]." LIKE '%".@trim($data['post']['sSearch_'.$i])."%' ";
			}
		}
		$findstr_uname = strpos($order_by,"user_name");
		if($findstr_uname!=''){
			if($findstr_uname=='user_name'){
				$order_by = " ORDER BY user_id DESC";
			}
		}else{
			$order_by= " ORDER BY user_id DESC";
		}
		
		$query = "SELECT * "." FROM "." admin_users "." ".$software_where.$where." ".$order_by.' '.$limit;
		/*      echo $query;
		  die;  */   
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_partner_request_count($software_where);
		
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
					}elseif($data['select_order_colum'][$i]=='first_name'){
						if(trim($aRow['first_name'])!='')
						{
							$row[] = ucfirst(strtolower($aRow['first_name']));	
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='user_email'){
						if(trim($aRow['user_email'])!='')
						{
							$row[] = $aRow['user_email'];	
						}else{
							$row[] = '-';
						}
					}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
				}else
				{
					$html = '';
						if($this->site_santry->get_auth_data('user_type')=='admin' && $data['partner_request_status'] == "pending"){
							$html .='&nbsp<span><a title="Accept Partner" class="btn btn-success accept_Partner_request_confirm" href="'.base_url().'admin/accept-partner-request/'.base64_encode($aRow['user_id']).'"><i class="mdi mdi-unlock-alt"></i>Approve</a>
										&nbsp<a title="Reject Partner" class="btn btn-danger reject_Partner_request_confirm" href="'.base_url().'admin/reject-partner-request/'.base64_encode($aRow['user_id']).'"><i class="mdi mdi-unlock-alt"></i>Reject</a>
							</span>';
							
						}else if($this->site_santry->get_auth_data('user_type')=='admin' && $data['partner_request_status'] == "rejected"){
							$html .='&nbsp<span><a title="Reject Partner" class="btn btn-success accept_Partner_request_confirm" href="'.base_url().'admin/accept-partner-request/'.base64_encode($aRow['user_id']).'"><i class="mdi mdi-unlock-alt"></i>Approve</a>
							</span>';
							
						}
						
						
					$row[] = $html ;
						/* &nbsp
						<span class="pad_0"><a href="javascript:void(0);" onclick=deleteuser('.$aRow['id'].'); class="btn del-color btn-lg delate_but button_icon" title="Delete"><i class="fa fa-fw fa-trash"></i></a></span>
						
					   &nbsp */
				}
			}
		
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	
	
	public function list_partner_request_count($software_where) {
		$where = str_replace('where','',$software_where);
		$this->db->select('user_id');
		$this->db->from('admin_users');
		$this->db->where($where);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
		 return $result->num_rows();
		}else{
		 return '0';
		}
	}
	
	
	public function getPartnerDetail($partner_id="") {
		if($partner_id!=''){
			$this->db->select('au.*');
			$this->db->from('admin_users au');
			$this->db->where('au.user_status','1');
			$this->db->where('au.user_delete','0');
			$this->db->where('au.user_id',$partner_id);
			$get = $this->db->get();
			//echo $this->db->last_query();die;
			if($get->num_rows() > 0) {
				$result=$get->row_array();
				return $result;
			}else{
				return false;
			}
		}else {
			return false;	
		}
	}
	
}
?>
