<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Link Click Log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)  
 */

class Popstate_click_list_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'popstate_click_list';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'pc_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'popstate_click_list.*,post.post_id,post.post_title';
        $join[] = array('table' => 'post', 'on' => 'popstate_click_list.post_id = post.post_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }


    public function get_post_group_list($start_date = '', $end_date = '', $brd_id = 0)
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
       
        $this->db->select('popstate_click_list.*,post.post_id,post.post_title', false);
        
        
       
        $this->db->where('left(pc_datetime, 10) >=', $start_date);
        $this->db->where('left(pc_datetime, 10) <=', $end_date);
       
        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('post.brd_id', $brd_id);
        }
        
        
        $this->db->join('post', 'popstate_click_list.post_id = post.post_id', 'inner');

        $this->db->group_by('popstate_click_list.post_id');
        //$this->db->order_by('pc_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }


    public function get_link_click_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0, $orderby = 'asc', $skey = '')
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
        $left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
        if (strtolower($orderby) !== 'desc') $orderby = 'asc';
        if($type==='h'){
            $this->db->select('count(*) as cnt, mid(pc_datetime,12,2) as day ', false);
        } else {
            $this->db->select('count(*) as cnt, left(pc_datetime, ' . $left . ') as day ', false);
        }
        
        if($type === 'i'){
            $this->db->where('left(pc_datetime, 13) >=', $start_date);
            $this->db->where('left(pc_datetime, 13) <=', $end_date);
        } else {
            $this->db->where('left(pc_datetime, 10) >=', $start_date);
            $this->db->where('left(pc_datetime, 10) <=', $end_date);
        }
        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('post.brd_id', $brd_id);
        }

        if (!empty($skey)) {

            if(is_array($skey))
                $this->db->where_in('popstate_click_list.post_id', $skey);
            else 
                $this->db->where('popstate_click_list.post_id', $skey);
        }

        
        
        $this->db->join('post', 'popstate_click_list.post_id = post.post_id', 'inner');

        $this->db->group_by('day');
        $this->db->order_by('pc_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }

    public function migration()
    {   
        
        $this->db->select('post_id,brd_id,count(*) as ps_cnt,SUM(pc_hit) as ps_hit, left(pc_datetime, 13) as ps_datetime,pc_domain as ps_domain ', false);
        $groupby= array('post_id','ps_datetime','ps_domain');
        $this->db->group_by($groupby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();
        

        return $result;
    }
}
