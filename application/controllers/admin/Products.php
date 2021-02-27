<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Products extends CI_Controller {

	public function __construct(){

		$data=array();
		parent::__construct();
		if(!$this->site_santry->is_login())
		{
			redirect();
		}

		$this->layout->set_layout("admin/layout/inner");
		$this->load->model("admin/Products_model");
	}
	
	public function index(){
		
		$data['title'] = 'Products';
		$get_hd=1;
		if($this->input->post('get_hidden')){
			$get_hd=0;
		}
		if($this->input->post() && $get_hd == 1)
		{
			$data['product_name'] = $this->session->userdata('product_name');
			$data['post']	=  $this->input->post();
			$allrecord      = $this->Products_model->getAllProducts($data);
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
			$this->layout->view("admin/products/product_list", $data);
		}		
	}	
	
	public function add_product(){	
		$data['title'] = 'Add Product';		
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
				
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				array('field' => 'brand_id', 'label' =>'Choose Brand', 'rules' => 'trim|required'),
				array('field' => 'category_id', 'label' =>'Choose Category', 'rules' => 'trim|required'),
				//array('field' => 'subcategory_id', 'label' =>'Choose Sub Category', 'rules' => 'trim|required'),
				array('field' => 'product_name_en', 'label' =>'Product Name in English', 'rules' => 'trim|required'),
				array('field' => 'product_name_gr', 'label' =>'Product Name in German', 'rules' => 'trim|required'),
				array('field' => 'product_name_tr', 'label' =>'Product Name in Turkish', 'rules' => 'trim|required'),
				//array('field' => 'single_product_price', 'label' =>'Price Single Product', 'rules' => 'trim|required'),
				//array('field' => 'master_carton_price', 'label' =>'Price Master Carton', 'rules' => 'trim|required'),
				//array('field' => 'palette_price', 'label' =>'Price Palette', 'rules' => 'trim|required')		
		    );

		    if($post['single_product_price'] > 0 || $post['master_carton_price'] > 0 || $post['palette_price'] > 0)
		    {
			    $this->load->library('form_validation');
				$this->form_validation->set_rules($validation_post);
				if ($this->form_validation->run() === TRUE) {
					$alredyExits = $this->commonmodel->_get_data_row('product_name','tbl_products',array('product_name' => $post['product_name_en']));
					if(!$alredyExits){
						if(isset($_FILES) && $_FILES['product_image']['name'] != "") {
							 $upload_data = $this->do_upload_product($referrer,'product_image');
							 $post['product_image'] = $upload_data['upload_data']['file_name'];
					 	}
					 	$addrecord = $this->Products_model->addProduct($post);
						if($addrecord) {
							$this->session->set_flashdata('flashSuccess','Product Has Been Added Successfully');
							redirect('admin/products');
						}else{
							$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
							redirect($referrer);
						}

					}else {
						$this->session->set_flashdata('flashError','Category Name Already Exits....');
						redirect($referrer);
					}					
				}
			}else {
				$this->session->set_flashdata('flashError','At least One type of price is required');
				redirect($referrer);
			}	
		}	
		$data['allBrands'] = $this->Products_model->brand_dropdown_list();
		$data['allcategory'] = $this->Products_model->categories_dropdown_list();
		$data['allsubcategory'] = array(''=>'-- Select Sub Category --');
		$this->layout->view("admin/products/add_product", $data);
	}

	public function edit_product($product_id){
		$data['title'] = 'Edit Product';
		$product_id = base64_decode($product_id);
			
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();	
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				array('field' => 'brand_id', 'label' =>'Choose Brand', 'rules' => 'trim|required'),
				array('field' => 'category_id', 'label' =>'Choose Category', 'rules' => 'trim|required'),
				//array('field' => 'subcategory_id', 'label' =>'Choose Sub Category', 'rules' => 'trim|required'),
				array('field' => 'product_name', 'label' =>'Product Name in English', 'rules' => 'trim|required'),
				array('field' => 'product_name_gr', 'label' =>'Product Name in German', 'rules' => 'trim|required'),
				array('field' => 'product_name_tr', 'label' =>'Product Name in Turkish', 'rules' => 'trim|required'),
				//array('field' => 'single_product_price', 'label' =>'Price Single Product', 'rules' => 'trim|required'),
				//array('field' => 'master_carton_price', 'label' =>'Price Master Carton', 'rules' => 'trim|required'),
				//array('field' => 'palette_price', 'label' =>'Price Palette', 'rules' => 'trim|required')
		    );

			if($post['single_product_price'] > 0 || $post['master_carton_price'] > 0 || $post['palette_price'] > 0)
		    {
			    $this->load->library('form_validation');
				$this->form_validation->set_rules($validation_post);
				if ($this->form_validation->run() === TRUE) {
				
					$alredyExits = $this->commonmodel->_get_data_row('product_name','tbl_products',array('product_name' => $post['product_name'],'product_id != '=>$product_id,'delete_status'=>'0'));
					if(!$alredyExits){
						if(isset($_FILES) && $_FILES['product_image']['name'] != "") {
							 $upload_data = $this->do_upload_product($referrer,'product_image');
							 $post['product_image'] = $upload_data['upload_data']['file_name'];
					 	}
						$updaterecord = $this->Products_model->updateProducts($post,$product_id);
						if($updaterecord) {
							$this->session->set_flashdata('flashSuccess','Product Has Been Updated Successfully');
							redirect('admin/products');
						}else{
							$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
							redirect($referrer);
						}
					}
					else {
						$this->session->set_flashdata('flashError','Product Name Already Exits');
							redirect($referrer);
					}
				}
			}else {
				$this->session->set_flashdata('flashError','At least One type of price is required');
				redirect($referrer);
			}
		}
		$data['allBrands'] = $this->Products_model->brand_dropdown_list();
		$data['allcategory'] = $this->Products_model->categories_dropdown_list();
		$data['allsubcategory'] = array(''=>'-- Select Sub Category --');
		$data['product_details']=$this->Products_model->getProductDetailsById($product_id);	
		
		$this->layout->view("admin/products/edit_product", $data);
	}

	public function deleteProduct($product_id){
		$table='tbl_products';
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
	}

	public function get_product_discount(){
			
		$product_id = $this->input->post('product_id');	
		$data['allProductDiscount'] = $this->Products_model->product_discount_list($product_id);
		$data['product_details'] = $this->Products_model->getProductDetailsById($product_id);

		$this->layout->set_layout("admin/layout/middle");
		$this->layout->view("admin/products/discount_details", $data);
	}

	public function add_product_discount(){	
		   
		if($this->input->post()){
			$post = $this->input->post();			
			$validation_post = array(
				array('field' => 'price_type', 'label' =>'Choose Price Type', 'rules' => 'trim|required'),
				array('field' => 'quantity', 'label' =>'Product Quantity', 'rules' => 'trim|required'),
				array('field' => 'discount_price', 'label' =>'Product Discount', 'rules' => 'trim|required')	
		    );
		    $this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) {
				$alredyExits = $this->commonmodel->_get_data_row('id','tbl_product_discount',array('delete_status' => 0 , 'product_id' => $post['product_id'] , 'price_type' => $post['price_type'] , 'quantity' => $post['quantity']));
				if(!$alredyExits){
					
					$addrecord = $this->Products_model->addProductDiscount($post);
					if($addrecord) {
						$this->session->set_flashdata('flashSuccess','Product Discount Has Been Added Successfully');
					}else{
						$this->session->set_flashdata('flashError','An Error Occured.Please Try Again Later.');
					}

				}else {
					$this->session->set_flashdata('flashError','Product Discount Already Exits....');
				}
				
			}else {
				$this->session->set_flashdata('flashError','All fields are mandatory....');
			}
		}

		$data['allProductDiscount'] = $this->Products_model->product_discount_list($post['product_id']);
		$data['product_details'] = $this->Products_model->getProductDetailsById($post['product_id']);
		$this->layout->set_layout("admin/layout/middle");
		$this->layout->view("admin/products/discount_details", $data);		
	}

	public function delete_product_discount(){
		
		if($this->input->post()){

			$post = $this->input->post();

			$table='tbl_product_discount';
			$id=base64_decode($post['discount_id']);

			$prd_dis_details = $this->Products_model->product_discount_details($id);
			
			$condition		= array("id"=>$id);
			$detail = array('delete_status' => '1');
			$deletedType=$this->commonmodel->_update($table,$detail,$condition);
			if($deletedType){
				$this->session->set_flashdata('flashSuccess','Product Discount Has Been Deleted Successfully');
			}else{
				$this->session->set_flashdata('flashError','Something Discount Went wrong ! please try again later');
			}	

			$data['allProductDiscount'] = $this->Products_model->product_discount_list($prd_dis_details['product_id']);
			$data['product_details'] = $this->Products_model->getProductDetailsById($prd_dis_details['product_id']);
			$this->layout->set_layout("admin/layout/middle");
			$this->layout->view("admin/products/discount_details", $data);
		}

		print "";
	}

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
		$product_id_encode   		= $this->uri->segment(4);
		$product_id		  		= base64_decode($product_id_encode);
		$activation_status	= base64_decode($this->uri->segment(5));
		
		$change_activation_status ='0';
		if($activation_status=='0'){
			$change_activation_status	=	'1';
		}
		if($activation_status=='1'){
			$change_activation_status	=	'0';
		}
		
		$condition = array('product_id' => $product_id);
		$this->commonmodel->_update('tbl_products',array('status'=>$change_activation_status),$condition);
		$this->session->set_flashdata('flashSuccess','Status Has Been Updated Successfully');
		redirect('admin/products');
	}

}
