<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Website_model extends CI_Model{
	
	
	

	public function cms_pages_count($page_type) {
		$this->db->select('cms_page_id');
		$this->db->from('cms_pages');
		$this->db->where('page_key',$page_type);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
		 return $result->num_rows();
		}else{
		 return '0';
		}
	}

	public function getCMSPages($page_key){
				
	   $query ="SELECT * FROM cms_pages WHERE page_key = '".$page_key."' AND delete_status='0'  ";
	   $rResult = $this->db->query($query);
	   $result  =  $rResult->result_array();

	   if(!empty($result)){
		  return $result[0];
	   }else{
		  return '';	
	   }   
	}

    public function do_upload_by_ajax($submited_name)
	{
		
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'user-images/';
		if(!is_dir($folderPath))
		{
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'jpg|jpeg|png';
		$config['max_size']             = 1024;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name))
		{
			$data = array('error' => 'yes','msg' => $this->upload->display_errors());
		}else{
			$data = array('error' => 'no','upload_data' => $this->upload->data());
		}
		return $data;
	}

	public function random_key($length=10)
	{
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$email_token = '';
		for ($i = 0; $i < $length; $i++) {
			  $email_token .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $email_token;
	}

	public function editCMS($post,$page_key="") {

		//pr($page_key,1);
		if($page_key!=""){
			$table='cms_pages';
			$this->db->select('*');
			$this->db->select('cms_page_id');
			$this->db->from('cms_pages');
			$this->db->where('page_key',$page_key);
			$this->db->where('delete_status','0');
			$query = $this->db->get();
			if($query->num_rows() >0){
				$result = $query->row_array();
				//pr($result,1);
				$modified_on = $result['modified_on'];
				if($result['description'] == ""){
					$mod_date				=	date('Y-m-d H:i:s');
					//$data['page_name']	=	$post['page_name'];
					$data['description']	=	$post['description'];
					$data['modified_on']	=	$mod_date;
					$data['description_tr']	=	$post['description_tr'];
					//echo "helloo";die;
					$updateUser=$this->commonmodel->_update($table, $data,array('page_key' => $page_key));
					return true;
				}else{
					$data=array();
					$mod_date				=	date('Y-m-d H:i:s');
					$data['description']	=	$post['description'];
					$data['description_gr']	=	$post['description_gr'];
					$data['description_tr']	=	$post['description_tr'];
					$data['modified_on']	=	$mod_date;
					//echo "hiiiiii";die;
					$updateUser=$this->commonmodel->_update($table, $data,array('page_key' => $page_key));
					if($updateUser){
						$new_arr = array(
							'cms_page_type'			=>	$page_key,
							'cms_page_description'	=>	$post['description'],
							'cms_page_description_gr'	=>	$post['description_gr'],
							'cms_page_description_tr'	=>	$post['description_tr'],
							'created_on'			=>	$mod_date,
							'version_changed_on'	=>	date('Y-m-d H:i:s'),
							'version_created_on'	=>	$modified_on,
						);
						$createVersion = $this->commonmodel->_insert('cms_pages_versions',$new_arr);
						return true;
					}else {
						return false;
					}
				}
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
	
	public function get_faq($data)
	{
		//pr($data);die;
		$software_where  = 'where 1 And fq.delete_status = "0" '; 		
		
		$data['where_coloums']  = array('fq.faq_id','fq.question','fq.answer');
		$data['select_order_colum'] = array('faq_id','question','answer');
		
		
		$data['table_name']       = "tbl_faqs";
		$data['indexColumn']      = "faq_id";
		//print_r($data);die();
		// pagination code start 
		$limit = '';
		if ( isset($data['post']['iDisplayStart'] ) && $data['post']['iDisplayLength'] != '-1' )
		{
			$offset = intval($data['post']['iDisplayStart']);
			$limit = "LIMIT ".intval($data['post']['iDisplayStart']).", ".intval($data['post']['iDisplayLength']);  
		}
		// pagination code end 
		
		/* Ordering */
		if(isset($data['post']['iSortCol_0']))
		{
			$order_by = "ORDER BY  ";
			//print_r($order_by);die();
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
				$order_by = "";
			}
		}
		$where = "";
		if ($data['post']['sSearch'] != "" ) {
			$where = " and (";
			for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ ) {
				$where .= $data['where_coloums'][$i]." LIKE '%".@mysql_real_escape_string($data['post']['sSearch'] )."%' OR ";
			}
			$where = substr_replace( $where, "", -3 );
			$where .= ')';
		}
		
		for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ ) {
			if ($data['post']['bSearchable_'.$i] == "true" && $data['post']['sSearch_'.$i] != '' ) {
				if($where == "") {
					$where = "WHERE ";
				}
				else {
					$where .= " AND ";
				}
				echo $where .= $data['where_coloums'][$i]." LIKE '%".@mysql_real_escape_string($data['post']['sSearch_'.$i])."%' ";
			}
		}
		$query = "SELECT fq.* FROM tbl_faqs as fq ".$software_where.$where." ".$order_by.' '.$limit;
		//echo $query;die;
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		
		$display_records = count($result);
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		$total_records = $this->list_faq_count($software_where,$where,strtoupper($data['type']));
		
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
		
		foreach($result as $aRow ) {
			$row = array();
			for ( $i=0 ; $i<count($data['select_order_colum']) ; $i++ ) {
				if ($data['select_order_colum'][$i] != ' ' ) {
					if($i==0){
						$row[] = $j++;
						}elseif($data['select_order_colum'][$i]=='question'){
							if(trim($aRow['question'])!='') {
								$row[] = ucfirst(strtolower($aRow['question'])); 
								}else{
								$row[] = '-';
							} 
						}elseif($data['select_order_colum'][$i]=='category_name_cn'){
							if(trim($aRow['category_name_cn'])!='') {
								$row[] = $aRow['category_name_cn']; 
								}else{
								$row[] = '-';
							} 
						}elseif($data['select_order_colum'][$i]=='status'){
							if($aRow['status']=="1") {
								$row[] = 'Activated';
								}else{
								$row[] = 'Blocked';
							}
						}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
					}else {
						
						$edit_url = base_url('admin/edit-faq/'.base64_encode($aRow['faq_id']));
						$delete_url = base_url('admin/delete-faq/'.base64_encode($aRow['faq_id']));
						
						$html = '';
						$row[] = '
						<div class="action_box display-action">
						<span>
							<a href="'. $edit_url.'" class="btn btn-color button_icon text-success" title="Edit"><i class="fa fa-edit"></i></a>
							<a href="'. $delete_url.'" class="btn btn-color button_icon text-danger confim_del" title="Delete"><i class="fa fa-trash"></i></a></span>
						&nbsp
						'.$html.' 
						&nbsp
						'.$html.'          
						</div>';
					}
			}
			$output['aaData'][] = $row;
		}
		return $output;
	}
	
    public function list_faq_count($software_where,$where,$type) {
		//print_r($type);die;
		$this->db->select('fq.faq_id');
		$this->db->from('tbl_faqs fq');
		$this->db->where('fq.type',$type);
		$this->db->where('fq.delete_status','0');
		$result = $this->db->get();
		if($result->num_rows() > 0) {
			//echo $this->db->last_query();die;
			return $result->num_rows();
		}else{
			//echo $this->db->last_query();die;
			return '0';
		}
	}
	
	public function getFaqDetail($id){
			
		//   echo $id;die();
		$query ="SELECT * FROM tbl_faqs WHERE faq_id = '".$id."' AND delete_status='0'  ";
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		// print_r($result);die();
		if($result>0){
			return $result[0];
			}else{
			return '';  
		}   
	}
    
	public function getContactDetail(){
			
		//   echo $id;die();
		$query ="SELECT * FROM tbl_contact WHERE id = 1";
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		// print_r($result);die();
		if($result>0){
			return $result[0];
			}else{
			return '';  
		}   
	}

	public function getTaxSetting(){
			
		$query ="SELECT * FROM tbl_tax_setting WHERE id = 1";
		$rResult = $this->db->query($query);
		$result  =  $rResult->row_array();
		//print_r($result);die();
		if($result>0){
			return $result;//['tax'];
			}else{
			return '';  
		}   
	}
}
?>