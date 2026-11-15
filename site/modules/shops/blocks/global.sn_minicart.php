<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_minicart')) {
    function nv_block_sn_minicart($block_config) {
        return '<div class="sn-block sn-block-minicart"><strong>Gio hang nho</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_minicart($block_config); }
