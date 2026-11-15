<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_best')) {
    function nv_block_sn_best($block_config) {
        return '<div class="sn-block sn-block-best"><strong>Ban chay</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_best($block_config); }
