document.addEventListener("DOMContentLoaded", function () {
    // =========================
    // Theme toggle
    // =========================
    const themeToggle = document.getElementById("theme-toggle");
    if (themeToggle) {
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-theme");
            themeToggle.textContent = "☀️ Светлая тема";
        }

        themeToggle.addEventListener("click", () => {
            document.body.classList.toggle("dark-theme");
            if (document.body.classList.contains("dark-theme")) {
                localStorage.setItem("theme", "dark");
                themeToggle.textContent = "☀️ Светлая тема";
            } else {
                localStorage.setItem("theme", "light");
                themeToggle.textContent = "🌙 Тёмная тема";
            }
        });
    }

    // =========================
    // Logout confirmation
    // =========================
    const logoutLink = document.querySelector('a[href*="logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener("click", function (e) {
            if (!confirm("Вы уверены, что хотите выйти?")) {
                e.preventDefault();
            }
        });
    }

    // =========================
    // Button click animation
    // =========================
    const buttons = document.querySelectorAll("button");
    buttons.forEach(button => {
        button.addEventListener("click", () => {
            button.style.transform = "scale(0.95)";
            setTimeout(() => { button.style.transform = "scale(1)"; }, 100);
        });
    });

    // =========================
    // Fade-in for book/course cards
    // =========================
    const cards = document.querySelectorAll(".book-card, .course-card");
    cards.forEach((card, i) => {
        card.style.opacity = "0";
        setTimeout(() => {
            card.style.transition = "opacity 0.5s ease";
            card.style.opacity = "1";
        }, 100 * i);
    });

    // =========================
    // Live search with highlight
    // =========================
    const searchBox = document.getElementById("search-box");
    const resultsDiv = document.getElementById("search-results");

    if (searchBox) {
        searchBox.addEventListener("input", function () {
            const query = searchBox.value.trim();
            if (!query) {
                resultsDiv.innerHTML = "";
                return;
            }

            fetch(`/ajax_search.php?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    let html = "";
                    if (data.length === 0) {
                        html = "<p>Ничего не найдено</p>";
                    } else {
                        data.forEach(item => {
                            const regex = new RegExp(`(${query})`, 'gi');

                            if (item.type === "course") {
                                const name = item.name.replace(regex, `<mark>$1</mark>`);
                                const desc = item.description.replace(regex, `<mark>$1</mark>`);
                                html += `<div class="course-card">
                                            <a href="discipline.php?id=${item.id}">
                                                <h3>${name} (Дисциплина)</h3>
                                            </a>
                                            <p>${desc}</p>
                                        </div>`;
                            } else {
                                const title = item.title.replace(regex, `<mark>$1</mark>`);
                                const author = item.author.replace(regex, `<mark>$1</mark>`);
                                const course_name = item.course_name.replace(regex, `<mark>$1</mark>`);
                                const desc = item.description.replace(regex, `<mark>$1</mark>`);
                                html += `<div class="book-card">
                                            <h3>${title} (Книга)</h3>
                                            <p><strong>Автор:</strong> ${author}</p>
                                            <p><strong>Дисциплина:</strong> ${course_name}</p>
                                            <p>${desc}</p>
                                        </div>`;
                            }
                        });
                    }
                    resultsDiv.innerHTML = html;

                    // Плавное появление новых карточек
                    const newCards = resultsDiv.querySelectorAll(".book-card, .course-card");
                    newCards.forEach((card, i) => {
                        card.style.opacity = "0";
                        setTimeout(() => {
                            card.style.transition = "opacity 0.5s ease";
                            card.style.opacity = "1";
                        }, 100 * i);
                    });
                });
        });
    }
});