<?php

class Status {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Fetch a limited number of statuses with pagination.
     * 
     * @param int $limit Number of statuses to fetch per page.
     * @param int $offset Offset for pagination.
     * @return array Array of statuses.
     */
    public function getStatuses($limit, $offset) {
        // Validate limit and offset
        $limit = filter_var($limit, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 10;
        $offset = filter_var($offset, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: 0;

        $stmt = $this->pdo->prepare('
            SELECT * 
            FROM statuses 
            ORDER BY created_at DESC 
            LIMIT :limit OFFSET :offset
        ');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count the total number of statuses.
     * 
     * @return int Total number of statuses.
     */
    public function getTotalStatuses() {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM statuses');
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            // Log error and return a default value
            error_log('Database error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Create a new status and return the slug.
     * 
     * @param string $content Content of the status.
     * @param string $api_key API key for authorization.
     * @return string|array Slug of the newly created status or an error message.
     */
    public function createStatus($content, $api_key) {

        $content = $content;

        $slug = $this->generateSlug();

        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO statuses (content, slug) 
                VALUES (:content, :slug)
            ');
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();

            return $slug;

        } catch (PDOException $e) {
            // Log error and return an error message
            error_log('Database error: ' . $e->getMessage());
            return ['error' => 'Failed to create status'];
        }
    }

    /**
     * Validate the API key.
     * 
     * @param string $api_key The API key to validate.
     * @return bool True if valid, false otherwise.
     */
    public function validateApiKey($api_key) {
        // Implement your API key validation logic
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM api_keys WHERE api_key = :api_key');
        $stmt->execute(['api_key' => $api_key]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Generate a unique slug for the status.
     * 
     * @return string Generated slug.
     */
    private function generateSlug() {
        // Base slug
        $slug = 'status';

        // Append a unique identifier to ensure uniqueness
        $slug .= '-' . uniqid();

        return $slug;
    }

    /**
     * Fetch a status by its slug.
     * 
     * @param string $slug Slug of the status.
     * @return array|null Status data or null if not found.
     */
    public function getStatusBySlug($slug) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, IFNULL(l.like_count, 0) AS likes
            FROM statuses s
            LEFT JOIN (
                SELECT status_id, COUNT(*) AS like_count
                FROM likes
                GROUP BY status_id
            ) l ON s.id = l.status_id
            WHERE s.slug = :slug
        ");
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Like a status by its ID.
     * 
     * @param int $status_id ID of the status.
     * @return array Result of the operation.
     */
    public function likeStatus($status_id) {
        // Validate status_id to ensure it's a positive integer
        $status_id = filter_var($status_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$status_id) {
            return ['error' => 'Invalid status ID'];
        }

        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO likes (status_id) 
                VALUES (:status_id)
            ');
            $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
            $stmt->execute();

            return ['success' => true];
        } catch (PDOException $e) {
            // Log error and return an error message
            error_log('Database error: ' . $e->getMessage());
            return ['error' => 'Failed to like status'];
        }
    }

    /**
     * Get the count of likes for a specific status.
     * 
     * @param int $status_id ID of the status.
     * @return int Like count.
     */
    public function getLikeCount($status_id) {
        // Validate status_id to ensure it's a positive integer
        $status_id = filter_var($status_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$status_id) {
            return 0;
        }

        try {
            $stmt = $this->pdo->prepare('
                SELECT COUNT(*) 
                FROM likes 
                WHERE status_id = :status_id
            ');
            $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
            $stmt->execute();

            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            // Log error and return a default value
            error_log('Database error: ' . $e->getMessage());
            return 0;
        }
    }
}

?>