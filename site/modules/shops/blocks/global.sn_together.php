<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_together')) {
    function nv_block_sn_together($block_config) {
        return '<div class="sn-block sn-block-together"><strong>Mua cung</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_together($block_config); }
