<!-- BEGIN: main -->
<div class="table-responsive">          
  <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th class="text-center">Tên album</th>
        <th class="text-center">Ảnh album</th>
        <th class="text-center">Mô tả album</th>
        <th class="text-center">Trạng Thái</th>
        <th class="text-center">Hành Động</th>
        
      </tr>
    </thead>
    <tbody>
    <!-- BEGIN: dataLoop -->
      <tr>
        <td class="text-center">{DATA.id}</td>
        <td class="text-center">{DATA.album_name}</td>
        <td class="text-center"><img src="{DATA.album_thumbnail}"</td>
        <td class="text-center">{DATA.album_desc}</td>
        <td class="text-center">
            <input type="checkbox" name="active" {DATA.active} onchange="nv_change_active({DATA.id})">
        </td>
        <td class="text-center">
          <a href="{DATA.url_detail}" class="detail btn btn-info btn-xs">
            Detail
          </a>
          <a href="{DATA.url_edit}" class="edit btn btn-warning btn-xs btn_edit">
          <em class="fa fa-edit margin-right"></em>
            Sửa
          </a>
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

function nv_change_active(id) {
  $.ajax({
          url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=album_list',
          method: 'POST',
          dataType:"text",
          data: {id: id},
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