jQuery(document).ready(function($) {

	$('input').focusin(function() {
		input = $(this);
		input.data('place-holder-text', input.attr('placeholder'))
		input.attr('placeholder', '');
	});

	$('input').focusout(function() {
		input = $(this);
		input.attr('placeholder', input.data('place-holder-text'));
	});

});