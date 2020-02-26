	$('.efc_show').click(function(){
       // $('.efc').css('visibility','initial');
       $('.salery_hidden').hide();
       $('.salery_show').toggle();
       $('.salery_show').css('display','block');
        $('.efc_show').hide();
        $('.efc_hide').show();
    });
    $('.efc_hide').click(function(){
       // $('.efc').css('visibility','hidden');
       $('.salery_hidden').toggle();
       $('.salery_hidden').css('display','block');
       $('.salery_show').toggle();
        $('.efc_show').show();
        $('.efc_hide').hide();
    });