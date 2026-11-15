<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$note = '';
$estimate = 0;
if ($nv_Request->isset_request('send', 'post')) {
    $model = $nv_Request->get_title('model', 'post', '');
    $condition = $nv_Request->get_int('condition', 'post', 70);
    $name = $nv_Request->get_title('name', 'post', '');
    $phone = $nv_Request->get_title('phone', 'post', '');
    $estimate = 8000000 * max(30, min(100, $condition)) / 100;
    if ($name !== '' && preg_match('/^0\d{9}$/', $phone)) {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_shops_service (kind, pro_id, name, phone, note, status, add_time) VALUES (\'tradein\',0,' . $db->quote($name) . ',' . $db->quote($phone) . ',' . $db->quote($model . ' ' . $condition . '% ~ ' . $estimate) . ",'new'," . NV_CURRENTTIME . ')');
        $note = '<p class="sn-note">Giá thu ước tính ' . nv_shops_money($estimate) . '. Nhân viên sẽ thẩm định, đây chưa phải giá chốt.</p>';
    } else {
        $note = '<p class="sn-note">Cần họ tên và số điện thoại.</p>';
    }
}
$html = $note . '<form class="sn-form" method="post"><label>Dòng máy cũ<input name="model" placeholder="Ví dụ Galaxy A50"></label><label>Tình trạng %<input name="condition" value="70"></label><label>Họ tên<input name="name"></label><label>Điện thoại<input name="phone"></label><button name="send" value="1">Gửi đăng ký</button></form>';
nv_shops_page('Thu cũ đổi mới', $html);
