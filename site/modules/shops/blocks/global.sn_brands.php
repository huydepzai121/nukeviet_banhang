<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_brands')) {
    function nv_block_sn_brands($block_config) {
        return '<div class="sn-block sn-block-brands"><strong>Thuong hieu</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_brands($block_config); }
