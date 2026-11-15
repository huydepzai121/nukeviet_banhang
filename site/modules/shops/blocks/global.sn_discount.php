<?php
if (!defined('NV_MAINFILE')) { die('Stop!!!'); }
if (!function_exists('nv_block_sn_discount')) {
    function nv_block_sn_discount($block_config) {
        return '<div class="sn-block sn-block-discount"><strong>Giam gia nhieu</strong></div>';
    }
}
if (defined('NV_SYSTEM')) { $content = nv_block_sn_discount($block_config); }
