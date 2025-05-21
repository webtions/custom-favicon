(function($) {
	$(function() {
		$('.upload').each(function() {
			var container = $(this);
			var button = container.find('.button-upload');
			var input = container.find('.text-upload');
			var preview = container.find('.preview-upload');

			button.on('click', function(e) {
				e.preventDefault();

				var frame = wp.media({
					title: 'Select or Upload Image',
					button: {
						text: 'Use this image'
					},
					multiple: false
				});

				frame.on('select', function() {
					var attachment = frame.state().get('selection').first().toJSON();
					input.val(attachment.url).trigger('change');
					preview.attr('src', attachment.url).show();
				});

				frame.open();
			});

			input.on('change', function() {
				preview.attr('src', input.val());
			});
		});
	});
})(jQuery);
