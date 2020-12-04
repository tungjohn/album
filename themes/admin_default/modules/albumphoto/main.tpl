<!-- BEGIN: main -->
<!-- BEGIN: error -->
    <div class='alert alert-warning' role="alert">{ERROR}</div>
    
<!-- END: error -->
<!-- BEGIN: alert -->
    <div class='alert alert-info' role="alert">{ALERT}</div>
<!-- END: alert -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
        <input type="hidden" class="form-control" name="id" value="{POST.id}">
        <input type="hidden" class="form-control" name="old_thumbnail" value="{POST.album_thumbnail}">

        <div class="form-group">
            <label for="">Tên album: </label>
            <input type="text" class="form-control" name="album_name" value="{POST.album_name}">
        </div>
        <div class="form-group">
            <label for="">Ảnh đại diện: </label>
            <input type="file" class="form-control" name="album_thumbnail">
            <img src="{POST.img}">
        </div>
        <div class="form-group">
            <label for="">Mô tả album: </label>
            <textarea name="album_desc" class="form-control">{POST.album_desc}</textarea>
        </div>
        <div class="text-center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
<!-- END: main -->