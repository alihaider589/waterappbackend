<?php 

require APPPATH . '/libraries/REST_Controller.php';
class WaterApi extends REST_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('WaterModel', 'wm');
    }

    public function check_get(){
        $this->response($this->input->get(), 200);
    }

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

    public function fetch_products_get(){
        if(!empty($this->wm->fetch_products())):
            $this->response($this->wm->fetch_products(),200);
        endif;
    }

    public function order_post(){
        if($this->wm->order($this->input->get())):
            $this->response([
                'message'=>'Ordered Successfully'
            ]);
        endif;
    }

}

?>