<?

class RegisterForm
{
	public $name = "";
	public $name_err = "";
	public $surname = "";
	public $surname_err = "";
	public $email = "";
	public $email_err = "";
	public $password = "";
	public $password_err = "";
	public $confirm_password = "";
	public $confirm_password_err = "";

	public function __construct($db)
	{
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(empty(trim($_POST["email"]))){
				$this->email_err = "Please enter a email.";
			} else{
				$sql = "SELECT id FROM user WHERE email = ?";
				if($stmt = mysqli_prepare($db, $sql)){
					mysqli_stmt_bind_param($stmt, "s", $param_email);
					$param_email = trim($_POST["email"]);
					if(mysqli_stmt_execute($stmt)){
						mysqli_stmt_store_result($stmt);
						if(mysqli_stmt_num_rows($stmt) == 1){
							$this->email_err = "This email is already taken.";
						} else{
							$this->email = trim($_POST["email"]);
							$this->name = trim($_POST["name"]);
							$this->surname = trim($_POST["surname"]);
						}
					} else{
						echo "Oops! Something went wrong. Please try again later.";
					}
				}
				mysqli_stmt_close($stmt);
			}

			if(empty(trim($_POST["password"]))){
				$this->password_err = "Please enter a password.";
			} elseif(strlen(trim($_POST["password"])) < 6){
				$this->password_err = "Password must have atleast 6 characters.";
			} else{
				$this->password = trim($_POST["password"]);
			}

			if(!trim($_POST["confirm_password"])){
				$this->confirm_password_err = "Please confirm password.";
			} else{
				$this->confirm_password = trim($_POST["confirm_password"]);
				if(!$this->password_err && ($this->password != $this->confirm_password)){
					$this->confirm_password_err = "Password did not match.";
				}
			}

			if(!$this->email_err && !$this->password_err && !$this->confirm_password_err){
				$sql = "INSERT INTO user (email, password, name, surname) VALUES (?, ?, ?, ?)";
				if($stmt = mysqli_prepare($db, $sql)){
					mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_password, $param_name, $param_surname);
					$param_email = $this->email;
					$param_password = password_hash($this->password, PASSWORD_DEFAULT); // Creates a password hash
					$param_name = $this->name;
					$param_surname = $this->surname;
					if(mysqli_stmt_execute($stmt)){
						header("location: login.php");
					} else{
						echo "Something went wrong. Please try again later. " . $db->error;
					}
				}
				mysqli_stmt_close($stmt);
			}
		}
	}
}

