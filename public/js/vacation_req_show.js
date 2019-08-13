$('#prikaz').change(function(){
	var selected = $('option:selected', this).attr('class');
	var optionText = $('.editable1').text();
	var optionText1 = $('.editable2').text();
	var optionText2 = $('.editable3').text();
	  
	if(selected == "editable1"){
	  $('.uputa_RGO').show();
	  $('.editOption1').show();
	  $('.editOption2').show();
	  $('.editOption3').hide();
	  $('.editOption4').show();
	  $('.uputa_NPL').hide();
	  $('.uputa_PL').hide();
	  $('.text1').show();
	  $('.text2').hide();
	}
	if(selected == "editable4" || selected == "editable5"){
	  $('.editOption1').show();
	  $('.editOption2').show();
	  $('.editOption3').hide();
	  $('.editOption4').hide();
	  $('.uputa_RGO').hide();
	  $('.uputa_NPL').show();
	  $('.uputa_PL').hide();
	  $('.text1').show();
	  $('.text2').hide();
	}
	if(selected == "editable6"){
	  $('.editOption1').show();
	  $('.editOption2').show();
	  $('.editOption3').hide();
	  $('.editOption5').hide();
	  $('.editOption4').hide();
	  $('.uputa_RGO').hide();
	  $('.uputa_NPL').hide();
	  $('.uputa_PL').hide();
	  $('.text1').show();
	  $('.text2').hide();
	}
	if(selected == "editable7"){
	  $('.editOption1').show();
	  $('.editOption2').show();
	  $('.editOption3').hide();
	  $('.editOption5').hide();
	  $('.editOption4').hide();
	  $('.uputa_RGO').hide();
	  $('.uputa_NPL').hide();
	  $('.uputa_PL').show();
	  $('.text1').show();
	  $('.text2').hide();
	  
	}
	if(selected == "editable5"){
	  $('.editOption1').show();
	  $('.editOption2').show();
	  $('.editOption3').hide();
	  $('.editOption5').show();
	  $('.uputa_RGO').hide();
	  $('.uputa_NPL').hide();
	  $('.uputa_PL').hide();
	  $('.text1').show();
	  $('.text2').hide();
	}
	
	if(selected == "editable3"){
	  $('.editOption1').show();
	  $('.editOption2').hide();
	  $('.editOption3').show();
	  $('.editOption4').hide();
	  $('.uputa_RGO').hide();
	  $('.uputa_NPL').hide();
	  $('.uputa_PL').hide();
	  $('.text1').show();
	  $('.text2').hide();
	}
	if(selected == "editable2"){
	  $('.editOption1').show();
	  $('.editOption2').show();
	  $('.editOption3').hide();
	  $('.editOption4').hide();
	  $('.uputa_RGO').hide();
	  $('.uputa_NPL').hide();
	  $('.uputa_PL').hide();
	  $('.text1').hide();
	  $('.text2').show();
	}
	});