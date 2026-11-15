<?php

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('ask', 'post')) {
    $pro = $nv_Request->get_int('pro', 'post', 0);
    $name = $nv_Request->get_title('name', 'post', '');
    $question = $nv_Request->get_textarea('question', '', 'br');
    if ($pro > 0 && $name !== '' && $question !== '') {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_shops_qa (pro_id, sender, question, answer, status, add_time) VALUES (' . $pro . ',' . $db->quote($name) . ',' . $db->quote($question) . ",'',0," . NV_CURRENTTIME . ')');
    }
}
$rows = $db->query('SELECT q.question, q.answer, q.sender, r.vi_title FROM ' . $db_config['prefix'] . '_shops_qa q INNER JOIN ' . $db_config['prefix'] . '_shops_rows r ON r.id=q.pro_id WHERE q.status=1 ORDER BY q.id DESC LIMIT 30')->fetchAll();
$html = '';
foreach ($rows as $row) {
    $html .= '<article class="sn-note"><strong>' . htmlspecialchars($row['vi_title'], ENT_QUOTES, 'UTF-8') . '</strong><p>' . htmlspecialchars($row['sender'], ENT_QUOTES, 'UTF-8') . ': ' . $row['question'] . '</p><p>Cửa hàng: ' . ($row['answer'] !== '' ? $row['answer'] : 'Đang trả lời') . '</p></article>';
}
nv_shops_page('Hỏi đáp sản phẩm', $html);
