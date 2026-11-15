<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_stock')) {
    function nv_block_sn_stock($block_config) {
        return '<div class="sn-block sn-block-stock"><strong>Cua hang con hang</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_stock($block_config); }
