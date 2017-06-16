<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Link Click Log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Popstate_stat_model extends CB_Model
{

    /**
     * 테이블명
     */
    public $_table = 'popstate_stat';

    /**
     * 사용되는 테이블의 프라이머리키
     */
    public $primary_key = 'ps_id'; // 사용되는 테이블의 프라이머리키

    function __construct()
    {
        parent::__construct();
    }


    public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
    {
        $select = 'popstate_stat.*,post.post_id,post.post_title';
        $join[] = array('table' => 'post', 'on' => 'popstate_stat.post_id = post.post_id', 'type' => 'inner');
        $result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

        return $result;
    }


    public function get_post_group_list($start_date = '', $end_date = '', $brd_id = 0)
    {
        if (empty($start_date) OR empty($end_date)) {
            return false;
        }
       
        $this->db->select('popstate_stat.*,post.post_id,post.post_title', false);
        
        
       
        $this->db->where('left(ps_datetime, 10) >=', $start_date);
        $this->db->where('left(ps_datetime, 10) <=', $end_date);
       
        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('post.brd_id', $brd_id);
        }
        
        
        $this->db->join('post', 'popstate_stat.post_id = post.post_id', 'inner');

        $this->db->group_by('popstate_stat.post_id');
        //$this->db->order_by('ps_datetime', $orderby);
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
        if($type==='domain'){
            $this->db->select('SUM(ps_cnt) as cnt,SUM(ps_hit) as hit_cnt, ps_domain as day ', false);
        } elseif($type==='week'){
            $this->db->select('SUM(ps_cnt) as cnt,SUM(ps_hit) as hit_cnt, WEEKDAY(ps_datetime) as day ', false);
        } elseif($type==='h'){
            $this->db->select('SUM(ps_cnt) as cnt,SUM(ps_hit) as hit_cnt, mid(ps_datetime,12,2) as day ', false);
        } else {
            $this->db->select('SUM(ps_cnt) as cnt,SUM(ps_hit) as hit_cnt, left(ps_datetime, ' . $left . ') as day ', false);
        }
        
        
        $this->db->where('left(ps_datetime, 10) >=', $start_date);
        $this->db->where('left(ps_datetime, 10) <=', $end_date);
        
        $brd_id = (int) $brd_id;
        if ($brd_id) {
            $this->db->where('post.brd_id', $brd_id);
        }

        if (!empty($skey)) {

            if(is_array($skey))
                $this->db->where_in('popstate_stat.post_id', $skey);
            else 
                $this->db->where('popstate_stat.post_id', $skey);
        }

        
        
        $this->db->join('post', 'popstate_stat.post_id = post.post_id', 'inner');

        $this->db->group_by('day');
        if($type==='domain') $this->db->order_by('cnt', $orderby);

        $qry = $this->db->get($this->_table);
        $result = $qry->result_array();

        return $result;
    }
}
