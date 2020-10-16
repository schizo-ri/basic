 $(function() {
    $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show');
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal-body').height(body_height);
    
});
$( window ).resize(function() {
    $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show');
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal-body').height(body_height);
    
});
$('.btn-statistic').click(function(){
    $('.statistic').toggle();
    $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show');
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal-body').height(body_height);
});