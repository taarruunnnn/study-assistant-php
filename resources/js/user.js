require('./bootstrap');

require('malihu-custom-scrollbar-plugin');

require('@fortawesome/fontawesome-free/js/all');

global.Bloodhound = require('corejs-typeahead/dist/typeahead.bundle');

require('bootstrap-datepicker/dist/js/bootstrap-datepicker');


$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#sidebarCollapse').on('click', function () {
           $('#sidebar, #content').toggleClass('active');
           $('.collapse.in').toggleClass('in');
           $('a[aria-expanded=true]').attr('aria-expanded', 'false');
   });

   $('.datepicker').datepicker({
        format: 'yyyy',
        viewMode: 'years',
        minViewMode: 'years',
        maxViewMode: 'years'
    });


    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/users/universities?q=%QUERY%',
            wildcard: '%QUERY%'
        }
    });

    $('#typeahead-university .typeahead').typeahead(null, {
        name: 'universities',
        source: bloodhound,
        display: function(data){
            return data
        }
    });

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });

});