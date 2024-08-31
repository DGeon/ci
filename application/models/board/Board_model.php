<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Board_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database('dgeon');
    }

    public function get_boardCount($searchMenu, $keyword){
        $this->db->like($searchMenu, $keyword, 'both');
        $query = $this->db->get('board');
        return $query->num_rows();
    }

    public function boardInsert($data){
        return $this->db->insert('board', $data);
    }

    public function get_boardList($searchMenu, $keyword, $start = null, $amount = null)
    {
        if ($start === null && $amount === null) {
            $this->db->like($searchMenu, $keyword, 'both', null);
            $this->db->order_by('bno', 'DESC');
            return $query = $this->db->get('board');
        }
        else{
            $this->db->like($searchMenu, $keyword, 'both', null);
            $this->db->order_by('bno', 'DESC');
            $this->db->limit($amount, $start);
            $query = $this->db->get('board');
            return $query->result_array();
        }
    }
    
    public function get_board_by_bno($bno){
        $this->db->where('bno', $bno);
        $query = $this->db->get('board');
        return $query->row_array();
    }

    public function boardUpdate($data){
        if($getBoard = $this->get_board_by_bno(($data['bno']))){
            $updateBoard = array(
                'title' => $data['title'],
                'content' => $data['content']
            );
            $updateWhere = array(
                'writer' => $data['writer'],
                'bno' => $getBoard['bno']
            );
            $this->db->where($updateWhere);
            $this->db->update('board', $updateBoard);
            return true;
        }else{
            return false;
        }
    }

    public function boardDelete($data){
        if($this->get_board_by_bno($data['bno'])){
            $this->db->where('bno', $data['bno']);
            $this->db->where('writer', $data['writer']);
            $this->db->delete('board');
            return true;
        }else{
            return false;
        }
    }
}
