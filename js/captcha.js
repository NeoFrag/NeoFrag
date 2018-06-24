var onloadCallback = function(){
	$(function(){
		$('.g-recaptcha').each(function(index, captcha){
			var data = {
				sitekey: "<?php echo $this->config->nf_captcha_public_key ?>"
			};

			$.each(['theme', 'size'], function(_, key){
				if ($(captcha).data(key)){
					data[key] = $(captcha).data(key);
				}
			});

			grecaptcha.render(captcha, data);
		});
	});
};

$(function() {
	$.getScript('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=<?php echo $this->config->lang ?>&_=');
	$('body').on('nf.load', function(){
		onloadCallback();
	});
});
