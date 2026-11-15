<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_suggest')) {
    function nv_block_sn_suggest($block_config) {
        return '<div class="sn-block sn-block-suggest"><strong>Goi y tim kiem</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_suggest($block_config); }
