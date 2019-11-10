<?

require_once "config.php";
require_once "forms/LoginForm.php";

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("location: index.php");
	exit;
}

echo render('login', ['model' => new LoginForm($db)]);
$db->close();
