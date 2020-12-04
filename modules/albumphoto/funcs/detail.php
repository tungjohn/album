<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2020 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 31 Oct 2020 02:20:33 GMT
 */

if (!defined('NV_IS_MOD_SAMPLES')) {
    die('Stop!!!');
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$array_data = [];
$post['album_id'] = $nv_Request->get_int('album_id', 'post, get', 0);

if (($post['album_id']) > 0)
{
    $db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_album_image')
    ->where('album_id=' . $post['album_id'] . ' AND active=1');
    $sql = $db->sql();
    $result = $db->query($sql);

    //lấy dữ liệu tương ứng với id từ bảng 
    $sql = "SELECT * FROM `nv4_vi_album_info` WHERE id=" . $post['album_id'];
    $_result = $db->query($sql);
    //nếu không có dữ liệu
    if ($row = $_result->fetch())
    {
       //điều chỉnh title;
        $page_title = $row['album_name'];
        /* TẠO LINK TỪNG PHẦN */
        $array_mod_title[] = array(
            'title' => $row['album_name'],
            'link' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;album_id=' . $row['id'], true)
        );  
    /* END TẠO LINK TỪNG PHẦN */
    }
    
}


$contents = nv_theme_samples_detail($array_data, $result, $row);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
