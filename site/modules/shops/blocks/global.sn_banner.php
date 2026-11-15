<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_banner')) {
    function nv_block_sn_banner($block_config) {
        return '<div class="sn-block sn-block-banner"><strong>Banner</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_banner($block_config); }
