$(document).ready (function()
{
	$('.tool-left a').each(function(indx, element)
	{
		$(element).click(function()
		{
			/*$(element).parents('tr').after('<tr><td>1111</td><td>2222</td></tr>');*/

			var data = {};
			data['ajax'] = $(element).attr('id');

			$.ajax(
			{
				type: 'POST',
				url: '',
				dataType: 'json',
				data: data,
				success: function(msg)
				{
					$(element).parents('tr').after('<tr><td>1111</td><td>2222</td></tr>');
				}
			});
		});
	});
});