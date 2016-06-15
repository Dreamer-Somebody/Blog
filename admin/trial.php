<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Ueditor的尝试</title>
</head>
<body>
<form action="control.php" method="post">
	<script id="container" name="content" type="text/plain"></script>
	<input type="submit" />
</form>
	<!-- 配置文件 -->
    <script type="text/javascript" src="/blog/components/ueditor/utf8-php/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/blog/components/ueditor/utf8-php/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
    </script>
</body>
</html>