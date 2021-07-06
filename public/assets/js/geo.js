'use strict';
function split( val ) {
    return val.split( /,\s*/ );
}
function extractLast( term ) {
    return split( term ).pop();
}
function sendGeoUrl($input) {
    let array = [];
    let requestUrl = 'https://api-adresse.data.gouv.fr/search/?q=8'+encodeURIComponent($input.val());
    return $.ajax(requestUrl, {
        success: function(data){
            $.each(data.features, function(key, val){
                array.push(val.properties.label);
            });
        }
    });
}
function checkStep() {
    $('.inputValited').each(function(k,v){
        $(this).attr({'name':'hiking[wayPoints]['+k+']'});
    });
}
$(document.body).on('keyup','#geoInput',function(){
    sendGeoUrl($(this)).done(function(data){
        $('#geoContent').html('');
        $.each(data.features, function(key,val){
            $('<button/>').attr({'type':'button', 'data-x':val.properties.x, 'data-y':val.properties.y})
            .text(val.properties.label).appendTo($('#geoContent')).on('click', function(){
                if ($(this).hasClass('valited')) { console.log('has');
                    $(this).remove();
                } else { console.log('else');
                    let p = $(this).addClass('valited').css('background-color', 'rgb(140, 217, 140)').appendTo($('#geoValid'));
                    $('<input/>').addClass('inputValited').attr({'hidden':'hidden'}).val(val.properties.x+';'+val.properties.y).appendTo(p);    
                }
                checkStep();     
            });
        });
    });
});

 
