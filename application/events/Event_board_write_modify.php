<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Event_board_write_modify extends CI_Controller
{
  
    private $CI;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        Events::register('after_post_update_hiadone_newspopcon', array($this, 'after_post_update_hiadone_newspopcon'));
        Events::register('after_post_update_pop', array($this, 'after_post_update_pop'));
        Events::register('after_post_update_eco', array($this, 'after_post_update_eco'));
        Events::register('after_post_update_any', array($this, 'after_post_update_any'));
        Events::register('after_post_update_hiadone_webtoon', array($this, 'after_post_update_hiadone_webtoon'));
        Events::register('after_post_update_tomix', array($this, 'after_post_update_tomix'));
        Events::register('after_post_update_anytoon', array($this, 'after_post_update_anytoon'));
        Events::register('after_post_update_toptoon', array($this, 'after_post_update_toptoon'));
        Events::register('after_post_update_issue_1', array($this, 'after_post_update_issue_1'));
        Events::register('after_post_update_issue_2', array($this, 'after_post_update_issue_2'));
    }
     
    public function after_post_update_hiadone_newspopcon() {
         
        $result = array();
        
        $result['result'] = $this->_create_file('http://newspopcon.com/common/create_file.php',array('brd_key'=>'hiadone_newspopcon'));
  
        return $result;
    }

    public function after_post_update_pop() {
         
        $result = array();
        
        $result['result'] = $this->_create_file('http://newspopcon.com/common/create_file.php',array('brd_key'=>'pop'));
        
  
        return $result;
    }

    public function after_post_update_eco() {
         
        $result = array();
  
        
  
        $result['result'] = $this->_create_file('http://newspopcon.com/common/create_file.php',array('brd_key'=>'eco'));
  
        return $result;
    }

    public function after_post_update_any() {
         
        $result = array();
        
        $result['result'] = $this->_create_file('http://newspopcon.com/common/create_file.php',array('brd_key'=>'any'));
        
  
        return $result;
    }

    public function after_post_update_hiadone_webtoon() {
         
        $result = array();  
        
  
        $result['result'] = $this->_create_file('http://www.popapp.co.kr/common/create_file.php',array('brd_key'=>'hiadone_webtoon'));
  
        return $result;
    }

    public function after_post_update_tomix() {
         
        $result = array();
  
        $result['result'] = $this->_create_file('http://www.popapp.co.kr/common/create_file.php',array('brd_key'=>'tomix'));
  
        return $result;
    }

    public function after_post_update_anytoon() {
         
        $result = array();
  
        $result['result'] = $this->_create_file('http://www.popapp.co.kr/common/create_file.php',array('brd_key'=>'anytoon'));
  
        return $result;
    }

    public function after_post_update_toptoon() {
         
        $result = array();
  
        $result['result'] = $this->_create_file('http://www.popapp.co.kr/common/create_file.php',array('brd_key'=>'toptoon'));
  
        return $result;
    }

    public function after_post_update_issue_1() {
         
        $result = array();
        
        $result['result'] = $this->_create_file('http://issuepopcon.com/common/create_file.php',array('brd_key'=>'issue_1'));
  
        return $result;
    }

    public function after_post_update_issue_2() {
         
        $result = array();
  
        $result['result'] = $this->_create_file('http://issuepopcon.com/common/create_file.php',array('brd_key'=>'issue_2'));
  
        return $result;
    }
  
    public function _create_file($url='',$data=array())
    {
        
        

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, sizeof($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        // $obj = json_decode($result);

        // if ((string) $obj->success !== '1') {
            
        //     return false;
        // }

        return true;
    }
}