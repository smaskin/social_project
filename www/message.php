<?

require_once "config.php";
require_once "entities/User.php";
require_once "search/MessageProvider.php";
require_once "forms/MessageForm.php";

session_start();

if (!isset($_GET['api']) && (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)) {
	header("location: login.php");
	exit;
}

$user = new User($db);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	new MessageForm($user, $_POST, $db);
	http_response_code(200);
	die();
}

echo renderPartial('message', ['messageProvider' => new MessageProvider($db, $user), 'user' => $user]);
$db->close();