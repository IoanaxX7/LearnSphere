document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.reaction-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const reaction = this.getAttribute('data-reaction');
            const type = this.getAttribute('data-type');  // 'material' or 'comment'

            if (!reaction) {
                console.error("Unknown reaction type:", reaction);
                return;
            }
            if (!id) {
                console.error("No ID found on button");
                return;
            }

            // Compose POST data
            let body = `reaction=${reaction}`;
            if (type === 'comment') {
                body += `&comentariuID=${id}`;
            } else if (type === 'material') {
                body += `&materialID=${id}`;
            } else {
                console.error("Unknown type:", type);
                return;
            }

            fetch('../actiuni/actiune.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: body
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update counts
                    const likeCountEl = document.getElementById(`like-count-${id}`);
                    const dislikeCountEl = document.getElementById(`dislike-count-${id}`);
                    if (likeCountEl) likeCountEl.innerText = data.likes;
                    if (dislikeCountEl) dislikeCountEl.innerText = data.dislikes;

                    const likeBtn = document.querySelector(`.reaction-btn.like[data-id="${id}"][data-type="${type}"]`);
                    const dislikeBtn = document.querySelector(`.reaction-btn.dislike[data-id="${id}"][data-type="${type}"]`);
                    if (!likeBtn || !dislikeBtn) {
                        console.warn(`Like or dislike button not found for ${id} ${type}`);
                        return;
                    }

                    const likeIcon = likeBtn.querySelector('i');
                    const dislikeIcon = dislikeBtn.querySelector('i');

                    if (reaction === 'like') {
                        if (likeBtn.classList.contains('active')) {
                            // Toggle off
                            likeBtn.classList.remove('active');
                            likeIcon.classList.replace('bi-hand-thumbs-up-fill', 'bi-hand-thumbs-up');
                        } else {
                            // Activate like
                            likeBtn.classList.add('active');
                            likeIcon.classList.replace('bi-hand-thumbs-up', 'bi-hand-thumbs-up-fill');

                            // Deactivate dislike
                            dislikeBtn.classList.remove('active');
                            dislikeIcon.classList.replace('bi-hand-thumbs-down-fill', 'bi-hand-thumbs-down');
                        }
                    } else if (reaction === 'dislike') {
                        if (dislikeBtn.classList.contains('active')) {
                            // Toggle off
                            dislikeBtn.classList.remove('active');
                            dislikeIcon.classList.replace('bi-hand-thumbs-down-fill', 'bi-hand-thumbs-down');
                        } else {
                            // Activate dislike
                            dislikeBtn.classList.add('active');
                            dislikeIcon.classList.replace('bi-hand-thumbs-down', 'bi-hand-thumbs-down-fill');

                            // Deactivate like
                            likeBtn.classList.remove('active');
                            likeIcon.classList.replace('bi-hand-thumbs-up-fill', 'bi-hand-thumbs-up');
                        }
                    }
                } else {
                    alert(data.message || "Unexpected error");
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("Server error or invalid response.");
            });
        });
    });
});
