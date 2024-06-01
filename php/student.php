class Student {
    private $db;
    private $table = 'student';

    public $StudentEmail;
    public $StudentPassword;
    public $FirstName;
    public $LastName;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createStudent() {
        $query = "INSERT INTO $this->table (StudentEmail, StudentPassword, FirstName, LastName)
                  VALUES (:StudentEmail, :StudentPassword, :FirstName, :LastName)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':StudentEmail', $this->StudentEmail);
        $stmt->bindParam(':StudentPassword', $this->StudentPassword);
        $stmt->bindParam(':FirstName', $this->FirstName);
        $stmt->bindParam(':LastName', $this->LastName);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getStudentByEmail($StudentEmail) {
        $query = "SELECT * FROM $this->table WHERE StudentEmail = :StudentEmail";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':StudentEmail', $StudentEmail);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
