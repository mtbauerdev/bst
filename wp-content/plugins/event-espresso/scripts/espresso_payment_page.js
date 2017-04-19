jQuery(document).ready(function($) {

	var preventLeavePage = true;
	var bypass_payment_page = $('#bypass_payment_page').val();
	if ( $('#allow_leave_page').val() == 'true' ) {
		preventLeavePage = false;
	}
	if( bypass_payment_page == 'true' ) {
		var bpp = $('#bypass_payment_page-dv').html();
		$('#espresso-payment_page-dv').html( bpp );
		$('.payment_option_title').hide();
		$('.payment-option-dv').hide();
		$('.payment-option-dv a').hide();
		$('#bypass_payment_page_gateway_form').submit();
	}
	
	$('.hide-if-js').hide();
	$('.payment_container').toggleClass('payment-option-closed'); 
	$('.payment-option-dv').toggleClass('payment-option-closed'); 

	$('.allow-leave-page').on( 'click', function() {
		preventLeavePage = false;
	});
	
	$('.payment-option-dv .event_espresso_form_wrapper').on( 'change', 'input', function() {
		preventLeavePage = true;
	});
	
	window.onbeforeunload = function() {
		if ( preventLeavePage && bypass_payment_page != 'true' ) {
	  	  return 'Warning!!! Using the back button will overwrite your existing registration.';
		}
	}	
});	
