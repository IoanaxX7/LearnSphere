document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("comment-form");
    if (!form) return;

    const textarea = document.getElementById("comentariu");
    const materialID = document.getElementById("materialID").value;
    const commentsList = document.getElementById("comments-list");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const comment = textarea.value.trim();
        if (comment === "") return;

        fetch("../actiuni/adauga_comentariu.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `materialID=${encodeURIComponent(materialID)}&comentariu=${encodeURIComponent(comment)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentDiv = document.createElement("div");
                commentDiv.classList.add("comment");
                commentDiv.innerHTML = `
                    <div class="profile-pic">
                        <img src="uploads/${data.username}/${data.pozaProfil}" alt="Profile picture">
                    </div>
                    <div class="comment-body">
                        <span class="username">${data.username}</span>
                        <span class="time">${data.timestamp}</span>
                        <p>${data.comentariu.replace(/\n/g, "<br>")}</p>
                    </div>
                `;
                commentsList.appendChild(commentDiv);
                textarea.value = "";
            } else {
                alert(data.message || "Eroare la trimiterea comentariului.");
            }
        })
        .catch(err => {
            console.error("Eroare AJAX:", err);
            alert("A apărut o eroare la trimiterea comentariului.");
        });
    });
});
