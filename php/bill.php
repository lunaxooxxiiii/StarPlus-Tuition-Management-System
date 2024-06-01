class Bill {
    private $db;
    private $table = 'bill';

    public $BillID;
    public $BillTotal;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createBill() {
        $query = "INSERT INTO $this->table (BillID, BillTotal)
                  VALUES (:BillID, :BillTotal)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':BillID', $this->BillID);
        $stmt->bindParam(':BillTotal', $this->BillTotal);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getBillByID($BillID) {
        $query = "SELECT * FROM $this->table WHERE BillID = :BillID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':BillID', $BillID);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
