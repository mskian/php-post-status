<?php
require '../src/config/database.php';
require '../src/middleware/csrf.php';
require '../src/helpers/sanitize.php';
require '../src/models/Status.php';

class StatusController {
    private $pdo;
    private $statusModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->statusModel = new Status($pdo);
    }

    /**
     * Handle posting a new status.
     * 
     * @param string $content The content of the status.
     * @param string $csrf_token The CSRF token for validation.
     * @param string $api_key The API key for authorization.
     * @return array The response array with success or error message.
     */
    public function postStatus($content, $csrf_token, $api_key) {
        if (!verifyCsrfToken($csrf_token)) {
            return ['error' => 'Invalid CSRF token'];
        }

        $content = sanitizeInput($content);
        if (strlen($content) < 2 || strlen($content) > 600) {
            return ['error' => 'Content length must be between 2 and 600 characters'];
        }

        if (!$this->statusModel->validateApiKey($api_key)) {
            return ['error' => 'Invalid Status key'];
        }

        try {
            $slug = $this->statusModel->createStatus($content, $api_key);
            return ['success' => true, 'slug' => $slug];
        } catch (PDOException $e) {
            return ['error' => 'Failed to post status'];
        }
    }

    /**
     * Handle fetching statuses with pagination.
     * 
     * @param int $page The current page number.
     * @param int $limit The number of statuses to fetch per page.
     * @return array The response array containing statuses and pagination info.
     */
    public function fetchStatuses($page, $limit = 3) {
        $offset = ($page - 1) * $limit;

        if ($page < 1) {
            $page = 1;
        }

        try {
            $statuses = $this->statusModel->getStatuses($limit, $offset);
            $totalStatuses = $this->statusModel->getTotalStatuses();
            $totalPages = ceil($totalStatuses / $limit);

            // Add like counts to statuses
            foreach ($statuses as &$status) {
                $status['likes'] = $this->statusModel->getLikeCount($status['id']);
            }

            return [
                'statuses' => $statuses,
                'current_page' => $page,
                'total_pages' => $totalPages
            ];
        } catch (PDOException $e) {
            return ['error' => 'Failed to fetch statuses'];
        }
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new StatusController($pdo);
    $content = isset($_POST['content']) ? $_POST['content'] : '';
    $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    $api_key = isset($_POST['api_key']) ? $_POST['api_key'] : '';
    $response = $controller->postStatus($content, $csrf_token, $api_key);
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new StatusController($pdo);
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $response = $controller->fetchStatuses($page);
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
