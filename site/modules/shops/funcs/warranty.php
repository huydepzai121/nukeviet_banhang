<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$phone = $nv_Request->get_title('phone', 'get', '');
$code = $nv_Request->get_title('code', 'get', '');
$html = '<form class="sn-form" method="get"><input type="hidden" name="language" value="vi"><input type="hidden" name="nv" value="shops"><input type="hidden" name="op" value="warranty"><label>Số điện thoại<input name="phone" value="' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '"></label><label>Mã đơn<input name="code" value="' . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . '"></label><button type="submit">Tra cứu</button></form>';
if ($phone !== '' || $code !== '') {
    $sql = 'SELECT o.order_code, o.order_phone, o.order_time, o.transaction_status, r.vi_title, r.warranty_months FROM ' . $db_config['prefix'] . '_shops_orders o INNER JOIN ' . $db_config['prefix'] . '_shops_orders_id i ON i.order_id=o.order_id INNER JOIN ' . $db_config['prefix'] . '_shops_rows r ON r.id=i.proid WHERE o.transaction_status=4';
    if ($phone !== '') {
        $sql .= ' AND o.order_phone=' . $db->quote($phone);
    }
    if ($code !== '') {
        $sql .= ' AND o.order_code=' . $db->quote($code);
    }
    $rows = $db->query($sql . ' LIMIT 20')->fetchAll();
    if (!$rows) {
        $html .= '<p class="sn-note">Không thấy đơn đã hoàn tất khớp thông tin. Không hiển thị đơn của người khác.</p>';
    }
    foreach ($rows as $row) {
        $until = $row['order_time'] + ((int) $row['warranty_months'] * 30 * 86400);
        $html .= '<article class="sn-note"><strong>' . htmlspecialchars($row['vi_title'], ENT_QUOTES, 'UTF-8') . '</strong><p>Đơn ' . htmlspecialchars($row['order_code'], ENT_QUOTES, 'UTF-8') . ' · bảo hành đến ' . date('d/m/Y', $until) . '</p></article>';
    }
}
nv_shops_page('Tra cứu bảo hành', $html);
