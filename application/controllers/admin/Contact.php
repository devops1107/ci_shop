<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}

		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/Contact_model");
	}
	
	public function index(){
		
		$data['title'] = 'contact';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['product_name'] = $this->session->userdata('product_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->Contact_model->getAllContacts($data);
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
			$this->layout->view("admin/contact/contact_list", $data);
		}		
	}

		
	
	public function change_activation_status(){
		$contact_id_encode   		= $this->uri->segment(4);
		$contact_id		  		= base64_decode($contact_id_encode);
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

		$condition = array('contact_id' => $contact_id);
		$this->commonmodel->_update('tbl_contact_us',array('status'=>$activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','contact status Successfully changed to '.$statusTitle);
		redirect('admin/contact_list');
	}

}
