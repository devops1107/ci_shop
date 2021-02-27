<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Payments_model extends CI_Model{
	
	

	public function getVendorPaymentDetails($post) 
	{
		//pr($post,1);
		$partner_id = $post['partner_id'];
		//print_r($partner_id);die;
		$this->db->select('*');
		$this->db->from('admin_users');
		$this->db->where('user_id',$partner_id);
		$this->db->where('partner_request_status','ACCEPTED');
		$this->db->where('user_delete','0');
		$result = $this->db->get();
		//echo $this->db->last_query();die;
		$responseArr = array();
		if($result->num_rows() > 0)
		{
		 	$partnerData 								=	 $result->row_array();
			//pr($partnerData,1);
			$transationDetails['shop_name']				=	 $partnerData['first_name'];
			$outstandingBalance							=	 $this->getOutstandingBalanceEachShop($partner_id);
			$outstandingBalance							=	 str_replace('-', '', $outstandingBalance);
			$transationDetails['id'] 					=	 base64_encode($partnerData['user_id']);
			$transationDetails['shopaccountNumber']		=	 "";
			$transationDetails['outstandingBalance']	=	 $outstandingBalance;
			//print_r($transationDetails);die;
			return $transationDetails;
		}else{
			return array();
		}
	}
	
	public function getPartnerCommissionPercent(){
	    $comm_query =  'SELECT partner_commission_percent FROM settingmaster';
	    $result = $this->db->query($comm_query);
		$partner_commission  =  $result->row_array();
		return $partner_commission['partner_commission_percent'];
	}
	
	public function getOutstandingBalanceEachShop($partner_id=""){
	    $partner_commission_percent  =  $this->getPartnerCommissionPercent();
		if($partner_id!=""){
			$query='SELECT au.*, partnerPaybleAmount(au.user_id,'.$partner_commission_percent.')-CASE WHEN totalPartnerPaymentAmount(au.user_id) IS NOT NULL  THEN totalPartnerPaymentAmount(au.user_id) ELSE 0 END as balance_outstanding   FROM admin_users au where 1 AND au.user_id="'.$partner_id.'" AND au.user_delete = "0" AND partnerPaybleAmount(au.user_id,'.$partner_commission_percent.')>"0"';
			
			$rResult = $this->db->query($query);
			$totalResult  =  $rResult->row_array();
			//pr($totalResult,1);
			$balance_outstanding=$totalResult['balance_outstanding'];	
			if($balance_outstanding!=""){
				return $balance_outstanding;
			}else{
				return '0';
			}
		}else{
			return false;
		}
	}
	
	public function addPaymentDetails($post){
		//pr($post,1);
		$partner_id=$post['partner_id'];
		//pr($partner_id,1);
		$paymentArr = array();
		$mandtoryError = false;
		$outstandingError = false;
		if($post['payment_amount']=='')
		{
			$mandtoryErrorMessage = "All fileds are required!";
			$mandtoryError = true;
		}
		$balance_outstanding=$this->getOutstandingBalanceEachShop($partner_id);
		if($balance_outstanding>'0' && $post['payment_amount']>$balance_outstanding){
			$outstandingMessage = 'Your outstanding balance is '.$balance_outstanding.'';
			$outstandingError = true;
		}
		$responseArr = array();
		if($mandtoryError===false)
		{
			//echo $partner_id; die;
			$this->db->where('user_id',$partner_id);
			/* $this->db->where('user_status','1'); */
			$this->db->where('partner_request_status','ACCEPTED');
			$this->db->where('user_delete','0');
			$result = $this->db->get('admin_users');
			if($result->num_rows()>0)
			{	
				//$post['date_of_payment']=str_replace('-', '/', $post['date_of_payment']);
				$paymentArr['partner_id'] = $post['partner_id'];
				$paymentArr['payment_amount'] = $post['payment_amount'];
				//$paymentArr['shop_bank_account_number'] = $post['shop_account_number'];
				$paymentArr['date_of_payment'] = date('Y-m-d');
				$paymentArr['created'] = date('Y-m-d H:i:s');
				$paymentArr['updated'] = date('Y-m-d H:i:s');
			
				$vendorArray=$result->row_array();
				/* $settle_cycle=$vendorArray['settlement_payment_cycle']; */
				/* $next_payment_due_date_business=$vendorArray['next_payment_due_date']; */
				//$date_of_payment=date('Y-m-d',strtotime($post['date_of_payment'])); 
				/* $next_payment_due_date="";
				if($settle_cycle == 'monthly'){
					$next_payment_due_date = date('Y-m-d', strtotime($date_of_payment. ' + 1 month'));
				}else if($settle_cycle == 'weekly'){
					$next_payment_due_date = date('Y-m-d', strtotime($date_of_payment. ' + 1 week'));
				}else if($settle_cycle == 'daily' || $settle_cycle == 'Daily'){
					$next_payment_due_date = date('Y-m-d', strtotime($date_of_payment. ' + 1 day'));
				}else if($settle_cycle == 'monday' || $settle_cycle == 'tuesday' || $settle_cycle == 'wednesday' || $settle_cycle == 'thuresday' || $settle_cycle == 'friday' || $settle_cycle == 'saturday' || $settle_cycle == 'sunday'){
					$next_payment_due_date = date('Y-m-d', strtotime('next '.$settle_cycle.'', strtotime($date_of_payment)));
				}else{
					$next_payment_due_date ='';
				}
				//pr($next_payment_due_date,1); */
				
				/* $paymentArr['next_payment_due_date'] = $next_payment_due_date; */
				//$date_of_payment;
				//pr($paymentArr,1);	
				/* $update_array=array();
				$update_array['next_payment_due_date']=$next_payment_due_date; 
				$update_array['last_payment_due_date']=$date_of_payment;
				if($date_of_payment!=$next_payment_due_date_business){
					$update_array['last_payment_due_status']='1';
					$paymentArr['last_payment_due_status']='1';
				}else{
					$update_array['last_payment_due_status']='0';
				} 
				$this->commonmodel->_update('admin_users',$update_array,array('user_id'=>$partner_id));
				//$paymentArr['date_of_payment'] = date('Y-m-d',strtotime($post['date_of_payment']));*/
				if($this->commonmodel->_insert('admin_payments', $paymentArr))
				{
					$responseArr['status']='success';
					$responseArr['message']="Payment has been added successfully";
				}else{
					$responseArr['status']='error';
					$responseArr['message']="Something went wrong...please try again!";
				}
			}else{
				$responseArr['status']='error';
				$responseArr['message']='Something went wrong...please try again!';
			}
		}else{


			if($mandtoryError===true)
			{
				$responseArr['status'] = 'error';
				$responseArr['message'] = $mandtoryErrorMessage;
			}elseif($outstandingError===true){
				$responseArr['status'] = 'error';
				$responseArr['message'] = $outstandingMessage;
			}

		}
		return $responseArr;
	}
	
	
	public function allUpcomingPaymentsList($data)
	{ 
		//print_r($data);die;
		$partner_commission_percent  =  $this->getPartnerCommissionPercent();
		//pr($partner_commission_percent,1);
		$software_where  = 'where 1 And au.user_delete = "0" AND partnerPaybleAmount(au.user_id,'.$partner_commission_percent.')>0 ';
		$data['where_coloums'] 	= array('au.user_id','au.first_name','au.next_payment_due_date');
        $data['select_order_colum'] = array('user_id','first_name','balance_outstanding','partner_last_payment_date');
			
		$data['table_name']   		= "admin_users";
		$data['indexColumn']  		= "au.user_id";
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
				$order_by = "";
			}
		}
		//echo $order_by; die();
		/*if($order_by == "ORDER BY  category_id
									asc")
		{
			$order_by = "ORDER BY ic.category_id DESC";
		}*/
		//echo $order_by; die;
		$where = "";
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ )
			{

				$where .= $data['where_coloums'][$i]." LIKE '%".($data['post']['sSearch'] )."%' OR ";
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
				echo $where .= $data['where_coloums'][$i]." LIKE '%".($data['post']['sSearch_'.$i])."%' ";
			}
		}
		
		
		$query = "SELECT * FROM (SELECT au.*, partnerPaybleAmount(au.user_id,'.$partner_commission_percent.')-CASE WHEN totalPartnerPaymentAmount(au.user_id) IS NOT NULL THEN totalPartnerPaymentAmount(au.user_id) ELSE 0 END as balance_outstanding FROM admin_users au ". $software_where . $where .' '. $order_by." ".$limit.') AS SUBQUERY';
		//echo $query;die;     
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		//print_r($result);die;
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_payments_count($software_where);
		
		
		if($where!=''){
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
			//print_r($aRow);
			$row = array();
			for ( $i=0 ; $i<count($data['select_order_colum']) ; $i++ )
			{
				$today = date('Y-m-d');
				if ($data['select_order_colum'][$i] != ' ' )
				{
					if($data['select_order_colum'][$i]=='user_id'){
						if(!isset($aRow['user_id']))
						{
							$row[] = '-';	
						}else{
							$row[] = $j++;
						}
					}else if($data['select_order_colum'][$i]=='main_balance_outstanding'){
						if($aRow['main_balance_outstanding']!=""){
				   			if($aRow['main_balance_outstanding']=='0'){
					  			$row[] ='<span style="color:#ff6c60;"><b>'.number_format($aRow['main_balance_outstanding'],2).'</b></span>';
				   			}else{
				   				$row[] ='<span style="color:#a9d86e;"><b>'.number_format($aRow['main_balance_outstanding'],2).'</b></span>';
				   			}
				   		}else{
					  		$row[]='0';
				   		}
					}else if($data['select_order_colum'][$i]=='balance_outstanding'){
						if($aRow['balance_outstanding']!=""){
				   			if($aRow['balance_outstanding']=='0'){
					  			$row[] ='<span style="color:#ff6c60;"><b>'.number_format($aRow['balance_outstanding'],2).'</b></span>';
				   			}else{
				   				$row[] ='<span style="color:#a9d86e;"><b>'.number_format($aRow['balance_outstanding'],2).'</b></span>';
				   			}
				   		}else{
					  		$row[]='0';
				   		}
					}elseif($data['select_order_colum'][$i]=='partner_last_payment_date'){
						if(trim($aRow['partner_last_payment_date'])!="0000-00-00 00:00:00" )
						{
							$row[] = date('Y-m-d',strtotime($aRow['partner_last_payment_date']));	
					  	}else{
							$row[] = 'NA';
						}
					}elseif($data['select_order_colum'][$i]=='first_name'){
						if($aRow['first_name']!=""){
							$row[] = ucwords(strtolower($aRow['first_name']));
					  		//$row[] = '<div style="width:180px;text-align:left;"><a class="link_h" href="javasc" >'.ucwords(strtolower($aRow['first_name'])).'</a></div>'; 	
				   		}else{
					  		$row[] ='-';
				   		}
					}elseif($data['select_order_colum'][$i]=='first_name'){
						if(trim($aRow['first_name'])!='')
						{
							$row[] = '<div style="width:180px;text-align:left;"><a class="link_h" href="'. base_url().'admin/edit-vendor/'.base64_encode($aRow['user_id']).'" >'.ucfirst(strtolower($aRow['first_name'])).' '.ucfirst(strtolower($aRow['last_name'])).'</a></div>';	
						}else{
							$row[] = '-';
						}
					}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
				}else
				{	

					$action = '
					<div class="action_box display-action">
						<a href="javascript:void(0);" class="btn btn-warning" id="pay_now_btn" data-toggle="modal" data-target="#pay_now_modal" data-partner_id="'.base64_encode($aRow['user_id']).'" >
						 <i class="far fa-money-bill-alt"></i>'.$this->lang->line('pay_now_btn').'
						</a>&nbsp;&nbsp;
						
						
					</div>';			
					/*
					$row[]= '
					<div class="action_box display-action">
						<a href="javascript:void(0);" id="edit_category_modal" data-toggle="modal" data-target="#edit_category_mod" data-partner_id="'.base64_encode($aRow['business_id']).'" data-category_id="'.base64_encode($aRow['category_id']).'" >
						  <i class="fa fa-eye"></i>
						</a>
					</div>';

					$row[]= '
					<div class="action_box display-action">
						<a href="javascript:void(0);" id="delete_category_modal" data-toggle="modal" data-target="#delete_category_mod" data-category_id="'.base64_encode($aRow['category_id']).'" >
						  <i class="fa fa-remove"></i>
						</a>
					</div>';	*/
					$row[]=$action;
				}
			}
			$output['aaData'][] = $row;
		}
		
		return $output;
	}
	
	
	public function list_payments_count($where="") {
		/* $query="SELECT * FROM (SELECT au.*, shopPaybleAmount(au.user_id)-CASE WHEN totalShopPaymentAmount(au.user_id) IS NOT NULL  THEN totalShopPaymentAmount(au.user_id) ELSE 0 END as balance_outstanding  FROM admin_users au ". $where .' ) AS SUBQUERY
			WHERE balance_outstanding !=0'; */
		$partner_commission_percent  =  $this->getPartnerCommissionPercent();
		$query="SELECT * FROM (SELECT au.*, partnerPaybleAmount(au.user_id,'.$partner_commission_percent.')-CASE WHEN totalPartnerPaymentAmount(au.user_id) IS NOT NULL  THEN totalPartnerPaymentAmount(au.user_id) ELSE 0 END as balance_outstanding  FROM admin_users au ". $where .' ) AS SUBQUERY
			';
			
		$rResult = $this->db->query($query);
		$result  =  $rResult->num_rows();
		return $result;
	}


	public function allCompletedPaymentsList($data){
		//pr($data,1);
		$user_id    = $this->site_santry->get_auth_data('id');
		$user_type = $this->site_santry->get_auth_data('user_type');
		$software_where  = 'where 1 And ap.delete_status = "0"';
		if($user_type=="partner"){
		    $software_where  .= " AND ap.partner_id = ".$user_id."";
		}
		
		$data['where_coloums'] 	= array('ap.admin_payment_id','au.first_name','ap.shop_bank_account_number','ap.date_of_payment','ap.payment_amount','ap.status');
        $data['select_order_colum'] = array('admin_payment_id','first_name','shop_bank_account_number','date_of_payment','payment_amount','status');
			
		$data['table_name']   		= "admin_payments";
		$data['indexColumn']  		= "ap.admin_payment_id";
		$limit = '';
		if ( isset($data['post']['iDisplayStart'] ) && $data['post']['iDisplayLength'] != '-1' )
		{
			$offset = intval($data['post']['iDisplayStart']);
			$limit = "LIMIT ".intval($data['post']['iDisplayStart']).", ".intval($data['post']['iDisplayLength']); 	
		}
		/* Ordering */
		if(isset($data['post']['iSortCol_0']))
		{
			$order_by = "ORDER BY created DESC  ";
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
				$order_by = "ORDER BY created DESC";
			}
		}
		//echo $order_by; die();
		/*if($order_by == "ORDER BY  category_id
									asc")
		{
			$order_by = "ORDER BY ic.category_id DESC";
		}*/
		//echo $order_by; die;
		$where = "";
		if ($data['post']['sSearch'] != "" )
		{
			$where = " and (";
			for ( $i=0 ; $i<count($data['where_coloums']) ; $i++ )
			{

				$where .= $data['where_coloums'][$i]." LIKE '%".($data['post']['sSearch'] )."%' OR ";
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
				echo $where .= $data['where_coloums'][$i]." LIKE '%".($data['post']['sSearch_'.$i])."%' ";
			}
		}
		
		
		$query = "SELECT ap.*,au.first_name,au.last_name,au.user_id FROM admin_payments ap LEFT JOIN admin_users au ON au.user_id=ap.partner_id ".$software_where.$where." ".$order_by.' '.$limit;
		 //echo $query;
		 //die;     
		$rResult = $this->db->query($query);
		$result  =  $rResult->result_array();
		//print_r($result);die;
		
		$sQuery = "SELECT FOUND_ROWS()";
		$rResultFilterTotal = $this->db->query($sQuery) or die(mysql_error());
		$aResultFilterTotal =$rResultFilterTotal->result_array();
		$display_records = $aResultFilterTotal[0]['FOUND_ROWS()'];
		
		$total_records = $this->list_completed_payments_count($software_where);
		
		
		if($where!=''){
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
			//print_r($aRow);
			$row = array();
			for ( $i=0 ; $i<count($data['select_order_colum']) ; $i++ )
			{
				if ($data['select_order_colum'][$i] != ' ' )
				{
					if($data['select_order_colum'][$i]=='admin_payment_id'){
						if(!isset($aRow['admin_payment_id']))
						{
							$row[] = '-';	
						}else{
							$row[] = $j++;
						}
					}elseif($data['select_order_colum'][$i]=='shopname'){
						if($aRow['shopname']!=""){
					  		$row[] = '<div style="width:180px;text-align:left;"><a class="link_h" href="'. base_url().'admin/edit-vendor/'.base64_encode($aRow['user_id']).'" >'.ucwords(strtolower($aRow['shopname'])).'</a></div>'; 	
				   		}else{
					  		$row[] ='-';
				   		}
					}elseif($data['select_order_colum'][$i]=='first_name'){
						if(trim($aRow['first_name'])!='')
						{
							$row[] = '<div style="width:180px;text-align:left;"><a class="link_h" href="'. base_url().'admin/edit-vendor/'.base64_encode($aRow['user_id']).'" >'.ucfirst(strtolower($aRow['first_name'])).' '.ucfirst(strtolower($aRow['last_name'])).'</a></div>';	
						}else{
							$row[] = '-';
						}
					}else if($data['select_order_colum'][$i] == "date_of_payment"){
				   		if($aRow['date_of_payment']!="0000-00-00"){
				   			if($aRow['last_payment_due_status']=='1'){
					  			$row[] = '<span style="color:orange;">'.date('Y-m-d',strtotime($aRow['date_of_payment'])).'</span>'; 	
					  		}else{
					  			$row[] = date('Y-m-d',strtotime($aRow['date_of_payment']));	
					  		}
				   		}else{
					  		$row[] = '-';
				   		}

				    }else if($data['select_order_colum'][$i] == "next_payment_due_date"){	
				   		if($aRow['next_payment_due_date']!="0000-00-00"){
					  		
					  		$row[] = date('Y-m-d',strtotime($aRow['next_payment_due_date'])); 	
					  			
				   		}else{
					  		$row[] ='-';
				   		}

				    }else if($data['select_order_colum'][$i] == "status"){	
				   		if($aRow['status']!="0"){
					  		
					  		$row[] = "PAID"; 	
					  			
				   		}else{
					  		$row[] ='NOt PAID';
				   		}

				    }elseif($data['select_order_colum'][$i]=='next_payment_due_date'){
						if($aRow['next_payment_due_date']!="0000-00-00"){
					  		$row[] = date('Y-m-d',strtotime($aRow['next_payment_due_date'])); 	
				   		}else{
					  		$row[] ='-';
				   		}
					}else{
						$row[] = $aRow[$data['select_order_colum'][$i]];
					}
				}else
				{	

					$action = '';			
					/*
					$row[]= '
					<div class="action_box display-action">
						<a href="javascript:void(0);" id="edit_category_modal" data-toggle="modal" data-target="#edit_category_mod" data-partner_id="'.base64_encode($aRow['business_id']).'" data-category_id="'.base64_encode($aRow['category_id']).'" >
						  <i class="fa fa-eye"></i>
						</a>
					</div>';

					$row[]= '
					<div class="action_box display-action">
						<a href="javascript:void(0);" id="delete_category_modal" data-toggle="modal" data-target="#delete_category_mod" data-category_id="'.base64_encode($aRow['category_id']).'" >
						  <i class="fa fa-remove"></i>
						</a>
					</div>';	*/
					$row[]=$action;
				}
			}
			$output['aaData'][] = $row;
		}
		
		return $output;
	}

	public function list_completed_payments_count($where=""){
		$query = "SELECT ap.*,au.first_name FROM admin_payments ap LEFT JOIN admin_users au ON au.user_id=ap.partner_id ".$where;
			
		$rResult = $this->db->query($query);
		$result  =  $rResult->num_rows();
		return $result;
	}
}
?>
