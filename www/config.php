<?php

define('DB_SERVER', 'master');
define('DB_SLAVE_SERVER', 'slave');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'test');
define('DB_NAME', 'social');

$slaveFlag = strlen($_GET['query']) > 4;
$db = ($slaveFlag)
	? new mysqli(DB_SLAVE_SERVER, DB_USERNAME , DB_PASSWORD, DB_NAME)
	: new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

mysqli_set_charset($db, "utf8");

if (mysqli_connect_errno()) {
	printf("ERROR: Could not connect: %s\n", mysqli_connect_error());
	exit();
}

function render($template, $params = [])
{
	return renderPhpFile('layout', ['content' => renderPhpFile($template, $params)]);
}

function renderPhpFile($template, $params)
{
	ob_start();
	ob_implicit_flush(false);
	extract($params, EXTR_OVERWRITE);
	require("views/$template.html");
	return ob_get_clean();
}