<?php
$exclude = array('res_cd', 'LGD_PAYKEY');

$attributes = array('name' => 'fpayment', 'id' => 'fpayment');
echo form_open(element('order_action_url', $view), $attributes);

    foreach (element('data', $view) as $key => $value) {
        if (in_array($key, $exclude)) {
            continue;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                echo '<input type="hidden" name="' . $key . '[' . $k . ']" value="' . $v . '" />' . PHP_EOL;
            }
        } else {
            echo '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . PHP_EOL;
        }
    }

    echo '<input type="hidden" name="res_cd" value="' . element('LGD_RESPCODE', $view) . '" />' . PHP_EOL;
    echo '<input type="hidden" name="LGD_PAYKEY" value="' . element('LGD_PAYKEY', $view) . '" />' . PHP_EOL;

echo form_close();
?>

<div>
    <div id="show_progress">
        <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo site_url(VIEW_DIR . 'paymentlib/images/ajax-loader.gif'); ?>" alt="주문완료중" title="주문완료중" /></span>
        <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>
</div>

<script type="text/javascript">
function setLGDResult() {
    setTimeout( function() {
        document.fpayment.submit();
    }, 300);
}
</script>
