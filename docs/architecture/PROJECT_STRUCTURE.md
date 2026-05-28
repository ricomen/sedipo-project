# Sedipo — структура проекта и зависимости

> Документ описывает структуру кода, ключевые модули и зависимости проекта.
> Для индексации/анализа намеренно **исключены** каталоги: `tmp/`, `uploads/`, `font-awesome/`.

---

## 1. Назначение системы

**Sed 2.0** (он же *Система сопровождения образования*) — внутренняя веб-система
для организации дополнительного профессионального образования (ДПО):

- ведение контрагентов (организаций-заказчиков обучения),
- учёт слушателей и преподавателей,
- управление курсами, учебными группами и потоками,
- оформление заявок, договоров, платежей,
- генерация документов (договоры, протоколы, удостоверения, дипломы, ведомости, акты, счета),
- интеграция с **Moodle** (LMS) и опционально с **1С**,
- выгрузка отчётности (ЕИСОТ, ФИС ФРДО, ФСН, ERCDO и др.).

Базовые параметры (домены БД, признаки `IS_1C`, `IS_LMS`, `IS_LITTLE`, `IS_NDS`, `IS_SECR`, `IS_DEVELOPMENT`, `IS_DEPRECATED`)
задаются в `config.php` и `config-mainpage.php`.

---

## 2. Технологический стек

### Backend
- **PHP** (без фреймворка) — процедурный стиль, прямое использование `PDO/MySQL`.
- **MySQL** через `PDO` — три логических БД:
  - `sed20` — основная (см. `config.php → $cfg`),
  - `helper` — справочники (см. `config.php → $cfg_helper`),
  - БД аутентификации — конфигурируется **вне репозитория** в `../config/config-auth.php`
    (репозиторий это файл не содержит; он подключается из `index.php`, `api/*_json.php`, и др.).

### Frontend
- **Vue 3** + **Vue Router 4** — SPA с hash-роутингом (`createWebHashHistory`).
- Подключаются **глобальные сборки** (`js/vue.global.min.js`, `js/vue-router.global.min.js`) —
  страницы пишутся как глобальные объекты-компоненты в `pages/*.vue.js`.
- В `index.php` есть альтернативная ветка `ESM` (через importmap + `/node_modules/...`) — пока не используется в продакшене.
- **Bootstrap 5** (тема `bootstrap-lumen`), **Font Awesome** (исключён из индексации, используется через `/font-awesome/css/all.min.css`).
- **Axios** — HTTP-клиент.
- **Jodit** — WYSIWYG-редактор для шаблонов документов.
- **Chart.js** — графики аналитики.
- **Sortable / vue-draggable-plus** — drag-and-drop.

### Сборка / окружение
- **Deno** + **Vite** (см. `deno.json`, `vite.config.js`, `deno.lock`).
- Задачи Deno:
  - `deno task dev` — `vite` dev-сервер,
  - `deno task build` / `preview`,
  - `deno task serve` — раздать `dist/` через `@std/http file-server`.
- В текущем виде Vite используется опционально — рантайм страниц всё ещё PHP + глобальные Vue-скрипты.

### Серверные библиотеки (вендорные, лежат прямо в репозитории)
| Каталог             | Библиотека                            | Версия          | Назначение                                  |
|---------------------|---------------------------------------|-----------------|---------------------------------------------|
| `smarty/`           | `smarty/smarty`                       | 4.x (master)    | Шаблонизатор HTML-документов                |
| `dompdf/`           | `dompdf/dompdf`                       | (исходники)     | HTML → PDF                                  |
| `PhpSpreadsheet/`   | `phpoffice/phpspreadsheet`            | `^3.8` (Composer)| Чтение/запись XLSX-отчётов                  |
| `PhpWord/`          | `phpoffice/phpword`                   | `^1.4` (Composer)| Чтение/запись DOCX-шаблонов                 |
| `PDFMerger/`        | `PDFMerger` + `tcpdf`                 | (исходники)     | Объединение PDF (пакеты документов)         |

