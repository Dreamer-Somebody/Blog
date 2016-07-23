<?php
define("ROOT", "http://" . $_SERVER['SERVER_NAME']);
include_once "../common/db_fns.php";
session_start();

function login($username, $password)
{
    $password = sha1($password);
    $conn     = db_connect();
    //转义输出
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $result   = $conn->query("select * from user where username='{$username}' and password ='{$password}'");
    if (!$result) {
        echo "出错了，请刷新页面重试...";
    }
    if ($result->num_rows > 0) {
        $_SESSION['user'] = "{$username}";
        echo "登陆成功！";
    } else {
        echo "账号或密码错误，请重试...";
    }
}

function check_valid_user()
{
    $result = false;
    if (isset($_SESSION['user'])) {
        $result = true;
    }
    return $result;
}

function change_password($username, $old_password, $new_password)
{
    login($username, $old_password);
    $conn   = db_connect();
    $result = $conn->query("update user
                          set password = sha1('" . $new_password . "')
                          where username = '" . $username . "'");
    if (!$result) {
        echo ('不能改密码');
        die();
    } else {
        return true;
    }
}

function get_page($id)
{
    $string = preg_split('/[?=]+/i', $id);
    $page   = $string[0];
    @$extra = $string[2] ? $string : '';
    switch ($page) {
        case 'index':
            get_index_page();
            break;
        case 'article':
            get_article_page();
            break;
        case 'comment':
            get_comment_page();
            break;
        case 'file':
            get_file_page();
            break;
        case 'edit':
            get_edit_page($extra);
            break;
        case 'recycle':
            get_recycle_page();
            break;
        case 'comment':
            get_comment_page();
            break;
        case 'article_comment':
            get_article_comment_page($extra);
            break;
        default:
            echo "获取页面出错!";
            break;
    }
}

function get_table_data($table = '', $col = 'count(*)')
{
    $conn = db_connect();
    if (!$conn) {
        echo "连接数据库失败！";
        exit();
    }
    $query  = "select " . $col . " data from " . $table;
    $result = $conn->query($query);
    $row    = $result->fetch_array();
    return $row['data'];
}

function get_index_page()
{
    $string = "<h1>信息摘要</h1>";
    $string .= "<table id='index_table'>";
    $string .= "<tr><th colspan='2'>信息统计</th></tr>";
    $string .= "<tr><td>当前用户：</td><td>{$_SESSION['user']}</td></tr>";
    $string .= "<tr><td>文章总数：</td><td>" . get_table_data('article') . "</td></tr>";
    $string .= "<tr><td>评论总数：</td><td>" . get_table_data('comment') . "</td></tr>";
    $string .= "<tr><td>分类总数：</td><td>" . get_table_data('article', 'count(distinct class)') . "</td></tr>";
    $string .= "<tr><td>浏览总数：</td><td>" . get_table_data('article', 'sum(click)') . "</td></tr>";
    $string .= "</table>";
    echo "$string";
}

function get_article_page()
{
    $string = "<h1>文章管理</h1>";
    $string .= "<a href='###' id='edit'>+ 编写新文章</a>";
    $string .= "<a href='###' id='recycle'>- 回收站</a>";
    $string .= "<table><tr><th>序号</th><th>文章编号</th><th>标题</th><th>发表时间</th><th colspan=3>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select article_id,title,pub_time from article order by pub_time desc");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['article_id'] . "</td>";
        $string .= "<td>" . $row['title'] . "</td>";
        $string .= "<td>" . $row['pub_time'] . "</td>";
        $string .= "<td><a href='###' id='edit?edit_id={$row['article_id']}'>编辑</a></td>";
        $string .= "<td><a href='/blog/admin/control.php?action=del&id={$row['article_id']}&type=article' class='del'>删除</a></td>";
        $string .= "<td><a href='/blog/admin/control.php?action=del&id={$row['article_id']}&real=true&type=article' class='del'>彻底删除</a></td>";
        $string .= "</tr>";
    }
    $string .= "</table>";
    echo "$string";
}

