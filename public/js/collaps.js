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

   
});