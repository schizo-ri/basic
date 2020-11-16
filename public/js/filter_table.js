function mySearchTable() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    
    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    $("#index_table1 tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}

function mySearchTableAbsence() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();

    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}

function mySearchDoc() {
  $("#mySearch").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    $(".panel").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
  });
}

function mySearch_col1() {
  // Declare variables
  var input, filter, ul, li, a, i;
  input = document.getElementById("mySearch");
  filter = input.value.toUpperCase();
  ul = document.getElementById("myTable");
  li = ul.getElementsByTagName("tr");

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("td")[0];
	if (a != undefined){
		if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
		  li[i].style.display = "";
		} else {
		  li[i].style.display = "none";
		}
	}
  }
}

function mySearchElement() {
  $("#mySearchElement").on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$(".user_card").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}