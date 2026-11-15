<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_filter')) {
    function nv_block_sn_filter($block_config) {
        return '<div class="sn-block sn-block-filter"><strong>Bo loc</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_filter($block_config); }
