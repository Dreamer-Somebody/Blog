$(document).ready(function($) {
	var $i=0;
	$("div.menu").click(function(event) {
        event.preventDefault();
		$sidebar =$("div#sidebar");
		$container =$("div#container");
		if($i++%2===0){
			$sidebar.css('transform', 'translate(-160px) translateZ(0)');
			$sidebar.css('-moz-transform', 'translate(-160px) translateZ(0)');
		    $("div.menu").css('left','0px');
		    $container.css('transform', 'translate(-85px) translateZ(0)');
		    $container.css('-moz-transform', 'translate(-85px) translateZ(0)');
		}
		else{
			$sidebar.css('transform', 'translate(0px) translateZ(0)');
			$sidebar.css('-moz-transform', 'translate(0px) translateZ(0)');
			$("div.menu").css('left','160px');
			$container.css('transform', 'translate(0px) translateZ(0)');
		    $container.css('-moz-transform', 'translate(0px) translateZ(0)');
		}
	});
});