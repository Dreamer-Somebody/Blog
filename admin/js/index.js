jQuery(document).ready(function($) {
    if ($("#content").text() === '') {
        get_page("index");
    }
    $("body").on('click', 'a[id]', function(event) {
        event.preventDefault();
        $id = $(this).attr('id');
        get_page($id);
    });

    function get_page($id) {
        $.ajax({
            url: 'control.php',
            type: 'get',
            data: { action: 'get_page', id: $id },
            success: function(msg) {
                $("#content").html(msg);
            }
        });
        $("body").off('click', '.del').on('click', '.del', function(event) {
            event.preventDefault();
            $url = $(this).attr('href');
            $.ajax({
                url: $url,
                success: function(msg) {
                    if (msg == "true") {
                        alert("删除成功！");
                    } else {
                        alert("删除失败，请重试！");
                        alert(msg);

                    }
                    get_page("article");
                }
            });

        });
        $("body").off('click', '.recover').on('click', '.recover', function(event) {
            event.preventDefault();
            $url = $(this).attr('href');
            $.ajax({
                url: $url,
                error: function(request) {
                    alert("连接服务器失败");
                },
                success: function(msg) {
                    if (msg == "true") {
                        alert("恢复成功！");
                    } else {
                        alert("恢复失败，请重试！");
                    }
                    get_page("recycle");
                }
            });
        });
        $("body").off('submit','form').on('submit','form',function(event){
        	event.preventDefault();
        	$url= $('form').attr('action');
        	$.ajax({
                type: "POST",
                url:$url,
                data:$('form').serialize(),// 你的formid
                error: function(request) {
                    alert("连接服务器失败");
                },
                success: function(msg) {
                    if (msg == "true") {
                        alert("插入成功！");
                    } else {
                        alert("插入失败！");
                        alert(msg+"...");
                    }
                    get_page("article");
                }
            });
        });
    }
});
