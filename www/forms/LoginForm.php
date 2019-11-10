<?

class LoginForm
{
	public $email = "";
	public $email_err = "";
	public $password = "";
	public $password_err = "";

	public function __construct($db)
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if (empty(trim($_POST["email"]))) {
				$this->email_err = "Please enter email.";
			} else {
				$this->email = trim($_POST["email"]);
			}

			if (empty(trim($_POST["password"]))) {
				$this->password_err = "Please enter your password.";
			} else {
				$this->password = trim($_POST["password"]);
			}

			if (empty($this->email_err) && empty($this->password_err)) {
				$sql = "SELECT id, email, password FROM user WHERE email = ?";
				if ($stmt = mysqli_prepare($db, $sql)) {
					mysqli_stmt_bind_param($stmt, "s", $param_email);
					$param_email = $this->email;
					if (mysqli_stmt_execute($stmt)) {
						mysqli_stmt_store_result($stmt);
						if (mysqli_stmt_num_rows($stmt) == 1) {
							mysqli_stmt_bind_result($stmt, $id, $this->email, $hashed_password);
							if (mysqli_stmt_fetch($stmt)) {
								if (password_verify($this->password, $hashed_password)) {
									session_start();
									$_SESSION["loggedin"] = true;
									$_SESSION["id"] = $id;
									$_SESSION["email"] = $this->email;
									header("location: index.php");
								} else {
									$this->password_err = "The password you entered was not valid.";
								}
							}
						} else {
							$this->email_err = "No account found with that email.";
						}
					} else {
						echo "Oops! Something went wrong. Please try again later.";
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
	}
}