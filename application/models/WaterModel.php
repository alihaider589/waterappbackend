<?php 

class WaterModel extends CI_Model{
    public function __construct(){
        parent::__construct();
    }

    public function check_email($email){
        return $this->db->from('customers')->where('email',$email)->get()->row_array();
    }

    public function register($data){
        return $this->db->insert('customers',$data);
    }

    public function login($email, $password){
        return $this->db->from('customers')->where('email',$email)->where('password',$password)->get()->row_array();
    }
    
    public function login_driver($email, $password){
        return $this->db->from('driver')->where('driver_email',$email)->where('driver_password',$password)->get()->row_array();
    }

    public function fetch_products(){
        return $this->db->from('products')->get()->result_array();
    }

    public function order($mydata){
        $myarray = array();
        $myarray['ordered_by'] = $mydata['ordered_by'];
        $myarray['grandTotal'] = $mydata['grandTotal'];
        $myarray['payment_type'] = $mydata['payment_type'];
        $myarray['easyimage'] = $mydata['image'];
        $this->db->insert('orders',$myarray);
        $last_insert_id = $this->db->insert_id();
        $order_items = array();
        /* echo '<pre>';
        print_r($mydata['myarray']);
        echo '</pre>'; */
        for($i=0; $i<count($mydata['myarray'][0]); $i++):
            if($mydata['myarray'][0][$i]['quantity'] != 0):
                $order_items[$i]['product_id'] = $mydata['myarray'][0][$i]['product_id'];
            $order_items[$i]['order_id'] = $last_insert_id;
            $order_items[$i]['quantity'] = $mydata['myarray'][0][$i]['quantity'];
            $order_items[$i]['sub_total'] = $mydata['myarray'][0][$i]['subtotal'];
            endif;
        endfor;
        return $this->db->insert_batch('order_items', $order_items);

    }
    
    public function order_cod($mydata){
        $myarray = array();
        $myarray['ordered_by'] = $mydata['ordered_by'];
        $myarray['grandTotal'] = $mydata['grandTotal'];
        $myarray['payment_type'] = $mydata['payment_type'];
        $this->db->insert('orders',$myarray);
        $last_insert_id = $this->db->insert_id();
        $order_items = array();
        /* echo '<pre>';
        print_r($mydata['myarray']);
        echo '</pre>'; */
        for($i=0; $i<count($mydata['myarray'][0]); $i++):
            if($mydata['myarray'][0][$i]['quantity'] != 0):
                $order_items[$i]['product_id'] = $mydata['myarray'][0][$i]['product_id'];
            $order_items[$i]['order_id'] = $last_insert_id;
            $order_items[$i]['quantity'] = $mydata['myarray'][0][$i]['quantity'];
            $order_items[$i]['sub_total'] = $mydata['myarray'][0][$i]['subtotal'];
            endif;
        endfor;
        return $this->db->insert_batch('order_items', $order_items);

    }
    
    public function fetch_pending_orders(){
        return $this->db
        ->select('orders.order_id, cs.full_name, orders.grandTotal, orders.ordered_at, orders.payment_type,cs.address, cs.email')
        ->from('orders')
        ->join('customers as cs','orders.ordered_by = cs.id')
        ->where('orders.order_status','pending')
        ->get()
        ->result_array();
    }
    
    public function fetch_pending_orders_details($order_id){
        return $this->db
        ->select('products.size, order_items.quantity, products.product_price, order_items.sub_total')
        ->from('order_items')
        ->join('products','products.id = order_items.product_id')
        ->where('order_items.order_id', $order_id)
        ->get()
        ->result_array();
    }
    
    public function mark_completed($order_id){
        return $this->db
        ->set('order_status','delivered')
        ->where('order_id',$order_id)
        ->update('orders');
    }
    
    
    public function fetch_delivered_orders(){
        return $this->db
        ->select('orders.order_id, cs.full_name, orders.grandTotal, orders.ordered_at, orders.payment_type,cs.address, cs.email')
        ->from('orders')
        ->join('customers as cs','orders.ordered_by = cs.id')
        ->where('orders.order_status','delivered')
        ->get()
        ->result_array();
    }
    
    public function fetch_delivered_details($order_id){
        return $this->db
        ->select('products.size, order_items.quantity, products.product_price, order_items.sub_total')
        ->from('order_items')
        ->join('products','products.id = order_items.product_id')
        ->where('order_items.order_id', $order_id)
        ->get()
        ->result_array();
    }
    
    public function count_delivered_orders(){
        return $this->db->from('orders')->where('order_status','delivered')->count_all_results();
    }
    
    public function count_pending_orders(){
        return $this->db->from('orders')->where('order_status','pending')->count_all_results();
    }
    
    public function fetch_specific_user_orders($userid){
        return $this->db
        ->select('orders.order_id, orders.grandTotal, orders.ordered_at, orders.payment_type, orders.order_status')
        ->from('orders')
        ->where('orders.ordered_by',$userid)
        ->get()
        ->result_array();
    }
    
    public function fetch_specific_user_order_details($order_id){
        return $this->db
        ->select('products.size, order_items.quantity, products.product_price, order_items.sub_total')
        ->from('order_items')
        ->join('products','products.id = order_items.product_id')
        ->where('order_items.order_id', $order_id)
        ->get()
        ->result_array();
    }
    
}


?>