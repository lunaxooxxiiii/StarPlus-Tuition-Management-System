class Subject {
    private $db;
    private $table = 'subject';

    public $SubjectCode;
    public $SubjectName;
    public $SubjectPrice;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createSubject() {
        $query = "INSERT INTO $this->table (SubjectCode, SubjectName, SubjectPrice)
                  VALUES (:SubjectCode, :SubjectName, :SubjectPrice)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':SubjectCode', $this->SubjectCode);
        $stmt->bindParam(':SubjectName', $this->SubjectName);
        $stmt->bindParam(':SubjectPrice', $this->SubjectPrice);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getSubjectByCode($SubjectCode) {
        $query = "SELECT * FROM $this->table WHERE SubjectCode = :SubjectCode";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':SubjectCode', $SubjectCode);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
