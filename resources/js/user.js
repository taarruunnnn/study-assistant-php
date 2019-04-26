require('./app');

global.Bloodhound = require('corejs-typeahead/dist/typeahead.bundle');

$(document).ready(function () {

   $('.datepicker-years').datepicker({
        format: 'yyyy',
        viewMode: 'years',
        minViewMode: 'years',
        maxViewMode: 'years',
        autoClose: true
    });

    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/users/universities?q=%QUERY%',
            wildcard: '%QUERY%'
        }
    });

    $('#typeahead-university .typeahead').typeahead({
        minLength: 2,
        }, {
        name: 'universities',
        source: bloodhound,
        display: function(data){
            return data;
        }
    });
});