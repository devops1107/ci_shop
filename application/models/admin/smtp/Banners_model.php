<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Banners_model extends CI_Model{
	
	public function getAllBanners($data)
	{
        $user_id            = $this->site_santry->get_auth_data('id');
		$user_type		    = $this->site_santry->get_auth_data('user_type');
	    $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
		/* if($data['status']!="")
		{
			$software_where	.= ' AND c.status = "'.$data['status'].'" '; 
		} */
		 
	   $data['where_coloums'] 	    = array('serial_number');
       $data['select_order_colum']  = array('serial_number','banner_image');
		$data['table_name']   		= "banners";
		$data['indexColumn']  		= "banner_id";
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
				$order_by = "ORDER BY banner_id DESC";
			}
		}
		$where = "";
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=1 ; $i<count($data['where_coloums']) ; $i++ )
			{
				$where .= $data['where_coloums'][$i]." LIKE '%".mysql_real_escape_string($data['post']['sSearch'] )."%' OR ";
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
				echo $where .= $$data['where_coloums'][$i]." LIKE '%".mysql_real_escape_string($data['post']['sSearch_'.$i])."%' ";
			}
		}
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.* FROM "."
		banners c
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_item_count();
		
		/* Output */
		$output = array(
			"sEcho" => intval($data['post']['sEcho']),
			"iTotalRecords" => $total_records,
			"iTotalDisplayRecords" => $display_records,
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
				   if($data['select_order_colum'][$i] == "banner_image")
				   {
				   		if($aRow['banner_image']!='' && is_file(UPLOAD_PHYSICAL_PATH.'banners/'.$aRow['banner_image']))
				   		{
				   			$banner_image = '<img src="'.UPLOAD_URL.'banners/'.$aRow['banner_image'].'" width="150" />';
				   		}else{
				   			$banner_image = '<img src="'.UPLOAD_URL.'banners/default_banner_image.jpg" width="150" />';
				   		}
					   $row[] = $banner_image;
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/edit-banner/'.base64_encode($aRow['banner_id']).'" class="btn btn-color button_icon" title="View"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/delete-banner/'.base64_encode($aRow['banner_id']).'" class="btn btn-color button_icon" title="Delete Item" onclick="return confirm_delete();"><i class="mdi mdi-close"></i></a>
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
	
	public function list_item_count() {
		
		$this->db->select('banner_id');
		$this->db->from('banners');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getBnnerDetails($banner_id) {
		
		$this->db->select('*');
		$this->db->from('banners');
		$this->db->where('banner_id',$banner_id);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			$details = $result->result_array();
			$details = $details[0];
			return $details;
		}else{
			return array();
		}
	}
	
	public function getCategoriesList() {
		
		$categories = $this->commonmodel->_get_data('categories',array('delete_status'=>'0'),'*');
		$result = array(''=>'--Select Category--');
		if($categories){
			foreach($categories as $category)
			{
				$result[$category['category_id']] = $category['category_name'];
			}
		}
		return $result;
	}
}
?>
