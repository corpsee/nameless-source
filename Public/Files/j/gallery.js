$(document).ready (function(){

    $().piroBox
    ({
		my_speed: 400,
		bg_alpha: 0.5,
		slideShow : false,
		close_all : '.piro_close,'
    });

    file_path = $('#file_path').text();

    $('.gr-col').mouseover (function()
    {
        var ss = file_path + 'pictures/xmin/' + $(this).attr('id') + '-min.jpg';
        $(this).attr('src', ss);
    });

    $('.gr-col').mouseout (function()
    {
        var ss = file_path + 'pictures/xgray/' + $(this).attr('id') + '-gray.jpg';
        $(this).attr('src', ss);
    });
});