# Повторяющиеся UI-компоненты (кандидаты на выделение)

> Источник анализа: активные `pages/*.vue.js` (без `pages/2/`, `pages/unuse/`).  
> Сейчас в проде глобально зарегистрирован только **`navigation`** (`index.php`).  
> Каталог `components/` — целевая структура для редизайна; подключение см. [components/README.md](../../components/README.md).

---

## Сводная таблица

| # | Компонент | Файл | Приоритет | Где повторяется | Примечание |
|---|-----------|------|-----------|-----------------|------------|
| 0 | Navigation | `index.php` (встроен) | — | ~65 страниц | Уже есть |
| 1 | PageShell | `layout/PageShell.js` | Высокий | 13+ списков/форм | `<navigation>` + заголовок + ошибка + guard по правам |
| 2 | Pagination | `ui/Pagination.js` | Высокий | 10+ списков | `numPages`, `page`, событие `page-change` |
| 3 | SearchToolbar | `ui/SearchToolbar.js` | Высокий | 15+ списков | Поиск + «Сбросить фильтры» |
| 4 | FioSearch | `ui/FioSearch.js` | Высокий | 8+ страниц | Фамилия / имя / отчество |
| 5 | CreateEntityButton | `ui/CreateEntityButton.js` | Высокий | 12+ списков | `router-link` + иконка «создать» |
| 6 | RowActions | `ui/RowActions.js` | Высокий | все `*_list` | edit / clone / delete / report |
| 7 | DataTable | `ui/DataTable.js` | Средний | 15 списков | Обёртка `table.table` + слоты header/body |
| 8 | PrivilegeGuard | `auth/PrivilegeGuard.js` | Средний | 13+ страниц | `v-if` по `role_privileges.*` |
| 9 | BootstrapModal | `ui/BootstrapModal.js` | Средний | 10+ страниц | header / body / footer |
| 10 | DateRangeFilter | `filters/DateRangeFilter.js` | Средний | orders, groups, lstream, analytics | `date1`, `date2` |
| 11 | YearMonthTabs | `filters/YearMonthTabs.js` | Средний | orders, groups, lstream, analytics | `year_arr`, `mon_arr` |
| 12 | StatusSelect | `filters/StatusSelect.js` | Средний | orders, groups, order_items | `api/status_json.php` |
| 13 | CounterpartyPicker | `filters/CounterpartyPicker.js` | Средний | orders, groups, lstream | autocomplete контрагента |
| 14 | CategorySelect | `filters/CategorySelect.js` | Низкий | courses, teacher_course | `course_category_json` |
| 15 | PerformerSelect | `filters/PerformerSelect.js` | Низкий | courses_list | учебные центры (type=1) |
| 16 | SplitButtonDropdown | `ui/SplitButtonDropdown.js` | Средний | courses, orders, groups | btn-group + export menu |
| 17 | DocumentExportLinks | `documents/DocumentExportLinks.js` | Средний | orders, groups, courses | ссылки `d-api/documents-*.php` |
| 18 | FileUploadModal | `documents/FileUploadModal.js` | Средний | orders, counterparty, templates | `multipart/form-data` |
| 19 | FormFieldHelp | `forms/FormFieldHelp.js` | Средний | order_edit, course_edit, … | `helpers_json` + `fa-circle-question` |
| 20 | PageFooterClose | `layout/PageFooterClose.js` | Средний | edit-страницы | кнопка «Закрыть» → список |
| 21 | ArchiveToggle | `filters/ArchiveToggle.js` | Низкий | students, teachers, templates | Активные / архив |
| 22 | OrderStatusActions | `orders/OrderStatusActions.js` | Доменный | orders_list | кнопки смены статуса |
| 23 | OrderWarningIcons | `orders/OrderWarningIcons.js` | Доменный | orders_list | СНИЛС, группы, потоки |
| 24 | ContractDocumentsModal | `orders/ContractDocumentsModal.js` | Доменный | orders_list, counterparty_list | бух. документы |
| 25 | StudentsCountBadge | `orders/StudentsCountBadge.js` | Доменный | orders_list | «(N чел.)» |
| 26 | SnilsField | `forms/SnilsField.js` | Доменный | student_edit, order_* | поле + валидация |
| 27 | OrderEntityLinks | `orders/OrderEntityLinks.js` | Доменный | orders_list | items / groups / lstream / discounts |

---

## Дерево `components/`

