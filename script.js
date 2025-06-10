// script.js
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Обработчики для кнопок входа и регистрации
document.getElementById('loginBtn')?.addEventListener('click', () => showModal('loginModal'));
document.getElementById('registerBtn')?.addEventListener('click', () => showModal('registerModal'));

// Закрытие модальных окон при клике вне области
window.onclick = function(event) {
    if (event.target.classList.contains('fixed')) {
        document.querySelectorAll('.fixed.inset-0').forEach(modal => {
            modal.classList.add('hidden');
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Плавная прокрутка для якорных ссылок
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href");
            if (targetId === "#") return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerHeight = document.getElementById("header").offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                window.scrollTo({ top: targetPosition, behavior: "smooth" });
                if (mobileMenu.classList.contains("open")) {
                    mobileMenu.classList.remove("open");
                }
            }
        });
    });

    // Мобильное меню
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const closeMenuButton = document.getElementById("close-menu");
    const mobileMenu = document.getElementById("mobile-menu");
    mobileMenuButton?.addEventListener("click", function () {
        mobileMenu.classList.add("open");
    });
    closeMenuButton?.addEventListener("click", function () {
        mobileMenu.classList.remove("open");
    });

    // Активация пунктов меню при скролле
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");
    function highlightNavLink() {
        const scrollPosition = window.scrollY;
        const headerHeight = document.getElementById("header").offsetHeight;
        sections.forEach((section) => {
            const sectionTop = section.offsetTop - headerHeight - 100;
            const sectionBottom = sectionTop + section.offsetHeight;
            const sectionId = section.getAttribute("id");
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                navLinks.forEach((link) => {
                    link.classList.remove("active");
                    if (link.getAttribute("href") === `#${sectionId}`) {
                        link.classList.add("active");
                    }
                });
            }
        });
    }
    window.addEventListener("scroll", highlightNavLink);
    highlightNavLink();

    // Кнопка "Наверх"
    const backToTopButton = document.getElementById("back-to-top");
    function toggleBackToTopButton() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add("show");
        } else {
            backToTopButton.classList.remove("show");
        }
    }
    window.addEventListener("scroll", toggleBackToTopButton);
    backToTopButton?.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // Кастомные радио-кнопки
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    radioButtons.forEach((radio) => {
        radio.addEventListener("change", function () {
            const radioGroup = document.querySelectorAll(`input[name="${this.name}"]`);
            radioGroup.forEach((btn) => {
                const indicator = btn.nextElementSibling.querySelector("span");
                if (btn.checked) {
                    indicator.style.transform = "scale(0.75)";
                } else {
                    indicator.style.transform = "scale(0)";
                }
            });
        });
    });

    // Кастомные чекбоксы
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const indicator = this.nextElementSibling.querySelector("span");
            if (this.checked) {
                indicator.style.opacity = "1";
            } else {
                indicator.style.opacity = "0";
            }
        });
    });
});
// Обработка форм
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    // Показать сообщение об успехе
                }
            } else {
                // Показать ошибки
                Object.entries(result.errors).forEach(([field, message]) => {
                    const errorElement = document.getElementById(`${field}Error`);
                    if (errorElement) {
                        errorElement.textContent = message;
                    }
                });
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    });
});

// Обработка формы записи
document.getElementById('appointmentForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = {
        name: formData.get('name'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        date: formData.get('date'),
        message: formData.get('message')
    };

    try {
        const response = await fetch('order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert('Заявка успешно отправлена!');
            e.target.reset();
        } else {
            Object.entries(result.errors).forEach(([field, message]) => {
                const errorElement = document.getElementById(`${field}Error`);
                if (errorElement) {
                    errorElement.textContent = message;
                }
            });
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке');
    }
});

// Удаляем существующие обработчики перед добавлением нового
const appointmentForm = document.getElementById('appointmentForm');
if (appointmentForm) {
    appointmentForm.replaceWith(appointmentForm.cloneNode(true));
}

// Добавляем новый обработчик
document.getElementById('appointmentForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');

    try {
        // Блокируем кнопку
        submitButton.disabled = true;
        submitButton.innerHTML = 'Отправка...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            alert('Заявка успешно отправлена!');
            form.reset();
        } else {
            console.error('Ошибка сервера:', result);
        }
    } catch (error) {
        console.error('Ошибка сети:', error);
    } finally {
        // Разблокируем кнопку
        submitButton.disabled = false;
        submitButton.innerHTML = 'Отправить заявку';
    }
});