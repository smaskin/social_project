<?php

define('DB_SERVER', 'master');
define('DB_SLAVE_SERVER', 'slave');
define('DB_SERF_SERVER', 'serf');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'test');
define('DB_NAME', 'social');

$slaveFlag = strlen($_GET['query']) > 2;
$serfFlag = strlen($_GET['query']) > 4;
$server = $serfFlag ? DB_SERF_SERVER : ($slaveFlag ? DB_SLAVE_SERVER : DB_SERVER);
$db = new mysqli($server, DB_USERNAME , DB_PASSWORD, DB_NAME);

mysqli_set_charset($db, "utf8");

if (mysqli_connect_errno()) {
	printf("ERROR: Could not connect: %s\n", mysqli_connect_error());
	exit();
}

function render($template, $params = [])
{
	return renderPhpFile('layout', ['content' => renderPartial($template, $params)]);
}

function renderPartial($template, $params = [])
{
	return renderPhpFile($template, $params);
}

function renderPhpFile($template, $params)
{
	ob_start();
	ob_implicit_flush(false);
	extract($params, EXTR_OVERWRITE);
	require("views/$template.html");
	return ob_get_clean();
}