class Announcement {
    private $db;
    private $table = 'announcement';

    public $AnnouncementID;
    public $AnnouncementDescription;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createAnnouncement() {
        $query = "INSERT INTO $this->table (AnnouncementDescription)
                  VALUES (:AnnouncementDescription)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':AnnouncementDescription', $this->AnnouncementDescription);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAnnouncementByID($AnnouncementID) {
        $query = "SELECT * FROM $this->table WHERE AnnouncementID = :AnnouncementID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':AnnouncementID', $AnnouncementID);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
