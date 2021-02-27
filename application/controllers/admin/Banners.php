<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banners extends CI_Controller {

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
		$this->load->model("admin/banners_model");
	}
	
	public function index(){
		
		$data['title'] = 'Banners List';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['post']	=  $this->input->post();
			$allrecord      = $this->banners_model->getAllBanners($data);
			echo json_encode($allrecord);
		}else {
			
			$this->layout->view("admin/banners/banners_list", $data);
		}		
	}
	
	public function add_banner(){
		
		$data['title'] = 'Add Banner';
		/* $product_id_encode   = $this->uri->segment(4);
		$product_id		  = base64_decode($product_id_encode); */
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post())
		{
			$post = $this->input->post();
			if(isset($_FILES) && $_FILES['image']['name'] != "")
			{
				$temp_arr = array();
				$upload_data = $this->do_upload($referrer);
				$temp_arr['heading'] = $post['heading'];
				$temp_arr['heading_gr'] = $post['heading_gr'];
				$temp_arr['heading_tr'] = $post['heading_tr'];
				$temp_arr['sub_heading'] = $post['sub_heading'];
				$temp_arr['sub_heading_gr'] = $post['sub_heading_gr'];
				$temp_arr['sub_heading_tr'] = $post['sub_heading_tr'];
				$temp_arr['banner_image'] = $upload_data['upload_data']['file_name'];
				$temp_arr['created_on'] = date('Y-m-d H:i:s');
				$temp_arr['modified_on'] = date('Y-m-d H:i:s');
				if($this->commonmodel->_insert('tbl_banners',$temp_arr))
				{
					$this->session->set_flashdata('flashSuccess','Banner Has Been Added Successfully.');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}else{
				$this->session->set_flashdata('flashError','Please select banner image first!');
			}
			redirect($referrer);
		}
		
		$data['referer'] = $referrer;
		$this->layout->view("admin/banners/add_banner", $data);
	}
	
	public function edit_banner($banner_id_encode){
		
		$data['title'] = 'Edit Banner';
		$product_id_encode   = $this->uri->segment(3);
		$banner_id		  = base64_decode($banner_id_encode); 
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post())
		{
			$post = $this->input->post();
			if(isset($_FILES) && $_FILES['image']['name'] != "")
			{
				$temp_arr = array();
				$upload_data = $this->do_upload($referrer);
				$temp_arr['heading'] = $post['heading'];
				$temp_arr['heading_gr'] = $post['heading_gr'];
				$temp_arr['heading_tr'] = $post['heading_tr'];
				$temp_arr['sub_heading'] = $post['sub_heading'];
				$temp_arr['sub_heading_gr'] = $post['sub_heading_gr'];
				$temp_arr['sub_heading_tr'] = $post['sub_heading_tr'];
				$temp_arr['banner_image'] = $upload_data['upload_data']['file_name'];
				$temp_arr['modified_on'] = date('Y-m-d H:i:s');
				if($this->commonmodel->_update('tbl_banners',$temp_arr,array('banner_id'=>$banner_id)))
				{
					$this->session->set_flashdata('flashSuccess','Banner Has Been Added Successfully.');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}else{
				$temp_arr['heading'] = $post['heading'];
				$temp_arr['heading_gr'] = $post['heading_gr'];
				$temp_arr['heading_tr'] = $post['heading_tr'];
				$temp_arr['sub_heading'] = $post['sub_heading'];
				$temp_arr['sub_heading_gr'] = $post['sub_heading_gr'];
				$temp_arr['sub_heading_tr'] = $post['sub_heading_tr'];
				$temp_arr['modified_on'] = date('Y-m-d H:i:s');
				if($this->commonmodel->_update('tbl_banners',$temp_arr,array('banner_id'=>$banner_id)))
				{
					$this->session->set_flashdata('flashSuccess','Banner Has Been Added Successfully.');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}
			redirect($referrer);
		}
		
		//$data['detail'] = $details;
		$data['bannerImageDetails'] = $this->banners_model->getBnnerDetails($banner_id);
		$data['referer'] = $referrer;
		//pr($data,1);
		$this->layout->view("admin/banners/edit_banner", $data);
	}
	
	public function delete_banner($id){
		
		$table='tbl_banners';
		$id=base64_decode($id);
		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		$condition = array('banner_id' => $id);
		$rows = $this->commonmodel->_get_data_row('',$table,$condition);
		if($rows<1)
		{
			$this->session->set_flashdata('flashError','Please select valid entry.');
			redirect($referrer);
			exit;
		}
		
		$data = array('delete_status'=>'1');
		$this->commonmodel->_update($table, $data, $condition);
		$this->session->set_flashdata('flashSuccess','Banner Has Been Deleted Successfully');
		redirect($referrer);
	}
	
	public function do_upload($referrer)
	{
		$random_key = $this->random_key(10);
		$image_name =date("Ymdhis").$random_key.'.png';
		
		$config['file_name']         	= $image_name;
		$config['upload_path']          = './assets/uploads/banners';
		$config['allowed_types']        = 'gif|jpg|jpeg|png';
		$config['max_size']             = 2048;
		$config['max_width']            = 1925;
		$config['max_height']           = 1085;
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
	
	public function edit_middle_banner(){
		
		$data['title'] = 'Edit Middle Banner';		
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		
		if($this->input->post())
		{
			if(isset($_FILES) && $_FILES['image']['name'] != "")
			{
				$temp_arr = array();
				$upload_data = $this->do_upload($referrer);
				$temp_arr['banner_image'] = $upload_data['upload_data']['file_name'];
				$temp_arr['modified_on'] = date('Y-m-d H:i:s');
				if($this->commonmodel->_update('tbl_middle_banner',$temp_arr,array('banner_id'=>1)))
				{
					$this->session->set_flashdata('flashSuccess','Banner Has Been Added Successfully.');
				}else{
					$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
				}
			}else{
				$this->session->set_flashdata('flashError','Please select banner image first!');
			}
			redirect($referrer);
		}

		$data['bannerImageDetails'] = $this->banners_model->getMiddleBnnerDetails();
		$data['referer'] = $referrer;
		//pr($data,1);
		$this->layout->view("admin/banners/edit_middle_banner", $data);
	}
}
