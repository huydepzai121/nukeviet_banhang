<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_lookup')) {
    function nv_block_sn_lookup($block_config) {
        return '<div class="sn-block sn-block-lookup"><strong>Tra cuu don</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_lookup($block_config); }
