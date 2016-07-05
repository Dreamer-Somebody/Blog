jQuery(document).ready(function($) {
	 $("i.icon-fold").on("click",function(event) {
	   event.preventDefault();
        //由于jquery对象与普通DOM对象不同，不能使用普通DOM对象的方法，
        // 所以$this.dataset.type报type属性未定义的错误，此处只能用this.dataset.type。
        type =this.dataset.type;
        var $this = $(this);
        if (!($this.hasClass('rotate'))) {
            $this.addClass("rotate");
            if ($("ul#"+type).hasClass("hide")) {
                $("ul#"+type).removeClass("hide");
            }
        } else {
            $this.removeClass("rotate");
            $("ul#"+type).addClass("hide");
        }
	 });
    $("ul#nav a").click(function(event) {
        var $target = event.target;
        $("ul#nav li").removeClass("active");
        $($target.parentNode).addClass('active');
    });
    var $i=0;
    $("div.menu").click(function(event) {
        event.preventDefault();
        $sidebar =$("div#sidebar");
        $container =$("div#container");
        if($i++%2===0){
            $sidebar.css('transform', 'translate(-160px) translateZ(0)');
            $sidebar.css('-moz-transform', 'translate(-160px) translateZ(0)');
            $container.css('transform', 'translate(-85px) translateZ(0)');
            $container.css('-moz-transform', 'translate(-85px) translateZ(0)');
        }
        else{
            $sidebar.css('transform', 'translate(0px) translateZ(0)');
            $sidebar.css('-moz-transform', 'translate(0px) translateZ(0)');
            $container.css('transform', 'translate(0px) translateZ(0)');
            $container.css('-moz-transform', 'translate(0px) translateZ(0)');
        }
    }); 
});