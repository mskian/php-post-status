<?php

require '../src/middleware/csrf.php';
echo json_encode(['csrf_token' => generateCsrfToken()]);

?>