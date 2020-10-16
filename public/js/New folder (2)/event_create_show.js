$('.event_show .type_event').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('event');
    $('.event_show').hide();
});
$('.event_show .type_task').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('task');
    $('.event_show').hide();
});
$('.event_show .type_other').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('other');
    $('.event_show').hide();
});