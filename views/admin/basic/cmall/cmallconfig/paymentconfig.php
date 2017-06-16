<div class="box">
    <div class="box-header">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/editor'); ?>" onclick="return check_form_changed();">에디터기능</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
            <li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/paymentconfig'); ?>" onclick="return check_form_changed();">결제기능</a></li>
            <li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림설정</a></li>
        </ul>
    </div>
    <div class="box-table">
        <?php
        echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
        echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
        $attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
        echo form_open(current_full_url(), $attributes);
        ?>
            <input type="hidden" name="is_submit" value="1" />
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">현금/카드 결제시 결제 가능 방법</label>
                    <div class="col-sm-10 form-inline">
                        <label for="use_payment_bank" class="checkbox-inline">
                            <input type="checkbox" name="use_payment_bank" id="use_payment_bank" value="1" <?php echo set_checkbox('use_payment_bank', '1', (element('use_payment_bank', element('data', $view)) ? true : false)); ?> /> 무통장입금
                        </label>
                        <label for="use_payment_card" class="checkbox-inline">
                            <input type="checkbox" name="use_payment_card" id="use_payment_card" value="1" <?php echo set_checkbox('use_payment_card', '1', (element('use_payment_card', element('data', $view)) ? true : false)); ?> /> 카드결제
                        </label>
                        <label for="use_payment_realtime" class="checkbox-inline">
                            <input type="checkbox" name="use_payment_realtime" id="use_payment_realtime" value="1" <?php echo set_checkbox('use_payment_realtime', '1', (element('use_payment_realtime', element('data', $view)) ? true : false)); ?> /> 실시간계좌이체
                        </label>
                        <label for="use_payment_vbank" class="checkbox-inline">
                            <input type="checkbox" name="use_payment_vbank" id="use_payment_vbank" value="1" <?php echo set_checkbox('use_payment_vbank', '1', (element('use_payment_vbank', element('data', $view)) ? true : false)); ?> /> 가상계좌결제
                        </label>
                        <label for="use_payment_phone" class="checkbox-inline">
                            <input type="checkbox" name="use_payment_phone" id="use_payment_phone" value="1" <?php echo set_checkbox('use_payment_phone', '1', (element('use_payment_phone', element('data', $view)) ? true : false)); ?> /> 핸드폰결제
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">결제대행사</label>
                    <div class="col-sm-10 form-inline">
                        <select name="use_payment_pg" class="form-control" id="use_payment_pg">
                            <option value="kcp" <?php echo set_select('use_payment_pg', 'kcp', (element('use_payment_pg', element('data', $view)) === 'kcp' ? true : false)); ?> >KCP</option>
                            <option value="lg" <?php echo set_select('use_payment_pg', 'lg', (element('use_payment_pg', element('data', $view)) === 'lg' ? true : false)); ?> >LG유플러스</option>
                            <option value="inicis" <?php echo set_select('use_payment_pg', 'inicis', (element('use_payment_pg', element('data', $view)) === 'inicis' ? true : false)); ?> >KG이니시스</option>
                        </select>
                    </div>
                </div>
                <div class="form-group pg_info pg_kcp">
                    <label class="col-sm-2 control-label">KCP SITE CODE</label>
                    <div class="col-sm-10 form-inline">
                        SITE CODE <input type="text" class="form-control" name="pg_kcp_mid" id="pg_kcp_mid" value="<?php echo set_value('pg_kcp_mid', element('pg_kcp_mid', element('data', $view))); ?>" />
                        SITE KEY <input type="text" class="form-control" name="pg_kcp_key" id="pg_kcp_key" value="<?php echo set_value('pg_kcp_key', element('pg_kcp_key', element('data', $view))); ?>" />
                        <div class="help-block">KCP 에서 받은 SITE CODE 와 SITE KEY 를 입력해주세요</div>
                    </div>
                </div>
                <div class="form-group pg_info pg_lg">
                    <label class="col-sm-2 control-label">LG유플러스 상점아이디</label>
                    <div class="col-sm-10 form-inline">
                        상점아이디 <input type="text" class="form-control" name="pg_lg_mid" id="pg_lg_mid" value="<?php echo set_value('pg_lg_mid', element('pg_lg_mid', element('data', $view))); ?>" />
                        MERT KEY <input type="text" class="form-control" name="pg_lg_key" id="pg_lg_key" value="<?php echo set_value('pg_lg_key', element('pg_lg_key', element('data', $view))); ?>" />
                        <div class="help-block">LG유플러스 에서 받은 상점아이디와 와 MERT KEY 를 입력해주세요</div>
                    </div>
                </div>
                <div class="form-group pg_info pg_inicis">
                    <label class="col-sm-2 control-label">KG이니시스 상점아이디</label>
                    <div class="col-sm-10 form-inline">
                        상점아이디 <input type="text" class="form-control" name="pg_inicis_mid" id="pg_inicis_mid" value="<?php echo set_value('pg_inicis_mid', element('pg_inicis_mid', element('data', $view))); ?>" />
                        키패스워드 <input type="text" class="form-control" name="pg_inicis_key" id="pg_inicis_key" value="<?php echo set_value('pg_inicis_key', element('pg_inicis_key', element('data', $view))); ?>" />
                        <div class="help-block">KG이니시스 에서 받은 상점아이디와 와 KEY PASSWORD 를 입력해주세요</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">무이자할부 기능 사용</label>
                    <div class="col-sm-10">
                        <label for="use_pg_no_interest" class="checkbox-inline">
                            <input type="checkbox" name="use_pg_no_interest" id="use_pg_no_interest" value="1" <?php echo set_checkbox('use_pg_no_interest', '1', (element('use_pg_no_interest', element('data', $view)) ? true : false)); ?> /> 사용합니다
                        </label>
                        <div class="help-block">이 기능을 사용하시면, PG사 가맹점 관리자 페이지에서 설정하신 무이자할부 설정이 적용됩니다.<br />
                        사용안하시면 PG사 무이자 이벤트 카드를 제외한 모든 카드의 무이자 설정이 적용되지 않습니다.</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">실결제여부</label>
                    <div class="col-sm-10">
                        <label class="radio-inline" for="use_pg_test_2" >
                            <input type="radio" name="use_pg_test" id="use_pg_test_2" value="0" <?php echo set_checkbox('use_pg_test', '0', ( ! element('use_pg_test', element('data', $view)) ? true : false)); ?> /> 실결제
                        </label>
                        <label class="radio-inline" for="use_pg_test_1" >
                            <input type="radio" name="use_pg_test" id="use_pg_test_1" value="1" <?php echo set_checkbox('use_pg_test', '1', (element('use_pg_test', element('data', $view)) ? true : false)); ?> /> 테스트 결제
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">계좌안내(무통장입금시)</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="payment_bank_info"><?php echo set_value('payment_bank_info', element('payment_bank_info', element('data', $view))); ?></textarea>
                        <div class="help-block">예) 00은행 123-456-7890 예금주 : 홍길동</div>
                    </div>
                </div>
                <div class="btn-group pull-right" role="group" aria-label="...">
                    <button type="submit" class="btn btn-success btn-sm">저장하기</button>
                </div>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
    $('#fadminwrite').validate({
        rules: {
            use_payment_pg: {required :true}
        }
    });
});

$('.pg_info').hide();
$('.pg_<?php echo element('use_payment_pg', element('data', $view)); ?>').show();
$('#use_payment_pg').on('change', function() {
    var pg = $(this).val();
    $('.pg_info').hide();
    $('.pg_' + pg).show();
});

var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
    if ($('#fadminwrite').serialize() !== form_original_data) {
        if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
            return true;
        } else {
            return false;
        }
    }
    return true;
}
//]]>
</script>
