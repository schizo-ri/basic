if($('.mail_form').length > 0) {
    var selected_element;
    var color;
    var font_size;
    var text_align;
    var border_color;
    var background_color;
    var border_width;
    var border_style;
    var selected_id;
    var this_element ;

    styleElement ();
    $('#mail_template>div>input').on('click',function(){ 
           var this_element = $(this);
           var parent = $( this ).parent();
           var parent_id = $(parent).attr('id');
           console.log("selected_id " + $(this_element).attr('id'));
           console.log("parent_id " + parent_id);
           styleElement ();
    }); 

    $('.add_line').on('click',function(){
        var parent = $( this ).parent();
        var parent_id = $(parent).attr('id');
        var count_input = parent.find('input').length;
        this_element ='<input name="text_'+parent_id+'[text]['+(count_input+1)+']" type="text" id="text_'+parent_id+'[text]['+(count_input+1)+']" class="text_'+parent_id+'" />';
        $( this_element ).insertBefore($(this));
        $( parent ).find('input').last().trigger('focus');
        styleElement ();
    });
   
    function styleElement () {
        $('#mail_template>div').on('click',function(){
            selected_element = $(this);
            selected_id = $(selected_element).attr('id');
      
            $('#mail_template>div').css('border','1px dashed #eee');
            $(selected_element).css('border','1px solid #ccc');
            $('#style_items>div').attr('hidden',true);
            style_element = $('#style_items>div.'+selected_id);
            $(style_element).attr('hidden',false);
        
             $(style_element).find('.color_'+selected_id).on('input',function(){
                color = $( this ).val();
                $(selected_element).css('color', color ); 
                $(selected_element).children().css('color', color ); 
            });
            $(style_element).find('.font-size_'+selected_id).on('change',function(){
                font_size = $( this ).val();
                $(selected_element).css('font-size',font_size +'px');
                $(selected_element).children().css('font-size',font_size);
            });
            $(style_element).find('.text-align_'+selected_id).on('change',function(){
                text_align = $( this ).val();
                $(selected_element).css('text-align',text_align);
                $(selected_element).children().css('text-align',text_align);
            });
            $(style_element).find('.padding-left_'+selected_id).on('change',function(){
                padding_left = $( this ).val();
                $(selected_element).css('padding-left',padding_left);
                $(selected_element).children().css('padding-left', padding_left);
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
                $(selected_element).children().css('background-color',background_color);
            }); 
        });
        /*  selected_id = $(this_element).attr('id');
        console.log("selected_id " +selected_id);
        console.log("parent_id " + parent_id);

        $('#mail_template>div').css('border','1px dashed #eee');
        $(parent).css('border','1px solid #ccc');

        $('#style_items>div').attr('hidden',true);
        style_element = $('#style_items>div.'+parent_id);
        $(style_element).attr('hidden',false);

        style_element = $('#style_items>div.'+parent_id);
        
        
        $(style_element).find('.color_'+parent_id).on('input',function(){
            color = $( this ).val();
            $(this_element).css('color', color ); 
        });
        $(style_element).find('.font-size_'+parent_id).on('change',function(){
            font_size = $( this ).val();
            $(this_element).css('font-size',font_size +'px');
        });
        $(style_element).find('.text-align_'+parent_id).on('change',function(){
            text_align = $( this ).val();
            $(this_element).css('text-align',text_align);
        });
        $(style_element).find('.padding-left_'+parent_id).on('change',function(){
            padding_left = $( this ).val();
            $(this_element).css('padding-left',padding_left);
        });
        $(style_element).find('.border-color_'+parent_id).on('input',function(){
            border_color = $( this ).val();
            $(this_element).css('border-color',border_color);
        });
        $(style_element).find('.border-width_'+parent_id).on('input',function(){
            border_width = $( this ).val();
            $(this_element).css('border-width',border_width);
        });
        $(style_element).find('.border-style_'+parent_id).on('input',function(){
            border_style = $( this ).val();
            $(this_element).css('border-style', border_style);
        });
        $(style_element).find('.background-color_'+parent_id).on('input',function(){
            background_color = $( this ).val();
            $(this_element).css('background-color',background_color);
        });
     */
    }
}