<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Slider_model extends CI_Model{
	
	public function getAllSliders($data)
	{
        $user_id            = $this->site_santry->get_auth_data('id');
		$user_type		    = $this->site_santry->get_auth_data('user_type');
	    $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		
		/* if($data['status']!="")
		{
			$software_where	.= ' AND c.status = "'.$data['status'].'" '; 
		} */
		 
	   $data['where_coloums'] 	    = array('serial_number');
       $data['select_order_colum']  = array('serial_number','slider_image');
		$data['table_name']   		= "tbl_slider";
		$data['indexColumn']  		= "slider_id";
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
				$order_by = "ORDER BY slider_id DESC";
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
		tbl_slider c
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
				   if($data['select_order_colum'][$i] == "slider_image")
				   {
				   		if($aRow['slider_image']!='' && is_file(UPLOAD_PHYSICAL_PATH.'slider/'.$aRow['slider_image']))
				   		{
				   			$slider_image = '<img src="'.UPLOAD_URL.'slider/'.$aRow['slider_image'].'" height="80" />';
				   		}else{
				   			$slider_image = '<img src="'.UPLOAD_URL.'slider/default_slider_image.jpg" height="80" />';
				   		}
					   $row[] = $slider_image;
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/edit-slider/'.base64_encode($aRow['slider_id']).'" class="btn btn-color button_icon" title="View"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/delete-slider/'.base64_encode($aRow['slider_id']).'" class="btn btn-color button_icon" title="Delete Item" onclick="return confirm_delete();"><i class="fas fa-trash"></i></a>
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
		
		$this->db->select('slider_id');
		$this->db->from('tbl_slider');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getSliderDetails($slider_id) {
		
		$this->db->select('*');
		$this->db->from('tbl_slider');
		$this->db->where('slider_id',$slider_id);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			$details = $result->result_array();
			$details = $details[0];
			return $details;
		}else{
			return array();
		}
	}
}
?>
