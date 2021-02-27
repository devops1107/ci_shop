<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Rooms_model extends CI_Model{
	
	public function getAllRooms($data)
	{
	    $user_id            = $this->site_santry->get_auth_data('id');
		$user_type		    = $this->site_santry->get_auth_data('user_type');
        $software_where  	= 'where 1 AND c.delete_status = "0" AND ct.delete_status = "0" ';
        if($user_type=="partner"){
		    $software_where  	.= " AND c.user_id = ".$user_id."";
		}
		$status = $this->uri->segment(3);
		if($status=='active')
		{
			$software_where  	.= ' AND c.event_completed = "0" AND c.start_datetime <= "'.date('Y-m-d H:i:s').'" ';
		}elseif($status=='queued')
		{
			$software_where  	.= ' AND c.event_completed = "0" AND c.start_datetime > "'.date('Y-m-d H:i:s').'" ';
		}elseif($status=='expired')
		{
			$software_where  	.= ' AND c.event_completed = "1" ';
		}
		
		if($data['room_name'] != ""){
			$software_where .= ' AND c.room_name Like "%'.$data['room_name'].'%"  ';
		}	
		if($data['room_category'] != ""){
			$software_where .= ' AND c.category_id = "'.$data['room_category'].'" ';
		}
		if($data['room_status'] != ""){
			$software_where .= ' AND c.status = "'.$data['room_status'].'" ';
		}	
		/* if($data['start_date_filter'] != ""){
			$start_date_filters = explode(' - ',$data['start_date_filter']);
			$software_where .= " AND c.start_date BETWEEN '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$start_date_filters[0])))."' AND '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$start_date_filters[1])))."' ";
		}
		if($data['end_date_filter'] != ""){
			$end_date_filters = explode(' - ',$data['end_date_filter']);
			$software_where .= " AND c.end_date BETWEEN '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$end_date_filters[0])))."' AND '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$end_date_filters[1])))."' ";
		} */	
		if($user_type!='partner'){
		    
		    $data['where_coloums'] 	    = array('serial_number','category_name','room_name');
            $data['select_order_colum']  = array('serial_number','room_id','partner_name','category_name','room_image','room_name','per_ticket_tokens','available_tickets','start_end_date','status');
		}else{
		    $data['where_coloums'] 	    = array('serial_number','category_name','room_name');
            $data['select_order_colum']  = array('serial_number','room_id','category_name','room_image','room_name','per_ticket_tokens','available_tickets','start_end_date','status');
		}
	   
		$data['table_name']   		= "rooms";
		$data['indexColumn']  		= "c.room_id";
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
				$order_by = "ORDER BY c.room_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.*,ct.category_name,au.first_name as partner_name FROM "."
		rooms c
		LEFT JOIN categories ct ON c.category_id=ct.category_id
		LEFT JOIN admin_users au ON au.user_id=c.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		//echo $query; die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_rooms_count($software_where);
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
				   }elseif($data['select_order_colum'][$i] == "room_name")
				   {
					   	$row[] = '<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['room_id']).'" class="text-dark" title="View Details">'.$aRow['room_name'].'</a>';
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }elseif($data['select_order_colum'][$i]=='start_end_date')
				   {
				   		$row[] = date('d/M/Y h:i A',strtotime($aRow['start_datetime']));
				   }elseif($data['select_order_colum'][$i]=='room_image'){
				   		if($aRow['room_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/rooms/'.$aRow['room_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'rooms/'.$aRow['room_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'rooms/default_room.png" width="50">';
				   		}
				   }else if($data['select_order_colum'][$i]=='partner_name'){
				       if($aRow['user_id']=='0')
				   		{
				   			$row[] = 'Admin';
				   		}else{
				   			$row[] = $aRow['partner_name'];
				   		}
				   }
				   else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action" style="width: max-content;">';				
					//<a href="'. base_url().'admin/copy-room/'.base64_encode($aRow['room_id']).'" class="btn btn-color button_icon" title="Copy Room"><i class="mdi mdi-content-copy"></i></a>
					if($status!='expired' && $aRow['entry_key']=='')
					{
						$action .= 
						'<span>
							<a href="'. base_url().'admin/send-entry-key/'.base64_encode($aRow['room_id']).'" class="btn btn-color button_icon" title="Send Entry Key"><i class="mdi mdi-key"></i></a>
						';
					}elseif($status!='expired'){
						$action .= 
						'<span>
							<a href="'. base_url().'admin/complete-event/'.base64_encode($aRow['room_id']).'" class="btn btn-color button_icon" title="Complete Event"><i class="mdi mdi-calendar"></i></a>
						';
					}else{
						$action .= 
						'<span>
							<a href="'. base_url().'admin/completed-event-details/'.base64_encode($aRow['room_id']).'" class="btn btn-color button_icon" title="View Completed Event Users"><i class="mdi mdi-calendar"></i></a>
						';
					}
					$action .= 
						'<span>
							<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['room_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-eye"></i></a>
						';
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
					}else{
						$iconName = 'check';
					}
					$action .= 
						'
							<a href="'. base_url().'admin/rooms/change_activation_status/'.base64_encode($aRow['room_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_del" title="Delete Room"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					
					$action .= 
						'<span>
							<a href="'. base_url().'admin/joined-users/'.base64_encode($aRow['room_id']).'" class="btn btn-color btn-success button_icon" title="View Joined Users">View Users</i></a>
						';
					$action .= '</div>';
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	
	public function list_rooms_count($software_where) {
		$where = str_replace('where',' ',$software_where);
		$this->db->select('c.room_id');
		$this->db->from('rooms c');
		$this->db->join('categories ct','c.category_id=ct.category_id','left');
		$this->db->where($where);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}

	
	public function room_details($condition) {
		
		$this->db->select('c.*');
		$this->db->from('rooms c');
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

	public function categories_dropdown_list() {
		
		$this->db->select('*');
		$this->db->from('categories');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			$resulArray = $result->result_array();
			$room=array(''=>'-- All Event Types --');
			foreach ($resulArray as $val) {
				$room[$val['category_id']]=$val['category_name'];
			}
			return $room;
		}else{
			return array();
		}
	}

	


	public function room_dropdown(){
		$this->db->select('appointment_status');
		$this->db->from('appointments');
        $this->db->group_by('appointment_status');
		$qry = $this->db->get();
		if($qry->num_rows() > 0) {
		 $result=$qry->result_array();
		 return $result;
		}else{
		 return array();
		}	
	}

	public function addRooms($post,$is_copy='',$room_id=''){
		if($post){
			//pr($post,1);
			$data = array();
			$data['category_id']	 	=	 trim($post['category_id']);
			$data['room_name']	 		=	 trim($post['room_name']);
			$data['room_name_gr']	 	=	 trim($post['room_name_gr']);
			$user_type = $this->site_santry->get_auth_data('user_type');
			$user_id = $this->site_santry->get_auth_data('id');
			//if($user_type == 'partner'){
			    $data['user_id']	 	=	 $user_id;
			//}
			
			if($is_copy=='copy')
			{
				if(isset($post['room_image']))
				{
					$data['room_image']  =	 trim($post['room_image']);
				}else{
					$roomImageDetail = $this->commonmodel->_get_data('rooms',array('room_id'=>$room_id),'room_image');
					$data['room_image'] = $roomImageDetail[0]['room_image'];
				}
			}else{

				$data['room_image']  =	 trim($post['room_image']);
			}
			//$data['short_description']  =	 trim($post['short_description']);
			
			/* $start_end_date = $post['start_end_date'];
			$start_end_dates = explode(' - ', $start_end_date);
			$data['start_date'] = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $start_end_dates[0])));
			$data['end_date'] = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $start_end_dates[1]))); */
			/*if(isset($post['is_purchase_allowed']))
			{
				$data['is_purchase_allowed']  =	'1';
				$data['direct_purchase_tokens']  =	trim($post['direct_purchase_tokens']);
				
			}else{
				$data['is_purchase_allowed']  =	'0';
				$data['direct_purchase_tokens']  =	0;
			}
			if(isset($post['is_terms_conditions_vidible']))
			{
				$data['is_terms_conditions_vidible']  =	'1';
			}else{
				$data['is_terms_conditions_vidible']  =	'0';
			}*/
			//$data['terms_condition']  =	 trim($post['terms_condition']);
			$data['grand_loot_price_value']  =	 trim($post['grand_loot_price_value']);
			$data['start_datetime']  =	 date('Y-m-d H:i:s',strtotime($post['start_end_date']));
			//$data['secoundry_prize_tokens']  =	 trim($post['secoundry_prize_tokens']);
			$data['secoundry_prize_value']  =	 trim($post['secoundry_prize_value']);
			$data['third_price_value']  =	 trim($post['third_price_value']);
			$data['per_ticket_tokens']  =	 trim($post['per_ticket_tokens']);
			//$data['per_user_allowed_purchase']  =	 trim($post['per_user_allowed_purchase']);
			$data['available_tickets']  =	 trim($post['available_tickets']);
			//$data['affiliate_link']  =	 trim($post['affiliate_link']);
			$data['created_on']  	 =	 date('Y-m-d H:i:s');
			$data['modified_on'] 	 =	date('Y-m-d H:i:s');
			//pr($data,1);
			//pr($post,1);
			if($this->commonmodel->_insert('rooms', $data)){
				/*$room_id = $this->db->insert_id(); 
				if($post['start_end_date']){
					foreach($post['start_end_date'] as $row){
						$start_end_date = $row;
						$start_end_dates = explode(' - ', $start_end_date);
						$drawings = array(
							'room_id'	=>	$room_id,
							'start_date'	=>	date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $start_end_dates[0]))),
							'end_date'		=>	date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $start_end_dates[1]))),
							'created_on'	=>	date('Y-m-d H:i:s'),
							
						);
						$this->commonmodel->_insert('rooms_drawings', $drawings);
					}
				}*/
			
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}

	
	public function updateRooms($post,$room_id=""){
		//echo $room_id;
		if($room_id!=""){
			$data = array();
			$condition=array('room_id'=>$room_id);
			$data = array();
			$data['category_id']	 =	 trim($post['category_id']);
			$data['room_name']	 =	 trim($post['room_name']);
			$data['room_name_gr']	 =	 trim($post['room_name_gr']);
			//$data['short_description']  =	 trim($post['short_description']);

			/* $start_end_date = $post['start_end_date'];
			$start_end_dates = explode(' - ', $start_end_date);
			$data['start_date'] = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $start_end_dates[0])));
			$data['end_date'] = date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $start_end_dates[1]))); */
			/*if(isset($post['is_purchase_allowed']))
			{
				$data['is_purchase_allowed']  =	'1';
				$data['direct_purchase_tokens']  =	trim($post['direct_purchase_tokens']);
				
			}else{
				$data['is_purchase_allowed']  =	'0';
				$data['direct_purchase_tokens']  =	0;
			}
			if(isset($post['is_terms_conditions_vidible']))
			{
				$data['is_terms_conditions_vidible']  =	'1';
			}else{
				$data['is_terms_conditions_vidible']  =	'0';
			}*/
			//$data['terms_condition']  =	 trim($post['terms_condition']);
			$data['start_datetime']  =	 date('Y-m-d H:i:s',strtotime($post['start_end_date']));
			$data['grand_loot_price_value']  =	 trim($post['grand_loot_price_value']);
			//$data['secoundry_prize_tokens']  =	 trim($post['secoundry_prize_tokens']);
			$data['secoundry_prize_value']  =	 trim($post['secoundry_prize_value']);
			$data['third_price_value']  =	 trim($post['third_price_value']);
			$data['per_ticket_tokens']  =	 trim($post['per_ticket_tokens']);
			//$data['per_user_allowed_purchase']  =	 trim($post['per_user_allowed_purchase']);
			$data['available_tickets']  =	 trim($post['available_tickets']);
			//$data['affiliate_link']  =	 trim($post['affiliate_link']);
			$data['modified_on'] 	 =	date('Y-m-d H:i:s');
			if(isset($post['room_image'])){
				$data['room_image'] = trim($post['room_image']);
			}
			$updated=$this->commonmodel->_update('rooms',$data,$condition);
			if($updated){
				/*if(isset($post['old_start_end_date']) && $post['old_start_end_date']){
					foreach($post['old_start_end_date'] as $key=>$row){
						$old_start_end_date = $row;
						$old_start_end_date = explode(' - ', $old_start_end_date);
						$drawings = array(
							'room_id'	=>	$room_id,
							'start_date'	=>	date('Y-m-d',strtotime(str_replace('/', '-', $old_start_end_date[0]))),
							'end_date'		=>	date('Y-m-d',strtotime(str_replace('/', '-', $old_start_end_date[1]))),
							'modified_on'	=>	date('Y-m-d H:i:s'),
							
						);
						$this->commonmodel->_update('rooms_drawings', $drawings,array('room_drawing_id'=>$key));
					}
				}
				if(isset($post['old_end_date']) && $post['old_end_date']){
					$data = array(
						'end_date'=>date('Y-m-d',strtotime(str_replace('/', '-',$post['old_end_date'][$post['old_end_date_id']]))),
						'modified_on'=>date('Y-m-d H:i:s')
					);
					//pr($data,1);
					$this->commonmodel->_update('rooms_drawings',$data,array('room_drawing_id'=>$post['old_end_date_id']));
				}
				if(isset($post['start_end_date']) && $post['start_end_date'][0]){
					foreach($post['start_end_date'] as $row){
						$start_end_date = $row;
						$start_end_dates = explode(' - ', $start_end_date);
						$drawings = array(
							'room_id'	=>	$room_id,
							'start_date'	=>	date('Y-m-d',strtotime(str_replace('/', '-', $start_end_dates[0]))),
							'end_date'		=>	date('Y-m-d',strtotime(str_replace('/', '-', $start_end_dates[1]))),
							'created_on'	=>	date('Y-m-d H:i:s'),
							
						);
						$this->commonmodel->_insert('rooms_drawings', $drawings);
					}
				}*/
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function sendEnterKey($room_id,$post)
	{
		$this->db->select('jm.*,um.emailid,um.fcm_id,um.full_name,r.room_name');
		$this->db->from('joined_matches jm');
		$this->db->join('usermaster um','um.id=jm.user_id','left');
		$this->db->join('rooms r','r.room_id=jm.room_id','left');
		$this->db->where('jm.room_id',$room_id);
		$this->db->where('jm.status','1');
		$this->db->where('jm.delete_status','0');
		$get = $this->db->get();
		if($get->num_rows()>0){
			$result=$get->result_array();
			if(!empty($result))
			{
				$entry_key = $post['entry_key'];
				$this->commonmodel->_update('rooms',array('entry_key'=>$entry_key,'modified_on'=>date('Y-m-d H:i:s')),array('room_id'=>$room_id));
				$finalArr = array();
				foreach ($result as $key => $value) {
					$tempArr = array();
					$tempArr['room_id'] = $value['room_id'];
					$tempArr['user_id'] = $value['user_id'];
					$tempArr['action'] 	= 'ENTRY_KEY_ENTERED';
					$tempArr['created_on'] = date('Y-m-d H:i:s');
					$tempArr['modified_on'] = date('Y-m-d H:i:s');
					$finalArr[] = $tempArr;
					
					$to = $value['emailid'];
					$user_name = $value['full_name'];
					$subject = SITE_NAME." match Entry Key Entered";
					$message =	'Hello '.$user_name.'<br/>';
					$message .= 'Your joined match '.$value['room_name'].' key has been entered. The key is "'.$entry_key.'". <br/> <br/>';
					$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
					$message .= 'We hope you enjoy our services!<br/><br/>';
					$message .= 'Sincerely<br/>';
					$message .= SITE_NAME.' Team';
					$mail_confirm = $this->sendemail($to,$subject,$message);
					
					$user_id = $value['user_id'];
            		$message = 'Your joined match '.$value['room_name'].' key has been entered. The key is "'.$entry_key.'".';
            		$title = 'Match Entry Key Entered';
            		$type = 'ENTRY_KEY_ENTERED';
            		sendApnsPushNotification($user_id,$message,$title,$type);
				}
				if(!empty($finalArr))
				{
					$this->commonmodel->_insert_batch('user_notifications',$finalArr);
				}
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function completeEvent($room_id,$post)
	{
		$roomDetails = $this->getRoomDetailsById($room_id);
		//pr($post,1);
		/*pr($roomDetails,1);*/
		$notificationArr = array();
		$notiLog  = array();
		if($roomDetails['total_joiners']>=3){
    		$insertArr = array(
				array(
					'user_id'=>$post['user_id_first'],
					'room_id'=>$room_id,
					'winning_amount'=>$roomDetails['grand_loot_price_value'],
					'price_type'=>'FIRST',
					'kills'=>$post['kills_first'],
					'rank'=>$post['rank_first'],
					'category_id'=>$roomDetails['category_id'],
					'room_name'=>$roomDetails['room_name'],
					'room_image'=>$roomDetails['room_image'],
					'created_on'=>date('Y-m-d H:i:s'),
					'modified_on'=>date('Y-m-d H:i:s'),
					),
				array(
					'user_id'=>$post['user_id_second'],
					'room_id'=>$room_id,
					'winning_amount'=>$roomDetails['secoundry_prize_value'],
					'price_type'=>'SECOND',
					'kills'=>$post['kills_second'],
					'rank'=>$post['rank_second'],
					'category_id'=>$roomDetails['category_id'],
					'room_name'=>$roomDetails['room_name'],
					'room_image'=>$roomDetails['room_image'],
					'created_on'=>date('Y-m-d H:i:s'),
					'modified_on'=>date('Y-m-d H:i:s'),
					),
				array(
					'user_id'=>$post['user_id_third'],
					'room_id'=>$room_id,
					'winning_amount'=>$roomDetails['third_price_value'],
					'price_type'=>'THIRD',
					'kills'=>$post['kills_third'],
					'rank'=>$post['rank_third'],
					'category_id'=>$roomDetails['category_id'],
					'room_name'=>$roomDetails['room_name'],
					'room_image'=>$roomDetails['room_image'],
					'created_on'=>date('Y-m-d H:i:s'),
					'modified_on'=>date('Y-m-d H:i:s'),
				),
			);
		}else if($roomDetails['total_joiners']==2){
		    	$insertArr = array(
					array(
						'user_id'=>$post['user_id_first'],
						'room_id'=>$room_id,
						'winning_amount'=>$roomDetails['grand_loot_price_value'],
						'price_type'=>'FIRST',
						'kills'=>$post['kills_first'],
						'rank'=>$post['rank_first'],
						'category_id'=>$roomDetails['category_id'],
						'room_name'=>$roomDetails['room_name'],
						'room_image'=>$roomDetails['room_image'],
						'created_on'=>date('Y-m-d H:i:s'),
						'modified_on'=>date('Y-m-d H:i:s'),
						),
					array(
						'user_id'=>$post['user_id_second'],
						'room_id'=>$room_id,
						'winning_amount'=>$roomDetails['secoundry_prize_value'],
						'price_type'=>'SECOND',
						'kills'=>$post['kills_second'],
						'rank'=>$post['rank_second'],
						'category_id'=>$roomDetails['category_id'],
						'room_name'=>$roomDetails['room_name'],
						'room_image'=>$roomDetails['room_image'],
						'created_on'=>date('Y-m-d H:i:s'),
						'modified_on'=>date('Y-m-d H:i:s'),
						),
    			);
    			
		}else{
		    $insertArr = array(
				array(
					'user_id'=>$post['user_id_first'],
					'room_id'=>$room_id,
					'winning_amount'=>$roomDetails['grand_loot_price_value'],
					'price_type'=>'FIRST',
					'kills'=>$post['kills_first'],
					'rank'=>$post['rank_first'],
					'category_id'=>$roomDetails['category_id'],
					'room_name'=>$roomDetails['room_name'],
					'room_image'=>$roomDetails['room_image'],
					'created_on'=>date('Y-m-d H:i:s'),
					'modified_on'=>date('Y-m-d H:i:s'),
					),
			);
			
		}
		//pr($insertArr,1);
		$this->commonmodel->_insert_batch('room_winners',$insertArr);
		    $this->db->select('jm.*,um.emailid,um.fcm_id,um.full_name,r.room_name');
    		$this->db->from('joined_matches jm');
    		$this->db->join('usermaster um','um.id=jm.user_id','left');
    		$this->db->join('rooms r','r.room_id='.$room_id.'','left');
    		$this->db->where('jm.room_id',$room_id);
    		$this->db->where('jm.status','1');
    		$this->db->where('jm.delete_status','0');
    		$get = $this->db->get();
    		if($get->num_rows()>0){
    			$result=$get->result_array();
    			foreach ($result as $key => $value) {
					$tempArr = array();
					$tempArr['room_id'] = $value['room_id'];
					$tempArr['user_id'] = $value['user_id'];
					$tempArr['action'] 	= 'WINNER_ANNOUNCED';
					$tempArr['created_on'] = date('Y-m-d H:i:s');
					$tempArr['modified_on'] = date('Y-m-d H:i:s');
					$finalArr[] = $tempArr;
					
					$to = $value['emailid'];
					$user_name = $value['full_name'];
					$subject = SITE_NAME." Winners Announced";
					$message =	'Hello '.$user_name.'<br/>';
					$message .= 'Winner has been announced of your joined match '.$value['room_name'].'. <br/> <br/>';
					$message .= 'Please do not hesitate to contact us at '.CONTACT_US_ADMIN_EMAIL.' with any questions or concerns. <br/>';
					$message .= 'We hope you enjoy our services!<br/><br/>';
					$message .= 'Sincerely<br/>';
					$message .= SITE_NAME.' Team';
					$mail_confirm = $this->sendemail($to,$subject,$message);
					
					$user_id = $value['user_id'];
            		$message = 'Winner has been announced of your joined match '.$value['room_name'].'.';
            		$title = 'Winners announced';
            		$type = 'WINNER_ANNOUNCED';
            		sendApnsPushNotification($user_id,$message,$title,$type);
				}
				if(!empty($finalArr))
				{
					$this->commonmodel->_insert_batch('user_notifications',$finalArr);
				}
    		}
    		$room_arr = array();
    		$room_arr['event_completed'] =  '1';
    		if(isset($post['match_video']) && $post['match_video']!=""){
    		    $room_arr['matchVideoURL'] = $post['match_video'];
    		}
    		$room_arr['modified_on'] =  date('Y-m-d H:i:s');
    		$saveData = $this->commonmodel->_update('rooms',$room_arr,array('room_id'=>$room_id));
		    return true;
	}

	public function getRoomDetailsById($room_id=""){
		//pr($room_id,1);
		if($room_id!=""){
			$this->db->select('ss.*,getTotalSoldTickets(ss.room_id) as total_joiners,c.category_name,COUNT(room_winner_id) as winners,au.first_name as partner_name');
			$this->db->from('rooms as ss');
			$this->db->join('categories as c',"c.category_id=ss.category_id",'left');
			$this->db->join('room_winners as sw',"sw.room_id=ss.room_id",'left');
			$this->db->join('admin_users as au',"au.user_id=ss.user_id",'left');
			$this->db->where('ss.room_id',$room_id);
			$get=$this->db->get();
			//echo $this->db->last_query();die;
			if($get->num_rows()>0){
				$result=$get->row_array();
				/*$result['start_date'] = "";
				$result['end_date'] = "";
				$this->db->select('*');
				$this->db->from('rooms_drawings as sd');
				$this->db->where('sd.room_id',$room_id);
				$query=$this->db->get();
				//pr($query->num_rows(),1);
				if($query->num_rows()>0){
					$drawingResult = $query->result_array();
					foreach($drawingResult as $drawing){
						//pr($drawing);
						//echo var_dump($drawing['start_date']<=date('Y-m-d') && $drawing['end_date']>=date('Y-m-d'));
						if($drawing['start_date']<=date('Y-m-d') && $drawing['end_date']>=date('Y-m-d')){
							$start_date		=	$drawing['start_date'];
							$end_date		=	$drawing['end_date'];
							break;
						}else if($drawing['start_date']>date('Y-m-d')){
							$start_date		=	$drawing['start_date'];
							$end_date		=	$drawing['end_date'];
							break;
						}else{
							$start_date		=	$drawing['start_date'];
							$end_date		=	$drawing['end_date'];
						}
					}
				}
				//pr($start_date,1);
				$result['start_date'] = $start_date; 
				$result['end_date'] = $end_date; 
				//pr($result,1);*/
				return $result;
			}else{
				return array();
			}
		}
		else {
			return array();
		}
	}

	public function getCompletedEventDetailsById($room_id=""){
		//pr($room_id,1);
		if($room_id!=""){
			$this->db->select('room_winners.*,usermaster.full_name');
			$this->db->from('room_winners');
			$this->db->join('tbl_usermaster','usermaster.id=room_winners.user_id','LEFT');
			$this->db->where('room_id',$room_id);
			$this->db->order_by('price_type','ASC');
			$get=$this->db->get();
			//echo $this->db->last_query();die;
			if($get->num_rows()>0){
				$result=$get->result_array();
				return $result;
			}else{
				return array();
			}
		}
		else {
			return array();
		}
	}

	public function getRoomDrawingsById($room_id=""){
		//pr($room_id,1);
		if($room_id!=""){
			$this->db->select('sd.*');
			$this->db->from('rooms_drawings as sd');
			$this->db->where('sd.room_id',$room_id);
			$this->db->where('sd.status','1');
			$get=$this->db->get();
			if($get->num_rows()>0){
				$result=$get->result_array();
				//pr($result,1);
				return $result;
			}else{
				return array();
			}
		}
		else {
			return array();
		}
	}
	
	public function getUsersPurchasedTickets($data)
	{
		//pr($data,1);
		$room_id = base64_decode($this->uri->segment(3));
		$type = $this->uri->segment(4);

		$data['where_coloums'] 	    = array('serial_number','full_name','emailid');

        $software_where	= 'where 1 AND tp.delete_status = "0" AND u.account_confirm = "C" AND u.delete_status = "0" '; 
	    if($data['customer_id']!='')
	    {
	    	$software_where	.= ' AND tp.user_id = "'.$data['customer_id'].'" ';
	    }else if($room_id!='')
	    {
	    	$software_where	.= ' AND tp.room_id = "'.$room_id.'" ';
	    }
	   
	    $data['select_order_colum']  = array('serial_number','full_name','emailid','tokens','created_on');
	    
		$data['table_name']   		= "joined_matches";
		$data['indexColumn']  		= "joined_match_id";
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
				$order_by = "ORDER BY tp.joined_match_id DESC";
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
				echo $where .= $data['where_coloums'][$i]." LIKE '%".trim($data['post']['sSearch_'.$i])."%' ";
			}
		}
		
		if($data['customer_id'] !=""){
			$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,tp.*,sp.room_name,u.full_name,u.emailid FROM "."
			joined_matches tp
			LEFT JOIN tbl_usermaster u ON u.id=tp.user_id
			LEFT JOIN rooms sp ON sp.room_id=tp.room_id
			".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		}else{
			$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,tp.*,u.full_name,u.emailid FROM "."
			joined_matches tp
			LEFT JOIN tbl_usermaster u ON u.id=tp.user_id
			".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		}
		
		//echo $query; die; 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		//echo $software_where;die;
		$total_records = $this->list_tokens_count($software_where);
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
				   }elseif($data['select_order_colum'][$i] == "full_name")
				   {
					   	$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark" title="View User Details">'.$aRow['full_name'].'</a>';
				   }elseif($data['select_order_colum'][$i] == "emailid")
				   {
					   	$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark" title="View User Details">'.$aRow['emailid'].'</a>';
				   }elseif($data['select_order_colum'][$i] == "room_name")
				   {
					   	$row[] = '<a href="'.base_url().'admin/edit-room/'.base64_encode($aRow['sweepstack_id']).'" class="text-dark" title="View User Details">'.$aRow['room_name'].'</a>';
				   }elseif($data['select_order_colum'][$i] == "tokens")
				   {
					   	$row[] = $aRow['tokens'];
				   }elseif($data['select_order_colum'][$i] == "created_on")
				   {
					   	$row[] = date('d/m/Y h:i A',strtotime($aRow['created_on']));
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';
					//'.base64_encode($aRow['user_id']).'
					$action .= 
						'<span>
							<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="btn btn-primary btn-color button_icon" title="View User Details">View User</a>
						</span>';
					/*if($aRow['status']=='1')
					{
						$iconName = 'ban';
					}else{
						$iconName = 'check';
					}
					$action .= 
						'<span>
							<a href="'. base_url().'admin/categories/change_activation_status/'.base64_encode($aRow['subscription_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_del" title="Delete Category"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					$action .= '</div>';*/
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	public function list_tokens_count($software_where) {
		$where = str_replace('where','',$software_where);
		//echo $where;die;
		$this->db->select('tp.*,u.*');
		$this->db->from('joined_matches tp');
		$this->db->join('usermaster u','u.id=tp.user_id','left');
		$this->db->where($where);
		$result = $this->db->get();
		//echo $this->db->last_query();die;
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getRoomPurchased($data)
	{
		$type = $this->uri->segment(3);

		$data['where_coloums'] 	    = array('serial_number','room_name','full_name','emailid');

        $software_where	= 'where 1 AND tp.delete_status = "0" AND u.account_confirm = "C" AND u.delete_status = "0" '; 
	    if($type!='')
	    {
	    	$software_where	.= ' AND tp.type = "'.$type.'" ';
	    	if($type=='TOKENS')
	    	{
	    		$data['select_order_colum']  = array('serial_number','room_name','room_drawing_id','full_name','emailid','per_ticket_tokens','created_on');
	    	}else{
	    		$data['select_order_colum']  = array('serial_number','room_name','room_drawing_id','full_name','emailid','paid_tokens','created_on','room_process_status');
	    	}
	    }else{
	    	$data['select_order_colum']  = array('serial_number','room_name','full_name','emailid','per_ticket_tokens','created_on');
	    }
		
		if($data['user_name_email'] != ""){
			$software_where .= ' AND full_name Like "%'.$data['user_name_email'].'%" OR emailid LIKE "%'.$data['user_name_email'].'%" ';
		}	
		if($data['room_name'] != ""){
			$software_where .= ' AND room_name Like "%'.$data['room_name'].'%" ';
		}	
		
		if($data['purchase_date'] != ""){
			$purchase_dates = explode(' - ',$data['purchase_date']);
			$software_where .= " AND created_on BETWEEN '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$purchase_dates[0])))."' AND '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$purchase_dates[1])))."' ";
		}	
		
		$data['table_name']   		= "tickets_purchased";
		$data['indexColumn']  		= "tickets_purchased_id";
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
				$order_by = "ORDER BY tp.tickets_purchased_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,tp.*,sd.start_date,sd.end_date,u.full_name,u.emailid FROM "."
		tickets_purchased tp
		LEFT JOIN rooms_drawings sd ON sd.room_drawing_id=tp.room_drawing_id
		LEFT JOIN tbl_usermaster u ON u.id=tp.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_tokens_count($software_where);
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
				   }elseif($data['select_order_colum'][$i] == "full_name")
				   {
					   if($type !='' && $type == "TOKENS"){
							$row[] = '<a href="'.base_url().'admin/user-purchase-ticket/'.base64_encode($aRow['user_id'])."/TOKENS".'" class="text-dark" title="View User Details">'.$aRow['full_name'].'</a>';
					   }else{
							$row[] = '<a href="'.base_url().'admin/user-direct-purchase/'.base64_encode($aRow['user_id'])."/DIRECT".'" class="text-dark" title="View User Details">'.$aRow['full_name'].'</a>';
					   }
				   }elseif($data['select_order_colum'][$i] == "emailid")
				   {
					   	if($type !='' && $type == "TOKENS"){
							$row[] = '<a href="'.base_url().'admin/user-purchase-ticket/'.base64_encode($aRow['user_id'])."/TOKENS".'" class="text-dark" title="View User Details">'.$aRow['emailid'].'</a>';
					   }else{
							$row[] = '<a href="'.base_url().'admin/user-direct-purchase/'.base64_encode($aRow['user_id'])."/DIRECT".'" class="text-dark" title="View User Details">'.$aRow['emailid'].'</a>';
					   }
				   }elseif($data['select_order_colum'][$i]=='room_drawing_id')
				   {
				   		$row[] = date('d/M/Y',strtotime($aRow['start_date'])).' - '.date('d/M/Y',strtotime($aRow['end_date']));
				   }elseif($data['select_order_colum'][$i] == "room_name")
				   {
					   	$row[] = '<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['sweepstack_id']).'" class="text-dark" title="View Room Details">'.$aRow['room_name'].'</a>';
				   }elseif($data['select_order_colum'][$i] == "paid_tokens")
				   {
					   	$row[] = $aRow['paid_tokens'];
				   }elseif($data['select_order_colum'][$i] == "created_on")
				   {
					   	$row[] = date('d/m/Y h:i A',strtotime($aRow['created_on']));
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';
					//'.base64_encode($aRow['user_id']).'
					$action .= 
						'<span>
							<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="btn btn-primary btn-color button_icon" title="View User Details">View User</a>
						</span>';
					if($aRow['type']=='DIRECT'){
						$action .= 
							'<span>
								<a href="javascript:void()" data-tickets_purchased_id="'.base64_encode($aRow['tickets_purchased_id']).'" class="btn btn-color button_icon" id="viewDetail" title="View Details"><i class="mdi mdi-eye"></i></a>
							';
					}
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	public function purchaser_details_by_id($tickets_purchased_id){
		$this->db->select('tp.*,um.username,um.emailid');
		$this->db->from('tickets_purchased tp');
		$this->db->join('usermaster um','um.id=tp.user_id','left');
		$this->db->where('tp.tickets_purchased_id',$tickets_purchased_id);
		$this->db->where('tp.status','1');
		$this->db->where('tp.delete_status','0');
		$query = $this->db->get();
		$result = array();
		if($query->num_rows() > 0) {
			$result = $query->row_array();
		}
		return $result;
	}

	public function roomPurchaseUserDetails($tickets_purchased_id="") {
		if($tickets_purchased_id!=''){
			$this->db->select('*');
			$this->db->from('tickets_purchased');
			$this->db->where('tickets_purchased_id',$tickets_purchased_id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			$html = '';
			if($query->num_rows() > 0) {
				$result=$query->row_array();
				//pr($result,1);
				if($result['user_is_address_confirm']=='1'){
					$html.='<div class="modal_details col-md-12"><div>User Name: <span id="room_winner_name">'.$result['user_first_name']." ".$result['user_last_name'].'</span></div>
						<div>Phone No.:  <span id="phone_no">'.$result['user_phone_no'].'</span></div>
						<div>Address:  <span id="address">'.$result['user_address'].'</span></div>
						<div>Address 2:  <span id="address2">'.$result['user_address2'].'</span></div>
						<div>City:  <span id="city">'.$result['user_city'].'</span></div>
						<div>State:  <span id="state">'.$result['user_state'].'</span></div>
						<div>Zip Code:  <span id="zip_code">'.$result['user_zip_code'].'</span></div>
						<div>Purchased Prize Status:  <span id="prize_status">'.ucwords(strtolower($result['room_process_status'])).'</span></div></div>';
						if($result['room_process_status']=="PENDING"){
							$html .= '
							<form action="'.base_url('admin/process-direct-purchase-prize/'.base64_encode($result['tickets_purchased_id'])).'" method="POST">
								<div class="form-group col-md-12 has-feedback"> <label>Tracking ID :</label><input type="text" name="tracking_id" class="form-control" required></div>
								<div class="form-group col-md-12 has-feedback"> <label>Tracking Url : :</label><input type="text" name="tracking_url" class="form-control" required></div>
								<div class="col-md-12"><input type="submit" name="submit" class="btn btn-primary btn-sm" value="Process Now" /></div>
							</form>';
						}else if($result['room_process_status']=="PROCESSING"){
							$html .= '
								<div>Processed On:  <span id="process_date">'.date('d-M-Y H:i A',strtotime($result['user_room_prize_process_date'])).'</span></div>
								<div>Tracking ID:  <span id="tracking_id">'.$result['user_tracking_id'].'</span></div>
								<div>Tracking Url:  <span id="tracking_url"><a href="'.$result['user_tracking_url'].'">'.$result['user_tracking_url'].'</a></span></div>
								<form action="'.base_url('admin/deliver-direct-purchase-prize/'.base64_encode($result['tickets_purchased_id'])).'" method="POST">
									<input type="submit" name="submit" class="btn btn-primary btn-sm" value="Mark as Delivered" />
								</form>';
						}else if($result['room_process_status']=="DELIVERED"){
							$html .= '<div>Processed On:  <span id="process_date">'.date('d-M-Y H:i A',strtotime($result['user_room_prize_process_date'])).'</span></div>
								<div>Delivered On:  <span id="delivery_date">'.date('d-M-Y H:i A',strtotime($result['user_room_prize_deliver_date'])).'</span></div>
								<div>Tracking ID:  <span id="tracking_id">'.$result['user_tracking_id'].'</span></div>
								<div>Tracking Url:  <span id="tracking_url"><a href="'.$result['user_tracking_url'].'">'.$result['user_tracking_url'].'</a></span></div>';
						}
					$html.='</div>';
				}else{
					$html="<div>Address not confirmed yet by User!</div>";
				}
				return $html;
			}else{
				return false;
			}
		}else {
			return false;	
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
?>
