<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');

class User_model extends CI_Model{

	public function get_tax_percent(){

		$this->db->select('tax');
		$this->db->from('tbl_tax_setting');
		$this->db->where('id',1);
		$query = $this->db->get();
		$tax_info = $query->row_array();
		return $tax_info['tax'];
	}

	public function my_order_details($language="",$login_id){

		$response = array();
		if($login_id > 0)
		{
			if($language=="" || $language=="english")
			{
				$this->db->select('P.product_id , P.product_name AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image');
			}
			elseif($language=="turkish")
			{
				$this->db->select('P.product_id , P.product_name_tr AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image');
			}
			elseif($language=="german")
			{
				$this->db->select('P.product_id , P.product_name_gr AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image');	
			}
			$this->db->from('tbl_order_details O');
			$this->db->join('tbl_products P' ,'P.product_id = O.product_id' , 'LEFT');
			$this->db->where('O.status','1');
			$query = $this->db->get();
			if($query->num_rows() >0)
			{
				$response = $query->result_array();
			}
		}
	}

	public function getUserData($user_id){

		$this->db->select('id,first_name, last_name, emailid, mobileno, vat_number, commercial_reg_no');
		$this->db->from('tbl_usermaster');
		$this->db->where('id',$user_id);
		$this->db->where('status','1');
		$this->db->where('delete_status','0');
		$query = $this->db->get();
		return $query->row_array();
	}

	public function getUserOrders($user_id){

		$this->db->select('*');
		$this->db->from('tbl_orders');
		$this->db->where('user_id',$user_id);
		$this->db->where('status','1');
		$query = $this->db->get();
		$order_details = array();
		if($query->num_rows() > 0)
		{	$i = 0;
			$user_order = $query->result_array();
			foreach ($user_order as $odr_info) {
				$order_details[$i] = $odr_info;
				$order_id = $odr_info['order_id'];

				$this->db->select('*');
				$this->db->from('tbl_order_details');
				$this->db->where('order_id',$order_id);
				$this->db->where('user_id',$user_id);
				$this->db->where('status','1');
				$ord_qur = $this->db->get();
				$odr_info = $ord_qur->result_array();

				$order_details[$i]['product_info'] = $odr_info;
			}
		}
		return $order_details;
	}
	
