<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */


/**
 * 게시물 열람 페이지 주소를 return 합니다
 */
if ( ! function_exists('cmall_item_url')) {
    function cmall_item_url($url = '')
    {
        $url = trim($url, '/');
        $itemurl = site_url(config_item('uri_segment_cmall_item') . '/' . $url);
        return $itemurl;
    }
}
