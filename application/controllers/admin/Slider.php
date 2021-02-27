<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slider extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}		
		$login_key = $this->site_santry->get_auth_data('login_key');
		$user_id    = $this->site_santry->get_auth_data('id');
		$lg_user = get_user($user_id);
		if(!empty($lg_user['login_key'])){
		    if($login_key!=$lg_user['login_key']){
				redirect('admin/welcome/logout'); 
			}
		}else{
			 redirect('admin/welcome/logout');
		}
		/* $user_type = $this->site_santry->get_auth_data('user_type');
		if($user_type=='agent' || $user_type=='customer'){
			redirect('welcome');
		} */
		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/slider_model");
	}
	
	public function index(){
		
		$data['title'] = 'Slider List';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['post']	=  $this->input->post();
			$allrecord      = $this->slider_model->getAllSliders($data);
			echo json_encode($allrecord);
		}else {
			
			$this->layout->view("admin/slider/slider_list", $data);
		}		
	}
	
	public function add_slider(){
		
		$data['title'] = 'Add Slider';
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post())
		{
			if(isset($_FILES) && $_FILES['image']['name'] != "")
			{
				$temp_arr = array();
				$upload_data = $this->do_upload($referrer);
				$temp_arr['slider_image'] = $upload_data['upload_data']['file_name'];
				$temp_arr['created_on'] = date('Y-m-d H:i:s');
				$temp_arr['modified_on'] = date('Y-m-d H:i:s');
				if($this->commonmodel->_insert('tbl_slider',$temp_arr))
				{
					$this->session->set_flashdata('flashSuccess','Slider Has Been Added Successfully.');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}else{
				$this->session->set_flashdata('flashError','Please select slider image first!');
			}
			redirect($referrer);
		}
		
		$data['referer'] = $referrer;
		$this->layout->view("admin/slider/add_slider", $data);
	}
	
	public function edit_slider($slider_id_encode){
		
		$data['title'] = 'Edit Slider';
		$product_id_encode   = $this->uri->segment(3);
		$slider_id		  = base64_decode($slider_id_encode); 
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post())
		{
			if(isset($_FILES) && $_FILES['image']['name'] != "")
			{
				$temp_arr = array();
				$upload_data = $this->do_upload($referrer);
				$temp_arr['slider_image'] = $upload_data['upload_data']['file_name'];
				$temp_arr['modified_on'] = date('Y-m-d H:i:s');
				if($this->commonmodel->_update('tbl_slider',$temp_arr,array('slider_id'=>$slider_id)))
				{
					$this->session->set_flashdata('flashSuccess','Slider Has Been Added Successfully.');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}else{
				$this->session->set_flashdata('flashError','Please select slider image first!');
			}
			redirect($referrer);
		}
		
		//$data['detail'] = $details;
		$data['sliderImageDetails'] = $this->slider_model->getSliderDetails($slider_id);
		$data['referer'] = $referrer;
		//pr($data,1);
		$this->layout->view("admin/slider/edit_slider", $data);
	}
	
	public function delete_slider($id){
		
		$table='tbl_slider';
		$id=base64_decode($id);
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$condition = array('slider_id' => $id);
		$rows = $this->commonmodel->_get_data_row('',$table,$condition);
		if($rows<1)
		{
			$this->session->set_flashdata('flashError','Please select valid entry.');
			redirect($referrer);
			exit;
		}
		
		$data = array('delete_status'=>'1');
		$this->commonmodel->_update($table, $data, $condition);
		$this->session->set_flashdata('flashSuccess','Slider Has Been Deleted Successfully');
		redirect($referrer);
	}
	
	public function do_upload($referrer)
	{
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.'.png';
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = './assets/uploads/slider';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 2048;
		$config['max_width']            = 500;
		$config['max_height']           = 700;
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload('image'))
		{
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
		$b_id_encode   		= $this->uri->segment(4);
		$b_id		  		= base64_decode($b_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('b_id' => $b_id);
		$this->commonmodel->_update('tbl_slider',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect('admin/products');
	}
}
