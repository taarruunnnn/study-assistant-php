
require('./bootstrap');

require('malihu-custom-scrollbar-plugin');

require('bootstrap-datepicker/dist/js/bootstrap-datepicker');

require('fullcalendar');

global.moment = require('moment');

global.Bloodhound = require('corejs-typeahead/dist/typeahead.bundle');

require('chart.js');


$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/users/modules?q=%QUERY%',
            wildcard: '%QUERY%'
        }
    });

    $('#typeahead-modules .typeahead').typeahead({
        minLength: 1,
        }, {
        name: 'modules',
        source: bloodhound,
        display: function(data){
            return data;
        }
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


