<?php

require '../src/config/database.php';
require '../src/middleware/csrf.php';
require '../src/models/Like.php';

class LikeController {
    private $pdo;
    private $likeModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->likeModel = new Like($pdo);
    }

    /**
     * Handle liking a status.
     *
     * @param int $status_id The ID of the status to like.
     * @param string $csrf_token The CSRF token for validation.
     * @return array Response indicating success or error.
     */
    public function likeStatus($status_id, $csrf_token) {
        // Validate CSRF token
        if (!verifyCsrfToken($csrf_token)) {
            return ['error' => 'Invalid CSRF token'];
        }

        // Validate status_id
        if (!filter_var($status_id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
            return ['error' => 'Invalid status ID'];
        }

        try {
            $liked = $this->likeModel->likeStatus($status_id);
            return ['success' => $liked ? 'Like added' : 'Already liked'];
        } catch (InvalidArgumentException $e) {
            // Handle validation exceptions
            return ['error' => $e->getMessage()];
        } catch (PDOException $e) {
            // Log the error message
            error_log("Failed to like status: " . $e->getMessage());
            return ['error' => 'Failed to like status'];
        }
    }
}

// Check request method and process
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure proper handling of input data
    $status_id = isset($_POST['status_id']) ? (int)$_POST['status_id'] : 0;
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

    $controller = new LikeController($pdo);
    $response = $controller->likeStatus($status_id, $csrf_token);
    echo json_encode($response);
} else {
    // Handle invalid request methods
    echo json_encode(['error' => 'Invalid request method']);
}
?>