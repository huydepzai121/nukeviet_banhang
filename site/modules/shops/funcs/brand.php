<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$alias = isset($array_op[1]) ? $array_op[1] : '';
$where = $alias !== '' ? ' AND b.vi_alias=' . $db->quote($alias) : '';
$rows = $db->query('SELECT r.id, r.vi_title, r.vi_alias, r.product_price, r.saleprice, r.homeimgfile, r.homeimgthumb, c.vi_alias AS cat, b.vi_title AS brand FROM ' . $db_config['prefix'] . '_shops_rows r LEFT JOIN ' . $db_config['prefix'] . '_shops_brands b ON b.id=r.brand_id LEFT JOIN ' . $db_config['prefix'] . '_shops_catalogs c ON c.catid=r.listcatid WHERE r.status=1' . ($alias !== '' ? ' AND b.vi_alias=' . $db->quote($alias) : ' AND r.brand_id>0') . ' ORDER BY r.id DESC LIMIT 48')->fetchAll();
$html = '<div class="sn-grid">';
foreach ($rows as $row) {
    $img = $row['homeimgthumb'] == 2 ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/shops/' . $row['homeimgfile'] : NV_BASE_SITEURL . 'themes/sangnha/images/logo-mark.svg';
    $href = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=shops&amp;' . NV_OP_VARIABLE . '=' . $row['cat'] . '/' . $row['vi_alias'] . $global_config['rewrite_exturl'], true);
    $price = $row['saleprice'] > 0 ? $row['saleprice'] : $row['product_price'];
    $html .= '<a class="sn-mini" href="' . $href . '"><img src="' . $img . '" alt=""><strong>' . htmlspecialchars($row['vi_title'], ENT_QUOTES, 'UTF-8') . '</strong><em>' . htmlspecialchars($row['brand'] ?: '', ENT_QUOTES, 'UTF-8') . '</em><span>' . nv_shops_money($price) . '</span></a>';
}
$html .= '</div>';
if ($alias === '') {
    $html = '<div class="sn-brands"><div>' . nv_shops_category_menu() . '</div></div>' . $html;
}
nv_shops_page($alias !== '' ? 'Thương hiệu' : 'Thương hiệu nổi bật', $html);