Все они подключаются напрямую через `require_once '.../vendor/autoload.php'` или `require_once '.../autoload.inc.php'`.

---

## 3. Структура каталогов верхнего уровня

```
sedipo-project/
├── index.php              # Главная страница SPA: PHP-обвязка + Vue-приложение + роуты
├── login-info.php         # JSON-endpoint информации о текущей сессии
├── config.php             # Конфиг БД (sed20, helper) + флаги системы
├── config-mainpage.php    # Заголовок браузера
├── config-moodle.php      # Конфиг БД Moodle (отдельный $CFG)
├── deno.json              # Задачи Deno + npm-импорты (vue, vite, axios, vue-router)
├── deno.lock              # Зафиксированные версии npm-зависимостей
├── vite.config.js         # Vite + @deno/vite-plugin + @vitejs/plugin-vue
├── README.md
│
├── api/                   # JSON API основной CRM-части (~60 активных endpoint'ов)
├── d-api/                 # «Document API»: генерация документов (cert, contract, protocol, vedomost, ...)
├── analytics/             # Аналитические выгрузки (календарь, XLSX-отчёты)
├── reports/               # Государственные отчёты (ЕИСОТ, ФИС ФРДО, ERCDO, ФСН-стат)
├── lms-api/               # Интеграция с Moodle (REST WS) и выгрузки по студентам LMS
│
├── components/            # Переиспользуемые Vue-компоненты (редизайн), см. REUSABLE_COMPONENTS.md
├── pages/                 # Vue-страницы SPA (~70 активных + множество резервных копий)
├── js/                    # Глобальные JS-библиотеки (vue, vue-router, axios, jodit, chart, sortable, md5)
├── css/                   # Стили (bootstrap-lumen, style, home, polina) + bootstrap bundle
├── gfonts/                # Локальные шрифты Google Fonts (woff2)
├── images/                # Статические изображения / favicon / лого
│
├── documents/             # HTML/Smarty-шаблоны печатных документов (договоры, протоколы, дипломы, удостоверения)
├── docs/                  # Документация
│   ├── instructions/      #   • PDF/DOCX-инструкции для пользователей
│   └── architecture/      #   • Документация для разработчиков (этот файл, REUSABLE_COMPONENTS.md, …)
│
├── smarty/                # Вендор: шаблонизатор Smarty 4.x
├── dompdf/                # Вендор: HTML → PDF конвертер
├── PhpSpreadsheet/        # Вендор (Composer): чтение/запись XLSX
├── PhpWord/               # Вендор (Composer): чтение/запись DOCX
├── PDFMerger/             # Вендор: объединение PDF (на базе TCPDF)
│
├── cache/                 # Каталог для серверных кэшей (Smarty/Dompdf), сейчас пуст
├── unuse/                 # Архив устаревшего кода, не подключается из основных точек входа
│
├── font-awesome/          # ⛔ исключён из индексации (иконки)
├── uploads/               # ⛔ исключён из индексации (пользовательские загрузки)
└── tmp/                   # ⛔ исключён из индексации (временные файлы)
```

> ⚠️ Файлы с суффиксами `-1`, `-2`, …, `1`, `2` (например `auth_json.php-9`, `orders_json.php-11`) —
> это **локальные исторические копии** PHP/JS-файлов рядом с актуальной версией.
> Из `index.php` подключается только версия **без суффикса** (`auth_json.php`, `orders_json.php`, …).
> При навигации по проекту опирайтесь на «чистые» имена.

---

## 4. Точки входа и поток выполнения

### 4.1. Главная точка входа — `index.php`

1. `session_start()` + анти-кэширующие заголовки.
2. Подключение `../config/config-auth.php` (вне репозитория) и установление PDO к БД аутентификации (`$dbh_a`).
3. Подключение `config.php` и PDO к основной БД (`$dbh`).
4. Функция `is_auth($first)`:
   - читает запись из `a_session` по `session_id()`,
   - подтягивает `a_account` + `a_role`,
   - заполняет глобальные `$session_login`, `$session_account_id`, `$session_role`, `$session_privileges` (~24 флага доступа).
