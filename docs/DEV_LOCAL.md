# Локальная разработка UI без PHP

Для редизайна страниц можно запустить SPA с моками API — без PHP, MySQL и `config-auth.php`.

## Быстрый старт

Из корня репозитория:

```bash
python3 -m http.server 8080
```

Откройте в браузере:

- [http://localhost:8080/dev.html](http://localhost:8080/dev.html) — главная
- [http://localhost:8080/dev.html#/orders_list](http://localhost:8080/dev.html#/orders_list) — заявки
- [http://localhost:8080/dev.html#/courses_list](http://localhost:8080/dev.html#/courses_list) — курсы
- [http://localhost:8080/dev.html#/counterparty_list](http://localhost:8080/dev.html#/counterparty_list) — контрагенты

Альтернативы:

```bash
npx serve -l 8080 .
deno run -A jsr:@std/http@1/file-server .
```

## Файлы

| Файл | Назначение |
|------|------------|
| `dev.html` | Точка входа: стили, Vue, все `pages/*.vue.js`, глобалы |
| `dev/dev-mocks.js` | Перехват `axios.post` и фикстуры API |
| `dev/dev-app.js` | Маршруты, компонент `navigation`, `app.mount` |
| `components/` | Переиспользуемые UI-компоненты ([REUSABLE_COMPONENTS.md](architecture/REUSABLE_COMPONENTS.md)) |

## Как добавить мок для страницы

1. Откройте страницу в браузере и смотрите консоль: `[mock] courses.list ✓` или `[mock] foo.bar (fallback)`.
2. Добавьте ответ в `dev/dev-mocks.js` → объект `MOCKS`:

```js
'orders.list': {
  status: 0,
  role: 'admin',
  numPages: 1,
  page: 1,
  list: [ /* ваши объекты */ ],
},
```

Ключ: `<endpoint>.<action>`, где `endpoint` — имя файла без `_json.php` (`courses_json.php` → `courses`), `action` — первый ключ тела запроса (`list`, `object`, `delete`, …).

## Ограничения

- Сохранение на сервер не работает (моки возвращают `status: 0`, данные не персистятся).
- Генерация PDF/DOCX (`d-api/*`) недоступна.
- Для полного E2E нужен PHP + БД (см. `docs/architecture/PROJECT_STRUCTURE.md`).

## Права в dev-режиме

Все `session_role_privileges` и пункты меню включены (`2` = полный доступ). Роль: `admin`, логин: `dev`.
