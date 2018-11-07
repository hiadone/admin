<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Event_board_post_post extends CI_Controller
{
  
    private $CI;
    protected $xmlhttp;
    function __construct()
    {
        $this->CI = & get_instance();
        $this->xmlhttp = '<script>var xmlhttp;  
        if (window.XMLHttpRequest) {  
            xmlhttp = new XMLHttpRequest();
        } 
        else {  
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        //Ajax구현부분
        xmlhttp.onreadystatechange = function() {  
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                 //통신 성공시 구현부분
            }
        }
        ';
        Events::register('before_post_layout_hiadone_newspopcon', array($this, 'before_post_layout_hiadone_newspopcon'));
        Events::register('before_post_layout_pop', array($this, 'before_post_layout_pop'));
        Events::register('before_post_layout_eco', array($this, 'before_post_layout_eco'));
        Events::register('before_post_layout_any', array($this, 'before_post_layout_any'));
        Events::register('before_post_layout_hiadone_webtoon', array($this, 'before_post_layout_hiadone_webtoon'));
        Events::register('before_post_layout_anytoon', array($this, 'before_post_layout_anytoon'));
        Events::register('before_post_layout_tomix', array($this, 'before_post_layout_tomix'));
        Events::register('before_post_layout_toptoon', array($this, 'before_post_layout_toptoon'));
        Events::register('before_post_layout_issue_1', array($this, 'before_post_layout_issue_1'));
        Events::register('before_post_layout_issue_2', array($this, 'before_post_layout_issue_2'));
    }
     
    public function before_post_layout_hiadone_newspopcon() {
         
        $result = array();
        
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://newspopcon.com/common/create_file.php?brd_key=hiadone_newspopcon&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';

        
        $result['result'] = 1;
        
        return $result;
    }

    public function before_post_layout_pop() {
         
        $result = array();
        
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://newspopcon.com/common/create_file.php?brd_key=pop&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
  
        $result['result'] = 1;
  
        return $result;
    }

    public function before_post_layout_eco() {
         
        $result = array();
  
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://newspopcon.com/common/create_file.php?brd_key=eco&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
  
        $result['result'] = 1;
  
        return $result;
    }

    public function before_post_layout_any() {
         
        $result = array();
        
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://newspopcon.com/common/create_file.php?brd_key=any&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
  
        $result['result'] = 1;
  
        return $result;
    }

    public function before_post_layout_hiadone_webtoon() {
         
        $result = array();
        
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://www.popapp.co.kr/common/create_file.php?brd_key=hiadone_webtoon&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
        
  
        $result['result'] = 1;
  
        return $result;
    }

    public function before_post_layout_anytoon() {
         
        $result = array();
  
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://www.popapp.co.kr/common/create_file.php?brd_key=anytoon&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
  
        $result['result'] = 1;
  
        return $result;
    }

    public function before_post_layout_tomix() {
         
        $result = array();
  
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://www.popapp.co.kr/common/create_file.php?brd_key=tomix&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
  
        $result['result'] = 1;
  
        return $result;
    }

    public function before_post_layout_toptoon() {
         
        $result = array();
  
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://www.popapp.co.kr/common/create_file.php?brd_key=toptoon&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
        
        $result['result'] = 1;
  
        return $result;
    }
  
    public function before_post_layout_issue_1() {
         
        $result = array();
    
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://issuepopcon.com/common/create_file.php?brd_key=issue_1&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
        
        $result['result'] = 1;
    
        return $result;
    }

    public function before_post_layout_issue_2() {
         
        $result = array();
    
        echo $this->xmlhttp;
        echo '
        xmlhttp.open("GET", "http://issuepopcon.com/common/create_file.php?brd_key=issue_2&"+(new Date()).getTime(), true);  
        xmlhttp.send();</script>
        ';
        
        $result['result'] = 1;
    
        return $result;
    }
}