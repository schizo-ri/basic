var form_data;
var url = $('form.form_template').attr('action');
var data_new = {};
var json;
var html; 
var design;
var temp;
try {
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        mergeTags: {
            first_name: {
              name: "First Name",
              value: "Jelena"
            },
            last_name: {
              name: "Last Name",
              value: "Juras"
            }
        },
        id: 'editor-container',
        projectId: 4441,
        displayMode: 'email'
    })
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            body = json.body;
            design = data.design;
        })
    })
} catch (error) {
    
}
	

$('.form_template.template_create .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#form_template')[0];
    
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text', html );
   
  //  $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
 /*    form_data = $('.form_template').serialize(); */
    form_data_array = $('.form_template').serializeArray();

    
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "") {  //$(field).attr('required') && 
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
    console.log(validate);
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti predložak");
    } else { 
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
                console.log("responce : ", data);
                $(".btn-submit").prop("disabled", false);
                location.reload();
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
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */

/*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
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

// LINK BACK   
$('.main_noticeboard .header_document .link_back').on('click',function(e){
       
});

