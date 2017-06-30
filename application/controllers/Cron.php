<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bannerclick class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>페이지설정>배너 클릭 controller 입니다.
 */
class Cron extends CB_Controller {

 

    protected $models = array('Popstate_stat','Popstate_list','Popstate_click_list');   

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        

        /**
         * 로그인이 필요한 페이지입니다
         */
        
    }

    public function migration(){

        
        // 이벤트 라이브러리를 로딩합니다

        $result = $this->Popstate_list_model
            ->migration();
        
        
         foreach($result as $value){
            
             $this->Popstate_stat_model->replace($value);
         }

        $criterion = cdate('Y-m-d H:i:s', strtotime(cdate('Y-m-d H').'0000'.'-1 hour'));

        $deletewhere = array(
                    'pl_datetime <=' => $criterion,
                );

        $result = $this->Popstate_list_model->delete_where($deletewhere);

        $deletewhere = array(
                    'pc_datetime <=' => $criterion,
                );

        $result = $this->Popstate_click_list_model->delete_where($deletewhere);

        echo $result;
    }

}
