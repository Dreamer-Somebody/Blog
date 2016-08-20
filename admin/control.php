<?php
include_once "admin_fn.php";
include_once "../common/db_fns.php";
@$action = $_REQUEST['action'];
if ($action == 'login') {
    if (!get_magic_quotes_gpc()) {
        //过滤输入
        $_POST['user'] = addslashes($_POST['user']);
        $_POST['pwd']  = addslashes($_POST['pwd']);
    }
    if (isset($_POST['user']) && $_POST['user'] != '' && isset($_POST['pwd']) && $_POST['pwd'] != '') {
        $result = login($_POST['user'], $_POST['pwd']);
        return $result;
    } else {
        echo "账号和密码都不能为空！";
    }
}
if ($action == 'get_page') {
    @$id = $_REQUEST['id'];
    get_page($id);
}
if ($action == 'del') {
    @$id   = isset($_REQUEST['id']) ? $_REQUEST['id'] : 'none';
    @$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'article';
    @$real = isset($_REQUEST['real']) ? $_REQUEST['real'] : 'false';
    del_article($id, $type, $real);
}
if ($action == 'recover') {
    @$id = $_REQUEST['id'];
    recover_article($id);
}
if ($action == 'insert_article') {
    @$title     = $_REQUEST['title'];
    @$file_name = $_REQUEST['file_name'] . ".html";
    @$class     = $_REQUEST['class'];
    @$tags      = $_REQUEST['tags'];
    @$content   = $_REQUEST['content'];
    @$new       = $_REQUEST['new'];
    @$id        = $_REQUEST['id'];
    $id         = insert_article($title, $file_name, $class, $tags, $content, $new, $id);
    create_file($id);

}
if ($action == 'get_sidebar') {
    echo get_sidebar();
}
if ($action == 'add_click') {
    $id = $_REQUEST['article_id'];
    add_click($id);
}
if ($action == 'like') {
    $id = $_REQUEST['article_id'];
    add_like($id);
}

if ($action == 'get_comment') {
    $id = $_REQUEST['article_id'];
    get_comment($id);
}

if ($action == 'insert_comment') {
    $id      = $_REQUEST['article_id'];
    $pic     = $_REQUEST['pic'];
    $user    = $_REQUEST['nickname'];
    $content = isset($_REQUEST['comment_html']) && $_REQUEST['comment_html'] !== '' ? $_REQUEST['comment_html'] : $_REQUEST['content'];
    $content = htmlentities($content, ENT_QUOTES);
    if ($user === '' || $content === '') {
        echo "false";
        exit();
    }
    $parent = isset($_REQUEST['comment_parent']) && $_REQUEST['comment_parent'] !== '' ? $_REQUEST['comment_parent'] : null;
    insert_comment($id, $pic, $user, $content, $parent);
}

if ($action == 'insert_works') {
    $id          = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
    $name        = $_REQUEST['name'];
    $description = $_REQUEST['description'];
    $url         = $_REQUEST['url'];
    insert_works($id, $name, $description, $url);
}

if ($action == 'get_avatar') {
    get_avatar();
}
if ($action == 'get_likes_count') {
    $id = $_REQUEST['article_id'];
    get_likes_count($id);
}
if ($action == 'get_random_avatar') {
    get_random_avatar();
}
if ($action == 'get_article_list') {
    $id = $_REQUEST['article_id'];
    get_article_list($id);
}
if ($action == 'mark_as_read') {
    mark_as_read();
}
