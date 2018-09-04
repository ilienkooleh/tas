<?php
include 'autoload.php';
define('BP', __DIR__);

$site = new Controller\Site();
$site->run();
