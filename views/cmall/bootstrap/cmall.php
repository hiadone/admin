<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h5>추천상품</h5>
<div class="cmall-list">
    <div class="row">
    <?php
    if (element('type1', $view)) {
        foreach (element('type1', $view) as $item) {
    ?>
        <div class="col-xs-6 col-md-3">
            <a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
                <img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 180, 180); ?>" class="thumbnail" style="width:180px;height:180px;" />
            </a>
            <p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
            <p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
            <ul class="cmall-detail">
                <li><i class="fa fa-heart"></i> 찜 <?php echo number_format(element('cit_wish_count', $item)); ?></li>
                <li><i class="fa fa-shopping-cart"></i> 구매 <?php echo number_format(element('cit_sell_count', $item)); ?></li>
                <li class="cmall-price pull-right"> <?php echo number_format(element('cit_price', $item)); ?></li>
            </ul>
        </div>
    <?php
        }
    }
    ?>
    </div>
</div>

<h5>인기상품</h5>
<div class="cmall-list">
    <div class="row">
    <?php
    if (element('type2', $view)) {
        foreach (element('type2', $view) as $item) {
    ?>
        <div class="col-xs-6 col-md-3">
            <a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
                <img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 180, 180); ?>" class="thumbnail" style="width:180px;height:180px;" />
            </a>
            <p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
            <p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
            <ul class="cmall-detail">
                <li><i class="fa fa-heart"></i> 찜 <?php echo number_format(element('cit_wish_count', $item)); ?></li>
                <li><i class="fa fa-shopping-cart"></i> 구매 <?php echo number_format(element('cit_sell_count', $item)); ?></li>
                <li class="cmall-price pull-right"> <?php echo number_format(element('cit_price', $item)); ?></li>
            </ul>
        </div>
    <?php
        }
    }
    ?>
    </div>
</div>

<h5>최신상품</h5>
<div class="cmall-list">
    <div class="row">
    <?php
    if (element('type3', $view)) {
        foreach (element('type3', $view) as $item) {
    ?>
        <div class="col-xs-6 col-md-3">
            <a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
                <img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 180, 180); ?>" class="thumbnail" style="width:180px;height:180px;" />
            </a>
            <p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
            <p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
            <ul class="cmall-detail">
                <li><i class="fa fa-heart"></i> 찜 <?php echo number_format(element('cit_wish_count', $item)); ?></li>
                <li><i class="fa fa-shopping-cart"></i> 구매 <?php echo number_format(element('cit_sell_count', $item)); ?></li>
                <li class="cmall-price pull-right"> <?php echo number_format(element('cit_price', $item)); ?></li>
            </ul>
        </div>
    <?php
        }
    }
    ?>
    </div>
</div>

<h5>할인상품</h5>
<div class="cmall-list">
    <div class="row">
    <?php
    if (element('type4', $view)) {
        foreach (element('type4', $view) as $item) {
    ?>
        <div class="col-xs-6 col-md-3">
            <a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
                <img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 180, 180); ?>" class="thumbnail" style="width:180px;height:180px;" />
            </a>
            <p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
            <p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
            <ul class="cmall-detail">
                <li><i class="fa fa-heart"></i> 찜 <?php echo number_format(element('cit_wish_count', $item)); ?></li>
                <li><i class="fa fa-shopping-cart"></i> 구매 <?php echo number_format(element('cit_sell_count', $item)); ?></li>
                <li class="cmall-price pull-right"> <?php echo number_format(element('cit_price', $item)); ?></li>
            </ul>
        </div>
    <?php
        }
    }
    ?>
    </div>
</div>
