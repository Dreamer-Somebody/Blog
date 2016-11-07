<?php include_once "common/include.php";?>
<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <meta name="keywords" content="天边,个人博客,前端开发,生活感悟,web开发,blog,html,css,js,php"/>
<meta name='description' content='天边的个人博客,前端开发分享与生活交流，利用html,css,js,php等工具将想法创造成现实。'/>
        <title>作品集--天边的博客</title>
        <link rel='stylesheet' href='lab.css' />
    </head>
    <body>
        <div id='container'>
            <ul><li>
                <div class='wrapper'><img src='/blog/img/title/0b2cf1273342800f8008fb0281e58469dc0c1eb5b11b-8aUTqX_fw658.png'/>
                        <div class='name'>
                            <div class='head'>轮播图demo</div>
                            <div class='content'>这是第一次jquery实战的demo</div>
                            <a href='http://dreamer-somebody.github.io/demo/' target='_blank'>查看</a>
                        </div>
                    </div>
                </li></ul>
        </div>
        <script src='/blog/common/jquery.js'></script>
        <script>
        (function get_sidebar() {
        $.ajax({
            url: '/blog/admin/control.php',
            data: {
                action: 'get_sidebar'
            },
            success: function(data) {
                $('body').prepend(data);
            }
        });

    })();
        </script>
    </body>
    </html>
