if($('.mail_form').length > 0) {
    var selected_element;
    var color;
    var font_size;
    var font_weight;
    var text_align;
    var border_color;
    var background_color;
    var border_width;
    var border_style;
    var selected_id;
    var this_element ;
    var parent;
    var parent_id;

    parentClick();
    inputClick();

    function parentClick() {
        $("#mail_template>div").on('click',function( ){ 
            console.log('mail_template>div');
            parent = $(this);
            parent_id = parent[0]['id'];
            $("#mail_template>div").removeClass('activeElement');
            $('#mail_template>div>input').removeClass('activeElement');
            $(this).addClass('activeElement');
            styleElement (  );
        }).children().on('click', function(e) {
            return false;
        });
    }

    $('.remove_line').on('click',function( ){ 
        selected_element =  $('.activeElement');
        if(selected_element) {
            $(selected_element).remove();
        }
    });

    function inputClick() {
        $('#mail_template>div>input').on('click',function(){ 
            console.log('mail_template>input');
            $("#mail_template>div").removeClass('activeElement');
            $('#mail_template>div>input').removeClass('activeElement');
            $(this).addClass('activeElement');
            var this_element = $( this );
            var parent = $(this).parent();
            parent_id = $( parent ).attr('id');
            styleElement (  );
        }).parent().on('click', function(e) {
            return false;
        });
    }

    $('.add_line').on('click',function(){
        var parent = $( this ).parent();
        parent_id = $(parent).attr('id'); 
        count_input = parent.find('input').length;
        $("#mail_template>div").removeClass('activeElement');
        $('#mail_template>div>input').removeClass('activeElement');
        var this_element = $.parseHTML('<input name="text_'+parent_id+'[text]['+(count_input+1)+']" type="text" id="text_'+parent_id+'[text]['+(count_input+1)+']" class="text_'+parent_id+' activeElement" placeholder="Unesi tekst" />');
        $( this_element ).insertBefore( $(this) );
        $( parent ).find('input').last().trigger('focus');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
        $.ajax({
            type: 'POST', 
            url : location.origin + "/create_style", 
            data: { element: parent_id+'_input', 'count_input': count_input },
            success : function (data) {
                $("#style_items").append(data);
                styleElement ();
                inputClick();
                parentClick();
            }
        });
    });
   
    $('.add_link').on('click',function() {
        var parent = $( this ).parent();
        parent_id = $(parent).attr('id'); 
        count_button = parent.find('input[name=button]').length;
        $("#mail_template>div").removeClass('activeElement');
        $('#mail_template>div>input').removeClass('activeElement');
        var this_element = $.parseHTML('<input name="text_'+parent_id+'[button]['+(count_button+1)+']" type="text" class="button activeElement" value="Link" />');
        $( this_element ).insertBefore( $(this).parent().find('.add_line') );
        $( parent ).find('button').last().trigger('focus');
        
        styleElement ();
        inputClick();
        parentClick()
    });

    function styleElement () {
        selected_element =  $('.activeElement');
        selected_id = $(selected_element).attr('id');
        
        if( selected_element && selected_element[0]['localName'] == 'input' ) {
            parent = $( selected_element ).parent();
            var obj = parent.find('input');
            count_input = obj.index( selected_element );
            parent_id = $( parent ).attr('id') + '_input'+(count_input);
           
        } else {
            parent_id = $( selected_element ).attr('id');
        }

        console.log("selected_id " +selected_id);
        console.log("parent_id " + parent_id);

       /*  $('#mail_template>div').css('border','1px dashed #eee');
        $(parent).css('border','1px solid #ccc'); */
        $('#style_items>div').attr('hidden',true);
        
        style_element = $('#style_items>div.'+parent_id);
        $(style_element).attr('hidden',false);

        $(style_element).find('.color_'+parent_id).on('input',function(){
            color = $( this ).val();
            console.log( color);
            $(selected_element).css('color', color );
        });
        $(style_element).find('.font-size_'+parent_id).on('change',function(){
            font_size = $( this ).val();
            console.log(font_size);
            $(selected_element).css('font-size',font_size);
        });
        $(style_element).find('.font-weight_'+parent_id).on('change',function(){
            font_weight = $( this ).val();
            console.log(font_weight);
            $(selected_element).css('font-weight',font_weight);
        });
        $(style_element).find('.text-align_'+parent_id).on('change',function(){
            text_align = $( this ).val();
            console.log(text_align);
            $(selected_element).css('text-align',text_align);
        });
        $(style_element).find('.padding-left_'+parent_id).on('change',function(){
            padding_left = $( this ).val();
            console.log(padding_left);
            $(selected_element).css('padding-left',padding_left);
        });
        $(style_element).find('.border-color_'+parent_id).on('input',function(){
            border_color = $( this ).val();
            console.log(border_color);
            $(selected_element).css('border-color',border_color);
        });
        $(style_element).find('.border-width_'+parent_id).on('input',function(){
            border_width = $( this ).val();
            console.log(border_width);
            $(selected_element).css('border-width',border_width);
        });
        $(style_element).find('.border-style_'+parent_id).on('input',function(){
            border_style = $( this ).val();
            console.log(border_style);
            $(selected_element).css('border-style', border_style);
        });
        $(style_element).find('.background-color_'+parent_id).on('input',function(){
            background_color = $( this ).val();
            console.log(background_color);
            $(selected_element).css('background-color',background_color);
        });
    }
}