<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_recent')) {
    function nv_block_sn_recent($block_config) {
        return '<div class="sn-block sn-block-recent"><strong>Da xem</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_recent($block_config); }
