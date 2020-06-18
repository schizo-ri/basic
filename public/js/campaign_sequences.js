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

var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();

$('.main_campaign').height(form_sequence_height-header_campaign_height);

/* var campaign_id = $('#campaign_id').val(); */
/* var form_data = $('.form_sequence').serialize(); */
var url = $('form.form_sequence').attr('action');
/* var json; */
var html; 
var design; 

unlayer.init({
    appearance: {
        theme: 'light',
        panels: {
            tools: {
                dock: 'left'
            }
        }
    },
    id: 'editor-container',
    projectId: 4441,
    displayMode: 'email'
})

unlayer.addEventListener('design:updated', function(updates) {
    unlayer.exportHtml(function(data) {
        /* json = data.design; */ // design json
        html = data.html; // design html
        design = data.design;
     /*    $('#text_html').text(html);
        $('#text_json').text(JSON.stringify(design)); */
      /*   console.log(html);
        console.log(JSON.stringify(design)); */
    })
})			

$('.btn-submit').click(function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('.form_sequence')[0];

    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text_html', html );

    $(".btn-submit").prop("disabled", true);   // disabled the submit button
    
    var form_data_array = $('.form_sequence').serializeArray();
    
/*     console.log("data:");
    console.log(data); */

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( html == undefined  || JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
 
    if(validate.includes("block") ) {
        event.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti sekvencu");
     } else {    
        $(".btn-submit").prop("disabled", false);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
               /*  console.log("SUCCESS : ", form_data_array); */
               // console.log("responce : ", data);
                $(".btn-submit").prop("disabled", false);
              //  location.reload();
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
     }  
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});


var dataArrTemplates;
var htmlTemplates;
var designTemplates;
var temp;

if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
    /* console.log(dataArrTemplates); */
    
    $.each(dataArrTemplates, function(i, item) {

        var title = item.title;
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').click(function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).mouseover( function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).mouseout( function(){
    $('.show_temp#temp' + temp).remove();
});