$(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    

    
    
    $('#calendar-holder').fullCalendar({
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month, basicWeek, basicDay,'
        },
        lazyFetching: true,
        firstDay:1,
        height: 650,
        aspectRatio: 1,
        timeFormat: {
            // for agendaWeek and agendaDay
            agenda: 'h:mmt',    // 5:00 - 6:30

            // for all other views
            '': 'h:mmt'         // 7p
        },
        
        eventRender: function(event, element) {
            //alert('event-render');
            element.attr("data-toggle","modal");
            element.attr("data-target","#calendar-modal-event");

            //element.addClass('btn');
            //element.addClass('btn-info');
            //element.colorbox({opacity: 0.5, scrolling:false, width: 350, height: 580});
        },        
        eventSources: [
            {
                url: Routing.generate('fullcalendar_loader'),
                type: 'POST',
                // A way to add custom filters to your event listeners
                data: {
                },
                error: function() {
                   //alert('There was an error while fetching Google Calendar!');
                }
            }
        ]
    });
});
