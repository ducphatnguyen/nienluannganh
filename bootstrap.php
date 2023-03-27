<?php

require_once __DIR__ . '/vendor/Psr4AutoloaderClass.php';
$loader = new Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('CT275\Nienluannganh', __DIR__ .'/src');

try {
    $PDO = (new CT275\Nienluannganh\PDOFactory)->create([
    'dbhost' => 'localhost',
    'dbname' => 'lms',
    'dbuser' => 'root',
    'dbpass' => ''
    ]);
} catch (Exception $ex) {
    echo 'Không thể kết nối đến MySQL, kiểm tra lại username/password đến MySQL.<br>';
    exit("<pre>${ex}</pre>");
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
session_start();
    
