<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="HandheldFriendly" content="True" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABqklEQVQ4jZ2Tv0scURDHP7P7SGWh14mkuXJZEH8cgqUWcklAsLBbCEEJSprkD7hD/4BUISHEkMBBiivs5LhCwRQBuWgQji2vT7NeYeF7GxwLd7nl4knMwMDMfL8z876P94TMLt+8D0U0EggQSsAjwMvga8ChJAqxqjTG3m53AQTg4tXHDRH9ABj+zf6oytbEu5d78nvzcyiivx7QXBwy46XOi5z1jbM+Be+nqVfP8yzuD3FM6rzIs9YE1hqGvDf15cVunmdx7w5eYJw1pcGptC9CD4gBUuef5Ujq/BhAlTLIeFYuyfmTZgeYv+2nPt1a371P+Hm1WUPYydKf0lnePwVmh3hnlcO1uc7yvgJUDtdG8oy98kduK2KjeHI0fzCQINSXOk/vlXBUOaihAwnGWd8V5r1uhe1VIK52V6JW2D4FqHZX5lphuwEE7ooyaN7gjLMmKSwYL+pMnV+MA/6+g8RYa2Lg2RBQbj4+rll7uymLy3coiuXb5PdQVf7rKYvojAB8Lf3YUJUHfSYR3XqeLO5JXvk0dhKqSqQQoCO+s5AIxCLa2Lxc6ALcAPwS26XFskWbAAAAAElFTkSuQmCC" />

<title>Post Status</title>
<meta name="description" content="Post Status: Just Share What's on your Mind to the Hello World.">

<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css" integrity="sha512-IgmDkwzs96t4SrChW29No3NXBIBv8baW490zk5aXvhCD8vuZM3yUSkbyTBcXohkySecyzIrUwiF/qV0cuPcL3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Madurai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.2/axios.min.js" integrity="sha512-JSCFHhKDilTRRXe9ak/FJ28dcpOJxzQaCd3Xg8MyF6XFjODhy/YMCM8HW0TFDckNHWUewW+kfvhin43hKtJxAw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

<section class="section">
<div class="container">
<div class="status-card">
<div id="quote-card">
<h1 class="title has-text-success">Post a Status</h1>
<label class="label" style="color: #ff79c6">Status</label>
<div class="control">
<textarea class="textarea" id="statusContent" rows="10" cols="50" placeholder="What's on your mind?"></textarea>
</div>
<br>
<label class="label" style="color: #ff79c6">Status Key</label>
<div class="control">
<input class="input" type="password" id="apiKey" placeholder="Enter your Status key" autocomplete="current-password">
</div>
<input type="hidden" id="csrfToken">
<br>
<div class="control">
<button class="button is-danger is-rounded" onclick="postStatus()">Post</button>
</div>
</div>
</div>
</div>
</section>

<section class="container">
<h1 class="title has-text-centered is-size-4" style="color: #ff79c6">Statuses</h1>
<div id="statuses" class="columns is-multiline is-centered"></div>
<p id="currentPageInfo" class="pagination-info has-text-danger has-text-centered"></p>
<nav class="pagination" role="navigation" aria-label="pagination">
    <a class="pagination-link" id="prevPage" aria-disabled="true">Previous</a>
    <a class="pagination-link" id="nextPage" aria-disabled="true">Next page</a>
</nav> 
</section>
<br>

<div class="container">
<div id="alertModal" class="modal">
<div class="modal-background"></div>
<div class="modal-card">
<header class="modal-card-head">
<p class="modal-card-title">Alert</p>
<button class="delete" aria-label="close"></button>
</header>
<section class="modal-card-body" id="alertContent">
</section>
<footer class="modal-card-foot">
<button class="button is-primary" id="alertConfirmButton">OK</button>
</footer>
</div>
</div>
</div>

<script src="js/csrf.js"></script>
<script src="js/script.js"></script>

</body>
</html>