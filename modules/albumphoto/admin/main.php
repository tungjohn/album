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

$page_title = $lang_module['main'];

$error = [];
$post = [];


$post['submit'] = $nv_Request->get_title('submit', 'post', '');
$post['album_name'] = trim(nv_htmlspecialchars($nv_Request->get_title('album_name', 'post', '')));
$post['album_desc'] = nv_htmlspecialchars($nv_Request->get_title('album_desc', 'post', ''));
$post['id'] = $nv_Request->get_int('id', 'post, get', 0);
$post['action'] = $nv_Request->get_title('action', 'get', '');
$post['old_thumbnail'] = $nv_Request->get_title('old_thumbnail', 'post', '');

/* EDIT PRODUCT */
        //lấy dữ liệu trong database in ra form sửa
        try {
            if (!empty($post['action']) && $post['action'] == 'edit' && $post['id']>0)
            {
                $sql = "SELECT * FROM " . NV_PREFIXLANG . "_album_info WHERE id = " . $post['id'];
                $post = $db->query($sql)->fetch();
                if (!empty($post['album_thumbnail'])) {
                    $post['img'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/'. $module_name . '/' . $post['id'] . '/' . $post['album_thumbnail'];
                }
                
            }
        } catch (PDOException $e) {
            echo "<pre>";
            print_r($e);
            echo "</pre>";
        }
        
/* END EDIT PRODUCT */

if (!empty($post['submit']))
{

/* Kiểm tra album_name */
//bắt biến album_name


if (empty($post['album_desc']))
{
    $error[] = 'Bạn chưa nhập mô tả cho album';
}
if (empty($post['album_name']))
{
    $error[] = 'Bạn chưa nhập tên album';
}

//------------------
// Kiểm tra file ảnh gửi lên
//------------------
if ($nv_Request->isset_request('submit', 'post') and isset($_FILES, $_FILES['album_thumbnail'], $_FILES['album_thumbnail']['tmp_name']) and is_uploaded_file($_FILES['album_thumbnail']['tmp_name'])) {

    // Khởi tạo Class upload
    $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
    
    // Thiết lập ngôn ngữ, nếu không có dòng này thì ngôn ngữ trả về toàn tiếng Anh
    $upload->setLanguage($lang_global);
    
    // Tải file lên server
    $upload_info = $upload->save_file($_FILES['album_thumbnail'], NV_UPLOADS_REAL_DIR . '/' . $module_name . '/tmp', false, $global_config['nv_auto_resize']);
   
    //Nếu upload thành công -> tiến hành resize ảnh và lưu vào folder tạm (tmp)
    if ($upload_info['error'] == '' && empty($error)) {
        $image = new NukeViet\Files\Image(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/tmp' . '/' . $upload_info['basename'], NV_MAX_WIDTH, NV_MAX_HEIGHT);

        $image->resizeXY(150, 150);
        $newname = $upload_info['basename'];
        $quality = 100;
        
        
    } else {
        $error[] = $upload_info['error'];
    }
} 
    //Kiểm tra nếu không có file tải lên hoặc không có ảnh cũ thì hiển thị lỗi
    if (empty($newname) && empty($nv_Request->get_title('old_thumbnail', 'post', '')))
    {
        $error[] = 'Bạn chưa chọn hình ảnh sản phẩm';
    }
//------------------
// END Kiểm tra file ảnh
//------------------

//------------------
// Lưu thông tin album vào Database
//------------------
if (empty($error))
{
    
    if ($post['id'] > 0)
    {
        if (!empty($newname))
        {

            try {
                $sql = "UPDATE " . NV_PREFIXLANG . "_album_info SET `album_name`=:album_name,`album_thumbnail`=:album_thumbnail,`album_desc`=:album_desc,`creat_by_user_id`=:creat_by_user_id,`create_time`=:create_time  WHERE `id`=" . $post['id'];
                $s = $db->prepare($sql);
                $s->bindParam('album_name', $post['album_name']);
                $s->bindParam('album_thumbnail', $newname);
                $s->bindParam('album_desc', $post['album_desc']);
                $s->bindValue('creat_by_user_id', $admin_info['admin_id']);
                $s->bindValue('create_time', NV_CURRENTTIME);

                if ($s->execute())
                {
                    //xóa ảnh album thumbnail cũ
                    $file = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $post['id'] . '/' . $post['old_thumbnail'];
                    nv_deletefile($file, $delsub = false);

                    //lưu ảnh album thumbnail mới
                    $image->save(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $post['id'] . '/', $newname, $quality);
                    $image->close();
                    $info = $image->create_Image_info;
                    //thông báo
                    $alert = 'Sửa Thành Công';

                }
            } catch (PDOException $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die();
            }
           

                    
        } else if (empty($newname))
        {
            try {
                $sql = "UPDATE " . NV_PREFIXLANG . "_album_info SET `album_name`=:album_name,`album_desc`=:album_desc,`creat_by_user_id`=:creat_by_user_id,`create_time`=:create_time  WHERE `id`=" . $post['id'];
                    $s = $db->prepare($sql);
                    $s->bindParam('album_name', $post['album_name']);
                    $s->bindParam('album_desc', $post['album_desc']);
                    $s->bindValue('creat_by_user_id', $admin_info['admin_id']);
                    $s->bindValue('create_time', NV_CURRENTTIME);
                    $s->execute();
                    $alert = 'Sửa Thành Công';
            } catch (PDOException $e) {
                echo "<pre>";
                print_r($e);
                echo "</pre>";
                die();
            }
            
        }
    } else {
        try {
            $sql = "INSERT INTO " . NV_PREFIXLANG ."_album_info (`album_name`, `album_thumbnail`, `album_desc`, `creat_by_user_id`, `create_time`) VALUES (:album_name,:album_thumbnail, :album_desc, :creat_by_user_id, :create_time)";
            $s = $db->prepare($sql);
            $s->bindParam('album_name', $post['album_name']);
            $s->bindParam('album_thumbnail', $newname);
            $s->bindParam('album_desc', $post['album_desc']);
            $s->bindValue('creat_by_user_id', $admin_info['admin_id']);
            $s->bindValue('create_time', NV_CURRENTTIME);
            //nếu lưu vào csdl thành công
            if ($s->execute())
            {
                //lấy ra id vừa insert
                $id = $db->lastInsertId();
                //tạo folder tương ứng với album id để lưu ảnh
                nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_name, $id);
                //lưu ảnh album thumbnail
                $image->save(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $id . '/', $newname, $quality);
                $image->close();
                $info = $image->create_Image_info;
                //thông báo
                $alert = 'Tạo album mới thành công';
    
            }
            
    
        } catch (PDOException $e) {
            echo "<pre>";
            print_r($e);
            echo "</pre>";
            die();
        }
    }

    

    //tạo thư mục album để lưu ảnh vào trong
    
}
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('POST', $post);

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

//-------------------------------
// Viết code xuất ra site vào đây
//-------------------------------

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
