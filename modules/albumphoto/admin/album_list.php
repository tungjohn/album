<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2020 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 31 Oct 2020 02:20:33 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['album_list'];

/* ACTIVE */
$id = $nv_Request->get_int('id', 'post', 0);
if ($id > 0)
{
    try {
        $sql = "SELECT id, active FROM " . NV_LANG_VARIABLE . "_album_info WHERE id = " . $id;
        $result = $db->query($sql);
        if ($row = $result->fetch()) {
            $active = $row['active'] == 1 ? 0 : 1; 
            $exe = $db->query("UPDATE " . NV_PREFIXLANG . "_album_info SET active =" . $active . " WHERE id=" . $row['id'] );
            
    }
    } catch (PDOException $e) {
        echo "<pre>";
        print_r($e);
        echo "</pre>";
    }
}

/* END ACTIVE */

/* CODE PHÂN TRANG PAGINATION*/
//gán số lượng hiển thị mỗi trang
$perpage = 5;
//nhận biến page từ url
$page = $nv_Request->get_int('page', 'get', 1);
// đếm dòng dữ liệu trong bảng album_info
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_album_info');
$sql = $db->sql();
//đếm số bản ghi
$total = $db->query($sql)->fetchColumn();
/* END PAGINATION  */

/* DELETE album */
$post['action'] = $nv_Request->get_title('action', 'get', '');
$post['id'] = $nv_Request->get_title('id', 'get', '');
$checksess = $nv_Request->get_title('checksess', 'post, get', '');
$file = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $post['id'];
if (!empty($post['action']) && $post['action'] == 'delete' && $post['id']>0 && $checksess == md5($post['id'] . NV_CHECK_SESSION))
{
    //xóa dữ liệu trong sql
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_album_info WHERE id = :id";
    $s = $db->prepare($sql);
    $s->bindParam('id', $post['id']);
    if ($s->execute())
        {
            //xóa tất cả ảnh trong folder
            nv_deletefile($file, $delsub = true);
        }
}
/* END DELETE */

/* QUERY IN RA BẢN GHI TỪNG TRANG PAGINATION */
$db->select('*')
    ->limit($perpage)
    ->offset(($page - 1) * $perpage);

    $sql = $db->sql();
    $result = $db->query($sql);
/* END QUERY PAGINATION */

$xtpl = new XTemplate('album_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

foreach ($result as $data)
{
    $data['url_detail'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=album_detail&amp;album_id=' . $data['id'];
    $data['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=main&amp;action=edit&amp;id=' . $data['id'];
    $data['url_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=album_list&amp;action=delete&amp;id=' . $data['id'] . '&checksess=' . md5($data['id'] . NV_CHECK_SESSION);
    $data['active'] = $data['active'] == 1 ? 'checked' : '';
    //gán đường dẫn hiển thị ngoài list
    $data['album_thumbnail'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'. $data['id'] . '/' . $data['album_thumbnail'];
    

    $xtpl->assign('DATA', $data);
    $xtpl->parse('main.dataLoop');
}



$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=album_list';
$generate_page = nv_generate_page($base_url, $total, $perpage, $page);
$xtpl->assign('GENERATE_PAGE', $generate_page);

//Nếu số bản ghi > 5 thì hiển thị khối phân trang
if ($total > 5 )
{
    $xtpl->parse('main.page');
}
/* end code xuất ra site */

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
