<?php
// kcp 전자결제를 사용할 때만 실행
if ($this->cbconfig->item('use_payment_card')
    OR $this->cbconfig->item('use_payment_realtime')
    OR $this->cbconfig->item('use_payment_vbank')
    OR $this->cbconfig->item('use_payment_phone')) {

} else {
    return;
}
?>

<script language = 'javascript'>
//<![CDATA[
/*
 * 상점결제 인증요청후 PAYKEY를 받아서 최종결제 요청.
 */
function doPay_ActiveX() {
    ret = xpay_check(document.getElementById('fpayment'), '<?php echo element('CST_PLATFORM', element('pg', $view)); ?>');

    if (ret === '00') { //ActiveX 로딩 성공
        var LGD_RESPCODE = dpop.getData('LGD_RESPCODE'); //결과코드
        var LGD_RESPMSG = dpop.getData('LGD_RESPMSG'); //결과메세지

        if ('0000' === LGD_RESPCODE) { //인증성공
            var LGD_PAYKEY = dpop.getData('LGD_PAYKEY'); //LG유플러스 인증KEY
            //var msg = "인증결과 : " + LGD_RESPMSG + "\n";
            //msg += "LGD_PAYKEY : " + LGD_PAYKEY +"\n\n";
            document.getElementById('LGD_PAYKEY').value = LGD_PAYKEY;
            //alert(msg);
            $('#display_pay_button').hide();
            $('#display_pay_process').show();
            document.getElementById('fpayment').submit();
        } else { //인증실패
            alert('인증이 실패하였습니다. ' + LGD_RESPMSG);
            return false;
        }
    } else {
        alert('LG유플러스 전자결제를 위한 ActiveX Control이 설치되지 않았습니다.');
        xpay_showInstall(); //설치안내 팝업페이지 표시 코드 추가
    }
}

function isActiveXOK() {
    if (lgdacom_atx_flag === true) {
        $('#display_pay_button').show();
    } else {
        $('#display_pay_button').hide();
    }
}

function Pay_Request(od_id, amount, timestamp) {
    $.ajax({
        url: '<?php echo site_url('payment/lg_markethashdata'); ?>',
        type: 'POST',
        cache: false,
        dataType: 'html',
        data: { LGD_OID : od_id, LGD_AMOUNT : amount, LGD_TIMESTAMP : timestamp, csrf_test_name: cb_csrf_hash },
        success: function(data) {
            $('#LGD_HASHDATA').val(data);
            doPay_ActiveX();
        }
    });
}

//]]>
</script>

<div id="LGD_ACTIVEX_DIV"></div> <!-- ActiveX 설치 안내 Layer 입니다. 수정하지 마세요. -->
