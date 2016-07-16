jQuery(document).ready(function($) {
    //点赞开始

    $("#like-button").on('mouseleave', function(event) {
        event.preventDefault();
        $("#thanks").css({
            transform: 'translateY(0px)',
            opacity: '0'
        });
    }).on('click', function(event) {
        event.preventDefault();
        if ($("#thanks").attr('fav') == 1) {
            $("#thanks").html("(*￣3￣)╭你已经支持过啦，</br>不能重复支持哦。。。").css({
                transform: 'translateY(-20px)',
                opacity: '1'
            });
            return;
        }
        article_id = this.dataset.id;
        $.ajax({
            url: '/blog/admin/control.php',
            data: {
                action: 'like',
                article_id: article_id
            },
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

    //点赞结束

    $("#list_nav li").on('click', function(event) {
        event.preventDefault();
        $this = $(this);
        $this.parent().find('li').removeClass('now');
        $class = $this.attr('class');
        $this.addClass('now');
        $(".sublist").removeClass('show');
        $('ul.' + $class).addClass('show');
    });

    //获取评论并展示开始

    (function get_comment() {
        $.ajax({
            url: '/blog/admin/control.php',
            data: {
                action: 'get_comment',
                article_id: '2016070801'
            },
            success: function(data) {
                //解析JSON时，数据被正序排序好了。
                var arr = JSON.parse(data);
                var obj = [];

                //获取arr对象数组的所有键名，然后倒序，这是为了实现评论按照最新最先的排序方式。
                obj = Object.keys(arr).reverse();
                var html = make_comment(arr, obj);
                $("#comment_list").append(html);
            }
        });
        var html = '';

        function make_comment(arr, obj) {
            for (var index in obj) {
                make_up(arr, obj[index]);
            }
            return html;
        }
        var start = false;
        var tag = [];

        function make_up(obj, index) {
            var now = obj[index];
            if ((tag[now.comment_id] != 1) && ((now.parent === null)||(now.parent==='')|| (now.parent !== null&&now.parent!==''&& 
                tag[obj[now.parent].comment_id] == 1))) {
                html += "<div class='user_comment " + (now.parent !== null&&now.parent!=='' ? 'children' : '') + 
            "'id='comment" + now.comment_id + "'>";
                html += "<div class='pic'>";
                html += "<img src='/blog/img/avatar/" + now.user_pic + "' name='avatar' /></div>";
                html += "<div class='wrapper'><span>" + now.user_name + "</span><span class='time'>" + 
                now.pub_time + "</span>";
                html += "<p class='comment_content'>"+ now.content + "</p></div>";
                html+="<div class='comment_actions'><a href='#comment_form' class='call' data-id='"+
                now.comment_id+"' data-user='"+now.user_name+"' data-parent='"+now.parent+"'>@TA</a><a href='#comment_form' class='reply' data-id='"+now.comment_id+"' data-user='"+now.user_name+"' data-parent='"+(now.parent===null?now.comment_id:now.parent)+"'>回复</a></div>";
                html += "</div>";
                start = true;
                tag[now.comment_id] = 1;
                if (now.children !== null) {
                    now.children= $.trim(now.children);
                    var arr= now.children.split(",");
                    for (var i = 0; i <= arr.length - 2; i++) {
                        arguments.callee(obj,arr[i]);
                    }
                }
            }
        }
    })();

    //获取评论并展示结束

    $("body").on('submit', 'form', function(event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/blog/admin/control.php?action=insert_comment',
            data: $('form').serialize(),
            error: function(request) {
                alert("连接服务器失败");
            },
            success: function(msg) {
                if (msg == "true") {
                    alert("评论成功！");
                } else {
                    alert("评论失败！");
                    console.log(msg);
                }
            }
        });
    });

//由于“@TA”和“回复”都是动态生成的节点，直接绑定事件不能成功，必须绑定到父级节点上。
    $("body").on('click', '.call', function(event) {
        event.preventDefault();
        id=this.dataset.id;
        user=this.dataset.user;
        $("textarea").focus();
        $("textarea").val($('textarea').val()+"<a href='#comment"+id+"'><span>@"+user+"</span></a>");
    });

    $("body").on('click', '.reply', function(event) {
        event.preventDefault();
        id=this.dataset.id;
        user=this.dataset.user;
        parent=this.dataset.parent;
        $("#comment_parent").val(parent);
        $("textarea").focus();
        $("textarea").val("回复<a href=#comment"+id+"><span>@"+user+"</span></a>：");
    });
});
