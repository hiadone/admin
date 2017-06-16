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
<script src="<?php echo element('pg_conf_js_url', element('pg', $view)); ?>"></script>
<?php
/* = -------------------------------------------------------------------------- = */
/* = Javascript source Include END = */
/* ============================================================================== */
?>
<script type="text/javascript">
function CheckPayplusInstall()
{
    if (ChkBrowser())
    {
        if (document.Payplus.object !== null) {
            $('#display_setup_message_top').hide();
            $('#display_setup_message').hide();
            $('#display_pay_button').show();
        }
    }
    else
    {
        setTimeout('init_pay_button();',300);
    }
}

/* Payplus Plug-in 실행 */
function jsf__pay(form)
{
    var RetVal = false;

     /* Payplus Plugin 실행 */
    if (MakePayMessage(form) === true)
    {
        //openwin = window.open( "./kcp/proc_win.html", "proc_win", "width=449, height=209, top=300, left=300" );
        $('#display_pay_button').hide();
        $('#display_pay_process').show();
        RetVal = true;
    }
    else
    {
        /* res_cd와 res_msg변수에 해당 오류코드와 오류메시지가 설정됩니다.
         * ex) 고객이 Payplus Plugin에서 취소 버튼 클릭시 res_cd=3001, res_msg=사용자 취소
         * 값이 설정됩니다.
         */
        res_cd = document.fpayment.res_cd.value;
        res_msg = document.fpayment.res_msg.value;
    }

    return RetVal;
}

// Payplus Plug-in 설치 안내

function init_pay_button()
{
    if (navigator.userAgent.indexOf('MSIE') > 0)
    {
        try
        {
            if (document.Payplus.object === null)
            {
                $('#display_setup_message_top').show();
                $('#display_setup_message').show();
                $('#display_pay_button').hide();
                document.getElementById('display_setup_message').scrollIntoView();
            } else {
                $('#display_setup_message_top').hide();
                $('#display_setup_message').hide();
                $('#display_pay_button').show();
            }
        }
        catch (e)
        {
            $('#display_setup_message_top').show();
            $('#display_setup_message').show();
            $('#display_pay_button').hide();
            document.getElementById('display_setup_message').scrollIntoView();
        }
    }
    else
    {
        try
        {
            if (Payplus === null) {
                $('#display_setup_message_top').show();
                $('#display_setup_message').show();
                $('#display_pay_button').hide();
                document.getElementById('display_setup_message').scrollIntoView();
            } else {
                $('#display_setup_message_top').hide();
                $('#display_setup_message').hide();
                $('#display_pay_button').show();
            }
        }
        catch (e)
        {
            $('#display_setup_message_top').show();
            $('#display_setup_message').show();
            $('#display_pay_button').hide();
            document.getElementById('display_setup_message').scrollIntoView();
        }
    }
}

function get_intall_file()
{
    document.location.href = GetInstallFile();
    return false;
}
</script>

<!-- Payplus Plug-in 설치 안내 -->
<div id="display_setup_message_top" class="alert alert-warning" style="display:block">
    <strong>결제안내</strong>
    <span class="text-danger">결제를 하시려면 상단의 노란색 표시줄을 클릭</span>하시거나, <a href="https://pay.kcp.co.kr/plugin_new/file/KCPPluginSetup.exe" onclick="return get_intall_file();"><strong>[수동설치]</strong></a>를 눌러 Payplus Plug-in을 설치하시기 바랍니다.<br />
 [    수동설치]를 눌러 설치하신 경우 <strong class="text-danger">새로고침(F5)키</strong>를 눌러 진행하시기 바랍니다.
</div>
