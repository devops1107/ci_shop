<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class News_model extends CI_Model{
	
	public function getAllNews($data)
	{
        $user_id            = $this->site_santry->get_auth_data('id');
		$user_type		    = $this->site_santry->get_auth_data('user_type');
	    $software_where  	= 'where 1 AND c.delete_status = "0" '; 
		if($user_type=="partner"){
		    $software_where  	.= " AND c.user_id = ".$user_id."";
		}
		/* if($data['status']!="")
		{
			$software_where	.= ' AND c.status = "'.$data['status'].'" '; 
		} */
		 
	   $data['where_coloums'] 	    = array('serial_number');
       $data['select_order_colum']  = array('serial_number','uploaded_by','news_image','news_title','news_title_gr');
		$data['table_name']   		= "news";
		$data['indexColumn']  		= "news_id";
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
				$order_by = "ORDER BY news_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,c.*, CONCAT(au.first_name,' ',au.last_name) as uploaded_by FROM "."
		news c
		LEFT JOIN admin_users au ON au.user_id = c.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_item_count($software_where);
		
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
				   if($data['select_order_colum'][$i] == "news_image")
				   {
				   		if($aRow['news_image']!='' && is_file(UPLOAD_PHYSICAL_PATH.'news/'.$aRow['news_image']))
				   		{
				   			$news_image = '<img src="'.UPLOAD_URL.'news/'.$aRow['news_image'].'" width="150" />';
				   		}else{
				   			$news_image = '<img src="'.UPLOAD_URL.'news/default_news_image.jpg" width="150" />';
				   		}
					   $row[] = $news_image;
				   }else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/edit-news/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="View"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/delete-news/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="Delete Item" onclick="return confirm_delete();"><i class="mdi mdi-close"></i></a>
						</span>';
					$action .= 
						'<span>
							<a href="'. base_url().'admin/news-comments/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="View News Comments"><i class="mdi mdi-comment"></i></a>
						</span>';
					$action .= 
						'<span>
							<a href="'. base_url().'admin/news-likes/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="View News Likes"><i class="mdi mdi-heart"></i></a>
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
	
	public function list_item_count($software_where) {
		$where = str_replace('where',' ',$software_where);
		$this->db->select('c.news_id');
		$this->db->from('news c');
		$this->db->where($where);
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getAllNewsComments($data)
	{
		//pr($data,1);
        $user_id            = $this->site_santry->get_auth_data('id');
		$user_type		    = $this->site_santry->get_auth_data('user_type');
	    $software_where  	= 'where 1 AND nc.delete_status = "0" '; 
		
		/* if($data['status']!="")
		{
			$software_where	.= ' AND c.status = "'.$data['status'].'" '; 
		} */
		 
	   $data['where_coloums'] 	    = array('serial_number');
       $data['select_order_colum']  = array('serial_number','user_name','emailid','comment','created_on');
		$data['table_name']   		= "news_comments";
		$data['indexColumn']  		= "comment_id";
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
				$order_by = "ORDER BY news_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,nc.*, CONCAT(um.firstname,' ',um.lastname) as user_name,um.emailid FROM "."
		news_comments nc
		LEFT JOIN tbl_usermaster um ON um.id = nc.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_news_comments_count();
		
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
					if($data['select_order_colum'][$i] == "user_name")
					{
				   		if($aRow['user_name']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['user_name'])).'</a>';	
				   		}else{
							$row[] = "-";
				   		}
					}else if($data['select_order_colum'][$i] == "emailid"){
						if($aRow['emailid']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['emailid'])).'</a>';	
				   		}else{
							$row[] = "-";
				   		}
					}else if($data['select_order_colum'][$i] == "created_on"){
						if($aRow['created_on']!='')
				   		{
				   			$row[] = date('d-M-Y H:i a',strtotime($aRow['created_on']));	
				   		}else{
							$row[] = "-";
				   		}
					}else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';				
					/* $action .= 
						'<span>
							<a href="'. base_url().'admin/edit-news/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="View"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/delete-news/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="Delete Item" onclick="return confirm_delete();"><i class="mdi mdi-close"></i></a>
						</span>';
					$action .= '</div>'; */
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	public function list_news_comments_count() {
		
		$this->db->select('comment_id');
		$this->db->from('news_comments');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getAllNewsLikes($data)
	{
		//pr($data,1);
        $user_id            = $this->site_santry->get_auth_data('id');
		$user_type		    = $this->site_santry->get_auth_data('user_type');
	    $software_where  	= 'where 1 AND nl.delete_status = "0" '; 
		
		/* if($data['status']!="")
		{
			$software_where	.= ' AND c.status = "'.$data['status'].'" '; 
		} */
		 
	   $data['where_coloums'] 	    = array('serial_number');
       $data['select_order_colum']  = array('serial_number','user_name','emailid','created_on');
		$data['table_name']   		= "news_likes";
		$data['indexColumn']  		= "news_like_id";
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
				$order_by = "ORDER BY news_id DESC";
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
		
		$query = "SELECT SQL_CALC_FOUND_ROWS @a:=@a+1 as serial_number,nl.*, CONCAT(um.firstname,' ',um.lastname) as user_name,um.emailid FROM "."
		news_likes nl
		LEFT JOIN tbl_usermaster um ON um.id = nl.user_id
		".",(SELECT @a:= 0) AS a ". $software_where . $where .' '. $order_by." ".$limit;
		
		/* echo $query; die;  */ 
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_news_likes_count();
		
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
					if($data['select_order_colum'][$i] == "user_name")
					{
				   		if($aRow['user_name']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['user_name'])).'</a>';	
				   		}else{
							$row[] = "-";
				   		}
					}else if($data['select_order_colum'][$i] == "emailid"){
						if($aRow['emailid']!='')
				   		{
				   			$row[] = '<a href="'.base_url().'admin/view-user/'.base64_encode($aRow['user_id']).'" class="text-dark">'.ucfirst(strtolower($aRow['emailid'])).'</a>';	
				   		}else{
							$row[] = "-";
				   		}
					}else if($data['select_order_colum'][$i] == "created_on"){
						if($aRow['created_on']!='')
				   		{
				   			$row[] = date('d-M-Y H:i a',strtotime($aRow['created_on']));	
				   		}else{
							$row[] = "-";
				   		}
					}else{					   
					   $row[] = $aRow[$data['select_order_colum'][$i]];
				   }
				}else
				{
					$action	=  '<div class="action_box width-162 display-action">';				
					/* $action .= 
						'<span>
							<a href="'. base_url().'admin/edit-news/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="View"><i class="mdi mdi-pencil-box-outline"></i></a>
						</span>';				
					$action .= 
						'<span>
							<a href="'. base_url().'admin/delete-news/'.base64_encode($aRow['news_id']).'" class="btn btn-color button_icon" title="Delete Item" onclick="return confirm_delete();"><i class="mdi mdi-close"></i></a>
						</span>';
					$action .= '</div>'; */
					
					$row[]	= $action;
				}
			}
			$j++;
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	public function list_news_likes_count() {
		
		$this->db->select('news_like_id');
		$this->db->from('news_likes');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			return $result->num_rows();
		}else{
			return 0;
		}
	}
	
	public function getBnnerDetails($news_id) {
		
		$this->db->select('*');
		$this->db->from('news');
		$this->db->where('news_id',$news_id);
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
	
	public function getContactDetails($contact_id) {
		
		$this->db->select('*');
		$this->db->from('contact_details');
		$this->db->where('contact_id',$contact_id);
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
