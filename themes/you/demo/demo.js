var isOpened;

$(document).ready(function() {

    isOpened = true;

    var photo = $('#photoBox img');
    photo.attr('src', 'http://www.grw.name/themes/you/demo/p-all.jpg');

    var preview = $('#colorsPreview');
    preview.css('opacity', '1');
    preview.hover(function() {
        $(this).animate({
            opacity : '1'
        });
    }, function() {
        $(this).animate({
            opacity : '1'
        });
    });
    
    /* Temp */
   /*
    var marginLeft, href;
        switch('blue') {
            case 'blue' :
                marginLeft = '0';
                href = '';
                break;
            case 'grey' :
                marginLeft = '-250px';
                href = 'css/colors/grey.css';
                break;
            case 'green' :
                marginLeft = '-500px';
                href = 'css/colors/green.css';
                break;
            case 'orange' :
                marginLeft = '-750px';
                href = 'css/colors/orange.css';
                break;
            case 'red' :
                marginLeft = '-1000px';
                href = 'css/colors/red.css';
                break;
        }

        $('link#color').attr('href', href);
        photo.css('margin-left', marginLeft);*/
    /* Temp */
    
    
    
    $('#colors a').click(function() {
        var marginLeft, href;
        switch($(this).attr('id')) {
            case 'blue' :
                marginLeft = '0';
                href = '';
                break;
            case 'grey' :
                marginLeft = '-250px';
                href = 'css/colors/grey.css';
                break;
            case 'green' :
                marginLeft = '-500px';
                href = 'css/colors/green.css';
                break;
            case 'orange' :
                marginLeft = '-750px';
                href = 'css/colors/orange.css';
                break;
            case 'red' :
                marginLeft = '-1000px';
                href = 'css/colors/red.css';
                break;
        }

        $('link#color').attr('href', href);
        photo.css('margin-left', marginLeft);

    });

    $('#bgs a').click(function() {
        var bg;
        switch($(this).attr('id')) {
            case 'bg1' :
                bg = 'images/bg/brick.png';
                break;
            case 'bg2' :
                bg = 'images/bg/emboss.jpg';
                break;
            case 'bg3' :
                bg = 'images/bg/large-leather.jpg';
                break;
            case 'bg4' :
                bg = 'images/bg/wood.jpg';
                break;
            case 'bg5' :
                bg = 'images/bg/lghtmesh.png';
                break;
        }
        
        
        if($(this).attr('id') == 'bg4') {
            $('#rootContainer').css({
                '-moz-box-shadow' : '0 3px 8px #888',
                '-webkit-box-shadow' : '0 3px 8px #888',
                'box-shadow' : '0 3px 8px #888'
            });
        } else {
            $('#rootContainer').css({
                '-moz-box-shadow' : '0 3px 8px #CCC',
                '-webkit-box-shadow' : '0 3px 8px #CCC',
                'box-shadow' : '0 3px 8px #CCC'
            });
        }
        $('body').css('background', 'url("' + bg + '")');
        
    });

    $('#panelButton').click(function() {
        if (isOpened) {
            preview.animate({
                top : '-50px'
            }, 400);
        } else {
            preview.animate({
                top : '0'
            }, 500, 'easeOutQuart');
        }
        
        isOpened = !isOpened;
    });

}); 