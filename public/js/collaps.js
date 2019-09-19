
$(function() {
    $('.collapsible').click(function(event){ 
        $(this).next('.content').show();
        
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