
require('./bootstrap');

require('malihu-custom-scrollbar-plugin');

require('@fortawesome/fontawesome-free/js/all');

require('bootstrap-datepicker/dist/js/bootstrap-datepicker');


$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('.datepicker').datepicker({
        maxViewMode: 'years',
        format: "yyyy-mm-dd"
    });

    $('#sidebarCollapse').on('click', function () {
           $('#sidebar, #content').toggleClass('active');
           $('.collapse.in').toggleClass('in');
           $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });

});