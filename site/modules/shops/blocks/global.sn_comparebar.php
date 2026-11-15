<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_comparebar')) {
    function nv_block_sn_comparebar($block_config) {
        return '<div class="sn-block sn-block-comparebar"><strong>So sanh</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_comparebar($block_config); }
