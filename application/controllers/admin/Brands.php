<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);

class Brands extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}

		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/brands_model");
	}
	
	public function index(){
		
		$data['title'] = 'tbl_brands';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['brand_name'] = $this->session->userdata('brand_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->brands_model->getAllbrands($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['brand_name'] = $this->input->post('brand_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('brand_name')!=''){
				$filter_session_data['brand_name'] = $this->input->post('brand_name');
			}else{
					$filter_session_data['brand_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/brands/brands_list", $data);
		}		
	}	
	
	public function add_brand (){

		$data['title'] = 'Add Brand';		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();

		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'brand_name_en', 'label' =>'Brand Name in English', 'rules' => 'trim|required'),
				  array('field' => 'brand_name_gr', 'label' =>'Brand Name in German', 'rules' => 'trim|required'),
				  array('field' => 'brand_name_tr', 'label' =>'Brand Name in Turkish', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				$alredyExits = $this->commonmodel->_get_data_row('brand_name','tbl_brands',array('brand_name' => $post['brand_name_en']));
				if(!$alredyExits){
					if(isset($_FILES) && $_FILES['brand_image']['name'] != "") {
						 $upload_data = $this->do_upload_brand($referrer,'brand_image');
						 $post['brand_image'] = $upload_data['upload_data']['file_name'];
				 	}
				 	$addrecord = $this->brands_model->addbrands($post);
					if($addrecord) {
						$this->session->set_flashdata('flashSuccess','Brand Has Been Added Successfully');
						redirect('admin/brands');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
						redirect($referrer);
					}

				}else {
					$this->session->set_flashdata('flashError','Brand Name Already Exits....');
					redirect($referrer);
				}
				
			}
		}	
		$this->layout->view("admin/brands/add_brand", $data);
	}

	public function edit_brand ($brand_id){
		$data['title'] = 'Edit Brand';
		$brand_id		  = base64_decode($brand_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'brand_name_en', 'label' =>'Brand Name in English', 'rules' => 'trim|required'),
				  array('field' => 'brand_name_gr', 'label' =>'Brand Name in German', 'rules' => 'trim|required'),
				  array('field' => 'brand_name_tr', 'label' =>'Brand Name in Turkish', 'rules' => 'trim|required'),
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				
					$alredyExits = $this->commonmodel->_get_data_row('brand_name','tbl_brands',array('brand_name' => $post['brand_name'],'brand_id != '=>$brand_id,'delete_status'=>'0'));
					if(!$alredyExits){
						if(isset($_FILES) && $_FILES['brand_image']['name'] != "") {
							 $upload_data = $this->do_upload_brand($referrer,'brand_image');
							 $post['brand_image'] = $upload_data['upload_data']['file_name'];
					 	}
						$updaterecord = $this->brands_model->updatebrands($post,$brand_id);
						if($updaterecord) {
							$this->session->set_flashdata('flashSuccess','Brand Has Been Updated Successfully');
							redirect('admin/brands');
						}else{
							$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
							redirect($referrer);
						}
					}
					else {
						$this->session->set_flashdata('flashError','Brand Name Already Exits');
							redirect($referrer);
					}
			}
		}
		$data['brand_details']=$this->brands_model->getBrandDetailsById($brand_id);	
		$this->layout->view("admin/brands/edit_brand", $data);
	}

	public function deleteBrands($brand_id){
		$table='tbl_brands';
		$brand_id=base64_decode($brand_id);
		
		$condition		= array("brand_id"=>$brand_id);
		$detail = array('delete_status'		=> 	 '1');
		$deletedType=$this->commonmodel->_update($table,$detail,$condition);
		if($deletedType){
			$this->session->set_flashdata('flashSuccess','Brand Has Been Deleted Successfully');
			redirect('admin/brands');
		}else{
			$this->session->set_flashdata('flashError','Something Went wrong ! please try again later');
			redirect($referrer);
		}	
	}

/*------------ Brand End ------------------- */


	
	public function do_upload_brand($referrer,$submited_name)
	{
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		$random_key = $this->random_key(10);

		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'brands/';
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
		$folderPath = UPLOAD_PHYSICAL_PATH.'brands/';
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
		$brand_id_encode   		= $this->uri->segment(4);
		$brand_id		  		= base64_decode($brand_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('brand_id' => $brand_id);
		$this->commonmodel->_update('tbl_brands',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect('admin/brands');
	}


}
