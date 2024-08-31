<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->database('dgeon');
    }

    public function get_user_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('member');
        return $query->row_array();
    }

    public function insert_member($user){
        return $this->db->insert('member', $user);
    }

    public function update_member($user){
        if($getUser = $this->get_user_by_id($user['id'])){
            if(password_verify($user['password'], $getUser['password'])){
                $updateUser = array(
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone']
                );
                $this->db->where('id', $user['id']);
                $this->db->update('member', $updateUser);
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function delete_member($id, $password){
        $getUser = $this->get_user_by_id($id);
        if($getUser){
            if(password_verify($password, $getUser['password'])){
                $this->db->where('id', $id);
                $this->db->delete('member');
                return true;
            }else{
                return false;
            }
        }else{
            return $id;
        }
    }
}