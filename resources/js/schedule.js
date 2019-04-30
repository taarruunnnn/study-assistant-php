require('./app');

global.Bloodhound = require('corejs-typeahead/dist/typeahead.bundle');

global.Timer = require('easytimer.js');

$(document).ready(function () {

    $(".report-card").mCustomScrollbar({
        theme: "minimal-dark",
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

});


