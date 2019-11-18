<?

class MessageProvider
{
	/**
	 * @var User
	 */
	public $user;
	/**
	 * @var mysqli
	 */
	public $db;

	public $query_err = '';

	public function __construct($db, $user)
	{
		$this->db = $db;
		$this->user = $user;
	}

	public function search()
	{
		$user = $this->user->id;
		if ($user && ($stmt = $this->db->prepare("SELECT sender, text FROM social_message.message WHERE sender=? OR recipient=? LIMIT 20"))) {
			$stmt->bind_param("ss", $user, $user);
			if (!$stmt->execute()) {
				$this->query_err = "Oops! Something went wrong. Please try again later.";
				return [];
			}
			$result = $stmt->get_result();
			if (!($models = array_map(function($item) { return ['sender' => $item[0], 'text' => $item[1]]; }, $result->fetch_all()))) {
				$this->query_err = "Nothing.";
			}
			$stmt->close();
			return $models;
		}
		return [];
	}
}