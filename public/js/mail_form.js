if($('.mail_form').length > 0) {
    var selected_element;
    var color;
    var font_size;
    var text_align;
    var border_color;
    var background_color;
    var border_width;
    var border_style;

     $('#mail_template>div').on('click',function(){
        selected_element = $(this);
        var selected_id = $(selected_element).attr('id');
        console.log(selected_id);
        $('#mail_template>div').css('border','1px dashed #eee');
        $(selected_element).css('border','1px solid #ccc');
        $('#style_items>div').attr('hidden',true);
        var style_element = $('#style_items>div.'+selected_id);
        $(style_element).attr('hidden',false);
        
        $(style_element).find('.color_'+selected_id).on('input',function(){
            color = $( this ).val();
            console.log(color);
            $(selected_element).css('color', color ); 
        });
        $(style_element).find('.font-size_'+selected_id).on('change',function(){
            font_size = $( this ).val();
            $(selected_element).css('font-size',font_size +'px');
        });
        $(style_element).find('.text-align_'+selected_id).on('change',function(){
            text_align = $( this ).val();
            $(selected_element).css('text-align',text_align);
        });
        $(style_element).find('.border-color_'+selected_id).on('input',function(){
            border_color = $( this ).val();
            $(selected_element).css('border-color',border_color);
        });
        $(style_element).find('.border-width_'+selected_id).on('input',function(){
            border_width = $( this ).val();
            $(selected_element).css('border-width',border_width);
        });
        $(style_element).find('.border-style_'+selected_id).on('input',function(){
            border_style = $( this ).val();
            $(selected_element).css('border-style', border_style);
        });
        $(style_element).find('.background-color_'+selected_id).on('input',function(){
            background_color = $( this ).val();
            $(selected_element).css('background-color',background_color);
        });
        
    });
}