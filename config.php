<?php 

include("class/mysql.class.php");

/**
 * 数据库信息
 */
$DATABASE_CONFIG = array(
        'server'    => 'localhost',
        'username'  => 'root',
        'password'  => 'usbw',
        'database'  => 'library'
        );

$mysql = new MySQLDatabase($DATABASE_CONFIG);

?>