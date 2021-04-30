$('.period').on('change',function(){
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

try {
    var design = JSON.parse( $('.dataArr').text()); // template JSON */
    var html = $('.dataArrHtml').text();
    var form_data = $('.form_sequence').serialize();
    var url = $('form.form_sequence').attr('action');
    var data_new = {};
    var json = '';
    var html = '';
    var id = $('#id').val();

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
        projectId: 16716,
        displayMode: 'email'
    })
    unlayer.loadDesign(design);
    
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
    
        /* 	$('#text_html').text( html.replace(/\n\s+|\n/g, ""));
            $('#text_json').text(JSON.stringify(json)); */
        })
    
    })
} catch (error) {
    
}

$('.form_sequence.edit .btn-submit').on('click',function(e) {
    var validate = [];
	e.preventDefault();
	form_data = $('.form_sequence').serialize();
    form_data_array = $('.form_sequence').serializeArray();
    data_new = form_data;
    var form = $('.form_sequence')[0];
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json', JSON.stringify(design) );
    data.append('text_html', html );
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {
            validate.push("block");
        } else {
            validate.push(true);
        }
      });

    console.log(form_data_array);
    console.log(validate); 

    if(validate.includes("block") ) {
        e.preventDefault();
      
        alert("Nisu uneseni svi parametri, nemoguće spremiti sekvencu");
        
     } else {    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
    
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("SUCCESS : ", form_data_array);
                $(".btn-submit").prop("disabled", false);
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

$('.btn-back').on('click',function(e){
  /*   e.preventDefault();

    url = location.origin + '/dashboard';
    console.log("url "+ url);
    console.log("referrer "+ document.referrer);
    window.location = url;
    console.log(" window.location = url");
 */
  /*   window.location = location.origin + "/campaign_sequences/" + campaign_id; */
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;

if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
    
    $.each(dataArrTemplates, function(i, item) {
       /*  html = dataArrTemplates[i].text;  */
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});