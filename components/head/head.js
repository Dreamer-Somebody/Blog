$(document).ready(function($) {var $i=0;
	$("div.menu").click(function(event) {
        event.preventDefault();
		$sidebar =$("div#sidebar");
		if($i++%2==0){
			$sidebar.css('transform', 'translate(-150px) translateZ(0)');
			$sidebar.css('-moz-transform', 'translate(-150px) translateZ(0)');
		    $("div.menu").css('left','0px');
		}
		else{
			$sidebar.css('transform', 'translate(0px) translateZ(0)');
			$sidebar.css('-moz-transform', 'translate(0px) translateZ(0)');
			$("div.menu").css('left','150px');
		}
	});
});