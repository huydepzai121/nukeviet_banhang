<?php

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = 'Flash sale';
$rows = $db->query('SELECT f.title, f.begin_time, f.end_time, COUNT(i.id) AS items FROM ' . $db_config['prefix'] . '_' . $module_data . '_flash f LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_flash_item i ON i.flash_id=f.id GROUP BY f.id')->fetchAll();
$html = '<table class="table"><tr><th>Đợt</th><th>Bắt đầu</th><th>Kết thúc</th><th>Sản phẩm</th></tr>';
foreach ($rows as $row) {
    $html .= '<tr><td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</td><td>' . date('d/m/Y H:i', $row['begin_time']) . '</td><td>' . date('d/m/Y H:i', $row['end_time']) . '</td><td>' . (int) $row['items'] . '</td></tr>';
}
$html .= '</table>';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($html);
include NV_ROOTDIR . '/includes/footer.php';