function get_recycle_page()
{
    $string = "<h1>回收站</h1>";
    $string .= "<a href='###' id='article'>返回文章管理页</a>";
    $string .= "<a href='/blog/admin/control.php?action=del&real=true&type=article' class='del'>× 清空回收站</a>";
    $string .= "<table><tr><th>序号</th><th>文章编号</th><th>标题</th><th>发表时间</th><th colspan=2>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select article_id,title,pub_time from recycle order by pub_time desc");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['article_id'] . "</td>";
        $string .= "<td>" . $row['title'] . "</td>";
        $string .= "<td>" . $row['pub_time'] . "</td>";
        $string .= "<td><a href='/blog/admin/control.php?action=recover&id={$row['article_id']}' class='recover'>恢复</a></td>";
        $string .= "<td><a href='/blog/admin/control.php?action=del&id={$row['article_id']}&real=true&type=article' class='del'>彻底删除</a></td>";
        $string .= "</tr>";
    }
    $string .= "</table>";
    echo "$string";
}

function get_edit_page($extra = '')
{
    $title_value     = '';
    $file_name_value = '';
    $class_value     = '';
    $tags_value      = '';
    $content         = '';
    $new             = 1;
    $id              = '';
    if ($extra != '') {
        $id              = $extra[2];
        $conn            = db_connect();
        $query           = "select title,link,class,keywords,content from article where article_id='{$id}'";
        $result          = $conn->query($query);
        $row             = $result->fetch_array();
        $file_name_value = substr(strrchr($row['link'], "/"), 1);
        $file_name_value = explode('.', $file_name_value);
        $file_name_value = $file_name_value[0];
        $title_value     = "{$row['title']}";
        $class_value     = "{$row['class']}";
        $tags_value      = "{$row['keywords']}";
        $content         = "{$row['content']}";
        $new             = 0;
    }
    $string = "<h1>文章管理</h1>";
    $string .= "<form action='control.php?action=insert_article' method='post'>";
    $string .= "标题：<input type='text' name='title' value='{$title_value}'></input></br>";
    $string .= "文件名(只能输入英文)：<input type='text' name='file_name' value='{$file_name_value}'></input>" . ".html</br>";
    $string .= "分类：<input type= 'text' name='class' value='{$class_value}'></input>";
    $string .= "标签名：<input type= 'text' name='tags' value='{$tags_value}'></input>";
    $string .= "<input type='hidden' name='new' value='{$new}'></input>";
    $string .= "<input type='hidden' name='id' value='{$id}'></input>";
    $string .= "<script id='container' name='content' type='text/plain'>
  {$content}
  </script>
  <input type='submit' />
</form>
  <!-- 配置文件 -->
    <script type='text/javascript' src='/blog/components/ueditor/utf8-php/ueditor.config.js'></script>
    <!-- 编辑器源码文件 -->
    <script type='text/javascript 'src='/blog/components/ueditor/utf8-php/ueditor.all.js'></script>
    <link rel='stylesheet' href='/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCoreDefault.css' />
    <script src='/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCore.js'></script>
    <script src='/blog/common/jquery.js'></script>
    <!-- 实例化编辑器 -->
    <script type='text/javascript'>
        var ue = UE.getEditor('container');
    </script>";
    echo "$string";
}

function get_comment_page()
{
    $string = "<h1>评论管理</h1>";
    $string .= "<table><tr><th>序号</th><th>文章ID</th><th>文章标题</th><th>评论总数</th><th colspan=1>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select article_id,title,comment from article order by article_id desc");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['article_id'] . "</td>";
        $string .= "<td>" . $row['title'] . "</td>";
        $string .= "<td>" . $row['comment'] . "</td>";
        if ($row['comment'] != 0) {
            $string .= "<td><a href='###' id='article_comment?article_id={$row['article_id']}'>查看</a></td>";
        } else {
            $string .= "<td><span>查看</span></td>";
        }
        $string .= "</tr>";

    }
    $string .= "</table>";
    echo "$string";
}

function get_article_comment_page($extra)
{
    $article_id = $extra[2];
    $string     = "<h1>评论管理</h1>";
    $string .= "<a href='###' id='comment'>返回上一页</a>";
    $string .= "<table><tr><th>序号</th><th>评论编号</th><th>内容</th><th>评论用户</th><th>发表时间</th><th colspan=2>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select comment_id,content,user,pub_time from comment where article_id= '{$article_id}' order by pub_time desc ");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['comment_id'] . "</td>";
        $string .= "<td>" . $row['content'] . "</td>";
        $string .= "<td>" . $row['user'] . "</td>";
        $string .= "<td>" . $row['pub_time'] . "</td>";
        $string .= "<td><a href='/blog/admin/control.php?action=del&id={$row['comment_id']}&type=comment' class='del'>删除</a></td>";
        $string .= "</tr>";
    }
    $string .= "</table>";
    echo "$string";
}

