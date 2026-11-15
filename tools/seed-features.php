<?php

/**
 * Bổ sung dữ liệu cho các tính năng mới. Gọi từ seed-sangnha.php sau khi đã có sản phẩm.
 */
declare(strict_types=1);

if (!isset($pdo, $prefix, $shops, $now)) {
    fwrite(STDERR, "seed-features.php cần được gọi từ seed-sangnha.php\n");
    exit(1);
}

$pdo->exec('DELETE FROM ' . $shops . '_brands');
$pdo->exec('DELETE FROM ' . $shops . '_stores');
$pdo->exec('DELETE FROM ' . $shops . '_stock');
$pdo->exec('DELETE FROM ' . $shops . '_flash');
$pdo->exec('DELETE FROM ' . $shops . '_flash_item');
$pdo->exec('DELETE FROM ' . $shops . '_spec');
$pdo->exec('DELETE FROM ' . $shops . '_qa');
$pdo->exec('DELETE FROM ' . $shops . '_together');
$pdo->exec('DELETE FROM ' . $shops . '_province');
$pdo->exec('DELETE FROM ' . $shops . '_ward');
$pdo->exec('DELETE FROM ' . $shops . '_banner');
$pdo->exec('DELETE FROM ' . $shops . '_gateway');

$brands = ['Samsung', 'LG', 'Sony', 'Apple', 'Xiaomi', 'OPPO', 'Dell', 'Asus', 'Panasonic', 'Daikin', 'Toshiba', 'Electrolux'];
$brandId = [];
$w = 0;
$insB = $pdo->prepare('INSERT INTO ' . $shops . '_brands (weight, status, logo, vi_title, vi_alias, vi_description) VALUES (:w,1,\'\',:t,:a,:d)');
foreach ($brands as $name) {
    $w++;
    $alias = strtolower($name);
    $insB->execute(['w' => $w, 't' => $name, 'a' => $alias, 'd' => 'Thương hiệu ' . $name . '. Dữ liệu mẫu Sáng Nhà.']);
    $brandId[$name] = (int) $pdo->lastInsertId();
}

$products = $pdo->query('SELECT id, vi_title, product_price, saleprice FROM ' . $shops . '_rows')->fetchAll();
$upd = $pdo->prepare('UPDATE ' . $shops . '_rows SET brand_id=:b, warranty_months=:m, vi_search=:s WHERE id=:id');
foreach ($products as $row) {
    $brand = 0;
    foreach ($brandId as $name => $id) {
        if (stripos($row['vi_title'], $name) !== false) {
            $brand = $id;
            break;
        }
    }
    if ($brand === 0) {
        $brand = $brandId['Samsung'];
    }
    $fold = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $row['vi_title']) ?: $row['vi_title'];
    $upd->execute([
        'b' => $brand,
        'm' => 12 + ((int) $row['id'] % 24),
        's' => strtolower($fold),
        'id' => $row['id'],
    ]);
}

$stores = [
    ['Sáng Nhà Quận 1', '0901000001', '[Địa chỉ] Nguyễn Huệ, Quận 1', 'TP. Hồ Chí Minh', '8:00–21:30', 1],
    ['Sáng Nhà Hoàn Kiếm', '0901000002', '[Địa chỉ] Hàng Bài, Hoàn Kiếm', 'Hà Nội', '8:00–21:00', 0],
    ['Sáng Nhà Hải Châu', '0901000003', '[Địa chỉ] Bạch Đằng, Hải Châu', 'Đà Nẵng', '8:30–21:00', 0],
];
$insS = $pdo->prepare('INSERT INTO ' . $shops . '_stores (title, phone, address, province, hours, lat, lng, is_default, status) VALUES (:t,:p,:a,:pr,:h,10.77,106.7,:d,1)');
$storeIds = [];
foreach ($stores as $s) {
    $insS->execute(['t' => $s[0], 'p' => $s[1], 'a' => $s[2], 'pr' => $s[3], 'h' => $s[4], 'd' => $s[5]]);
    $storeIds[] = (int) $pdo->lastInsertId();
}
$insStock = $pdo->prepare('INSERT INTO ' . $shops . '_stock (pro_id, listgroup, store_id, quantity) VALUES (:p,\'\',:s,:q)');
foreach ($products as $i => $row) {
    if ($i > 80) {
        break;
    }
    foreach ($storeIds as $k => $sid) {
        $insStock->execute(['p' => $row['id'], 's' => $sid, 'q' => ($k === 0 ? 4 : 2)]);
    }
}

