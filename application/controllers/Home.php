<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Home extends CI_Controller {
	public $language ="";
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	//private $language ="";
	public function __construct() {
		parent::__construct();
		
		$language = get_site_language();
		$this->language = $language;
		$this->lang->load('all_web_content_lang',$language);
		
		$this->layout->set_layout("web/layout/main");
		$this->load->model('web/Website_model');
		
	}

	public function index(){

		$data['title'] = "Alibaba-nuts.online - Home";
		$language = get_site_language();
		//pr($this->language,1);
		$data['slider_image'] = $this->Website_model->get_slider_image();
		$data['banner_image'] = $this->Website_model->get_banner_image($language);
		$data['middle_banner'] = $this->Website_model->get_middle_banner();

		$data['product_brands'] = $this->Website_model->get_product_brands($language);
		$data['product_categories'] = $this->Website_model->get_product_categories($language);
		$data['recent_products'] = $this->Website_model->get_recent_products($language);
		$data['offer_products'] = $this->Website_model->get_offer_products($language);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		$data['top_products'] = $this->Website_model->get_top_products($language);
		//pr($data['recent_products'],1);
        $this->layout->view('web/index',$data);
    }

	public function get_product_detail($product_id){
		$data['title'] = "Baaba.de - Product Details";
		$prod_id = base64_decode($product_id);
		//pr($room_id,1);
		$language = get_site_language();
		$pdetails = $this->Website_model->get_product_detail_by_id($prod_id,$language);
		//pr($prod_id,1);
		$data['similar_product'] = $this->Website_model->get_similar_product($pdetails['category_id'],$language);
		$data['pdetails'] = $pdetails;
		$data['contact_details'] = $this->Website_model->get_contact_details($language);

		$data['allProductDiscount'] = $this->Website_model->get_discount_details($prod_id);
		//pr($details,1);
		$this->layout->view('web/product-details',$data);
	}

	public function get_products(){
		
		$data['title'] = "Baaba.de - Products";
		$language = get_site_language();

		$data['searchPrdCat'] = '';
		$data['searchPrdBrand'] = '';
		$searchArrCat = $searchArrBrand = array();
		if($this->input->post()) 
		{
			$post = $this->input->post();
			if($this->input->post('catId'))
			{
				$data['searchPrdCat'] = base64_decode($this->input->post('catId'));
				$searchArrCat[0] = $data['searchPrdCat'];
			}
			if($this->input->post('brandId'))
			{
				$data['searchPrdBrand'] = base64_decode($this->input->post('brandId'));
				$searchArrBrand[0] = $data['searchPrdBrand'];
			}
		}

		$product_categories = $this->Website_model->get_product_categories($language);
		$product_sub_cates = $sub_categories = array();
		foreach ($product_categories as $key => $categories) 
		{
			$sub_categories = $this->Website_model->get_product_subcategories($language,$categories['category_id']);
			if(!empty($sub_categories))
				$categories['sub_categories'] = $sub_categories;
			else
				$categories['sub_categories'] = array();

			$product_sub_cates[] = $categories;
			//print_r($categories);
		}

		$data['perPage'] = MAX_WEB_RECORD;

		$data['product_brands'] = $this->Website_model->get_product_brands($language);
		
		$data['product_categories'] = $product_sub_cates;
		//pr($product_sub_cates,1);

		$data['products_list'] = $this->Website_model->search_popular_products_list($language,MAX_WEB_RECORD,$searchArrBrand,$searchArrCat);
		$data['total_products_count'] = $this->Website_model->search_total_products_count($searchArrBrand,$searchArrCat);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		//pr($data['recent_products'],1);
		$this->layout->view('web/shop-product',$data);
	}

	public function search_products(){
		
		$language = get_site_language();
		$this->layout->set_layout("web/layout/inner");
		if($this->input->post()) 
		{
			$post = $this->input->post();
			$data['perPage'] = $this->input->post('perPage');
			$searchArrCat = $this->input->post('searchArrCat');
			$searchArrBrand = $this->input->post('searchArrBrand');

			$data['current_page'] = isset($post['currentPage']) ? $post['currentPage'] : 1;

			if($this->input->post('listShortBy')=='short-popular')
			{
				$data['products_list'] = $this->Website_model->search_popular_products_list($language,$data['perPage'],$searchArrBrand,$searchArrCat,$data['current_page']);
			}
			elseif($this->input->post('listShortBy')=='short-new')
			{
				$data['products_list'] = $this->Website_model->search_new_products_list($language,$data['perPage'],$searchArrBrand,$searchArrCat,$data['current_page']);
			}
			$data['total_products_count'] = $this->Website_model->search_total_products_count($searchArrBrand,$searchArrCat);
			//pr($this->language,1);
		}
		$this->layout->view('web/shop-product-ajax',$data);
	}

	public function get_offer_products(){
		
		$data['title'] = "Baaba.de - Products";
		$language = get_site_language();

		$data['current_page'] = !empty($this->input->post('currentPage')) ? $this->input->post('currentPage') : 1;
		$data['perPage'] = !empty($this->input->post('perPage')) ? $this->input->post('perPage') : MAX_WEB_RECORD;

		$data['products_list'] = $this->Website_model->search_offers_products_list($language,$data['perPage'],$data['current_page']);

		$data['total_products_count'] = $this->Website_model->search_count_offer_products();

		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		//pr($data['recent_products'],1);
		$this->layout->view('web/offer-product',$data);
	}

	public function search_category($cate_id){
		
		$data['title'] = "Baaba.de - Products";
		$language = get_site_language();

		$searchArrCat = array();
		$searchArrCat[0] = base64_decode($cate_id);

		$product_categories = $this->Website_model->get_product_categories($language);
		$product_sub_cates = $sub_categories = array();
		foreach ($product_categories as $key => $categories) 
		{
			$sub_categories = $this->Website_model->get_product_subcategories($language,$categories['category_id']);
			if(!empty($sub_categories))
				$categories['sub_categories'] = $sub_categories;
			else
				$categories['sub_categories'] = array();

			$product_sub_cates[] = $categories;
			//print_r($categories);
		}

		$data['product_brands'] = $this->Website_model->get_product_brands($language);
		$data['product_categories'] = $product_sub_cates;
		//pr($product_sub_cates,1);

		$data['products_list'] = $this->Website_model->search_popular_products_list($language,MAX_WEB_RECORD,$searchArrCat);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		//pr($data['recent_products'],1);
		$this->layout->view('web/shop-product',$data);
	}

	public function get_web_search_products(){
		
		$data['title'] = "Baaba.de - Search Products";
		$language = get_site_language();

		if($this->input->post()) 
		{
			$search_key = $this->input->post('search_key');

			$data['products_list'] = $this->Website_model->web_search_products_list($language,$search_key);

			$data['contact_details'] = $this->Website_model->get_contact_details($language);
			//pr($data['recent_products'],1);
			$this->layout->view('web/search-product',$data);
		}
		else
		{
			redirect('home');
		}
	}

	public function get_product_price(){
		
		$discount_amount = 0;
		if($this->input->post()) 
		{
			$product_id = base64_decode($this->input->post('productId'));
			$price_type = base64_decode($this->input->post('productPriceType'));
			$quantity = $this->input->post('prodQuantity');

			$discount_amount = $this->Website_model->get_product_price($product_id,$price_type,$quantity);
		}
		print $discount_amount;
	}

	public function get_brands(){
		$language = get_site_language();
		$data['title'] = "Baaba.de - Brands";
		$data['product_brands'] = $this->Website_model->get_product_brands($language);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		$this->layout->view('web/brands',$data);
	}

	
	public function about_us(){
		$language = get_site_language();
		$data['title'] = "Baaba.de - About Us";
		$page_key = "ABOUT_US";
		$data['details'] = $this->Website_model->getCmsPages($page_key,$language);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		$this->layout->view('web/about-us',$data);
	}
	
	public function get_web_page_info($page_key){

		$language = get_site_language();
		$data['details'] = $this->Website_model->getCmsPages($page_key,$language);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		$data['title'] = "Baaba.de - ".$data['details']['page_name'];
		$this->layout->view('web/'.$page_key,$data);
	}
	
	public function contact_us(){

		if($this->input->post()) {
			$post = $this->input->post();
			//pr($post,1);
			$validation_post = array(
				array('field' => 'email', 'label' =>'Email', 'rules' => 'required')
               	, array('field' => 'name', 'label' =>'Name', 'rules' => 'trim|required')
				, array('field' => 'mobile', 'label' =>'Mobile Number', 'rules' => 'trim|required')
				, array('field' => 'message', 'label' =>'Message', 'rules' => 'trim|required')
            );
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules($validation_post);
			if ($this->form_validation->run() === TRUE) 
			{
				$user_name  = $post['name'];
				$message    = $post['message'];
				$mobile_no  = $post['mobile'];
				$to_user 	= $post['email'];
				$email 	= $post['email'];
				if($this->language == 'english')
				{
					$subject_user 	= SITE_NAME.' Contact us email';
					$message_user =	'Hello '.$user_name.'<br/>';
					$message_user .= 'Thank you for contact us, we will reach you soon.<br/>';
					$message_user .= 'We hope you enjoy our services!<br/><br/>';
					$message_user .= 'Sincerely<br/>';
					$message_user .=  SITE_NAME.' Team';
				}else{
					$subject_user 	= SITE_NAME.' Kontaktieren Sie uns per E-Mail';
					$message_user =	'Hallo '.$user_name.'<br/>';
					$message_user .= 'Vielen Dank für Ihre Kontaktaufnahme. Wir werden uns bald bei Ihnen melden.<br/>';
					//$message_user .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
					$message_user .= 'Wir hoffen, Sie genießen unsere Dienstleistungen!<br/><br/>';
					$message_user .= 'Mit freundlichen Grüßen<br/>';
					$message_user .=  SITE_NAME.' Mannschaft';
				}
				//pr($message_user);
				$mailConfirm1 = $this->sendemail($to_user,$subject_user,$message_user);
				//pr($mailConfirm1,1);
				
				$to_admin 		= CONTACT_US_ADMIN_EMAIL;

				if($this->language == 'english')
				{
					$subject_admin 	= SITE_NAME.' Contact us email';
					$message_admin  = 'Hello Admin <br/>';
					$message_admin .= 'Mobile No. : '.$mobile_no.'<br/>';
					$message_admin .= 'Email :'.$email.'<br/>';
					//$message_admin .= 'Company :'.$company.'<br/>';
					$message_admin .= 'Message :'.$message.'<br/>';
				}else{
					$subject_admin 	= SITE_NAME.' Kontaktieren Sie uns per E-Mail';
					$message_admin  = 'Hallo Admin <br/>';
					$message_admin .= 'Handynummer. : '.$mobile_no.'<br/>';
					$message_admin .= 'Email :'.$email.'<br/>';
					//$message_admin .= 'Company :'.$company.'<br/>';
					$message_admin .= 'Botschaft :'.$message.'<br/>';
				}
				//pr($message_admin,1);
				//$message .= 'Please do not hesitate to contact us at loot_champs@gmail.com with any questions or concerns. <br/>';
				$mailConfirm2 = $this->sendemail($to_admin,$subject_admin,$message_admin);

				$detail = array(
					'mobile_no'	=>	$mobile_no,
					'email'		=>	$to_user,
					'name'		=>	$user_name,
					//'company'	=>	$company,
					'message'	=>	$message,
					'created_on'=>	date('Y-m-d H:i:s')
				);
				$result 	= $this->db->insert('tbl_contact_us',$detail);
				$contact_us_id = $this->db->insert_id();
				$this->session->set_flashdata('flashSuccess',$this->lang->line('contact_us_success_msg'));
				redirect('contact-us');
			}
		}

		$language = get_site_language();
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		$this->layout->view("web/contact-us", $data);
	}
	
	public function get_faqs(){
		$data['title'] = 'FAQ';
		$language = get_site_language();
		$data['faq_contents'] = $this->Website_model->get_faq_details($language);
		$data['contact_details'] = $this->Website_model->get_contact_details($language);
		//pr($data,1);
		$this->layout->view("web/faq", $data);
	}



#------------ Common function -----------------
	
	public function testemail()
	{
		$to='cpk6168@gmail.com';
		$subject=SITE_NAME;
		$message='Test mail from '.SITE_NAME;
		$this->sendemail($to,$subject,$message);
	}
	
	public function sendemail($to,$subject,$upd_msg)
	{
		$message = '<table with="800"><tr><td style="background:#e7e7e7; padding:10px;"><center><a class="navbar-brand" href="'.base_url('home').'"><img style="width:auto; height:80px;" src="'.WEB_PATH.'/images/logo.png" alt="Logo" /></a></center></td></tr><tr><td style="background:#f1f1f1; padding:10px;">'.$upd_msg.'</td></tr></table>';

		require_once('smtp/class.phpmailer.php');

		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug =1;  // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true;  // authentication enabled
		$mail->SMTPSecure = SMTP_SECURE; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = HOST_NAME;
		$mail->CharSet = 'utf-8';
		$mail->Port = SMTP_PORT;
		$mail->Username = USER_NAME;
		$mail->Password = SMTP_PASSWORD;
		$mail->SetFrom(FROM_MAIL , FROM_NAME);
		$mail->Subject = $subject;					
		$mail->Body = $message;
		$mail->IsHTML(true); 
		$mail->AddAddress($to);

		if(!$mail->Send()) {
			return false;
		} else {
			return true;
		}
	}
	
	public function get_token($length=10)
	{
		$characters = '0123456789';
		$otp_token = '';
		for ($i = 0; $i < $length; $i++) {
			  $otp_token .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $otp_token;
	}	
	
	public function genrate_code($user_id){
		///////////////// for int value //////////////
		$length = 4;
		$numbers = range(0,9);
		shuffle($numbers);
		for($i = 0; $i < $length; $i++){
			global $digits;
			$digits .= $numbers[$i];
		}
			$data=array('email_varification_code'=>$digits,'modify'=>date('Y-m-d H:i:s'));
			$this->db->where('id',$user_id);
			$result = $this->db->update('tbl_usermaster',$data);
		return $digits;
		
		///////////////// for int and text(small and large) //////////////
		
		/* $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString; */
	}
	
	public function getLoginKey($user_id)
	{
		$salt = "23df$#%%^66sd$^%fg%^sjgdk90fdklndg099ndfg09LKJDJ*@##lkhlkhlsa#$%";
		$login_key = hash('sha1',$salt.$user_id);
		//print_r($login_key);die;
		return $login_key;
	}
	
	/* to change site language  */
	public function change_language($sel_language=""){
		$this->load->library('user_agent');
		$referrer = $this->agent->referrer();
		if($sel_language){
			$selected_lang	 	= change_site_language($sel_language);
			$siteLang		 	= $this->session->userdata('site_lang');
			//print_r($siteLang);die;
			$data['sel_lang'] 	= $selected_lang;
			//$this->layout->view("admin/dashboard", $data);
			
		}
		redirect($referrer);
	}
	/* end of change site language  */
	
}

/* End of file welcome.php */
