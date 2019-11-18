<?

require_once "config.php";
require_once "entities/User.php";
require_once "search/UserProvider.php";

session_start();

if (!isset($_GET['api']) && (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)) {
	header("location: login.php");
	exit;
}
echo render('index', ['model' => new User($db), 'userProvider' => new UserProvider($db, $_GET['query'])]);
$db->close();