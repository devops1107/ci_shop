<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Site_santry
 *
 * @author Administrator
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Site_santry{
    var $ci_obj = null;
    var $redirect = "/";
    //put your code here
    /**
     *
     * @param type $params 
     */
    public function __construct($params = array()){
        $this->ci_obj = & get_instance();
    }
    public function is_login(){
        $auth_data = $this->ci_obj->session->userdata('_auth_data_baaba_de');
      	return is_array($auth_data) ? true : false;
    }
	
	public function is_web_login(){
        $auth_data = $this->ci_obj->session->userdata('_auth_data_baaba_de_web');
      	return is_array($auth_data) ? true : false;
    }
    /**
     *
     * @param type $params 
     */
    public function do_login($params = array()){
    	//pr($params,1);
        $this->ci_obj->session->set_userdata(array("_auth_data_baaba_de" => $params));
    }
	
	public function do_web_login($params = array()){
        $this->ci_obj->session->set_userdata(array("_auth_data_baaba_de_web" => $params));
    }
	
	/**
     *
     * @param type $params 
     */
	public function do_web_log_out(){
		return $this->ci_obj->session->unset_userdata('_auth_data_baaba_de_web');
	}
	
	public function do_log_out(){
		//pr($this->ci_obj->session->userdata('_auth_data_baaba_de')); exit;
		return $this->ci_obj->session->unset_userdata('_auth_data_baaba_de');
	}
	
	
	public function get_auth_data($get_data = NULL){
		if($get_data == NULL) {
			return $this->ci_obj->session->userdata('_auth_data_baaba_de');
		} else {
			$single_auth_data = $this->ci_obj->session->userdata('_auth_data_baaba_de');
			return $single_auth_data[$get_data];
		}
	}
	
	public function get_web_auth_data($get_data = NULL){
		if($get_data == NULL) {
			return $this->ci_obj->session->userdata('_auth_data_baaba_de_web');
		} else {
			$single_auth_data = $this->ci_obj->session->userdata('_auth_data_baaba_de_web');
			return $single_auth_data[$get_data];
		}
	}
	
	public function update_auth_data($data = NULL){
		if($data == NULL) {
			return false;
		} else {
			$auth_data = $this->ci_obj->session->userdata('_auth_data_baaba_de');
			foreach ($data as $key => $value) {
				$auth_data[$key] = $value;
			}
			$this->ci_obj->session->set_userdata(array("_auth_data_baaba_de" => $auth_data));
			return true;
		}
	}
	
	public function update_web_auth_data($data = NULL){
		if($data == NULL) {
			return false;
		} else {
			$auth_data = $this->ci_obj->session->userdata('_auth_data_baaba_de_web');
			foreach ($data as $key => $value) {
				$auth_data[$key] = $value;
			}
			$this->ci_obj->session->set_userdata(array("_auth_data_baaba_de_web" => $auth_data));
			return true;
		}
	}
	
	public function allow($actions = array()){
		if(!in_array($this->ci_obj->uri->rsegments[2], $actions) && $this->get_auth_data() === FALSE){
			redirect($this->redirect."?request=".base64_encode(uri_string()."?".$_SERVER['QUERY_STRING']));
		}
		return TRUE;
	}
	
}