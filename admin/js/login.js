jQuery(document).ready(function($) {
	$("form").submit(function(event) {
		event.preventDefault();
		$user= $("input#user").val();
		$pwd= $("input#pwd").val();
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
		data: {user: $user,pwd: $pwd},
		success:function(msg){
			if(msg=="登陆成功！"){
				window.location.href="index.php";
			}else{
				$("div#msg p").html(msg);
			}
		}
	});
	return false;
	});
});