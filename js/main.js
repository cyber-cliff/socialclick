$(document).ready(function(e) {
    

$('#need_login').click(function(e) {
		$('body').after(function() {
            $(this).prepend('<div id="overlay" style="height:' + $(document).height()+'px' + '"></div>');
			$(this).css({'overflow':'hidden'});
			showLoading();
        	$('#overlay,#signinbox').fadeIn('fast');
		});
		
        e.preventDefault();
		jQuery.get('/?p=login_now',function(data){
			$('#loadercenter').fadeOut('fast',function(){
				//$(this).remove();
			}).after(function() {
                $('body').prepend(data);
            });
			
			$('#overlay,#signinbox').fadeIn('fast');
			$('#signinclose').bind('click',function(){
				$('#overlay,#signinbox').fadeOut('slow',function(){$(this).remove();});	
				$('body').removeAttr('style');
			});
		});
		
		$('body').delegate('#loginbutton','click',function(e){
			e.preventDefault();
			var username = $('#username');
			var password = $('#password');
			$('#signinbox input').on('keydown focus',function(e){
				$(this).removeClass('input_error');
			});
			$.ajax({
					type:"POST",
					url:"/ajax.php?login=true",
					dateType:'json',
					data:$('#form_signin').serialize(),
					beforeSend:function(xhr){
						if(username.val()=='' || password.val()==''){
							if(username.val()==''){username.addClass('input_error');}
							if(password.val()==''){password.addClass('input_error');}
							$('#login_error_msg').css('display','block');
							$('#login_error_msg').html("username/password is required");
							xhr.abort();
						}else{
							$('#signinbox').hide();
							$('#loadercenter').show();
						}
					}
				}).done(function(msg){
					if(isJSON(msg)){
						msg = $.parseJSON(msg);
						if(msg.login_status){
							window.location = '/';
						}else{
							$('#loadercenter').hide();$('#signinbox').show();
							$('#login_error_msg').css('display','block');
							$('#login_error_msg').html(msg.error);	
						}	
					}else{
						$('#loadercenter').hide();$('#signinbox').show();
						$('#login_error_msg').css('display','block');
						$('#login_error_msg').html('Login Error, Try Again');
					}
				});	
		});	
	});
$('input[name=save_settings]').click(function(e) {
    e.preventDefault();
	var short_code = $('input[name=shortcode]').val();
	var ouv_val = $('input[name=ouv]:checked').val()?true:false;
	var hf_val = $('input[name=hf]:checked').val()?true:false;
	//alert(hf.val() +" "+ ouv.val());
	$.ajax({
		type:"POST",
		url:"/ajax.php?set_opt=true",
		dateType:'json',
		//data:{ouv:ouv_val,hf:hf_val,s_code:short_code},
		data:$('form').serialize(),
		beforeSend:function(xhr){
			showLoading();
		}
	}).done(function(msg){
		hideLoading();
			if(isJSON(msg)){
				msg = $.parseJSON(msg);
				if(msg.status){
					alert(msg.msg);
					//window.location = window.location.href;
				}else{
					alert(msg.msg);
				}
			}else{
				alert("save failed!");
			}
		
	});
});

});