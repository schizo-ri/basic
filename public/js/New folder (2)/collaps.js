$(function() { 
    $('.collapsible').on('click',function(event){ 
       if($(this).siblings().is(":visible")){ 
            $(this).siblings().css('display','none');
        } else {
            $(this).siblings().css('display','inline-block');
        }
     
    });
    $('.index_page table.dataTable .content').on('mouseleave',function(){
        $(this).hide();
    });

    $('.modal.modal_questionnaire .question .content').on('mouseleave',function(){
        $(this).hide();
    });
    $('.modal.modal_questionnaire .category .content').on('mouseleave',function(){
        $(this).hide();
    });

    $('ul.admin_pages li >span.arrow_down ').on('click',function(event){ 
        if($(this).parent().siblings('.car_links').is(":visible")){ 
            $(this).parent().siblings('.car_links').css('display','none');
         } else {
            $(this).parent().siblings('.car_links').css('display','block');
         }
     });
});