<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}

		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/categories_model");
	}
	
	public function index(){
		
		$data['title'] = 'tbl_categories';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['category_name'] = $this->session->userdata('category_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->categories_model->getAllCategories($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['category_name'] = $this->input->post('category_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('category_name')!=''){
				$filter_session_data['category_name'] = $this->input->post('category_name');
			}else{
					$filter_session_data['category_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/categories/categories_list", $data);
		}		
	}	
	
	public function add_category(){	
		$data['title'] = 'Add Category';		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
				
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'category_name_en', 'label' =>'Category Name in English', 'rules' => 'trim|required'),
				  array('field' => 'category_name_gr', 'label' =>'Category Name in German', 'rules' => 'trim|required'),
				  array('field' => 'category_name_tr', 'label' =>'Category Name in Turkish', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				$alredyExits = $this->commonmodel->_get_data_row('category_name','tbl_categories',array('category_name' => $post['category_name_en']));
				if(!$alredyExits){
					if(isset($_FILES) && $_FILES['category_image']['name'] != "") {
						 $upload_data = $this->do_upload_category($referrer,'category_image');
						 $post['category_image'] = $upload_data['upload_data']['file_name'];
				 	}
				 	$addrecord = $this->categories_model->addCategories($post);
					if($addrecord) {
						$this->session->set_flashdata('flashSuccess','Category Has Been Added Successfully');
						redirect('admin/categories');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
						redirect($referrer);
					}

				}else {
					$this->session->set_flashdata('flashError','Category Name Already Exits....');
					redirect($referrer);
				}
				
			}
		}	
		$this->layout->view("admin/categories/add_category", $data);
	}

	public function edit_category($category_id){
		$data['title'] = 'Edit Sub Category';
		$category_id		  = base64_decode($category_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'category_name_en', 'label' =>'Category Name in English', 'rules' => 'trim|required'),
				  array('field' => 'category_name_gr', 'label' =>'Category Name in German', 'rules' => 'trim|required'),
				  array('field' => 'category_name_tr', 'label' =>'Category Name in Turkish', 'rules' => 'trim|required'),
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				
					$alredyExits = $this->commonmodel->_get_data_row('category_name','tbl_categories',array('category_name' => $post['category_name_en'],'category_id != '=>$category_id,'delete_status'=>'0'));
					if(!$alredyExits){
						if(isset($_FILES) && $_FILES['category_image']['name'] != "") {
							 $upload_data = $this->do_upload_category($referrer,'category_image');
							 $post['category_image'] = $upload_data['upload_data']['file_name'];
					 	}
						$updaterecord = $this->categories_model->updateCategories($post,$category_id);
						if($updaterecord) {
							$this->session->set_flashdata('flashSuccess','Category Has Been Updated Successfully');
							redirect('admin/categories');
						}else{
							$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
							redirect($referrer);
						}
					}
					else {
						$this->session->set_flashdata('flashError','Category Name Already Exits');
							redirect($referrer);
					}
			}
		}
		$data['category_details']=$this->categories_model->getCategoryDetailsById($category_id);	
		$this->layout->view("admin/categories/edit_category", $data);
	}

	public function deleteCategories($category_id){
		$table='tbl_categories';
		$category_id=base64_decode($category_id);
		
		$condition		= array("category_id"=>$category_id);
		$detail = array('delete_status'		=> 	 '1');
		$deletedType=$this->commonmodel->_update($table,$detail,$condition);
		if($deletedType){
			$this->session->set_flashdata('flashSuccess','Category Has Been Deleted Successfully');
			redirect('admin/categories');
		}else{
			$this->session->set_flashdata('flashError','Something Went wrong ! please try again later');
			redirect($referrer);
		}	
	}

/*------------ Category End ------------------- */



