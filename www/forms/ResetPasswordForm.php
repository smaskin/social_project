<?

class ResetPasswordForm
{
	public $confirm_password = "";
	public $confirm_password_err = "";
	public $new_password = "";
	public $new_password_err = "";

	public function __construct($db)
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty(trim($_POST["new_password"]))) {
				$this->new_password_err = "Please enter the new password.";
			} elseif (strlen(trim($_POST["new_password"])) < 6) {
				$this->new_password_err = "Password must have atleast 6 characters.";
			} else {
				$this->new_password = trim($_POST["new_password"]);
			}
			if (empty(trim($_POST["confirm_password"]))) {
				$this->confirm_password_err = "Please confirm the password.";
			} else {
				$this->confirm_password = trim($_POST["confirm_password"]);
				if (empty($this->new_password_err) && ($this->new_password != $this->confirm_password)) {
					$this->confirm_password_err = "Password did not match.";
				}
			}
			if (empty($this->new_password_err) && empty($this->confirm_password_err)) {
				$sql = "UPDATE user SET password = ? WHERE id = ?";
				if ($stmt = mysqli_prepare($db, $sql)) {
					mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
					$param_password = password_hash($this->new_password, PASSWORD_DEFAULT);
					$param_id = $_SESSION["id"];
					if (mysqli_stmt_execute($stmt)) {
						session_destroy();
						header("location: login.php");
						exit();
					} else {
						echo "Oops! Something went wrong. Please try again later.";
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
	}
}

