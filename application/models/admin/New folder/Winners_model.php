<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Winners_model extends CI_Model{
	
	public function getWinnersListing($data)
	{
		//pr($data,1);
        $software_where  	= 'where 1 AND sw.delete_status = "0"  ';
		if(isset($data['room_id']) && $data['room_id']!="" && $data['room_id']!="0"){
			$software_where  	.= 'AND sw.room_id = '.$data['room_id'].'  ';
		}else if(isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="0"){
			$software_where  	.= 'AND sw.user_id = '.$data['user_id'].'';			
		}
		
		/* if($data['room_name'] != ""){
			$software_where .= ' AND c.room_name Like "%'.$data['room_name'].'%"  ';
		}	
		if($data['room_category'] != ""){
			$software_where .= ' AND c.category_id = "'.$data['room_category'].'" ';
		}
		if($data['room_status'] != ""){
			$software_where .= ' AND c.status = "'.$data['room_status'].'" ';
		} */	
		/* if($data['start_date_filter'] != ""){
			$start_date_filters = explode(' - ',$data['start_date_filter']);
			$software_where .= " AND c.start_date BETWEEN '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$start_date_filters[0])))."' AND '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$start_date_filters[1])))."' ";
		}
		if($data['end_date_filter'] != ""){
			$end_date_filters = explode(' - ',$data['end_date_filter']);
			$software_where .= " AND c.end_date BETWEEN '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$end_date_filters[0])))."' AND '".date('Y-m-d H:i:s',strtotime(str_replace('/', '-',$end_date_filters[1])))."' ";
		} */	
		if(isset($data['room_id']) && $data['room_id']!="" && $data['room_id']!="0"){
		   $data['where_coloums'] 	    = array('serial_number','um.full_name','um.address','sw.room_id','sw.room_winner_id');
		   $data['select_order_colum']  = array('serial_number','full_name','address','room_id','room_winner_id','room_winner_status','status');
		}else if(isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="0"){
		   $data['where_coloums'] 	    = array('serial_number','r.room_id','room_name','r.start_datetime','sw.price_type','sw.winning_amount');
		   $data['select_order_colum']  = array('serial_number','room_id','room_name','start_datetime','price_type','winning_amount','status');
		}else{
		   $data['where_coloums'] 	    = array('serial_number','um.full_name','um.address','sw.room_id','room_name','sw.room_id','sw.room_winner_id');
		   $data['select_order_colum']  = array('serial_number','full_name','address','room_id','room_name','room_id','room_winner_id','room_winner_status','status');
		}
		
		$data['table_name']   		= "room_winners";
		$data['indexColumn']  		= "room_winner_id";
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
				$order_by = "ORDER BY room_winner_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,sw.*,sd.grand_loot_price_value,sd.start_datetime,um.full_name,um.address FROM "."
		room_winners sw
		LEFT JOIN rooms as sd ON sd.room_id=sw.room_id
		LEFT JOIN tbl_usermaster as um ON um.id=sw.user_id
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
		
		if(isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="0"){
			$total_records = $this->list_winners_countUser($software_where);
		}else{
			$total_records = $this->list_winners_count($software_where);
		}
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
					    if(isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="0"){
							$row[] = '<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['room_id']).'" class="text-dark" title="View Details">'.$aRow['room_name'].'</a>';
					    }else{
							$row[] = '<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['room_id']).'" class="text-dark" title="View Details">'.$aRow['room_name'].'</a>';
					    }
				   }elseif($data['select_order_colum'][$i]=='start_datetime'){
				   		if($aRow['start_datetime']!='0000-00-00 00:00:00')
				   		{
				   			$row[] = date('d-M-Y h:i A',strtotime($aRow['start_datetime']));
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
				   }elseif($data['select_order_colum'][$i]=='full_name')
				   {
				   		if($aRow['full_name']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['full_name'])).'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='address')
				   {
				   		if($aRow['address']!='')
				   		{
				   			$row[] = $aRow['address'];
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='room_image'){
				   		if($aRow['room_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/rooms/'.$aRow['room_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'rooms/'.$aRow['room_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'rooms/default_room.png" width="50">';
				   		}
				   }
				   else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action" style="width: max-content;">';				
					if($aRow['room_winner_id']!='NULL' || (isset($data['room_id']) && $data['room_id']!="" && $data['room_id']!="0")){
						$action .= 
							'<span>
								<a href="javascript:void()" data-room_winner_id="'.base64_encode($aRow['room_winner_id']).'" class="btn btn-color button_icon" id="viewDetail" title="View Details"><i class="mdi mdi-eye"></i></a>
							';
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

	
	public function list_winners_count($software_where) {
		$where = str_replace('WHERE','',$software_where);
		$where = str_replace('where','',$where);
		$this->db->select('sw.room_winner_id');
		$this->db->from('room_winners sw');
		$this->db->where('sw.delete_status','0');
		$this->db->where($where);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function list_winners_countUser($software_where) {
		$where = str_replace('WHERE','',$software_where);
		$where = str_replace('where','',$where);
		$this->db->select('sw.room_winner_id');
		$this->db->from('room_winners sw');
		$this->db->join('rooms sd','sd.room_id = sw.room_id AND event_completed="1"','left');
		$this->db->where('sw.delete_status','0');
		$this->db->where($where);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getSecondaryWinnersListing($data)
	{
		//pr($data,1);
        $software_where  	= "where 1 AND sd.is_drawing_complete='1' AND tp.sweepstack_id = '".$data['room_id']."' AND tp.type = 'TOKENS' AND tp.status = '1' AND tp.delete_status = '0' AND sw.room_winner_id IS NULL ";
		/* if(isset($data['room_id']) && $data['room_id']!="" && $data['room_id']!="0"){
			$software_where  	.= 'AND sw.room_id = '.$data['room_id'].'  ';
		}else if(isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="0"){
			$software_where  	.= 'AND tp.user_id = '.$data['user_id'].' AND tp.type="TOKENS" ';			
		} */
		
		$data['where_coloums'] 	    = array('serial_number','um.full_name','um.address','tp.room_drawing_id','tp.secoundry_prize_tokens');
		$data['select_order_colum']  = array('serial_number','full_name','address','room_drawing_id','secoundry_prize_tokens','status');
		
		
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
				$order_by = "ORDER BY tp.created_on DESC";
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
		//$query = "SELECT `tp`.*, `sw`.`room_winner_id`, `c`.`category_name` FROM `tickets_purchased` `tp` LEFT JOIN `room_winners` `sw` ON `tp`.`user_id` = `sw`.`user_id` AND `tp`.`room_drawing_id` = `sw`.`room_drawing_id` LEFT JOIN `categories` `c` ON `c`.`category_id` = `tp`.`category_id` LEFT JOIN `rooms_drawings` `sd` ON `sd`.`room_drawing_id` = `tp`.`room_drawing_id` AND `is_drawing_complete`='1' WHERE `sd`.`is_drawing_complete` = '1' AND `tp`.`sweepstack_id` = '8' AND `tp`.`type` = 'TOKENS' AND `tp`.`status` = '1' AND `tp`.`delete_status` = '0'";
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,`tp`.*, `sw`.`room_winner_id`,sd.start_date,sd.end_date,sd.is_drawing_complete,um.full_name,um.address FROM `tickets_purchased` `tp` LEFT JOIN tbl_usermaster as um ON um.id=tp.user_id LEFT JOIN `room_winners` `sw` ON `tp`.`user_id` = `sw`.`user_id` AND `tp`.`room_drawing_id` = `sw`.`room_drawing_id` LEFT JOIN `categories` `c` ON `c`.`category_id` = `tp`.`category_id` LEFT JOIN `rooms_drawings` as `sd` ON `sd`.`room_drawing_id` = `tp`.`room_drawing_id` AND `is_drawing_complete`='1'  ".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		
		//echo $query; die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		//pr($result,1);
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		
		$total_records = count($result);
		
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
					    if(isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="0"){
							$row[] = '<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['sweepstack_id']).'" class="text-dark" title="View Details">'.$aRow['room_name'].'</a>';
					    }else{
							$row[] = '<a href="'. base_url().'admin/edit-room/'.base64_encode($aRow['room_id']).'" class="text-dark" title="View Details">'.$aRow['room_name'].'</a>';
					    }
				   }elseif($data['select_order_colum'][$i]=='secoundry_prize_tokens'){
				   		if($aRow['secoundry_prize_tokens']!='0')
				   		{
				   			$row[] = $aRow['secoundry_prize_tokens'].' Tokens';
				   		}else{
				   			$row[] = '0';
				   		}
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }elseif($data['select_order_colum'][$i]=='room_drawing_id')
				   {
				   		$row[] = date('d/M/Y',strtotime($aRow['start_date'])).' - '.date('d/M/Y',strtotime($aRow['end_date']));
				   }elseif($data['select_order_colum'][$i]=='full_name')
				   {
				   		if($aRow['full_name']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['full_name'])).'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='address')
				   {
				   		if($aRow['address']!='')
				   		{
				   			$row[] = $aRow['address'];
				   		}else{
				   			$row[] = '-';
				   		}
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					/* $action	=  '<div class="action_box width-162 display-action" style="width: max-content;">';				
					
					$action .= '</div>';
					
					$row[]	= $action; */
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		return $output;
	}

	
	public function list_second_winners_count($software_where) {
		$where = str_replace('WHERE','',$software_where);
		$where = str_replace('where','',$where);
		$this->db->select('tp.*, sw.room_winner_id,sd.is_drawing_complete');
		$this->db->from('tickets_purchased tp');
		$this->db->join('room_winners sw','tp.user_id=sw.user_id AND tp.room_drawing_id = sw.room_drawing_id','LEFT');
		$this->db->join('rooms_drawings sd','sd.room_drawing_id = tp.room_drawing_id AND sd.is_drawing_complete="1"','LEFT');
		$this->db->where('sw.delete_status','0');
		$this->db->where($where);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
		
	public function winners_details($winner_id="") {
		if($winner_id!=''){
			$this->db->select('*');
			$this->db->from('room_winners');
			$this->db->where('room_winner_id',$winner_id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			$html = '';
			if($query->num_rows() > 0) {
				$result=$query->row_array();
				$html.='<div class="modal_details col-md-12"><div>Room Winner Name: <span id="room_winner_name">'.$result['first_name']." ".$result['last_name'].'</span></div>
					<div>Phone No.:  <span id="phone_no">'.($result['phone_no']).'</span></div>
					<div>Address:  <span id="address">'.$result['address'].'</span></div>
					<div>Address 2:  <span id="address2">'.$result['address2'].'</span></div>
					<div>City:  <span id="city">'.$result['city'].'</span></div>
					<div>State:  <span id="state">'.$result['state'].'</span></div>
					<div>Zip Code:  <span id="zip_code">'.$result['zip_code'].'</span></div>
					<div>Winning Prize Status:  <span id="prize_status">'.ucwords(strtolower($result['room_winner_status'])).'</span></div></div>';
				if($result['room_winner_status']=="PENDING"){
					$html .= '
					<form action="'.base_url('admin/process-winner/'.base64_encode($result['room_winner_id'])).'" method="POST">
						<div class="form-group col-md-12 has-feedback"> <label>Tracking ID :</label><input type="text" name="tracking_id" class="form-control" required></div>
						<div class="form-group col-md-12 has-feedback"> <label>Tracking Url : :</label><input type="text" name="tracking_url" class="form-control" required></div>
						<div class="col-md-12"><input type="submit" name="submit" class="btn btn-primary btn-sm" value="Process Now" /></div>
					</form>';
				}else if($result['room_winner_status']=="PROCESSING"){
					$html .= '
						<div>Processed On:  <span id="process_date">'.date('d-M-Y H:i A',strtotime($result['room_prize_process_date'])).'</span></div>
						<div>Tracking ID:  <span id="tracking_id">'.$result['tracking_id'].'</span></div>
						<div>Tracking Url:  <span id="tracking_url"><a href="'.$result['tracking_url'].'">'.$result['tracking_url'].'</a></span></div>
						<form action="'.base_url('admin/deliver-winner/'.base64_encode($result['room_winner_id'])).'" method="POST">
							<input type="submit" name="submit" class="btn btn-primary btn-sm" value="Mark as Delivered" />
						</form>';
				}else if($result['room_winner_status']=="DELIVERED"){
					$html .= '<div>Processed On:  <span id="process_date">'.date('d-M-Y H:i A',strtotime($result['room_prize_process_date'])).'</span></div>
						<div>Delivered On:  <span id="delivery_date">'.date('d-M-Y H:i A',strtotime($result['room_prize_deliver_date'])).'</span></div>
						<div>Tracking ID:  <span id="tracking_id">'.$result['tracking_id'].'</span></div>
						<div>Tracking Url:  <span id="tracking_url"><a href="'.$result['tracking_url'].'">'.$result['tracking_url'].'</a></span></div>';
				}
				$html.='</div>';
				return $html;
			}else{
				return false;
			}
		}else {
			return false;	
		}
	}
	
	public function winner_details_by_id($winner_id){
		$this->db->select('sw.*,um.username,um.emailid');
		$this->db->from('room_winners sw');
		$this->db->join('usermaster um','um.id=sw.user_id','left');
		$this->db->where('sw.room_winner_id',$winner_id);
		$this->db->where('sw.status','1');
		$this->db->where('sw.delete_status','0');
		$query = $this->db->get();
		$result = array();
		if($query->num_rows() > 0) {
			$result = $query->row_array();
		}
		return $result;
	}
	
	public function irsRequiredUsersList($data){
		//pr($data,1);
		$today = date('Y-m-d');
		$before_one_yr = date('Y-m-d',strtotime($today.'-1 year'));
        $software_where  	= 'where 1 AND sw.delete_status = "0" AND um.is_irs_confirm_status="0" ';
				
	    $data['where_coloums'] 	    = array('serial_number','um.full_name','um.emailid','total_grand_loot');
	    $data['select_order_colum']  = array('serial_number','full_name','emailid','total_grand_loot','status');
		
		
		$data['table_name']   		= "room_winners";
		$data['indexColumn']  		= "room_winner_id";
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
				$order_by = "ORDER BY room_winner_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,sw.*,SUM(tp.grand_loot_price_value) as total_grand_loot,sd.start_date,sd.end_date,um.full_name,um.emailid,um.is_irs_confirm_status FROM "."
		room_winners sw
		LEFT JOIN rooms_drawings as sd ON sd.room_drawing_id=sw.room_drawing_id
		LEFT JOIN tickets_purchased as tp ON tp.user_id=sw.user_id AND tp.room_drawing_id=sw.room_drawing_id
		LEFT JOIN tbl_usermaster as um ON um.id=sw.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where.' '.'group by sw.user_id'.' '.'HAVING SUM(tp.grand_loot_price_value)>=600'.' '. $order_by." ".$limit;
		
		//echo $query; die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		//pr($result,1);
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		
		$total_records = $this->list_irs_required_users_count($software_where);
		
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
				   }elseif($data['select_order_colum'][$i]=='total_grand_loot'){
				   		if($aRow['total_grand_loot']!='NULL')
				   		{
				   			$row[] = $aRow['total_grand_loot'];
				   		}else{
				   			$row[] = '0';
				   		}
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }elseif($data['select_order_colum'][$i]=='full_name')
				   {
				   		if($aRow['full_name']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['full_name'])).'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='emailid')
				   {
				   		if($aRow['emailid']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['emailid'])).'</a>';
				   		}else{
				   			$row[] = '-';
				   		}
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action" style="width: max-content;">';				
					if($aRow['room_winner_id']!='NULL' || (isset($data['room_id']) && $data['room_id']!="" && $data['room_id']!="0")){
						$action .= 
							'<span>
								<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="btn btn-color button_icon" id="viewDetail" title="View Details"><i class="mdi mdi-eye"></i></a>
							';
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
	
	public function list_irs_required_users_count($software_where) {
		$where = str_replace('WHERE','',$software_where);
		$where = str_replace('where','',$where);
		$this->db->select('sw.*,SUM(tp.grand_loot_price_value) as total_grand_loot,sd.start_date,sd.end_date,um.full_name,um.emailid,um.is_irs_confirm_status');
		$this->db->from('room_winners sw');
		$this->db->join('rooms_drawings sd','sd.room_drawing_id=sw.room_drawing_id','LEFT');
		$this->db->join('tickets_purchased tp','tp.user_id=sw.user_id AND tp.room_drawing_id=sw.room_drawing_id','LEFT');
		$this->db->join('usermaster um','um.id=sw.user_id','LEFT');
		$this->db->where($where);
		$this->db->group_by('user_id'); 
		$this->db->having('SUM(tp.grand_loot_price_value) >= 600'); 
		$result = $this->db->get();
		//echo $this->db->last_query();die;
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function send_prize_email_to_user($details,$type){
		//pr($details,1);
		if(!empty($details)){
			$user_name = $details['username'];
			$emailid = $details['emailid'];
			$subject = 'Loot Champs Prize Distribution Email';
			$to = $emailid;
			$message 	 = 'Hello '.$user_name.'<br/>';
			if($type=="PROCESSING"){
				if(isset($details['type']) && $details['type']=='DIRECT'){
					$message .= 'Your prize is on its way! Prize item will be delivered at your mentioned address within 2-3 days. Please make sure your address is right. The tracking number is "'.$details['user_tracking_id'].'" <br/> <br/>';
				}else{
					$message .= 'Your prize is on its way! Prize item will be delivered at your mentioned address within 2-3 days. Please make sure your address is right. The tracking number is "'.$details['tracking_id'].'" <br/> <br/>';
				}
				//$message .= 'Prize item will be delivered at your mentioned address within 2-3 days. Please make sure your address is right. <br/> <br/>';
				//$message .= 'Your prize is on its way! The tracking number is "'.$details['tracking_id'].'" <br/> <br/>';
			}else if($type=="DELIVERED"){
				$message 	.= 'Your item has been delivered at your mentioned address. <br/> <br/>';
			}
			$message 	.= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
			$message 	.= 'We hope you enjoy our services!<br/><br/>';
			$message 	.= 'Sincerely<br/>';
			$message 	.= SITE_NAME.' Team';
			//pr($message,1);
			$mailConfirm = $this->sendemail($to,$subject,$message);
			//pr($mailConfirm,1);
			return $mailConfirm;
		}else{
			return false;
		}
	}
	
	public function sendemail($to,$subject,$message)
	{
		require_once('/../smtp/class.phpmailer.php');
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