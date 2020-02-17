$(document).ready(function () {
	$( ".cache-clear-button" ).on('click',function () {
		const url = $(this).data('url');
		$.ajax({
			url:url,
			type:'GET',
			dataType: 'json',
			success:function(data){
				if (data.status) {
					success_msg(data.msg);
				}else{
					error_msg(data.msg);
				}
			}
		});
	});
	
	function success_msg(msg) {
		const alert = $('.success-msg');
		alert.html(msg).fadeIn(500);
		setTimeout(()=>{alert.fadeOut(500)},3000);
	}
	
	function error_msg(msg) {
		const alert = $('.error-msg');
		alert.html(msg).fadeIn(500);
		setTimeout(()=>{alert.fadeOut(500)},3000);
	}
});