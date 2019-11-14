<?

require_once "config.php";

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
function logger($msg) {
	$file = __DIR__. '/app.log';
	$current = file_get_contents($file);
	$current .= "$msg\n";
	file_put_contents($file, $current);
}

$sql = "INSERT INTO user (email, password, name, surname) VALUES (?, ?, ?, ?)";
if($stmt = mysqli_prepare($db, $sql)){
	mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_password, $param_name, $param_surname);
	$param_email = generateRandomString();
	$param_password = password_hash($param_email, PASSWORD_DEFAULT);
	$param_name = $param_email;
	$param_surname = $param_email;
	if(mysqli_stmt_execute($stmt)){
		logger("Created user - $param_email");
		header("location: login.php");
	} else{
		logger("Not created user - $param_email");
		echo "Something went wrong. Please try again later. " . $db->error;
	}
}
mysqli_stmt_close($stmt);

$db->close();
