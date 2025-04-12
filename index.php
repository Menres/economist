<?php
// Устанавливаем заголовок HTTP-ответа, чтобы браузер знал, что это HTML-страница с кодировкой UTF-8 (для корректного отображения русских букв)
header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<!-- Объявляем тип документа — HTML5, это стандарт для современных веб-страниц -->
<html lang="ru">
<!-- Открываем тег <html> и указываем, что основной язык страницы — русский (lang="ru"), это помогает поисковикам и программам экранного чтения -->
<head>
    <!-- Указываем кодировку страницы — UTF-8, чтобы русские буквы отображались корректно -->
    <meta charset="UTF-8">
    <!-- Настраиваем отображение страницы на мобильных устройствах: ширина равна ширине экрана, масштаб 1:1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Задаем заголовок страницы, который отображается на вкладке браузера -->
    <title>Зарплатный лист - Бухгалтерия Про</title>
    <!-- Подключаем стили Bootstrap 5.3.0 через CDN — это готовый CSS-фреймворк для красивого оформления -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Подключаем шрифт Roboto (с начертаниями 400 и 700) из Google Fonts для улучшения дизайна текста -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Начинаем секцию пользовательских стилей CSS -->
    <style>
        /* Задаем стиль для всей страницы: серый фон и шрифт Roboto */
        body { background-color: #E8ECEF; font-family: 'Roboto', sans-serif; }
        /* Ограничиваем ширину контейнера до 700px и добавляем отступ сверху */
        .container { max-width: 700px; margin-top: 30px; }
        /* Стили для заголовка: синий фон, белый текст, отступы, скругленные углы сверху */
        .header { background-color: #003087; color: #FFFFFF; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        /* Добавляем небольшой отступ между параграфами в заголовке */
        .header p { margin: 5px 0; }
        /* Стили для элемента с текущим временем: уменьшаем размер шрифта и задаем светлый цвет */
        #current-time { font-size: 0.9em; color: #E8ECEF; }
        /* Делаем само время (внутри <span>) жирным */
        #current-time span { font-weight: 700; }
        /* Стили для карточки с формой: белый фон, скругленные углы снизу, тень, отступы */
        .card { background-color: #FFFFFF; border-radius: 0 0 10px 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 30px; }
        /* Стили для кнопки "Создать PDF": золотой фон, без границы, жирный текст */
        .btn-primary { background-color: #D4A017; border: none; font-weight: 700; }
        /* Цвет кнопки "Создать PDF" при наведении: чуть темнее золотой */
        .btn-primary:hover { background-color: #b38b14; }
        /* Стили для кнопки "Экспорт в 1С": синий фон, без границы, жирный текст */
        .btn-secondary { background-color: #003087; border: none; font-weight: 700; }
        /* Цвет кнопки "Экспорт в 1С" при наведении: чуть темнее синий */
        .btn-secondary:hover { background-color: #00205b; }
        /* Стили для меток формы: жирный текст, синий цвет */
        .form-label { font-weight: 700; color: #003087; }
        /* Добавляем отступ снизу для сообщений об ошибках */
        .alert { margin-bottom: 20px; }
        /* Скрываем спиннер (индикатор загрузки) по умолчанию */
        .spinner { display: none; }
        /* Стили для таблицы в модальном окне: задаем ширину столбцов */
        #exampleModal .table th:nth-child(1), #exampleModal .table td:nth-child(1) { width: 12%; } /* Период */
        #exampleModal .table th:nth-child(2), #exampleModal .table td:nth-child(2) { width: 25%; } /* ФИО */
        #exampleModal .table th:nth-child(3), #exampleModal .table td:nth-child(3) { width: 20%; } /* Должность */
        #exampleModal .table th:nth-child(4), #exampleModal .table td:nth-child(4) { width: 10%; } /* Оклад */
        #exampleModal .table th:nth-child(5), #exampleModal .table td:nth-child(5) { width: 10%; } /* Налог */
        #exampleModal .table th:nth-child(6), #exampleModal .table td:nth-child(6) { width: 10%; } /* Премии */
        #exampleModal .table th:nth-child(7), #exampleModal .table td:nth-child(7) { width: 13%; } /* Итого */
    </style>
</head>
<body>
    <!-- Создаем контейнер для содержимого страницы, который центрирует элементы -->
    <div class="container">
        <!-- Заголовок страницы: синий блок с текстом -->
        <div class="header">
            <!-- Заголовок "Бухгалтерия Про" -->
            <h1>Бухгалтерия Про</h1>
            <!-- Подзаголовок "Создание зарплатных листов" -->
            <p>Создание зарплатных листов</p>
            <!-- Элемент для отображения текущего времени, будет обновляться JavaScript -->
            <p id="current-time">Текущее время: <span>00:00:00</span></p>
        </div>
        <!-- Карточка с формой для загрузки данных -->
        <div class="card">
            <!-- Заголовок внутри карточки -->
            <h3 class="mb-4">Загрузите данные</h3>
            <!-- Проверяем, есть ли ошибка в URL (например, ?error=Сообщение), и если есть — показываем её -->
            <?php if (isset($_GET['error'])): ?>
                <!-- Сообщение об ошибке в красном блоке (Bootstrap класс alert-danger) -->
                <div class="alert alert-danger" role="alert">
                    <!-- Выводим сообщение об ошибке, экранируя специальные символы для безопасности -->
                    <?php echo htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            <!-- Форма для загрузки данных, отправляет данные в process.php методом POST -->
            <form action="process.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <!-- Поле для выбора периода отчётности -->
                <div class="mb-3">
                    <!-- Метка для поля -->
                    <label for="reportPeriod" class="form-label">Выберите период отчётности</label>
                    <!-- Поле ввода типа "month" для выбора месяца и года, по умолчанию — текущий месяц -->
                    <input type="month" class="form-control" id="reportPeriod" name="reportPeriod" value="<?php echo date('Y-m'); ?>" required>
                </div>
                <!-- Поле для выбора шаблона PDF -->
                <div class="mb-3">
                    <!-- Метка для поля -->
                    <label for="template" class="form-label">Шаблон PDF</label>
                    <!-- Выпадающий список с тремя вариантами шаблонов -->
                    <select class="form-control" id="template" name="template">
                        <option value="classic">Классический</option>
                        <option value="modern">Современный</option>
                        <option value="minimal">Минималистичный</option>
                    </select>
                </div>
                <!-- Поле для загрузки сертификата для цифровой подписи -->
                <div class="mb-3">
                    <!-- Метка для поля -->
                    <label for="certificate" class="form-label">Сертификат для цифровой подписи (.p12 файл)</label>
                    <!-- Поле для загрузки файла, принимает только файлы .p12 -->
                    <input type="file" class="form-control" id="certificate" name="certificate" accept=".p12">
                    <!-- Подсказка, что это поле необязательное -->
                    <small class="form-text text-muted">Оставьте пустым, если подпись не нужна.</small>
                </div>
                <!-- Поле для ввода максимального оклада -->
                <div class="mb-3">
                    <!-- Метка для поля -->
                    <label for="salaryLimit" class="form-label">Максимальный оклад для уведомления</label>
                    <!-- Поле ввода числа, по умолчанию 100000 -->
                    <input type="number" class="form-control" id="salaryLimit" name="salaryLimit" min="0" value="100000">
                </div>
                <!-- Поле для загрузки файла с данными -->
                <div class="mb-3">
                    <!-- Метка для поля -->
                    <label for="file" class="form-label">Выберите Excel, Word или CSV файл</label>
                    <!-- Поле для загрузки файла, принимает только файлы .xlsx, .xls, .docx, .csv, обязательное -->
                    <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.docx,.csv" required>
                    <!-- Подсказка о формате файла и ссылка на пример -->
                    <small class="form-text text-muted">
                        Поддерживаемые форматы: .xlsx, .xls, .docx, .csv. Файл должен содержать таблицу с 7 колонками: Период (в формате ГГГГ-ММ, например, 2025-04), ФИО, Должность, Оклад, Налог, Премии, Итого.
                        <!-- Ссылка для открытия модального окна с примером таблицы -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Посмотреть пример</a>
                    </small>
                </div>
                <!-- Скрытое поле для передачи действия (какая кнопка была нажата) -->
                <input type="hidden" name="action" id="formAction" value="">
                <!-- Контейнер для двух кнопок -->
                <div class="d-flex gap-2">
                    <!-- Кнопка для создания PDF -->
                    <button type="submit" class="btn btn-primary w-50" id="createPdfBtn">Создать PDF</button>
                    <!-- Кнопка для экспорта в CSV -->
                    <button type="submit" class="btn btn-secondary w-50" id="exportCsvBtn">Экспорт в 1С (CSV)</button>
                </div>
                <!-- Спиннер (индикатор загрузки), показывается во время отправки формы -->
                <div class="spinner mt-3 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Модальное окно для отображения примера таблицы -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <!-- Контейнер модального окна с увеличенной шириной (modal-lg) -->
        <div class="modal-dialog modal-lg">
            <!-- Содержимое модального окна -->
            <div class="modal-content">
                <!-- Заголовок модального окна -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Пример таблицы</h5>
                    <!-- Кнопка для закрытия модального окна -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Тело модального окна с таблицей -->
                <div class="modal-body">
                    <!-- Таблица с примерами данных -->
                    <table class="table table-bordered">
                        <!-- Заголовки таблицы -->
                        <thead>
                            <tr>
                                <th>Период</th>
                                <th>ФИО</th>
                                <th>Должность</th>
                                <th>Оклад</th>
                                <th>Налог</th>
                                <th>Премии</th>
                                <th>Итого</th>
                            </tr>
                        </thead>
                        <!-- Данные таблицы -->
                        <tbody>
                            <tr>
                                <td>2025-04</td>
                                <td>Иванов Иван Иванович</td>
                                <td>Бухгалтер</td>
                                <td>50000</td>
                                <td>6500</td>
                                <td>5000</td>
                                <td>48500</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Нижняя часть модального окна с кнопкой -->
                <div class="modal-footer">
                    <!-- Кнопка для закрытия модального окна -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Подключаем JavaScript Bootstrap для работы модального окна, форм и других интерактивных элементов -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Начинаем пользовательский JavaScript -->
    <script>
        // Находим элементы формы и кнопок по их ID
        const form = document.getElementById('uploadForm');
        const fileInput = document.getElementById('file');
        const createPdfBtn = document.getElementById('createPdfBtn');
        const exportCsvBtn = document.getElementById('exportCsvBtn');
        const formAction = document.getElementById('formAction');
        const spinner = document.querySelector('.spinner');

        // Добавляем обработчик события на отправку формы
        form.addEventListener('submit', function(e) {
            // Проверяем, выбран ли файл
            const file = fileInput.files[0];
            if (file) {
                // Задаем максимальный размер файла — 10 МБ
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    // Если файл слишком большой, отменяем отправку формы и показываем предупреждение
                    e.preventDefault();
                    alert('Файл слишком большой. Максимальный размер: 10 МБ.');
                    return;
                }
                // Проверяем расширение файла
                const validExtensions = ['xlsx', 'xls', 'docx', 'csv'];
                const ext = file.name.split('.').pop().toLowerCase();
                if (!validExtensions.includes(ext)) {
                    // Если расширение не поддерживается, отменяем отправку и показываем предупреждение
                    e.preventDefault();
                    alert('Неподдерживаемый формат файла. Выберите .xlsx, .xls, .docx или .csv.');
                    return;
                }
            }
            // Отключаем кнопки и показываем спиннер во время отправки
            createPdfBtn.disabled = true;
            exportCsvBtn.disabled = true;
            spinner.style.display = 'block';
        });

        // Добавляем обработчик на кнопку "Создать PDF"
        createPdfBtn.addEventListener('click', function() {
            // Устанавливаем значение скрытого поля action для отправки в process.php
            formAction.value = 'create_pdf';
        });

        // Добавляем обработчик на кнопку "Экспорт в 1С"
        exportCsvBtn.addEventListener('click', function() {
            // Устанавливаем значение скрытого поля action для отправки в process.php
            formAction.value = 'export_csv';
        });

        // После отправки формы сбрасываем её
        form.addEventListener('submit', function() {
            setTimeout(() => {
                // Сбрасываем форму
                this.reset();
                // Включаем кнопки обратно
                createPdfBtn.disabled = false;
                exportCsvBtn.disabled = false;
                // Скрываем спиннер
                spinner.style.display = 'none';
            }, 100);
        });

        // Если в URL есть параметр error, убираем его из адресной строки
        if (window.location.search.includes('error=')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // Функция для обновления текущего времени
        function updateTime() {
            const now = new Date(); // Получаем текущую дату и время
            const hours = String(now.getHours()).padStart(2, '0'); // Получаем часы и добавляем ведущий ноль
            const minutes = String(now.getMinutes()).padStart(2, '0'); // Получаем минуты и добавляем ведущий ноль
            const seconds = String(now.getSeconds()).padStart(2, '0'); // Получаем секунды и добавляем ведущий ноль
            const timeString = `${hours}:${minutes}:${seconds}`; // Формируем строку времени
            // Обновляем элемент с ID current-time
            document.getElementById('current-time').innerHTML = `Текущее время: <span>${timeString}</span>`;
        }

        // Вызываем функцию updateTime сразу при загрузке страницы
        updateTime();

        // Обновляем время каждую секунду
        setInterval(updateTime, 1000);
    </script>
</body>
</html>