$pdo->prepare('INSERT INTO ' . $shops . '_flash (title, begin_time, end_time, status) VALUES (:t,:b,:e,1)')->execute([
    't' => 'Flash sale cuối tuần',
    'b' => $now - 3600,
    'e' => $now + 6 * 3600,
]);
$flashId = (int) $pdo->lastInsertId();
$insF = $pdo->prepare('INSERT INTO ' . $shops . '_flash_item (flash_id, pro_id, price, slots, sold) VALUES (:f,:p,:price,20,3)');
foreach (array_slice($products, 0, 8) as $row) {
    $price = max(1000, (int) (($row['saleprice'] > 0 ? $row['saleprice'] : $row['product_price']) * 0.85));
    $insF->execute(['f' => $flashId, 'p' => $row['id'], 'price' => $price]);
}

$specSets = [
    'smartphone' => [['Màn hình', '6.5 inch Super AMOLED'], ['Bộ nhớ', '128GB'], ['Camera', '50MP + 5MP'], ['Pin', '5000 mAh']],
    'dien-thoai-pho-thong' => [['Màn hình', '2.4 inch'], ['Pin', '1000 mAh'], ['Kết nối', '2G'], ['Bàn phím', 'Vật lý']],
    'laptop' => [['Màn hình', '15.6 inch Full HD'], ['CPU', 'Core i5'], ['RAM', '16GB'], ['Ổ cứng', '512GB SSD']],
    'tablet' => [['Màn hình', '10.9 inch'], ['Bộ nhớ', '128GB'], ['Kết nối', 'Wi-Fi'], ['Pin', '8000 mAh']],
    'tu-lanh' => [['Dung tích', '300 lít'], ['Công nghệ', 'Inverter'], ['Ngăn đá', 'Trên'], ['Điện năng', 'Khoảng 1 kWh/ngày']],
    'may-giat' => [['Khối lượng', '9 kg'], ['Cửa', 'Trước'], ['Công nghệ', 'Inverter'], ['Vắt', '1400 vòng/phút']],
    'dieu-hoa' => [['Công suất', '12000 BTU'], ['Loại', '1 chiều Inverter'], ['Gas', 'R32'], ['Phạm vi', 'Phòng 15–20 m²']],
    'smart-tv' => [['Kích thước', '55 inch'], ['Độ phân giải', '4K'], ['Hệ điều hành', 'Google TV'], ['Cổng', 'HDMI, USB']],
    'man-hinh-may-tinh' => [['Kích thước', '27 inch'], ['Tấm nền', 'IPS'], ['Tần số', '75 Hz'], ['Cổng', 'HDMI']],
    'tai-nghe' => [['Kiểu', 'Chụp tai'], ['Kết nối', 'Bluetooth 5.3'], ['Pin', '30 giờ'], ['Micro', 'Có']],
    'dong-ho' => [['Màn hình', '1.4 inch AMOLED'], ['Chống nước', '5 ATM'], ['Pin', '2 ngày'], ['Cảm biến', 'Nhịp tim']],
    'noi-com' => [['Dung tích', '1.8 lít'], ['Lòng nồi', 'Chống dính'], ['Chế độ', 'Nấu, hâm, hấp'], ['Công suất', '700 W']],
];
$aliases = [];
foreach ($pdo->query('SELECT r.id, c.vi_alias FROM ' . $shops . '_rows r LEFT JOIN ' . $shops . '_catalogs c ON c.catid=r.listcatid') as $row) {
    $aliases[(int) $row['id']] = (string) $row['vi_alias'];
}
$insSpec = $pdo->prepare('INSERT INTO ' . $shops . '_spec (pro_id, name, value, filterable) VALUES (:p,:n,:v,1)');
foreach (array_slice($products, 0, 120) as $row) {
    $pairs = $specSets[$aliases[(int) $row['id']] ?? ''] ?? [['Màn hình / công suất', 'Theo model'], ['Công nghệ', 'Inverter'], ['Xuất xứ', 'Lắp ráp']];
    foreach ($pairs as $pair) {
        $insSpec->execute(['p' => $row['id'], 'n' => $pair[0], 'v' => $pair[1]]);
    }
    $insSpec->execute(['p' => $row['id'], 'n' => 'Bảo hành', 'v' => '12 tháng']);
}

