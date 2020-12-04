<!-- BEGIN: main -->
<!-- BEGIN: error -->
    <div class='alert alert-warning' role="alert">{ERROR}</div>
    
<!-- END: error -->
<!-- BEGIN: alert -->
    <div class='alert alert-info' role="alert">{ALERT}</div>
<!-- END: alert -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
    <input type="hidden" class="form-control" name="album_id" value="{POST.album_id}">
        <div class="form-group">
            <label for="">Thêm ảnh vào album: </label>
            <input type="file" class="form-control" name="image">
        </div>
        <div class="form-group">
            <label for="">Mô tả ảnh: </label>
            <textarea name="image_desc" class="form-control"></textarea>
        </div>
    <div class="text-center"><input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" /></div>
</form>
<h2>List ảnh trong album</h2>
<div class="table-responsive">          
  <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th class="text-center">Tên album</th>
        <th class="text-center">Ảnh</th>
        <th class="text-center">Mô tả</th>
        <th class="text-center">Trạng Thái</th>
        <th class="text-center">Hành Động</th>
        
      </tr>
    </thead>
    <tbody>
    <!-- BEGIN: dataLoop -->
      <tr>
        <td class="text-center">{DATA.image_id}</td>
        <td class="text-center">{DATA.album_id}</td>
        <td class="text-center"><img src="{DATA.image}"></td>
        <td class="text-center">{DATA.image_desc}</td>
        <td class="text-center">
            <input type="checkbox" name="active" {DATA.active} onchange="nv_change_active({DATA.image_id})">
        </td>
        <td class="text-center">
          <a href="{DATA.url_delete}" class="delete btn btn-danger btn-xs">
          <em class="fa fa-trash-o margin-right"></em>
            Xóa
          </a>
        </td>
      </tr>
    <!-- END: dataLoop -->
    </tbody>
  </table>
</div>
  <!-- BEGIN: page -->
    {GENERATE_PAGE}
  <!-- END: page -->
<script type='text/javascript'>

function nv_change_active(image_id) {
  $.ajax({
          url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=album_detail',
          method: 'POST',
          dataType:"text",
          data: {image_id: image_id},
          success: function(data) {
              alert('thay đổi trạng thái hiển thị thành công');
          }
        });
}
      $(document).ready(function() {
          $('.delete').click(function() {
              
              var xn = confirm('Bạn có chắc chắn muốn xóa?');
              if (xn == true) {
                return true;
                
              } else { 
                return false; 
              } 
          });
      }); 
</script>
<!-- END: main -->