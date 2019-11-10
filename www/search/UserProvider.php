<?

class UserProvider
{
	public $query;
	/**
	 * @var mysqli
	 */
	public $db;

	public $query_err = '';

	public function __construct($db, $query)
	{
		$this->db = $db;
		$this->query = $query;
	}

	public function search()
	{
		$query = trim($this->query);
		if ($query && ($stmt = $this->db->prepare("SELECT id, name, surname FROM user WHERE name LIKE ? OR surname LIKE ? LIMIT 20"))) {
			$query = "$query%";
			$stmt->bind_param("ss", $query, $query);
			if (!$stmt->execute()) {
				$this->query_err = "Oops! Something went wrong. Please try again later.";
				return [];
			}
			$result = $stmt->get_result();
			if (!($models = array_map(function($item) { return ['id' => $item[0], 'name' => $item[1], 'surname' => $item[2]]; }, $result->fetch_all()))) {
				$this->query_err = "Nothing.";
			}
			$stmt->close();
			return $models;
		}
		return [];
	}
}