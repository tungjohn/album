<!-- BEGIN: main -->
<div class="well">
    <div class="row">
    <!-- BEGIN: dataLoop -->

        <div class="col-sm-8 col-md-6">
            <div class="thumbnail">
                <a href="{DATA.url_detail}">
                    <img src="{DATA.album_thumbnail}" alt="">
                </a>
            <div class="caption">
                <h3><b>Tên album:</b> {DATA.album_name}</h3>
                <p><b>Mô tả:</b>  {DATA.album_desc}</p>
            </div>
            </div>
        </div>

  <!-- END: dataLoop -->
  
    </div>
</div>
    <!-- BEGIN: page -->
        {GENERATE_PAGE}
    <!-- END: page -->
<!-- END: main -->