```
components/
├── README.md
├── register.js                 # app.component(...) для dev и prod
├── layout/
│   ├── PageShell.js
│   └── PageFooterClose.js
├── ui/
│   ├── Pagination.js
│   ├── SearchToolbar.js
│   ├── FioSearch.js
│   ├── CreateEntityButton.js
│   ├── RowActions.js
│   ├── DataTable.js
│   ├── BootstrapModal.js
│   └── SplitButtonDropdown.js
├── filters/
│   ├── DateRangeFilter.js
│   ├── YearMonthTabs.js
│   ├── StatusSelect.js
│   ├── CounterpartyPicker.js
│   ├── CategorySelect.js
│   ├── PerformerSelect.js
│   └── ArchiveToggle.js
├── forms/
│   ├── FormFieldHelp.js
│   └── SnilsField.js
├── auth/
│   └── PrivilegeGuard.js
├── documents/
│   ├── DocumentExportLinks.js
│   └── FileUploadModal.js
└── orders/
    ├── OrderStatusActions.js
    ├── OrderWarningIcons.js
    ├── ContractDocumentsModal.js
    ├── StudentsCountBadge.js
    └── OrderEntityLinks.js
```

---

## Маппинг: страница → какие компоненты использовать

| Страница | Рекомендуемые компоненты |
|----------|--------------------------|
| `courses_list` | PageShell, SearchToolbar, CategorySelect, PerformerSelect, DataTable, Pagination, CreateEntityButton, RowActions, SplitButtonDropdown |
| `orders_list` | PageShell, SearchToolbar, DateRangeFilter, YearMonthTabs, StatusSelect, CounterpartyPicker, DataTable, Pagination, OrderStatusActions, OrderWarningIcons, OrderEntityLinks, ContractDocumentsModal, BootstrapModal |
| `students_list` | PageShell, FioSearch, DataTable, Pagination, RowActions, ArchiveToggle, PageFooterClose |
| `teachers_list` | PageShell, FioSearch, DataTable, Pagination, RowActions, ArchiveToggle |
| `counterparty_list` | PageShell, SearchToolbar, DataTable, Pagination, FileUploadModal, BootstrapModal |
| `groups_list` | PageShell, DateRangeFilter, YearMonthTabs, StatusSelect, CounterpartyPicker, DataTable, Pagination, DocumentExportLinks |
| `lstream_list` | PageShell, DateRangeFilter, YearMonthTabs, CounterpartyPicker, DataTable, Pagination, DocumentExportLinks |
| `template_list` | PageShell, SearchToolbar, DataTable, RowActions, ArchiveToggle |
| `order_edit` | PageShell, FormFieldHelp, CounterpartyPicker, StatusSelect, SnilsField, PageFooterClose |
| `student_edit` | PageShell, FormFieldHelp, SnilsField, PageFooterClose |
| `home` | PageShell (без guard), отдельные виджеты (календарь, новости — не выносить в общие) |
| `login` | отдельная страница, общие компоненты не нужны |

---

## Порядок внедрения при редизайне

1. **Фаза 1** — `register.js` + `PageShell` + `Pagination` + `SearchToolbar` → перевести 1 пилот (`courses_list`).
2. **Фаза 2** — `RowActions`, `CreateEntityButton`, `DataTable`, `PageFooterClose` → остальные списки.
3. **Фаза 3** — фильтры (`DateRangeFilter`, `CounterpartyPicker`, …).
4. **Фаза 4** — доменные блоки заявок (`orders/*`).

---

## Конвенции

- Формат: **глобальные объекты Vue 3 Options API** (как `pages/*.vue.js`), без `.vue` SFC.
- Имена в DOM: **PascalCase** (`<sed-pagination>`) — регистрация через `app.component('SedPagination', ...)`.
- Стили: пока общие `css/style.css`, `css/polina.css`; при редизайне — BEM-классы `sed-*` в компонентах.
- Права: props `privilege` + `minLevel` (0/1/2) или обёртка `PrivilegeGuard`.
- API не трогаем: компоненты получают данные через **props** / **emit**, запросы остаются в странице-контейнере.

---

## Не выносить в общие компоненты (пока)

| Страница | Причина |
|----------|---------|
| `home` | календарь, новости, mascot, Chart.js — уникальная компоновка |
| `login` / `logout` | изолированный flow |
| `plan_edit` | сложная сетка учебного плана |
| `template1_edit` / `template2_edit` | Jodit + HTML |
| `calendar`, `eisot_import`, `stat_report` | отчёты и импорты |
| `contacts`, `about`, `docs` | статический контент |

---

## Связанные документы

- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) — стек и точки входа
- [ROLE_PRIVILEGES_MATRIX.md](ROLE_PRIVILEGES_MATRIX.md) — права → страницы
- [DEV_LOCAL.md](../DEV_LOCAL.md) — локальный UI с моками (`dev.html`)
