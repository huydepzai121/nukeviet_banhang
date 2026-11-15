<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$gateway = $nv_Request->get_title('gateway', 'post', '');
$orderId = $nv_Request->get_int('order_id', 'post', 0);
$amount = (float) $nv_Request->get_string('amount', 'post', '0');
$txn = $nv_Request->get_title('txn', 'post', '');
$sign = $nv_Request->get_title('sign', 'post', '');
$expect = hash('sha256', $gateway . '|' . $orderId . '|' . $amount . '|' . $txn . '|sandbox');
header('Content-Type: application/json; charset=utf-8');
if (!hash_equals($expect, $sign) || $orderId < 1 || $txn === '') {
    nv_jsonOutput(['ok' => 0, 'error' => 'invalid-signature']);
}
$dup = $db->query('SELECT id FROM ' . $db_config['prefix'] . '_shops_paylog WHERE txn=' . $db->quote($txn))->fetchColumn();
if ($dup) {
    nv_jsonOutput(['ok' => 1, 'duplicate' => 1]);
}
$db->query('INSERT INTO ' . $db_config['prefix'] . '_shops_paylog (order_id, gateway, amount, txn, status, payload, add_time) VALUES (' . $orderId . ',' . $db->quote($gateway) . ',' . $amount . ',' . $db->quote($txn) . ",'ok'," . $db->quote('sandbox') . ',' . NV_CURRENTTIME . ')');
$db->query('UPDATE ' . $db_config['prefix'] . '_shops_orders SET transaction_status=4, pay_gateway=' . $db->quote($gateway) . ' WHERE order_id=' . $orderId);
nv_jsonOutput(['ok' => 1, 'order_id' => $orderId]);
