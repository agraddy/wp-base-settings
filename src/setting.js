(function() {
	var $;                 

	function formSubmit() {
		var $form = $(this);            
		var $button = $form.find('.button-primary');
		var $spinner = $form.find('.spinner');
		var data = $(this).serialize(); 

		$button.addClass('disabled');   
		$button.addClass('button-disabled');
		$button.addClass('button-primary-disabled');
		$spinner.addClass('is-active'); 

		$.ajax({       
			data: data,                     
			dataType: 'json',               
			method: $form.attr('method'),   
			url: $form.attr('action'),      
			success: responseSuccess.bind(null, $form),
			error: responseError.bind(null, $form) 
		});            
		return false;  
	}

	function responseError($form, jqXHR, textStatus, error) {
		var $button = $form.find('.button-primary');
		var $spinner = $form.find('.spinner');

		$button.removeClass('disabled');
		$button.removeClass('button-disabled');
		$button.removeClass('button-primary-disabled');
		$spinner.removeClass('is-active');

		var data = jqXHR.responseJSON;
		if(data && data.message) {
			$('#ajax_message').addClass('notice');
			$('#ajax_message').addClass('notice-error');
			$('#ajax_message').removeClass('notice-success');
			$('#ajax_message').html('<p>' + data.message + '</p>');
		} else {
			$('#ajax_message').addClass('notice');
			$('#ajax_message').addClass('notice-error');
			$('#ajax_message').removeClass('notice-success');
			$('#ajax_message').html('<p>There was a problem with the submission.</p>');
		}
		$('html, body').animate({
			scrollTop: 0
		}, 300);
	}

	function responseSuccess($form, data) {
		var $button = $form.find('.button-primary');
		var $spinner = $form.find('.spinner');

		$button.removeClass('disabled');
		$button.removeClass('button-disabled');
		$button.removeClass('button-primary-disabled');
		$spinner.removeClass('is-active');

		if(data.status && data.status == 'success' && data.message) {
			/*
			$('#ajax_message').addClass('notice');
			$('#ajax_message').removeClass('notice-error');
			$('#ajax_message').addClass('notice-success');
			$('#ajax_message').html('<p>' + data.message + '</p>');
			*/
			//window.location.reload(true);
			window.location.href = window.location.href;
		} else if(data.status && data.status == 'success') {
			/*
			$('#ajax_message').addClass('notice');
			$('#ajax_message').removeClass('notice-error');
			$('#ajax_message').addClass('notice-success');
			$('#ajax_message').html('<p>' + 'The data has been updated.' + '</p>');
			*/
			//window.location.reload(true);
			window.location.href = window.location.href;
		} else {
			if(data.message) {
				$('#ajax_message').addClass('notice');
				$('#ajax_message').removeClass('notice-error');
				$('#ajax_message').addClass('notice-success');
				$('#ajax_message').html('<p>' + data.message + '</p>');
			} else {
				$('#ajax_message').addClass('error');
				$('#ajax_message').removeClass('success');
				$('#ajax_message').html('<p>There was a problem with the submission.</p>');
			}

			$('html, body').animate({
				scrollTop: 0
			}, 300);
		}
	}

	function init() {
		$(document).on('submit', '.setting_page form', formSubmit);
	}

	if(typeof global == 'undefined') {
		global = window;
	}
	global.jQuery(document).ready(function(jquery) {
		$ = jquery;
		init();
	});
})();