$insQa = $pdo->prepare('INSERT INTO ' . $shops . '_qa (pro_id, sender, question, answer, status, add_time) VALUES (:p,:s,:q,:a,1,:t)');
for ($i = 0; $i < 40; $i++) {
    $row = $products[$i];
    $insQa->execute([
        'p' => $row['id'],
        's' => 'Khách mẫu',
        'q' => 'Máy này có tiết kiệm điện không?',
        'a' => 'Bản mẫu có nhãn năng lượng giả định. Nhân viên sẽ xác nhận khi lên đơn thật.',
        't' => $now - $i * 1000,
    ]);
}

$insT = $pdo->prepare('INSERT IGNORE INTO ' . $shops . '_together (pro_id, with_id) VALUES (:a,:b)');
for ($i = 0; $i < 30; $i++) {
    $insT->execute(['a' => $products[$i]['id'], 'b' => $products[$i + 1]['id']]);
}

$provinces = [
    'TP. Hồ Chí Minh' => [30000, ['Phường Bến Nghé', 'Phường Bến Thành']],
    'Hà Nội' => [35000, ['Phường Hàng Bài', 'Phường Tràng Tiền']],
    'Đà Nẵng' => [40000, ['Phường Hải Châu']],
    'Cần Thơ' => [45000, ['Phường Ninh Kiều']],
];
$insP = $pdo->prepare('INSERT INTO ' . $shops . '_province (title, fee) VALUES (:t,:f)');
$insW = $pdo->prepare('INSERT INTO ' . $shops . '_ward (province_id, title) VALUES (:p,:t)');
foreach ($provinces as $name => $info) {
    $insP->execute(['t' => $name, 'f' => $info[0]]);
    $pid = (int) $pdo->lastInsertId();
    foreach ($info[1] as $ward) {
        $insW->execute(['p' => $pid, 't' => $ward]);
    }
}

$gateways = [
    ['cod', 'Trả tiền khi nhận hàng', 1],
    ['sepay', 'Chuyển khoản QR', 1],
    ['vnpay', 'VNPAY', 1],
    ['momo', 'MoMo', 1],
    ['zalopay', 'ZaloPay', 1],
];
$insG = $pdo->prepare('INSERT INTO ' . $shops . '_gateway (code, title, active, sandbox, secret) VALUES (:c,:t,:a,1,:s)');
foreach ($gateways as $g) {
    $insG->execute(['c' => $g[0], 't' => $g[1], 'a' => $g[2], 's' => 'sandbox']);
}

$banners = [
    ['Điện máy cho ngôi nhà sáng', 'Chọn đúng máy, giá minh hoạ rõ ràng.', 'teal', 1],
    ['Suất flash sale đang chạy', 'Hết giờ hoặc hết suất, giá về mức thường.', 'rose', 2],
    ['Nhận tại cửa hàng', 'Xem tồn từng điểm bán trước khi đi.', 'ink', 3],
];
$insBan = $pdo->prepare('INSERT INTO ' . $shops . '_banner (title, subtitle, link, tone, weight) VALUES (:t,:s,\'\',:tone,:w)');
foreach ($banners as $b) {
    $insBan->execute(['t' => $b[0], 's' => $b[1], 'tone' => $b[2], 'w' => $b[3]]);
}

