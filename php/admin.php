class Admin {
    private $db;
    private $table = 'admin';

    public $AdminEmail;
    public $AdminPassword;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createAdmin() {
        $query = "INSERT INTO $this->table (AdminEmail, AdminPassword)
                  VALUES (:AdminEmail, :AdminPassword)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':AdminEmail', $this->AdminEmail);
        $stmt->bindParam(':AdminPassword', $this->AdminPassword);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getAdminByEmail($AdminEmail) {
        $query = "SELECT * FROM $this->table WHERE AdminEmail = :AdminEmail";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':AdminEmail', $AdminEmail);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
