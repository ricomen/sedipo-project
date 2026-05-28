/**
 * Bootstrap SPA для dev.html (без PHP).
 */
(function () {
  'use strict';

  const PRIVILEGE_KEYS = window.__devMocks?.PRIVILEGE_KEYS || [];
  const fullAccess = Object.fromEntries(PRIVILEGE_KEYS.map((k) => [k, 2]));

  var routes = window.SEDIPO_ROUTES || [];

  const router = VueRouter.createRouter({
    history: VueRouter.createWebHashHistory(),
    routes,
  });

  const app = Vue.createApp({});
  app.use(router);

  app.component('navigation', {
    data() {
      return {
        sessionId: '1',
        my_login: 'dev',
        role_privileges: fullAccess,
        account_id: 1,
        counterparty_id: 0,
        role: 'admin',
        notefication: 1,
      };
    },
    mounted() {
      this.my_login = 'dev';
    },
    template: `
<nav class="navbar navbar-expand-lg bg-light bg-body-tertiary" data-bs-theme="light" style="background-color: #c5c5c5;">
  <div class="container-fluid">
    <router-link class="navbar-brand" to="/" style="margin-left: 8px;">
      <img src="/uploads/images/logo.png" width="30" alt="" onerror="this.style.display='none'">
      <span class="badge bg-secondary ms-1">DEV</span>
    </router-link>
    <div class="collapse navbar-collapse" id="navbar-content">
      <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
        <li v-if="role_privileges.orders_list==2 || role_privileges.orders_list==1" class="nav-item">
          <router-link to="/orders_list_redirect/" class="nav-link active">Заявки</router-link>
        </li>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;">Обучение</a>
          <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 30rem;">
            <li><router-link to="/groups_list_redirect/" class="dropdown-item">Учебные группы</router-link></li>
            <li><router-link to="/lstream_list_redirect/" class="dropdown-item">Учебные потоки</router-link></li>
            <li><router-link :to="{ name: 'calendar' }" class="dropdown-item">Расписание</router-link></li>
            <li><router-link to="/eisot_import/" class="dropdown-item">Импорт номеров удостоверений из ЕИСОТ</router-link></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;">Аналитика</a>
          <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 30rem;">
            <li><router-link :to="{ name: 'orders_analytics', params: { counterpartyid: 0 }}" class="dropdown-item">Аналитика по заявкам</router-link></li>
            <li><router-link :to="{ name: 'orders_table'}" class="dropdown-item">Сводная таблица по заявкам</router-link></li>
            <li><router-link :to="{ name: 'stat_report' }" class="dropdown-item">Отчёт — ФСН</router-link></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;">Контрагенты</a>
          <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 20rem;">
            <li><router-link to="/counterparty_list" class="dropdown-item">Контрагенты</router-link></li>
            <li><router-link :to="{ name: 'students_list_home', params: { counterpartyid: 0 }}" class="dropdown-item">Список слушателей</router-link></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;">Настройки</a>
          <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 20rem;">
            <li><router-link to="/course_category_list" class="dropdown-item">Категории</router-link></li>
            <li><router-link to="/courses_list" class="dropdown-item">Курсы</router-link></li>
            <li><router-link to="/teachers_commission_list" class="dropdown-item">Состав комиссии</router-link></li>
            <li><router-link to="/teacher_list" class="dropdown-item">Преподаватели</router-link></li>
            <li><router-link to="/template_list" class="dropdown-item">Шаблоны документов</router-link></li>
          </ul>
        </li>
        <li class="nav-item"><router-link to="/contacts" class="nav-link">Контакты</router-link></li>
      </ul>
      <div class="d-flex ms-auto">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="fa-solid fa-circle-user" style="font-size: larger;"></i>
              <span style="color: #212529;">{{ my_login }}</span>
            </a>
            <ul class="dropdown-menu shadow dropdown-menu-end">
              <li><router-link :to="{ name: 'accountedit', params:{ accountid: account_id }}" class="dropdown-item">Настройки аккаунта</router-link></li>
              <li><router-link :to="{ name: 'self_list' }" class="dropdown-item">Реквизиты организации</router-link></li>
              <li><hr class="dropdown-divider"></li>
              <li><router-link to="/rolelist" class="dropdown-item">Роли пользователей</router-link></li>
              <li><router-link to="/accountslist" class="dropdown-item">Настройки доступа администраторов</router-link></li>
              <li><hr class="dropdown-divider"></li>
              <li><a href="#/logout" class="dropdown-item">Выход</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
<br />`,
  });

  app.mount('#app');
  console.info('[dev-app] SPA запущена. Маршруты: #/orders_list, #/courses_list, #/ и др.');
})();