5. Рендерит HTML с подключением:
   - стилей: `css/bootstrap-lumen.min.css`, `css/style.css`, `css/home.css`, `css/polina.css`, `font-awesome/css/all.min.css`,
   - скриптов: `js/vue.global.min.js`, `js/vue-router.global.min.js`, `js/axios.min.js`, `js/jodit.min.*`,
   - всех Vue-страниц из `pages/*.vue.js` (под условиями `is_auth(0)`),
   - роутера (`Vue.createApp`, `VueRouter.createRouter`) с ~50 маршрутами.
6. Глобальные переменные для фронта пробрасываются прямо из PHP: `JsonApiURL`, `MoodleApiURL`, `IS_1C`, `IS_LMS`, `IS_DEVELOPMENT`, `session_t`, `session_role_privileges`, `session_var`, `session_navigation`.
7. Компонент `navigation` (главное меню) объявлен прямо внутри `index.php` и отражает права (`role_privileges`).

### 4.2. JSON API — `api/*_json.php`

Конвенция:
- Каждый endpoint — это `*_json.php`, принимающий **один JSON-объект** через `php://input`.
- Первый ключ объекта = имя функции, его значение = аргументы:
  ```json
  { "orders_list": { "page": 1, "search": "..." } }
  ```
- Endpoint подключает `lib.php`, `json_lib.php` (общий бутстрап PDO + сессии),
  диспатчит вызов в локальные функции и возвращает `json_encode($result, JSON_UNESCAPED_UNICODE)`.
