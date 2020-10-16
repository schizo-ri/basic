	$(function() {

		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_width = $('body').width();
		var body_height =  modal_height - header_height - 80;
		if(body_width > 450) {
			$('.modal-body').height(body_height);
		}
		
		var countElement = 0;
		$('textarea').each(function(){
			countElement += 1;
		});
		$('input[type="radio"]').parent().parent().each(function(){
			countElement += 1;
		});
		$('input[type="checkbox"]').parent().parent().each(function(){
			countElement += 1;
		});
		$( "#questionnaire_form span.progress_val" ).text( 0 + '/' + countElement);
		var countChecked = function() {
			var n = $( "input[type=radio]:checked" ).length;
			
			$( "textarea" ).each(function(){ 
				if($(this).val() != '') {
					n += 1;
				}
			});
			$('input[type="checkbox"]:checked').parent().parent().each(function(){
				n += 1;
			});
				
			$( "#questionnaire_form span.progress_val" ).text( n + '/' + countElement);
			var valProgress = n/countElement*100;
			$('#questionnaire_form .progress_bar .progress').css('width', valProgress + '%');
		};
		countChecked();
		
		$( "input[type=radio]" ).on( "click", countChecked );
		$( "input[type=checkbox]" ).on( "click", countChecked );
		$( "textarea" ).on( "change", countChecked );
	});
	$( window ).resize(function() {
		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_height =  modal_height - header_height - 80;
		$('.modal-body').height(body_height);
		
	});
	$('.btn-statistic').click(function(){
		$('.statistic').toggle();
		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_height =  modal_height - header_height - 80;
		$('.modal-body').height(body_height);
	});
