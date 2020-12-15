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

    public function fetch_products(){
        return $this->db->from('products')->get()->result_array();
    }

    public function order($mydata){
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

        for($i=0; $i<count($mydata['myarray']); $i++):
            $order_items[$i]['product_id'] = $mydata['myarray'][$i]['product_id'];
            $order_items[$i]['order_id'] = $last_insert_id;
            $order_items[$i]['quantity'] = $mydata['myarray'][$i]['quantity'];
            $order_items[$i]['sub_total'] = $mydata['myarray'][$i]['subtotal'];
        endfor;

        return $this->db->insert_batch('order_items', $order_items);

    }
}


?>