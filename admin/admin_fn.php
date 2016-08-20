<?php
define("ROOT", "http://" . $_SERVER['SERVER_NAME']);
@include_once "../common/db_fns.php";
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
        setcookie("author", "true", time() + 60 * 60 * 24 * 7, "/");
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
    $extra  = isset($string[2]) ? $string : '';

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
        case 'new_comment':
            get_new_comment_page();
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
        case 'feedback':
            get_feedback_page();
            break;
        case 'works':
            get_works_page();
            break;
        case 'edit_works':
            if ($extra) {
                get_edit_works_page($extra[2]);
            } else {
                get_edit_works_page();
            }
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
    $string .= "<form action='control.php?action=insert_article' method='post' enctype='multipart/form-data'>";
    $string .= "标题：<input type='text' name='title' value='{$title_value}'></input> ";
    $string .= "文件名(只能输入英文)：<input type='text' name='file_name' value='{$file_name_value}'></input>" . ".html</br>";
    $string .= "分类：<input type='text' name='class' id='class'/ value='{$class_value}'>";
    $string .= "<input type='button' id='button1'/ value='选择分类'><ul class='sub_menu' id='menu1'>";
    $class = get_all_class();
    while (list(, $val) = each($class)) {
        $string .= "<li value='$val'>$val</li>";
    }
    $string .= "<li>取消</li>";
    $string .= "</ul>";
    $string .= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 标签名：<input type= 'text' name='tags' id='tags' value='{$tags_value}'/>";
    $string .= "<input type='button' id='button2'/ value='添加标签'><ul class='sub_menu' id='menu2'>";
    $tags = get_all_tags();
    while (list(, $val) = each($tags)) {
        $string .= "<li value='$val'>$val</li>";
    }
    $string .= "<li>取消</li>";
    $string .= "</ul>";
    $string .= "<input type='file' name='upload'/>";
    $string .= "<input type='hidden' name='new' value='{$new}'/>";
    $string .= "<input type='hidden' name='id' value='{$id}'/>";
    $string .= "<script id='container' name='content' type='text/plain'>
  {$content}
  </script>
  <input type='submit' />
</form>
  <style>
  form .sub_menu{
    position: absolute;
    z-index: 1000;
    display: none;
    width: 216px;
    padding: 0;
    border: 1px solid #ddd;
    overflow: hidden;
    color: #423E3E;
    background-color: #FEF8EF;
  }
  form .sub_menu li{
    list-style-type: none;
    cursor: pointer;
    padding: 5px;
    display: inline-table;
    width: 60px;
    text-align: center;
    font-size: 12px;
    overflow: hidden;
    margin: 1px;
}
form .sub_menu li:hover{
    background-color: #6D4A4A;
    color: #fff;
}
  </style>
  <script>
  var button1=$('#button1');
  var menu1=$('#menu1');
  button1.on('click',function(event){
    event.preventDefault();
    menu1.css('display','block');
    $('#menu1').css({'left':event.clientX-10,'top':event.clientY-10});
});
  $('#menu1 li').on('click',function(event){
    menu1.css('display','none');
    value= $(this).attr('value');
    if(value){
    $('#class').val($(this).attr('value'));
    }
});
  var button2=$('#button2');
  var menu2=$('#menu2');
  button2.on('click',function(event){
    event.preventDefault();
    menu2.css('display','block');
    $('#menu2').css({'left':event.clientX-10,'top':event.clientY-10});
});
  $('#menu2 li').on('click',function(event){
    menu2.css('display','none');
    value= $(this).attr('value');
    var val=$('#tags').val();
    if( value && val.indexOf(value)<0 ){
        if(val){
            val+=' ';
        }
    val+= $(this).attr('value');
    $('#tags').val(val);
    }
});
  $('form').on('submit',function(event){
    if($('input[name=\'title\']').val()===''||$('input[name=\'file_name\']').val()===''||
    $('input[name=\'class\']').val()===''||$('input[name=\'tags\']').val()===''){
       alert('输入不完整，请检查！');
       return false;
    }
  });
  </script>
    <!-- 配置文件 -->
    <script type='text/javascript' src='/blog/components/ueditor/utf8-php/ueditor.config.js'></script>
    <!-- 编辑器源码文件 -->
    <script type='text/javascript 'src='/blog/components/ueditor/utf8-php/ueditor.all.js'></script>
    <link rel='stylesheet' href='/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCoreDefault.css' />
    <script src='/blog/components/ueditor/utf8-php/third-party/SyntaxHighlighter/shCore.js'></script>
    <script src='/blog/common/jquery.js'></script>
    <!-- 实例化编辑器 -->
    <script type='text/javascript'>
        var ue = UE.getEditor('container',{
            toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
             'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|','|',
            'link', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'insertvideo', 'music', 'attachment', 'insertframe', 'insertcode', 'pagebreak', 'template', 'background', '|',
            'horizontal',   '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'preview', 'searchreplace', 'drafts',
        ]],
           initialFrameHeight:320
           });

    </script>";
    echo "$string";
}

