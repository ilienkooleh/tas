<?php
spl_autoload_register ('autoload');
function autoload ($className) {
	$class = str_replace('\\', "/", $className);
    $fileName = $class . '.php';
    include $fileName;
}
