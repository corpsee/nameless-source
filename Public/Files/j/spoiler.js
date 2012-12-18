$(document).ready (function(){
	$('div.spoiler_content').hide();
	$('div.spoiler a').click(function() {
		$(this).parent().children('.spoiler_content').slideToggle('slow');
   	});
});