	public function my_cart_details($language="",$login_id){

		$response = array();
		if($login_id == "" && $login_id > 0)
		{
			if($language=="" || $language=="english")
			{
				$this->db->select('P.product_id , P.product_name AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image , O.quantity');
			}
			elseif($language=="turkish")
			{
				$this->db->select('P.product_id , P.product_name_tr AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image , O.quantity');
			}
			elseif($language=="german")
			{
				$this->db->select('P.product_id , P.product_name_gr AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image , O.quantity');	
			}
			$this->db->from('tbl_order_request O');
			$this->db->join('tbl_products P' ,'P.product_id = O.product_id' , 'LEFT');
			$this->db->where('O.status','1');
			$query = $this->db->get();
			if($query->num_rows() >0)
			{
				$response = $query->result_array();
			}
		}
		else
		{
			$shoapping_details = $this->db->session->userdata('user_shoapping_details');
			foreach ($shoapping_details as $sdetails) 
			{
				$quantity = $sdetails['quantity'];
				$product_id = $sdetails['product_id'];
				if($language=="" || $language=="emglish")
				{
					$this->db->select('product_id , product_name AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image');
				}
				elseif($language=="turkish")
				{
					$this->db->select('product_id , product_name_tr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image');
				}
				elseif($language=="german")
				{
					$this->db->select('product_id , product_name_gr AS product_title , single_product_price , master_carton_price , palette_price , single_product_offer , master_carton_offer , palette_offer , product_image');	
				}
				$this->db->from('tbl_products');
				$this->db->where('product_id',$product_id);
				$query = $this->db->get();
				if($query->num_rows() >0)
				{
					$ord_info = $query->result_array();
					$ord_info['quantity'] = $quantity;
					$response[] = $ord_info;
				}
			}
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
	
	public function get_product_request_by_id($user_id,$product_id,$shop_type){

		$this->db->select('*');
		$this->db->from('tbl_order_request');
		$this->db->where('user_id',$user_id);
		$this->db->where('product_id',$product_id);
		$this->db->where('shop_type',$shop_type);
		$this->db->where('status','1');
		$query = $this->db->get();
		$order_details = array();
		if($query->num_rows() > 0)
		{	
			$user_order = $query->row_array();
			$order_details['order_req_id'] = $user_order['order_request_id'];
			$order_details['product_id'] = $user_order['product_id'];
			$order_details['shop_type'] = $user_order['shop_type'];
			$order_details['quantity'] = $user_order['quantity'];
		}
		return $order_details;
	}

	public function update_order_request($user_id,$product_id,$shop_type,$product_qty)
	{
		$product_discount_price = $this->get_product_discount_price($product_id,$shop_type,$product_qty);

		if($product_discount_price == 0)
		{
			$this->db->select('*');
			$this->db->from('tbl_products');
			$this->db->where('product_id',$product_id);
			$query = $this->db->get();
			$product_details = $query->row_array();

			if($shop_type == 1)
			{
				$product_price = $product_details['single_product_price'];
				$product_discount = $product_details['single_product_offer'];
			}
			elseif($shop_type == 2)
			{
				$product_price = $product_details['master_carton_price'];
				$product_discount = $product_details['master_carton_offer'];
			}
			elseif($shop_type == 3)
			{
				$product_price = $product_details['palette_price'];
				$product_discount = $product_details['palette_offer'];
			}

			$product_discount = ($product_discount) ? $product_discount : 0;

			$net_prd_price = $product_price-$product_discount;
			$total_amount = $net_prd_price*$product_qty;
		}
		else
		{
			$product_price = $net_prd_price = sprintf('%0.2f', $product_discount_price);
			$product_discount = 0;
			$total_amount = sprintf('%0.2f', ($product_discount_price*$product_qty));
		}

		$update_data = array('quantity' => $product_qty, 'price' => $product_price , 'discount' => $product_discount, 'net_amount' => $net_prd_price, 'total_amount' => $total_amount, 'modify_date'=>date("Y-m-d H:i:s"));
		$this->db->where('user_id',$user_id);
		$this->db->where('product_id',$product_id);
		$this->db->where('shop_type',$shop_type);
		$result = $this->db->update('tbl_order_request',$update_data);
    }

    public function remove_order_request($user_id)
	{
		$update_data = array('status' => 0, 'modify_date'=>date("Y-m-d H:i:s"));
		$this->db->where('user_id',$user_id);
		$result = $this->db->update('tbl_order_request',$update_data);
    }
	
	public function insert_order_request($user_id,$product_id,$shop_type,$product_qty)
	{
		$product_discount_price = $this->get_product_discount_price($product_id,$shop_type,$product_qty);

		if($product_discount_price == 0)
		{
			$this->db->select('*');
			$this->db->from('tbl_products');
			$this->db->where('product_id',$product_id);
			$query = $this->db->get();
			$product_details = $query->row_array();

			if($shop_type == 1)
			{
				$product_price = $product_details['single_product_price'];
				$product_discount = $product_details['single_product_offer'];
			}
			elseif($shop_type == 2)
			{
				$product_price = $product_details['master_carton_price'];
				$product_discount = $product_details['master_carton_offer'];
			}
			elseif($shop_type == 3)
			{
				$product_price = $product_details['palette_price'];
				$product_discount = $product_details['palette_offer'];
			}

			$product_discount = ($product_discount) ? $product_discount : 0;

			$net_prd_price = $product_price-$product_discount;
			$total_amount = $net_prd_price*$product_qty;
		}
		else
		{
			$product_price = $net_prd_price = sprintf('%0.2f', $product_discount_price);
			$product_discount = 0;
			$total_amount = sprintf('%0.2f', ($product_discount_price*$product_qty));
		}	

		$order_data = array('user_id' => $user_id , 'product_id' => $product_id , 'shop_type' => $shop_type , 'quantity' => $product_qty, 'price' => sprintf("%0.2f",$product_price) , 'discount' => sprintf("%0.2f",$product_discount), 'net_amount' => sprintf("%0.2f",$net_prd_price), 'total_amount' => sprintf("%0.2f",$total_amount) , 'created_on'=>date("Y-m-d H:i:s") , 'modify_date'=>date("Y-m-d H:i:s"));

		$result = $this->db->insert('tbl_order_request',$order_data);
		return $result;
    }

    public function get_order_request_details($user_id,$language="")
	{
		if($language=="" || $language=="english")
		{
			$this->db->select('tbl_order_request.* , tbl_order_request.order_request_id AS order_id , tbl_brands.brand_name AS brand_title , P.product_name AS product_title , P.product_image');
		}
		elseif($language=="turkish")
		{
			$this->db->select('tbl_order_request.* , tbl_order_request.order_request_id AS order_id , tbl_brands.brand_name AS brand_title , P.product_name_tr AS product_title , P.product_image');
		}
		elseif($language=="german")
		{
			$this->db->select('tbl_order_request.* , tbl_order_request.order_request_id AS order_id , tbl_brands.brand_name AS brand_title , P.product_name_gr AS product_title , P.product_image');	
		}
		
		$this->db->from('tbl_order_request');
		$this->db->join('tbl_products P' ,'P.product_id = tbl_order_request.product_id' , 'LEFT');
		$this->db->join('tbl_brands' ,'tbl_brands.brand_id = P.brand_id' , 'LEFT');
		$this->db->where('tbl_order_request.user_id',$user_id);
		$this->db->where('tbl_order_request.status','1');
		$query = $this->db->get();
		$order_details = array();
		if($query->num_rows() > 0)
		{	
			$order_details = $query->result_array();
		}
		return $order_details;
    }

    public function remove_product_request_by_id($user_id,$order_id){

    	$this ->db-> where('user_id', $user_id);
	    $this ->db-> where('order_request_id', $order_id);
	    $this ->db-> delete('tbl_order_request');

		$this->db->select('*');
		$this->db->from('tbl_order_request');
		$this->db->where('user_id',$user_id);
		$this->db->where('status','1');
		$query = $this->db->get();
		$order_details = $query->result_array();
		return $order_details;
	}

	public function update_product_request_quantity($user_id,$order_tbl_id,$product_qty)
	{
		$this->db->select('*');
		$this->db->from('tbl_order_request');
		$this->db->where('user_id',$user_id);
		$this->db->where('order_request_id',$order_tbl_id);
		$this->db->where('status','1');
		$query = $this->db->get();
		$order_details = array();
		if($query->num_rows() > 0)
		{	
			$user_order = $query->row_array();
			$order_req_id = $user_order['order_request_id'];
			$product_id = $user_order['product_id'];
			$shop_type = $user_order['shop_type'];
		}

		$product_discount_price = $this->get_product_discount_price($product_id,$shop_type,$product_qty);

		if($product_discount_price == 0)
		{
			$this->db->select('*');
			$this->db->from('tbl_products');
			$this->db->where('product_id',$product_id);
			$query = $this->db->get();
			$product_details = $query->row_array();

			if($shop_type == 1)
			{
				$product_price = $product_details['single_product_price'];
				$product_discount = $product_details['single_product_offer'];
			}
			elseif($shop_type == 2)
			{
				$product_price = $product_details['master_carton_price'];
				$product_discount = $product_details['master_carton_offer'];
			}
			elseif($shop_type == 3)
			{
				$product_price = $product_details['palette_price'];
				$product_discount = $product_details['palette_offer'];
			}

			$net_prd_price = $product_price-$product_discount;
			$total_amount = $net_prd_price*$product_qty;
		}
		else
		{
			$product_price = $net_prd_price = sprintf('%0.2f', $product_discount_price);
			$product_discount = 0;
			$total_amount = sprintf('%0.2f', ($product_discount_price*$product_qty));
		}	

		$update_data = array('quantity' => $product_qty, 'price' => sprintf("%0.2f",$product_price) , 'discount' => sprintf("%0.2f",$product_discount), 'net_amount' => sprintf("%0.2f",$net_prd_price), 'total_amount' => sprintf("%0.2f",$total_amount), 'modify_date'=>date("Y-m-d H:i:s"));
		$this->db->where('user_id',$user_id);
		$this->db->where('order_request_id',$order_tbl_id);
		$this->db->update('tbl_order_request',$update_data);
	}

	public function insert_order($user_id)
	{
		$current_date = date('Y-m-d');

		$this->db->where("created_on BETWEEN '$current_date 00:00:00' AND '$current_date 23:59:59'");
		$totalorder = $this->db->count_all_results('tbl_orders');
		$transaction_id = date('Ym').$user_id.(sprintf("%'.05d\n", (rand(111,999)+$totalorder)));
		$order_data = array('user_id' => $user_id , 'transaction_id' => $transaction_id , 'created_on' => date("Y-m-d H:i:s") , 'modify_date' => date("Y-m-d H:i:s"));

		$this->db->insert('tbl_orders',$order_data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }

    public function insert_order_details($user_id,$orderInfo)
	{
		$this->db->insert('tbl_order_details',$orderInfo);
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }

    public function update_order($order_id,$update_array)
	{
		$this->db->where('order_id',$order_id);
		$result = $this->db->update('tbl_orders',$update_array);
    }

    public function get_order_by_order_id($order_id)
    {
    	$this->db->select('*');
		$this->db->from('tbl_orders');
		$this->db->where('order_id',$order_id);
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			$response = $query->row_array();
		}
		return $response;
    }

    public function get_order_details_by_order_id($language,$order_id)
	{
		$response = array();
		if($order_id > 0)
		{
			if($language=="" || $language=="english")
			{
				$this->db->select('O.* , P.product_id , P.product_name AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image');
			}
			elseif($language=="turkish")
			{
				$this->db->select('O.* , P.product_id , P.product_name_tr AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image');
			}
			elseif($language=="german")
			{
				$this->db->select('O.* , P.product_id , P.product_name_gr AS product_title , P.single_product_price , P.master_carton_price , P.palette_price , P.single_product_offer , P.master_carton_offer , P.palette_offer , P.product_image');	
			}
			$this->db->from('tbl_order_details O');
			$this->db->join('tbl_products P' ,'P.product_id = O.product_id' , 'LEFT');
			$this->db->where('O.order_id',$order_id);
			$query = $this->db->get();
			if($query->num_rows() >0)
			{
				$response = $query->result_array();
			}
		}
		return $response;
    }

    public function get_total_user_orders($user_id)
    {
    	$this->db->select('order_request_id');
		$this->db->from('tbl_order_request');
		$this->db->where('user_id',$user_id);
		$this->db->where('status','1');
		$query = $this->db->get();
		return $query->num_rows();
    }

    public function get_product_discount_price($product_id,$price_type,$quantity){

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

}

?>