<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Layout
 *
 * @author Chandra Prakash Khatri
 */
if (!defined('BASEPATH')) { exit('No direct script access allowed');}

class Layout {
	private $_javascript = array();
	private $_css = array();
	private $_inline_scripting = array("infile"=>"", "stripped"=>"", "unstripped"=>"");
	private $_sections = array();
	private $_cached_css = array();
	private $_cached_js = array();

    //put your code here
    var $obj;
    var $layout;

    function __construct($layout = "layout_main") {
        $this->obj = & get_instance();
		$this->layout = $layout;
		
		if(!$this->obj->session->userdata('current_session_id'))
		{
			$this->obj->session->set_userdata('current_session_id',time());
		}
    }

    function set_layout($layout) {
        $this->layout = $layout;
		
    }

    function view($view, $data=null, $return=false) {
		$loadedData = array();
        $loadedData['content_for_layout'] = $this->obj->load->view($view, $data, true);

        if ($return) {
            $output = $this->obj->load->view($this->layout, $loadedData, true);
            return $output;
        } else {
            $this->obj->load->view($this->layout, $loadedData, false);
        }
    }
    function element($view){
        $this->obj->load->view($view);
    }
	function js(){
		$script_files = func_get_args();

		foreach($script_files as $script_file){
			$script_file = substr($script_file,0,1) == '/' ? substr($script_file,1) : $script_file;

			$is_external = false;
			if(is_bool($script_file))
				continue;

			$is_external = preg_match("/^https?:\/\//", trim($script_file)) > 0 ? true : false;

			if(!$is_external)
				if(!file_exists($script_file))
					show_error("Cannot locate javascript file: {$script_file}.");

			$script_file = $is_external == FALSE ?  base_url() . $script_file : $script_file;

			if(!in_array($script_file, $this->_javascript))
				$this->_javascript[] = $script_file ;
		}

		return;
	}

}

?>
