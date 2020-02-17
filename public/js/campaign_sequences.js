$('.period').change(function(){
    if($(this).val() == 'customized') {
        $('#period .period').hide();
        $('#period .period').removeAttr('required');
        $('#interval').show();
        $('input.input_interval').prop( "required" );
    }
});

$('.label_custom_interal').click(function(){
    $('#period .period').hide();
    $('#period .period').removeAttr('required');
    $('#interval').show();
    $('input.input_interval').prop( "required" );
});
$('.label_period').click(function(){
    $('#period .period').show();
    $('#period .period').prop('required');
    $('#interval').hide();
    $('input.input_interval').removeAttr( "required" );
});