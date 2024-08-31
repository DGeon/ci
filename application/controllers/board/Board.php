<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Board extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('board/Board_model');
    }

    public function list()
    {
        $keyword = $this->input->get('keyword', TRUE) ?: '';
        $searchMenu = $this->input->get('searchMenu', TRUE) ?: 'title';
        $pageNum = $this->input->get('pageNum', TRUE) ?: 1;
        $amount = 5;
        $pageNation = 3;

        $total = $this->Board_model->get_boardCount($searchMenu, $keyword);
        $totalPage = ceil($total / $amount);

        $start = ($pageNum - 1) * $amount;
        $totalBlock = ceil($totalPage / $pageNation);
        $nowBlock = ceil($pageNum / $pageNation);

        $s_pageNum = ($nowBlock - 1) * $pageNation + 1;
        if ($s_pageNum <= 0) {
            $s_pageNum = 1;
        }
        $e_pageNum = $nowBlock * $pageNation;
        if ($e_pageNum > $totalPage) {
            $e_pageNum = $totalPage;
        }
        $rows = $this->Board_model->get_boardList($searchMenu, $keyword, $start, $amount);
        $data = [
            'rows' => $rows,
            's_pageNum' => $s_pageNum,
            'e_pageNum' => $e_pageNum,
            'totalPage' => $totalPage,
            'pageNum' => $pageNum,
            'totalBlock' => $totalBlock,
            'searchMenu' => $searchMenu,
            'keyword' => $keyword,
        ];
        $this->load->view('board/board', $data);
    }

    public function boardRegister()
    {
        $this->load->view('board/write');
        if ($this->input->server("REQUEST_METHOD") === "POST") {
            $title = $this->input->post('title', true) ?: null;
            $writer = $_COOKIE['id'] ?: null;
            $content = $this->input->post('content', true) ?: null;
            if ($title && $writer && $content != null) {
                $data = array(
                    'title' => $title,
                    'writer' => $writer,
                    'content' => $content
                );
                if ($this->Board_model->boardInsert($data)) {
                    $this->load->helper('url');
                    redirect('board');
                }
            }
        }
    }

    public function boardDetail()
    {
        $bno = $this->input->get('bno', true) ?: null;
        $result = $this->Board_model->get_board_by_bno($bno);
        if ($result) {
            $this->load->view('board/detail', ['row' => $result]);
        }
    }

    public function boardUpdate()
    {
        $bno = $this->input->post('bno', true) ?: null;
        $title = $this->input->post('title', true) ?: null;
        $writer = $this->input->post('writer', true) ?: null;
        $content = $this->input->post('content', true) ?: null;
        if ($title && $writer && $content != null) {
            $data = array(
                'bno' => $bno,
                'title' => $title,
                'writer' => $writer,
                'content' => $content,
            );
            $result = $this->Board_model->boardUpdate($data);
            if ($result) {
                $response = array(
                    'msg' => '수정이 완료 되었습니다.'
                );
            } else {
                $response = array(
                    'msg' => '수정에 실패 했습니다.'
                );
            }
        } else {
            $response = array(
                'msg' => '빈칸은 입력이 불가능 합니다.'
            );
        }
        echo json_encode($response);
    }

    public function boardDelete()
    {

        $bno = $this->input->post('bno');
        $writer = $this->input->post('writer');
        $result = $this->Board_model->get_board_by_bno($bno);
        if ($result['writer'] === $writer) {
            $data = array(
                'bno' => $bno,
                'writer' => $writer
            );
            if ($this->Board_model->boardDelete($data)) {
                $response = array(
                    'msg' => "게시물이 삭제 되었습니다."
                );
            }
        } else {
            $response = array(
                'msg' => "게시물 삭제가 실패하였습니다."
            );
        }
        echo json_encode($response);
    }
}
