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

$page_title = $lang_module['album_detail'];

$error = [];
$post = [];


$post['submit'] = $nv_Request->get_title('submit', 'post', '');
$post['album_id'] = $nv_Request->get_int('album_id', 'post, get', 0);
$post['action'] = $nv_Request->get_title('action', 'get', '');
$post['image_desc'] = htmlspecialchars($nv_Request->get_title('image_desc', 'post', ''));

//------------------
// Kiểm tra file ảnh gửi lên
//------------------
if (!empty($post['submit']) && $post['album_id']>0)
{
    if (empty($post['image_desc']))
    {
        $error[] = 'Bạn chưa nhập mô tả ảnh';
    }
    if ($nv_Request->isset_request('submit', 'post') and isset($_FILES, $_FILES['image'], $_FILES['image']['tmp_name']) and is_uploaded_file($_FILES['image']['tmp_name'])) {

        // Khởi tạo Class upload
        $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        
        // Thiết lập ngôn ngữ, nếu không có dòng này thì ngôn ngữ trả về toàn tiếng Anh
        $upload->setLanguage($lang_global);
        
        // Tải file lên server
        $upload_info = $upload->save_file($_FILES['image'], NV_UPLOADS_REAL_DIR . '/' . $module_name . '/tmp', false, $global_config['nv_auto_resize']);
       
        //Nếu upload thành công -> tiến hành resize ảnh và lưu vào folder tạm (tmp)
        if ($upload_info['error'] == '' && empty($error)) {
            $image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/tmp' . '/' . $upload_info['basename'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
    
            $image->resizeXY(150, 150);
            $newname = $upload_info['basename'];
            $quality = 100;
            $image->save(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $post['album_id'] . '/', $newname, $quality);
            $image->close();
            $info = $image->create_Image_info;
            
        } else {
            $error[] = $upload_info['error'];
        }
    } else {$error[] = 'Bạn chưa tải ảnh lên';}
    //------------------
    // END Kiểm tra file ảnh
    //------------------
    //------------------
    // Thêm vào CSDL
    //------------------
    if (empty($error))
    {
        $sql = "INSERT INTO " . NV_PREFIXLANG . "_album_image (`album_id`, `image`, `image_desc`, `creat_by_userid`, `create_time`) VALUES (:album_id,:image, :image_desc, :creat_by_userid, :create_time)";
                $s = $db->prepare($sql);
                $s->bindParam('album_id', $post['album_id']);
                $s->bindParam('image', $newname);
                $s->bindParam('image_desc', $post['image_desc']);
                $s->bindValue('creat_by_userid', $admin_info['admin_id']);
                $s->bindValue('create_time', NV_CURRENTTIME);
                $s->execute();
                $alert = 'Thêm ảnh thành công';
    }
    //------------------
    // END Thêm vào CSDL
    //------------------
}

/* ACTIVE */
$image_id = $nv_Request->get_int('image_id', 'post', 0);
if ($image_id > 0)
{
    try {
        $sql = "SELECT image_id, active FROM " . NV_PREFIXLANG . "_album_image WHERE image_id = " . $image_id;
        $result = $db->query($sql);
        if ($row = $result->fetch()) {
            $active = $row['active'] == 1 ? 0 : 1; 
            $exe = $db->query("UPDATE " . NV_PREFIXLANG . "_album_image SET active =" . $active . " WHERE image_id=" . $row['image_id'] );
            
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
// đếm dòng dữ liệu trong bảng album_image
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_album_image')
    ->where('album_id=' . $post['album_id']);
$sql = $db->sql();
//đếm số bản ghi
$total = $db->query($sql)->fetchColumn();
/* END PAGINATION  */

/* DELETE album */
$post['action'] = $nv_Request->get_title('action', 'get', '');
$post['image_id'] = $nv_Request->get_title('image_id', 'get', '');
$post['image'] = $nv_Request->get_title('image', 'get', '');

$checksess = $nv_Request->get_title('checksess', 'post, get', '');
$file = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $post['album_id'] . '/' . $post['image'];
if (!empty($post['action']) && $post['action'] == 'delete' && $post['image_id']>0 && $checksess == md5($post['image_id'] . NV_CHECK_SESSION))
{
    //xóa dữ liệu trong sql
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_album_image WHERE image_id = :image_id";
    $s = $db->prepare($sql);
    $s->bindParam('image_id', $post['image_id']);
    if ($s->execute())
        {
            //xóa ảnh trên serve
            nv_deletefile($file, $delsub = false);
        }
}
/* END DELETE */

/* QUERY IN RA BẢN GHI TỪNG TRANG PAGINATION */
if ($post['album_id'] > 0)
{
    $db->select('*')
    ->limit($perpage)
    ->offset(($page - 1) * $perpage)
    ->where('album_id=' . $post['album_id']);
    $sql = $db->sql();
    $result = $db->query($sql);
}

/* END QUERY PAGINATION */

$xtpl = new XTemplate('album_detail.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('POST', $post);

/* Hiển thị list ảnh, phân trang */
foreach ($result as $data)
{

    $data['url_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=album_detail&amp;action=delete&amp;image_id=' . $data['image_id'] . '&album_id=' . $data['album_id'] . '&image=' . $data['image'] . '&checksess=' . md5($data['image_id'] . NV_CHECK_SESSION);
    $data['active'] = $data['active'] == 1 ? 'checked' : '';
    //gán đường dẫn hiển thị ngoài list
    $data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/'. $data['album_id'] . '/' . $data['image'];
    

    $xtpl->assign('DATA', $data);
    $xtpl->parse('main.dataLoop');
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=album_detail' . '&album_id=' . $post['album_id'];
$generate_page = nv_generate_page($base_url, $total, $perpage, $page);
$xtpl->assign('GENERATE_PAGE', $generate_page);

//Nếu số bản ghi > 5 thì hiển thị khối phân trang
if ($total > 5  && $post['album_id'] > 0)
{
    $xtpl->parse('main.page');
}
/* end code phân trang */

/* Hiển thị thông báo */
if (!empty($alert))
{
    $xtpl->assign('ALERT', $alert);
    $xtpl->parse('main.alert');

}
if (!empty($error))
{
    $xtpl->assign('ERROR', implode('<br>',$error));
    $xtpl->parse('main.error');
}
if ($post['album_id'] > 0)
{
    $xtpl->parse('main');

}
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
