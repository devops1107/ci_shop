<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Contact_model extends CI_Model{


/* -------------------- Products ---------------------- */	
	
	public function getAllContacts($data)
	{
        $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
	    $data['where_coloums'] 	    = array('serial_number','name','mobile_no','email');
        $data['select_order_colum']  = array('serial_number','name','mobile_no','email','message');
		$data['table_name']   		= "tbl_contact_us";
		$data['indexColumn']  		= "contact_us_id";
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
				$order_by = "ORDER BY contact_us_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.* FROM tbl_contact_us c ". $software_where . $where .' '. $order_by." ".$limit;
		
		/*echo $query; die;  */	
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_tbl_contact_us_count();
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
							$row[] = 'Pending';
						}elseif($aRow['status']=='2')
						{
							$row[] = 'Ongoing';
						}elseif($aRow['status']=='3')
						{
							$row[] = 'Delivered';
						}else{
							$row[] = 'Canceled';
						}
				    }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				    }
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}	

	public function list_tbl_contact_us_count() {
		
		$this->db->select('contact_us_id');
		$this->db->from('tbl_contact_us');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}

	public function getOrderDetailsById($data){

		$contact_us_id = $data['contact_us_id'];
		$software_where  	= "where 1 AND contact_us_id = '$contact_us_id' "; 
		
	    $data['where_coloums'] 	    = array('serial_number');
        $data['select_order_colum']  = array('serial_number','product_name','shop_type','price','discount','net_amount','quantity','total_amount');
		$data['table_name']   		= "tbl_order_details";
		$data['indexColumn']  		= "order_detail_id";
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
				$order_by = "ORDER BY order_detail_id DESC";
			}
		}
		$where = "";
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.* , tbl_products.product_name,tbl_products.product_image FROM "." tbl_order_details c LEFT JOIN tbl_products ON tbl_products.product_id = c.product_id ". $software_where . $where .' '. $order_by." ".$limit;
		
		/*echo $query; die;  */	
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_tbl_contact_us_count();
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
							$row[] = 'Pending';
						}elseif($aRow['status']=='2')
						{
							$row[] = 'Ongoing';
						}elseif($aRow['status']=='3')
						{
							$row[] = 'Delivered';
						}else{
							$row[] = 'Canceled';
						}
				    }elseif($data['select_order_colum'][$i]=='price' || $data['select_order_colum'][$i]=='discount' || $data['select_order_colum'][$i]=='net_amount' || $data['select_order_colum'][$i]=='total_amount'){
				   		$row[] = "$ ".$aRow[$data['select_order_colum'][$i]];
				   	}elseif($data['select_order_colum'][$i]=='shop_type'){
				   		if($aRow['shop_type'] == '1')
				   		{
				   			$row[] = "Single Product";	
				   		}
				   		elseif($aRow['shop_type'] == '2')
				   		{
				   			$row[] = "Master Carton";	
				   		}
				   		else
				   		{
				   			$row[] = "Palette";	
				   		}
				   		
				    }elseif($data['select_order_colum'][$i]=='product_name'){
				   		if($aRow['product_image']!="" && file_exists(UPLOAD_PHYSICAL_PATH.'/products/'.$aRow['product_image'])){
							$row[] = '<img src="'.UPLOAD_URL.'products/'.$aRow['product_image'].'" width="50">'.$aRow['product_name'];
				   		}else{
							$row[] = '<img src="'.UPLOAD_URL.'products/default_product.png" width="50">'.$aRow['product_name'];
				   		}
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				    }
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	public function updateProducts($post,$contact_us_id=""){
		
		if($contact_us_id!=""){
			$data = array();
			$condition=array('contact_us_id'=>$contact_us_id);

			$data = array();
			$data['brand_id'] = trim($post['brand_id']);
			$data['category_id'] = trim($post['category_id']);
			$data['subcategory_id'] = trim($post['subcategory_id']);
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

			if($post['product_image']){
				$data['product_image'] = trim($post['product_image']);
			}

			$updated=$this->commonmodel->_update('tbl_contact_us',$data,$condition);
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

/* -------------------- Sub Category ---------------------- */


}
?>
