<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Promocodes_model extends CI_Model{
	
	public function getAllPromocodes($data)
	{
        $software_where  	= 'where 1 AND c.delete_status = "0" AND au.user_delete = "0" '; 
		
	   $data['where_coloums'] 	    = array('serial_number','promocode_title');
       $data['select_order_colum']  = array('serial_number','admin_name','promocode_title','promocode','number_of_times_use','tokens','totalTimesUsed','status');
		$data['table_name']   		= "promocodes";
		$data['indexColumn']  		= "promocode_id";
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
				$order_by = "ORDER BY promocode_id DESC";
			}
		}
		$where = "";
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=1 ; $i<count($data['where_coloums']) ; $i++ )
			{
				$where .= $data['where_coloums'][$i]." LIKE '%".mysqli_real_escape_string($this->db->conn_id,$data['post']['sSearch'] )."%' OR ";
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
		
		/* $query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.*,u.full_name, CONCAT(au.first_name,' ',au.last_name) as admin_name FROM "."
		promocodes c
		LEFT JOIN tbl_usermaster u ON u.id = c.user_id
		LEFT JOIN admin_users au ON au.user_id = c.admin_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit; */
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.*,getTotalUsedPromocode(c.promocode_id) as totalTimesUsed,CONCAT(au.first_name,' ',au.last_name) as admin_name FROM "."
		promocodes c
		LEFT JOIN admin_users au ON au.user_id = c.admin_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_promocodes_count();
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
			for ( $i=0; $i<count($data['select_order_colum']) ; $i++ )
			{
				if ($data['select_order_colum'][$i] != ' ' )
				{
				   if($data['select_order_colum'][$i] == "serial_number")
				   {
					   	$row[] = $j;
				   }elseif($data['select_order_colum'][$i]=='is_promocode_used'){
				   		if($aRow['is_promocode_used']=='1')
				   		{
				   			$row[] = 'Used On - '.date('d/m/Y h:i A',strtotime($aRow['used_on']));
				   		}else{
				   			$row[] = 'Not Used Yet';
				   		}
				   }elseif($data['select_order_colum'][$i]=='totalTimesUsed'){
				   		if($aRow['totalTimesUsed']=='0')
				   		{
				   			$row[] = '0';
				   		}else{
				   			$row[] = $aRow['totalTimesUsed'];
				   		}
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }elseif($data['select_order_colum'][$i]=='promocode_image'){
				   		if($aRow['promocode_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/promocodes/'.$aRow['promocode_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'promocodes/'.$aRow['promocode_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'promocodes/default_promocode.png" width="50">';
				   		}
				   }
				   else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/edit-promocode/'.base64_encode($aRow['promocode_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
					}else{
						$iconName = 'check';
					}
					
					$action .= 
						'<span>
							<a href="'. base_url().'admin/promocodes/change_activation_status/'.base64_encode($aRow['promocode_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_del" title="Delete Promocode"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					if($aRow['totalTimesUsed']>0){
						$action .= 
						'<span>
							<a href="'. base_url().'admin/user-promocodes/'.base64_encode($aRow['promocode_id']).'" class="btn btn-color button_icon btn-info" title="View Users Promocode">View Users</a>
						</span>';
					}
					$action .= '</div>';
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	public function list_promocodes_count() {
		
		$this->db->select('promocode_id');
		$this->db->from('promocodes');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getAllUserPromocodes($data){
		//pr($data,1);
		$software_where  	= 'where 1 AND up.delete_status = "0" AND um.delete_status = "0" AND up.promocode_id='.$data['promocode_id'].' '; 
		$promocode_id = $data['promocode_id'];
		
		if($data['user_name_email'] != ""){
			$software_where .= "AND concat_ws(' ',um.firstname,um.lastname) LIKE '%".trim($data['user_name_email'])."%' "; 
		}
		if($data['promocode_used_date'] != ""){
			$promocode_dates = explode(' - ',$data['promocode_used_date']); 
			//pr($promocode_dates,1);
			$software_where .= " AND up.created_on BETWEEN '".date('Y-m-d',strtotime(str_replace('/', '-',$promocode_dates[0])))."' AND '".date('Y-m-d',strtotime(str_replace('/', '-',$promocode_dates[1])))."' ";
		}
		
		$data['where_coloums'] 	    = array('serial_number','promocode');
		$data['select_order_colum']  = array('serial_number','promocode','full_name','emailid','tokens','created_on');
		$data['table_name']   		= "user_promocodes";
		$data['indexColumn']  		= "user_promocode_id";
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
				$order_by = "ORDER BY user_promocode_id DESC";
			}
		}
		$where = "";
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=1 ; $i<count($data['where_coloums']) ; $i++ )
			{
				$where .= $data['where_coloums'][$i]." LIKE '%".mysqli_real_escape_string($this->db->conn_id,$data['post']['sSearch'] )."%' OR ";
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
		
		/* $query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.*,u.full_name, CONCAT(au.first_name,' ',au.last_name) as admin_name FROM "."
		promocodes c
		LEFT JOIN tbl_usermaster u ON u.id = c.user_id
		LEFT JOIN admin_users au ON au.user_id = c.admin_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit; */
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,up.*,p.promocode,CONCAT(um.firstname,' ',um.lastname) as full_name,um.emailid FROM "."
		user_promocodes up
		LEFT JOIN promocodes p ON p.promocode_id = up.promocode_id
		LEFT JOIN tbl_usermaster um ON um.id = up.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		//echo $query; die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_user_promocodes_count($promocode_id);
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
			for ( $i=0; $i<count($data['select_order_colum']) ; $i++ )
			{
				if ($data['select_order_colum'][$i] != ' ' )
				{
				   if($data['select_order_colum'][$i] == "serial_number")
				   {
					   	$row[] = $j;
				   }elseif($data['select_order_colum'][$i]=='created_on'){
				   		if($aRow['created_on']!='')
				   		{
				   			$row[] = date('d/m/Y h:i A',strtotime($aRow['created_on']));
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='promocode'){
				   		if($aRow['promocode']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/edit-promocode/'.base64_encode($aRow['promocode_id']).'" class="text-dark" title="View Promocode Details">'.$aRow['promocode'].'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='full_name'){
				   		if($aRow['full_name']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark" title="View User Details">'.$aRow['full_name'].'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='emailid'){
				   		if($aRow['emailid']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark" title="View User Details">'.$aRow['emailid'].'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }
				   else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '';				
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	public function list_user_promocodes_count($promocode_id) {
		
		$this->db->select('user_promocode_id');
		$this->db->from('user_promocodes');
		$this->db->where('promocode_id',$promocode_id);
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function promocode_details($condition) {
		
		$this->db->select('c.*');
		$this->db->from('promocodes c');
		$this->db->where($condition);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			$details = $result->result_array();
			$details = $details[0];
			return $details;
		}else{
			return array();
		}
	}

	public function users_dropdown_list() {
		
		$this->db->select('*');
		$this->db->from('tbl_usermaster');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->where('account_confirm','C');
		$result = $this->db->get();
		$resulArray = $result->result_array();
		if($resulArray > 0) {
			$promocode=array(''=>'-- Select User --');
			foreach ($resulArray as $val) {
				$promocode[$val['id']]=$val['full_name'];
			}
			return $promocode;
		}else{
			return array();
		}
	}	

	public function addPromocode($post){
		//pr($post,1);
		if($post){
			
			$number_of_promocodes = $post['number_of_promocodes'];
			$inserted_promocode = 0;
			$result = 	$this->addNumberofPromocodes($number_of_promocodes,$post);
				
			return true;
		}else{
			return false;
			
		}
		return false;
	}
	
	public function addNumberofPromocodes($number_of_promocodes,$post){
		//pr($number_of_promocodes,1);
		$inserted_promocode = 0;
		for($i=0;$i<$number_of_promocodes;$i++){
			$promocode ="";
			$promocode = $this->getPromoCode();
			//pr($promocode,1);
			if($alredyExits = $this->commonmodel->_get_data_row('promocode_id','promocodes',array('promocode' => $promocode))){
				$inserted_promocode--;
			}else{
				$data = array();
				$admin_id    = $this->site_santry->get_auth_data('id');
				$data['admin_id'] 			 =	 $admin_id;
				$data['number_of_times_use'] =	 trim($post['number_of_times_use']);
				$data['promocode_title']  	 =	 trim($post['promocode_title']);
				$data['promocode']  		 =	 trim($promocode);
				$data['tokens']  			 =	 trim($post['tokens']);
				$data['created_on']  	 	 =	 date('Y-m-d H:i:s');
				$data['modified_on'] 	 	 =	 date('Y-m-d H:i:s');
				
				$this->commonmodel->_insert('promocodes', $data);
				$inserted_promocode++;
			}
		}
		if($inserted_promocode<$number_of_promocodes){
			$this->addNumberofPromocodes($number_of_promocodes-$inserted_promocode,$post);
		}
		return true;
	}
	
	public function updatePromocodes($post,$promocode_id=""){
		//echo $category_id;
		if($promocode_id!=""){
			$data = array();
			$condition=array('promocode_id'=>$promocode_id);
			$data['number_of_times_use']	 =	 trim($post['number_of_times_use']);
			$data['promocode_title']  		 =	 trim($post['promocode_title']);
			$data['promocode'] 				 =	 trim($post['promocode']);
			$data['tokens'] 			  	 =	 trim($post['tokens']);
			$data['modified_on'] 	 		 =	 date('Y-m-d H:i:s');

			if($post['category_image']){
				$data['category_image'] = trim($post['category_image']);
			}
			$updated=$this->commonmodel->_update('promocodes',$data,$condition);
			if($updated){
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	public function getPromocodeDetailsById($promocode_id=""){
		if($promocode_id!=""){
			$this->db->where('promocode_id',$promocode_id);
			$get=$this->db->get("promocodes");
			if($get->num_rows()>0){
				$result=$get->result_array();			
				return $result[0];
			}else{
				return array();
			}
		}
		else {
			return array();
		}
	}
	
	public function getPromoCode(){
		$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$res = "";
		for ($i = 0; $i < 6; $i++) {
			$res .= $chars[mt_rand(0, strlen($chars)-1)];
		}
		return $res;
	}
}
?>
