<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Event_postact_post_extra extends CI_Controller
{
  
    private $CI;
    protected $xmlhttp;
    function __construct()
    {
        $this->CI = & get_instance();
        
        Events::register('after_hiadone_newspopcon', array($this, 'after_hiadone_newspopcon'));
        Events::register('after_pop', array($this, 'after_pop'));
        Events::register('after_eco', array($this, 'after_eco'));
        Events::register('after_any', array($this, 'after_any'));
        Events::register('after_hiadone_webtoon', array($this, 'after_hiadone_webtoon'));
        Events::register('after_anytoon', array($this, 'after_anytoon'));
        Events::register('after_tomix', array($this, 'after_tomix'));
        Events::register('after_toptoon', array($this, 'after_toptoon'));
    }
     
    public function after_hiadone_newspopcon() {
         
        $result = array();
        
        $result['url'] = 'http://newspopcon.com/common/create_file.php?brd_key=hiadone_newspopcon';
        
        return $result;
    }

    public function after_pop() {
         
        $result = array();
  
        $result['url'] = 'http://newspopcon.com/common/create_file.php?brd_key=pop';
  
        return $result;
    }

    public function after_eco() {
         
        $result = array();
  
        
  
        $result['url'] = 'http://newspopcon.com/common/create_file.php?brd_key=eco';
  
        return $result;
    }

    public function after_any() {
         
        $result = array();
        
        
  
        $result['url'] = 'http://newspopcon.com/common/create_file.php?brd_key=any';
  
        return $result;
    }

    public function after_hiadone_webtoon() {
         
        $result = array();
        
     
        
  
        $result['url'] = 'http://www.popapp.co.kr/common/create_file.php?brd_key=hiadone_webtoon';
  
        return $result;
    }

    public function after_anytoon() {
         
        $result = array();
  
      
  
        $result['url'] = 'http://www.popapp.co.kr/common/create_file.php?brd_key=anytoon';
  
        return $result;
    }

    public function after_tomix() {
         
        $result = array();
  
     
        $result['url'] = 'http://www.popapp.co.kr/common/create_file.php?brd_key=tomix';
  
        return $result;
    }

    public function after_toptoon() {
         
        $result = array();
  
   
        
        $result['url'] = 'http://www.popapp.co.kr/common/create_file.php?brd_key=toptoon';
  
        return $result;
    }
  
    
}