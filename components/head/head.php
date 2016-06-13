<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="/blog/common/base.css" />
	<link rel="stylesheet" href="/blog/components/head/head.css" />
</head>
<body>
<div id="header">
<div class="menu"><i class="icon-menu"></i></div>
<form action="/blog/sort.php" method="get">
	<input type="hidden" name="key" value="keywords"/>
	<input type="text" id="search" name="value" placeholder="搜索文章关键字..."/> 
	<button class="btn-search" type="submit"><i class="icon-search"></i></button>
</form>
</div>
    <script src="/blog/common/jquery.js"></script>
	<script src="/blog/components/head/head.js"></script>
</body>
</html>