function get_all_class()
{
    $class  = [];
    $conn   = db_connect();
    $result = $conn->query("select distinct class from article");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        array_push($class, $row['class']);
    }
    return $class;
}

function get_all_tags()
{
    $conn     = db_connect();
    $query    = "select keywords from article";
    $result   = $conn->query($query);
    $rows     = $result->num_rows;
    $tags     = array();
    $tags_row = $rows;
    for ($i = 0, $num = 0; ($i < $rows) && ($num < $tags_row); $i++) {
        $row = $result->fetch_array();
        if ($row['keywords'] == null) {
            continue;
        }
        $tagss = explode(' ', $row['keywords']);
        $len   = count($tagss);
        for ($j = 0; $j < $len; $j++) {
            if (@is_null($tags[$tagss[$j]])) {
                $tags[$tagss[$j]] = $tagss[$j];
                $num++;
            }
        }

    }
    return $tags;
}

function get_comment_page()
{
    $string = "<h1>评论管理</h1>";
    $string .= "<a href='###' id='new_comment'>+ 查看未读评论</a>";
    $string .= "<table><tr><th>序号</th><th>文章ID</th><th>文章标题</th><th>评论总数</th><th colspan=1>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select article_id,title,comment from article where comment>0 order by article_id desc");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['article_id'] . "</td>";
        $string .= "<td>" . $row['title'] . "</td>";
        $string .= "<td>" . $row['comment'] . "</td>";
        $string .= "<td><a href='###' id='article_comment?article_id={$row['article_id']}'>查看</a></td>";
        $string .= "</tr>";

    }
    $string .= "</table>";
    echo "$string";
}

function get_new_comment_page()
{
    $string = "<h1>未读评论管理</h1>";
    $string .= "<a href='###' id='comment'>返回上一页</a>";
    $string .= "<a href='###' class='mark_as_read'>全部标为已读</a>";
    $string .= "<table><tr><th>序号</th><th>评论编号</th><th>内容</th><th>评论用户</th><th>发表时间</th><th>评论链接</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select article.article_id,comment_id,comment.content,comment.user,link,title,comment.pub_time
        from article,comment where have_read=0 and article.article_id=comment.article_id order by article.article_id desc");
    $rows = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['comment_id'] . "</td>";
        $string .= "<td>" . $row['content'] . "</td>";
        $string .= "<td>" . $row['user'] . "</td>";
        $string .= "<td>" . $row['pub_time'] . "</td>";
        $link = "{$row['link']}#comment{$row['comment_id']}";
        $string .= "<td><a href='{$link}' target='_blank'>查看</a></td>";
        $string .= "</tr>";
    }
    $string .= "</table>";
    echo "$string";
}

function mark_as_read()
{
    $conn   = db_connect();
    $query  = "update comment set have_read=1 where have_read=0";
    $result = $conn->query($query);
    if ($result) {
        echo "true";
    }
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

function get_feedback_page()
{
    $string = "<h1>留言管理</h1>";
    $string .= "<table><tr><th>序号</th><th>留言编号</th><th>内容</th><th>用户</th><th>发表时间</th><th colspan=2>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select comment_id,content,user,pub_time from comment where article_id= 0 order by pub_time desc ");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$i}</td>";
        $string .= "<td>" . $row['comment_id'] . "</td>";
        $string .= "<td>" . $row['content'] . "</td>";
        $string .= "<td>" . $row['user'] . "</td>";
        $string .= "<td>" . $row['pub_time'] . "</td>";
        $string .= "<td><a href='/blog/admin/control.php?action=del&id={$row['comment_id']}&type=feedback' class='del'>删除</a></td>";
        $string .= "</tr>";
    }
    $string .= "</table>";
    echo "$string";
}

