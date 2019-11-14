    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendarInteraction.Draggable;

        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');
        var checkbox = document.getElementById('drop-remove');
        let project_id = null;
        // initialize the external events
        var url_basic = location.origin + '/dashboard';
        var url_update;
        var employee_id = null;
        var date = null;
        var events = JSON.parse( $('.dataArr').text());
        var drop_el;
        var drop_el_title;
        // -----------------------------------------------------------------

        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText
                };
            }
        });

        // initialize the calendar
        // -----------------------------------------------------------------

        var calendar = new Calendar(calendarEl, {
                events: events,
                firstDay:1,
                selectable: true,
                plugins: [ 'interaction', 'dayGrid','list' ],
                locale: 'hr',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
            },
            editable: false,
            droppable: true, // this allows things to be dropped onto the calendar

            drop: function(info) {
               /* if (checkbox.checked) {
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                }*/
                drop_el = info.draggedEl;
                drop_el_title = $(info.draggedEl).text();
                employee_id = $(info.draggedEl).attr('id');
                date = info.date.getFullYear() + '-' + (info.date.getMonth() + 1) + '-' + info.date.getDate();
            },
            eventMouseLeave: function(info) {
               project_id = info.event.id;
               if(employee_id && date && project_id ) {
                    var all_days = prompt("Primjeniti na sve dane trajanje projekta? (u suprotnom ostavi prazno)", "da");
                    if (all_days == "da") {
                        all_days = 1; 
                    } else {
                        all_days = 0;
                    }
                                     
                    var url_store = 'save/' + employee_id  + '/' + date + '/' + project_id + '/' + all_days; 
                    $.ajax({ 
                        url:  url_store, 
                        dataType: "text",
                        success: function(data) {
                         
                        }, 
                        error: function(xhr,textStatus,thrownError) {
                            console.log("eventMouseLeave store error " + xhr + "\n" + textStatus + "\n" + thrownError);  
                        }
                    });
                   
                    var url_brisi = 'brisi/'+ project_id; 
                    $.ajax({ 
                        type: 'GET',
                        url:  url_brisi, 
                        dataType: "text",
                        success: function(data) {
                            console.log(data);
                        }, 
                        error: function(xhr,textStatus,thrownError) {
                            console.log("eventMouseLeave brisi error " + xhr + "\n" + textStatus + "\n" + thrownError);  
                        }
                    });
                  
                    url_update = location.origin + '/dashboard/?date=' + date;
                //    window.history.replaceState({url_basic}, document.title, url_basic + '/?date=' + date );
 
                    $.ajax({
                        type: 'GET',
                        url: url_update,
                        dataType: 'text',
                        data: {
                            '_token':  $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#external-events').load( url_update + ' #external-events .resource');
                              location.reload();
                            $(".fc-content").hover(function(e) {
                                console.log($(e.currentTarget).data("date")); 
                            });
                            $.each($('span.fc-title'), function( ) {
                                if($.trim($(this).text()) == $.trim(drop_el_title) ) {
                                    $(this).parent().parent().hide();
                                } 
                            });
                        },
                        error: function(xhr,textStatus,thrownError) {
                            console.log("eventMouseLeave update error " + xhr + "\n" + textStatus + "\n" + thrownError); 
                           
                        }
                    }); 
                } else {
                    if(employee_id && date) {
                        alert("Nisu dostupni svi podaci za snimanje, niste djelatnika ispustili u projekt");
                        $.each($('span.fc-title'), function( ) {
                            if($.trim($(this).text()) == $.trim(drop_el_title) ) {
                                $(this).parent().parent().hide();
                            } 
                        });
                    } 
                }
               project_id = null;
               employee_id = null;
               date = null;
            },
            dateClick: function(info) {
                date = info.dateStr
                url_update = location.origin + '/dashboard/?date=' + date;
             //   window.history.replaceState({url_basic}, document.title, url_basic + '/?date=' + date );
           
                $.ajax({
                    type: 'GET',
                    url: url_update,
                    dataType: 'text',
                    data: {
                        '_token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#external-events').load( url_update + ' #external-events .resource');
                        $('aside .projects_list').load( url_update + ' aside .projects_list>div');

                        $.getScript( location.origin + "/restfulizer.js");
                       
                    },
                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                        console.log("dateClick error"); 
                    }
                });
            },
            eventRender: function() {  // ne radi
                var d = new Date();
                var today = d.getFullYear() + '-' + (d.getMonth() +1) + '-' + d.getDate();

                var url = location.origin + '/dashboard/?date=' + today;
           //     window.history.replaceState({}, document.title, url);
                if( window.location.href == url ) {
                   //
                } else {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        dataType: 'text',
                        data: {
                            '_token':  $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#external-events').load( url + ' #external-events .resource');
                           
                        },
                        error: function(xhr,textStatus,thrownError) {
                            alert(xhr + "\n" + textStatus + "\n" + thrownError);
                            console.log("eventRender error"); 
                        
                        }
                    });

                   // window.history.replaceState({}, document.title, url);
                  //  window.location.replace(url);
                    
                }
            }
        });
       
        calendar.render();
        
    });