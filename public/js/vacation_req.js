$(function(){
    if( $('.index_page.vacation_index').length > 0 ) {
       /*  $('.add_plan').on('click',function(){
            if (! confirm("Sigurno želiš unijeti plan?")) {
                return false;
            } else {
                return true;
            }
        }); */
        $('.create_request').on('click',function(){
            if (! confirm("Sigurno želiš pokrenuti izradu zahtjeva?")) {
                return false;
            } else {
                return true;
            }
        });
        var slice = $('#no_week').text();
        $('tbody tr td').not('.employee_name').on('mouseover', function(){
            $( this ).css('background','#bbb');
            $( this ).nextAll().slice(0, slice - 1).css('background','#bbb');
            $( this ).nextAll().slice(0, slice - 1).find('a').css('visibility','hidden');
            
        });
        $('tbody tr td').not('.employee_name').on('mouseleave', function(){
            $( this ).css('background','inherit');
            $( this ).nextAll().slice(0, slice - 1).css('background','inherit');
            $( this ).nextAll().slice(0, slice - 1).find('a').css('visibility','visible');
        });

        delete_request ();
        store_request();

        function delete_request () {
            $('.btn-delete').on('click', function(e) {
                if (! confirm("Sigurno želiš obrisati zahtjev?")) {
                    return false;
                } else {
                    e.preventDefault();
                    id = $( this ).attr('id');
                    url_delete = $( this ).attr('href');
                    url_load = location.href;
                    token = $( this ).attr('data-token');

                    $.ajaxSetup({
                        headers: {
                            '_token': token
                        }
                    });
                    $.ajax({
                        url: url_delete,
                        type: 'POST',
                        data: {_method: 'delete', _token :token},
                        beforeSend: function(){
                            $('body').prepend('<div id="loader"></div>');
                        },
                        success: function(result) {
                            if( $('.basic_view').length > 0 ) {
                                $("tr.basic_view").load(url_load + ' tr.basic_view>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                                });
                            } else {
                                $("tr#empl_"+id).load(url_load + " tr#empl_"+id+'>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                                });
                            }
                        }
                    });
                }
            });
        }

        function store_request () {
            $('.add_plan').on('click', function(e) {
                if (! confirm("Sigurno želiš dodati zahtjev?")) {
                    return false;
                } else {
                    e.preventDefault();
                    id = $( this ).attr('id');
                    url_store = $( this ).attr('href');
                    url_load = location.href;
                    token = $( this ).attr('data-token');

                    $.ajaxSetup({
                        headers: {
                            '_token': token
                        }
                    });
                    $.ajax({
                        url: url_store,
                        type: 'GET',
                        data: { _token :token },
                        beforeSend: function(){
                            $('body').prepend('<div id="loader"></div>');
                        },
                        success: function(result) {
                            if( $('.basic_view').length > 0 ) {
                                $("tr.basic_view").load(url_load + ' tr.basic_view>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                                });
                            } else {
                                $('#loader').remove();
                                
                                $("tr#empl_"+id).load(url_load + " tr#empl_"+id+'>td',function(){
                                    $('#loader').remove();
                                    delete_request ();
                                    store_request();
                            
                                });
                            }
                        }
                    });
                }
            });
        }
    }
});