<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Landing extends CI_Controller {
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
		$this->load->model('web/Website_model');
        $this->layout->set_layout("web/layout_landing/main");
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
        $data['sal_products'] = $this->Website_model->get_sale_products($language);
        $data['best_products'] = $this->Website_model->get_best_products($language);
        $data['deals_products'] = $this->Website_model->get_deals_products($language);
        $data['top_a_products'] = $this->Website_model->get_top_a_products($language);
        //pr($data['recent_products'],1);
        $this->layout->view('web/landing',$data);
    }

	
}

/* End of file welcome.php */
