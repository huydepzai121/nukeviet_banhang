<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$rows = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_shops_stores WHERE status=1 ORDER BY is_default DESC, id ASC')->fetchAll();
$html = '<div class="sn-stores">';
foreach ($rows as $row) {
    $html .= '<article><h2>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</h2><p>' . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . '</p><p>' . htmlspecialchars($row['province'], ENT_QUOTES, 'UTF-8') . ' · ' . htmlspecialchars($row['hours'], ENT_QUOTES, 'UTF-8') . '</p><p>' . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . '</p></article>';
}
$html .= '</div>';
nv_shops_page('Hệ thống cửa hàng', $html);
