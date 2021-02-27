<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Brands_model extends CI_Model{


/* -------------------- Brand ---------------------- */	
	public function getAllBrands($data)
	{
        $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
	   $data['where_coloums'] 	    = array('serial_number','brand_name','brand_name_gr','brand_name_tr');
       $data['select_order_colum']  = array('serial_number','brand_image','brand_name','brand_name_gr','brand_name_tr','status');
		$data['table_name']   		= "tbl_brands";
		$data['indexColumn']  		= "brand_id";
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
				$order_by = "ORDER BY brand_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.* FROM "."
		tbl_brands c
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_brands_count();
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
				   }elseif($data['select_order_colum'][$i]=='status'){
				   		if($aRow['status']=='1')
				   		{
				   			$row[] = 'Active';
				   		}else{
				   			$row[] = 'Deactive';
				   		}
				   }elseif($data['select_order_colum'][$i]=='brand_image'){
				   		if($aRow['brand_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/brands/'.$aRow['brand_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'brands/'.$aRow['brand_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'brands/default_Brand.png" width="50">';
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
							<a href="'. base_url().'admin/edit-brand/'.base64_encode($aRow['brand_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
						$iconTitle = 'Deactivate Brand';
					}else{
						$iconName = 'check';
						$iconTitle = 'Activate Brand';
					}
					$action .= 
						'<span>
							<a href="'. base_url().'admin/brands/change_activation_status/'.base64_encode($aRow['brand_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_dec" title="'.$iconTitle.'"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					$action .= 
						'<span>
							<a onClick="confirm_delete();" href="'. base_url().'admin/delete-brand/'.base64_encode($aRow['brand_id']).'" class="btn btn-color button_icon btn_del" title="Delete Brand"><i class="fas fa-trash"></i></a>
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

	public function list_brands_count() {
		
		$this->db->select('brand_id');
		$this->db->from('tbl_brands');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function brand_details($condition) {
		
		$this->db->select('c.*');
		$this->db->from('tbl_brands c');
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

	public function brands_dropdown_list() {
		
		$this->db->select('*');
		$this->db->from('tbl_brands');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		$resulArray = $result->result_array();
		if($resulArray > 0) {
			$brand=array(''=>'-- Select Brand --');
			foreach ($resulArray as $val) {
				$brand[$val['brand_id']]=$val['brand_name'];
			}
			return $brand;
		}else{
			return array();
		}
	}

	public function addBrands($post){
		if($post){
			//pr($post,1);
			$data = array();
			$data['brand_name']	 =	 trim($post['brand_name_en']);
			$data['brand_name_gr']	 =	 trim($post['brand_name_gr']);
			$data['brand_name_tr']	 =	 trim($post['brand_name_tr']);
			$data['brand_image']  =	 trim($post['brand_image']);
			$data['created_on']  	 =	 date('Y-m-d H:i:s');
			$data['modify_date'] 	 =	date('Y-m-d H:i:s');
			if($this->commonmodel->_insert('tbl_brands', $data)){
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	public function updateBrands($post,$brand_id=""){
		//echo $brand_id;
		if($brand_id!=""){
			$data = array();
			$condition=array('brand_id'=>$brand_id);
			$data['brand_name'] 	 =   trim($post['brand_name_en']);
			$data['brand_name_gr'] 	 =   trim($post['brand_name_gr']);
			$data['brand_name_tr'] 	 =   trim($post['brand_name_tr']);
			$data['modify_date']	 =   date('Y-m-d H:i:s');

			if($post['brand_image']){
				$data['brand_image'] = trim($post['brand_image']);
			}
			$updated=$this->commonmodel->_update('tbl_brands',$data,$condition);
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
	
	public function getBrandDetailsById($brand_id=""){
		if($brand_id!=""){
			$this->db->where('brand_id',$brand_id);
			$get=$this->db->get("tbl_brands");
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

/* -------------------- End Brand ---------------------- */		



}
?>
