<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$root .= "/blog";
$db      = "{$root}" . "/common/db_fns.php";
$sidebar = "{$root}" . "/components/sidebar/sidebar.php";
$common  = "{$root}" . "/common/common_fn.php";
include_once $db;
include_once $sidebar;
include_once $common;
