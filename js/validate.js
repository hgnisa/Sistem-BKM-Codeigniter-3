function formguard(formId, guard){
	if(guard){
		var _guard = guard.split('$');
		var kguard = _guard[0];
		var vguard = _guard[1];
		var eguard = _guard[2];
	} else if( typeof _gf !== "undefined"){
		if( typeof _gf[formId] !== "undefined"){
			var _guard = _gf[formId].split('$');
			var kguard = _guard[0];
			var vguard = _guard[1];
			var eguard = _guard[2];
		}
	}

	if(kguard && $(formId).find('input[name=_gkey]').length == 0){
		$(formId).append('<input type="hidden" name="'+kguard+'" value="'+vguard+'"/>');
		$(formId).find('input[name='+kguard+']').hide();

		$(formId).append('<input type="hidden" name="_gkey" value="'+eguard+'"/>');
		$(formId).find('input[name=_gkey]').hide();
	}

	if($(formId).find('input[name=ishuman]').length == 0){
		$(formId).prepend('<input type="hidden" name="ishuman" value=""/>');
	}
}

function validate(result, formId, redirectLoc, guard) {
	if(guard){
		var _guard = guard.split('$');
		var kguard = _guard[0];
		var vguard = _guard[1];
		var eguard = _guard[2];
	}  else if( typeof _gf !== "undefined"){
		if( typeof _gf[formId] !== "undefined"){
			var _guard = _gf[formId].split('$');
			var kguard = _guard[0];
			var vguard = _guard[1];
			var eguard = _guard[2];
		}
	}

	var url=$(formId).attr('action');
	if(url){
		if(url.slice(-3)=='php'){
			url=url+"?js=on";
		}else{
			url=url+"&js=on";
		}
	}

	$(formId).validate({
		submitHandler: function(form) {
			formguard(formId, guard);

			var options = {
				type: $(this).attr('method'),
				url:url,
				data:$(this).serialize(),
				beforeSubmit: function(){
					btn = $(formId).find('input[type=submit], button[type=submit]');
					btn.attr('disabled','disabled');
					btn.addClass('btn-disabled');
					if($('.preloadingbutton').length>0){
						$('.preloadingbutton').show();
					}
				},
				afterSubmit: function(){
					if(kguard){
						$(formId).find('input[name='+kguard+']').remove();
						$(formId).find('input[name=_gkey]').remove();
					}
				},
				success: function (data) {
					btn = $(formId).find('input[type=submit], button[type=submit]');
					btn.removeAttr('disabled');
					btn.removeClass('btn-disabled');
					if($('.preloadingbutton').length>0){
						$('.preloadingbutton').hide();
					}

					data=$.trim(data);
					if (data=='ok') {
						 window.location = redirectLoc;
					}
					else if(data.slice(0,7)=='<script' || data.slice(-1,9)=='</script>'){
						$(result).html(data);
					}else{

						if(data){
							$(result).show();
							$(result).html(data);
							$(formId).click(function(form) {
								$(result).fadeOut("slow");
							});
						}
					}
				},

			};

			if(kguard){
				var s = Math.random().toString(36).substring(2); /* add prefix */
				var d = new Date(); /* avoid browser cache */
				var tmpImg = new Image() ;
				tmpImg.src = '/www95/g/'+s+'$'+eguard+'$'+d.getTime()+'.gif';
				tmpImg.onload = function() {
					jQuery(form).ajaxSubmit(options);
				}
				tmpImg.onerror = function() {
					alert('Network connection lost. Try again!');
				}
			} else {
				jQuery(form).ajaxSubmit(options);
			}
		}
	});
}

function validateCaptcha(result,formId,redirectLoc) {
	var url=$(formId).attr('action');
	if(url){
		if(url.slice(-3)=='php'){
			url=url+"?js=on";
		}else{
			url=url+"&js=on";
		}
	}
	$(formId).validate({
	submitHandler: function(form) {
			var options = {
				type: $(this).attr('method'),
				url:url,
				data:$(this).serialize(),
				success: function (data) {
					if (data=='ok') {
						 window.location = redirectLoc;
					}else{
						Recaptcha.reload();
						$(result).show();
						$(result).html(data);
						$(formId).click(function(form) {
						  $(result).fadeOut("slow");
						});
					}
				}
			};
			jQuery(form).ajaxSubmit(options);
			return false;
		}
	});
}


function confirmMe(MSG){
	if(!confirm(MSG)) return false;
	return true;
}

jQuery(document).ready(function($){
	$('html').click(function(){
		$('#result').slideUp(500);
	});

	$(document).on('click', '.delete', function(event) {
		con=$(this);
		if(con.attr('title')){
			var orititle=con.attr('title');
		}else{
			var orititle=con.attr('data-original-title');
		}
		event.preventDefault();

		var delete_word = $('body .delwordcms').text();

		if(confirmMe( delete_word + ' ' + orititle.replace('Delete: ', '') + '?')){
			$.get($(this).attr('href')+'&js=on',function(data){
				if(data=='ok'){
					con.closest('tr').slideUp(100);
					con.closest(".design-main-box").slideUp(100);
				}else{
					$('#result').html(data).slideUp(100).slideDown(300);
				}
				console.log("tidak tau");
			});
		}
	});

	$(document).on('click', '.process-without-form', function(event) {
		con=$(this);
		if(con.attr('title')){
			var orititle=con.attr('title');
		}else{
			var orititle=con.attr('data-original-title');
		}
		event.preventDefault();

		var delete_word = "Apakah anda ingin mengaktifkan/non-aktifkan kategori:";

		if(confirmMe( delete_word + ' ' + orititle.replace('Delete: ', '') + '?')){
			$.get($(this).attr('href')+'&js=on',function(data){
				if(data=='ok'){
					window.location.href = 'productcat.php';
				}else{
					$('#result').html(data).slideUp(100).slideDown(300);
				}
				console.log("tidak tau");
			});
		}
	});

	$('.noscript').hide();
});