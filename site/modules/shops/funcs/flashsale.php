<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$now = NV_CURRENTTIME;
$rows = $db->query('SELECT f.title, f.begin_time, f.end_time, i.price, i.slots, i.sold, r.vi_title, r.vi_alias, c.vi_alias AS cat FROM ' . $db_config['prefix'] . '_shops_flash f INNER JOIN ' . $db_config['prefix'] . '_shops_flash_item i ON i.flash_id=f.id INNER JOIN ' . $db_config['prefix'] . '_shops_rows r ON r.id=i.pro_id LEFT JOIN ' . $db_config['prefix'] . '_shops_catalogs c ON c.catid=r.listcatid WHERE f.status=1 ORDER BY f.begin_time ASC')->fetchAll();
$html = '<div class="sn-flash-row">';
foreach ($rows as $row) {
    $state = ($row['begin_time'] <= $now && $row['end_time'] > $now) ? 'Đang diễn ra' : ($row['begin_time'] > $now ? 'Sắp mở' : 'Đã kết thúc');
    $href = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=shops&amp;' . NV_OP_VARIABLE . '=' . $row['cat'] . '/' . $row['vi_alias'] . $global_config['rewrite_exturl'], true);
    $html .= '<a class="sn-flash-card" href="' . $href . '"><em>' . $state . ' · còn ' . max(0, $row['slots'] - $row['sold']) . ' suất</em><strong>' . htmlspecialchars($row['vi_title'], ENT_QUOTES, 'UTF-8') . '</strong><span>' . nv_shops_money($row['price']) . '</span></a>';
}
$html .= '</div>';
nv_shops_page('Flash sale', $html);
