<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Website_model extends CI_Model{
	
	public function get_product_brands($language="",$is_offer="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('tbl_brands.brand_id , tbl_brands.brand_name AS brand_title , brand_image , COUNT(tbl_products.product_id) AS total_products');
		}
		elseif($language=="turkish")
		{
			$this->db->select('tbl_brands.brand_id , tbl_brands.brand_name_tr AS brand_title , tbl_brands.brand_image , COUNT(tbl_products.product_id) AS total_products');
		}
		elseif($language=="german")
		{
			$this->db->select('tbl_brands.brand_id , tbl_brands.brand_name_gr AS brand_title , tbl_brands.brand_image , COUNT(tbl_products.product_id) AS total_products');	
		}

		$this->db->from('tbl_brands');
		if($is_offer=="")
		{
			$this->db->join("tbl_products" , "tbl_products.brand_id = tbl_brands.brand_id AND tbl_products.delete_status = '0'" , "LEFT");
		}
		else
		{
			$this->db->join("tbl_products" , "tbl_products.brand_id = tbl_brands.brand_id AND tbl_products.delete_status = '0' AND (tbl_products.single_product_offer > 0 OR tbl_products.master_carton_offer > 0 OR tbl_products.palette_offer > 0)" , "LEFT");
		}
		$this->db->where('tbl_brands.status','1');
		$this->db->where('tbl_brands.delete_status','0');
		$this->db->group_by('tbl_brands.brand_id');
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}

	public function get_product_categories($language="",$is_offer="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('tbl_categories.category_id , tbl_categories.category_name AS category_title , category_image , COUNT(tbl_products.product_id) AS total_products');
		}
		elseif($language=="turkish")
		{
			$this->db->select('tbl_categories.category_id , tbl_categories.category_name_tr AS category_title , tbl_categories.category_image , COUNT(tbl_products.product_id) AS total_products');
		}
		elseif($language=="german")
		{
			$this->db->select('tbl_categories.category_id , tbl_categories.category_name_gr AS category_title , tbl_categories.category_image , COUNT(tbl_products.product_id) AS total_products');	
		}

		$this->db->from('tbl_categories');
		
		if($is_offer=="")
		{
			$this->db->join("tbl_products" , "tbl_products.category_id = tbl_categories.category_id AND tbl_products.delete_status = '0'" , "LEFT");
		}
		else
		{
			$this->db->join("tbl_products" , "tbl_products.category_id = tbl_categories.category_id AND tbl_products.delete_status = '0' AND (tbl_products.single_product_offer > 0 OR tbl_products.master_carton_offer > 0 OR tbl_products.palette_offer > 0)" , "LEFT");
		}
		$this->db->where('tbl_categories.status','1');
		$this->db->where('tbl_categories.delete_status','0');
		$this->db->group_by('tbl_categories.category_id');
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}

	public function get_product_subcategories($language="",$category_id)
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('tbl_sub_categories.sub_category_id , tbl_sub_categories.subcategory_name AS subcategory_title , tbl_sub_categories.subcategory_image , COUNT(tbl_products.product_id) AS total_sbproducts');
		}
		elseif($language=="turkish")
		{
			$this->db->select('tbl_sub_categories.sub_category_id , tbl_sub_categories.subcategory_name_tr AS subcategory_title , tbl_sub_categories.subcategory_image , COUNT(tbl_products.product_id) AS total_sbproducts');
		}
		elseif($language=="german")
		{
			$this->db->select('tbl_sub_categories.sub_category_id , tbl_sub_categories.subcategory_name_gr AS subcategory_title , tbl_sub_categories.subcategory_image , COUNT(tbl_products.product_id) AS total_sbproducts');	
		}

		$this->db->from('tbl_sub_categories');
		$this->db->join("tbl_products" , "tbl_products.subcategory_id = tbl_sub_categories.sub_category_id AND tbl_products.delete_status = '0'" , "LEFT");
		$this->db->where('tbl_sub_categories.status','1');
		$this->db->where('tbl_sub_categories.delete_status','0');
		$this->db->where('tbl_sub_categories.category_id',$category_id);
		$this->db->group_by('tbl_sub_categories.sub_category_id');
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}

	public function get_recent_products($language="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
		}
		elseif($language=="turkish")
		{
			$this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
		}
		elseif($language=="german")
		{
			$this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');	
		}
		$this->db->from('tbl_products');
		$this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
		$this->db->where('tbl_products.status','1');
		$this->db->where('tbl_products.delete_status','0');
		$this->db->order_by('tbl_products.created_on','DESC');
		$this->db->limit('100');
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}
    public function get_top_products($language="")
    {
        $response = array();
        if($language=="" || $language=="english")
        {
            $this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
        }
        elseif($language=="turkish")
        {
            $this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
        }
        elseif($language=="german")
        {
            $this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');
        }
        $this->db->from('tbl_products');
        $this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
        $this->db->where('tbl_products.status','1');
        $this->db->where('tbl_products.delete_status','0');
        $this->db->order_by('single_product_offer','desc');
        $this->db->order_by('rand()');
        $this->db->limit('40');
        $query = $this->db->get();
        if($query->num_rows() >0)
        {
            $response = $query->result_array();
        }
        return $response;
    }
    public function get_top_a_products($language="")
    {
        $response = array();
        if($language=="" || $language=="english")
        {
            $this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
        }
        elseif($language=="turkish")
        {
            $this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
        }
        elseif($language=="german")
        {
            $this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');
        }
        $this->db->from('tbl_products');
        $this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
        $this->db->where('tbl_products.status','1');
        $this->db->where('tbl_products.delete_status','0');
        $this->db->order_by('single_product_offer','desc');
        $this->db->order_by('rand()');
        $this->db->limit('40');
        $query = $this->db->get();
        if($query->num_rows() >0)
        {
            $response = $query->result_array();
        }
        return $response;
    }
    public function get_sale_products($language="")
    {
        $response = array();
        if($language=="" || $language=="english")
        {
            $this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
        }
        elseif($language=="turkish")
        {
            $this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
        }
        elseif($language=="german")
        {
            $this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');
        }
        $this->db->from('tbl_products');
        $this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
        $this->db->where('tbl_products.status','1');
        $this->db->where('tbl_products.delete_status','0');
        $this->db->order_by('rand()');
        $this->db->limit('20');
        $query = $this->db->get();
        if($query->num_rows() >0)
        {
            $response = $query->result_array();
        }
        return $response;
    }
    public function get_best_products($language="")
    {
        $response = array();
        if($language=="" || $language=="english")
        {
            $this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
        }
        elseif($language=="turkish")
        {
            $this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
        }
        elseif($language=="german")
        {
            $this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');
        }
        $this->db->from('tbl_products');
        $this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
        $this->db->where('tbl_products.status','1');
        $this->db->where('tbl_products.delete_status','0');
        $this->db->order_by('rand()');
        $this->db->limit('20');
        $query = $this->db->get();
        if($query->num_rows() >0)
        {
            $response = $query->result_array();
        }
        return $response;
    }
	public function get_offer_products($language="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
		}
		elseif($language=="turkish")
		{
			$this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
		}
		elseif($language=="german")
		{
			$this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');	
		}
		$this->db->from('tbl_products');
		$this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
		$this->db->where('tbl_products.status','1');
		$this->db->where('tbl_products.delete_status','0');
		$this->db->group_start();
		$this->db->where('single_product_offer > 0');
		$this->db->or_where('master_carton_offer > 0');
		$this->db->or_where('palette_offer > 0');
		$this->db->group_end();
		$this->db->order_by('rand()');
		$this->db->limit('10');
		$query = $this->db->get();
		//print_r($this->db->last_query());
		//pr($query,1);
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}
	public function get_deals_products($language="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image');
		}
		elseif($language=="turkish")
		{
			$this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image ');
		}
		elseif($language=="german")
		{
			$this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image ');
		}
		$this->db->from('tbl_products');
		$this->db->where('tbl_products.product_id > 10');
		$this->db->limit(3);
		$query = $this->db->get();
		//print_r($this->db->last_query());
		//pr($query,1);
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}
	public function get_product_detail_by_id($product_id,$language="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('product_id , tbl_products.category_id , product_name AS product_title , description AS product_description , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category , tbl_brands.brand_name AS brand_title');
		}
		elseif($language=="turkish")
		{
			$this->db->select('product_id , tbl_products.category_id , product_name_tr AS product_title , description_tr AS product_description , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category , tbl_brands.brand_name_tr AS brand_title');
		}
		elseif($language=="german")
		{
			$this->db->select('product_id , tbl_products.category_id , product_name_gr AS product_title , description_gr AS product_description , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category , tbl_brands.brand_name_gr AS brand_title');	
		}
		$this->db->from('tbl_products');
		$this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
		$this->db->join('tbl_brands' ,'tbl_brands.brand_id = tbl_products.brand_id' , 'LEFT');
		$this->db->where('tbl_products.product_id',$product_id);
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->row_array();
		}
		return $response;
	}

	public function get_similar_product($category_id,$language="")
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name AS product_category');
		}
		elseif($language=="turkish")
		{
			$this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_tr AS product_category');
		}
		elseif($language=="german")
		{
			$this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , tbl_categories.category_name_gr AS product_category');	
		}
		$this->db->from('tbl_products');
		$this->db->join('tbl_categories' ,'tbl_categories.category_id = tbl_products.category_id' , 'LEFT');
		$this->db->where('tbl_products.category_id',$category_id);
		$this->db->where('tbl_products.status','1');
		$this->db->where('tbl_products.delete_status','0');
		$this->db->order_by('tbl_products.product_id','DESC');
		$this->db->limit('10');
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->result_array();
		}
		return $response;
	}

	public function search_popular_products_list($language="",$perPage,$searchArrBrand=array(),$searchArrCat=array(),$coffset=0)
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$query_select = 't1.product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name AS product_category';
		}
		elseif($language=="turkish")
		{
			$query_select = 't1.product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_tr AS product_category';
		}
		elseif($language=="german")
		{
			$query_select = 't1.product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_gr AS product_category';	
		}

		$offset = ($coffset > 0) ? (($coffset - 1) * $perPage) : 0;
		$limit = "LIMIT " . $offset . "," . $perPage;

		$search_by_brand_cat = "";
		if(!empty($searchArrCat))
		{
			$search_by_brand_cat = "AND ( t1.category_id IN (".implode(', ', $searchArrCat).")";
		}

		if(!empty($searchArrBrand))
		{
			$search_by_brand_cat .= ($search_by_brand_cat == "") ? " AND (t1.brand_id IN (".implode(', ', $searchArrBrand).")" : " OR t1.brand_id IN (".implode(', ', $searchArrBrand).")";
		}

		$search_by_brand_cat .= ($search_by_brand_cat == "") ? "" : ")";
		
		$query = "SELECT $query_select , COALESCE(t2.totalQuantity, 0) FROM tbl_products t1 LEFT JOIN (SELECT product_id, SUM(quantity) AS totalQuantity FROM tbl_order_details GROUP BY product_id) t2 ON t1.product_id = t2.product_id LEFT JOIN tbl_categories t3 ON t3.category_id = t1.category_id WHERE t1.delete_status = '0' $search_by_brand_cat AND t1.status = '1' ORDER BY t2.totalQuantity DESC $limit";
		//pr($query,1);
		$sResult = $this->db->query($query);
		if($sResult->num_rows() >0)
		{
			$response = $sResult->result_array();
		}
		return $response;
	}

	public function search_new_products_list($language="",$perPage,$searchArrBrand=array(),$searchArrCat=array(),$coffset=0)
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$query_select = 't1.product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name AS product_category';
		}
		elseif($language=="turkish")
		{
			$query_select = 't1.product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_tr AS product_category';
		}
		elseif($language=="german")
		{
			$query_select = 't1.product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_gr AS product_category';	
		}

		$offset = ($coffset > 0) ? (($coffset - 1) * $perPage) : 0;
		$limit = "LIMIT " . $offset . "," . $perPage;

		$search_by_brand_cat = "";
		if(!empty($searchArrCat))
		{
			$search_by_brand_cat = "AND ( t1.category_id IN (".implode(', ', $searchArrCat).")";
		}

		if(!empty($searchArrBrand))
		{
			$search_by_brand_cat .= ($search_by_brand_cat == "") ? " AND (t1.brand_id IN (".implode(', ', $searchArrBrand).")" : " OR t1.brand_id IN (".implode(', ', $searchArrBrand).")";
		}

		$search_by_brand_cat .= ($search_by_brand_cat == "") ? "" : ")";
		
		$query = "SELECT $query_select FROM tbl_products t1 LEFT JOIN tbl_categories t3 ON t3.category_id = t1.category_id WHERE t1.delete_status = '0' AND t1.status = '1' $search_by_brand_cat ORDER BY t1.product_id DESC $limit";

		$sResult = $this->db->query($query);
		if($sResult->num_rows() >0)
		{
			$response = $sResult->result_array();
		}
		return $response;
	}

	public function search_total_products_count($searchArrBrand=array(),$searchArrCat=array())
	{
		$search_by_brand_cat = "";
		if(!empty($searchArrCat))
		{
			$search_by_brand_cat = "AND ( t1.category_id IN (".implode(', ', $searchArrCat).")";
		}

		if(!empty($searchArrBrand))
		{
			$search_by_brand_cat .= ($search_by_brand_cat == "") ? " AND (t1.brand_id IN (".implode(', ', $searchArrBrand).")" : " OR t1.brand_id IN (".implode(', ', $searchArrBrand).")";
		}

		$search_by_brand_cat .= ($search_by_brand_cat == "") ? "" : ")";
		
		$query = "SELECT t1.product_id FROM tbl_products t1 WHERE t1.delete_status = '0' AND t1.status = '1' $search_by_brand_cat ";

		$sResult = $this->db->query($query);
		return $sResult->num_rows();
	}

	public function search_offers_products_list($language="",$perPage,$coffset=0)
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$query_select = 't1.product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name AS product_category';
		}
		elseif($language=="turkish")
		{
			$query_select = 't1.product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_tr AS product_category';
		}
		elseif($language=="german")
		{
			$query_select = 't1.product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_gr AS product_category';	
		}

		$offset = ($coffset > 0) ? (($coffset - 1) * $perPage) : 0;
		$limit = "LIMIT " . $offset . "," . $perPage;

		$query = "SELECT $query_select FROM tbl_products t1 LEFT JOIN tbl_categories t3 ON t3.category_id = t1.category_id WHERE t1.delete_status = '0' AND t1.status = '1' AND (t1.single_product_offer > 0 OR t1.master_carton_offer > 0 OR t1.palette_offer > 0) ORDER BY t1.product_id DESC $limit";
		//print_r($this->db->last_query());
		//pr($query,1);
		$sResult = $this->db->query($query);
		if($sResult->num_rows() >0)
		{
			$response = $sResult->result_array();
		}
		return $response;
	}

	public function search_count_offer_products()
	{
		$query = "SELECT t1.product_id FROM tbl_products t1 WHERE t1.delete_status = '0' AND t1.status = '1' AND (t1.single_product_offer > 0 OR t1.master_carton_offer > 0 OR t1.palette_offer > 0)";
		$sResult = $this->db->query($query);
		return $sResult->num_rows();
	}

	
	public function web_search_products_list($language="",$search_key)
	{
		$response = array();
		if($language=="" || $language=="english")
		{
			$query_select = 't1.product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name AS product_category';
		}
		elseif($language=="turkish")
		{
			$query_select = 't1.product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_tr AS product_category';
		}
		elseif($language=="german")
		{
			$query_select = 't1.product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image , t3.category_name_gr AS product_category';	
		}

		$search_by_key = " (t1.category_id IN (SELECT category_id FROM tbl_categories WHERE category_name like '%$search_key%') OR t1.brand_id IN (SELECT brand_id FROM tbl_brands WHERE brand_name like '%$search_key%')) ";

		$search_by_brand_cat .= ($search_by_brand_cat == "") ? "" : ")";
		
		$query = "SELECT $query_select FROM tbl_products t1 LEFT JOIN tbl_categories t3 ON t3.category_id = t1.category_id WHERE $search_by_key AND t1.delete_status = '0' AND t1.status = '1' GROUP BY t1.product_id";
		//pr($query,1);
		$sResult = $this->db->query($query);
		if($sResult->num_rows() >0)
		{
			$response = $sResult->result_array();
		}
		return $response;
	}
	
	public function getCmsPages($page_key,$language){
		$this->db->select('*');
		$this->db->from('cms_pages');
		$this->db->where('page_key',$page_key);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		$response['description'] = "";
		if($query->num_rows()>0){
			$cms_data = $query->row_array();
			$response['page_name'] = $cms_data['page_name'];
			$response['page_key'] = $page_key;
			if($language=="" || $language=="english")
			{
				$response['description'] = $cms_data['description'];
			}
			elseif($language=="turkish")
			{
				$response['description'] = $cms_data['description_tr'];
			}
			elseif($language=="german")
			{
				$response['description'] = $cms_data['description_gr'];	
			}
		}
		return $response;
	}

	public function get_contact_details($language){

		if($language=="" || $language=="english")
		{
			$this->db->select('mobile_no , email , address');
		}
		elseif($language=="turkish")
		{
			$this->db->select('mobile_no , email , address_tr AS address');
		}
		elseif($language=="german")
		{
			$this->db->select('mobile_no , email , address_gr AS address');
		}
		$this->db->from('tbl_contact');
		$this->db->where('id',1);
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		$response = $query->row_array();
		return $response;
	}

	public function get_faq_details($language){

		if($language=="" || $language=="english")
		{
			$this->db->select('faq_id,question AS faq_question,answer AS faq_answer');
		}
		elseif($language=="turkish")
		{
			$this->db->select('faq_id,question_tr AS faq_question,answer_tr AS faq_answer');
		}
		elseif($language=="german")
		{
			$this->db->select('faq_id,question_gr AS faq_question,banswer_gr AS faq_answer');
		}
		$this->db->from('tbl_faqs');
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$this->db->order_by('created_on','DESC');
		$query2 = $this->db->get();
		$faqsData = array();
		if($query2->num_rows()>0){			
			$faqsData = $query2->result_array();
		}
		return $faqsData;
	}

	public function get_discount_details($product_id){

		$this->db->select('*');
		$this->db->from('tbl_product_discount');
		$this->db->where('product_id',$product_id);
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		return $resulArray = $result->result_array();
	}

	public function get_slider_image(){

		$this->db->select('*');
		$this->db->from('tbl_slider');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		return $resulArray = $result->result_array();
	}

	public function get_banner_image($language){ 

		if($language=="" || $language=="english")
		{
			$this->db->select('heading AS banner_heading , sub_heading AS banner_sub_heading , banner_image');
		}
		elseif($language=="turkish")
		{
			$this->db->select('heading_tr AS banner_heading , sub_heading_tr AS banner_sub_heading , banner_image');
		}
		elseif($language=="german")
		{
			$this->db->select('heading_gr AS banner_heading , sub_heading_gr AS banner_sub_heading , banner_image');
		}
		$this->db->from('tbl_banners');
		$this->db->where('delete_status','0');
		$result = $this->db->get();
		return $resulArray = $result->result_array();
	}

	public function get_middle_banner(){

		$this->db->select('*');
		$this->db->from('tbl_middle_banner');
		$result = $this->db->get();
		return $resulArray = $result->row_array();
	}

	public function get_product_price($product_id,$price_type,$quantity){

		$this->db->select('discount_price');
		$this->db->from('tbl_product_discount');
		$this->db->where('product_id',$product_id);
		$this->db->where('price_type',$price_type);
		$this->db->where('quantity',$quantity);
		$this->db->where('delete_status','0');
		$query = $this->db->get();

		if($query->num_rows()>0){
			$dis_data = $query->row_array();
			$discount_price = $dis_data['discount_price'];
		}
		else
			$discount_price = 0;

		return $discount_price;
	}
}
?>