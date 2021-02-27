<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class products_model extends CI_Model{


/* -------------------- Products ---------------------- */	
	
	public function getAllProducts($data)
	{
        $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
	    $data['where_coloums'] 	    = array('serial_number','product_name','product_name_gr','single_product_price');
        $data['select_order_colum']  = array('serial_number','product_image','brand_name','category_name','product_name','single_product_price','single_product_offer','status');
		$data['table_name']   		= "tbl_products";
		$data['indexColumn']  		= "product_id";
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
				$order_by = "ORDER BY product_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.* , tbl_categories.category_name , tbl_brands.brand_name FROM "." tbl_products c LEFT JOIN tbl_brands ON tbl_brands.brand_id = c.brand_id LEFT JOIN tbl_categories ON tbl_categories.category_id = c.category_id ,(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/*echo $query; die;  */	
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
				
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_tbl_products_count();
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
				   }elseif($data['select_order_colum'][$i]=='single_product_price'){
				   		$row[] = 'Single - € '.$aRow['single_product_price'].'<br>Umkarton (Kolli) - € '.$aRow['master_carton_price'].'<br>Palette - € '.$aRow['palette_price'];
				   }elseif($data['select_order_colum'][$i]=='single_product_offer'){
				   		$row[] = 'Stück (Stk.) - € '.$aRow['single_product_offer'].'<br>Umkarton (Kolli) - € '.$aRow['master_carton_offer'].'<br>Palette - € '.$aRow['palette_offer'];
				   }elseif($data['select_order_colum'][$i]=='product_image'){
				   		if($aRow['product_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/products/'.$aRow['product_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'products/'.$aRow['product_image'].'" width="50">';
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'products/default_product.png" width="50">';
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
							<a onClick="getProductDiscount('.$aRow['product_id'].');" data-toggle="modal" data-target="#discountModal" class="btn btn-color button_icon" title="Discount List"><i class="fas fa-bars"></i></a>
						</span>';
					$action .= 
						'<span>
							<a href="'. base_url().'admin/edit-product/'.base64_encode($aRow['product_id']).'" class="btn btn-color button_icon" title="Edit"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';	
					if($aRow['status']=='1')
					{
						$iconName = 'ban';
						$iconTitle = 'Deactivate Product';
					}else{
						$iconName = 'check';
						$iconTitle = 'Activate Product';
					}
					$action .= 
						'<span>
							<a href="'.base_url().'admin/products/change_activation_status/'.base64_encode($aRow['product_id']).'/'.base64_encode($aRow['status']).'" data-status="'.$aRow['status'].'" class="btn btn-color button_icon btn_dec" title="'.$iconTitle.'"><i class="fas fa-'.$iconName.'"></i></a>
						</span>';
					$action .= 
						'<span>
							<a onClick="confirm_delete();" href="'. base_url().'admin/delete-product/'.base64_encode($aRow['product_id']).'" class="btn btn-color button_icon btn_del" title="Delete Product"><i class="fas fa-trash"></i></a>
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

	public function list_tbl_products_count() {
		
		$this->db->select('product_id');
		$this->db->from('tbl_products');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}

	public function getProductDetailsById($product_id=""){
		if($product_id!=""){
			$this->db->where('product_id',$product_id);
			$get=$this->db->get("tbl_products");
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
	
	public function updateProducts($post,$product_id=""){
		
		if($product_id!=""){
			$data = array();
			$condition=array('product_id'=>$product_id);

			$data = array();
			$data['brand_id'] = trim($post['brand_id']);
			$data['category_id'] = trim($post['category_id']);
			//$data['subcategory_id'] = trim($post['subcategory_id']);
			$data['product_name'] = trim($post['product_name']);
			$data['product_name_gr'] = trim($post['product_name_gr']);
			$data['product_name_tr'] = trim($post['product_name_tr']);
			$data['single_product_price'] = trim($post['single_product_price']);
			$data['single_product_offer'] = trim($post['single_product_offer']);
			$data['master_carton_price'] = trim($post['master_carton_price']);
			$data['master_carton_offer'] = trim($post['master_carton_offer']);
			$data['palette_price'] = trim($post['palette_price']);	
			$data['palette_offer'] = trim($post['palette_offer']);
			$data['description'] = trim($post['description']);
			$data['description_tr'] = trim($post['description_tr']);
			$data['description_gr'] = trim($post['description_gr']);

			if($post['product_image']){
				$data['product_image'] = trim($post['product_image']);
			}

			$updated=$this->commonmodel->_update('tbl_products',$data,$condition);
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

	public function addProduct($post){
		if($post){
			//pr($post,1);
			$data = array();
			$data['brand_id'] = trim($post['brand_id']);
			$data['category_id'] = trim($post['category_id']);
			//$data['subcategory_id'] = trim($post['subcategory_id']);
			$data['product_name'] = trim($post['product_name_en']);
			$data['product_name_gr'] = trim($post['product_name_gr']);
			$data['product_name_tr'] = trim($post['product_name_tr']);
			$data['single_product_price'] = trim($post['single_product_price']);
			$data['single_product_offer'] = trim($post['single_product_offer']);
			$data['master_carton_price'] = trim($post['master_carton_price']);
			$data['master_carton_offer'] = trim($post['master_carton_offer']);
			$data['palette_price'] = trim($post['palette_price']);	
			$data['palette_offer'] = trim($post['palette_offer']);
			$data['description'] = trim($post['description']);
			$data['description_tr'] = trim($post['description_tr']);
			$data['description_gr'] = trim($post['description_gr']);

			$data['product_image']  =	 trim($post['product_image']);
			$data['created_on']  	 =	 date('Y-m-d H:i:s');
			$data['modify_date'] 	 =	date('Y-m-d H:i:s');
			if($this->commonmodel->_insert('tbl_products', $data)){
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
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

	public function brand_dropdown_list() {
		
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

	public function product_discount_list($product_id) {
		
		$this->db->select('tbl_product_discount.*,tbl_products.product_name');
		$this->db->from('tbl_product_discount');
		$this->db->where('tbl_product_discount.product_id',$product_id);
		$this->db->join("tbl_products" , "tbl_products.product_id = tbl_product_discount.product_id" , "LEFT");
		$this->db->where('tbl_product_discount.delete_status','0');
		$result = $this->db->get();
		return $resulArray = $result->result_array();
	}

	public function product_discount_details($id) {
		
		$this->db->select('*');
		$this->db->from('tbl_product_discount');
		$this->db->where('id',$id);
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		return $resulArray = $result->row_array();
	}

	public function addProductDiscount($post){
		if($post){
			//pr($post,1);
			$data = array();
			$data['product_id'] = trim($post['product_id']);
			$data['price_type'] = trim($post['price_type']);
			$data['quantity'] = trim($post['quantity']);
			$data['discount_price'] = trim($post['discount_price']);
			$data['created_on']  	 =	 date('Y-m-d H:i:s');
			$data['modify_date'] 	 =	date('Y-m-d H:i:s');
			if($this->commonmodel->_insert('tbl_product_discount', $data)){
				return true;
			}else{
				return false;
			}
		}
		else {
			return false;
		}
	}

/* -------------------- Sub Category ---------------------- */


}
?>
