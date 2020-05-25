$( function () {
    $('.dates li').first().addClass('active_date');

    var div_width = $( '.dates').width();
    var all_width = 69;
    var dates = $('.box-content').find('.dates');
    var day_of_week = new Array("SUN","MON","TUE","WED","THU","FRI","SAT");
    var monthNames = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    var today = new Date();
    var date_today = today.getFullYear() + '-' +  ('0' + (today.getMonth() +1) ).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);

    var broj_dana = div_width / all_width;

    dates.append('<li id="li-' + date_today + '" class="active_date"><span class="month">' + monthNames[today.getMonth()] +  '</span><span class="day">' + today.getDate() +  '</span><span class="week_day">' + day_of_week[today.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');

    for(i=0; i<broj_dana-1; i++) {
        var date_plus1 = new Date(today.setDate(today.getDate() +1));

        var date_new = date_plus1.getFullYear() + '-' +  ('0' + (date_plus1.getMonth() +1) ).slice(-2) + '-' + ('0' + date_plus1.getDate()).slice(-2);

        dates.append('<li id="li-' + date_new + '" class=""><span class="month">' + monthNames[date_plus1.getMonth()] +  '</span><span class="day">' + date_plus1.getDate() +  '</span><span class="week_day">' + day_of_week[date_plus1.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
    }

    $( window ).resize(function() {
        var div_width = $( '.dates').width();
        var broj_dana = div_width / all_width;
        
        for(i=0; i<broj_dana; i++) {
            var date_plus1 = new Date(today.setDate(today.getDate() +1));
    
            var date_new = date_plus1.getFullYear() + '-' +  ('0' + (date_plus1.getMonth() +1) ).slice(-2) + '-' + ('0' + date_plus1.getDate()).slice(-2);
    
            dates.append('<li id="li-' + date_new + '" class=""><span class="month">' + monthNames[date_plus1.getMonth()] +  '</span><span class="day">' + date_plus1.getDate() +  '</span><span class="week_day">' + day_of_week[date_plus1.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
        }

    });
   
    //prikaz evenata za selektirani dan
    $('.dates li').click(function(){
        var active_li =  $(this).attr('id');
        var active_date = active_li.replace('li-','');
        console.log(active_date);

        var url = location.origin + '/dashboard?active_date='+active_date;
        console.log(url);
        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});   
        $.ajax({
			url: url,
			type: "GET",
			success: function( response ) {
				console.log("prošlo");
				$('.comming_agenda').load(url + ' .comming_agenda>a, .comming_agenda>h3, .comming_agenda .all_agenda');
			}, 
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr);
			}
        });
    });
    
 
    /*i f(active_li) {
        var active_li_id = active_li.replace("li-",""); // selektirani datum
        $( ".comming_agenda > .agenda" ).each( function(index, element) {
            $(element).addClass('display_none');
            if($(element).attr('id') == active_li_id ) {
                $(element).removeClass('display_none');
                $(element).addClass('show_agenda');
            }
        });
    }
 */
    if(! $('.comming_agenda .agenda.show_agenda').length) {
        var calendar_height = $('section.calendar>div').height() - $('section.calendar #calendar').height() -40;
        $('.comming_agenda .placeholder').show();
        var placeholder_height =  $('.placeholder img').height();
        $('.calendar .comming_agenda').height(calendar_height );
        //   $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' );
    } else {
        $('.comming_agenda .placeholder').hide();
    }
    
    $('#left-button').click(function() {
        var active_li = $('.dates').find('li.active_date');

        var first_li = $(dates).find('li').first();
        var day = first_li.find('.day').text();
        var month = first_li.find('span.YYYY_mm').text().slice(5,7);
        var year = first_li.find('span.YYYY_mm').text().slice(0,4);
        var currentDate = new Date(year + '-' + month + '-' + day);
        var date_prev = new Date(currentDate.setDate(currentDate.getDate() -1));

        var date = date_prev.getFullYear() + '-' +  ('0' + (date_prev.getMonth() +1) ).slice(-2) + '-' + ('0' + date_prev.getDate()).slice(-2);

        if($('.dates').scrollLeft() == 0) {
            dates.prepend('<li id="li-' + date + '" class=""><span class="month">' + monthNames[date_prev.getMonth()] +  '</span><span class="day">' + date_prev.getDate() +  '</span><span class="week_day">' + day_of_week[date_prev.getDay()]  +  '</span><span class="display_none YYYY_mm">' + currentDate.getFullYear()  + '-' + + ('0' + (currentDate.getMonth()+1)).slice(-2)+ '</span></li>');
        }
        $('.dates').animate({
            scrollLeft: "-=69"
        }, "slow");
        var previous_li = active_li.prev();
        previous_li.addClass('active_date');
        active_li.removeClass('active_date');
        
        previous_li.click();

        $.getScript( '/../js/event_click.js');
    });

    $('#right-button').click(function() {
        var active_li = $('.dates').find('li.active_date');

        var last_li = $(dates).find('li').last();
        var day_last = last_li.find('.day').text();
        var month = last_li.find('span.YYYY_mm').text().slice(5,7);
        var year = last_li.find('span.YYYY_mm').text().slice(0,4);

        var lastDate = new Date(year + '-' + month + '-' + day_last);
        var date_next = new Date(lastDate.setDate(lastDate.getDate() +1));

        var next_date = date_next.getFullYear() + '-' + ('0' + (date_next.getMonth() +1) ).slice(-2) + '-' + ('0' + date_next.getDate()).slice(-2);
  
        $('.dates').animate({
            scrollLeft: "+=69"
        }, "slow");
        var count_li = 0;
        $( ".dates > li" ).each(function (index, element) {
            all_width += 69;
            count_li++;
        });
        if(((count_li * 69) - (div_width + 69)) < $('.dates').scrollLeft() ){
            dates.append('<li id="li-' + next_date + '" class=""><span class="month">' + monthNames[date_next.getMonth()] +  '</span><span class="day">' + date_next.getDate() +  '</span><span class="week_day">' + day_of_week[date_next.getDay()] +  '</span><span class="display_none YYYY_mm">' + date_next.getFullYear()  + '-' + + ('0' + (date_next.getMonth()+1)).slice(-2)+ '</span></li>');
        }
        var next_li = active_li.next();

        next_li.addClass('active_date');
        active_li.removeClass('active_date');
        next_li.click();        
        
        $.getScript( '/../js/event_click.js');
    });
});
