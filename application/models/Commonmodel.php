<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Commonmodel
 *
 * @author virendra
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Commonmodel extends CI_Model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    /**
     *
     * @param type $table
     * @param type $data
     * @return type 
     */
    public function _insert($table, $data){
        if($this->db->insert($table, $data))
		   return $this->db->insert_id();
		else
			return false;
    }
	
    /**
     *
     * @param type $table
     * @param type $data
     * @return type 
     */
    public function _insert_batch($table, $data){
        $this->db->insert_batch($table, $data);
        //return $this->db->insert_id();
    }	
	
    /**
     *
     * @param type $table
     * @param type $data
     * @param type $condition
     * @return type 
     */
    public function _update($table, $data, $condition){
	    $this->db->update($table, $data, $condition);
		//echo $this->db->last_query(); die;
        return $this->db->affected_rows() ? $this->db->affected_rows(): true;
        //$this->db->affected_rows() ? $this->db->affected_rows(): true;
		
    }
    /**
     *
     * @param type $table
     * @param type $condition
     * @return type 
     */
    public function _delete($table, $condition){
        $this->db->delete($table, $condition);
        return $this->db->affected_rows();
    }
	
	public function _get_data($table,$condition,$field='*',array $orderby=null)
	{
		$this->db->select($field)
					->where($condition);
		 if (!is_null($orderby))
		 { 
			$this->db->order_by(key($orderby),$orderby[key($orderby)]);
		 }
		$result = 	$this->db->get($table);
		return $result->num_rows() > 0 ? $result->result_array() : null;
	}
	
	public function _get_data_where_in($table,$condition,$field='*',array $orderby=null)
	{
		$this->db->select($field)
					->where_in(key($condition),$condition[key($condition)]);
		 if (!is_null($orderby))
		 { 
			$this->db->order_by(key($orderby),$orderby[key($orderby)]);
		 }
		
		$result = 	$this->db->get($table);
		return $result->num_rows() > 0 ? $result->result_array() : null;
	}
	
	public function _get_data_like($table,$condition)
	{
			$this->db->select("*")
						->like($condition);
			$result = 	$this->db->get($table);
			return $result->num_rows() > 0 ? $result->result_array() : null;
	}
	public function _get_data_row($field=0,$table,$condition)
	{
		$this->db->select($field)
			->where($condition);
		$result = 	$this->db->get($table);
		//echo $this->db->last_query(); 
		return $result->num_rows();
	}
	/* public function getFeaturedItem($table, $filterParams = null, $fieldParams = null){
		$data = null;
		$filters = array($table .'.featured' => 1);
		if(!empty($filterParams)) {
			$filters = $filterParams;
		}
		if(!empty($fieldParams)) {
			$fields = $fieldParams;
		}
		else {
			$fields = $table .'.*';
		}
		
		$result = $this->db->select($fields)
					       ->get_where($table, $filters);
		if ($result->num_rows() > 0){
		   $data = $result->result();		
		}
        return $data;
	}
	
	public function fetchSelected($table, $filterParams = null, $fieldParams = null){
		$data = null;
		
		if(!empty($fieldParams)) {
			$fields = $fieldParams;
		}
		else {
			$fields = $table .'.*';
		}
		
		$this->db->select($fields);
		
		if(!empty($filterParams)) {
			$this->db->where($filterParams);
		}
		
		$result = $this->db->get($table);
		
		if ($result->num_rows() > 0){
		   $data = $result->result();		
		}
        return $data;
	} */
	
	
	public function validateLoginId($role,$id) {
		if($role == 'student') {
			$this->db->select('st_id')
				->where('st_id',$id);
			$result = 	$this->db->get('students');
			return $result->num_rows();
		} else {
			$this->db->select('tu_id')
				->where('tu_id',$id);
			$result = 	$this->db->get('tutors');
			return $result->num_rows();
		}
	}
	
	
	
	
}

?>
