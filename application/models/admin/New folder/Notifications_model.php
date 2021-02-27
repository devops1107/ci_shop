<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Notifications_model extends CI_Model{
	
	public function getAllNotifications($data)
	{
        $software_where  	= 'where 1 AND an.delete_status = "0"  '; 
		
	    $data['where_coloums'] 	     = array('serial_number','um.full_name','um.emailid');
        $data['select_order_colum']  = array('serial_number','full_name','emailid','action','description','created_on','status');
		$data['table_name']   		 = "admin_notifications";
		$data['indexColumn']  		 = "an.admin_notification_id";
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
				$order_by = "ORDER BY an.created_on DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,an.*,sw.room_name,sd.start_date,sd.end_date,um.full_name,CONCAT(um.firstname,' ',um.lastname) as user_name,um.username,um.emailid FROM "."
		admin_notifications an
		LEFT JOIN tbl_usermaster um ON um.id = an.user_id
		LEFT JOIN room_winners sw ON sw.room_drawing_id=an.room_drawing_id
		LEFT JOIN rooms_drawings sd ON sd.room_drawing_id=an.room_drawing_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		//echo $query; die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		//pr($result,1);
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_notifications_count();
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
				   		if($aRow['created_on']!='0000-00-00')
				   		{
				   			$row[] = date('d/m/Y h:i A',strtotime($aRow['created_on']));
				   		}else{
				   			$row[] = '0000-00-00';
				   		}
				    }elseif($data['select_order_colum'][$i]=='action'){
				   		if($aRow['action']!='')
				   		{
							if($aRow['action']=='IRS_REQUIRED'){
								$row[] = 'IRS Required';
							}else if($aRow['action']=='WINNER_ANNOUNCED'){
								$row[] = 'Room Drawing Completed';
							}
				   		}else{
				   			$row[] = '-';
				   		}
				    }elseif($data['select_order_colum'][$i]=='emailid'){
						if(trim($aRow['emailid'])!='')
						{
							$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['winner_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['emailid'])).'</a>';	
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='full_name'){
						if(trim($aRow['full_name'])!='')
						{
							$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['winner_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['full_name'])).'</a>';	
						}else if(trim($aRow['user_name'])!=''){
							$row[] = $row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['winner_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['user_name'])).'</a>';
						}else if(trim($aRow['username'])!=''){
							$row[] = $row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['winner_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['username'])).'</a>';
						}else{
							$row[] = '-';
						}
					}elseif($data['select_order_colum'][$i]=='description'){
				   		
							if($aRow['action']=='IRS_REQUIRED'){
								$row[] = "User ".$aRow['full_name']." has won a rooms putting them over $600 of winning this year. Please send them the IRS form to fill out and attach it to their profile.";
							}else if($aRow['action']=='WINNER_ANNOUNCED'){
								$row[] = "Room ".$aRow['room_name']."'s drawing ".$aRow['start_date']."-".$aRow['end_date']." is done and the winner is ".$aRow['full_name']."";
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
				   }else if($data['select_order_colum'][$i]=='promocode_image'){
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
					if($aRow['action']=='IRS_REQUIRED'){
						$action .= 
							'<span>
								<a href="'.base_url('admin/view-user/'.base64_encode($aRow['winner_id']).'').'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-eye"></i></a>
							</span>';
					}else if($aRow['action']=='WINNER_ANNOUNCED'){
						$action .= 
							'<span>
								<a href="'. base_url('admin/room-winner/'.base64_encode($aRow['room_id']).'').'" class="btn btn-color button_icon" title="View"><i class="mdi mdi-eye"></i></a>
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

	public function list_notifications_count() {
		
		$this->db->select('admin_notification_id');
		$this->db->from('admin_notifications');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	
}
?>
