<?php
// kcp 전자결제를 사용할 때만 실행
if ($this->cbconfig->item('use_payment_card')
    OR $this->cbconfig->item('use_payment_realtime')
    OR $this->cbconfig->item('use_payment_vbank')
    OR $this->cbconfig->item('use_payment_phone')) {
?>
<!-- Payplus Plug-in 설치 안내 시작 { -->
<div id="display_setup_message" class="alert alert-warning" style="display:block">
    <strong>결제안내</strong>
    <span class="text-danger">결제를 하시려면 상단의 노란색 표시줄을 클릭</span>하시거나, <a href="https://pay.kcp.co.kr/plugin_new/file/KCPPluginSetup.exe" onclick="return get_intall_file();"><strong>[수동설치]</strong></a>를 눌러 Payplus Plug-in을 설치하시기 바랍니다.<br />
    [수동설치]를 눌러 설치하신 경우 <strong class="text-danger">새로고침(F5)키</strong>를 눌러 진행하시기 바랍니다.
</div>
<!-- } Payplus Plug-in 설치 안내 끝 -->
<?php } ?>

<div id="display_pay_button" style="display:none">
    <div class="form-group text-center">
        <button type="button" onClick="fpayment_check();" class="btn btn-primary">주문하기</button>
    </div>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo site_url(VIEW_DIR . 'paymentlib/images/ajax-loader.gif'); ?>" alt="주문완료중" title="주문완료중" />
    <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<?php
// 무통장 입금만 사용할 때는 주문하기 버튼 보이게
if ( ! ($this->cbconfig->item('use_payment_card')
    OR $this->cbconfig->item('use_payment_realtime')
    OR $this->cbconfig->item('use_payment_vbank')
    OR $this->cbconfig->item('use_payment_phone'))) {
?>
<script type="text/javascript">
$('#display_pay_button').show();
</script>
<?php } ?>
