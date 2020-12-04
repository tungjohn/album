<!-- BEGIN: main -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
  <div>
        <div class="owl-carousel owl-theme">
    <!-- BEGIN: dataLoop -->
    
            <img class="owl-lazy" data-src="{DATA.image}" alt="">
            
              
    <!-- END: dataLoop -->
        </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                items:1,
                lazyLoad:true,
                loop:true,
                margin:5
            });
        });
    </script>
<!-- END: main -->