
require('./bootstrap');

require('malihu-custom-scrollbar-plugin');

require('@fortawesome/fontawesome-free/js/all');

require('bootstrap-datepicker/dist/js/bootstrap-datepicker');

global.moment = require('moment');

require('fullcalendar');


$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });


    $('#sidebarCollapse').on('click', function () {
           $('#sidebar, #content').toggleClass('active');
           $('.collapse.in').toggleClass('in');
           $('a[aria-expanded=true]').attr('aria-expanded', 'false');
   });

   $("#messageAlert").fadeTo(2000, 500).slideUp(500, function(){
        $("#messageAlert").slideUp(500);
    });

});


