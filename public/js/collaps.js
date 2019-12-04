
$(function() {
    $('.collapsible').click(function(event){ 
        console.log("collapsible1");
        $(this).siblings().toggle();

        
  /*      if($(this).siblings().is(":visible")){ 
            $(this).siblings().css('display','none');
        } else {
            $(this).siblings().css('display','inline-block');
        }*/
        //      $(this).next('.content').show();        
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