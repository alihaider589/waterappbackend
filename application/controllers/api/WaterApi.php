<?php 

require APPPATH . '/libraries/REST_Controller.php';
class WaterApi extends REST_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('WaterModel', 'wm');
    }

    /* public function check_get(){
        $this->response($this->input->get(), 200);
    } */

    //REGISTERING CUSTOMER
    public function register_post(){
        $email = $this->input->get('email');
        $emailverify = $this->wm->check_email($email);
        if(empty($emailverify)):
            $myarray = array(
                'full_name'=>$this->input->get('full_name'),
                'email'=>$this->input->get('email'),
                'password'=>$this->input->get('password'),
                'address'=>$this->input->get('address'),
                'phone'=>$this->input->get('phone')
            );
            if($this->wm->register($myarray)):
                $this->response([
                    'message'=>'Registeration Successfull'
                ]);
            else:
                $this->response([
                    'message'=>'Some Error Occured'
                ]);
            endif;
            
        else:
            $this->response([
                'message'=>'Email Already Exist'
            ], 200);
        endif;
    }

    //CUSTOMER LOGIN
    public function login_post(){
       $email = $this->input->get('email');
       $password = $this->input->get('password');

       if(!empty($this->wm->login($email, $password))):
        $this->response([
            'message'=>'Login Successfull',
            'data'=>$this->wm->login($email, $password)
        ]);

        else:
            $this->response([
                'message'=>'Incorrect Email / Password'
            ]); 
       endif;
    }


    //FETCH ALL PRODUCTS
    public function fetch_products_get(){
        if(!empty($this->wm->fetch_products())):
            $this->response($this->wm->fetch_products(),200);
        endif;
    }


    //POSTING ORDERS
    public function order_post(){
        
        //$this->response(json_decode($this->input->get('myarray')));
        //$this->response($this->input->get());exit;
        if($this->input->get('payment_type') == 'EasyPaisa'):
                if(!empty($this->input->get('easyimge'))):
                        $data['ordered_by'] = $this->input->get('ordered_by');
                        $data['grandTotal'] = $this->input->get('grandTotal');
                        $data['payment_type'] = $this->input->get('payment_type');
                        $data['image'] = $this->input->get('easyimge');
                        $data['myarray'] = json_decode($this->input->get('myarray'), true);
                        //$this->response($data);exit;
                        //$data['easyimage'] = $this->input->get('easyimge');
                        //$this->response($this->wm->order($data));exit;
                        if($this->wm->order($data)):
                            $this->response([
                                'message'=>'Ordered Successfully'
                            ]);
                            
                            else:
                                $this->response([
                                    'message'=>'Problem Inserting Order'
                                    ]);
                        endif;
                else:
                    $this->response(['message'=>'Select A Screenshot']);
                endif;
            else:
                        $data['ordered_by'] = $this->input->get('ordered_by');
                        $data['grandTotal'] = $this->input->get('grandTotal');
                        $data['payment_type'] = $this->input->get('payment_type');
                        //$data['image'] = $this->input->get('easyimge');
                        $data['myarray'] = json_decode($this->input->get('myarray'), true);
                        //$this->response($data);exit;
                        //$data['easyimage'] = $this->input->get('easyimge');
                        //$this->response($this->wm->order($data));exit;
                        if($this->wm->order_cod($data)):
                            $this->response([
                                'message'=>'Ordered Successfully'
                            ]);
                            
                            else:
                                $this->response([
                                    'message'=>'Problem Inserting Order'
                                    ]);
                        endif;
        endif;
    }
    
    
    //FETCHING PENDING ORDERS
    public function fetch_pending_orders_get(){
       // if(!empty($this->wm->fetch_pending_orders())):
            $this->response($this->wm->fetch_pending_orders());
        //endif;
    }
    

    //FETCH PENDING ORDER DETAILS
    public function fetch_pending_order_details_get(){
        $order_id = $this->input->get('order_id');
        $this->response($this->wm->fetch_pending_orders_details($order_id));
    }
    

    //MARK AS COMPLETED
    public function mark_completed_post(){
        $order_id = $this->input->get('order_id');
        if($this->wm->mark_completed($order_id)):
            $this->response([
                'message'=>'Delivered Successfully'
                ]);
                
                else:
                    $this->response([
                'message'=>'Some Error Occured'
                ]);
        endif;
    }
    
    //FETCH DELIVERED ORDERS
    public function fetch_delivered_orders_get(){
       // if(!empty($this->wm->fetch_pending_orders())):
            $this->response($this->wm->fetch_delivered_orders());
        //endif;
    }
    
    public function fetch_delivered_order_details_get(){
        $order_id = $this->input->get('order_id');
        $this->response($this->wm->fetch_delivered_orders_details($order_id));
    }
    
    
    public function login_driver_post(){
       $email = $this->input->get('email');
       $password = $this->input->get('password');
        
       if(!empty($this->wm->login_driver($email, $password))):
        $this->response([
            'message'=>'Login Successfull',
            'data'=>$this->wm->login_driver($email, $password)
        ]);

        else:
            $this->response([
                'message'=>'Incorrect Email / Password'
            ]); 
       endif;
    }
    
    public function count_all_get(){
        $count['delivered_count'] = $this->wm->count_delivered_orders();
        $count['pending_count'] = $this->wm->count_pending_orders();
        $this->response($count);
    }
    
    public function fetch_specific_user_orders_get(){
        $userid = $this->input->get('user_id');
        $this->response($this->wm->fetch_specific_user_orders($userid));
    }
    
    
    public function fetch_specific_user_order_details_get(){
        $order_id = $this->input->get('order_id');
        $this->response($this->wm->fetch_specific_user_orders_details($order_id));
    }

}

?>