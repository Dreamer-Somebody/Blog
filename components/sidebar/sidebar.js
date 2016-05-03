$(document).ready(function($) {
    $("i").click(function(event) {
    	event.preventDefault();
        var $this = $(this);
        if ($this.hasClass('icon-fold')) {
            $this.removeClass("icon-fold");
            $id = $this.attr("class");
            $this.addClass("icon-unfold");
            if ($("ul#" + $id).hasClass("hide")) {
                $("ul#" + $id).removeClass("hide");
            }
        } else if ($this.hasClass('icon-unfold')) {
            $this.removeClass("icon-unfold");
            $id = $this.attr("class");
            $this.addClass("icon-fold");
            $("ul#" + $id).addClass("hide");
        }
    });
    $("ul#nav a").click(function(event) {
    	event.preventDefault();
    	var $target=event.target;
    	$("ul#nav li").removeClass("active");
    	$($target.parentNode).addClass('active');
    });
});
