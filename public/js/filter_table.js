function mySearchTable() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var search_Array = value.split(" ");
console.log(value);
console.log(search_Array);
    $(".display.table tr").filter(function() {
      if( search_Array.length == 1 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
      } else if( search_Array.length == 2 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
      } else if( search_Array.length == 3 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
      } else if( search_Array.length == 4 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
      } else if( search_Array.length == 5 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
      }
    });
    if($("#index_table1 tbody tr").length > 0 ) {
      $("#index_table1 tbody tr").filter(function() {
        if( search_Array.length == 1 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
        } else if( search_Array.length == 2 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
        } else if( search_Array.length == 3 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
        } else if( search_Array.length == 4 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
        } else if( search_Array.length == 5 ) {
          $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
        }
      });
    }
    
  });
}

function mySearch() {
  $("#mySearch").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var search_Array = value.split(" ");

    $(".panel").filter(function() {
      /* $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1) */
      if( search_Array.length == 1 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
      } else if( search_Array.length == 2 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
      } else if( search_Array.length == 3 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
      } else if( search_Array.length == 4 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
      } else if( search_Array.length == 5 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
      }
    });
  
  });
}

function mySearchTableAbsence() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var search_Array = value.split(" ");
    $("#index_table tbody tr").filter(function() {
      if( search_Array.length == 1 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
      } else if( search_Array.length == 2 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
      } else if( search_Array.length == 3 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
      } else if( search_Array.length == 4 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
      } else if( search_Array.length == 5 ) {
        $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
      }
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

/* function mySearch_col1() {
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
} */

function mySearchElement() {
  $("#mySearchElement").on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$(".user_card").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}