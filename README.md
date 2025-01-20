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
git clone https://github.com/yourusername/taskflow.git
cd taskflow
```

2. Установите зависимости:

```bash
composer install
npm install
```
3. Настройки окружения

```bash
cp .env.example .env
```

4. Создайте базу данных и настройте подключение в файле `.env`:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=
```

5. Запустите миграции:

```bash
php artisan migrate
```

6. Запустите сервер разработки:

```bash
php artisan serve
```

7. Запустите Vue.js:

```bash
npm run dev
```

8. Откройте браузер и перейдите по адресу `http://localhost:8000`