//$real 是彻底删除的意思。

function del_article($id, $type, $real = false)
{
    $conn = db_connect();
    if ($type == 'article') {
        if ($real == 'false') {
            $query  = "insert into recycle select * from article where article_id={$id}";
            $result = $conn->query($query);
        } else {
            $query1 = "delete from recycle ";
            if ($id != 'none') {
                $query1 .= "where article_id={$id}";
            }
            $result1 = $conn->query($query1);
        }
        if ($id == 'none') {
            if (!$result1) {
                echo "false";
            } else {
                echo "true";
            }
            exit();
        }
    }
    if ($type == 'comment') {
        $query = "update article set comment=comment-1 where article_id= (select article_id from comment where comment_id={$id})";
        $conn->query($query);
        $query2   = "select children,comment_id from comment where comment_id=(select parent from comment where comment_id={$id})";
        $result   = $conn->query($query2);
        $row      = $result->fetch_array();
        $children = $row['children'];
        $parent   = $row['comment_id'];
        if ($parent !== null && $parent !== '') {
            $children = str_replace($id . ',', '', $children);
            $query3   = "update comment set children='{$children}' where comment_id={$parent}";
            $conn->query($query3);
        }
        $query4   = "select children from comment where comment_id={$id}";
        $result   = $conn->query($query4);
        $row      = $result->fetch_array();
        $children = $row['children'];
        if ($children !== null && $children !== '') {
            $delete   = [];
            $children = explode(',', $children);
            $len      = count($children);
            for ($i = 0; $i < $len; $i++) {
                if ($children[$i] !== null && $children[$i] !== '') {
                    $delete[$i] = $children[$i];
                }
            }
            $delete = implode(',', $delete);
            $query5 = "delete from comment where comment_id in ({$delete})";
            $conn->query($query5);
        }
    }
    $query2  = "delete from {$type} where {$type}_id={$id}";
    $result2 = $conn->query($query2);
    if (!$result2) {
        echo "false";
    } else {
        echo "true";
    }
}

function recover_article($id)
{
    $conn    = db_connect();
    $query   = "insert into article select * from recycle where article_id={$id}";
    $result  = $conn->query($query);
    $query2  = "delete from recycle where article_id={$id}";
    $result2 = $conn->query($query2);
    if (!$result2) {
        echo "false";
    } else {
        echo "true";
    }
}

function insert_article($title = '未命名文章', $file_name = 'untitled.html', $class, $tags, $content, $new, $id)
{
    $user       = isset($_SESSION['user']) ? $_SESSION['user'] : '天边';
    $article_id = ($id == '') ? get_article_id() : $id;
    $link       = get_article_link($file_name);
    $conn       = db_connect();
    if ($new == '1') {
        $query = "insert into article(article_id,title,link,user,content,class,keywords)
    values({$article_id},'{$title}','{$link}','{$user}','{$content}','{$class}','{$tags}')";
    } else {
        $query = "update article set title='{$title}',link='{$link}',class='{$class}',keywords='${tags}'
    ,content='${content}' where article_id='${article_id}'";

    }
    $result = $conn->query($query);
    if (!$result) {
        echo "false";
    } else {
        echo "true";
        return $article_id;
    }
}

function create_file($id)
{
    $conn   = db_connect();
    $query  = "select article.*,avatar from article,user where article_id={$id} and article.user=username";
    $result = $conn->query($query);
    $row    = $result->fetch_array();
    $html   = "";
    $html .= get_sidebar();
    $html .= get_article($row);
    $url = $_SERVER['DOCUMENT_ROOT'] . $row['link'];
    write_file($url, $html);
}

