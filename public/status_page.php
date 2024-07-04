<?php

require '../src/config/database.php';
require '../src/middleware/csrf.php';
require '../src/models/Status.php';

$slug = filter_var($_GET['slug'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$statusContent = '';
$totalLikes = 0;
$statusDate = '';

function formatDate($date) {
    $dateTime = new DateTime($date);
    $dateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
    return $dateTime->format('F j, Y \a\t g:i A');
}

if ($slug) {
    $statusModel = new Status($pdo);
    $status = $statusModel->getStatusBySlug($slug);

    if ($status) {
        $statusContent = $status['content'] ?? 'Status content not available';
        $statusDate = formatDate($status['created_at'] ?? 'Status Date not available');
        $totalLikes = isset($status['likes']) ? (int) $status['likes'] : 0;
    } else {
        $statusContent = 'Status not found';
    }
} else {
    $statusContent = 'Invalid status';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="True" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABqklEQVQ4jZ2Tv0scURDHP7P7SGWh14mkuXJZEH8cgqUWcklAsLBbCEEJSprkD7hD/4BUISHEkMBBiivs5LhCwRQBuWgQji2vT7NeYeF7GxwLd7nl4knMwMDMfL8z876P94TMLt+8D0U0EggQSsAjwMvga8ChJAqxqjTG3m53AQTg4tXHDRH9ABj+zf6oytbEu5d78nvzcyiivx7QXBwy46XOi5z1jbM+Be+nqVfP8yzuD3FM6rzIs9YE1hqGvDf15cVunmdx7w5eYJw1pcGptC9CD4gBUuef5Ujq/BhAlTLIeFYuyfmTZgeYv+2nPt1a371P+Hm1WUPYydKf0lnePwVmh3hnlcO1uc7yvgJUDtdG8oy98kduK2KjeHI0fzCQINSXOk/vlXBUOaihAwnGWd8V5r1uhe1VIK52V6JW2D4FqHZX5lphuwEE7ooyaN7gjLMmKSwYL+pMnV+MA/6+g8RYa2Lg2RBQbj4+rll7uymLy3coiuXb5PdQVf7rKYvojAB8Lf3YUJUHfSYR3XqeLO5JXvk0dhKqSqQQoCO+s5AIxCLa2Lxc6ALcAPwS26XFskWbAAAAAElFTkSuQmCC" />

<title>View Status</title>
<meta name="description" content="Post Status: Just Share What's on your Mind to the Hello World.">

<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css" integrity="sha512-IgmDkwzs96t4SrChW29No3NXBIBv8baW490zk5aXvhCD8vuZM3yUSkbyTBcXohkySecyzIrUwiF/qV0cuPcL3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Madurai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Hind Madurai', sans-serif;
        background-color: #2e2e2e;
        color: #cfcfcf;
    }
    .status-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        padding: 20px;
    }
    .status-card {
        max-width: 600px;
        width: 100%;
        background-color: #1e1e1e;
        border: 1px solid #444;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.7);
        padding: 20px;
    }
    .quote-text {
        font-size: 1.0rem;
        font-weight: 600;
        line-height: 1.6;
        color: #f1fa8c;
        margin-bottom: 2rem;
        white-space: pre-line;
    }
    .subtitle {
        color: #6272a4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .subtitle svg {
        margin-right: 0.5rem;
    }
    .card-footer {
        border-top: 1px solid #444;
        margin-top: 1rem;
        padding-top: 1rem;
        text-align: center;
    }
    .card-footer-item {
        color: #bd93f9;
        text-decoration: none;
        font-size: 0.9rem;
    }
    .card-footer-item:hover {
        color: #ff79c6;
    }
</style>
</head>
<body>

<section class="section">
    <div class="container status-container">
        <div id="quote-card" class="card status-card">
            <div class="card-content">
                <p class="quote-text"><?php echo $statusContent; ?></p>
                <br>
                <br>
                <p class="subtitle is-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff5555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.8 4.6c-1.4-1.4-3.5-1.4-4.9 0L12 8.5l-3.9-3.9c-1.4-1.4-3.5-1.4-4.9 0-1.4 1.4-1.4 3.5 0 4.9l8.9 8.9 8.9-8.9c1.4-1.4 1.4-3.5 0-4.9z"></path>
                    </svg>
                    Total Likes: <?php echo htmlspecialchars($totalLikes, ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <p class="subtitle is-6"><?php echo htmlspecialchars($statusDate, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <footer class="card-footer">
                <a href="/" class="card-footer-item">Back to Home</a>
            </footer>
        </div>
    </div>
</section>

</body>
</html>