
$( document ).ready(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
   
    var all_height = [];
    $('.email_title').each(function(){
        all_height.push($(this).height());
    });
    all_height.sort(function(a, b) {
        return b-a;
    });
    var max_height = all_height[0];
    $('.email_title').height(max_height);

});
$( window ).resize(function() {
    
});
