<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_tabs')) {
    function nv_block_sn_tabs($block_config) {
        return '<div class="sn-block sn-block-tabs"><strong>San pham theo nganh</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_tabs($block_config); }
