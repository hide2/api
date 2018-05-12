<?php
global $db;
$db = new Workerman\MySQL\Connection('127.0.0.1', '3306', 'user', 'password', 'database');