$couponCols = $pdo->query('SHOW COLUMNS FROM ' . $shops . '_coupons')->fetchAll();
$fields = array_column($couponCols, 'Field');
if (in_array('code', $fields, true)) {
    $pdo->exec('DELETE FROM ' . $shops . '_coupons WHERE code=\'WELCOME10\'');
    $data = [
        'code' => 'WELCOME10',
        'type' => 'p',
        'discount' => 10,
        'total_amount' => 0,
        'date_start' => $now - 86400,
        'date_end' => $now + 86400 * 30,
        'uses_per_coupon' => 1000,
        'uses_per_coupon_count' => 0,
        'status' => 1,
    ];
    $cols = [];
    $vals = [];
    $params = [];
    foreach ($data as $col => $val) {
        if (!in_array($col, $fields, true)) {
            continue;
        }
        $cols[] = $col;
        $vals[] = ':' . $col;
        $params[$col] = $val;
    }
    if ($cols) {
        $pdo->prepare('INSERT INTO ' . $shops . '_coupons (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ')')->execute($params);
    }
}

$pdo->exec("UPDATE {$shops}_rows r JOIN {$shops}_catalogs c ON c.catid=r.listcatid SET r.homeimgthumb=3, r.homeimgfile=CASE
 WHEN c.vi_alias IN ('smartphone','dien-thoai-pho-thong') THEN ELT(1 + ((r.id DIV 14) MOD 3), '/themes/sangnha/images/catalog/phone.jpg', '/themes/sangnha/images/catalog/phone-white.jpg', '/themes/sangnha/images/catalog/phone-blue.jpg')
 WHEN c.vi_alias IN ('smart-tv','man-hinh-may-tinh') THEN ELT(1 + ((r.id DIV 14) MOD 2), '/themes/sangnha/images/catalog/tv.jpg', '/themes/sangnha/images/catalog/tv-silver.jpg')
 WHEN c.vi_alias='tu-lanh' THEN ELT(1 + ((r.id DIV 14) MOD 2), '/themes/sangnha/images/catalog/fridge.jpg', '/themes/sangnha/images/catalog/fridge-black.jpg')
 WHEN c.vi_alias='laptop' THEN '/themes/sangnha/images/catalog/laptop.jpg'
 WHEN c.vi_alias='may-tinh-bang' THEN '/themes/sangnha/images/catalog/tablet.jpg'
 WHEN c.vi_alias='loa-tai-nghe' THEN '/themes/sangnha/images/catalog/headphones.jpg'
 WHEN c.vi_alias='dong-ho-thong-minh' THEN '/themes/sangnha/images/catalog/watch.jpg'
 WHEN c.vi_alias='may-lanh' THEN '/themes/sangnha/images/catalog/ac.jpg'
 WHEN c.vi_alias='may-giat' THEN '/themes/sangnha/images/catalog/washer.jpg'
 WHEN c.vi_alias='gia-dung-nha-bep' THEN '/themes/sangnha/images/catalog/cooker.jpg'
 ELSE '/themes/sangnha/images/catalog/phone.jpg' END");

$pdo->exec('UPDATE ' . $prefix . '_config SET config_value=\'sangnha\' WHERE lang=\'vi\' AND module=\'global\' AND config_name=\'site_theme\'');
$pdo->exec('DELETE FROM ' . $prefix . '_vi_modthemes WHERE theme=\'sangnha\'');
$pdo->exec('INSERT INTO ' . $prefix . '_vi_modthemes (func_id, layout, theme) SELECT func_id, layout, \'sangnha\' FROM ' . $prefix . '_vi_modthemes WHERE theme=\'default\'');

echo "Đã nạp thương hiệu, cửa hàng, flash sale, thông số, hỏi đáp, phí tỉnh và cổng thanh toán thử nghiệm.\n";
