<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Stores_model extends CI_Model{
	
	public function getAllStores($data)
	{
        $software_where  	= 'where 1 AND sp.delete_status = "0" '; 
		
	   $data['where_coloums'] 	    = array('serial_number','c.category_name','product_title');
       $data['select_order_colum']  = array('serial_number','category_name','product_title','product_image','tokens','product_description','status');
		$data['table_name']   		= "store_products";
		$data['indexColumn']  		= "store_room_id";
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
				$order_by = "ORDER BY store_room_id DESC";
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
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,sp.*,c.category_name FROM "."
		store_products sp
		LEFT JOIN categories as c ON c.category_id=sp.category_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_product_store_count();
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
				   }elseif($data['select_order_colum'][$i]=='product_title'){
				   		if($aRow['product_title']!='')
				   		{
				   			$row[] = '<a href="'. base_url().'admin/edit-store-product/'.base64_encode($aRow['store_room_id']).'" class="text-dark">'.$aRow['product_title'].'</a>';
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
				   }elseif($data['select_order_colum'][$i]=='product_image'){
				   		if($aRow['product_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/stores/'.$aRow['product_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'stores/'.$aRow['product_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'stores/default_product.png" width="50">';
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
							<a href="'. base_url().'admin/edit-store-product/'.base64_encode($aRow['store_room_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
					}else{
						$iconName = 'check';
					}
					
					$action .= 
						'<span>
							<a href="'. base_url().'admin/stores/change_activation_status/'.base64_encode($aRow['store_room_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_del" title="Change Status"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					
					$action .= '</div>';
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	public function list_product_store_count() {
		$this->db->select('store_room_id');
		$this->db->from('store_products');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
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
			$room=array(''=>'-- Select Category --');
			foreach ($resulArray as $val) {
				$room[$val['category_id']]=$val['category_name'];
			}
			return $room;
		}else{
			return array();
		}
	}
	
	
	public function addStoreProduct($post){
		//pr($post,1);
		if($post){
			$data = array();
			/* $admin_id    = $this->site_santry->get_auth_data('id');
			$data['admin_id'] 			 =	 $admin_id; */
			if(isset($post['is_terms_conditions_visible']))
			{
				$data['is_terms_conditions_visible']  =	'1';
			}else{
				$data['is_terms_conditions_visible']  =	'0';
			}
			$data['category_id'] 				=	 trim($post['category_id']);
			$data['product_title'] 				=	 trim($post['product_title']);
			$data['product_image']  	 		=	 trim($post['store_product_image']);
			$data['tokens']  		 			=	 trim($post['tokens']);
			$data['product_description']  		=	 trim($post['product_description']);
			$data['affiliate_link']  			=	 trim($post['affiliate_link']);
			$data['created_on']  	 	 		=	 date('Y-m-d H:i:s');
			//pr($data,1);
			
			$result = $this->commonmodel->_insert('store_products', $data);
			if($result){
				return true;
			}else{
				return false;
			}				
		}else{
			return false;
		}
	}
	
	public function updateStoreProduct($post,$store_room_id=""){
		//echo $category_id;
		if($store_room_id!=""){
			$data = array();
			$condition=array('store_room_id'=>$store_room_id);
			if(isset($post['is_terms_conditions_visible']))
			{
				$data['is_terms_conditions_visible']  =	'1';
			}else{
				$data['is_terms_conditions_visible']  =	'0';
			}
			$data['category_id'] 				=	 trim($post['category_id']);
			$data['product_title'] 				=	 trim($post['product_title']);
			$data['tokens']  		 			=	 trim($post['tokens']);
			$data['product_description']  		=	 trim($post['product_description']);
			$data['affiliate_link']  			=	 trim($post['affiliate_link']);
			$data['modified_on'] 	 		 	=	 date('Y-m-d H:i:s');

			if(isset($post['store_product_image']) && $post['store_product_image']){
				$data['product_image'] = trim($post['store_product_image']);
			}
			//pr($data,1);
			$updated=$this->commonmodel->_update('store_products',$data,$condition);
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
	
	public function getProductDetailsById($product_id=""){
		if($product_id!=""){
			$this->db->where('store_room_id',$product_id);
			$get=$this->db->get("store_products");
			if($get->num_rows()>0){
				$result=$get->row_array();			
				return $result;
			}else{
				return array();
			}
		}
		else {
			return array();
		}
	}
	
	public function getAllStorePurchases($data){
		$software_where  	= 'where 1 AND stp.delete_status = "0" '; 
		
		if($data['user_name'] != ""){
			$software_where .= ' AND um.full_name Like "%'.$data['user_name'].'%"  ';
		}
		if($data['product_name'] != ""){
			$software_where .= ' AND stp.product_title Like "%'.$data['product_name'].'%"  ';
		}
		if($data['produtc_status'] != ""){
			$software_where .= ' AND stp.prize_process_status ="'.$data['produtc_status'].'"  ';
		}
		
		$data['where_coloums'] 	    = array('serial_number','um.full_name','um.address','stp.store_room_id','stp.product_title','stp.tokens');
		$data['select_order_colum']  = array('serial_number','full_name','address','store_room_id','product_title','product_image','tokens','prize_process_status','tracking_id','tracking_url');
		$data['table_name']   		= "store_token_purchases";
		$data['indexColumn']  		= "store_token_purchase_id";
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
				$order_by = "ORDER BY store_token_purchase_id DESC";
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
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,stp.*,um.full_name,um.address FROM "."
		store_token_purchases stp
		LEFT JOIN tbl_usermaster as um ON um.id=stp.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		//echo $query; die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_store_purchases_count();
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
				   }elseif($data['select_order_colum'][$i]=='product_title'){
				   		if($aRow['product_title']!='')
				   		{
				   			$row[] = '<a href="'. base_url().'admin/edit-store-product/'.base64_encode($aRow['store_room_id']).'" class="text-dark">'.$aRow['product_title'].'</a>';
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
				   }elseif($data['select_order_colum'][$i]=='tracking_id')
				   {
				   		if($aRow['tracking_id']!='')
				   		{
				   			$row[] = $aRow['tracking_id'];
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='tracking_url')
				   {
				   		if($aRow['tracking_url']!='')
				   		{
				   			$row[] = $aRow['tracking_url'];
				   		}else{
				   			$row[] = '-';
				   		}
				   }elseif($data['select_order_colum'][$i]=='product_image'){
				   		if($aRow['product_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/stores/'.$aRow['product_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'stores/'.$aRow['product_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'stores/default_product.png" width="50">';
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
							<a href="javascript:void()" data-store_token_purchase_id="'.base64_encode($aRow['store_token_purchase_id']).'" class="btn btn-color button_icon" id="viewDetail" title="View Details"><i class="mdi mdi-eye"></i></a>
						</span>';
					
					$action .= '</div>';
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	public function list_store_purchases_count(){
		$this->db->select('store_token_purchase_id');
		$this->db->from('store_token_purchases');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function store_purchase_detail_by_id($store_token_purchase_id){
		$this->db->select('stp.*,um.username,um.emailid');
		$this->db->from('store_token_purchases stp');
		$this->db->join('usermaster um','um.id=stp.user_id','left');
		$this->db->where('stp.store_token_purchase_id',$store_token_purchase_id);
		$this->db->where('stp.status','1');
		$this->db->where('stp.delete_status','0');
		$query = $this->db->get();
		$result = array();
		if($query->num_rows() > 0) {
			$result = $query->row_array();
		}
		return $result;
	}
	
	
	public function store_purchase_details($store_token_purchase_id){
		if($store_token_purchase_id!=''){
			$this->db->select('*');
			$this->db->from('store_token_purchases');
			$this->db->where('store_token_purchase_id',$store_token_purchase_id);
			$this->db->where('status','1');
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			$html = '';
			if($query->num_rows() > 0) {
				$result=$query->row_array();
				$html.='<div class="modal_box_new"><div><span>User Name:</span> <span id="user_name">'.$result['first_name']." ".$result['last_name'].'</span></div>
					<div><span>Phone No.:</span> <span id="phone_no">'.$result['phone_no'].'</span></div>
					<div><span>Address: </span> <span id="address">'.$result['address'].'</span></div>
					<div><span>Address 2:</span>  <span id="address2">'.$result['address2'].'</span></div>
					<div><span>City:</span>  <span id="city">'.$result['city'].'</span></div>
					<div><span>State:</span>  <span id="state">'.$result['state'].'</span></div>
					<div><span>Zip Code:</span>  <span id="zip_code">'.$result['zip_code'].'</span></div>
					<div><span>Winning Prize Status:</span>  <span id="prize_status">'.ucwords(strtolower($result['prize_process_status'])).'</span></div>';
				if($result['prize_process_status']=="PENDING"){
					$html .= '
					<form action="'.base_url('admin/process-store-purchase/'.base64_encode($result['store_token_purchase_id'])).'" method="POST">
						<div class="form-group col-md-12 has-feedback"> <label>Tracking ID :</label><input type="text" name="tracking_id" class="form-control" required></div>
						<div class="form-group col-md-12 has-feedback"> <label>Tracking Url : :</label><input type="text" name="tracking_url" class="form-control" required></div>
						<input type="submit" name="submit" class="btn btn-primary btn-sm" value="Process Now" />
					</form>';
				}else if($result['prize_process_status']=="PROCESSING"){
					$html .= '
						<div><span>Processed On:</span> <span id="process_date">'.date('d-M-Y H:i A',strtotime($result['product_process_date'])).'</span></div>
						<div><span>Tracking ID:</span> <span id="tracking_id">'.$result['tracking_id'].'</span></div>
						<div><span>Tracking Url:</span>  <span id="tracking_url"><a href="'.$result['tracking_url'].'">'.$result['tracking_url'].'</a></span></div>
						<form action="'.base_url('admin/deliver-store-purchase/'.base64_encode($result['store_token_purchase_id'])).'" method="POST">
							<input type="submit" name="submit" class="btn btn-primary btn-sm" value="Mark as Delivered" />
						</form>';
				}else if($result['prize_process_status']=="DELIVERED"){
					$html .= '<div><span>Processed On:</span>  <span id="process_date">'.date('d-M-Y H:i A',strtotime($result['product_process_date'])).'</span></div>
						<div><span>Delivered On:</span>  <span id="delivery_date">'.date('d-M-Y H:i A',strtotime($result['product_deliver_date'])).'</span></div>
						<div><span>Tracking ID:</span>  <span id="tracking_id">'.$result['tracking_id'].'</span></div>
						<div><span>Tracking Url:</span>  <span id="tracking_url"><a href="'.$result['tracking_url'].'">'.$result['tracking_url'].'</a></span></div>';
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
}
?>
