<?php
include_once "common/include.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>天边的博客</title>
	<link rel="stylesheet" href="/blog/index.css" />
	<link rel="stylesheet" href="/blog/common/blog.css" />
</head>
<body>
	<div id="container">
		<div id="pic">
		<a id="switch">
			<i class="icon-loop"></i>
			<span>换一换</span>
		</a>
			<img src="/blog/img/headPic/8.jpg" alt="" />
		</div>
		<div id="content">
		<div id="show_article">
			<p id="header">最新文章</p>
			<?php
$query = "select * from article order by pub_time desc";
@$page = $_GET['page'] ? $_GET['page'] : 1;
show_article_list($query, $page);
?>
		</div>
		<div id="classify">
			<div class="date">
				<h3>文章归档</h3>
				<?php
$conn   = db_connect();
$query  = "select pub_time from article order by pub_time desc";
$result = $conn->query($query);
$rows   = $result->num_rows;
$dates  = array();
echo "<ul class='date'>";
for ($i = 1; $i <= $rows; $i++) {
    $array  = array();
    $row    = $result->fetch_array();
    $datess = explode('-', $row['pub_time']);
    array_push($array, $datess[0], $datess[1]);
    $string = implode('', $array);
    if (@is_null($dates[$string])) {
        $dates[$string] = 1;
    } else {
        $dates[$string]++;
    }
}
while (list($key, $val) = each($dates)) {
    echo "<li><a href='/blog/sort.php?key=article_id&value={$key}'>{$key} ({$val})</a></li>";
}
echo "<li><a href='/blog/sort.php?key=article_id'>更多 &nbsp;&gt;&gt;</a></li>";
echo "</ul>";
?>
			</div>
			<div class="category">
				<h3>文章分类</h3>
				<?php
$query  = "select distinct class from article";
$result = $conn->query($query);
$rows   = $result->num_rows;
echo "<ul class='category'>";
for ($i = 1; $i <= $rows; $i++) {
    $row = $result->fetch_array();
    echo "<li><a href='/blog/sort.php?key=class&value={$row['class']}'>{$row['class']}</a></li>";
}
echo "<li><a href='/blog/sort.php?key=class'>更多 &nbsp;&gt;&gt;</a></li>";
echo "</ul>";
?>

			</div>
			<div class="tags">
				<h3>标签分类</h3>
				<?php
$query  = "select keywords from article";
$result = $conn->query($query);
$rows   = $result->num_rows;
$tags   = array();
echo "<ul class='tags'>";
for ($i = 1; $i <= $rows; $i++) {
    $row = $result->fetch_array();
    if ($row['keywords'] == null) {
        continue;
    }
    $tagss = explode(',', $row['keywords']);
    $len   = count($tagss);
    for ($j = 0; $j < $len; $j++) {
        if (@is_null($tags[$tagss[$j]])) {
            $tags[$tagss[$j]] = $tagss[$j];
        }
    }

}
while (list($val) = each($tags)) {
    echo "<li><a href='/blog/sort.php?key=keywords&value={$val}'>{$val}</a></li>";
}
echo "<li><a href='/blog/sort.php?key=keywords'>更多 &nbsp;&gt;&gt;</a></li>";
echo "</ul>";
?>
			</div>
			<div class="hot">
				<h3>推荐文章</h3>
				<?php
$query  = "select title,link from article order by click,fav desc limit 0,7;";
$result = $conn->query($query);
$rows   = $result->num_rows;
echo "<ul class='hot'>";
for ($i = 1; $i <= $rows; $i++) {
    $row = $result->fetch_array();
    echo "<li><a href='{$row['link']}'>{$row['title']}</a></li>";
}
echo "<li><a href=''>更多 &nbsp;&gt;&gt;</a></li>";
echo "</ul>";
?>
			</div>
		</div>
		</div>
	</div>
	<script src="/blog/common/jquery.js"></script>
	<script src="/blog/index.js"></script>
</body>
</html>