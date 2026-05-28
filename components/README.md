# Компоненты Sedipo (frontend)

Глобальные Vue 3-компоненты для редизайна. Формат совместим с `pages/*.vue.js` и `dev.html`.

## Подключение

### dev.html (локально)

После `dev/dev-mocks.js`, **до** `pages/*.vue.js`:

```html
<script src="/components/register.js"></script>
```

`register.js` вызывает `registerSedComponents(app)` — в `dev/dev-app.js` перед `app.mount` добавьте:

```js
if (typeof registerSedComponents === 'function') {
  registerSedComponents(app);
}
```

### index.php (прод)

Аналогично: подключить `components/register.js` и вызвать `registerSedComponents(app)` перед `app.mount('#app')`.

## Использование в странице

```js
// pages/courses_list.vue.js — фрагмент template
template: `
  <sed-page-shell title="Курсы" :privilege="'courses_list'" :privilege-level="1">
    <sed-search-toolbar v-model="namesearch" @search="search_go(1)" @reset="search_go(0)" />
    <!-- таблица -->
    <sed-pagination :page="page" :num-pages="numPages" @page-change="page_go" />
  </sed-page-shell>
`
```

## Статус реализации

| Компонент | Статус |
|-----------|--------|
| PageShell, Pagination, SearchToolbar, CreateEntityButton, RowActions, PageFooterClose, PrivilegeGuard, BootstrapModal | реализованы (базово) |
| Остальные | заглушки — см. [REUSABLE_COMPONENTS.md](../docs/architecture/REUSABLE_COMPONENTS.md) |

Полная таблица и маппинг страниц: [docs/architecture/REUSABLE_COMPONENTS.md](../docs/architecture/REUSABLE_COMPONENTS.md).
