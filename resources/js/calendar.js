import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import bootstrapPlugin from '@fullcalendar/bootstrap';

$(document).ready(function(){

	var mainCalendar = mainCalendarInit();
	
	mainCalendar.render();
	
	function mainCalendarInit()
	{
		var calendarEl = document.getElementById('calendar');
    	var calendar = new Calendar(calendarEl, {
			plugins: [ dayGridPlugin, bootstrapPlugin ],
			themeSystem: 'bootstrap',
			defaultView: 'dayGridMonth',
			events: route('schedules.sessions').url(),
			firstDay: 1,
			showNonCurrentDates: false,
			fixedWeekCount: false,
			height: 'auto',
			aspectRatio: 1.5,
			eventColor: '#2196f3',
			eventTextColor: '#FFF',
			eventOrder: "id",
			customButtons: {
				modify : {
					text: 'Modify Schedule',
					click: function() {
						$('#modifySchedule').modal();
					}
				},
				eventAdd : {
					text: 'Add Event',
					click: function() {
						$('#addEvent').modal();
					}
				}
			},
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'modify,eventAdd'
			},
			eventClick: function(info) {
				if(info.event.extendedProps.description == 'session')
				{
					sessionDetailsInit(info);
				}
				else if(info.event.extendedProps.description == 'event')
				{
					$("#addEvent").modal();
					var id = info.event.id;
					var start = info.event.start;
					var title = info.event.title;
					eventDetails(id, start, title);
				}
			}
		});

		return calendar;
	}
	
	  
	function eventDetails(id, start, title)
	{
		var startDate = new Date(start);
		var eventId = id;
		$('#eventdate').datepicker('update', startDate);
		$('#eventid').val(eventId);
		$('#description').val(title);
		$('<input>').attr({
			type: 'hidden',
			id: 'eventId',
			name: 'id',
			value: eventId
		}).appendTo('#eventForm');
		$('#eventForm').attr("action", route('events.update'));
		$('#eventDelete').css('display','block');
	}

	function sessionDetailsInit(info)
	{
		$('#session-name').text(info.event.title);
		$('#session-date').text(moment(info.event.start).format("MMMM Do YYYY"));
		$('#session-id').val(info.event.id)

		if(moment(info.event.start).isSame(new Date(), 'day')){
			$('#btn-study').show();
		}

		var status = info.event.extendedProps.status;
		$('#session-status').text(status);
		switch(status) {
			case "Incomplete":
				$('#session-status').css("background-color", "#6c757d")
				break;
			case "Completed":
				$('#session-status').css("background-color", "#28a745")
				break
			case "Failed":
				$('#session-status').css("background-color", "#dc3545")
				break
			default:
				$('#session-status').css("background-color", "#6c757d")
		}

		
		$('#session-date-new').datepicker({
			maxViewMode: 'years',
			format: "yyyy-mm-dd",
			autoclose: true,
			todayHighlight: true,
			weekStart: 1
		});

		if ((typeof window.scheduleStartDate !== 'undefined') && (typeof window.scheduleEndDate !== 'undefined')) {
			$('#session-date-new').datepicker('setStartDate', window.scheduleStartDate);
			$('#session-date-new').datepicker('setEndDate', window.scheduleEndDate);
		}
		

		$('#session-date-new').datepicker('setDate', new Date(info.event.start))

		$('#session-change-btn').click(function(){
			$('#session-change-form').show('slow');
		});

		$('#sessionDetails').on('hidden.bs.modal', function (e) {
			$("#session-change-form").hide();
		});

		$("#sessionDetails").modal();
	}
	
})