<?php

/**
 * Nạp dữ liệu mẫu cho cửa hàng điện máy Sáng Nhà.
 *
 * Chạy lại được: xoá ngành hàng, sản phẩm, đơn, đánh giá và thành viên mẫu
 * rồi tạo lại. Không nằm trong gói module shops. Site vẫn chạy khi chưa nạp.
 *
 *   php tools/seed-sangnha.php
 *
 * Cần site NukeViet đã cài và module shops đã được cài.
 * Ảnh minh hoạ tự vẽ bằng GD, ghi vào site/uploads/shops/seed/ (không đưa vào git).
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Chỉ chạy từ dòng lệnh.\n");
    exit(1);
}

$root = dirname(__DIR__);
$configFile = $root . '/site/config.php';
if (!is_file($configFile)) {
    fwrite(STDERR, "Chưa thấy site/config.php. Hãy cài NukeViet trước.\n");
    exit(1);
}

$config = file_get_contents($configFile);
$pick = static function (string $key) use ($config): string {
    if (!preg_match("/\\['" . preg_quote($key, '/') . "'\\]\\s*=\\s*'([^']*)'/", $config, $m)) {
        fwrite(STDERR, "Không đọc được {$key} trong site/config.php.\n");
        exit(1);
    }
    return $m[1];
};

$dsn = 'mysql:host=' . $pick('dbhost') . ';dbname=' . $pick('dbname') . ';charset=utf8mb4';
$pdo = new PDO($dsn, $pick('dbuname'), $pick('dbpass'), [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$prefix = $pick('prefix');
$shops = $prefix . '_shops';

$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
if (!in_array($shops . '_rows', $tables, true)) {
    fwrite(STDERR, "Chưa có bảng shops. Hãy cài module shops trong quản trị trước khi nạp dữ liệu mẫu.\n");
    exit(1);
}

const SAMPLE_DOMAIN = 'mau.sangnha.example';
const SAMPLE_PASSWORD = 'Mau123a!';
const PRODUCT_COUNT = 420;
const CUSTOMER_COUNT = 130;
const ORDER_COUNT = 260;
const REVIEW_COUNT = 450;

$now = time();

$pdo->beginTransaction();

$sampleUsers = $pdo->prepare('SELECT userid FROM ' . $prefix . '_users WHERE email LIKE :mail');
$sampleUsers->execute(['mail' => '%@' . SAMPLE_DOMAIN]);
$sampleIds = $sampleUsers->fetchAll(PDO::FETCH_COLUMN);
if ($sampleIds) {
    $idList = implode(',', array_map('intval', $sampleIds));
    $pdo->exec('DELETE FROM ' . $prefix . '_users_groups_users WHERE userid IN (' . $idList . ')');
    if (in_array($prefix . '_users_info', $tables, true)) {
        $pdo->exec('DELETE FROM ' . $prefix . '_users_info WHERE userid IN (' . $idList . ')');
    }
    $pdo->exec('DELETE FROM ' . $prefix . '_users WHERE userid IN (' . $idList . ')');
}

foreach ([
    'orders_id_group', 'orders_id', 'orders_shipping', 'orders', 'review',
    'group_items', 'group_quantity', 'block', 'rows', 'catalogs',
] as $table) {
    $name = $shops . '_' . $table;
    if (in_array($name, $tables, true)) {
        $pdo->exec('DELETE FROM ' . $name);
    }
}

$unitId = $pdo->query('SELECT id FROM ' . $shops . '_units WHERE vi_title = ' . $pdo->quote('Chiếc') . ' LIMIT 1')->fetchColumn();
if (!$unitId) {
    $pdo->exec('INSERT INTO ' . $shops . '_units (vi_title, vi_note) VALUES (' . $pdo->quote('Chiếc') . ', ' . $pdo->quote('Đơn vị mẫu') . ')');
    $unitId = (int) $pdo->lastInsertId();
}
$unitId = (int) $unitId;

$setConfig = $pdo->prepare('UPDATE ' . $prefix . '_config SET config_value = :v WHERE lang = \'vi\' AND module = \'shops\' AND config_name = :n');
foreach ([
    'saleprice_active' => '1',
    'home_data' => 'all',
    'home_view' => 'viewgrid',
    'per_page' => '20',
    'per_row' => '4',
    'image_size' => '240x180',
    'active_price' => '1',
    'active_order' => '1',
    'active_guest_order' => '1',
    'review_active' => '1',
    'money_unit' => 'VND',
] as $name => $value) {
    $setConfig->execute(['v' => $value, 'n' => $name]);
}

$tree = [
    ['Điện thoại', 'dien-thoai', [
        ['Smartphone', 'smartphone'],
        ['Điện thoại phổ thông', 'dien-thoai-pho-thong'],
    ]],
    ['Máy tính', 'may-tinh', [
        ['Laptop', 'laptop'],
        ['Máy tính bảng', 'may-tinh-bang'],
        ['Màn hình máy tính', 'man-hinh-may-tinh'],
    ]],
    ['Âm thanh và đồng hồ', 'am-thanh-dong-ho', [
        ['Loa và tai nghe', 'loa-tai-nghe'],
        ['Đồng hồ thông minh', 'dong-ho-thong-minh'],
    ]],
    ['Tivi', 'tivi', [
        ['Smart TV', 'smart-tv'],
    ]],
    ['Điện lạnh', 'dien-lanh', [
        ['Máy lạnh', 'may-lanh'],
        ['Tủ lạnh', 'tu-lanh'],
        ['Máy giặt', 'may-giat'],
    ]],
    ['Gia dụng và linh kiện', 'gia-dung-linh-kien', [
        ['Gia dụng nhà bếp', 'gia-dung-nha-bep'],
        ['Linh kiện máy tính', 'linh-kien-may-tinh'],
        ['SIM và thẻ cào', 'sim-the-cao'],
    ]],
];

$insertCat = $pdo->prepare(
    'INSERT INTO ' . $shops . '_catalogs (
        parentid, image, weight, sort, lev, viewcat, numsubcat, subcatid, inhome, numlinks, newday,
        typeprice, form, group_price, viewdescriptionhtml, admins, add_time, edit_time, groups_view,
        cat_allow_point, cat_number_point, cat_number_product,
        vi_title, vi_title_custom, vi_alias, vi_description, vi_descriptionhtml, vi_keywords, vi_tag_description
    ) VALUES (
        :parentid, \'\', :weight, 0, 0, :viewcat, 0, \'\', 1, 8, 7,
        0, \'\', \'\', 0, \'\', :add_time, :add_time, \'6\',
        0, 0, 0,
        :title, :title, :alias, :description, :descriptionhtml, :keywords, :description
    )'
);

$leaves = [];
$weightParent = 0;
foreach ($tree as $parent) {
    $weightParent++;
    $insertCat->execute([
        'parentid' => 0,
        'weight' => $weightParent,
        'viewcat' => 'view_home_cat',
        'add_time' => $now,
        'title' => $parent[0],
        'alias' => $parent[1],
        'description' => 'Ngành hàng ' . $parent[0] . '. Dữ liệu mẫu của Sáng Nhà.',
        'descriptionhtml' => '<p>Dữ liệu mẫu. Ngành ' . htmlspecialchars($parent[0], ENT_QUOTES, 'UTF-8') . '.</p>',
        'keywords' => $parent[0] . ', điện máy, Sáng Nhà, dữ liệu mẫu',
    ]);
    $parentId = (int) $pdo->lastInsertId();
    $weightChild = 0;
    foreach ($parent[2] as $child) {
        $weightChild++;
        $insertCat->execute([
            'parentid' => $parentId,
            'weight' => $weightChild,
            'viewcat' => 'viewgrid',
            'add_time' => $now,
            'title' => $child[0],
            'alias' => $child[1],
            'description' => $child[0] . '. Dữ liệu mẫu — giá minh hoạ.',
            'descriptionhtml' => '<p>Dữ liệu mẫu. ' . htmlspecialchars($child[0], ENT_QUOTES, 'UTF-8') . '.</p>',
            'keywords' => $child[0] . ', Sáng Nhà',
        ]);
        $leaves[] = [
            'id' => (int) $pdo->lastInsertId(),
            'title' => $child[0],
            'alias' => $child[1],
        ];
    }
}

$order = 0;
$fix = static function (int $parent, int $lev) use (&$fix, &$order, $pdo, $shops): void {
    $rows = $pdo->query('SELECT catid FROM ' . $shops . '_catalogs WHERE parentid = ' . $parent . ' ORDER BY weight ASC')->fetchAll();
    $lev = $parent > 0 ? $lev + 1 : 0;
    $weight = 0;
    $ids = [];
    foreach ($rows as $row) {
        $order++;
        $weight++;
        $ids[] = (int) $row['catid'];
        $pdo->exec('UPDATE ' . $shops . '_catalogs SET weight = ' . $weight . ', sort = ' . $order . ', lev = ' . $lev . ' WHERE catid = ' . (int) $row['catid']);
        $fix((int) $row['catid'], $lev);
    }
    if ($parent > 0) {
        $pdo->exec(
            'UPDATE ' . $shops . '_catalogs SET numsubcat = ' . $weight . ', subcatid = ' . $pdo->quote(implode(',', $ids)) . ' WHERE catid = ' . $parent
        );
    }
};
$fix(0, 0);

$lines = [
    'smartphone' => [
        ['Samsung', 'Galaxy A%s', [15, 25, 35, 55, 73], ['128GB', '256GB'], 3990000, 15990000],
        ['Xiaomi', 'Redmi Note %s', [12, 13, 14], ['128GB', '256GB'], 3490000, 9990000],
        ['OPPO', 'Reno %s', [8, 10, 11, 12], ['256GB'], 6990000, 14990000],
        ['Apple', 'iPhone %s', [11, 12, 13, 14, 15], ['128GB', '256GB'], 10990000, 32990000],
    ],
    'dien-thoai-pho-thong' => [
        ['Nokia', '110 %s', ['4G', 'Pro'], [''], 450000, 1290000],
        ['Masstel', 'Izi %s', [10, 20, 50], [''], 350000, 890000],
    ],
    'laptop' => [
        ['Dell', 'Inspiron %s', [14, 15, 16], ['i5', 'i7'], 12990000, 28990000],
        ['Asus', 'Vivobook %s', [14, 15], ['i3', 'i5'], 9990000, 19990000],
        ['Lenovo', 'IdeaPad %s', [3, 5], ['Ryzen 5', 'Ryzen 7'], 11490000, 24990000],
        ['Apple', 'MacBook Air %s', ['M1', 'M2', 'M3'], ['256GB', '512GB'], 18990000, 35990000],
    ],
    'may-tinh-bang' => [
        ['Samsung', 'Galaxy Tab %s', ['A9', 'S9', 'S10'], ['64GB', '128GB'], 3990000, 18990000],
        ['Apple', 'iPad %s', ['Gen 9', 'Gen 10', 'Air'], ['64GB', '256GB'], 7990000, 22990000],
        ['Xiaomi', 'Pad %s', [6, 7], ['128GB'], 4990000, 9990000],
    ],
    'man-hinh-may-tinh' => [
        ['LG', 'UltraGear %s', [24, 27, 32], ['IPS', 'VA'], 2990000, 12990000],
        ['Dell', 'SE%s', [2422, 2723, 3223], ['Full HD'], 2490000, 8990000],
        ['Asus', 'ProArt %s', [24, 27], ['2K', '4K'], 5490000, 15990000],
    ],
    'loa-tai-nghe' => [
        ['Sony', 'WH-%s', [720, 1000], ['Đen', 'Trắng'], 1990000, 7990000],
        ['JBL', 'Flip %s', [5, 6], [''], 1490000, 3490000],
        ['SoundPEATS', 'Air %s', [3, 4, 5], [''], 390000, 1290000],
    ],
    'dong-ho-thong-minh' => [
        ['Apple', 'Watch SE %s', ['40mm', '44mm'], [''], 5990000, 9990000],
        ['Samsung', 'Galaxy Watch %s', [5, 6, 7], ['40mm', '44mm'], 3990000, 8990000],
        ['Xiaomi', 'Band %s', [8, 9], [''], 690000, 1490000],
    ],
    'smart-tv' => [
        ['Samsung', 'Crystal UHD %s', [43, 50, 55, 65], ['4K'], 7490000, 24990000],
        ['LG', 'webOS %s', [43, 50, 55], ['4K'], 6990000, 19990000],
        ['Sony', 'Bravia %s', [43, 55], ['4K'], 9990000, 28990000],
        ['TCL', 'Google TV %s', [43, 50, 55, 65], [''], 5490000, 15990000],
    ],
    'may-lanh' => [
        ['Daikin', 'Inverter %s HP', ['1', '1.5', '2'], ['1 chiều', '2 chiều'], 9990000, 22990000],
        ['Panasonic', 'Aero %s HP', ['1', '1.5', '2'], ['1 chiều'], 8990000, 18990000],
        ['Casper', 'LC-%s', [9, 12, 18], [''], 6490000, 13990000],
    ],
    'tu-lanh' => [
        ['Samsung', 'Inverter %s lít', [236, 300, 360, 490], ['Ngăn đá trên'], 6990000, 22990000],
        ['LG', 'Linear %s lít', [255, 315, 470], ['Ngăn đá dưới'], 7990000, 24990000],
        ['Toshiba', 'GR-%s', [20, 25, 30], [''], 5490000, 12990000],
    ],
    'may-giat' => [
        ['LG', 'Cửa trước %s kg', [8, 9, 10, 12], ['Inverter'], 7490000, 16990000],
        ['Samsung', 'Cửa trên %s kg', [8, 9, 10], [''], 4990000, 9990000],
        ['Electrolux', 'UltimateCare %s kg', [8, 9, 10], [''], 8990000, 18990000],
    ],
    'gia-dung-nha-bep' => [
        ['Sunhouse', 'Nồi cơm %s lít', ['1.2', '1.8'], [''], 490000, 1490000],
        ['Sharp', 'Lò vi sóng %s lít', [20, 23, 25], [''], 1290000, 2990000],
        ['Kangaroo', 'Lọc nước %s', ['KG100', 'KG10A'], [''], 3490000, 8990000],
    ],
    'linh-kien-may-tinh' => [
        ['Kingston', 'RAM DDR4 %s', ['8GB', '16GB', '32GB'], [''], 490000, 2490000],
        ['WD', 'SSD %s', ['256GB', '512GB', '1TB'], [''], 690000, 2490000],
        ['Logitech', 'Chuột %s', ['M185', 'M331', 'MX'], [''], 190000, 1890000],
    ],
    'sim-the-cao' => [
        ['Viettel', 'Thẻ cào %s', ['50K', '100K', '200K', '500K'], [''], 50000, 500000],
        ['Mobifone', 'SIM %s', ['trả trước', 'data'], [''], 50000, 150000],
        ['Vinaphone', 'Thẻ cào %s', ['20K', '50K', '100K'], [''], 20000, 100000],
    ],
];

$anchor = [
    'title' => 'Samsung Galaxy A15 128GB',
    'alias' => 'samsung-galaxy-a15-128gb',
    'cat' => 'smartphone',
    'price' => 5490000,
    'sale' => 4990000,
    'stock' => 12,
    'code' => 'SN-A15',
];

$imageDir = $root . '/site/uploads/shops/seed';
if (!is_dir($imageDir) && !mkdir($imageDir, 0775, true) && !is_dir($imageDir)) {
    fwrite(STDERR, "Không tạo được thư mục ảnh.\n");
    exit(1);
}
foreach (glob($imageDir . '/*.jpg') ?: [] as $old) {
    unlink($old);
}

$insertRow = $pdo->prepare(
    'INSERT INTO ' . $shops . '_rows (
        listcatid, user_id, addtime, edittime, status, publtime, exptime, archive,
        product_code, product_number, product_price, price_config, saleprice, money_unit, product_unit,
        product_weight, weight_unit, discount_id, homeimgfile, homeimgthumb, homeimgalt, otherimage,
        imgposition, copyright, gift_from, gift_to, inhome, allowed_comm, allowed_rating, ratingdetail,
        allowed_send, allowed_print, allowed_save, hitstotal, hitscm, hitslm, num_sell, showprice,
        vi_title, vi_alias, vi_hometext, vi_bodytext, vi_gift_content, vi_address, vi_tag_title, vi_tag_description
    ) VALUES (
        :listcatid, 1, :addtime, :addtime, 1, :publtime, 0, 0,
        :code, :stock, :price, \'\', :sale, \'VND\', :unit,
        :weight, \'kg\', 0, :image, 2, :title, \'\',
        1, 0, 0, 0, 1, 1, 1, \'\',
        0, 1, 1, :hits, 0, 0, :sold, 1,
        :title, :alias, :hometext, :body, \'\', \'\', :title, :hometext
    )'
);

$paint = static function (string $file, string $caption, int $hue): void {
    $im = imagecreatetruecolor(640, 640);
    imageantialias($im, true);
    $paper = imagecolorallocate($im, 246, 248, 248);
    $white = imagecolorallocate($im, 255, 255, 255);
    $ink = imagecolorallocate($im, 20, 37, 42);
    $r = (int) (40 + (sin(deg2rad($hue)) + 1) * 70);
    $g = (int) (80 + (sin(deg2rad($hue + 140)) + 1) * 50);
    $b = (int) (80 + (sin(deg2rad($hue + 260)) + 1) * 40);
    $tone = imagecolorallocate($im, $r, $g, $b);
    imagefilledrectangle($im, 0, 0, 640, 640, $paper);
    imagefilledrectangle($im, 70, 80, 570, 470, $tone);
    imagefilledrectangle($im, 110, 120, 530, 400, $white);
    imagestring($im, 5, 130, 180, 'SANG NHA', $ink);
    imagestring($im, 5, 130, 210, $caption, $tone);
    imagestring($im, 3, 130, 500, 'Anh minh hoa tu tao', $ink);
    imagejpeg($im, $file, 82);
    imagedestroy($im);
};

$catalogByAlias = [];
foreach ($leaves as $leaf) {
    $catalogByAlias[$leaf['alias']] = $leaf;
}

$products = [];
$n = 0;

$pushProduct = static function (array $item) use (&$products, &$n, $insertRow, $paint, $imageDir, $unitId, $now, $catalogByAlias, $pdo): void {
    $n++;
    $leaf = $catalogByAlias[$item['cat']];
    $fileName = sprintf('p%04d.jpg', $n);
    $paint($imageDir . '/' . $fileName, sprintf('SN-%04d', $n), ($n * 47) % 360);
    $home = 'Dữ liệu mẫu — giá minh hoạ. ' . $item['title'] . ' thuộc ngành ' . $leaf['title'] . '.';
    $body = '<p><strong>Dữ liệu mẫu của cửa hàng Sáng Nhà.</strong> Giá, tồn kho và mô tả là số giả định. Ảnh do công cụ nạp tự vẽ, không lấy từ hãng hay trang bán lẻ khác.</p>'
        . '<p>' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . ' dùng cho nhu cầu tham khảo giao diện. Bảo hành minh hoạ 12 tháng tại cửa hàng.</p>';
    $age = ($n * 86400) % (80 * 86400);
    $insertRow->execute([
        'listcatid' => $leaf['id'],
        'addtime' => $now - $age,
        'publtime' => $now - $age,
        'code' => $item['code'],
        'stock' => $item['stock'],
        'price' => $item['price'],
        'sale' => $item['sale'],
        'unit' => $unitId,
        'weight' => 0.4 + ($n % 20) / 10,
        'image' => 'seed/' . $fileName,
        'title' => $item['title'],
        'alias' => $item['alias'],
        'hometext' => $home,
        'body' => $body,
        'hits' => 20 + ($n * 17) % 4000,
        'sold' => (int) (($n * 3) % 80),
    ]);
    $products[] = [
        'id' => (int) $pdo->lastInsertId(),
        'title' => $item['title'],
        'price' => $item['sale'] > 0 ? $item['sale'] : $item['price'],
        'stock' => $item['stock'],
    ];
};

$pushProduct([
    'cat' => $anchor['cat'],
    'title' => $anchor['title'],
    'alias' => $anchor['alias'],
    'code' => $anchor['code'],
    'price' => $anchor['price'],
    'sale' => $anchor['sale'],
    'stock' => $anchor['stock'],
]);

$usedAlias = [$anchor['alias'] => true];
$leafCount = count($leaves);
while ($n < PRODUCT_COUNT) {
    $leaf = $leaves[$n % $leafCount];
    $family = $lines[$leaf['alias']];
    $fam = $family[$n % count($family)];
    [$brand, $pattern, $models, $variants, $min, $max] = $fam;
    $model = $models[$n % count($models)];
    $variant = $variants[$n % count($variants)];
    $name = trim($brand . ' ' . sprintf($pattern, $model) . ($variant !== '' ? ' ' . $variant : ''));
    $alias = slug($name);
    if (isset($usedAlias[$alias])) {
        $alias .= '-' . $n;
        $name .= ' bản ' . $n;
    }
    $usedAlias[$alias] = true;
    $span = max(1000, $max - $min);
    $price = $min + (($n * 97000) % $span);
    $price = (int) (round($price / 1000) * 1000);
    if ($price < $min) {
        $price = $min;
    }
    $sale = 0;
    if ($n % 5 !== 0 && $price >= 100000) {
        $cut = 10 + ($n % 4) * 5;
        $sale = (int) (round($price * (100 - $cut) / 100 / 1000) * 1000);
        if ($sale >= $price) {
            $sale = $price - 1000;
        }
    }
    $stock = ($n % 17 === 0) ? 0 : (3 + ($n % 40));
    $pushProduct([
        'cat' => $leaf['alias'],
        'title' => $name,
        'alias' => $alias,
        'code' => sprintf('SN%04d', $n + 1),
        'price' => $price,
        'sale' => $sale,
        'stock' => $stock,
    ]);
}

$ho = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý'];
$lot = ['Văn', 'Thị', 'Minh', 'Ngọc', 'Quốc', 'Thanh', 'Hữu', 'Đức', 'Anh', 'Thu', 'Gia', 'Bảo'];
$ten = ['An', 'Bình', 'Chi', 'Dũng', 'Hà', 'Hùng', 'Lan', 'Mai', 'Nam', 'Phong', 'Quân', 'Trang', 'Tuấn', 'Vy', 'Yến', 'Khoa', 'Linh', 'My', 'Sơn', 'Thảo'];
$cities = [
    '12 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
    '48 Hai Bà Trưng, Hoàn Kiếm, Hà Nội',
    '15 Nguyễn Văn Linh, Hải Châu, Đà Nẵng',
    '90 Trần Phú, Ninh Kiều, Cần Thơ',
    '27 Điện Biên Phủ, Ngô Quyền, Hải Phòng',
    '8 Lê Duẩn, Thành phố Huế',
    '120 Cách Mạng Tháng 8, Quận 3, TP. Hồ Chí Minh',
    '33 Nguyễn Trãi, Thanh Xuân, Hà Nội',
];
$hash = '{CRYPT}' . password_hash(SAMPLE_PASSWORD, PASSWORD_BCRYPT);
$insertUser = $pdo->prepare(
    'INSERT INTO ' . $prefix . '_users (
        group_id, username, md5username, password, email, first_name, last_name, gender, photo, birthday, sig, regdate,
        question, answer, passlostkey, view_mail, remember, in_groups, active, checknum, last_login, last_ip, last_agent,
        last_openid, idsite, email_verification_time, active_obj
    ) VALUES (
        4, :username, :md5, :password, :email, :first, :last, :gender, \'\', 0, \'\', :regdate,
        \'q\', \'a\', \'\', 0, 1, \'4\', 1, \'\', :regdate, \'127.0.0.1\', \'seeder\',
        \'\', 0, -1, \'SYSTEM\'
    )'
);
$insertInfo = $pdo->prepare('INSERT INTO ' . $prefix . '_users_info (userid) VALUES (:id)');
$insertMember = $pdo->prepare(
    'INSERT INTO ' . $prefix . '_users_groups_users (group_id, userid, is_leader, approved, data, time_requested, time_approved)
     VALUES (4, :id, 0, 1, \'0\', :t, :t)'
);

$customers = [];
for ($i = 1; $i <= CUSTOMER_COUNT; $i++) {
    $username = sprintf('khach%03d', $i);
    $first = $ho[$i % count($ho)] . ' ' . $lot[$i % count($lot)];
    $last = $ten[$i % count($ten)];
    $insertUser->execute([
        'username' => $username,
        'md5' => md5($username),
        'password' => $hash,
        'email' => $username . '@' . SAMPLE_DOMAIN,
        'first' => $first,
        'last' => $last,
        'gender' => $i % 2 === 0 ? 'F' : 'M',
        'regdate' => $now - ($i * 3600),
    ]);
    $uid = (int) $pdo->lastInsertId();
    $insertInfo->execute(['id' => $uid]);
    $insertMember->execute(['id' => $uid, 't' => $now - ($i * 3600)]);
    $customers[] = [
        'id' => $uid,
        'name' => trim($first . ' ' . $last),
        'email' => $username . '@' . SAMPLE_DOMAIN,
        'phone' => '09' . str_pad((string) (10000000 + $i * 37), 8, '0', STR_PAD_LEFT),
        'address' => $cities[$i % count($cities)],
    ];
}
$pdo->exec(
    'UPDATE ' . $prefix . '_users_groups SET numbers = (SELECT COUNT(*) FROM ' . $prefix . '_users_groups_users WHERE group_id = 4) WHERE group_id = 4'
);

$statuses = array_merge(
    array_fill(0, 40, -1),
    array_fill(0, 50, 0),
    array_fill(0, 30, 1),
    array_fill(0, 50, 2),
    array_fill(0, 40, 3),
    array_fill(0, 50, 4)
);
if (count($statuses) !== ORDER_COUNT) {
    throw new RuntimeException('Số trạng thái đơn không khớp ORDER_COUNT');
}

$insertOrder = $pdo->prepare(
    'INSERT INTO ' . $shops . '_orders (
        order_code, lang, order_name, order_email, order_phone, order_address, order_note,
        user_id, admin_id, shop_id, who_is, unit_total, order_total, order_time, edit_time, postip, order_view,
        transaction_status, transaction_id, transaction_count
    ) VALUES (
        :code, \'vi\', :name, :email, :phone, :address, :note,
        :user_id, 0, 0, 0, \'VND\', :total, :order_time, 0, \'127.0.0.1\', 0,
        :status, 0, 0
    )'
);
$insertLine = $pdo->prepare(
    'INSERT INTO ' . $shops . '_orders_id (order_id, listgroupid, proid, num, price, discount_id)
     VALUES (:order_id, \'\', :proid, :num, :price, 0)'
);
$inStock = array_values(array_filter($products, static fn (array $p): bool => $p['stock'] > 0));
for ($i = 0; $i < ORDER_COUNT; $i++) {
    $guest = $i % 5 === 0;
    $customer = $customers[$i % count($customers)];
    $when = $now - (($i * 17) % (90 * 86400)) - 3600;
    $linesInOrder = 1 + ($i % 3);
    $chosen = [];
    $total = 0;
    for ($k = 0; $k < $linesInOrder; $k++) {
        $product = $inStock[($i * 3 + $k * 11) % count($inStock)];
        $qty = 1 + (($i + $k) % 2);
        $chosen[] = [$product, $qty];
        $total += $product['price'] * $qty;
    }
    $code = sprintf('TMP%06d', $i + 1);
    $insertOrder->execute([
        'code' => $code,
        'name' => $guest ? 'Khách vãng lai ' . ($i + 1) : $customer['name'],
        'email' => $guest ? 'vanglai' . $i . '@' . SAMPLE_DOMAIN : $customer['email'],
        'phone' => $guest ? '098' . str_pad((string) $i, 7, '0', STR_PAD_LEFT) : $customer['phone'],
        'address' => $customer['address'],
        'note' => $i % 4 === 0 ? 'Giao giờ hành chính. Đơn dữ liệu mẫu.' : 'Đơn dữ liệu mẫu.',
        'user_id' => $guest ? 0 : $customer['id'],
        'total' => $total,
        'order_time' => $when,
        'status' => $statuses[$i],
    ]);
    $orderId = (int) $pdo->lastInsertId();
    $finalCode = sprintf('S%06d', $orderId);
    $pdo->prepare('UPDATE ' . $shops . '_orders SET order_code = :code WHERE order_id = :id')->execute([
        'code' => $finalCode,
        'id' => $orderId,
    ]);
    foreach ($chosen as [$product, $qty]) {
        $insertLine->execute([
            'order_id' => $orderId,
            'proid' => $product['id'],
            'num' => $qty,
            'price' => $product['price'],
        ]);
    }
}

$comments = [
    'Máy chạy êm, giao hàng đúng hẹn. Đây là nhận xét mẫu.',
    'Giá minh hoạ hợp lý so với cấu hình. Nhân viên tư vấn rõ.',
    'Đóng gói cẩn thận. Mình sẽ giới thiệu cho người thân.',
    'Lắp đặt nhanh. Sản phẩm đúng mô tả trên website.',
    'Dùng hai tuần thấy ổn định. Đánh giá dữ liệu mẫu.',
    'Hơi nặng hơn mình nghĩ nhưng vận hành tốt.',
    'Màn hình đẹp, loa rõ. Phù hợp gia đình.',
    'Tiết kiệm điện so với máy cũ. Hài lòng.',
];
$insertReview = $pdo->prepare(
    'INSERT INTO ' . $shops . '_review (product_id, userid, sender, content, rating, add_time, edit_time, status)
     VALUES (:product_id, :userid, :sender, :content, :rating, :add_time, 0, 1)'
);
for ($i = 0; $i < REVIEW_COUNT; $i++) {
    $product = $products[$i % count($products)];
    $customer = $customers[$i % count($customers)];
    $guestReview = $i % 4 === 0;
    $insertReview->execute([
        'product_id' => $product['id'],
        'userid' => $guestReview ? 0 : $customer['id'],
        'sender' => $guestReview ? 'Khách ' . $ten[$i % count($ten)] : $customer['name'],
        'content' => $comments[$i % count($comments)] . ' Sản phẩm: ' . $product['title'] . '.',
        'rating' => ($i % 11 === 0) ? 2 : (3 + ($i % 3)),
        'add_time' => $now - (($i * 13) % (60 * 86400)),
    ]);
}

$pdo->commit();

$cache = $root . '/site/data/cache';
$clearCache = static function (string $dir) use (&$clearCache): void {
    if (!is_dir($dir)) {
        return;
    }
    foreach (scandir($dir) ?: [] as $name) {
        if ($name === '.' || $name === '..' || $name === 'index.html' || $name === '.htaccess') {
            continue;
        }
        $path = $dir . '/' . $name;
        if (is_dir($path)) {
            $clearCache($path);
            continue;
        }
        unlink($path);
    }
};
$clearCache($cache);

$catCount = (int) $pdo->query('SELECT COUNT(*) FROM ' . $shops . '_catalogs')->fetchColumn();
$proCount = (int) $pdo->query('SELECT COUNT(*) FROM ' . $shops . '_rows')->fetchColumn();
$userCount = (int) $pdo->query('SELECT COUNT(*) FROM ' . $prefix . '_users WHERE email LIKE ' . $pdo->quote('%@' . SAMPLE_DOMAIN))->fetchColumn();
$orderCount = (int) $pdo->query('SELECT COUNT(*) FROM ' . $shops . '_orders')->fetchColumn();
$reviewCount = (int) $pdo->query('SELECT COUNT(*) FROM ' . $shops . '_review')->fetchColumn();
$saleCount = (int) $pdo->query('SELECT COUNT(*) FROM ' . $shops . '_rows WHERE saleprice > 0')->fetchColumn();

@file_get_contents('http://127.0.0.1:8080/index.php?language=vi&nv=shops');
require __DIR__ . '/seed-features.php';

echo "Đã nạp dữ liệu mẫu Sáng Nhà.\n";
echo "Ngành hàng: {$catCount}\n";
echo "Sản phẩm: {$proCount} (có giảm giá: {$saleCount})\n";
echo "Thành viên mẫu: {$userCount} (tên đăng nhập khach001, mật khẩu " . SAMPLE_PASSWORD . ")\n";
echo "Đơn hàng: {$orderCount}\n";
echo "Đánh giá: {$reviewCount}\n";
echo "Sản phẩm mốc: {$anchor['title']} tại /shops/{$anchor['cat']}/{$anchor['alias']}.html\n";

function slug(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');
    $map = [
        'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
        'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
        'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
        'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
        'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
        'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
        'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o',
        'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
        'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
        'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
        'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
        'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
        'đ' => 'd',
    ];
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-');
}
