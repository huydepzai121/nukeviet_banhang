<?php

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = 'Báo cáo doanh thu';
$rows = $db->query('SELECT transaction_status, COUNT(*) AS num, SUM(order_total) AS total FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders GROUP BY transaction_status')->fetchAll();
$labels = ['-1' => 'Chờ duyệt', '0' => 'Chưa thanh toán', '1' => 'Đang thanh toán', '2' => 'Đã thanh toán, đang giữ', '3' => 'Hoàn / huỷ', '4' => 'Đã nhận tiền'];
$html = '<table class="table table-striped"><tr><th>Trạng thái</th><th>Số đơn</th><th>Tổng tiền</th></tr>';
foreach ($rows as $row) {
    $html .= '<tr><td>' . ($labels[(string) $row['transaction_status']] ?? $row['transaction_status']) . '</td><td>' . (int) $row['num'] . '</td><td>' . number_format((float) $row['total'], 0, ',', '.') . '</td></tr>';
}
$html .= '</table>';
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($html);
include NV_ROOTDIR . '/includes/footer.php';
