<?

require_once "config.php";
require_once "forms/RegisterForm.php";

echo render('register', ['model' => new RegisterForm($db)]);
$db->close();
