var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();

$('.main_campaign').height(form_sequence_height-header_campaign_height);

var form_data = $('.form_sequence').serialize();
var url = $('form.form_sequence').attr('action');
var data_new = {};
var json;
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
    projectId: 4839,
    displayMode: 'email'
})

if($('.dataArr').text()) {
    design = JSON.parse( $('.dataArr').text()); // template JSON */
    html = $('.dataArr_html').text();
    unlayer.loadDesign(design);
}

unlayer.addEventListener('design:updated', function(updates) {
    unlayer.exportHtml(function(data) {
        json = data.design; // design json
        html = data.html; // design html
        design = data.design;
      /*   $('#text_html').text(html);
        $('#text_json').text(JSON.stringify(json)); */
    })
})		

$('.btn-submit').click(function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#notice_form')[0];
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text_html', html );
    
    $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
    form_data = $('.form_sequence').serialize();
    form_data_array = $('.form_sequence').serializeArray();

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
    if(validate.includes("block") ) {
        event.preventDefault();
      
        alert("Nisu uneseni svi parametri, nemoguće spremiti obavijest");
        
    } else { 
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
            error: function (e) {
                alert("Dizajn nije spremljen, došlo je do greške!");
                console.log("ERROR : ", e);
                $(".btn-submit").prop("disabled", false);

            }
        });
    }
});
$('.link_back').click(function(e){
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

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var designTemplates;
var htmlTemplates
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
  /*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
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