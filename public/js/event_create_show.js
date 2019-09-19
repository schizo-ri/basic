$('.event_show .type_event').click(function(){
    $('.event_hidden').show();
    $('#event_type').val('event');
    $('.event_show').hide();
});
$('.event_show .type_task').click(function(){
    $('.event_hidden').show();
    $('#event_type').val('task');
    $('.event_show').hide();
});
$('.event_show .type_other').click(function(){
    $('.event_hidden').show();
    $('#event_type').val('other');
    $('.event_show').hide();
});