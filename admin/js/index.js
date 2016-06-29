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
        $("body").off('click','.del').on('click', '.del', function(event) {
            event.preventDefault();
            $url = $(this).attr('href');
            $.ajax({
                url: $url,
                success: function(msg) {
                    if (msg == "true") {
                        alert("删除成功！");
                        get_page("article");
                    } else {
                        alert("删除失败，请重试！");
                    }

                }
            });

        });
    }
});
