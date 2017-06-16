<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sample_scheduler class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 스케쥴러를 통해 실행되는 샘플 class 입니다.
 */
class Popstate_scheduler extends CI_Controller
{
    private $CI;

    function __construct()
    {   
        $this->CI = & get_instance();
    }

    public function scheduler()
    {
        $ip = $this->CI->input->ip_address();
        log_message('debug', $ip . '에서 Popstate_scheduler 가 실행되었습니다.');

        
        $this->CI->load->model('Popstate_list_model');
        $this->CI->load->model('Popstate_stat_model');

        $result = $this->CI->Popstate_list_model
            ->migration();
        
        foreach($result as $value){
            $this->CI->Popstate_stat_model->replace($value);
        }
    }
}
