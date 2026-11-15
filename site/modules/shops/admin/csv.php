<?php

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if ($nv_Request->get_int('export', 'get', 0)) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="san-pham.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id', 'title', 'price', 'saleprice', 'stock', 'brand_id']);
    $result = $db->query('SELECT id, vi_title, product_price, saleprice, product_number, brand_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows ORDER BY id');
    while ($row = $result->fetch()) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}

$page_title = 'Nhập xuất sản phẩm';
$msg = '';
if ($nv_Request->isset_request('import', 'post') && isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
    $handle = fopen($_FILES['file']['tmp_name'], 'r');
    $header = fgetcsv($handle);
    $n = 0;
    while (($cols = fgetcsv($handle)) !== false) {
        $id = (int) ($cols[0] ?? 0);
        $price = (float) ($cols[2] ?? 0);
        if ($id > 0 && $price > 0) {
            $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_price=' . $price . ' WHERE id=' . $id);
            $n++;
        }
    }
    fclose($handle);
    $msg = '<p>Đã cập nhật giá ' . $n . ' dòng.</p>';
}
$html = $msg . '<p><a class="btn btn-primary" href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=csv&export=1">Xuất CSV</a></p>'
    . '<form method="post" enctype="multipart/form-data"><input type="file" name="file" accept=".csv"><button class="btn btn-default" name="import" value="1">Nhập giá từ CSV</button></form>';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($html);
include NV_ROOTDIR . '/includes/footer.php';
