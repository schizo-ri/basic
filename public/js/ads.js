$('.select_filter.sort').change(function () {
    $('main.main_ads').load($(this).val() + ' main.main_ads article');
});

var body_width = $('body').width();
if(body_width > 450) {
    var all_height = [];
    $('.ad.panel .ad_content').each(function(){
        all_height.push($(this).height());
    });

    all_height.sort(function(a, b) {
        return b-a;
    });
    var max_height = all_height[0];

    $('.ad.panel .ad_content').height(max_height);
}