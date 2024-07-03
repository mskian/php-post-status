let currentPage = 1;
const statusesPerPage = 3;

document.addEventListener('DOMContentLoaded', () => {
    fetchStatuses(currentPage);
});

function fetchStatuses(page) {
    axios.get(`fetch_statuses.php?page=${page}&limit=${statusesPerPage}`)
        .then(response => {
            const data = response.data;

            if (data.error) {
                showAlert('Error', data.error);
                return;
            }

            const statuses = data.statuses;
            const totalPages = data.total_pages;

            const statusesContainer = document.getElementById('statuses');
            statusesContainer.innerHTML = '';

            if (statuses.length === 0) {
                statusesContainer.innerHTML = '<p class="has-text-white">No statuses found.</p>';
            } else {
                statuses.forEach(status => {
                    const statusElement = document.createElement('div');
                    statusElement.className = 'column is-one-third';
                    statusElement.innerHTML = `
                        <div class="status-card">
                            <div class="status-content">
                                <p class="quote-author" style="color: #f1fa8c">${status.content}</p>
                            </div>
                            <footer class="card-footer">
                                <a href="status_page.php?slug=${status.slug}" class="card-footer-item view-button">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="#" class="card-footer-item like-button" onclick="likeStatus(event, ${status.id})">
                                    <i class="fas fa-heart"></i> Like (${status.likes || 0})
                                </a>
                            </footer>
                        </div>
                    `;
                    statusesContainer.appendChild(statusElement);
                });
            }

            updatePaginationControls(page, totalPages);
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showAlert('Error', 'Failed to fetch statuses.');
        });
}

function postStatus() {
    const content = document.getElementById('statusContent').value.trim();
    const csrfToken = document.getElementById('csrfToken').value;
    const apiKey = document.getElementById('apiKey').value;

    if (content === '') {
        showAlert('Warning', 'Status content cannot be empty.');
        return;
    }

    if (apiKey === '') {
        showAlert('Warning', 'Add your Status Key.');
        return;
    }

    axios.post('post_status.php', new URLSearchParams({
        content: content,
        api_key: apiKey,
        csrf_token: csrfToken
    }))
        .then(response => {
            if (response.data.success) {
                fetchStatuses(currentPage);
                document.getElementById('statusContent').value = '';
                showAlert('Success', 'Status posted successfully.');
            } else {
                showAlert('Error', response.data.error);
            }
        })
        .catch(error => {
            console.error('Post error:', error);
            showAlert('Error', 'Failed to post status.');
        });
}

function likeStatus(event, statusId) {
    event.preventDefault();

    const csrfToken = document.getElementById('csrfToken').value;

    axios.post('like_status.php', new URLSearchParams({
        status_id: statusId,
        csrf_token: csrfToken
    }))
        .then(response => {
            if (response.data.success) {
                fetchStatuses(currentPage);
                showAlert('Success', 'Status liked successfully.');
            } else {
                showAlert('Error', response.data.error);
            }
        })
        .catch(error => {
            console.error('Like error:', error);
            showAlert('Error', 'Failed to like status.');
        });
}

function updatePaginationControls(currentPage, totalPages) {
    const prevPage = document.getElementById('prevPage');
    const nextPage = document.getElementById('nextPage');
    const currentPageInfo = document.getElementById('currentPageInfo');

    currentPageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

    prevPage.disabled = currentPage === 1;
    prevPage.removeEventListener('click', handlePreviousPage);
    prevPage.addEventListener('click', handlePreviousPage);

    nextPage.disabled = currentPage === totalPages;
    nextPage.removeEventListener('click', handleNextPage);
    nextPage.addEventListener('click', handleNextPage);
}

function handlePreviousPage() {
    if (currentPage > 1) {
        currentPage--;
        fetchStatuses(currentPage);
    }
}

function handleNextPage() {
    const totalPages = document.getElementById('currentPageInfo').textContent.split(' ')[3];
    if (currentPage < totalPages) {
        currentPage++;
        fetchStatuses(currentPage);
    }
}

function showAlert(title, message) {
    const modal = document.getElementById('alertModal');
    const modalContent = document.getElementById('alertContent');
    const closeButton = modal.querySelector('.delete');
    const confirmButton = document.getElementById('alertConfirmButton');

    modalContent.innerHTML = `<p><strong class="has-text-warning">${title}:</strong> ${message}</p>`;

    modal.classList.add('is-active');

    closeButton.addEventListener('click', () => {
        modal.classList.remove('is-active');
    });

    confirmButton.addEventListener('click', () => {
        modal.classList.remove('is-active');
    });

    modal.querySelector('.modal-background').addEventListener('click', () => {
        modal.classList.remove('is-active');
    });
}