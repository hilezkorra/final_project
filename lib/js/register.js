$( document ).ready(function() {
    'use strict';
    var countries, options = '',options2 = "";
    $.ajax({url:"lib/data/countries.json", success: function(result){
        countries = result;
        countries.forEach(c => {
            options += `<option value=\"${c.name}\">${c.name}</option>`
        });
        $('#countries-selector').append(options);
    }});
        $('#countries-selector').change( function() {
            var country = $('#countries-selector').val();
            $.ajax({url:"lib/data/cities.json", success: function(result){
                var cities = result[country];
                options2 = '<option value=\"\">Pick a city</option>';
                if(typeof cities !== 'undefined'){    
                    cities.forEach(city => {
                        options2 += `<option value=\"${city}\">${city}</option>`;       
                    });
                }
                
                options2 += `<option value="Other">Other</option>`;
                $("#cities-selector").empty().append(options2);
            }});    
        });
    
});