function get_works_page()
{
    $string = "<h1>作品集管理</h1>";
    $string .= "<a href='###' id='edit_works'>添加作品</a>";
    $string .= "<table><tr><th>作品编号</th><th>作品名</th><th>描述</th><th>链接</th><th>头图</th><th colspan=2>操作</th></tr>";
    $conn   = db_connect();
    $result = $conn->query("select * from works");
    $rows   = $result->num_rows;
    for ($i = 1; $i <= $rows; $i++) {
        $row = $result->fetch_array();
        $string .= "<tr><td>{$row['works_id']}</td>";
        $string .= "<td>" . $row['name'] . "</td>";
        $string .= "<td>" . $row['description'] . "</td>";
        $string .= "<td>" . $row['url'] . "</td>";
        $string .= "<td>" . $row['head_pic'] . "</td>";
        $string .= "<td><a href='###' id='edit_works?id={$row['works_id']}'>编辑</a></td>";
        $string .= "<td><a href='/blog/admin/control.php?action=del&id={$row['works_id']}&type=works' class='del'>删除</a></td>";
        $string .= "</tr>";
    }
    $string .= "</table>";
    echo "$string";
}

function get_edit_works_page($id = null, $name = '', $description = '', $url = '', $head_pic = '')
{
    if (isset($id)) {
        $conn        = db_connect();
        $result      = $conn->query("select * from works where works_id='{$id}'");
        $row         = $result->fetch_array();
        $name        = $row['name'];
        $description = $row['description'];
        $url         = $row['url'];
        $head_pic    = $row['head_pic'];
    }
    $string = "<h1>作品编辑</h1>";
    $string .= "<a href='###' id='works'>返回上一页</a>";
    $string .= "<form action='control.php?action=insert_works' method='post' enctype='multipart/form-data'>";
    $string .= "作品名：<input type='text' name='name' value='{$name}'></input> ";
    $string .= "描述：<input type='text' name='description' value='{$description}'></input></br>";
    $string .= "链接：<input type='text' name='url' id='class' value='{$url}'/>";
    $string .= "头图：<input type='file' name='upload' value='{$head_pic}'/></br>";
    if (isset($id)) {
        $string .= "<input type='hidden' name='id' value='{$id}'/>";
    }
    $string .= "<input type='submit' value='提交'/></form>";
    echo $string;
}

function insert_works($id = null, $name, $description, $url)
{
    $head_pic = upload_head_pic();
    $head_pic = $head_pic ? $head_pic : '/blog/img/title/default.png';
    if ($id === null) {
        $query = "insert into works(name,description,url,head_pic) values('{$name}','{$description}','{$url}','{$head_pic}')";
    } else {
        $query = "update works set name='{$name}',description='{$description}',url='{$url}',head_pic='{$head_pic}' where works_id={$id}";
        unlink_pic($id);
    }
    $conn   = db_connect();
    $result = $conn->query($query);
    if (!$result) {
        echo "false";
    } else {
        echo "true";
        create_lab_page();
    }
}

function dir_is_empty($dir)
{
    if ($handle = opendir($dir)) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                return false;
            }
        }
        return true;
    }
}

function del_relative($id)
{
    $conn     = db_connect();
    $query    = "select link,head_pic from article where article_id='{$id}'";
    $query1   = "select link,head_pic from recycle where article_id='{$id}'";
    $result   = $conn->query($query);
    $result1  = $conn->query($query1);
    $res      = ($result->num_rows) ? $result : $result1;
    $row      = $res->fetch_array();
    $link     = $row['link'];
    $head_pic = $row['head_pic'];
    $url      = $_SERVER['DOCUMENT_ROOT'] . $link;
    $pic      = $_SERVER['DOCUMENT_ROOT'] . $head_pic;
    $path     = dirname($url);
    unlink($url);
    if (!head_pic_using($head_pic)) {
        unlink($pic);
    }
    if (dir_is_empty($path)) {
        rmdir($path);
    }
    $query2 = "delete from comment where article_id={$id}";
    $conn->query($query2);

}

