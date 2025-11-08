<?php

spl_autoload_register(function($className) {

	$exp = explode('\\', $className);
	$path = implode('/', $exp);

	include_once sprintf('../src/%s.php', $path);
});