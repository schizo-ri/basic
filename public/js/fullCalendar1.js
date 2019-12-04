 if( $('.dataArr').text()) {
    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;     
        var calendarEl = document.getElementById('calendar');      
        // initialize the external events
       
        var events = JSON.parse( $('.dataArr').text());
       
        // -----------------------------------------------------------------

   
        // initialize the calendar
        // -----------------------------------------------------------------

        var calendar = new Calendar(calendarEl, {
                events: events,
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
            droppable: false // this allows things to be dropped onto the calendar 
           
        });
        calendar.render();     
        $('.fc-scroller.fc-day-grid-container').css('height','auto');
        $('.fc-scroller.fc-day-grid-container').css('overflow','hidden');
    });
 }