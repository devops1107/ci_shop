<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Categories_model extends CI_Model{


/* -------------------- Category ---------------------- */	
	public function getAllCategories($data)
	{
        $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
	   $data['where_coloums'] 	    = array('serial_number','category_name','category_name_gr','category_name_tr');
       $data['select_order_colum']  = array('serial_number','category_image','category_name','category_name_gr','category_name_tr','status');
		$data['table_name']   		= "tbl_categories";
		$data['indexColumn']  		= "category_id";
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
				$order_by = "ORDER BY category_id DESC";
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
		tbl_categories c
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_categories_count();
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
				   }elseif($data['select_order_colum'][$i]=='category_image'){
				   		if($aRow['category_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/categories/'.$aRow['category_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'categories/'.$aRow['category_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'categories/default_category.png" width="50">';
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
							<a href="'. base_url().'admin/edit-category/'.base64_encode($aRow['category_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
						$iconTitle = 'Deactivate Category';
					}else{
						$iconName = 'check';
						$iconTitle = 'Activate Category';
					}
					$action .= 
						'<span>
							<a href="'. base_url().'admin/categories/change_activation_status/'.base64_encode($aRow['category_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_dec" title="'.$iconTitle.'"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					$action .= 
						'<span>
							<a onClick="confirm_delete();" href="'. base_url().'admin/delete-category/'.base64_encode($aRow['category_id']).'" class="btn btn-color button_icon btn_del" title="Delete Category"><i class="fas fa-trash"></i></a>
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

	public function list_categories_count() {
		
		$this->db->select('category_id');
		$this->db->from('tbl_categories');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function category_details($condition) {
		
		$this->db->select('c.*');
		$this->db->from('tbl_categories c');
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
		$this->db->from('tbl_categories');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		$resulArray = $result->result_array();
		if($resulArray > 0) {
			$category=array(''=>'-- Select Category --');
			foreach ($resulArray as $val) {
				$category[$val['category_id']]=$val['category_name'];
			}
			return $category;
		}else{
			return array();
		}
	}

	public function addCategories($post){
		if($post){
			//pr($post,1);
			$data = array();
			$data['category_name']	 =	 trim($post['category_name_en']);
			$data['category_name_gr']	 =	 trim($post['category_name_gr']);
			$data['category_name_tr']	 =	 trim($post['category_name_tr']);
			$data['category_image']  =	 trim($post['category_image']);
			$data['created_on']  	 =	 date('Y-m-d H:i:s');
			$data['modify_date'] 	 =	date('Y-m-d H:i:s');
			if($this->commonmodel->_insert('tbl_categories', $data)){
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	public function updateCategories($post,$category_id=""){
		//echo $category_id;
		if($category_id!=""){
			$data = array();
			$condition=array('category_id'=>$category_id);
			$data['category_name'] 	 =   trim($post['category_name_en']);
			$data['category_name_gr'] 	 =   trim($post['category_name_gr']);
			$data['category_name_tr'] 	 =   trim($post['category_name_tr']);
			$data['modify_date']	 =   date('Y-m-d H:i:s');

			if(isset($post['category_image'])){
				$data['category_image'] = trim($post['category_image']);
			}
			$updated=$this->commonmodel->_update('tbl_categories',$data,$condition);
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
	
	public function getCategoryDetailsById($category_id=""){
		if($category_id!=""){
			$this->db->where('category_id',$category_id);
			$get=$this->db->get("tbl_categories");
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

/* -------------------- End Category ---------------------- */		


/* -------------------- Sub Category ---------------------- */	
	
	public function getAllSubCategories($data)
	{
        $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
	   $data['where_coloums'] 	    = array('serial_number','subcategory_name','subcategory_name_gr','subcategory_name_tr');
       $data['select_order_colum']  = array('serial_number','subcategory_image','category_name','subcategory_name','subcategory_name_gr','subcategory_name_tr','status');
		$data['table_name']   		= "tbl_sub_categories";
		$data['indexColumn']  		= "sub_category_id";
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
				$order_by = "ORDER BY sub_category_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.* , tbl_categories.category_name FROM "."
		tbl_sub_categories c "." LEFT JOIN tbl_categories ON tbl_categories.category_id = c.category_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/*echo $query; die;  */	
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_sub_categories_count();
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
				   }elseif($data['select_order_colum'][$i]=='subcategory_image'){
				   		if($aRow['subcategory_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/categories/'.$aRow['subcategory_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'categories/'.$aRow['subcategory_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'categories/default_category.png" width="50">';
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
							<a href="'. base_url().'admin/edit-subcategory/'.base64_encode($aRow['sub_category_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
						$iconTitle = 'Deactivate Category';
					}else{
						$iconName = 'check';
						$iconTitle = 'Activate Category';
					}
					$action .= 
						'<span>
							<a href="'.base_url().'admin/categories/change_subcat_activation_status/'.base64_encode($aRow['sub_category_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_dec" title="'.$iconTitle.'"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					$action .= 
						'<span>
							<a onClick="confirm_delete();" href="'. base_url().'admin/delete-subcategory/'.base64_encode($aRow['sub_category_id']).'" class="btn btn-color button_icon btn_del" title="Delete Category"><i class="fas fa-trash"></i></a>
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

	public function list_sub_categories_count() {
		
		$this->db->select('sub_category_id');
		$this->db->from('tbl_sub_categories');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}

	public function getSubCategoryDetailsById($sub_category_id=""){
		if($sub_category_id!=""){
			$this->db->where('sub_category_id',$sub_category_id);
			$get=$this->db->get("tbl_sub_categories");
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
	
	public function updateSubCategories($post,$sub_category_id=""){
		
		if($sub_category_id!=""){
			$data = array();
			$condition=array('sub_category_id'=>$sub_category_id);
			$data['category_id'] = trim($post['category_id']);
			$data['subcategory_name'] = trim($post['subcategory_name_en']);
			$data['subcategory_name_gr'] = trim($post['subcategory_name_gr']);
			$data['subcategory_name_tr'] = trim($post['subcategory_name_tr']);
			$data['modify_date'] = date('Y-m-d H:i:s');

			if($post['subcategory_image']){
				$data['subcategory_image'] = trim($post['subcategory_image']);
			}
			$updated=$this->commonmodel->_update('tbl_sub_categories',$data,$condition);
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

	public function addSubCategories($post){
		if($post){
			//pr($post,1);
			$data = array();
			$data['category_id'] = trim($post['category_id']);
			$data['subcategory_name'] = trim($post['subcategory_name_en']);
			$data['subcategory_name_gr'] = trim($post['subcategory_name_gr']);
			$data['subcategory_name_tr'] = trim($post['subcategory_name_tr']);
			$data['subcategory_image']  =	 trim($post['subcategory_image']);
			$data['created_on']  	 =	 date('Y-m-d H:i:s');
			$data['modify_date'] 	 =	date('Y-m-d H:i:s');
			if($this->commonmodel->_insert('tbl_sub_categories', $data)){
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function get_sub_categories_list($category_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_sub_categories');
		$this->db->where('category_id',$category_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		$resulArray = $result->result_array();
		if($resulArray > 0) {
			$category=array();
			foreach ($resulArray as $val) { 
				$category[$val['sub_category_id']]=$val['subcategory_name'];
			}
			return $category;
		}else{
			return array();
		}
	}

/* -------------------- Sub Category ---------------------- */


}
?>
