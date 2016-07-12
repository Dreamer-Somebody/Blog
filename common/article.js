jQuery(document).ready(function($) {
    $("#like-button").on('mouseleave', function(event) {
        event.preventDefault();
        $("#thanks").css({
            transform: 'translateY(0px)',
            opacity: '0'
        });
    }).on('click', function(event) {
        event.preventDefault();
        if($("#thanks").attr('fav')==1){
        	 $("#thanks").html("(*￣3￣)╭你已经支持过啦，</br>不能重复支持哦。。。").css({
                        transform: 'translateY(-20px)',
                        opacity: '1'
                    });
        	 return;
        }
        article_id = this.dataset.id;
        $.ajax({
            url: '/blog/admin/control.php',
            data: { action: 'like', article_id: article_id },
            success: function(msg) {
                if (msg == "true") {
                    $number = parseInt($("#likes-count").text()) + 1;
                    $("#likes-count").text($number);
                    $("#thanks").attr('fav', 1).html("=￣ω￣=感谢您的支持，</br>我会继续努力的！").css({
                        transform: 'translateY(-20px)',
                        opacity: '1'
                    });
                } else {
                	 $("#thanks").html("(╥╯^╰╥)点赞失败，，，</br>请重试。。。").css({
                        transform: 'translateY(-20px)',
                        opacity: '1'
                    });
                }
            }
        });
    });
});
