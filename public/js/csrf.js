document.addEventListener('DOMContentLoaded', () => {
    fetchCsrfToken();
});

function fetchCsrfToken() {
    axios.get('csrf.php')
        .then(response => {
            document.getElementById('csrfToken').value = response.data.csrf_token;
        })
        .catch(error => console.error('Error fetching CSRF token:', error));
}
