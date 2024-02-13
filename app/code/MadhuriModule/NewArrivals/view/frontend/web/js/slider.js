require(['jquery', 'slick'], function($){
    $(document).ready(function(){
        $('.slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000, // Adjust speed as needed
            arrows: true,
            dots: true
        });
    });
});
