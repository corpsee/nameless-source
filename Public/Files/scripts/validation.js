$(document).ready (function()
{
	$('.validation').each(function(indx, element)
	{
  		if ($(element).val() != '')
		{
			$(element).addClass('success');
		}
	});

	var elements = $('.validation').length;
	var has = $('.success').length;

	if (has == elements)
	{
		$('.submit').prop('disabled', false);
	}
	else
	{
		$('.submit').prop('disabled', true);
	}

	$('.validation').each(function(indx, element)
	{
		$(element).change(function()
		{
			var name = $(element).attr('name');
			var data = {};
			data[name] = $(this).val();

			$.ajax(
			{
				type: 'POST',
				url: '',
				dataType: 'json',
				data: data,
				beforeSend: function ()
				{
					$(element).removeClass('success error');
					$(element).parent('.control').prevAll('label').removeClass('success error');
					$(element).nextAll('.msg').empty();
				},
				success: function (msg)
				{
					$(element).parent('.control').prevAll('label').addClass(msg['status']);
					$(element).addClass(msg['status']);

					var message = msg['msg'];

					for (var el in message)
					{
						$(element).nextAll('.msg').append('<p class="help error">' + message[el] + '</p>');
					}
				}
			});

			setTimeout(function()
			{
				has = $('.success.validation').length;

				if (has == elements)
				{
					$('.submit').prop('disabled', false);
				}
				else
				{
					$('.submit').prop('disabled', true);
				}
  			}, 500);
		});
	});
});