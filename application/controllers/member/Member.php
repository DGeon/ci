<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Member_model');
    }

    public function login()
    {

        if ($this->input->server('REQUEST_METHOD') === 'POST') {


            $id = $this->input->post('id', false);
            $password = $this->input->post('password', false);

            $user = $this->Member_model->get_user_by_id($id);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $response['id'] = $user['id'];
                    $response['msg'] = "로그인에 성공하셨습니다.";
                    $this->load->helper('cookie');
                    $cookie = array(
                        'name'   => 'id',
                        'value'  => $user['id'],
                        'expire' => 3600,
                        'secure' => FALSE
                    );
                    $this->input->set_cookie($cookie);
                } else {
                    $response['msg'] = "비밀번호가 잘못되었습니다.";
                }
            } else {
                $response['msg'] = "아이디가 존재하지 않습니다.";
            }
            echo json_encode($response);
        } else if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $this->load->view('member/login'); //Views에 있는 파일을 Load함
        }
    }
    public function register()
    {
        $this->load->view('member/signup');
        if($this->input->server("REQUEST_METHOD") === "POST"){
            $id = $this->input->post('id',false);
            $password = password_hash($this->input->post('password',false), PASSWORD_DEFAULT);
            $name = $this->input->post('name',false);
            $email = $this->input->post('email',false);
            $phone = $this->input->post('phone',false);
            $user = array(
                'id' => $id,
                'password' => $password,
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            );
            if($this->Member_model->insert_member($user)){
                $this->load->helper('url');
                redirect("login");
            }else{
                redirect('member/register');
            }
        }
        
    }
    public function idcheck (){
        $id = $this->input->post('id', false);
        $idcheck = $this->Member_model->get_user_by_id($id);
        if($idcheck){
            $response = array(
                'status' => 1,
                'msg' => '동일한 아이디가 존재 합니다.'
            );
        }else{
            $response = array(
                'status' => 0,
                'msg' => '사용가능한 아이디 입니다.'
            );
        }
        echo json_encode($response);
    }
    public function mypage()
    {
        $id = $_COOKIE['id'];
        $user = $this->Member_model->get_user_by_id($id);
        if($user){
            $this->load->view('member/mypage', ['row' => $user]);
        }else{
            $this->load->helper('cookie');
            delete_cookie('id');
            $this->load->helper('url');
            redirect("");
        }    
    }
    public function memberUpdate(){
        $id = $this->input->post('id',false);
        $password = $this->input->post('password',false);
        $name = $this->input->post('name',false);
        $email = $this->input->post('email',false);
        $phone = $this->input->post('phone',false);
        $user = array(
            'id' => $id,
            'password' => $password,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        );
        if($this->Member_model->update_member($user)){
            $this->load->helper('cookie');
            delete_cookie('id');
            $response = array(
                'msg' => "회원정보 수정이 완료 되었습니다. 다시 로그인 해주세요"
            );
           
        }else{
            $response = array(
                'msg' => "회원정보 수정이 실패 했습니다.비밀번호를 확인해주세요."
            );
        }
        echo json_encode($response);
    }
    public function memberDelete(){
        
        $id = $this->input->post('id',false);
        $password = $this->input->post('password', false);

        if($this->Member_model->delete_member($id, $password)){
            $this->load->helper('cookie');
            delete_cookie('id');
            $response = array(
                'msg' => "탈퇴가 완료 되었습니다."
            );
        }else{
            $response = array(
                'msg' => "회원정보가 다릅니다. 확인해주세요"
            );
        }
        echo json_encode($response);
    }
    public function logout()
    {
        $this->load->helper('cookie');
        delete_cookie('id');
        $this->load->helper('url');
        redirect("");
    }
}
