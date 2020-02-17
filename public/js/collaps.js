$(function() { 
    console.log("collapse");
    $('.collapsible').click(function(event){ 
   
       if($(this).siblings().is(":visible")){ 
            $(this).siblings().css('display','none');
        } else {
            $(this).siblings().css('display','inline-block');
        }
     
    });
    $('.index_page table.dataTable .content').mouseleave(function(){
        $(this).hide();
    });

    $('.modal.modal_questionnaire .question .content').mouseleave(function(){
        $(this).hide();
    });
    $('.modal.modal_questionnaire .category .content').mouseleave(function(){
        $(this).hide();
    });
});