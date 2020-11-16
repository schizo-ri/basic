$(function(){
    var employee_id;
	var type;
	var start_date;
	var end_date;
	var tomorrow;
	var diff_days;
	var broj_dana;
	var date = new Date();
	var today = date.getFullYear() + '-' + ( '0' + (date.getMonth()+1) ).slice( -2 ) + '-' + ( '0' + (date.getDate()) ).slice( -2 );
	
	$( "#request_type" ).on('change',function() {
		type = $(this).val();
		if(type == 'IZL') {
			start_date = $( "#start_date" ).val();
			end_date = $( "#end_date" );
			end_date.val(start_date);
			
			date.setDate(date.getDate()-1);
			tomorrow = date.getFullYear() + '-' + ( '0' + (date.getMonth()+1) ).slice( -2 ) + '-' + ( '0' + (date.getDate()) ).slice( -2 );
			$('#start_date').attr('min', tomorrow);
			$('#start_date').val(today);
			$('.form-group.time').show();
			$('.form-group.date2').hide();
		} else {
			$('.form-group.time').hide();
			$('.form-group.date2').show();
			$('#start_date').removeAttr('min');
		}

		if(type == 'GO') {
			employee_id = $('#select_employee').val();
			console.log("employee_id " + employee_id);
			if( employee_id != '' &&  employee_id != undefined) {
				url = location.origin + '/getDays/'+employee_id;
				console.log(url);
				$.ajax({
					url: url,
					type: "get",
					success: function( days_response ) {
						broj_dana = days_response;
						
						if( broj_dana == 0 ) {
                            $('.days_employee').text("Nemoguće poslati zahtjev. Svi su dani iskorišteni!");
							$('input[name=start_date]').prop('disabled', true);
							$('input[name=end_date]').prop('disabled', true);
							$('.btn-submit').hide();
						} else {
                            $('.days_employee').text("Neiskorišteno "+broj_dana+" dana razmjernog godišnjeg odmora");
							$('input[name=start_date]').prop('disabled', false);
							$('input[name=end_date]').prop('disabled', false);
							$('.btn-submit').show();
                        }
                        $('.days_employee').show();
					},
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr);
						console.log(json);
						console.log(errorThrown);
					}
				});
			}
		} else {
            $('.days_employee').text("");
            $('.days_employee').hide();
            $('.btn-submit').show();
        }

        if(type == 'SLD') {
            employee_id = $('#select_employee').val();

            url = location.origin + '/days_offUnused/'+employee_id;
            console.log(url);
            $.ajax({
                url: url,
                type: "get",
                success: function( days_response ) {
                    broj_dana = days_response;
                    if( broj_dana == 0 ) {
                        $('.days_employee').text("Nemoguće poslati zahtjev. Svi slobodni dani su iskorišteni!");
                        $('.btn-submit').hide();
                    } else {
                        $('.days_employee').text("Neiskorišteno "+broj_dana+" slobodnih dana");
                        $('.btn-submit').show();
                    }
                    $('.days_employee').show();
                },
                error: function(jqXhr, json, errorThrown) {
                    console.log(jqXhr);
                    console.log(json);
                    console.log(errorThrown);
                }
            });
        }
	});
    
    $( "#select_employee" ).on('change',function() {
		employee_id = $(this).val();
		
		type = $( "#request_type" ).val();
		console.log("employee_id " + employee_id);
		if( employee_id != '' &&  employee_id != undefined && type == 'GO') {
			url = location.origin + '/getDays/'+employee_id;
			console.log(url);
			$.ajax({
				url: url,
				type: "get",
				success: function( days_response ) {
					broj_dana = days_response;
					console.log("broj_dana "+broj_dana);
					if( broj_dana == 0 ) {
                        $('.days_employee').text("Nemoguće poslati zahtjev. Svi su dani iskorišteni!");
                        $('input[name=start_date]').prop('disabled', true);
                        $('input[name=end_date]').prop('disabled', true);
                        $('.btn-submit').hide();
                    } else {
                        $('.days_employee').text("Neiskorišteno "+broj_dana+" dana razmjernog godišnjeg odmora");
                        $('input[name=start_date]').prop('disabled', false);
                        $('input[name=end_date]').prop('disabled', false);
                        $('.btn-submit').show();
                    }
                    $('.days_employee').show();
				},
				error: function(jqXhr, json, errorThrown) {
					console.log(jqXhr);
					console.log(json);
					console.log(errorThrown);
				}
			});
		}
	});	
    
    $( "#start_date" ).on('change',function() {
		start_date = $( this ).val();
		end_date = $( "#end_date" );
		end_date.val(start_date);
	});
    
    $( "#end_date" ).on('change',function() {
		console.log("broj_dana_end_date " + broj_dana);
		start_date = $( "#start_date" ).val();
		end_date = $( this ).val();
		if(start_date == '') {
			$( "#start_date" ).val(end_date);
		}
		type = $( "#request_type" ).val();
		employee_id = $('#select_employee').val();
		if( employee_id != '' && type == 'GO' ) {
			diff_days = date_diff_indays( start_date,end_date );
			console.log(diff_days);
			if( broj_dana < diff_days ) {
				$('.days_request>span').text(diff_days - broj_dana);
				$('.days_request').show();
				$('.btn-submit').hide();
			} else {
				$('.days_request>span').text("");
				$('.days_request').hide();
				$('.btn-submit').show();
			}
		}
	});
	
});

function date_diff_indays(date1, date2) { // input given as Date objects
	var dDate1 = new Date(date1);
	var dDate2 = new Date(date2);
	var iWeeks, iDateDiff, iAdjust = 0;
	if (dDate2 < dDate1) return -1; // error code if dates transposed
	var iWeekday1 = dDate1.getDay(); // day of week
	var iWeekday2 = dDate2.getDay();
	iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1; // change Sunday from 0 to 7
	iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
	if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1; // adjustment if both days on weekend
	iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1; // only count weekdays
	iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;

	// calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
	iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)

	if (iWeekday1 < iWeekday2) { //Equal to makes it reduce 5 days
		iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
	} else {
		iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
	}

	iDateDiff -= iAdjust // take into account both days on weekend

    return (iDateDiff + 1); // add 1 because dates are inclusive
}