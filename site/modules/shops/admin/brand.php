<?php

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = 'Thương hiệu';
if ($nv_Request->isset_request('save', 'post')) {
    $title = $nv_Request->get_title('title', 'post', '');
    $alias = preg_replace('/[^a-z0-9\-]+/', '-', strtolower($title)) ?: 'brand';
    if ($title !== '') {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_brands (weight, status, logo, vi_title, vi_alias, vi_description) VALUES (1,1,\'\',' . $db->quote($title) . ',' . $db->quote($alias) . ',' . $db->quote('Thương hiệu ' . $title) . ')');
    }
}
$rows = $db->query('SELECT id, vi_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_brands ORDER BY weight, id')->fetchAll();
$html = '<form method="post" class="form-inline"><input class="form-control" name="title" placeholder="Tên thương hiệu"> <button class="btn btn-primary" name="save" value="1">Thêm</button></form><ul>';
foreach ($rows as $row) {
    $html .= '<li>' . htmlspecialchars($row['vi_title'], ENT_QUOTES, 'UTF-8') . '</li>';
}
$html .= '</ul>';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($html);
include NV_ROOTDIR . '/includes/footer.php';
