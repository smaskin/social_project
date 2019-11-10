<?

class User
{
	public $email = "";

	public function __construct($db)
	{
		$this->email = htmlspecialchars($_SESSION["email"]);
	}
}