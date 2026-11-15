<?php

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = 'Cửa hàng';
$rows = $db->query('SELECT title, address, phone, hours FROM ' . $db_config['prefix'] . '_' . $module_data . '_stores ORDER BY id')->fetchAll();
$html = '<table class="table"><tr><th>Cửa hàng</th><th>Địa chỉ</th><th>Điện thoại</th><th>Giờ</th></tr>';
foreach ($rows as $row) {
    $html .= '<tr><td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($row['hours'], ENT_QUOTES, 'UTF-8') . '</td></tr>';
}
$html .= '</table>';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($html);
include NV_ROOTDIR . '/includes/footer.php';
