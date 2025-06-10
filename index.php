<?php
session_start();
require 'config.php';

// Обработка ошибок
$errors = [];
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// Проверка авторизации
$isLoggedIn = isset($_SESSION['user']);
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : 'guest';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личная карточка доктора</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#1E3D59", secondary: "#17A2B8" },
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        "2xl": "24px",
                        "3xl": "32px",
                        full: "9999px",
                        button: "8px",
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (!empty($errors)): ?>
    <div class="fixed top-4 right-4 z-50 space-y-2">
        <?php foreach ($errors as $error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Хедер -->
<header id="header" class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
    <?php if ($userRole === 'admin'): ?>
        <a href="admin.php" class="bg-gray-800 text-white px-4 py-2 !rounded-button">Админ-панель</a>
    <?php endif; ?>
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/" class="text-primary font-['Pacifico'] text-2xl transition-transform duration-300 hover:scale-110 inline-block">logo</a>

        <nav class="hidden md:flex items-center space-x-8">
            <a href="#education" class="nav-link text-gray-700 hover:text-primary transition-colors">Образование</a>
            <a href="#experience" class="nav-link text-gray-700 hover:text-primary transition-colors">Опыт работы</a>
            <a href="#specialization" class="nav-link text-gray-700 hover:text-primary transition-colors">Специализация</a>
            <a href="#contacts" class="nav-link text-gray-700 hover:text-primary transition-colors">Контакты</a>
        </nav>

        <div class="hidden md:flex items-center space-x-4">
            <?php if ($isLoggedIn): ?>
                <span class="text-gray-700"><?= htmlspecialchars($_SESSION['user']['email']) ?></span>
                <?php if ($userRole === 'admin'): ?>
                    <a href="admin.php" class="bg-secondary text-white px-4 py-2 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">Админ-панель</a>
                <?php endif; ?>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">Выход</a>
            <?php else: ?>
                <button id="registerBtn" class="bg-[#87CEEB] text-white px-4 py-2 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">Регистрация</button>
                <button id="loginBtn" class="bg-[#98FB98] text-gray-700 px-4 py-2 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">Вход</button>
            <?php endif; ?>
            <a href="#appointment" class="bg-primary text-white px-4 py-2 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">Записаться</a>
        </div>

        <button id="mobile-menu-button" class="md:hidden w-10 h-10 flex items-center justify-center text-primary">
            <i class="ri-menu-line ri-2x"></i>
        </button>
    </div>
</header>

<!-- Мобильное меню -->
<div id="mobile-menu" class="mobile-menu fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50 pt-20 px-4 md:hidden">
    <button id="close-menu" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center text-primary">
        <i class="ri-close-line ri-2x"></i>
    </button>
    <nav class="flex flex-col space-y-6">
        <a href="#education" class="text-gray-700 hover:text-primary transition-colors">Образование</a>
        <a href="#experience" class="text-gray-700 hover:text-primary transition-colors">Опыт работы</a>
        <a href="#specialization" class="text-gray-700 hover:text-primary transition-colors">Специализация</a>
        <a href="#contacts" class="text-gray-700 hover:text-primary transition-colors">Контакты</a>
        <a href="#appointment" class="bg-primary text-white px-4 py-2 !rounded-button text-center whitespace-nowrap hover:bg-opacity-90 transition-colors">Записаться на прием</a>
    </nav>
</div>

<!-- Основной контент -->
<main class="mt-20 pb-16">
    <!-- Первый экран -->
    <section id="hero" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="md:w-1/2">
                    <img src="https://readdy.ai/api/search-image?query=professional%20portrait%20of%20a%20doctor%2C%20female%2C%2040%20years%20old%2C%20wearing%20white%20coat%2C%20with%20stethoscope%2C%20confident%20and%20friendly%20expression%2C%20clean%20medical%20office%20background%2C%20high%20quality%20professional%20photography%2C%20soft%20lighting&width=600&height=800&seq=1&orientation=portrait"
                         alt="Доктор Анна Петрова"
                         class="rounded-lg shadow-lg object-cover object-top w-full max-w-md mx-auto">
                </div>
                <div class="md:w-1/2">
                    <h1 class="text-3xl md:text-4xl font-bold text-primary mb-4">Доктор Анна Петрова</h1>
                    <h2 class="text-xl text-secondary mb-6">Кардиолог высшей категории, к.м.н.</h2>
                    <p class="text-gray-700 mb-6">Более 15 лет опыта в диагностике и лечении сердечно-сосудистых заболеваний. Специализируюсь на профилактической кардиологии и современных методах лечения гипертонии.</p>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center text-primary">
                                <i class="ri-calendar-check-line ri-lg"></i>
                            </div>
                            <span>Стаж работы: 15 лет</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center text-primary">
                                <i class="ri-user-heart-line ri-lg"></i>
                            </div>
                            <span>Принято пациентов: 10000+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Образование -->
    <section id="education" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-primary mb-8 text-center">Образование</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded shadow-md">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-primary bg-opacity-10 rounded-full flex-shrink-0">
                            <i class="ri-graduation-cap-line text-primary ri-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Высшее образование</h3>
                            <p class="text-gray-700 mb-2">2003-2009 гг.</p>
                            <p class="text-gray-700">Первый Московский государственный медицинский университет имени И.М. Сеченова, лечебный факультет</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-primary bg-opacity-10 rounded-full flex-shrink-0">
                            <i class="ri-hospital-line text-primary ri-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Ординатура</h3>
                            <p class="text-gray-700 mb-2">2009-2011 гг.</p>
                            <p class="text-gray-700">Российский кардиологический научно-производственный комплекс, специальность "Кардиология"</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-primary bg-opacity-10 rounded-full flex-shrink-0">
                            <i class="ri-book-open-line text-primary ri-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Кандидатская диссертация</h3>
                            <p class="text-gray-700 mb-2">2014 г.</p>
                            <p class="text-gray-700">Тема: "Современные подходы к диагностике и лечению артериальной гипертензии у пациентов с метаболическим синдромом"</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-primary bg-opacity-10 rounded-full flex-shrink-0">
                            <i class="ri-award-line text-primary ri-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Повышение квалификации</h3>
                            <p class="text-gray-700 mb-2">2020 г.</p>
                            <p class="text-gray-700">Европейский конгресс кардиологов, Амстердам. Сертификат по современным методам диагностики ишемической болезни сердца</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Опыт работы -->
    <section id="experience" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-primary mb-8 text-center">Опыт работы</h2>
            <div class="relative max-w-3xl mx-auto">
                <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-0.5 bg-gray-200"></div>
                <div class="relative z-10">
                    <!-- Элементы таймлайна -->
                    <div class="flex flex-col md:flex-row items-center mb-12">
                        <div class="md:w-1/2 md:pr-8 md:text-right mb-4 md:mb-0">
                            <h3 class="font-bold text-lg">Городская клиническая больница №1</h3>
                            <p class="text-gray-600">2011-2014 гг.</p>
                            <p class="text-gray-700">Врач-кардиолог отделения кардиологии</p>
                        </div>
                        <div class="w-10 h-10 flex items-center justify-center bg-primary rounded-full z-10 mx-4">
                            <i class="ri-heart-pulse-line text-white"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8 md:text-left"></div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center mb-12">
                        <div class="md:w-1/2 md:pr-8 md:text-right order-1 md:order-1"></div>
                        <div class="w-10 h-10 flex items-center justify-center bg-primary rounded-full z-10 mx-4 order-2">
                            <i class="ri-heart-pulse-line text-white"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8 md:text-left order-3">
                            <h3 class="font-bold text-lg">Кардиологический центр "Здоровое сердце"</h3>
                            <p class="text-gray-600">2014-2019 гг.</p>
                            <p class="text-gray-700">Ведущий кардиолог, руководитель отделения функциональной диагностики</p>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row items-center mb-12">
                        <div class="md:w-1/2 md:pr-8 md:text-right mb-4 md:mb-0">
                            <h3 class="font-bold text-lg">Медицинский центр "Академия Здоровья"</h3>
                            <p class="text-gray-600">2019-2022 гг.</p>
                            <p class="text-gray-700">Заведующая кардиологическим отделением</p>
                        </div>
                        <div class="w-10 h-10 flex items-center justify-center bg-primary rounded-full z-10 mx-4">
                            <i class="ri-heart-pulse-line text-white"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8 md:text-left"></div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-8 md:text-right order-1 md:order-1"></div>
                        <div class="w-10 h-10 flex items-center justify-center bg-secondary rounded-full z-10 mx-4 order-2">
                            <i class="ri-heart-pulse-line text-white"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8 md:text-left order-3">
                            <h3 class="font-bold text-lg">Клиника современной кардиологии</h3>
                            <p class="text-gray-600">2022 г. - настоящее время</p>
                            <p class="text-gray-700">Главный врач, кардиолог высшей категории</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Специализация -->
    <section id="specialization" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-primary mb-8 text-center">Специализация</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded shadow-md">
                    <div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-4 mx-auto">
                        <i class="ri-heart-line text-primary ri-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-center">Ишемическая болезнь сердца</h3>
                    <p class="text-gray-700 text-center">Диагностика и лечение стенокардии, постинфарктных состояний, профилактика инфаркта миокарда</p>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-4 mx-auto">
                        <i class="ri-pulse-line text-primary ri-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-center">Артериальная гипертензия</h3>
                    <p class="text-gray-700 text-center">Подбор индивидуальной терапии, контроль давления, профилактика осложнений гипертонии</p>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-4 mx-auto">
                        <i class="ri-heart-add-line text-primary ri-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-center">Нарушения ритма сердца</h3>
                    <p class="text-gray-700 text-center">Диагностика и лечение аритмий, мерцательной аритмии, экстрасистолии</p>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-4 mx-auto">
                        <i class="ri-virus-line text-primary ri-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-center">Кардиомиопатии</h3>
                    <p class="text-gray-700 text-center">Диагностика и лечение различных видов кардиомиопатий, включая дилатационную и гипертрофическую</p>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-4 mx-auto">
                        <i class="ri-microscope-line text-primary ri-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-center">Функциональная диагностика</h3>
                    <p class="text-gray-700 text-center">ЭКГ, эхокардиография, холтеровское мониторирование, стресс-тесты</p>
                </div>

                <div class="bg-white p-6 rounded shadow-md">
                    <div class="w-16 h-16 flex items-center justify-center bg-primary bg-opacity-10 rounded-full mb-4 mx-auto">
                        <i class="ri-mental-health-line text-primary ri-xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-center">Профилактическая кардиология</h3>
                    <p class="text-gray-700 text-center">Оценка сердечно-сосудистых рисков, разработка программ профилактики заболеваний сердца</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Контакты -->
    <section id="contacts" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-primary mb-8 text-center">Контакты</h2>
            <div class="max-w-2xl mx-auto">
                <div class="bg-white p-6 rounded shadow-md mb-6">
                    <h3 class="font-bold text-lg mb-4">Информация для связи</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center text-primary">
                                <i class="ri-map-pin-line ri-lg"></i>
                            </div>
                            <span>г. Пенза, ул. Тарханова, д. 10В, Клиника современной кардиологии</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center text-primary">
                                <i class="ri-phone-line ri-lg"></i>
                            </div>
                            <span>+7 (495) 123-45-67</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center text-primary">
                                <i class="ri-mail-line ri-lg"></i>
                            </div>
                            <span>dr.petrova@cardio-clinic.ru</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 flex items-center justify-center text-primary">
                                <i class="ri-time-line ri-lg"></i>
                            </div>
                            <div>
                                <p>Пн-Пт: 9:00 - 18:00</p>
                                <p>Сб: 10:00 - 15:00</p>
                                <p>Вс: выходной</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded shadow-md">
                    <h3 class="font-bold text-lg mb-4">Социальные сети</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-opacity-90 transition-colors">
                            <i class="ri-telegram-line"></i>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-opacity-90 transition-colors">
                            <i class="ri-vk-line"></i>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-opacity-90 transition-colors">
                            <i class="ri-youtube-line"></i>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center bg-primary text-white rounded-full hover:bg-opacity-90 transition-colors">
                            <i class="ri-instagram-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Запись на прием -->
    <section id="appointment" class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="section-title text-primary mb-8 text-center">Записаться на прием</h2>
            <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow-md">
                <form id="appointmentForm" action="order.php" method="POST">
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-gray-700 mb-2">Ваше имя</label>
                            <input type="text" id="name" name="name"
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            <div id="nameError" class="text-red-500 text-sm mt-1"></div>
                        </div>

                        <div>
                            <label for="phone" class="block text-gray-700 mb-2">Телефон</label>
                            <input type="tel" id="phone" name="phone"
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                            <div id="phoneError" class="text-red-500 text-sm mt-1"></div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                        <div id="emailError" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-6">
                        <label for="date" class="block text-gray-700 mb-2">Предпочтительная дата</label>
                        <input type="date" id="date" name="date"
                               class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               required>
                        <div id="dateError" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 mb-2">Дополнительная информация</label>
                        <textarea id="message" name="message" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        <div id="messageError" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="agreement" class="hidden peer" required>
                            <span class="w-5 h-5 inline-block border border-gray-300 rounded mr-2 flex-shrink-0 relative">
                            <span class="absolute inset-0 rounded bg-primary text-white flex items-center justify-center opacity-0 peer-checked:opacity-100">
                                <i class="ri-check-line ri-sm"></i>
                            </span>
                        </span>
                            <span class="text-gray-700">Я согласен на обработку персональных данных</span>
                        </label>
                        <div id="agreementError" class="text-red-500 text-sm mt-1"></div>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary text-white px-6 py-3 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">
                        Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<!-- Модальное окно входа -->
<div id="loginModal" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeModal('loginModal')">
            <i class="ri-close-line ri-lg"></i>
        </button>
        <h2 class="text-2xl font-bold text-primary mb-6 text-center">Вход</h2>
        <form id="loginForm" action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="loginEmail" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="loginEmail" name="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            <div>
                <label for="loginPassword" class="block text-gray-700 mb-2">Пароль</label>
                <input type="password" id="loginPassword" name="password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
            </div>
            <button type="submit" class="w-full bg-primary text-white px-6 py-3 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-colors">Войти</button>
        </form>
        <p class="mt-4 text-center">
            <a href="#" class="text-primary hover:text-opacity-80" onclick="showModal('registerModal')">Не зарегистрирован?</a>
        </p>
    </div>
</div>

<!-- Модальное окно регистрации -->
<div id="registerModal" class="fixed inset-0 hidden flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4 relative">
        <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="closeModal('registerModal')">
            <i class="ri-close-line ri-lg"></i>
        </button>
        <h2 class="text-2xl font-bold text-primary mb-6 text-center">Регистрация</h2>
        <form id="registerForm" method="post" action="register.php" class="space-y-4">
            <div>
                <label for="registerName" class="block text-gray-700 mb-2">Имя</label>
                <input type="text" id="registerName" name="name" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <div id="nameError" class="text-red-500 text-sm mt-1"></div>
            </div>

            <div>
                <label for="registerEmail" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="registerEmail" name="email" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <div id="emailError" class="text-red-500 text-sm mt-1"></div>
            </div>

            <div>
                <label for="registerPassword" class="block text-gray-700 mb-2">Пароль</label>
                <input type="password" id="registerPassword" name="password" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <div id="passwordError" class="text-red-500 text-sm mt-1"></div>
            </div>

            <div>
                <label for="registerPasswordConfirm" class="block text-gray-700 mb-2">Подтвердите пароль</label>
                <input type="password" id="registerPasswordConfirm" name="password_confirm" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                <div id="passwordConfirmError" class="text-red-500 text-sm mt-1"></div>
            </div>

            <button type="submit" class="w-full bg-primary text-white px-6 py-3 rounded hover:bg-opacity-90">
                Зарегистрироваться
            </button>
        </form>
    </div>
</div>

<!-- Кнопка наверх -->
<button id="back-to-top" class="back-to-top fixed bottom-6 right-6 w-12 h-12 bg-primary text-white rounded-full shadow-lg flex items-center justify-center opacity-0">
    <i class="ri-arrow-up-line ri-lg"></i>
</button>

<script src="script.js"></script>
</body>
</html>