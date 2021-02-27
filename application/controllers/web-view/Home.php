<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
	
	public function __construct() {
		parent::__construct();
		//redirect('admin');
		/* if($this->site_santry->is_web_login())
		{
			redirect('dashboard');
		} */
		//$this->layout->set_layout("layout/main");
	}

	public function index(){
		
	}

	public function privacyPolicyWebView(){
		$this->db->where('page_key','PRIVACY_POLICY');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get('cms_pages');
		if($query->num_rows()>0)
		{
			$details = $query->row_array();
			//pr($details,1);
			$data['details']=$details;
		}else{
			$data['details'] = array();
		}
		$this->load->view('web-view/privacy-policy',$data);
	}
	
	public function termsConditionsWebView(){
		$this->db->where('page_key','TERMS_CONDITIONS');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get('cms_pages');
		if($query->num_rows()>0)
		{
			$details = $query->row_array();
			//pr($details,1);
			$data['details']=$details;
		}else{
			$data['details'] = array();
		}
		$this->load->view('web-view/terms_and_conditions',$data);
	}
}

/* End of file welcome.php */
