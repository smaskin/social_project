<?

class User
{
	public $id;
	public $email;

	public function __construct($db)
	{
		$this->id = $_SESSION["id"];
		$this->email = htmlspecialchars($_SESSION["email"]);
	}
}