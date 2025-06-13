jQuery(document).ready(function ($) {
	
	$('.color-field').wpColorPicker();

	$('.upload-image-button').on('click', function (e) {
		e.preventDefault();
		const target = $(this).data('target');
		const input = $('input[name="' + target + '"]');
		const preview = input.siblings('.image-preview');
		const removeButton = $('button.remove-image-button[data-target="' + target + '"]');

		const frame = wp.media({
			title: 'Select or Upload Image',
			button: { text: 'Use this image' },
			library: { type: 'image' },
			multiple: false
		});

		frame.on('select', function () {
			const attachment = frame.state().get('selection').first().toJSON();
			input.val(attachment.id);
			preview.html('<img src="' + attachment.sizes.medium.url + '" style="width:100px; height:100px; object-fit:cover;" />');
			removeButton.show();
		});

		frame.open();
	});

	$('.remove-image-button').on('click', function (e) {
		e.preventDefault();
		const target = $(this).data('target');
		const input = $('input[name="' + target + '"]');
		const preview = input.siblings('.image-preview');

		input.val('');
		preview.html('');
		$(this).hide();
	});
	
});
