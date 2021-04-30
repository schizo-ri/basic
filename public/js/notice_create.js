var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();
var url = $('form.form_sequence').attr('action');
var form_data;
var data_new = {};
var json;
var html; 
var design;
var temp;
$(function() {
    if($('body').width() > 760) {
        $('.main_campaign').height(form_sequence_height-header_campaign_height);
    }

// UNLAYER INIT    
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
            id: 'editor-container',
            projectId: 16716,
            displayMode: 'email'
        })

        unlayer.addEventListener('design:updated', function(updates) {
            unlayer.exportHtml(function(data) {
                json = data.design; // design json
                html = data.html; // design html
                design = data.design;
            })
        })		
    } catch (error) {
        console.log(error);
        alert( 'Molim kontaktirati administratora, nešto ne radi propisno. ' + error);
    }

    $('.form_sequence.notice_create .btn-submit').on('click',function(e) {
        var validate = [];
        e.preventDefault();
        var form = $('#notice_form')[0];
        
        var data = new FormData(form);              // Create an FormData object 
        data.append('text_json',JSON.stringify(design) );
        data.append('text_html', html );

        $(".btn-submit").prop("disabled", true);   // disabled the submit button
    
        form_data = $('.form_sequence').serialize();
        form_data_array = $('.form_sequence').serializeArray();

        var alert_text = '';
        console.log(form_data_array);
       
        jQuery.each( form_data_array, function( i, field ) {
            if(field.value == "" ) {  //$(field).attr('required') && 
                if( field.name == 'title' ) {
                    alert_text += 'Nije unesen naslov obavijesti! ';
                }
                if( field.name == 'schedule_date' ) {
                    alert_text += 'Nije postavljen datum!';
                }
                if( field.name == 'notice_create.js:65 schedule_time' ) {
                    alert_text += 'Nije postavljeno vrijeme! ';
                }
                validate.push("block");
            } else {
                validate.push(true);
            }
        });
        if( data.get('to_department[]') == null ) {
            alert_text += 'Nije unesen odjel na koji se šalje obavijest! ';
            validate.push("block");
        } else {
            validate.push(true);
        }
        if( html == undefined  || JSON.stringify(design) == undefined ) {
            alert_text += 'Nije unesena obavijest! ';
            validate.push("block");
        } else {
            validate.push(true);
        }
        console.log(validate);
        
        if(validate.includes("block") ) {
            alert(alert_text);
            $(".btn-submit").prop("disabled", false);
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
                    alert("Obavijest je uspješno spremljena te istu možete vidjeti na 'Naslovnoj strani' pod 'Oglasna ploča' ili na 'Oglasnoj ploči'!");
                    window.location = location.origin;
                },
                error: function(jqXhr, json, errorThrown) {
                    var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                        'message':  jqXhr.responseJSON.message,
                                        'file':  jqXhr.responseJSON.file,
                                        'line':  jqXhr.responseJSON.line };
                    $.ajax({
                        url: location.origin + '/errorMessage',
                        type: "get",
                        data: data_to_send,
                        success: function( response ) {
                            $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                            $(".btn-submit").prop("disabled", false);
                        }, 
                        error: function(jqXhr, json, errorThrown) {
                            console.log(jqXhr.responseJSON); 
                        }
                    });
                }
            });
        }
    });

    // LINK BACK   
    $('.main_noticeboard .header_document .link_back').on('click',function(e){
        e.preventDefault();
        var url = location['origin'] +'/campaigns';
        
        $('.container').load( url + ' .container > div', function() {		
            $.getScript( '/../js/datatables.js');
            $.getScript( '/../js/filter_table.js');                    
            $.getScript( '/../restfulizer.js');
            $.getScript( '/../js/event.js');
            $.getScript( '/../js/campaign.js');
        /*  $('.collapsible').click(function(event){        
                $(this).siblings().toggle();
            }); */
        });		
    });

/*  PREDLOŠCI  */
    var dataArrTemplates;
    var designTemplates;
    var htmlTemplates
    if($('.dataArrTemplates').text()) {
        dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
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
});
