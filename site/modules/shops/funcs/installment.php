<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$price = $nv_Request->get_int('price', 'post', 10000000);
$down = $nv_Request->get_int('down', 'post', 0);
$months = $nv_Request->get_int('months', 'post', 12);
if ($months < 3) {
    $months = 12;
}
$monthly = max(0, $price - $down) / $months;
$note = '';
if ($nv_Request->isset_request('send', 'post')) {
    $name = $nv_Request->get_title('name', 'post', '');
    $phone = $nv_Request->get_title('phone', 'post', '');
    if ($name !== '' && preg_match('/^0\d{9}$/', $phone)) {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_shops_service (kind, pro_id, name, phone, note, status, add_time) VALUES (\'installment\',0,' . $db->quote($name) . ',' . $db->quote($phone) . ',' . $db->quote($monthly . ' x ' . $months) . ",'new'," . NV_CURRENTTIME . ')');
        $note = '<p class="sn-note">Đã ghi nhận. Nhân viên sẽ liên hệ, chưa kết nối công ty tài chính.</p>';
    } else {
        $note = '<p class="sn-note">Cần họ tên và số điện thoại 10 số.</p>';
    }
}
$html = $note . '<form class="sn-form" method="post"><label>Giá máy<input name="price" value="' . $price . '"></label><label>Trả trước<input name="down" value="' . $down . '"></label><label>Số tháng<input name="months" value="' . $months . '"></label><p>Ước tính mỗi tháng: <strong>' . nv_shops_money($monthly) . '</strong></p><label>Họ tên<input name="name"></label><label>Điện thoại<input name="phone"></label><button name="send" value="1">Gửi đăng ký</button></form>';
nv_shops_page('Ước tính trả góp', $html);
