	$(function() {
		$('.modal').addClass('modal_questionnaire');
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

	/*

	$("#ev_employee_id1").change(function(){
		var radio1 = $( '#anketa_2 input[type=radio]' );
		var radio2 = $( '#anketa_1 input[type=radio]' );
		var anketa1 = $('#anketa_1');
		var anketa2 =  $('#anketa_2');
		var ev_employee_id1 = $("#ev_employee_id1").val();
		var employee_id = $("#employee_id").val();
		var tip_ankete = $("#tip_ankete");
		$('.btn-submit.fill').show();
		if(ev_employee_id1 === employee_id){
			tip_ankete.val('podgrupa');
			anketa1.removeAttr('class');
			anketa2.attr("class", "display_none");
			radio1.removeAttr("required");
			radio2.attr('required', 'required');
		}else {
			tip_ankete.val('grupa');
			radio1.attr('required', 'required');
			radio2.removeAttr("required");
			anketa1.attr("class", "display_none");
			anketa2.removeAttr('class');
		}
	});

	$("#tip_ankete").change(function(){
		var radio1 = $( '#anketa_2 input[type=radio]' );
		var radio2 = $( '#anketa_1 input[type=radio]' );
		var anketa1 = $('#anketa_1');
		var anketa2 =  $('#anketa_2');
		var tip_ankete = $("#tip_ankete").val();
		
		if(tip_ankete === 'podgrupa'){
			anketa1.removeAttr('class');
			anketa2.attr("class", "display_none");
			radio1.removeAttr("required");
			radio2.attr('required', 'required');
		}else {
			radio1.attr('required', 'required');
			radio2.removeAttr("required");
			anketa1.attr("class", "display_none");
			anketa2.removeAttr('class');
		}
		
	});*/
	