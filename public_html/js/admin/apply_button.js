$(document).ready(function () {
	var save_button = $( "button.action-save" );
	var apply_button = save_button.clone();
	save_button.after(apply_button);
	apply_button.prop('class','btn btn-success action-apply');
	apply_button.html('<i class="fa fa-check"></i> Применить');
	save_button.html('<i class="fa fa-save"></i> Сохранить и закрыть');
	
	$('.action-apply').bind('click', function(e) {
		e.preventDefault();
		$('input[name="referer"]').val('apply');
		$('form').submit();
	});
});