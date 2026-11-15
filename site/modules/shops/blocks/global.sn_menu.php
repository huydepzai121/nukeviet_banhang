<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_menu')) {
    function nv_block_sn_menu($block_config) {
        return '<div class="sn-block sn-block-menu"><strong>Menu nganh hang</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_menu($block_config); }
