
require('./bootstrap');

require('malihu-custom-scrollbar-plugin');

require('@fortawesome/fontawesome-free/js/all');

$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#sidebarCollapse').on('click', function () {
           $('#sidebar, #content').toggleClass('active');
           $('.collapse.in').toggleClass('in');
           $('a[aria-expanded=true]').attr('aria-expanded', 'false');
   });

});