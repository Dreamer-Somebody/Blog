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
});