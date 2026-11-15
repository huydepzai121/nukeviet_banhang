<?php

/**
 * Lớp nghiệp vụ bổ sung của shops: giá flash sale, thương hiệu, biến thể,
 * kho theo cửa hàng, vận chuyển, cổng thanh toán thử nghiệm, hỏi đáp.
 * Không phụ thuộc bộ dựng template.
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

function nv_shops_fold($text)
{
    $text = mb_strtolower((string) $text, 'UTF-8');
    $map = [
        'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a', 'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
        'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
        'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
        'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
        'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
        'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'đ' => 'd',
    ];
    $text = strtr($text, $map);
    return trim(preg_replace('/\s+/', ' ', $text) ?? $text);
}

function nv_shops_table($suffix)
{
    global $db_config, $module_data;
    $data = !empty($module_data) ? $module_data : 'shops';
    return $db_config['prefix'] . '_' . $data . '_' . $suffix;
}

function nv_shops_has_column($tableSuffix, $column)
{
    global $db;
    static $cache = [];
    $key = $tableSuffix . '.' . $column;
    if (isset($cache[$key])) {
        return $cache[$key];
    }
    $table = nv_shops_table($tableSuffix);
    $column = preg_replace('/[^a-z0-9_]/i', '', $column);
    try {
        $row = $db->query('SHOW COLUMNS FROM ' . $table . ' LIKE ' . $db->quote($column))->fetch();
        $cache[$key] = !empty($row);
    } catch (Exception $e) {
        $cache[$key] = false;
    }
    return $cache[$key];
}

function nv_shops_ensure_schema()
{
    global $db, $db_config, $nv_Cache;
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    nv_shops_release_holds();
    $flag = NV_ROOTDIR . '/data/cache/shops-schema-9.ok';
    if (is_file($flag)) {
        return;
    }
    $p = $db_config['prefix'] . '_shops';
    $sqls = [
        "CREATE TABLE IF NOT EXISTS {$p}_brands (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            weight SMALLINT UNSIGNED NOT NULL DEFAULT 0,
            status TINYINT NOT NULL DEFAULT 1,
            logo VARCHAR(250) NOT NULL DEFAULT '',
            vi_title VARCHAR(250) NOT NULL DEFAULT '',
            vi_alias VARCHAR(250) NOT NULL DEFAULT '',
            vi_description TEXT NOT NULL,
            PRIMARY KEY (id),
            KEY alias (vi_alias)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_stores (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(250) NOT NULL,
            phone VARCHAR(30) NOT NULL DEFAULT '',
            address VARCHAR(250) NOT NULL DEFAULT '',
            province VARCHAR(120) NOT NULL DEFAULT '',
            hours VARCHAR(120) NOT NULL DEFAULT '',
            lat DECIMAL(10,6) NOT NULL DEFAULT 0,
            lng DECIMAL(10,6) NOT NULL DEFAULT 0,
            is_default TINYINT NOT NULL DEFAULT 0,
            status TINYINT NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_stock (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            pro_id INT UNSIGNED NOT NULL,
            listgroup VARCHAR(247) NOT NULL DEFAULT '',
            store_id INT UNSIGNED NOT NULL,
            quantity INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY pair (pro_id, listgroup, store_id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_stock_log (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            pro_id INT UNSIGNED NOT NULL,
            store_id INT UNSIGNED NOT NULL,
            quantity INT NOT NULL,
            reason VARCHAR(250) NOT NULL,
            userid INT UNSIGNED NOT NULL DEFAULT 0,
            add_time INT UNSIGNED NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_flash (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(250) NOT NULL,
            begin_time INT UNSIGNED NOT NULL,
            end_time INT UNSIGNED NOT NULL,
            status TINYINT NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_flash_item (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            flash_id INT UNSIGNED NOT NULL,
            pro_id INT UNSIGNED NOT NULL,
            price DOUBLE NOT NULL DEFAULT 0,
            slots INT NOT NULL DEFAULT 0,
            sold INT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY flash_pro (flash_id, pro_id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_spec (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            pro_id INT UNSIGNED NOT NULL,
            name VARCHAR(120) NOT NULL,
            value VARCHAR(250) NOT NULL,
            filterable TINYINT NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY pro (pro_id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_qa (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            pro_id INT UNSIGNED NOT NULL,
            sender VARCHAR(120) NOT NULL,
            question TEXT NOT NULL,
            answer TEXT NOT NULL,
            status TINYINT NOT NULL DEFAULT 0,
            add_time INT UNSIGNED NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_together (
            pro_id INT UNSIGNED NOT NULL,
            with_id INT UNSIGNED NOT NULL,
            PRIMARY KEY (pro_id, with_id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_province (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(120) NOT NULL,
            fee INT UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_ward (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            province_id INT UNSIGNED NOT NULL,
            title VARCHAR(120) NOT NULL,
            PRIMARY KEY (id),
            KEY province_id (province_id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_gateway (
            code VARCHAR(20) NOT NULL,
            title VARCHAR(80) NOT NULL,
            active TINYINT NOT NULL DEFAULT 0,
            sandbox TINYINT NOT NULL DEFAULT 1,
            secret VARCHAR(120) NOT NULL DEFAULT '',
            PRIMARY KEY (code)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_paylog (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            order_id INT UNSIGNED NOT NULL,
            gateway VARCHAR(20) NOT NULL,
            amount DOUBLE NOT NULL DEFAULT 0,
            txn VARCHAR(80) NOT NULL DEFAULT '',
            status VARCHAR(20) NOT NULL,
            payload TEXT NOT NULL,
            add_time INT UNSIGNED NOT NULL,
            PRIMARY KEY (id),
            KEY txn (txn)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_service (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            kind VARCHAR(20) NOT NULL,
            pro_id INT UNSIGNED NOT NULL DEFAULT 0,
            name VARCHAR(120) NOT NULL,
            phone VARCHAR(20) NOT NULL,
            note TEXT NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'new',
            add_time INT UNSIGNED NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
        "CREATE TABLE IF NOT EXISTS {$p}_banner (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(180) NOT NULL,
            subtitle VARCHAR(250) NOT NULL DEFAULT '',
            link VARCHAR(250) NOT NULL DEFAULT '',
            tone VARCHAR(20) NOT NULL DEFAULT 'teal',
            weight SMALLINT NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM",
    ];
    foreach ($sqls as $sql) {
        try {
            $db->query($sql);
        } catch (Exception $e) {
        }
    }
    $alters = [
        "ALTER TABLE {$p}_rows ADD brand_id INT UNSIGNED NOT NULL DEFAULT 0",
        "ALTER TABLE {$p}_rows ADD warranty_months SMALLINT UNSIGNED NOT NULL DEFAULT 12",
        "ALTER TABLE {$p}_rows ADD vi_search VARCHAR(250) NOT NULL DEFAULT ''",
        "ALTER TABLE {$p}_group_quantity ADD product_code VARCHAR(64) NOT NULL DEFAULT ''",
        "ALTER TABLE {$p}_group_quantity ADD price DOUBLE NOT NULL DEFAULT 0",
        "ALTER TABLE {$p}_group_quantity ADD image VARCHAR(250) NOT NULL DEFAULT ''",
        "ALTER TABLE {$p}_orders ADD hold_until INT UNSIGNED NOT NULL DEFAULT 0",
        "ALTER TABLE {$p}_orders ADD ship_fee DOUBLE NOT NULL DEFAULT 0",
        "ALTER TABLE {$p}_orders ADD pay_gateway VARCHAR(20) NOT NULL DEFAULT ''",
        "ALTER TABLE {$p}_orders_id ADD variant_name VARCHAR(250) NOT NULL DEFAULT ''",
    ];
    foreach ($alters as $sql) {
        try {
            $db->query($sql);
        } catch (Exception $e) {
        }
    }
    nv_shops_register_pages();
    if (is_dir(NV_ROOTDIR . '/data/cache')) {
        @file_put_contents(NV_ROOTDIR . '/data/cache/shops-schema-9.ok', '1');
    }
}

function nv_shops_register_pages()
{
    global $db, $db_config;
    $funcs = [
        'brand' => 'Thương hiệu',
        'flashsale' => 'Flash sale',
        'stores' => 'Hệ thống cửa hàng',
        'warranty' => 'Tra cứu bảo hành',
        'installment' => 'Trả góp',
        'tradein' => 'Thu cũ đổi mới',
        'qa' => 'Hỏi đáp',
        'paynotify' => 'Thông báo thanh toán',
    ];
    $lang = $db_config['prefix'] . '_vi';
    foreach ($funcs as $name => $title) {
        $id = $db->query('SELECT func_id FROM ' . $lang . '_modfuncs WHERE in_module=\'shops\' AND func_name=' . $db->quote($name))->fetchColumn();
        if (!$id) {
            $db->query('INSERT INTO ' . $lang . '_modfuncs (func_name, alias, func_custom_name, func_site_title, in_module, show_func, in_submenu, subweight, setting) VALUES ('
                . $db->quote($name) . ',' . $db->quote($name) . ',' . $db->quote($title) . ',' . $db->quote($title) . ",'shops',1,0,20,'')");
            $id = (int) $db->lastInsertId();
        }
        foreach (['default', 'sangnha'] as $theme) {
            $has = $db->query('SELECT func_id FROM ' . $lang . "_modthemes WHERE func_id=" . (int) $id . ' AND theme=' . $db->quote($theme))->fetchColumn();
            if (!$has) {
                $db->query('INSERT INTO ' . $lang . '_modthemes (func_id, layout, theme) VALUES (' . (int) $id . ", 'left-main-right', " . $db->quote($theme) . ')');
            }
        }
    }
}

function nv_shops_release_holds()
{
    global $db;
    $table = nv_shops_table('orders');
    if (!nv_shops_has_column('orders', 'hold_until')) {
        return;
    }
    try {
        $db->query('UPDATE ' . $table . ' SET transaction_status=3, hold_until=0 WHERE hold_until>0 AND hold_until<' . NV_CURRENTTIME . ' AND transaction_status IN (0,1)');
    } catch (Exception $e) {
    }
}

function nv_shops_apply_flash($proId, array $quote, $number, $perPro, $currency)
{
    global $db;
    $now = NV_CURRENTTIME;
    try {
        $row = $db->query('SELECT i.price, i.slots, i.sold, f.end_time FROM ' . nv_shops_table('flash_item') . ' i INNER JOIN ' . nv_shops_table('flash') . ' f ON f.id=i.flash_id WHERE i.pro_id=' . (int) $proId . ' AND f.status=1 AND f.begin_time<=' . $now . ' AND f.end_time>' . $now . ' AND i.sold<i.slots ORDER BY i.price ASC LIMIT 1')->fetch();
    } catch (Exception $e) {
        return $quote;
    }
    if (empty($row)) {
        return $quote;
    }
    $unit = (float) $row['price'];
    $sale = $perPro ? $unit : $unit * max(1, (int) $number);
    $quote['price'] = max($quote['price'], $sale);
    $quote['sale'] = $sale;
    $quote['discount'] = max(0, $quote['price'] - $sale);
    $quote['discount_percent'] = $quote['price'] > 0 ? round($quote['discount'] * 100 / $quote['price']) : 0;
    $quote['discount_unit'] = '%';
    $quote['sale_format'] = nv_number_format($sale, nv_get_decimals($currency));
    $quote['price_format'] = nv_number_format($quote['price'], nv_get_decimals($currency));
    $quote['flash'] = 1;
    $quote['flash_end'] = (int) $row['end_time'];
    $quote['flash_left'] = max(0, (int) $row['slots'] - (int) $row['sold']);
    return $quote;
}

function nv_shops_money($amount)
{
    return number_format((float) $amount, 0, ',', '.') . ' ₫';
}

function nv_shops_category_menu()
{
    global $db, $db_config;
    $html = '';
    try {
        $rows = $db->query('SELECT catid, parentid, vi_title, vi_alias FROM ' . $db_config['prefix'] . '_shops_catalogs ORDER BY sort ASC')->fetchAll();
    } catch (Exception $e) {
        return '';
    }
    $children = [];
    foreach ($rows as $row) {
        $children[(int) $row['parentid']][] = $row;
    }
    foreach ($children[0] ?? [] as $parent) {
        $html .= '<div class="sn-mega-col"><a class="sn-mega-parent" href="' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=shops&amp;' . NV_OP_VARIABLE . '=' . $parent['vi_alias'], true) . '">' . htmlspecialchars($parent['vi_title'], ENT_QUOTES, 'UTF-8') . '</a>';
        foreach ($children[(int) $parent['catid']] ?? [] as $child) {
            $html .= '<a href="' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=shops&amp;' . NV_OP_VARIABLE . '=' . $child['vi_alias'], true) . '">' . htmlspecialchars($child['vi_title'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        $html .= '</div>';
    }
    return $html;
}

function nv_shops_cart_count()
{
    $cart = $_SESSION['shops_cart'] ?? [];
    $n = 0;
    if (is_array($cart)) {
        foreach ($cart as $line) {
            $n += (int) ($line['num'] ?? 0);
        }
    }
    return (string) $n;
}

function nv_shops_storefront_html()
{
    global $db, $db_config, $module_name;
    if ($module_name !== 'shops') {
        return '';
    }
    nv_shops_ensure_schema();
    $p = $db_config['prefix'] . '_shops';
    $banners = [];
    try {
        $banners = $db->query('SELECT * FROM ' . $p . '_banner ORDER BY weight ASC LIMIT 3')->fetchAll();
    } catch (Exception $e) {
    }
    if (!$banners) {
        $banners = [
            ['title' => 'Điện máy cho ngôi nhà sáng', 'subtitle' => 'Giá minh hoạ, hàng mẫu để xem giao diện.', 'tone' => 'teal'],
            ['title' => 'Flash sale cuối tuần', 'subtitle' => 'Suất có hạn, giá về thường khi hết giờ.', 'tone' => 'rose'],
            ['title' => 'Giao tận nơi hoặc nhận tại cửa hàng', 'subtitle' => 'Phí theo tỉnh thành, miễn phí khi đủ ngưỡng.', 'tone' => 'ink'],
        ];
    }
    $heroShots = [
        NV_BASE_SITEURL . 'themes/sangnha/images/catalog/hero.jpg',
        NV_BASE_SITEURL . 'themes/sangnha/images/catalog/hero-sale.jpg',
        NV_BASE_SITEURL . 'themes/sangnha/images/catalog/tv.jpg',
    ];
    $hero = '';
    foreach ($banners as $i => $banner) {
        $shot = $heroShots[$i % count($heroShots)];
        $hero .= '<a class="sn-hero' . ($i ? '' : ' is-main') . '" href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=shops&' . NV_OP_VARIABLE . '=flashsale"><img src="' . $shot . '" alt=""><strong>' . htmlspecialchars($banner['title'], ENT_QUOTES, 'UTF-8') . '</strong></a>';
    }
    $flash = '';
    $now = NV_CURRENTTIME;
    try {
        $deal = $db->query('SELECT f.title, f.end_time, i.price, i.slots, i.sold, r.vi_title, r.vi_alias, c.vi_alias AS cat FROM ' . $p . '_flash f INNER JOIN ' . $p . '_flash_item i ON i.flash_id=f.id INNER JOIN ' . $p . '_rows r ON r.id=i.pro_id LEFT JOIN ' . $p . '_catalogs c ON c.catid=r.listcatid WHERE f.status=1 AND f.begin_time<=' . $now . ' AND f.end_time>' . $now . ' ORDER BY i.price ASC LIMIT 4')->fetchAll();
    } catch (Exception $e) {
        $deal = [];
    }
    if ($deal) {
        $end = (int) $deal[0]['end_time'];
        $flash .= '<section class="sn-flash"><header><div><p>Đợt flash sale</p><h2>' . htmlspecialchars($deal[0]['title'], ENT_QUOTES, 'UTF-8') . '</h2></div><div class="sn-count" data-end="' . $end . '">Còn <b class="sn-count-text">--:--:--</b></div></header><div class="sn-flash-row">';
        foreach ($deal as $item) {
            $left = max(0, (int) $item['slots'] - (int) $item['sold']);
            $href = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=shops&amp;' . NV_OP_VARIABLE . '=' . $item['cat'] . '/' . $item['vi_alias'] . $GLOBALS['global_config']['rewrite_exturl'], true);
            $flash .= '<a class="sn-flash-card" href="' . $href . '"><em>Còn ' . $left . ' suất</em><strong>' . htmlspecialchars($item['vi_title'], ENT_QUOTES, 'UTF-8') . '</strong><span>' . nv_shops_money($item['price']) . '</span></a>';
        }
        $flash .= '</div></section>';
    }
    $brands = '';
    try {
        $rows = $db->query('SELECT vi_title, vi_alias FROM ' . $p . '_brands WHERE status=1 ORDER BY weight ASC LIMIT 12')->fetchAll();
    } catch (Exception $e) {
        $rows = [];
    }
    if ($rows) {
        $brands .= '<section class="sn-brands"><h2>Thương hiệu nổi bật</h2><div>';
        foreach ($rows as $row) {
            $brands .= '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=shops&' . NV_OP_VARIABLE . '=brand/' . htmlspecialchars($row['vi_alias'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['vi_title'], ENT_QUOTES, 'UTF-8') . '</a>';
        }
        $brands .= '</div></section>';
    }
    return '<div class="sn-home"><p class="sn-sample">Dữ liệu mẫu — giá minh hoạ.</p><div class="sn-hero-grid">' . $hero . '</div><ul class="sn-promises"><li>Giao nhanh nội thành</li><li>Đổi trả 7 ngày</li><li>Bảo hành chính hãng minh hoạ</li><li>Trả góp ước tính</li></ul>' . $flash . $brands . '</div>';
}

function nv_shops_page($title, $html)
{
    global $page_title;
    $page_title = $title;
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme('<div class="sn-page"><h1>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>' . $html . '</div>');
    include NV_ROOTDIR . '/includes/footer.php';
}

nv_shops_ensure_schema();
