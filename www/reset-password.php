<?

require_once "config.php";
require_once "forms/ResetPasswordForm.php";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: login.php");
	exit;
}
echo render('reset-password', ['model' => new ResetPasswordForm($db)]);
$db->close();
