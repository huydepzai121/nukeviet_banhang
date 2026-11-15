<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_flash')) {
    function nv_block_sn_flash($block_config) {
        return '<div class="sn-block sn-block-flash"><strong>Flash sale</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_flash($block_config); }
