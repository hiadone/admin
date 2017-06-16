<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmallconfig class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>컨텐츠몰관리>컨텐츠몰환경설정 controller 입니다.
 */
class Cmallconfig extends CB_Controller
{

    /**
     * 관리자 페이지 상의 현재 디렉토리입니다
     * 페이지 이동시 필요한 정보입니다
     */
    public $pagedir = 'cmall/cmallconfig';

    /**
     * 모델을 로딩합니다
     */
    protected $models = array('Cmall_config', 'Config');

    /**
     * 이 컨트롤러의 메인 모델 이름입니다
     */
    protected $modelname = 'Cmall_config_model';

    /**
     * 헬퍼를 로딩합니다
     */
    protected $helpers = array('form', 'array', 'cmall');

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 컨텐츠몰환경설정>기본설정 페이지입니다
     */
    public function index()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_index';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'use_cmall',
                'label' => '컨텐츠몰 기능사용',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_name',
                'label' => '컨텐츠몰명',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'use_cmall_deposit_to_contents',
                'label' => '예치금으로 상품구매 가능',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_cart_keep_days',
                'label' => '장바구니 보관기간',
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

            $array = array(
                'use_cmall', 'cmall_name', 'use_cmall_deposit_to_contents', 'cmall_cart_keep_days'
            );
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }

            $this->Cmall_config_model->save($savedata);
            $view['view']['alert_message'] = '기본설정이 저장되었습니다';
        }

        $getdata = $this->Cmall_config_model->get_all_meta();
        if ( ! isset($getdata['cmall_name'])) {

            $initdata['cmall_name'] = '컨텐츠몰';
            $initdata['use_cmall_deposit_to_contents'] = '1';
            $initdata['cmall_cart_keep_days'] = '14';
            $initdata['access_cmall_buy'] = '1';
            $initdata['use_cmall_product_dhtml'] = '1';
            $initdata['cmall_product_editor_type'] = 'smarteditor';
            $initdata['use_cmall_product_review_dhtml'] = '1';
            $initdata['cmall_product_review_editor_type'] = 'smarteditor';
            $initdata['use_cmall_product_qna_dhtml'] = '1';
            $initdata['cmall_product_qna_editor_type'] = 'smarteditor';
            $initdata['cmall_sendcont_admin_cash_to_contents']
                = '컨텐츠몰 - {고객명}님
주문번호 : {주문번호}
주문금액 : {주문금액}
결제완료';
            $initdata['cmall_sendcont_user_cash_to_contents']
                = '안녕하세요 {고객명}님
주문번호 : {주문번호}
주문금액 : {주문금액}
결제완료, 감사합니다 - {회사명}';
            $initdata['cmall_sendcont_admin_bank_to_contents']
                = '무통장입금요청 - {고객명}님
주문번호 : {주문번호}
주문금액 : {주문금액}
결제완료';
            $initdata['cmall_sendcont_user_bank_to_contents']
                = '안녕하세요 {고객명}님
주문번호 : {주문번호}
주문금액 : {주문금액}
입금확인후 주문이 완료됩니다. 감사합니다 ';
            $initdata['cmall_sendcont_admin_approve_bank_to_contents']
                = '입금확인함 - {고객명}님
주문번호 : {주문번호}
주문금액 : {주문금액}';
            $initdata['cmall_sendcont_user_approve_bank_to_contents']
                = '안녕하세요 {고객명}님
주문번호 : {주문번호}
주문금액 : {주문금액}
입금이 확인되었습니다. 감사합니다 ';
            $initdata['cmall_sendcont_admin_write_product_review']
                = '상품후기작성 - {고객명}님
상품명 : {상품명}';
            $initdata['cmall_sendcont_user_write_product_review']
                = '안녕하세요 {고객명}님
상품후기를 작성해주셔서 감사합니다 ';
            $initdata['cmall_sendcont_admin_write_product_qna']
                = '상품문의작성 - {고객명}님
상품명 : {상품명}';
            $initdata['cmall_sendcont_user_write_product_qna']
                = '안녕하세요 {고객명}님
상품문의가 접수되었습니다 감사합니다 ';
            $initdata['cmall_sendcont_admin_write_product_qna_reply']
                = '상품답변작성 - {고객명}님
상품명 : {상품명}';
            $initdata['cmall_sendcont_user_write_product_qna_reply']
                = '안녕하세요 {고객명}님
문의하신 상품문의에 답변이 작성되었습니다. 감사합니다 ';
            $this->Cmall_config_model->save($initdata);

        }
        $getdata = $this->Cmall_config_model->get_all_meta();
        $view['view']['data'] = $getdata;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'index');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 컨텐츠몰환경설정>레이아웃 설정 페이지입니다
     */
    public function layout()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_layout';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'layout_cmall',
                'label' => '일반레이아웃',
                'rules' => 'trim',
            ),
            array(
                'field' => 'mobile_layout_cmall',
                'label' => '모바일레이아웃',
                'rules' => 'trim',
            ),
            array(
                'field' => 'sidebar_cmall',
                'label' => '사이드바',
                'rules' => 'trim',
            ),
            array(
                'field' => 'mobile_sidebar_cmall',
                'label' => '모바일사이드바',
                'rules' => 'trim',
            ),
            array(
                'field' => 'skin_cmall',
                'label' => '일반스킨',
                'rules' => 'trim',
            ),
            array(
                'field' => 'mobile_skin_cmall',
                'label' => '모바일스킨',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall',
                'label' => '컨텐츠몰 메인 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall',
                'label' => '컨텐츠몰 메인 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall',
                'label' => '컨텐츠몰 메인 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall',
                'label' => '컨텐츠몰 메인 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall',
                'label' => '컨텐츠몰 메인 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_list',
                'label' => '컨텐츠몰 상품목록 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_list',
                'label' => '컨텐츠몰 상품목록 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_list',
                'label' => '컨텐츠몰 상품목록 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_list',
                'label' => '컨텐츠몰 상품목록 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_list',
                'label' => '컨텐츠몰 상품목록 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_item',
                'label' => '컨텐츠몰 상품페이지 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_item',
                'label' => '컨텐츠몰 상품페이지 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_item',
                'label' => '컨텐츠몰 상품페이지 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_item',
                'label' => '컨텐츠몰 상품페이지 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_item',
                'label' => '컨텐츠몰 상품페이지 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_cart',
                'label' => '컨텐츠몰 장바구니 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_cart',
                'label' => '컨텐츠몰 장바구니 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_cart',
                'label' => '컨텐츠몰 장바구니 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_cart',
                'label' => '컨텐츠몰 장바구니 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_cart',
                'label' => '컨텐츠몰 장바구니 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_order',
                'label' => '컨텐츠몰 주문하기 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_order',
                'label' => '컨텐츠몰 주문하기 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_order',
                'label' => '컨텐츠몰 주문하기 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_order',
                'label' => '컨텐츠몰 주문하기 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_order',
                'label' => '컨텐츠몰 주문하기 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_orderresult',
                'label' => '컨텐츠몰 주문결과 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_orderresult',
                'label' => '컨텐츠몰 주문결과 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_orderresult',
                'label' => '컨텐츠몰 주문결과 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_orderresult',
                'label' => '컨텐츠몰 주문결과 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_orderresult',
                'label' => '컨텐츠몰 주문결과 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_orderlist',
                'label' => '컨텐츠몰 주문내역 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_orderlist',
                'label' => '컨텐츠몰 주문내역 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_orderlist',
                'label' => '컨텐츠몰 주문내역 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_orderlist',
                'label' => '컨텐츠몰 주문내역 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_orderlist',
                'label' => '컨텐츠몰 주문내역 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_wishlist',
                'label' => '컨텐츠몰 찜한목록 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_wishlist',
                'label' => '컨텐츠몰 찜한목록 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_wishlist',
                'label' => '컨텐츠몰 찜한목록 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_wishlist',
                'label' => '컨텐츠몰 찜한목록 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_wishlist',
                'label' => '컨텐츠몰 찜한목록 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_review_write',
                'label' => '컨텐츠몰 후기작성 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_review_write',
                'label' => '컨텐츠몰 후기작성 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_review_write',
                'label' => '컨텐츠몰 후기작성 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_review_write',
                'label' => '컨텐츠몰 후기작성 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_review_write',
                'label' => '컨텐츠몰 후기작성 메타태그 page name',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_title_cmall_qna_write',
                'label' => '컨텐츠몰 상품문의작성 메타태그 Title',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_description_cmall_qna_write',
                'label' => '컨텐츠몰 상품문의작성 메타태그 meta description',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_keywords_cmall_qna_write',
                'label' => '컨텐츠몰 상품문의작성 메타태그 meta keywords',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_meta_author_cmall_qna_write',
                'label' => '컨텐츠몰 상품문의작성 메타태그 meta author',
                'rules' => 'trim',
            ),
            array(
                'field' => 'site_page_name_cmall_qna_write',
                'label' => '컨텐츠몰 상품문의작성 메타태그 page name',
                'rules' => 'trim',
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

            $array = array(
                'layout_cmall', 'mobile_layout_cmall', 'sidebar_cmall', 'mobile_sidebar_cmall',
                'skin_cmall', 'mobile_skin_cmall', 'site_meta_title_cmall', 'site_meta_description_cmall',
                'site_meta_keywords_cmall', 'site_meta_author_cmall', 'site_page_name_cmall',
                'site_meta_title_cmall_list', 'site_meta_description_cmall_list',
                'site_meta_keywords_cmall_list', 'site_meta_author_cmall_list',
                'site_page_name_cmall_list', 'site_meta_title_cmall_item',
                'site_meta_description_cmall_item', 'site_meta_keywords_cmall_item',
                'site_meta_author_cmall_item', 'site_page_name_cmall_item',
                'site_meta_title_cmall_cart', 'site_meta_description_cmall_cart',
                'site_meta_keywords_cmall_cart', 'site_meta_author_cmall_cart',
                'site_page_name_cmall_cart', 'site_meta_title_cmall_order',
                'site_meta_description_cmall_order', 'site_meta_keywords_cmall_order',
                'site_meta_author_cmall_order', 'site_page_name_cmall_order',
                'site_meta_title_cmall_orderresult', 'site_meta_description_cmall_orderresult',
                'site_meta_keywords_cmall_orderresult', 'site_meta_author_cmall_orderresult',
                'site_page_name_cmall_orderresult', 'site_meta_title_cmall_orderlist',
                'site_meta_description_cmall_orderlist', 'site_meta_keywords_cmall_orderlist',
                'site_meta_author_cmall_orderlist', 'site_page_name_cmall_orderlist',
                'site_meta_title_cmall_wishlist', 'site_meta_description_cmall_wishlist',
                'site_meta_keywords_cmall_wishlist', 'site_meta_author_cmall_wishlist',
                'site_page_name_cmall_wishlist', 'site_meta_title_cmall_review_write',
                'site_meta_description_cmall_review_write', 'site_meta_keywords_cmall_review_write',
                'site_meta_author_cmall_review_write', 'site_page_name_cmall_review_write',
                'site_meta_title_cmall_qna_write', 'site_meta_description_cmall_qna_write',
                'site_meta_keywords_cmall_qna_write', 'site_meta_author_cmall_qna_write',
                'site_page_name_cmall_qna_write'
            );
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }

            $this->Cmall_config_model->save($savedata);
            $view['view']['alert_message'] = '레이아웃 설정이 저장되었습니다';
        }

        $getdata = $this->Cmall_config_model->get_all_meta();
        $view['view']['data'] = $getdata;

        $view['view']['data']['layout_cmall_option'] = get_skin_name(
            '_layout',
            set_value('layout_cmall', element('layout_cmall', $getdata)),
            '기본설정따름'
        );
        $view['view']['data']['mobile_layout_cmall_option'] = get_skin_name(
            '_layout',
            set_value('mobile_layout_cmall', element('mobile_layout_cmall', $getdata)),
            '기본설정따름'
        );
        $view['view']['data']['skin_cmall_option'] = get_skin_name(
            'cmall',
            set_value('skin_cmall', element('skin_cmall', $getdata)),
            '기본설정따름'
        );
        $view['view']['data']['mobile_skin_cmall_option'] = get_skin_name(
            'cmall',
            set_value('mobile_skin_cmall', element('mobile_skin_cmall', $getdata)),
            '기본설정따름'
        );

        $view['view']['data']['layout_cmall_popup_option'] = get_skin_name(
            '_layout',
            set_value('layout_cmall_popup', element('layout_cmall_popup', $getdata)),
            '기본설정따름'
        );
        $view['view']['data']['mobile_layout_cmall_popup_option'] = get_skin_name(
            '_layout',
            set_value('mobile_layout_cmall_popup', element('mobile_layout_cmall_popup', $getdata)),
            '기본설정따름'
        );
        $view['view']['data']['skin_cmall_popup_option'] = get_skin_name(
            'cmall_popup',
            set_value('skin_cmall_popup', element('skin_cmall_popup', $getdata)),
            '기본설정따름'
        );
        $view['view']['data']['mobile_skin_cmall_popup_option'] = get_skin_name(
            'cmall_popup',
            set_value('mobile_skin_cmall_popup', element('mobile_skin_cmall_popup', $getdata)),
            '기본설정따름'
        );

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'layout');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 컨텐츠몰환경설정>권한관리 페이지입니다
     */
    public function access()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_access';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'access_cmall_list',
                'label' => '권한 - 목록',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'access_cmall_list_level',
                'label' => '권한 - 목록 레벨',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'access_cmall_list_group[]',
                'label' => '권한 - 목록 그룹',
                'rules' => 'trim',
            ),
            array(
                'field' => 'access_cmall_read',
                'label' => '권한 - 내용보기',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'access_cmall_read_level',
                'label' => '권한 - 내용보기 레벨',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'access_cmall_read_group[]',
                'label' => '권한 - 내용보기 그룹',
                'rules' => 'trim',
            ),
            array(
                'field' => 'access_cmall_buy',
                'label' => '권한 - 구매',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'access_cmall_buy_level',
                'label' => '권한 - 구매 레벨',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'access_cmall_buy_group[]',
                'label' => '권한 - 구매 그룹',
                'rules' => 'trim',
            ),
            array(
                'field' => 'use_cmall_product_review_anytime',
                'label' => '사용후기 작성',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_review_approve',
                'label' => '사용후기 승인 후 출력',
                'rules' => 'trim|numeric',
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

            $array = array(
                'access_cmall_list', 'access_cmall_list_level', 'access_cmall_read',
                'access_cmall_read_level', 'access_cmall_buy', 'access_cmall_buy_level',
                'use_cmall_product_review_anytime', 'use_cmall_product_review_approve'
            );

            $array_checkbox = array('access_cmall_list_group', 'access_cmall_read_group', 'access_cmall_buy_group');

            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }
            foreach ($array_checkbox as $value) {
                $savedata[$value] = json_encode($this->input->post($value, null, ''));
            }

            $this->Cmall_config_model->save($savedata);
            $view['view']['alert_message'] = '권한관리 설정이 저장되었습니다';
        }

        $getdata = $this->Cmall_config_model->get_all_meta();
        $getdata['config_max_level'] = $this->cbconfig->item('max_level');
        $this->load->model('Member_group_model');
        $getdata['mgroup'] = $this->Member_group_model
            ->get_admin_list('', '', '', '', 'mgr_order', 'asc');
        $view['view']['data'] = $getdata;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'access');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 컨텐츠몰환경설정>에디터기능 페이지입니다
     */
    public function editor()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_editor';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'use_cmall_product_dhtml',
                'label' => '상품관리페이지 에디터 사용',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_editor_type',
                'label' => '상품관리페이지 에디터 종류',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'cmall_product_thumb_width',
                'label' => '상품관리페이지 첨부파일 가로크기',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_mobile_thumb_width',
                'label' => '상품관리페이지 첨부파일 가로크기 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_content_target_blank',
                'label' => '상품관리페이지 링크 새창',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_mobile_content_target_blank',
                'label' => '상품관리페이지 링크 새창 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_auto_url',
                'label' => '상품관리페이지 본문안의 URL 자동링크',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_mobile_auto_url',
                'label' => '상품관리페이지 본문안의 URL 자동링크 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_review_dhtml',
                'label' => '사용후기페이지 에디터 사용',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_review_editor_type',
                'label' => '사용후기페이지 에디터 종류',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'cmall_product_review_thumb_width',
                'label' => '사용후기페이지 첨부파일 가로크기',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_review_mobile_thumb_width',
                'label' => '사용후기페이지 첨부파일 가로크기 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_review_content_target_blank',
                'label' => '사용후기페이지 링크 새창',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_review_mobile_content_target_blank',
                'label' => '사용후기페이지 링크 새창 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_review_auto_url',
                'label' => '사용후기페이지 본문안의 URL 자동링크',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_review_mobile_auto_url',
                'label' => '사용후기페이지 본문안의 URL 자동링크 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_qna_dhtml',
                'label' => '상품문의페이지 에디터 사용',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_qna_editor_type',
                'label' => '상품문의페이지 에디터 종류',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'cmall_product_qna_thumb_width',
                'label' => '상품문의페이지 첨부파일 가로크기',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_qna_mobile_thumb_width',
                'label' => '상품문의페이지 첨부파일 가로크기 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_qna_content_target_blank',
                'label' => '상품문의페이지 링크 새창',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_product_qna_mobile_content_target_blank',
                'label' => '상품문의페이지 링크 새창 - 모바일',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_qna_auto_url',
                'label' => '상품문의페이지 본문안의 URL 자동링크',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_cmall_product_qna_mobile_auto_url',
                'label' => '상품문의페이지 본문안의 URL 자동링크 - 모바일',
                'rules' => 'trim|numeric',
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

            $array = array(
                'use_cmall_product_dhtml', 'cmall_product_editor_type', 'cmall_product_thumb_width',
                'cmall_product_mobile_thumb_width', 'cmall_product_content_target_blank',
                'cmall_product_mobile_content_target_blank', 'use_cmall_product_auto_url',
                'use_cmall_product_mobile_auto_url', 'use_cmall_product_review_dhtml',
                'cmall_product_review_editor_type', 'cmall_product_review_thumb_width',
                'cmall_product_review_mobile_thumb_width',
                'cmall_product_review_content_target_blank',
                'cmall_product_review_mobile_content_target_blank',
                'use_cmall_product_review_auto_url', 'use_cmall_product_review_mobile_auto_url',
                'use_cmall_product_qna_dhtml', 'cmall_product_qna_editor_type',
                'cmall_product_qna_thumb_width', 'cmall_product_qna_mobile_thumb_width',
                'cmall_product_qna_content_target_blank',
                'cmall_product_qna_mobile_content_target_blank', 'use_cmall_product_qna_auto_url',
                'use_cmall_product_qna_mobile_auto_url'
            );
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }

            $this->Cmall_config_model->save($savedata);
            $view['view']['alert_message'] = '에디터기능 설정이 저장되었습니다';
        }

        $getdata = $this->Cmall_config_model->get_all_meta();
        $view['view']['data'] = $getdata;

        $view['view']['data']['cmall_product_editor_type_option'] = get_skin_name(
            'editor',
            set_value('cmall_product_editor_type', element('cmall_product_editor_type', $getdata)),
            '',
            $path = 'plugin'
        );
        $view['view']['data']['cmall_product_review_editor_type_option'] = get_skin_name(
            'editor',
            set_value('cmall_product_review_editor_type', element('cmall_product_review_editor_type', $getdata)),
            '',
            $path = 'plugin'
        );
        $view['view']['data']['cmall_product_qna_editor_type_option'] = get_skin_name(
            'editor',
            set_value('cmall_product_qna_editor_type', element('cmall_product_qna_editor_type', $getdata)),
            '',
            $path = 'plugin'
        );

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'editor');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 컨텐츠몰환경설정>SMS 설정 페이지입니다
     */
    public function smsconfig()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_smsconfig';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_sms',
                'label' => 'SMS 사용',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'sms_icode_id',
                'label' => '아이코드 아이디',
                'rules' => 'trim',
            ),
            array(
                'field' => 'sms_icode_pw',
                'label' => '아이코드 비밀번호',
                'rules' => 'trim|callback__sms_icode_check',
            ),
            array(
                'field' => 'sms_admin_phone',
                'label' => '회신번호',
                'rules' => 'trim',
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

            $array = array('use_sms', 'sms_icode_id', 'sms_icode_pw', 'sms_admin_phone',);

            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }
            if ($this->input->post('sms_icode_id') && $this->input->post('sms_icode_pw')) {
                $this->load->library('smslib');
                $smsinfo = $this->smslib->get_icode_info($this->input->post('sms_icode_id'), $this->input->post('sms_icode_pw'));
                if (element('payment', $smsinfo) === 'C') {
                    $savedata['sms_icode_port'] = '7296';
                } else {
                    $savedata['sms_icode_port'] = '7295';
                }
            }

            $this->Config_model->save($savedata);
            $view['view']['alert_message'] = 'SMS 설정이 저장되었습니다';
        }

        $getdata = $this->Config_model->get_all_meta();
        if (element('sms_icode_id', $getdata) && element('sms_icode_pw', $getdata)) {
            $this->load->library('smslib');
            $getdata['smsinfo'] = $this->smslib->get_icode_info(element('sms_icode_id', $getdata), element('sms_icode_pw', $getdata));
        }
        $view['view']['data'] = $getdata;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'smsconfig');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 컨텐츠몰환경설정>결제기능 페이지입니다
     */
    public function paymentconfig()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_paymentconfig';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'use_payment_bank',
                'label' => '무통장입금',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_payment_card',
                'label' => '카드결제',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_payment_realtime',
                'label' => '실시간계좌이체',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_payment_vbank',
                'label' => '가상계좌이체',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_payment_phone',
                'label' => '핸드폰결제',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'use_payment_pg',
                'label' => '결제대행사',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'pg_kcp_mid',
                'label' => 'KCP ID',
                'rules' => 'trim',
            ),
            array(
                'field' => 'pg_kcp_key',
                'label' => 'KCP KEY',
                'rules' => 'trim',
            ),
            array(
                'field' => 'pg_inicis_mid',
                'label' => 'INICIS ID',
                'rules' => 'trim',
            ),
            array(
                'field' => 'pg_inicis_key',
                'label' => 'INICIS KEY',
                'rules' => 'trim',
            ),
            array(
                'field' => 'pg_lg_mid',
                'label' => 'LG ID',
                'rules' => 'trim',
            ),
            array(
                'field' => 'pg_lg_key',
                'label' => 'LG KEY',
                'rules' => 'trim',
            ),
            array(
                'field' => 'use_pg_no_interest',
                'label' => '무이자할부사용',
                'rules' => 'trim',
            ),
            array(
                'field' => 'use_pg_test',
                'label' => '실결제여부',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'payment_bank_info',
                'label' => '계좌안내(무통장입금시)',
                'rules' => 'trim',
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

            $array = array(
                'use_payment_bank', 'use_payment_card', 'use_payment_realtime',
                'use_payment_vbank', 'use_payment_phone', 'use_payment_pg', 'pg_kcp_mid',
                'pg_kcp_key', 'pg_inicis_mid', 'pg_inicis_key', 'pg_lg_mid', 'pg_lg_key',
                'use_pg_no_interest', 'use_pg_test', 'payment_bank_info'
            );
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }

            $this->Config_model->save($savedata);
            $view['view']['alert_message'] = '결제기능 설정이 저장되었습니다';
        }

        $getdata = $this->Config_model->get_all_meta();
        $view['view']['data'] = $getdata;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'paymentconfig');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 컨텐츠몰환경설정>알림설정 페이지입니다
     */
    public function alarm()
    {
        // 이벤트 라이브러리를 로딩합니다
        $eventname = 'event_admin_cmall_cmallconfig_alarm';
        $this->load->event($eventname);

        $view = array();
        $view['view'] = array();

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before'] = Events::trigger('before', $eventname);

        /**
         * Validation 라이브러리를 가져옵니다
         */
        $this->load->library('form_validation');

        /**
         * 전송된 데이터의 유효성을 체크합니다
         */
        $config = array(
            array(
                'field' => 'is_submit',
                'label' => '전송',
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'cmall_email_admin_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_user_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_alluser_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_admin_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_user_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_admin_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_user_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_alluser_cash_to_contents',
                'label' => '컨텐츠구매시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_admin_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_user_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_alluser_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_admin_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_user_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_admin_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_user_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_alluser_bank_to_contents',
                'label' => '컨텐츠구매요청시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_admin_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_user_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_alluser_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_admin_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_user_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_admin_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_user_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_alluser_approve_bank_to_contents',
                'label' => '입금완료처리시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_admin_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_user_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_alluser_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_admin_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_user_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_admin_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_user_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_alluser_write_product_review',
                'label' => '상품후기 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_admin_write_product_qna',
                'label' => '상품문의 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_user_write_product_qna',
                'label' => '상품문의 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_admin_write_product_qna',
                'label' => '상품문의 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_user_write_product_qna',
                'label' => '상품문의 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_admin_write_product_qna',
                'label' => '상품문의 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_user_write_product_qna',
                'label' => '상품문의 작성시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_admin_write_product_qna_reply',
                'label' => '상품문의 답변시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_email_user_write_product_qna_reply',
                'label' => '상품문의 답변시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_admin_write_product_qna_reply',
                'label' => '상품문의 답변시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_note_user_write_product_qna_reply',
                'label' => '상품문의 답변시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_admin_write_product_qna_reply',
                'label' => '상품문의 답변시',
                'rules' => 'trim|numeric',
            ),
            array(
                'field' => 'cmall_sms_user_write_product_qna_reply',
                'label' => '상품문의 답변시',
                'rules' => 'trim|numeric',
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

            $array = array(
                'cmall_email_admin_cash_to_contents', 'cmall_email_user_cash_to_contents',
                'cmall_email_alluser_cash_to_contents', 'cmall_note_admin_cash_to_contents',
                'cmall_note_user_cash_to_contents', 'cmall_sms_admin_cash_to_contents',
                'cmall_sms_user_cash_to_contents', 'cmall_sms_alluser_cash_to_contents',
                'cmall_email_admin_bank_to_contents', 'cmall_email_user_bank_to_contents',
                'cmall_email_alluser_bank_to_contents', 'cmall_note_admin_bank_to_contents',
                'cmall_note_user_bank_to_contents', 'cmall_sms_admin_bank_to_contents',
                'cmall_sms_user_bank_to_contents', 'cmall_sms_alluser_bank_to_contents',
                'cmall_email_admin_approve_bank_to_contents',
                'cmall_email_user_approve_bank_to_contents',
                'cmall_email_alluser_approve_bank_to_contents',
                'cmall_note_admin_approve_bank_to_contents',
                'cmall_note_user_approve_bank_to_contents',
                'cmall_sms_admin_approve_bank_to_contents',
                'cmall_sms_user_approve_bank_to_contents',
                'cmall_sms_alluser_approve_bank_to_contents',
                'cmall_email_admin_write_product_review', 'cmall_email_user_write_product_review',
                'cmall_email_alluser_write_product_review', 'cmall_note_admin_write_product_review',
                'cmall_note_user_write_product_review', 'cmall_sms_admin_write_product_review',
                'cmall_sms_user_write_product_review', 'cmall_sms_alluser_write_product_review',
                'cmall_email_admin_write_product_qna', 'cmall_email_user_write_product_qna',
                'cmall_note_admin_write_product_qna', 'cmall_note_user_write_product_qna',
                'cmall_sms_admin_write_product_qna', 'cmall_sms_user_write_product_qna',
                'cmall_email_admin_write_product_qna_reply',
                'cmall_email_user_write_product_qna_reply',
                'cmall_note_admin_write_product_qna_reply',
                'cmall_note_user_write_product_qna_reply', 'cmall_sms_admin_write_product_qna_reply',
                'cmall_sms_user_write_product_qna_reply'
            );
            foreach ($array as $value) {
                $savedata[$value] = $this->input->post($value, null, '');
            }

            $this->Cmall_config_model->save($savedata);
            $view['view']['alert_message'] = '알림기능 설정이 저장되었습니다';
        }

        $getdata = $this->Cmall_config_model->get_all_meta();
        $view['view']['data'] = $getdata;

        // 이벤트가 존재하면 실행합니다
        $view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

        /**
         * 어드민 레이아웃을 정의합니다
         */
        $layoutconfig = array('layout' => 'layout', 'skin' => 'alarm');
        $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
        $this->data = $view;
        $this->layout = element('layout_skin_file', element('layout', $view));
        $this->view = element('view_skin_file', element('layout', $view));
    }

    /**
     * 아이코드 아이디와 패스워드가 맞는지 체크합니다
     */
    public function _sms_icode_check($pw = '')
    {
        $id = $this->input->post('sms_icode_id');

        if (empty($id) && empty($pw)) {
            return true;
        }
        $this->load->library('smslib');
        $result = $this->smslib->get_icode_info($id, $pw);
        if (element('code', $result) === '202') {
            $this->form_validation->set_message(
                '_sms_icode_check',
                '아이코드 아이디와 패스워드가 맞지 않습니다'
            );
            return false;
        }
        return true;
    }
}