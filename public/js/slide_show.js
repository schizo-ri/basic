$(function(){
    var slideIndex = 1;

    if($('.slide_index').length > 0) {
        slideIndex = $('.slide_index').text();
    }
    showSlides(slideIndex);

    function showSlides(n) {
        var slides = $(".mySlides");
        var slides_info = $(".mySlides_info");

        if (n > $(slides).length) {
            n = 1;
        } else if (n < 1){
            n = $(slides).length;
        } else {
            n = n;
        } 

        $(slides).each(function( index, element ) {
           
            if(index == n-1) {
                $(element).show();
                $(element).addClass('block');
                $(element).css('display','block');
               
            } else {
                $(element).hide();
                $(element).removeClass('block');
            }
            
        });
        $(slides_info).each(function( index, element ) {
            if(index == n-1) {
                $(element).show();
                $(element).addClass('block');
                $(element).css('display','block');
               
            } else {
                $(element).hide();
                $(element).removeClass('block');
            }
        });
       
    }

    function plusSlides(n) {
        var currentSlide;
        var slides = $(".mySlides");
        
        $(slides).each(function( index, element ) {
            if( $(element).hasClass('block')) {
                currentSlide = index+1;
            }
        });
        
        showSlides(currentSlide += n);
    }

    $('.prev').on('click',function(){
        plusSlides(-1);
    });
    $('.next').on('click',function(){
        plusSlides(+1);
    });
});