function get_sidebar()
{
    return file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/blog/components/sidebar/sidebar.php');
}
function get_article($row)
{
    $html = '';
    $html .= "
<html>
<head>
    <meta charset='UTF-8' />
    <title>{$row['title']}</title>
    <link rel='stylesheet' href='/blog/index.css' />
    <link rel='stylesheet' href='/blog/common/article.css' />
    <script src='/blog/components/ueditor/utf8-php/ueditor.parse.js'></script>
    <link rel='stylesheet' href='/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCoreDefault.css' />
    <script src='/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCore.js'></script>
</head>
<body>
    <div id='article'>
    <div id='title'><h1>{$row['title']}</h1>
    <div id='post-info'><a href='###'><img src='/blog/img/avatar/{$row['avatar']}'/>{$row['user']}</a>
    <span class='time'>{$row['pub_time']}</span></div></div><div id='content'>{$row['content']}<div
    id='addition'>" . get_keywords($row['keywords']) . "</div><div id='share'>分享到： <a class='circle-weibo'><i class=
    'icon-weibo'></i></a><a class='circle-qzone'>
<i class='icon-github'></i></a><a class='circle-wechat'><i class='icon-bubbles'></i></a></div><div id='like'>
<div id='like-button' data-id={$row['article_id']}><i class='icon-good'></i>支持<span id='likes-count'>
</span></div><p id='thanks' fav=0></p></div></div><div id='list'><ul id='list_nav'><li class='similar now'>相关文章
</li><li class='popular'>热门文章</li><li class='recommended'>推荐文章</li></ul></div><div id='comment'><h1>评论</h1>
<form id='comment_form'><p id='tips'><(￣v￣)/点击头像</br>可以更换头像</p><div class='pic'>
<div class='mask'><i class='icon-loop'></i></div><img src='' name='avatar' id='photo'/></div>
<input type='text' id='nickname' name='nickname' placeholder='昵称'/><textarea placeholder='评论...'
name='content'></textarea><div id='emoji'></div><input type='hidden' name='pic' value=''/>
<input type='hidden' name='article_id' value={$row['article_id']}><input type='hidden' id='comment_parent' name='comment_parent'/>
<input type= 'hidden' name='comment_html' id='comment_html'/>
<button type='submit'/>发表</button>
<div id='result'></div>
</form><div id='comment_list'></div></div></div><div id='choose'><div id='choices'><div id='avatar_pics'></div>
<div id='button'><div id='true'>确认</div></div></div><div id='cancel'><i class='icon-cancel'></i></div></div>
<div id='big_mask'></div><script>
    uParse('#content',{
    });
    </script>
    <script src='/blog/common/article.js'></script>
</body>
</html>
    ";
    return $html;
}

function write_file($url, $html)
{
    $fp = fopen($url, 'wb');
    fwrite($fp, $html);
    fclose($fp);
}

function get_keywords($keywords)
{
    $html     = '';
    $keywords = explode(',', $keywords);
    foreach ($keywords as $keyword) {
        $html .= "<a href='/blog/sort.php?key=keywords&value={$keyword}' class='keyword'>{$keyword} +</a>";
    }
    return $html;
}

function get_article_id()
{
    @$date      = date("Ymd");
    $conn       = db_connect();
    $query1     = "select count(*) from article where article.article_id like '{$date}%'";
    $query2     = "select count(*) from recycle where recycle.article_id like '{$date}%'";
    $result     = $conn->query($query1);
    $row        = $result->fetch_array();
    $result2    = $conn->query($query2);
    $row2       = $result2->fetch_array();
    $total      = $row['count(*)'] + $row2['count(*)'];
    $article_id = $total + 1;
    if ($article_id < 10) {
        $article_id = "0" . $article_id;
    }
    $article_id = $date . $article_id;
    return $article_id;
}
function get_article_link($file_name)
{
    @$year  = date("Y");
    @$month = date("m");
    $link   = "/blog/page/{$year}/{$month}/{$file_name}";
    return $link;
}

function add_like($id)
{
    $conn   = db_connect();
    $query  = "update article set fav=fav+1 where article_id={$id}";
    $result = $conn->query($query);
    if (!$result) {
        echo "false";
    } else {
        echo "true";
    }
}

