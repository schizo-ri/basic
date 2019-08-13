$('.button_nav.load_button').click(function(e){
    e.preventDefault();
    var page = $(this).attr('href');   
    $('.button_nav').css({
        'background': '#051847',
        'color': '#ffffff'
    });
    $( this ).css({
        'background': '#0A2A79',
        'color': '#ccc'
    });
   $('.container').load(page + ' .container > div'); 



});



