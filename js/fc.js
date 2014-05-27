// JavaScript Document
function showLoading(){
	$('body').prepend('<div id="loadercenter"><div style="position:relative;top:10%;width:64px;height:64px;margin:15% auto;"><img src="images/loader.gif" style="margin:auto auto" width="64" height="64" /></div></div>');	
}
function hideLoading(){$('#loadercenter').remove();}
//Check if json
function isJSON(data) {
	var isJson = false
	try {
		// this works with JSON string and JSON object, not sure about others
	   var json = $.parseJSON(data);
	   isJson = typeof json === 'object' ;
	} catch (ex) {
		console.error('data is not JSON');
	}
	return isJson;
}

function scDialogBox(msg){
	msg = '<div>'+msg+'</div>';
	$.fancybox({
		type		: 'html',
		content		: msg,
		autoSize	: true,
		closeClick	: true,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
}

function addurlDialogBox(msg,redirect,redirecturl){
	if(typeof(redirect)==='undefined') redirect = false;
	if(typeof(redirecturl)==='undefined') redirecturl = '';
	msg = '<p style="margin: 0;padding: 15px;white-space: nowrap;">'+msg+'</p>';
	$.fancybox({
		type		: 'inline',
		content		: msg,
		afterClose	:function(){
			if(redirect==true)window.location.href = redirecturl;
		}
	});
}

function alpha(e) {
    var k;
    document.all ? k = e.keyCode : k = e.which;
    return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
}

function checkkeeploggedin(){
	var keeploginbox = document.getElementById("keeploggedin");
	if(keeploginbox.checked===true)
		keeploginbox.checked=false;
	else
		keeploginbox.checked=true;
}

$(document).ready(function(e) {
	
	$(function(){//limit chars second cell mylink
		var maxLinkChars = 28;
		$('.listlink').each(function(index, element) {
			var td = $(this).children(this);
			var a = $(td).eq(1).find('a');
			var a_length = a.text().trim().length;
			if(a_length>maxLinkChars){
				var pindik = a.text().trim().substr(0,maxLinkChars);
				a.text(pindik);
			}
		});
	});
});