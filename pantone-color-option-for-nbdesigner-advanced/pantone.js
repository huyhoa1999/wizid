jQuery(document).ready(function ($) {
	var currentMode = $('#color_mode').val();
	if (currentMode == 'sc'){
		$('.single_opt').addClass('active');
	}

	$('#color_mode').on('change', function(){
		var mode = $(this).val();
		switch (mode){
			case 'sc':
				$('.multi_opt').removeClass('active');
				$('.single_opt').addClass('active');
				$('.multi-color-trigger').removeAttr('style');
				break;
			case 'mc':
				$('.single_opt').removeClass('active');
				$('.multi_opt').addClass('active');
				$('.color-picker-trigger.selected').removeAttr('style');
				break;
		}
	});
	$('#multi_opt').on('change', function(){
		var countColor = $(this).val();
		switch (countColor) {
			case '3':
				$('.stripe-3').addClass('active');
				$('.stripe-4').removeClass('active');
				$('.stripe-5').removeClass('active');
				break;
			case '4':
				$('.stripe-3').addClass('active');
				$('.stripe-4').addClass('active');
				$('.stripe-5').removeClass('active');
				break;
			case '5':
				$('.stripe-3').addClass('active');
				$('.stripe-4').addClass('active');
				$('.stripe-5').addClass('active');
				break;
			default:
				$('.stripe-3').removeClass('active');
				$('.stripe-4').removeClass('active');
				$('.stripe-5').removeClass('active');
		}
	});
	$('.overlay-popup').on('click',function(){
		$('.nbd-popup').removeClass('nb-show');
	});
});