# TaskFlow - Менеджер задач
TaskFlow - это веб-приложение для управления задачами, построенное на Laravel и Vue.js.

## Функциональность

- Создание, редактирование и удаление задач
- Управление статусами задач
- Установка дедлайнов
- Адаптивный интерфейс
- Мгновенное обновление без перезагрузки страницы

## Технологии

- Laravel 11
- Vue.js 3
- CSS
- Axios
- Vite

## Требования

- PHP >= 8.2
- Node.js >= 16
- Composer
- MySQL
## Установка

1. Клонируйте репозиторий:
```bash
git clone https://github.com/your-username/taskflow.git
cd taskflow
```

2. Установите зависимости:
```bash
composer install
npm install
```

3. Настройте окружение:
```bash
cp .env.example .env
php artisan key:generate
```

4. Создайте базу данных MySQL:
```bash
mysql -u root -p
CREATE DATABASE taskflow;
exit;
```

5. Настройте базу данных в .env файле:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=
```

6. Проверьте подключение к базе данных:
```bash
php artisan migrate:status
```

7. Выполните миграции:
```bash
php artisan migrate
```

8. Соберите фронтенд:
```bash
npm run build
```

9. Запустите сервер (в первом терминале):
```bash
php artisan serve
```

10. Запустите сборку фронтенда в режиме разработки (в другом терминале):
```bash
npm run dev
```

11. Откройте http://localhost:8000 в браузере