function head_pic_using($head_pic)
{
    $conn   = db_connect();
    $result = $conn->query("select * from article where head_pic='{$head_pic}'");
    $rows1  = $result->num_rows;
    $result = $conn->query("select * from recycle where head_pic='{$head_pic}'");
    $rows2  = $result->num_rows;
    $result = $conn->query("select * from works where head_pic='{$head_pic}'");
    $rows3  = $result->num_rows;
    $rows   = $rows1 + $rows2 + $rows3;
    if ($rows > 1) {
        return true;
    } else {
        return false;
    }
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
            if ($id != 'none') {
                $query1 = "delete from recycle where article_id={$id}";
                del_relative($id);
            } else {
                $query1 = "delete from recycle";
                $query2 = "select article_id from recycle";
                $result = $conn->query($query2);
                $rows   = $result->num_rows;
                for ($i = 0; $i < $rows; $i++) {
                    $row = $result->fetch_array();
                    del_relative($row['id']);
                }
            }
            $result1 = $conn->query($query1);
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
    if ($type == 'feedback') {
        $type = "comment";
    }
    if ($type == 'works') {
        unlink_pic($id);
    }
    $query2  = "delete from {$type} where {$type}_id={$id}";
    $result2 = $conn->query($query2);
    if ($type == 'works') {
        create_lab_page();
    }
    if (!$result2) {
        echo "false";
    } else {
        echo "true";
    }
}

