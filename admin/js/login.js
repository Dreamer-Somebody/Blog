jQuery(document).ready(function($) {
	$("form").submit(function(event) {
		event.preventDefault();
		$user= $("input#user");
		$pwd= $("input#pwd");
		// $.post('control.php', {user: '$user',pwd:'$pwd'}, function($data) {
		// 	if($data){
		// 		window.location.href="index.php";
		// 	}
		// 	else{
		// 		$("body").append("<p>账号或密码输入错误，请重试...</p>");
		// 	}
		// });
	$.ajax({
		url: 'control.php',
		type: 'post',
		data: {user: '$user',pwd:'$pwd'},
		success:function(msg){
			alert("return: "+msg);
		}
	});
	
	return false;
	});
});