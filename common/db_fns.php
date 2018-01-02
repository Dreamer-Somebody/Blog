<?php

function db_connect()
{
    $result = new mysqli('127.0.0.1', 'skyside', '545877356', 'blog');
    if (!$result) {
        throw new Exception('不能连接到数据库');
    } else {
        return $result;
    }
}