function unlink_pic($id)
{
    $query1   = "select head_pic from works where works_id= {$id}";
    $conn     = db_connect();
    $result   = $conn->query($query1);
    $row      = $result->fetch_array();
    $head_pic = $row['head_pic'];
    if (!head_pic_using($head_pic)) {
        $pic = $_SERVER['DOCUMENT_ROOT'] . $head_pic;
        unlink($pic);
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
    $article_id = $id ? $id : get_article_id();
    $conn       = db_connect();
    $head_pic   = upload_head_pic($article_id);
    $head_pic   = $head_pic ? $head_pic : '/blog/img/title/default.png';
    if ($new == '1') {
        $link  = get_article_link($file_name);
        $query = "insert into article(article_id,title,link,user,content,head_pic,class,keywords)
    values({$article_id},'{$title}','{$link}','{$user}','{$content}','{$head_pic}','{$class}','{$tags}')";
    } else {
        $query = "update article set title='{$title}',class='{$class}',keywords='${tags}'
    ,content='${content}',head_pic='{$head_pic}' where article_id='${article_id}'";
    }
    $result = $conn->query($query);
    if (!$result) {
        echo "false";
    } else {
        echo "true";
        return $article_id;
    }
}

function upload_head_pic($id = null)
{
    if ($_FILES['upload']['error'] > 0) {
        if ($_FILES['upload']['error'] == 4) {
            return false;
        }
        echo "出错了：" . $_FILES['upload']['error'];
        switch ($_FILES['upload']['error']) {
            case 1:echo "文件超过系统大小限制！";
                break;
            case 2:echo "文件超过表单大小限制！";
                break;
            case 3:echo "文件上传不完整！";
                break;
            case 6:echo "没有指定临时目录！";
                break;
            case 7:echo "不能写入磁盘！";
                break;
        }
        exit();
    }
    if ($id !== null) {
        $id   = substr($id, 0, 8);
        $path = "../img/upload/{$id}/";
        $url  = "/blog/img/upload/{$id}/";
    } else {
        $path = "../img/title/";
        $url  = "/blog/img/title/";
    }
    $upfile = $path . $_FILES['upload']['name'];
    if (!is_dir($path)) {
        if (!mkdir($path)) {
            echo "创建路径失败！";
            exit();
        }
    }
    if (!move_uploaded_file($_FILES['upload']['tmp_name'], $upfile)) {
        echo "出错了：不能将文件移到目标目录！";
        exit();
    }
    return $url .= $_FILES['upload']['name'];
}

function create_file($id)
{
    $conn   = db_connect();
    $query  = "select article.*,avatar from article,user where article_id={$id} and article.user=username";
    $result = $conn->query($query);
    $row    = $result->fetch_array();
    $html   = "";
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
    id='addition'>" . get_keywords($row['keywords']) . "</div><div id='like'>
<div id='like-button' data-id={$row['article_id']}><i class='icon-good'></i>支持<span id='likes-count'>
</span></div><p id='thanks' fav=0></p></div></div><div id='list'><ul id='list_nav'><li class='similar now'>相关文章
</li><li class='popular'>热门文章</li><li class='recommended'>推荐文章</li></ul></div></div><div id='comment'><h1>评论</h1>
<form id='comment_form'><p id='tips'><(￣v￣)/点击头像</br>可以更换头像</p><div class='pic'>
<div class='mask'><i class='icon-loop'></i></div><img src='' name='avatar' id='photo'/></div>
<input type='text' id='nickname' name='nickname' placeholder='昵称'/><textarea placeholder='评论...'
name='content'></textarea><div id='toolbar'><i class='icon-sentiment_satisfied' id='face'></i>" . get_emoji() . "</div><input type='hidden' name='pic' value=''/>
<input type='hidden' name='article_id' value={$row['article_id']}><input type='hidden' id='comment_parent' name='comment_parent'/>
<input type= 'hidden' name='comment_html' id='comment_html'/>
<button type='submit'/>发表</button>
<div id='result'></div>
</form><div id='comment_list'></div></div><div id='choose'><div id='choices'><div id='avatar_pics'></div>
<div id='button'><div id='true'>确认</div></div></div><div id='cancel'><i class='icon-cancel'></i></div></div>
<div id='big_mask'></div><script>
    uParse('#content',{
    });
    </script>
    <script src='/blog/common/jquery.js'></script>
    <script src='/blog/common/article.js'></script>
</body>
</html>
    ";
    return $html;
}

function write_file($url, $html)
{
    $fp = fopen($url, "w+b");
    fwrite($fp, $html);
    fclose($fp);
}

function get_emoji()
{
    $string = "<div id='emoji_list'>";
    $string .= "<img class='emoji' src='/blog/img/emoji/smile.png' data-emoji='smile' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/soso.png' data-emoji='soso' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/grin.png' data-emoji='grin' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/happy.png' data-emoji='happy' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/muse.png' data-emoji='muse' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/shock.png' data-emoji='shock' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/cold.png' data-emoji='cold' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/careless.png' data-emoji='careless' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/cry.png' data-emoji='cry' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/eye.png' data-emoji='eye' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/cool.png' data-emoji='cool' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/fear.png' data-emoji='fear' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/sweat.png' data-emoji='sweat' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/xx.png' data-emoji='xx' />";
    $string .= "<img class='emoji' src='/blog/img/emoji/lol.png' data-emoji='lol' />";
    $string .= "</div>";
    return $string;
}

function get_keywords($keywords)
{
    $html     = '';
    $keywords = explode(' ', $keywords);
    foreach ($keywords as $keyword) {
        $html .= "<a href='/blog/sort.php?key=keywords&value={$keyword}' class='keyword'>{$keyword} +</a>";
    }
    return $html;
}

function get_article_id()
{
    $date       = date("Ymd");
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
    $dir    = $_SERVER['DOCUMENT_ROOT'] . "/blog/page/{$year}/{$month}";
    $link   = "/blog/page/{$year}/{$month}/{$file_name}";
    if (!is_dir($dir)) {
        mkdir($dir);
    }

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
    $result     = $conn->query($query);
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
    $html .= "<ul class='show sublist similar'>";
    $conn  = db_connect();
    $query = "select title,link,pub_time from article where ";
    for ($i = 0; $i < count($keywords); $i++) {
        if ($keywords[$i]) {
            $query .= $i !== 0 ? "or" : '';
            $query .= " keywords like '%{$keywords[$i]}%' ";
        }
    }
    $query .= " and article_id!={$id} order by pub_time desc";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    for ($i = 0; $i < 5 && $rows > 0; $i++, $rows--) {
        $row = $result->fetch_array();
        $html .= "<li><i class='icon-link'></i><a href='{$row['link']}'>{$row['title']}</a></li>";
    }
    $html .= "</ul>";
    return $html;
}

function get_article_keywords($id)
{
    $conn     = db_connect();
    $result   = $conn->query("select keywords from article where article_id={$id}");
    $row      = $result->fetch_array();
    $keywords = explode(' ', $row['keywords']);
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

function create_lab_page()
{
    $html = create_lab_html();
    $url  = $_SERVER['DOCUMENT_ROOT'] . "/blog/lab.html";
    write_file($url, $html);
}
function create_lab_html()
{
    $html = "";
    $html .= "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8' />
        <title>作品集--天边的博客</title>
        <link rel='stylesheet' href='lab.css' />
    </head>
    <body>
        <div id='container'>
            <ul>";
    $conn   = db_connect();
    $result = $conn->query("select * from works order by works_id desc");
    $rows   = $result->num_rows;
    for ($i = 0; $i < $rows; $i++) {
        $row = $result->fetch_array();
        $html .= "<li>
                <div class='wrapper'><img src='{$row['head_pic']}'/>
                        <div class='name'>
                            <div class='head'>{$row['name']}</div>
                            <div class='content'>{$row['description']}</div>
                            <a href='{$row['url']}' target='_blank'>查看</a>
                        </div>
                    </div>
                </li>";
    }
    $html .= "</ul>
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
    </html>";
    return $html;
}
