<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Link Click Log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)  
 */

class Popstate_list_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'popstate_list';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'pl_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'popstate_list.*,post.post_id,post.post_title';
        $join[] = array('table' => 'post', 'on' => 'popstate_list.post_id = post.post_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }


    public function get_post_group_list($start_date = '', $end_date = '', $brd_id = 0)
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
       
        $this->db->select('popstate_list.*,post.post_id,post.post_title', false);
        
        
       
        $this->db->where('left(pl_datetime, 10) >=', $start_date);
        $this->db->where('left(pl_datetime, 10) <=', $end_date);
       
        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('post.brd_id', $brd_id);
        }
        
        
        $this->db->join('post', 'popstate_list.post_id = post.post_id', 'inner');

        $this->db->group_by('popstate_list.post_id');
        //$this->db->order_by('pl_datetime', $orderby);
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
            $this->db->select('count(*) as cnt, mid(pl_datetime,12,2) as day ', false);
        } else {
            $this->db->select('count(*) as cnt, left(pl_datetime, ' . $left . ') as day ', false);
        }
        
        if($type === 'i'){
            $this->db->where('left(pl_datetime, 13) >=', $start_date);
            $this->db->where('left(pl_datetime, 13) <=', $end_date);
        } else {
            $this->db->where('left(pl_datetime, 10) >=', $start_date);
            $this->db->where('left(pl_datetime, 10) <=', $end_date);
        }
        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('post.brd_id', $brd_id);
        }

        if (!empty($skey)) {

            if(is_array($skey))
                $this->db->where_in('popstate_list.post_id', $skey);
            else 
                $this->db->where('popstate_list.post_id', $skey);
        }

        
        
        $this->db->join('post', 'popstate_list.post_id = post.post_id', 'inner');

        $this->db->group_by('day');
        $this->db->order_by('pl_datetime', $orderby);
        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }

    public function migration()
    {   
        
        $qry = $this->db->query('SELECT A.post_id,A.brd_id,A.ps_datetime,A.ps_domain,A.ps_cnt,B.ps_hit from (SELECT post_id,brd_id,count(*) as ps_cnt, left(pl_datetime, 13) as ps_datetime,pl_domain as ps_domain from cb_popstate_list group by post_id,ps_datetime,ps_domain) A LEFT JOIN (SELECT post_id,brd_id,count(*) as ps_hit, left(pc_datetime, 13) as ps_datetime,pc_domain as ps_domain from cb_popstate_click_list group by post_id,ps_datetime,pc_domain) B on A.post_id=B.post_id and A.ps_datetime = B.ps_datetime and A.ps_domain = B.ps_domain ORDER BY A.ps_datetime ASC ');
        $result = $qry->result_array();
        

        return $result;
    }
}


