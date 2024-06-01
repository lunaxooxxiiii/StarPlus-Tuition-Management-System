class Payment {
    private $db;
    private $table = 'payment';

    public $PaymentID;
    public $PaymentAmount;
    public $PaymentStatus;
    public $PaymentDate;
    public $AdminEmail;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createPayment() {
        $query = "INSERT INTO $this->table (PaymentAmount, PaymentStatus, PaymentDate, AdminEmail)
                  VALUES (:PaymentAmount, :PaymentStatus, :PaymentDate, :AdminEmail)";
        
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':PaymentAmount', $this->PaymentAmount);
        $stmt->bindParam(':PaymentStatus', $this->PaymentStatus);
        $stmt->bindParam(':PaymentDate', $this->PaymentDate);
        $stmt->bindParam(':AdminEmail', $this->AdminEmail);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getPaymentByID($PaymentID) {
        $query = "SELECT * FROM $this->table WHERE PaymentID = :PaymentID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':PaymentID', $PaymentID);
        $stmt->execute();
        return $stmt;
    }

    // Other methods like update, delete, etc. can be added similarly
}
