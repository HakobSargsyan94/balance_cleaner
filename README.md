
# Balance History Cleaner

Проект Laravel для очистки дубликатов в таблице `balance_history`.  
Команда оставляет по одной записи на каждый день для каждой пары `account_id` и `currency_id`, удаляя остальные.

---

## Требования

- Docker и Docker Compose
- PHP (через Docker, локальный PHP не обязателен)
- PostgreSQL (в Docker контейнере)

---

## Быстрый старт

### 1. Клонируйте репозиторий

```bash
git clone https://github.com/HakobSargsyan94/balance_cleaner.git
cd balance_cleaner
```

### 2. Запустите Docker контейнеры

```bash
docker-compose up -d --build
```

### 3. Выполните миграции и заполните тестовыми данными

```bash
docker-compose exec app php artisan migrate:fresh --seed
```

### 4. Проверьте данные (опционально)

```bash
docker-compose exec app psql -U laravel -d laravel -c "SELECT DATE(created_at), account_id, currency_id, COUNT(*) FROM balance_history GROUP BY 1,2,3 ORDER BY 1 DESC LIMIT 10;"
```

### 5. Запустите команду очистки

```bash
docker-compose exec app php artisan balance-history:clean
```

### 6. Проверьте результат (опционально)

```bash
docker-compose exec app psql -U laravel -d laravel -c "SELECT DATE(created_at), account_id, currency_id, COUNT(*) FROM balance_history GROUP BY 1,2,3 HAVING COUNT(*) > 1;"
```

Если запрос пустой — дубликаты успешно удалены.

---

## Полезные команды

- Остановить контейнеры:

```bash
docker-compose down
```

- Просмотр логов Laravel:

```bash
docker-compose logs -f app
```

---

## Структура проекта

- `/app/Console/Commands/CleanBalanceHistory.php` — Artisan команда очистки  
- `/database/seeders/BalanceHistorySeeder.php` — сидер для тестовых данных  
- `/docker-compose.yml` — конфигурация Docker  
- `/Dockerfile` — образ приложения PHP + Laravel

---

## Контакты

Если возникнут вопросы — обращайтесь к [твой контакт или email].

---

Спасибо за внимание!
