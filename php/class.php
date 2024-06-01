class ClassEntity {
    private $db;
    private $table = 'class';

    public $ClassID;
    public $ClassDay;
    public $ClassTime;
    public $LinkClass;
    public $TutorName;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createClass() {
        $query = "INSERT INTO $this->table (ClassDay, ClassTime, LinkClass, TutorName)
                  VALUES (:ClassDay, :ClassTime, :LinkClass, :TutorName)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':ClassDay', $this->ClassDay);
        $stmt->bindParam(':ClassTime', $this->ClassTime);
        $stmt->bindParam(':LinkClass', $this->LinkClass);
        $stmt->bindParam(':TutorName', $this->TutorName);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getClassByID($ClassID) {
        $query = "SELECT * FROM $this->table WHERE ClassID = :ClassID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ClassID', $ClassID);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
