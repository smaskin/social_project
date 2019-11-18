<?

class MessageForm
{
	public $text;
	public $recipient;

	public function __construct($user, $post, $db)
	{
		$this->text = trim($_POST["text"]);
		$this->recipient = (int)$_POST["recipient"];
		$sql = "INSERT INTO social_message.message (sender, recipient, text) VALUES (?, ?, ?)";
		if($stmt = mysqli_prepare($db, $sql)){
			mysqli_stmt_bind_param($stmt, "sss", $param_sender, $param_recipient, $param_text);
			$param_sender = $user->id;
			$param_recipient = $this->recipient;
			$param_text = $this->text;
			if(mysqli_stmt_execute($stmt)){
				header("location: login.php");
			} else{
				echo "Something went wrong. Please try again later. " . $db->error;
			}
		}
		mysqli_stmt_close($stmt);
	}
}