$(document).ready (function()
{
	$('#cropbox').Jcrop(
	{
		aspectRatio: 20/9,
		onSelect: updateCoords
	});

	$('#cropform').submit(function()
	{
		return checkCoords();
	});
});

function updateCoords(c)
{
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

function checkCoords()
{
	if (parseInt($('#w').val())) return true;
	alert('Выделите область прежде чем отправлять на сервер');
	return false;
}

