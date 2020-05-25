 if( $('.dataArr').text()) {
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
        var resources = JSON.parse( $('.dataArrResource').text());

        var drop_el;
        var drop_el_title;
        var hover_title_el;
      
        var resourceTitle = resources.map(function(resource) { return resource.title });

    //  console.log(resources); 
      

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
                resources: resources,
                firstDay:1,
                selectable: true,
                selectHelper: true,
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
                           
                            if(data == "") {
                                alert( "Zaposlenik je već zadužen na projektu, ne može se spremiti na cijeli projekt. Pokušaj spremiti na određeni dan");
                            }
                            if(data == 1) {
                                alert ("Zaposlenik je već zadužen na projektu za taj dan");
                            }
                        }, 
                        error: function(xhr,textStatus,thrownError) {
                            console.log("eventMouseLeave store error " + xhr + "\n" + textStatus + "\n" + thrownError);  
                        }
                    });
                    /*
                        var url_brisi = 'brisi/'+ project_id; 
                        $.ajax({ 
                            type: 'GET',
                            url:  url_brisi, 
                            dataType: "text",
                            success: function() {
                                $.getScript( "/js/open_modal.js");
                            }, 
                            error: function(xhr,textStatus,thrownError) {
                                console.log("eventMouseLeave brisi error " + xhr + "\n" + textStatus + "\n" + thrownError);  
                            }
                        });
                    */
                    url_update = location.origin + '/dashboard/?date=' + date;            
                    $.ajax({
                        type: 'GET',
                        url: url_update,
                        dataType: 'text',
                        data: {
                            '_token':  $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#external-events').load( url_update + ' #external-events .resource');
                              //  location.reload();
                            
                            $(".fc-content").hover(function(e) {
                       //         console.log($(e.currentTarget).data("date")); 
                            });
                            $.each($('span.fc-title'), function( ) {
                                if($.trim($(this).text()) == $.trim(drop_el_title) ) {
                                    $(this).parent().parent().hide();
                                } 
                            });
                            
                            $.getScript( "/js/open_modal.js");
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
            eventDrop: function(info ) {
                if (!confirm("Are you sure about this change?")) {
                    info.revert();
                }
                project_id = info.event.id;
                date =  info.event.start.getFullYear() + '-' +  (info.event.start.getMonth() +1) + '-' +  info.event.start.getDate();
                if(project_id != null) {
                    url_project_update = location.origin + '/url_project_update/' + project_id + '/' + date;
                  
                    $.ajax({ 
                        type: 'GET',
                        url:  url_project_update, 
                        dataType: "text",
                        success: function(data) {
                            
                        }, 
                        error: function(xhr,textStatus,thrownError) {
                            console.log("eventMouseLeave brisi error " + xhr + "\n" + textStatus + "\n" + thrownError);  
                        }
                    });
                }               
            },
            dateClick: function(info) {
                date = info.dateStr
                url_update = location.origin + '/dashboard/?date=' + date;
             //   window.history.replaceState({}, document.title, url_update);  
                $.ajax({
                    type: 'GET',
                    url: url_update,
                    dataType: 'text',
                    data: {
                        'date':date,
                        '_token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function( dateClickInfo ) {
                        $('#external-events').load( url_update + ' #external-events .resource');                  
                        $('aside .list').load( url_update + ' aside .list .projects_list');
                    },
                    error: function(xhr,textStatus,thrownError) {
                        console.log("dateClick error"); 
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                       
                    }
                });
            },
            eventMouseEnter: function( mouseEnterInfo ) {
                hover_title_el = mouseEnterInfo.event.title;
                var resource_text = '';
                $.each(JSON.parse(mouseEnterInfo.event.extendedProps.resourceIds), function( index, value ) {
                    resource_text += '<p>' + value + '</p>';
                });
     
                tooltip = '<div class="tooltiptopicevent" style="top:' + (mouseEnterInfo.jsEvent.y + 10) + 'px;left:' + ( mouseEnterInfo.jsEvent.x + 20) + 'px;">' + hover_title_el + '<br>' + resource_text + '</div>';
            
                $('body').append(tooltip);
                $(this).mouseover(function () {
                    $(this).css('z-index', 10000);
                    $('.tooltiptopicevent').fadeIn('500');
                    $('.tooltiptopicevent').fadeTo('10', 1.9);
                }).mousemove(function () {
                    $('.tooltiptopicevent').css('top',);
                    $('.tooltiptopicevent').css('left', mouseEnterInfo.jsEvent.y + 20);
                });
            },
            eventMouseLeave: function (mouseLeaveInfo ) {
                $(this).css('z-index', 8);
    
                $('.tooltiptopicevent').remove();
    
            },
            eventRender: function(eventObj) {  // ne radi
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
                            '_token':$('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if(response) {
                                $('#external-events').load( url + ' #external-events .resource');
                                $('a.fc-day-grid-event').attr('rel','modal:open');
                            
                            }
                           
                        },
                        error: function(xhr,textStatus,thrownError) {
                            alert(xhr + "\n" + textStatus + "\n" + thrownError);
                            console.log("eventRender error"); 
                        }
                    });                    
                }
            }
        });
        calendar.render();
        $('.fc-scroller.fc-day-grid-container').css('height','auto');
        $('.fc-scroller.fc-day-grid-container').css('overflow','hidden');
    });
 }
 console.log("fullcalendar");