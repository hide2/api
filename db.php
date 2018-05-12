<?php
global $db;
$db = new Workerman\MySQL\Connection('127.0.0.1', '3306', 'root', 'Fancy@Qingguo..888', 'xserver');
$all_tables = $db->query('show tables');
var_dump($all_tables);