<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}

		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/Order_model");
	}
	
	public function index(){
		
		$data['title'] = 'Orders';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['product_name'] = $this->session->userdata('product_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->Order_model->getAllOrders($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['product_name'] = $this->input->post('product_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('product_name')!=''){
				$filter_session_data['product_name'] = $this->input->post('product_name');
			}else{
					$filter_session_data['product_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/orders/order_list", $data);
		}		
	}

	public function get_order_details($order_id){
		
		$data['title'] = 'Orders';
		$data['order_id'] = $order_id;
		$order_id=base64_decode($order_id);

		$data['title'] = 'Orders';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['product_name'] = $this->session->userdata('product_name');
			$data['post']	=  $this->input->post();
			$data['order_id']	=  $order_id;
			$allrecord      = $this->Order_model->getOrderDetailsById($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['product_name'] = $this->input->post('product_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('product_name')!=''){
				$filter_session_data['product_name'] = $this->input->post('product_name');
			}else{
					$filter_session_data['product_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/orders/order_details", $data);
		}
		
	}

	/*public function deleteProduct($product_id){
		$table='tbl_orders';
		$product_id=base64_decode($product_id);
		
		$condition		= array("product_id"=>$product_id);
		$detail = array('delete_status'		=> 	 '1');
		$deletedType=$this->commonmodel->_update($table,$detail,$condition);
		if($deletedType){
			$this->session->set_flashdata('flashSuccess','Product Has Been Deleted Successfully');
			redirect('admin/products');
		}else{
			$this->session->set_flashdata('flashError','Something Went wrong ! please try again later');
			redirect($referrer);
		}	
	}*/

/*------------ products End ------------------- */




	
	public function do_upload_product($referrer,$submited_name)
	{
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		$random_key = $this->random_key(10);

		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = '/mnt/web504/b3/67/58339167/htdocs/'.'assets/uploads/'.'products/';
		if(!is_dir($folderPath)) {
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = 'png|jpeg|jpg';
		$config['max_size']             = 2014; // 1 mb
		/* $config['max_width']            = 50;
		$config['max_height']           = 50;
		$config['min_width']            = 50;
		$config['min_height']           = 50; */
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if(!$this->upload->do_upload($submited_name)) {
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}

	public function do_upload($referrer,$submited_name) {
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'products/';
		if(!is_dir($folderPath)) {
			mkdir($folderPath,777,true);
		}
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = $folderPath;
		$config['allowed_types']        = '*';
		//$config['max_size']             = 1024;
		//$config['max_width']            = 1024;
		//$config['max_height']           = 768;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload($submited_name)) {
			$this->session->set_flashdata('flashError',$this->upload->display_errors());
			redirect($referrer);
		}else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
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
	
	public function change_activation_status(){
		$order_id_encode   		= $this->uri->segment(4);
		$order_id		  		= base64_decode($order_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		
		if($activation_status=='2')
		{
			$statusTitle = 'Ongoing';
		}elseif($activation_status=='3')
		{
			$statusTitle = 'Delivered';
		}else{
			$statusTitle = 'Canceled';
		}

		$condition = array('order_id' => $order_id);
		$this->commonmodel->_update('tbl_orders',array('status'=>$activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Order status Successfully changed to '.$statusTitle);
		redirect('admin/orders');
	}

}
