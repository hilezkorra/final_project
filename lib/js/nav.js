$( document ).ready(function() {
    'use strict';
    function addSelToSrc(src){
        var fileExt = src.substr(src.length - 4);
        if(src.substr(src.length-8, 4) !== "_sel"){
            var src = (src.substr(0, src.length - 4)) + "_sel" + fileExt;
        }
        return src;
    }
    function removeSel(src){
        var fileExt = src.substr(src.length - 4);
        if(src.substr(src.length-8, 4) === "_sel"){
            var src = (src.substr(0, src.length - 8)) + fileExt;
        }
        return src;
    }

    $('.navbutton').mouseenter(function(e){
        if($(this).attr('class') !== 'navbutton active'){
                $(this).fadeOut(60,function(){
                    $(this).attr('src', addSelToSrc($(this).attr('src')));
                    $(this).fadeIn(60);
                });    
        }
    }).mouseleave(function(e){
        if($(this).attr('class') !== 'navbutton active'){
            $(this).fadeOut(60,function(){
                $(this).attr('src', removeSel($(this).attr('src')));
                $(this).fadeIn(60);
            });
        }
    });

});