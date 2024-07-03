<?php

class Like {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Like a status.
     *
     * @param int $status_id ID of the status.
     * @return bool True if the like was added, false if it already existed.
     */
    public function likeStatus($status_id) {
        // Ensure the status_id is valid
        if (!filter_var($status_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            throw new InvalidArgumentException('Invalid status ID');
        }

        // Check if the status has already been liked
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM likes WHERE status_id = :status_id');
        $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
        $stmt->execute();
        
        // allow only 100 likes per status
        if ($stmt->fetchColumn() > 99) {
            // Status has already been liked
            return false;
        }

        // Insert new like
        $stmt = $this->pdo->prepare('INSERT INTO likes (status_id) VALUES (:status_id)');
        $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return true;
    }
}
?>