- Общий бутстрап: `api/json_lib.php` (∼560 строк), `api/lib.php` (∼42 строки), `api/api_core.php` (доменная логика отчётов).
- Ядро API (активные endpoint'ы, ~60 шт.):
  `auth`, `account1`, `chart`, `contract`, `counterparty`, `counterparty_contract`, `counterparty_check`,
  `course_calendar`, `course_calendar2`, `course_calendar_import`, `course_category`, `courses`, `egrul`, `fm`,
  `groups`, `groups_upload`, `helpers`, `lstream`, `lstream_teacher`, `moodle`, `moodle_categories`,
  `news`, `okpdtr`, `orders`, `orders_upload`, `order_contract_upload`, `order_discounts`, `order_payer`,
  `plan`, `price`, `reports`, `role`, `self`, `self2`, `self_upload`, `status`, `stream`,
  `students`, `students_import`, `students_lib`, `teacher`, `teacher2`,
  `teachers_commission`, `teachers_commission_edition`, `teachers_commission_teacher`, `teachers_commission_upload`,
  `template`, `template2`, `template_upload`, `upload`, `payment_1c`, и др.

### 4.3. Document API — `d-api/`

Генерация конечных документов (PDF/DOCX/HTML) на основе Smarty-шаблонов и БД.

- `d-api_lib.php` — общая логика (загрузка данных слушателей, заявок, потоков; интерполяция переменных в шаблоны).
- `DativeCase.php` — склонение русских ФИО и должностей в дательный падеж (для документов).
- Точки входа:
  - `documents-cert.php` — удостоверения,
  - `documents-contract.php` — договоры,
  - `documents-protocol.php` — протоколы,
  - `documents-vedomost.php` — ведомости,
  - `documents-order.php` — приказы,
  - `documents-group.php` — групповые документы,
  - `documents-lstream.php` — документы по потокам,
  - `documents-print.php`, `documents-view.php`, `documents2.php` — экраны просмотра/печати,
  - `files_teacher.php` — файлы по преподавателям,
  - `protocol-saved.php` — сохранённые протоколы.
- HTML-«рамки» (header/bottom) для PDF: `documents-editor*.header.html`, `documents-layout*.header.html`, `documents-editor.bottom.html`, `documents-layout.bottom.html`.

### 4.4. Аналитика — `analytics/`

- `calendar.php`, `calendar-inc.php`, `calendar-demo.php` + `calendar-demo.css` — PDF-календарь занятий (через `dompdf`).
- `order_xls.php`, `students_order_xls.php`, `table_xls.php` — XLSX-выгрузки (через `PhpSpreadsheet`).
- `validity_period.php` — отчёт по срокам действия документов сотрудников.

### 4.5. Отчёты для регуляторов — `reports/`

- `eisot.php`, `eisot_import_json.php` — Единая инф. система охраны труда (ЕИСОТ).
- `frdo.php` + `fis_frdo_dpo.xlsx`, `fis_frdo_po.xlsx` — выгрузка в ФИС ФРДО.
- `ercdo.php` + `ercdo1.xlsx`, `ercdo2.xlsx`, `ercdo3.xlsx` — отчёт ERCDO.
- `stat_report.php` + `po1_stat.xlsx`, `pk11_stat.xlsx` — Федеральное статистическое наблюдение.

### 4.6. Интеграция с Moodle — `lms-api/`

- `api.php` — обёртка над Moodle Web Services REST (token-based, `MOODLE_URL`, `wsfunction`).
- `lms_info_json.php`, `lms_sync_json.php`, `moodle_sync_rc.php` — синхронизация курсов/пользователей/оценок.
- `students_xls.php` — выгрузка списка студентов LMS в XLSX.

Дополнительно: `api/moodle_json.php` и `api/moodle_categories.php` — обращения к Moodle из основного API.

---

## 5. Фронтенд: страницы и роутинг

В `index.php` объявлено ~50 маршрутов `vue-router`. Каждому маршруту соответствует
файл `pages/<имя>.vue.js` (глобальный объект-компонент Vue 3 Options API).

**Основные функциональные группы:**

| Группа | Страницы (фрагмент) |
|---|---|
| Аутентификация | `login`, `logout`, `home`, `home_redirect` |
| Курсы | `courses_list`, `course_edit`, `course_report`, `course_category_list`, `course_category_edit`, `course_calendar_edit`, `plan_edit` |
| Преподаватели | `teachers_list`, `teacher_edit`, `teacher_course`, `teachers_commission_list`, `teachers_commission_edit`, `job_title_list`, `job_title_edit` |
| Контрагенты | `counterparty_list`, `counterparty_base_edit`, `self_list`, `self_edit`, `validity_period`, `validity_period_counterparty_list` |
| Слушатели | `students_list`, `students_list_home`, `student_edit`, `student_report`, `students_import` |
| Заявки / договоры | `orders_list`, `orders_list_redirect`, `order_edit`, `order_items`, `order_discounts`, `order_student`, `set_template_contract` |
| Учебные группы | `groups_list`, `groups_list_redirect`, `group_scheduler`, `group_student`, `group_report` |
| Учебные потоки | `lstream_list`, `lstream_list_redirect`, `lstream_edit`, `lstream_items`, `lstream_teacher` |
| Шаблоны документов | `template_list`, `template1_edit`, `template2_edit` |
| Аналитика | `orders_analytics`, `orders_table`, `stat_report`, `eisot_import`, `calendar`, `reports` |
| Администрирование | `accounts_list`, `account_edit`, `role_list`, `role_edit` |
| Прочее | `about`, `contacts`, `docs`, `wordvariables`, `import` |

**Авторизация на уровне UI** — флаги `role_privileges` (24 шт.) пробрасываются из PHP в `session_role_privileges` и проверяются через `v-if="role_privileges.X==1 || role_privileges.X==2"` в навигации и страницах.

---

## 6. Документы и шаблоны (`documents/`)

- `contract1/`, `contract2/` — HTML/DOCX-шаблоны договоров (виды: `dogovor-OY`, `dogovor-OOY`, `dogovor-OT`, `dogovor-KY`, и приложения `a1..a3`).
- `protocol/` — шаблоны протоколов экзаменов/итоговой аттестации (по категориям: ОТ, пожарная/промышленная безопасность, профпереподготовка, проф. обучение и т.д.).
- `certificate/` — удостоверения и сертификаты + версии под А1/А2.
- `usercase/{kpk,po,pp}/` — комплекты «личных дел» слушателей.
- `buhdoc/` — бухгалтерские документы (счета, акты выполненных работ).
- `conterparty/` — карточки/паспорта контрагентов.
- `signs/` — PNG-подписи и печати (используются как изображения в документах).
- `fonimages/` — фоны и QR-коды (ЕИСОТ, ФИС ФРДО).
- `teachers/` — шаблоны договоров с преподавателями.

---

## 7. Зависимости — сводно

### 7.1. JS / npm (через `deno.json`)

```json
{
  "@deno/vite-plugin":  "npm:@deno/vite-plugin@^2.0.2",
  "@vitejs/plugin-vue": "npm:@vitejs/plugin-vue@^6.0.5",
  "axios":              "npm:axios@^1.15.2",
  "vite":               "npm:vite@^8.0.3",
  "vue":                "npm:vue@^3.5.32",
  "vue-router":         "npm:vue-router@^5.0.6"
}
```

Локально подключаемые **глобальные** скрипты (без сборки) — в `js/`:
`vue.global.min.js`, `vue-router.global.min.js`, `axios.min.js`, `jodit.min.{js,css}`, `chart.min.js`, `Sortable.min.js`, `vue3-sortablejs.global.js`, `vue-draggable-plus.min.js`, `md5.min.js`.

### 7.2. PHP-вендоры (без Composer на уровне проекта — каждая библиотека самостоятельна)

| Библиотека | Где | Подключение |
|---|---|---|
| Smarty 4.x | `smarty/` | `require_once '.../smarty/libs/Smarty.class.php'` или `smarty/vendor/autoload.php` |
| dompdf | `dompdf/` | `require_once '.../dompdf/autoload.inc.php'` |
| PhpSpreadsheet ^3.8 | `PhpSpreadsheet/vendor/` | `require_once '.../PhpSpreadsheet/vendor/autoload.php'` |
| PhpWord ^1.4 | `PhpWord/vendor/` | `require_once '.../PhpWord/vendor/autoload.php'` |
| PDFMerger + TCPDF | `PDFMerger/` | `require_once '.../PDFMerger/PDFMerger.php'` |

### 7.3. Внешние зависимости вне репозитория

- `../config/config-auth.php` — конфигурация БД аутентификации (`$cfg_auth`). Подключается:
  - из `index.php`, `login-info.php`,
  - из всех `api/*_json.php`, `d-api/documents-*.php`, `analytics/*.php`, `reports/*.php`.
- **Moodle**: `https://sdoold.sedipo.ru/sedipo/`, `https://demosdo.sedipo.ru` (см. `config.php`, `lms-api/api.php`).
- Внешний JSON API (`$JsonApiURL = 'https://sed20d.sedipo.ru/'`) — endpoint для `login`-редиректа.

---

## 8. БД (логически)

| Соединение | Конфиг | Назначение |
|---|---|---|
| `$dbh_a` | `../config/config-auth.php → $cfg_auth` | Аутентификация: `a_session`, `a_account`, `a_role`, `a_customer` |
| `$dbh` | `config.php → $cfg` (`sed20`) | Основная: контрагенты, заявки, слушатели, курсы, группы, потоки, документы, отчёты |
| `$dbh_helper` / `$cfg_helper` | `config.php → $cfg_helper` (`helper`) | Справочники: ОКПДТР, страны и пр. |
| `$CFG` (Moodle) | `config-moodle.php` | Прямой доступ к БД Moodle (`openedu` / `priem` / `eos`) — только чтение для импорта |

Связь между БД: `customer_id`, `account_id`, `role_id`, `counterparty_id` — сквозные идентификаторы.

---

## 9. Конвенции и нюансы для разработчиков

1. **Никаких автозагрузчиков уровня проекта** — все включения через `require_once` относительными путями.
   Это значит, что **рабочая директория** при выполнении файла важна (`d-api/`, `api/`, `analytics/`, `reports/` — каждый ожидает запуска изнутри своего каталога).
2. **Чувствительные данные в `config.php`/`config-moodle.php`** (логины/пароли БД) — закоммичены в репозиторий. При деплое следует подменять их или вынести в `../config/`, как уже сделано для `config-auth.php`.
3. **Резервные копии файлов** (`*.php-3`, `*.vue.js-5`, и т.п.) живут рядом с актуальными — это исторический способ ведения изменений до Git. Не править их; ориентироваться на имя **без суффикса**.
4. **`unuse/`** — устаревший код, оставлен «на всякий случай», не должен подключаться.
5. **Сессии** общие между PHP и JSON API через `PHPSESSID` cookie + таблицу `a_session`.
6. **Кодировка** — `utf-8` повсюду (DSN с `charset=utf8`, `JSON_UNESCAPED_UNICODE`).
7. **Smarty-кэш** — каталог `cache/` (сейчас пуст; должен быть с правами на запись для web-пользователя).
8. **Загруженные файлы** — каталог `uploads/` (исключён из индексации; именно туда сохраняются договоры, скан-копии, контентные изображения).
9. **Временные файлы PDF/DOCX** — каталог `tmp/` (исключён из индексации).
10. **Запуск фронта в dev-режиме**: `deno task dev` (если требуется Vite-стек); продакшен по-прежнему — отдача `index.php` через PHP-FPM/Apache.

---

## 10. Карта подключений (cross-module)

```
                       ┌────────────────────────────────┐
   Browser  ◀──────────│   index.php  (PHP + Vue SPA)   │
                       └──────────┬────────────┬────────┘
                                  │            │
                fetch/axios       │            │  <script src="pages/*.vue.js">
                                  ▼            ▼
                       ┌────────────────────────────────┐
                       │            pages/              │  (~70 Vue-компонентов)
                       └──────────┬─────────────────────┘
                                  │ POST JSON
                                  ▼
        ┌─────────────────────────┴──────────────────────────┐
        │                                                    │
        ▼                                                    ▼
┌──────────────────┐                              ┌──────────────────────┐
│   api/*_json.php │  ◀── json_lib + lib.php ───▶ │  d-api/documents-*.php│
│  (CRUD, бизнес)  │                              │  (HTML/PDF/DOCX gen)  │
└────────┬─────────┘                              └──────────┬───────────┘
         │                                                    │
         ▼                                                    ▼
   ┌──────────────────────────────────────────────────────────────┐
   │ MySQL (auth) │ MySQL (sed20) │ MySQL (helper) │ MySQL (Moodle) │
   └──────────────────────────────────────────────────────────────┘
         ▲                          ▲                       ▲
         │                          │                       │
   lms-api/*  ───────────▶  Moodle WS REST     reports/*  ──┘
   analytics/* ─────────▶  PhpSpreadsheet · dompdf · PhpWord · PDFMerger · Smarty
```

---

## 11. Что НЕ входит в индекс (по запросу)

- `tmp/` — рантайм-временные файлы (PDF/DOCX-черновики).
- `uploads/` — загруженные пользователями файлы (~96 МБ).
- `font-awesome/` — иконочный фреймворк (~11 МБ).

Эти каталоги опускаются при поиске/анализе кодовой базы, но **должны существовать в развёртывании** —
на них есть жёсткие ссылки из `index.php` (`/font-awesome/css/all.min.css`), `config.php`
(`/uploads/images/logo.png`, `/uploads/images/login-logo.png`) и серверной логики (`tmp/`).
