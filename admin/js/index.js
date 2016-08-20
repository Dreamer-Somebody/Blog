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
            data: {
                action: 'get_page',
                id: $id
            },
            success: function(msg) {
                $("#content").html(msg);
            }
        });

        $("body").on('click', '.mark_as_read', function(event) {
            event.preventDefault();
            $.ajax({
                    url: 'control.php',
                    data: { action: 'mark_as_read' },
                })
                .done(function(data) {
                    if (data == 'true') {
                        alert("标记成功！");
                        get_page("new_comment");
                    } else {
                        alert("标记失败！");
                        alert(data);
                    }
                })
                .fail(function() {
                    console.log("连接服务器失败");
                });
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
                    if ($url.indexOf('type=article') >= 0) {
                        get_page("article");
                    } else if ($url.indexOf('type=comment') >= 0) {
                        get_page("comment");
                    } else if ($url.indexOf('type=feedback') >= 0) {
                        get_page("feedback");
                    } else if ($url.indexOf('type=works') >= 0) {
                        get_page("works");
                    }
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

        $("body").off('submit', 'form').on('submit', 'form', function(event) {
            event.preventDefault();
            $url = $('form').attr('action');
            $.ajax({
                type: "POST",
                url: $url,
                cache: false,
                data: new FormData($('form')[0]),
                processData: false,
                contentType: false,
                error: function(request) {
                    alert("连接服务器失败");
                },
                success: function(msg) {
                    if (msg == "true") {
                        alert("插入成功！");
                        if ($url.indexOf("action=insert_article")>=0) {
                            get_page("article");
                        } else if ($url.indexOf("action=insert_works")>=0) {
                            get_page("works");
                        }
                    } else {
                        alert("插入失败！");
                        console.log(msg);
                    }

                }
            });
        });
    }
});
