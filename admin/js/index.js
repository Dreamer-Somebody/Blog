jQuery(document).ready(function($) {
	if($("#content").text()===''){
		$.ajax({
			url: 'control.php',
			type: 'get',
			data: 'action=get_page&id=index'
		});	
	}
	$("#nav a[id]")
});