$(function(){
    var split = window.location.href.split('/');
    var uri_seg = split[split.length-1];
    $('#side-menu').find('a').each(function(i, li){
        
        if($(li).attr('href') == '/'+uri_seg){
            $(li).addClass('active');
            var flag = false;
            $(li).parents().each(function(j, li2){
                if(flag)
                    $(li2).children().addClass('active');
                flag = true;
            });
        }
    });
});