function get_comment($id)
{
    $conn     = db_connect();
    $result   = $conn->query("select * from comment where article_id={$id} order by pub_time");
    $comments = array();
    if (!!$result) {
        $rows = $result->num_rows;
        for ($i = 1; $i <= $rows; $i++) {
            $row            = $result->fetch_array();
            $row['user']    = html_entity_decode($row['user'], ENT_QUOTES);
            $row['content'] = html_entity_decode($row['content'], ENT_QUOTES);
            $comment        = array("comment_id" => $row['comment_id'], "user_pic" => $row['avatar'], "user_name" => $row['user'],
                "pub_time"                           => $row['pub_time'], "content"    => $row['content'], "parent"   => $row['parent'], "children" => $row['children']);
            // array_push($comments, $comment);
            $comments[$row['comment_id']] = $comment;
        }
    }
    echo json_encode($comments);
}

function insert_comment($id, $pic, $user, $content, $parent)
{
    $user    = htmlentities($user, ENT_QUOTES);
    $content = htmlentities($content, ENT_QUOTES);
    $conn    = db_connect();
    $query   = "insert into comment(article_id,user,parent,avatar,content) values({$id},'{$user}',
               '{$parent}','{$pic}','{$content}')";
    $result = $conn->query("insert into comment(article_id,user,parent,avatar,content) values({$id},'{$user}',
               '{$parent}','{$pic}','{$content}')");
    $comment_id = $conn->insert_id;
    if ($parent !== null && $parent !== '') {
        $children = $comment_id . ',';
        $conn->query("update comment set children= concat(children,'$children') where comment_id={$parent}");
    }
    $conn->query("update article set comment =comment+1 where article_id={$id}");
    if ($result) {
        echo "$comment_id";
    } else {
        echo "false";
    }
}

function get_avatar()
{
    $dir    = '../img/avatar/';
    $avatar = '';
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $avatar .= "<img src='/blog/img/avatar/{$file}'/>";
            }
        }
        closedir($handle);
    }
    echo $avatar;
}

function get_likes_count($id)
{
    $conn   = db_connect();
    $query  = "select fav from article where article_id={$id}";
    $result = $conn->query($query);
    $row    = $result->fetch_array();
    echo $row['fav'];
}

function get_random_avatar()
{
    $dir = '../img/avatar';
    if ($handle = opendir($dir)) {
        $random = rand(1, count(scandir($dir)) - 2);
        for ($i = 0; $i < 2; $i++) {
            $file = readdir($handle);
        }
        for ($i = 1; $i <= $random; $i++) {
            $file = readdir($handle);
        }
        closedir($handle);
    }
    echo ROOT . "/blog/img/avatar/" . $file;
}

function get_article_list($id)
{
    $html = '';
    $html .= get_sublist_similar($id);
    $html .= get_sublist_popular();
    $html .= get_sublist_recommended();
    echo $html;
}

function get_sublist_similar($id)
{
    $html     = '';
    $keywords = get_article_keywords($id);
    $len      = intval(5 / count($keywords));
    $html .= "<ul class='show sublist similar'>";
    foreach ($keywords as $keyword) {
        $conn   = db_connect();
        $query  = "select title,link from article where keywords like '%{$keyword}%' limit 0,{$len}";
        $result = $conn->query($query);
        $rows   = $result->num_rows;
        for (; $rows > 0; $rows--) {
            $row = $result->fetch_array();
            $html .= "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
        }
    }
    $html .= "</ul>";
    return $html;
}

function get_article_keywords($id)
{
    $conn     = db_connect();
    $result   = $conn->query("select keywords from article where article_id={$id}");
    $row      = $result->fetch_array();
    $keywords = explode(',', $row['keywords']);
    return $keywords;
}

function get_sublist_popular()
{
    $html = '';
    $html .= "<ul class='sublist popular'>";
    $conn   = db_connect();
    $query  = "select title,link,click from article order by click desc limit 0,5";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    for (; $rows > 0; $rows--) {
        $row = $result->fetch_array();
        $html .= "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
    }
    $html .= "</ul>";
    return $html;
}

function get_sublist_recommended()
{
    $html = '';
    $html .= "<ul class='sublist recommended'>";
    $conn   = db_connect();
    $query  = "select title,link,fav from article order by fav desc limit 0,5";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    for (; $rows > 0; $rows--) {
        $row = $result->fetch_array();
        $html .= "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
    }
    return $html;
}

function add_click($id)
{
    $conn   = db_connect();
    $result = $conn->query("update article set click=click+1 where article_id={$id}");
}
