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
class Popstate extends CB_Controller
{
    

    /**
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'stat/popstate';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Popstate_stat','Popstate_list','Popstate_click_list', 'Board', 'Post','Post_link');   
    

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array');

    function __construct()
    {
        parent::__construct();

        /**
         * 라이브러리를 로딩합니다
         */
        $this->load->library(array('pagination', 'querystring'));

        /**
         * 로그인이 필요한 페이지입니다
         */
        required_user_login();
    }

    /**
     * 목록을 가져오는 메소드입니다
     */
    public function index()
    {
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_stat_popstate_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['is_admin'] = $is_admin = $this->member->is_admin();
        /**
         * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
         */
        $param =& $this->querystring;
        $page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
        $findex = $this->input->get('findex') ? $this->input->get('findex') : $this->Popstate_list_model->primary_key;
        $forder = $this->input->get('forder', null, 'desc');
        $sfield = $this->input->get('sfield', null, '');
        $skeyword = $this->input->get('skeyword', null, '');

        $per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
        $offset = ($page - 1) * $per_page;

        /**
         * 게시판 목록에 필요한 정보를 가져옵니다.
         */
        $this->Popstate_list_model->allow_search_field = array('post.post_title', 'post.post_id', 'post.post_md','popstate_list.pl_referer'); // 검색이 가능한 필드
        $this->Popstate_list_model->search_field_equal = array('post.post_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
        $this->Popstate_list_model->allow_order_field = array('pl_id'); // 정렬이 가능한 필드

        $where = array();
        if ($brdid = (int) $this->input->get('brd_id')) {
            $where['post.brd_id'] = $brdid;
        }

        $result = $this->Popstate_list_model
            ->get_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
        $list_num = $result['total_rows'] - ($page - 1) * $per_page;
        if (element('list', $result)) {
            foreach (element('list', $result) as $key => $val) {
                $brd_key = $this->board->item_id('brd_key', element('brd_id', $val));
                $result['list'][$key]['posturl'] = post_url($brd_key, element('post_id', $val));
                $result['list'][$key]['post_link'] = $this->Post_link_model->get_one(element('pln_id', $val));
                $result['list'][$key]['board'] = $board = $this->board->item_all(element('brd_id', $val));
                if ($board) {
                    $result['list'][$key]['boardurl'] = board_url(element('brd_key', $board));
                }
                if (element('pl_useragent', $val)) {
                    $userAgent = get_useragent_info(element('pl_useragent', $val));
                    $result['list'][$key]['browsername'] = $userAgent['browsername'];
                    $result['list'][$key]['browserversion'] = $userAgent['browserversion'];
                    $result['list'][$key]['os'] = $userAgent['os'];
                    $result['list'][$key]['engine'] = $userAgent['engine'];
                }
                $result['list'][$key]['num'] = $list_num--;
            }
        }

        $view['view']['data'] = $result;

        $view['view']['boardlist'] = $this->Board_model->get_board_list();

        /**
         * primary key 정보를 저장합니다
         */
        $view['view']['primary_key'] = $this->Popstate_list_model->primary_key;

        /**
         * 페이지네이션을 생성합니다
         */
        $config['base_url'] = site_url($this->pagedir) . '?' . $param->replace('page');
        $config['total_rows'] = $result['total_rows'];
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $view['view']['paging'] = $this->pagination->create_links();
        $view['view']['page'] = $page;

        /**
         * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
         */
        $search_option = array('post.post_title' => '언론사명', 'post.post_md' => 'MD 코드', 'popstate_list.pl_referer' => '유입 URL');
        $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        $view['view']['search_option'] = search_option($search_option, $sfield);
        $view['view']['listall_url'] = site_url($this->pagedir);
        $view['view']['list_delete_url'] = site_url($this->pagedir . '/listdelete/?' . $param->output());

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */

        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'stat',
            'layout' => 'layout',
            'skin' => 'stat',
            'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );

        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 그래프 형식으로 보는 페이지입니다
     */
    public function realgraph($export = '')
    {
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_stat_popstate_graph';
        $this->load->event($eventname);

        

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['is_admin'] = $is_admin = $this->member->is_admin();
        $param =& $this->querystring;
        
        $datetype = 'h';
        
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : cdate('Y-m-d', strtotime('-1 months'));;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : cdate('Y-m-d');
        if ($datetype === 'y' OR $datetype === 'm') {
            $start_year = substr($start_date, 0, 4);
            $end_year = substr($end_date, 0, 4);
        }
        if ($datetype === 'm') {
            $start_month = substr($start_date, 5, 2);
            $end_month = substr($end_date, 5, 2);
            $start_year_month = $start_year * 12 + $start_month;
            $end_year_month = $end_year * 12 + $end_month;
        }

        $view['view']['start_date'] = $start_date;
        $view['view']['end_date'] = $end_date;
        $view['view']['datetype'] = $datetype;

        if ($datetype === 'h' && $this->input->get('datetime')) {
            $start_date = $this->input->get('datetime') ? $this->input->get('datetime') : cdate('Y-m-d');
            $end_date = cdate('Y-m-d', strtotime($start_date));
        }
        if ($datetype === 'i') {
            $start_date = $this->input->get('datetime') ? $this->input->get('datetime') : cdate('Y-m-d');
            $end_date = cdate('Y-m-d', strtotime($start_date.'+1 hour'));
        }
        $orderby = (strtolower($this->input->get('orderby')) === 'desc') ? 'desc' : 'asc';

        $brd_id = $this->input->get('brd_id', null, '');

        $this->Popstate_list_model->allow_search_field = array('ps_id', 'post.post_title', 'post.post_md'); // 검색이 가능한 필드
        $this->Popstate_list_model->search_field_equal = array('ps_id', 'post.post_title', 'post.post_md'); // 검색중 like 가 

        $skey = $this->input->get('post_id_', null, '');
        

        $result = $this->Popstate_list_model->get_link_click_count($datetype, $start_date, $end_date, $brd_id, $orderby, $skey);

        $result_click = $this->Popstate_click_list_model->get_link_click_count($datetype, $start_date, $end_date, $brd_id, $orderby, $skey);

        
        $sum_count = 0;
        $hit_sum_count = 0;
        $arr = array();
        $max = 0;

        if ($result && is_array($result)) {
            foreach ($result as $key => $value) {

                
                    $s = element('day', $value);

                if ( ! isset($arr[$s])) {
                    $arr[$s]['cnt'] = 0;
                    $arr[$s]['hit_cnt'] = 0;
                }
                $arr[$s]['cnt'] += element('cnt', $value);
                
                $arr[$s]['hit_cnt'] += element('cnt', element($key, $result_click));

                if ($arr[$s]['cnt'] > $max) {
                    $max = $arr[$s]['cnt'];
                }
                $sum_count += element('cnt', $value);
                $hit_sum_count += element('cnt', element($key, $result_click));
            }
        }

        $result = array();
        $i = 0;
        $save_count = -1;
        $tot_count = 0;

        if (count($arr)) {
            foreach ($arr as $key => $value) {
                $count = (int) $arr[$key]['cnt'];
                $hit_count = (int) $arr[$key]['hit_cnt'];

                $result[$key]['count'] = $count;
                $result[$key]['hit_count'] = $hit_count;
                $i++;
                if ($save_count !== $count) {
                    $no = $i;
                    $save_count = $count;
                }
                $result[$key]['no'] = $no;

                $result[$key]['key'] = $key;
                $rate = ($count / $sum_count * 100);
                $result[$key]['rate'] = $rate;
                $s_rate = number_format($rate, 1);
                $result[$key]['s_rate'] = $s_rate;

                $bar = (int)($count / $max * 100);
                $result[$key]['bar'] = $bar;
            }
            $view['view']['max_value'] = $max;
            $view['view']['sum_count'] = $sum_count;
            $view['view']['hit_sum_count'] = $hit_sum_count;
        }

        if ($datetype === 'y') {
            for ($i = $start_year; $i <= $end_year; $i++) {
                if( ! isset($result[$i])) $result[$i] = '';
            }
        } elseif ($datetype === 'm') {
            for ($i = $start_year_month; $i <= $end_year_month; $i++) {
                $year = floor($i / 12);
                if ($year * 12 == $i) $year--;
                $month = sprintf("%02d", ($i - ($year * 12)));
                $date = $year . '-' . $month;
                if( ! isset($result[$date])) $result[$date] = '';
            }
        } elseif ($datetype === 'd') {
            $date = $start_date;
            while ($date <= $end_date) {
                if( ! isset($result[$date])) $result[$date] = '';
                $date = cdate('Y-m-d', strtotime($date) + 86400);
            }
        } elseif ($datetype === 'h') {

            $date = $start_date;
            $i=0;
            while ($date < cdate('Y-m-d',strtotime($end_date))) {
            $i++;
            if($i > 24)  break;
                if( ! isset($result[cdate('H', strtotime($date))])) $result[cdate('H', strtotime($date))] = '';
                $date = cdate('Y-m-d His', strtotime($date) + 3600);
            }
        } elseif ($datetype === 'i') {

            $date = $start_date;
            $i=0;
            while ($date < cdate('Y-m-d',strtotime($end_date))) {
            $i++;
            if($i > 30)  break;
                if( ! isset($result[cdate('H', strtotime($date))])) $result[cdate('H', strtotime($date))] = '';
                $date = cdate('Y-m-d His', strtotime($date) + 3600);
                echo $date."<br>";
            }
        }


        if ($orderby === 'desc') {
            krsort($result);
        } else {
            ksort($result);
        }

        $view['view']['list'] = $result;

        
        

        $view['view']['boardlist'] = $this->Board_model->get_board_list();
        
        
        $brd_id = $this->input->get('brd_id', null, '');

        $post_result = $this->Popstate_list_model
            ->get_post_group_list($start_date, $end_date, $brd_id);

            
        


        $postlist="";
        foreach($post_result as $value){
            $postlist[$value['brd_id']][$value['post_id']] = array('post_id' => $value['post_id'],'post_title' => $value['post_title']);
        }

        $view['view']['postlist'] = $postlist;
        // $search_option = array('post.post_title' => '언론사명', 'post.post_md' => 'MD 코드');
        // $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        // $view['view']['search_option'] = search_option($search_option, $sfield);

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        if ($export === 'excel') {
            
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=후팝업_' . cdate('Y_m_d') . '.xls');
            echo $this->load->view('/stat/bootstrap/graph_excel', $view, true);

        } else {
            /**
             * 레이아웃을 정의합니다
             */

            $page_title = $this->cbconfig->item('site_meta_title_main');
            $meta_description = $this->cbconfig->item('site_meta_description_main');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
            $meta_author = $this->cbconfig->item('site_meta_author_main');
            $page_name = $this->cbconfig->item('site_page_name_main');

            $layoutconfig = array(
                'path' => 'stat',
                'layout' => 'layout',
                'skin' => 'realgraph',
                'layout_dir' => $this->cbconfig->item('layout_main'),
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
                'use_sidebar' => $this->cbconfig->item('sidebar_main'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
                'skin_dir' => $this->cbconfig->item('skin_main'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));
        }
    }

    public function graph($export = '')
    {
        
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_stat_popstate_graph';
        $this->load->event($eventname);

        

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);
        $view['view']['is_admin'] = $is_admin = $this->member->is_admin();
        $param =& $this->querystring;
        $datetype = $this->input->get('datetype', null, 'd');
        
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : cdate('Y-m-d', strtotime('-1 months'));;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : cdate('Y-m-d');
        if ($datetype === 'y' OR $datetype === 'm') {
            $start_year = substr($start_date, 0, 4);
            $end_year = substr($end_date, 0, 4);
        }
        if ($datetype === 'm') {
            $start_month = substr($start_date, 5, 2);
            $end_month = substr($end_date, 5, 2);
            $start_year_month = $start_year * 12 + $start_month;
            $end_year_month = $end_year * 12 + $end_month;
        }

        $view['view']['start_date'] = $start_date;
        $view['view']['end_date'] = $end_date;
        $view['view']['datetype'] = $datetype;

        if ($datetype === 'h' && $this->input->get('datetime')) {
            $start_date = $this->input->get('datetime') ? $this->input->get('datetime') : cdate('Y-m-d');
            $end_date = cdate('Y-m-d', strtotime($start_date));
        }
       
        $orderby = (strtolower($this->input->get('orderby')) === 'desc') ? 'desc' : 'asc';
        if($datetype==='domain') $orderby='desc';

        $brd_id = $this->input->get('brd_id', null, '');

        $this->Popstate_stat_model->allow_search_field = array('ps_id', 'post.post_title', 'post.post_md'); // 검색이 가능한 필드
        $this->Popstate_stat_model->search_field_equal = array('ps_id', 'post.post_title', 'post.post_md'); // 검색중 like 가 

        $skey = $this->input->get('post_id_', null, '');
        

        $result = $this->Popstate_stat_model->get_link_click_count($datetype, $start_date, $end_date, $brd_id, $orderby, $skey);

        $week_korean = array('월', '화', '수', '목', '금', '토', '일');
        $sum_count = 0;
        $hit_sum_count = 0;
        $arr = array();
        $max = 0;

        if ($result && is_array($result)) {
            foreach ($result as $key => $value) {

                if (element('day', $value) === '-') 
                    $s = '직접';
                else 
                    $s = element('day', $value);

                
                if ( ! isset($arr[$s])) {
                    $arr[$s]['cnt'] = 0;
                    $arr[$s]['hit_cnt'] = 0;
                }
                $arr[$s]['cnt'] += element('cnt', $value);
                $arr[$s]['hit_cnt'] += element('hit_cnt', $value);
                if ($arr[$s]['cnt'] > $max) {
                    $max = $arr[$s]['cnt'];
                }
                $sum_count += element('cnt', $value);
                $hit_sum_count += element('hit_cnt', $value);
            }
        }

        $result = array();
        $i = 0;
        $save_count = -1;
        $tot_count = 0;

        if (count($arr)) {
            foreach ($arr as $key => $value) {
                $count = (int) $arr[$key]['cnt'];
                $hit_count = (int) $arr[$key]['hit_cnt'];
                $result[$key]['count'] = $count;
                $result[$key]['hit_count'] = $hit_count;
                $i++;
                if ($save_count !== $count) {
                    $no = $i;
                    $save_count = $count;
                }
                $result[$key]['no'] = $no;

                $result[$key]['key'] = $key;
                $rate = ($count / $sum_count * 100);
                $result[$key]['rate'] = $rate;
                $s_rate = number_format($rate, 1);
                $result[$key]['s_rate'] = $s_rate;

                $bar = (int)($count / $max * 100);
                $result[$key]['bar'] = $bar;
            }
            $view['view']['max_value'] = $max;
            $view['view']['sum_count'] = $sum_count;
            $view['view']['hit_sum_count'] = $hit_sum_count;
            $view['view']['week_korean'] = $week_korean;
        }

        if ($datetype === 'y') {
            for ($i = $start_year; $i <= $end_year; $i++) {
                if( ! isset($result[$i])) $result[$i] = '';
            }
        } elseif ($datetype === 'm') {
            for ($i = $start_year_month; $i <= $end_year_month; $i++) {
                $year = floor($i / 12);
                if ($year * 12 == $i) $year--;
                $month = sprintf("%02d", ($i - ($year * 12)));
                $date = $year . '-' . $month;
                if( ! isset($result[$date])) $result[$date] = '';
            }
        } elseif ($datetype === 'd') {
            $date = $start_date;
            while ($date <= $end_date) {
                if( ! isset($result[$date])) $result[$date] = '';
                $date = cdate('Y-m-d', strtotime($date) + 86400);
            }
        } elseif ($datetype === 'h') {

            $date = $start_date;
            $i=0;
            while ($date < cdate('Y-m-d',strtotime($end_date))) {
            $i++;
            if($i > 24)  break;
                if( ! isset($result[cdate('H', strtotime($date))])) $result[cdate('H', strtotime($date))] = '';
                $date = cdate('Y-m-d His', strtotime($date) + 3600);
            }
        } elseif ($datetype === 'i') {

            $date = $start_date;
            $i=0;
            while ($date < cdate('Y-m-d',strtotime($end_date))) {
            $i++;
            if($i > 30)  break;
                if( ! isset($result[cdate('H', strtotime($date))])) $result[cdate('H', strtotime($date))] = '';
                $date = cdate('Y-m-d His', strtotime($date) + 3600);
                echo $date."<br>";
            }
        }


        if($datetype!=='domain'){
            if ($orderby === 'desc') {
                krsort($result);
            } else {
                ksort($result);
            }
        }
        $view['view']['list'] = $result;

        
        

        $view['view']['boardlist'] = $this->Board_model->get_board_list();
        
        
        $brd_id = $this->input->get('brd_id', null, '');

        $post_result = $this->Popstate_stat_model
            ->get_post_group_list($start_date, $end_date, $brd_id);

            
        


        $postlist="";
        foreach($post_result as $value){
            $postlist[$value['brd_id']][$value['post_id']] = array('post_id' => $value['post_id'],'post_title' => $value['post_title']);
        }

        $view['view']['postlist'] = $postlist;
        // $search_option = array('post.post_title' => '언론사명', 'post.post_md' => 'MD 코드');
        // $view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
        // $view['view']['search_option'] = search_option($search_option, $sfield);

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        if ($export === 'excel') {
            
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename=후팝업_' . cdate('Y_m_d') . '.xls');
            echo $this->load->view('/stat/bootstrap/graph_excel', $view, true);

        } else {
            /**
             * 레이아웃을 정의합니다
             */

            $page_title = $this->cbconfig->item('site_meta_title_main');
            $meta_description = $this->cbconfig->item('site_meta_description_main');
            $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
            $meta_author = $this->cbconfig->item('site_meta_author_main');
            $page_name = $this->cbconfig->item('site_page_name_main');

            $layoutconfig = array(
                'path' => 'stat',
                'layout' => 'layout',
                'skin' => 'graph',
                'layout_dir' => $this->cbconfig->item('layout_main'),
                'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
                'use_sidebar' => $this->cbconfig->item('sidebar_main'),
                'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
                'skin_dir' => $this->cbconfig->item('skin_main'),
                'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
                'page_title' => $page_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'meta_author' => $meta_author,
                'page_name' => $page_name,
            );
            $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
            $this->data = $view;
            $this->layout = element('layout_skin_file', element('layout', $view));
            $this->view = element('view_skin_file', element('layout', $view));
        }
    }

    /**
     * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
     */
    public function listdelete()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_stat_popstate_listdelete';
        $this->load->event($eventname);

        // 이벤트가 존재하면 실행합니다
        Events::trigger('before', $eventname);

        /**
         * 체크한 게시물의 삭제를 실행합니다
         */
        if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
            foreach ($this->input->post('chk') as $val) {
                if ($val) {
                    $this->Popstate_list_model->delete($val);
                }
            }
        }

        // 이벤트가 존재하면 실행합니다
        Events::trigger('after', $eventname);

        /**
         * 삭제가 끝난 후 목록페이지로 이동합니다
         */
        $this->session->set_flashdata(
            'message',
            '정상적으로 삭제되었습니다'
        );
        $param =& $this->querystring;
        $redirecturl = site_url($this->pagedir . '?' . $param->output());

        redirect($redirecturl);
    }

    /**
     * 오래된 후팝업로그삭제 페이지입니다
     */
    public function cleanlog()
    {

        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_stat_popstate_cleanlog';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        $view['view']['is_admin'] = $is_admin = $this->member->is_admin();
        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'day',
                'label' => '기간',
                'rules' => 'trim|required|numeric|is_natural',
            ),
        );
        $this->form_validation->set_rules($config);

        /**
         * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
         * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
         */
        if ($this->form_validation->run() === false) {

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

        } else {
            /**
             * 유효성 검사를 통과한 경우입니다.
             * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
             */

            // 이벤트가 존재하면 실행합니다
            $view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

            if ($this->input->post('criterion') && ($this->input->post('day') || (int)$this->input->post('day') === 0)) {
                $deletewhere = array(
                    'pl_datetime <=' => $this->input->post('criterion'),
                );
                $this->Popstate_list_model->delete_where($deletewhere);
                $view['view']['alert_message'] = '총 ' . number_format($this->input->post('log_count')) . ' 건의 ' . $this->input->post('day') . '일 이상된 로그가 모두 삭제되었습니다';
            } else {
                $criterion = cdate('Y-m-d H:i:s', ctimestamp() - $this->input->post('day') * 24 * 60 * 60);
                $countwhere = array(
                    'pl_datetime <=' => $criterion,
                );
                $log_count = $this->Popstate_list_model->count_by($countwhere);
                $view['view']['criterion'] = $criterion;
                $view['view']['day'] = $this->input->post('day');
                $view['view']['log_count'] = $log_count;
                if ($log_count > 0) {
                    $view['view']['msg'] = '총 ' . number_format($log_count) . ' 건의 ' . $this->input->post('day') . '일 이상된 후팝업로그가 발견되었습니다. 이를 모두 삭제하시겠습니까?';
                } else {
                    $view['view']['alert_message'] = $this->input->post('day') . '일 이상된 후팝업로그가 발견되지 않았습니다';
                }
            }
        }

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 레이아웃을 정의합니다
         */

        $page_title = $this->cbconfig->item('site_meta_title_main');
        $meta_description = $this->cbconfig->item('site_meta_description_main');
        $meta_keywords = $this->cbconfig->item('site_meta_keywords_main');
        $meta_author = $this->cbconfig->item('site_meta_author_main');
        $page_name = $this->cbconfig->item('site_page_name_main');

        $layoutconfig = array(
            'path' => 'stat',
            'layout' => 'layout',
            'skin' => 'cleanlog',
            'layout_dir' => $this->cbconfig->item('layout_main'),
            'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_main'),
            'use_sidebar' => $this->cbconfig->item('sidebar_main'),
            'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_main'),
            'skin_dir' => $this->cbconfig->item('skin_main'),
            'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_main'),
            'page_title' => $page_title,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'meta_author' => $meta_author,
            'page_name' => $page_name,
        );
        $view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));

    }

    public function migration(){

        
        // 이벤트 라이브러리를 로딩합니다

       // $result = $this->Popstate_list_model->migration();
        
        // foreach($result as $value){
        //     $this->Popstate_stat_model->replace($value);
        // }

        $criterion = cdate('Y-m-d H:i:s', strtotime(cdate('Y-m-d H').'0000'.'-1 hour'));

        echo $criterion;
        $deletewhere = array(
                    'pl_datetime <=' => $criterion,
                );

       // $result = $this->Popstate_list_model->delete_where($deletewhere);

    }
}