/*------------ Sub Category ------------------- */

	public function subCategories(){
		
		$data['title'] = 'Sub Categories';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['sub_category_name'] = $this->session->userdata('sub_category_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->categories_model->getAllSubCategories($data);
			echo json_encode($allrecord);
		}else {	
			$filter_session_data = array();
			$data['sub_category_name'] = $this->input->post('sub_category_name');
			//pr($data['appointment_dropdown'],1); 
			if($this->input->post('sub_category_name')!=''){
				$filter_session_data['sub_category_name'] = $this->input->post('sub_category_name');
			}else{
					$filter_session_data['sub_category_name'] = '';
			}
			if(!empty($filter_session_data)){
				$this->session->set_userdata($filter_session_data);    
			}
			$this->layout->view("admin/categories/subcategories_list", $data);
		}		
	}

	public function edit_subcategory($sub_category_id){
		$data['title'] = 'Edit Sub Category';
		$sub_category_id		  = base64_decode($sub_category_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
			  array('field' => 'category_id', 'label' =>'Csoose Category', 'rules' => 'trim|required'),
			  array('field' => 'subcategory_name_en', 'label' =>'Category Name in English', 'rules' => 'trim|required'),
			  array('field' => 'subcategory_name_gr', 'label' =>'Category Name in German', 'rules' => 'trim|required'),
			  array('field' => 'subcategory_name_tr', 'label' =>'Category Name in Turkish', 'rules' => 'trim|required'),
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				
				$alredyExits = $this->commonmodel->_get_data_row('subcategory_name','tbl_sub_categories',array('subcategory_name' => $post['subcategory_name_en'],'category_id = '=>$post['category_id'],'sub_category_id != '=>$sub_category_id,'delete_status'=>'0'));
				if(!$alredyExits){
					if(isset($_FILES) && $_FILES['subcategory_image']['name'] != "") {
						 $upload_data = $this->do_upload_category($referrer,'subcategory_image');
						 $post['subcategory_image'] = $upload_data['upload_data']['file_name'];
				 	}
					$updaterecord = $this->categories_model->updateSubCategories($post,$sub_category_id);
					if($updaterecord) {
						$this->session->set_flashdata('flashSuccess','Sub Category Has Been Updated Successfully');
						redirect('admin/subcategories');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
						redirect($referrer);
					}
				}
				else {
					$this->session->set_flashdata('flashError','Sub Category Name Already Exits');
						redirect($referrer);
				}
			}
		}

		$data['allcategory'] = $this->categories_model->categories_dropdown_list();
		$data['subcategory_details']=$this->categories_model->getSubCategoryDetailsById($sub_category_id);	
		$this->layout->view("admin/categories/edit_subcategory", $data);
	}

	public function add_subcategory(){	
		$data['title'] = 'Add Category';		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
				
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				  array('field' => 'category_id', 'label' =>'Choose Category', 'rules' => 'trim|required'),
				  array('field' => 'subcategory_name_en', 'label' =>'Category Name in English', 'rules' => 'trim|required'),
				  array('field' => 'subcategory_name_gr', 'label' =>'Category Name in German', 'rules' => 'trim|required'),
				  array('field' => 'subcategory_name_tr', 'label' =>'Category Name in Turkish', 'rules' => 'trim|required'),
				  
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				$alredyExits = $this->commonmodel->_get_data_row('subcategory_name','tbl_sub_categories',array('subcategory_name' => $post['subcategory_name_en'],'category_id = '=>$post['category_id'],'delete_status'=>'0'));
				if(!$alredyExits){
					if(isset($_FILES) && $_FILES['subcategory_image']['name'] != "") {
						 $upload_data = $this->do_upload_category($referrer,'subcategory_image');
						 $post['subcategory_image'] = $upload_data['upload_data']['file_name'];
				 	}
				 	$addrecord = $this->categories_model->addSubCategories($post);
					if($addrecord) {
						$this->session->set_flashdata('flashSuccess','Sub Category Has Been Added Successfully');
						redirect('admin/subcategories');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
						redirect($referrer);
					}

				}else {
					$this->session->set_flashdata('flashError','Category Name Already Exits....');
					redirect($referrer);
				}
				
			}
		}	

		$data['allcategory'] = $this->categories_model->categories_dropdown_list();
		$this->layout->view("admin/categories/add_subcategory", $data);
	}

	public function deleteSubcategory($sub_category_id){
		$table='tbl_sub_categories';
		$sub_category_id=base64_decode($sub_category_id);
		
		$condition		= array("sub_category_id"=>$sub_category_id);
		$detail = array('delete_status'		=> 	 '1');
		$deletedType=$this->commonmodel->_update($table,$detail,$condition);
		if($deletedType){
			$this->session->set_flashdata('flashSuccess','Category Has Been Deleted Successfully');
			redirect('admin/subcategories');
		}else{
			$this->session->set_flashdata('flashError','Something Went wrong ! please try again later');
			redirect($referrer);
		}	
	}


/*------------ Sub Category End ------------------- */

	
	public function do_upload_category($referrer,$submited_name)
	{
		$filename = $_FILES[$submited_name]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		$random_key = $this->random_key(10);

		$image_name =date("Ymdhis").$random_key.$ext;
		$folderPath = UPLOAD_PHYSICAL_PATH.'categories/';
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
		$folderPath = UPLOAD_PHYSICAL_PATH.'categories/';
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
		$category_id_encode   		= $this->uri->segment(4);
		$category_id		  		= base64_decode($category_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('category_id' => $category_id);
		$this->commonmodel->_update('tbl_categories',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect('admin/categories');
	}

	public function change_subcat_activation_status(){
		$sub_category_id_encode   		= $this->uri->segment(4);
		$sub_category_id		  		= base64_decode($sub_category_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('sub_category_id' => $sub_category_id);
		$this->commonmodel->_update('tbl_sub_categories',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect('admin/subcategories');
	}

	public function get_sub_category_list() {

		if($this->input->post())
		{
			$post = $this->input->post();

			$sub_category = $this->categories_model->get_sub_categories_list($post['category_id']);

			$selected_id = isset($post['selected_id']) ? $post['selected_id'] : 0;

			$return_res = '<option value="">-- Select Sub Category --</option>';
			foreach ($sub_category as $category_id => $value)
		    {
		        if($category_id == $selected_id)
		            $return_res .= '<option selected value="'.$category_id.'">'.$sub_category[$category_id].'</option>';
		        else
		            $return_res .= '<option value="'.$category_id.'">'.$sub_category[$category_id].'</option>';       
		    }
		    echo $return_res;
		}
	}
	

}
