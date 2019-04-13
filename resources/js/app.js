
require('./bootstrap');

require('malihu-custom-scrollbar-plugin');

require('jquery-mousewheel');

require('bootstrap-datepicker/dist/js/bootstrap-datepicker');

global.Timer = require('easytimer.js');

global.moment = require('moment');

require('chart.js');


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
           $('#navigation').toggleClass('navbar-pushed');
           $('.collapse.in').toggleClass('in');
           $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $(".alert-success").fadeTo(5000, 500).slideUp(500, function(){
        $(".alert-success").slideUp(500);
    });

});