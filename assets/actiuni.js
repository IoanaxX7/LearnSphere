document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.reaction-btn').forEach(button => {
        button.addEventListener('click', function () {
            const materialID = this.getAttribute('data-id');
            const reaction = this.getAttribute('data-reaction');

            fetch('actiuni/actiune.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `materialID=${materialID}&reaction=${reaction}`
            })
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        // Update counts
                        document.getElementById(`like-count-${materialID}`).innerText = data.likes;
                        document.getElementById(`dislike-count-${materialID}`).innerText = data.dislikes;

                        const likeBtn = document.querySelector(`.reaction-btn.like[data-id="${materialID}"]`);
                        const dislikeBtn = document.querySelector(`.reaction-btn.dislike[data-id="${materialID}"]`);
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
                } catch (e) {
                    console.error("Invalid JSON:", text);
                    alert("Server error or invalid response.");
                }
            });
        });
    });
});
