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
if ( ! ($this->cbconfig->item('use_payment_card') OR $this->cbconfig->item('use_payment_realtime') OR $this->cbconfig->item('use_payment_vbank') OR $this->cbconfig->item('use_payment_phone'))) {
?>
<script type="text/javascript">
$('#display_pay_button').show();
</script>
<?php } ?>
