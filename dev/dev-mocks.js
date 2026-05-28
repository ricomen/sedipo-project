/**
 * Локальные моки API для dev.html (без PHP).
 * Конвенция: POST api/<endpoint>_json.php с телом { <action>: { ... } }
 */
(function () {
  'use strict';

  const STATUS_OK = 0;

  const PRIVILEGE_KEYS = [
    'accountedit', 'self_list', 'rolelist', 'accountslist', 'set_template_contract',
    'validity_period_counterparty_list', 'course_category_list', 'courses_list',
    'teachers_commission_list', 'teacher_list', 'template_list', 'counterparty_list',
    'students_list', 'orders_analytics', 'orders_table', 'stat_report', 'groups_list',
    'lstream_list', 'calendar', 'eisot_import', 'orders_list', 'orders_list_buh', 'upload_flag',
  ];

  const MOCK_COURSES = [
    { course_id: 1, shortname: 'Охрана труда для руководителей и специалистов', main_module: 0, price: '4 500', rank_of_profession: 0, category_id: 1 },
    { course_id: 2, shortname: 'Пожарно-технический минимум', main_module: 0, price: '3 200', rank_of_profession: 0, category_id: 2 },
    { course_id: 3, shortname: 'Промышленная безопасность (А.1)', main_module: 0, price: '8 900', rank_of_profession: 0, category_id: 3 },
    { course_id: 4, shortname: 'Первая помощь пострадавшим', main_module: 1, price: '2 100', rank_of_profession: 0, category_id: 1 },
  ];

  const MOCK_CATEGORIES = [
    { category_id: 1, name: 'Охрана труда', is_rank_of_profession: 'false' },
    { category_id: 2, name: 'Пожарная безопасность', is_rank_of_profession: 'false' },
    { category_id: 3, name: 'Промышленная безопасность', is_rank_of_profession: 'false' },
  ];

  const MOCK_PERFORMERS = [
    { counterparty_id: 2, name: 'ИП Иванов И.И.' },
    { counterparty_id: 3, name: 'ООО «Учебный центр»' },
  ];

  const MOCK_COUNTERPARTIES = [
    { counterparty_id: 10, shortname: 'ООО «Ромашка»', name: 'ООО «Ромашка»', type: 0 },
    { counterparty_id: 11, shortname: 'АО «Строймонтаж»', name: 'АО «Строймонтаж»', type: 0 },
    { counterparty_id: 12, shortname: 'ИП Петров П.П.', name: 'ИП Петров П.П.', type: 0 },
  ];

  const MOCK_ORDERS = [
    {
      order_id: 101,
      name_order: 'Заявка №101',
      invoice: 'СЧ-2025-101',
      counterparty_id: 10,
      counterparty_name: 'ООО «Ромашка»',
      count: 12,
      snils_check_sum: 1,
      status_id: 5,
      status_name: 'В работе',
      groups_count: 1,
      lstream_count: 1,
      activity: 2,
      activity2: 1,
      action: 'Подтвердить',
      action2: 'Отменить',
      new_status2: 13,
      payment_receipt: 0,
      contract_list: [],
      completed_a: [],
      date_order: '2025-03-15',
      number: '101',
      date_completed: '',
    },
    {
      order_id: 102,
      name_order: 'Заявка №102',
      invoice: '',
      counterparty_id: 11,
      counterparty_name: 'АО «Строймонтаж»',
      count: 5,
      snils_check_sum: 0,
      status_id: 3,
      status_name: 'Новая заявка',
      groups_count: 0,
      lstream_count: 0,
      activity: 2,
      activity2: 0,
      action: 'Принять',
      action2: '',
      new_status2: 0,
      payment_receipt: 0,
      contract_list: [],
      completed_a: [],
      date_order: '2025-04-01',
      number: '102',
      date_completed: '',
    },
    {
      order_id: 103,
      name_order: 'Заявка №103',
      invoice: 'CED-103',
      counterparty_id: 12,
      counterparty_name: 'ИП Петров П.П.',
      count: 0,
      snils_check_sum: 1,
      status_id: 8,
      status_name: 'Обучение завершено',
      groups_count: 2,
      lstream_count: 2,
      activity: 0,
      activity2: 0,
      action: '',
      action2: '',
      new_status2: 0,
      payment_receipt: 1,
      contract_list: [],
      completed_a: [],
      date_order: '2025-01-20',
      number: '103',
      date_completed: '2025-05-01',
    },
  ];

  const MOCK_STUDENTS = [
    { user_id: 1, lastname: 'Иванов', firstname: 'Иван', middlename: 'Иванович', snils: '123-456-789 00', counterparty_name: 'ООО «Ромашка»' },
    { user_id: 2, lastname: 'Петрова', firstname: 'Мария', middlename: 'Сергеевна', snils: '987-654-321 11', counterparty_name: 'ООО «Ромашка»' },
    { user_id: 3, lastname: 'Сидоров', firstname: 'Алексей', middlename: 'Петрович', snils: '111-222-333 44', counterparty_name: 'АО «Строймонтаж»' },
  ];

  const MOCK_TEACHERS = [
    { user_id: 201, lastname: 'Козлов', firstname: 'Дмитрий', middlename: 'Андреевич', status: 0 },
    { user_id: 202, lastname: 'Новикова', firstname: 'Елена', middlename: 'Викторовна', status: 0 },
  ];

  const MOCK_GROUPS = [
    { group_id: 1, name: 'Группа ОТ-03/25', course: 'Охрана труда', date_begin: '2025-03-01', date_end: '2025-03-15', status_name: 'В обучении', count: 12 },
    { group_id: 2, name: 'Группа ПБ-04/25', course: 'ПТМ', date_begin: '2025-04-10', date_end: '2025-04-12', status_name: 'Формируется', count: 5 },
  ];

  const MOCK_STATUSES = [
    { status_id: 1, name: 'Новая заявка' },
    { status_id: 3, name: 'Принята' },
    { status_id: 5, name: 'В работе' },
    { status_id: 8, name: 'Обучение завершено' },
    { status_id: 13, name: 'Отменена' },
  ];

  const MOCK_NEWS_CED = {
    0: { b_id: 1, title: 'Обновление системы Sed 2.0', text: 'Вышла новая версия интерфейса.', img_ads: '', date_pub: '2025-05-01' },
    1: { b_id: 2, title: 'Изменения в отчётности ЕИСОТ', text: 'Обновлены требования к выгрузке.', img_ads: '', date_pub: '2025-04-15' },
    2: { b_id: 3, title: 'График работы в праздники', text: 'Техподдержка доступна по расписанию.', img_ads: '', date_pub: '2025-04-01' },
  };

  const MOCK_NEWS_WORLD = {
    0: { b_id: 1, title: 'Новости образования', text: 'Обзор изменений в сфере ДПО.', img_ads: '', date_pub: '2025-05-10' },
    1: { b_id: 2, title: 'ФИС ФРДО', text: 'Актуализация форматов выгрузки.', img_ads: '', date_pub: '2025-04-20' },
    2: { b_id: 3, title: 'Минтруд', text: 'Рекомендации по охране труда.', img_ads: '', date_pub: '2025-03-25' },
  };

  /** @type {Record<string, unknown>} */
  const MOCKS = {
    'auth.is_auth': { role: 'admin', login: 'dev', mascotHome: 0 },
    'auth.info': { role: 'admin', login: 'dev', account_id: 1 },
    'auth.auth': { status: 0 },

    'courses.list': {
      status: STATUS_OK,
      role: 'admin',
      numPages: 1,
      page: 1,
      list: MOCK_COURSES,
    },
    'courses.object': {
      status: STATUS_OK,
      object: { course_id: 1, shortname: 'Охрана труда', description: 'Программа повышения квалификации.', price: '4500', hours: 40, category_id: 1 },
    },
    'courses.delete': { status: STATUS_OK },

    'course_category.list': { status: STATUS_OK, list: MOCK_CATEGORIES },
    'course_category.object': { status: STATUS_OK, object: MOCK_CATEGORIES[0] },

    'counterparty.list': { status: STATUS_OK, list: MOCK_COUNTERPARTIES },
    'counterparty.object': {
      status: STATUS_OK,
      result: { counterparty_id: 10, shortname: 'ООО «Ромашка»', name: 'ООО «Ромашка»', inn: '7701234567' },
    },

    'price.list': { status: STATUS_OK, result: { price: '4000' }, list: [] },

    'orders.list': {
      status: STATUS_OK,
      role: 'admin',
      numPages: 1,
      page: 1,
      counterparty_id: 0,
      list: MOCK_ORDERS,
    },
    'orders.object': {
      status: STATUS_OK,
      object: {
        order_id: 101,
        counterparty_id: 10,
        date_order: '2025-03-15',
        date_begin: '2025-03-20',
        date_end: '2025-04-20',
        payer_id: 10,
        balance_pay: 0,
        invoice: 'СЧ-2025-101',
        status_id: 5,
      },
    },
    'orders.update': { status: STATUS_OK },
    'orders.insert': { status: STATUS_OK, objectId: 999 },
    'orders.update_status': { status: STATUS_OK },
    'orders.delete': { status: STATUS_OK },

    'order_payer.object': { status: STATUS_OK, lastname: '', firstname: '', middlename: '', snils: '' },
    'order_payer.replace': { status: STATUS_OK },

    'status.list': { status: STATUS_OK, list: MOCK_STATUSES },

    'students.list': {
      status: STATUS_OK,
      role: 'admin',
      numPages: 1,
      page: 1,
      list: MOCK_STUDENTS,
    },
    'students.object': {
      status: STATUS_OK,
      object: { user_id: 1, lastname: 'Иванов', firstname: 'Иван', middlename: 'Иванович', snils: '123-456-789 00' },
    },

    'teacher.list': {
      status: STATUS_OK,
      role: 'admin',
      numPages: 1,
      page: 1,
      list: MOCK_TEACHERS,
    },
    'teacher.object': {
      status: STATUS_OK,
      object: { user_id: 201, lastname: 'Козлов', firstname: 'Дмитрий', middlename: 'Андреевич' },
    },
    'teacher.delete': { status: STATUS_OK },
    'teacher.archive': { status: STATUS_OK },

    'teacher2.items': { status: STATUS_OK, list: [] },
    'teacher2.search': { status: STATUS_OK, list: [] },

    'groups.list': {
      status: STATUS_OK,
      role: 'admin',
      numPages: 1,
      page: 1,
      list: MOCK_GROUPS,
    },
    'groups.object': { status: STATUS_OK, object: { group_id: 1, name: 'Группа ОТ-03/25' } },

    'lstream.list': {
      status: STATUS_OK,
      role: 'admin',
      numPages: 1,
      page: 1,
      list: [
        { lstream_id: 1, name: 'Поток ОТ-03/25', course: 'Охрана труда', date_begin: '2025-03-01', count: 12 },
      ],
    },
    'lstream.object': { status: STATUS_OK, object: { lstream_id: 1, course_id: 1, name: 'Поток ОТ-03/25' } },

    'lstream_teacher.list': { status: STATUS_OK, list: [] },
    'lstream_teacher.insert': { status: STATUS_OK },

    'template.list': {
      status: STATUS_OK,
      list: [
        { template_id: 1, name: 'Договор ООО', type: 1, hidden: 0 },
        { template_id: 2, name: 'Удостоверение', type: 2, hidden: 0 },
      ],
    },
    'template.object': { status: STATUS_OK, object: { template_id: 1, name: 'Договор ООО', type: 1 } },
    'template.save': { status: STATUS_OK },
    'template.delete': { status: STATUS_OK },
    'template.insert': { status: STATUS_OK, objectId: 10 },

    'template2.template_html': { status: STATUS_OK, template_html: '<p>Шаблон документа</p>' },
    'template2.html_save': { status: STATUS_OK },

    'teachers_commission.list': {
      status: STATUS_OK,
      list: [{ commission_id: 1, name: 'Комиссия по ОТ' }],
    },
    'teachers_commission.object': { status: STATUS_OK, object: { commission_id: 1, name: 'Комиссия по ОТ' } },

    'self.list': { status: STATUS_OK, list: [{ edition_id: 1, name: 'Редакция 2025' }] },
    'self.object': { status: STATUS_OK, object: { edition_id: 1 } },

    'role.list': {
      status: STATUS_OK,
      list: [
        { role_id: 1, role_name: 'admin' },
        { role_id: 2, role_name: 'manager' },
      ],
    },
    'role.object': { status: STATUS_OK, object: { role_id: 1, role_name: 'admin' } },

    'account1.list': {
      status: STATUS_OK,
      list: [{ account_id: 1, login: 'dev', role_name: 'admin', fullname: 'Разработчик' }],
    },
    'account1.object': { status: STATUS_OK, object: { account_id: 1, login: 'dev', fullname: 'Разработчик' } },

    'plan.object': { status: STATUS_OK, name: 'Учебный план', hours: 40, items: [] },
    'plan.rprg_detalies': { status: STATUS_OK, items: [] },

    'moodle.report_sync': { status: STATUS_OK },

    'news.list': MOCK_NEWS_CED,
    'news.news_isRead': [{ ced: '111', world: '111' }],

    'chart.count': {
      status: STATUS_OK,
      list: [
        { status: 'Новая заявка', count: 3 },
        { status: 'В работе', count: 7 },
        { status: 'Завершена', count: 12 },
      ],
    },

    'payment_1c.payment_1c': { status: STATUS_OK, role: 'admin' },

    'fm.list': { status: STATUS_OK, list: [] },
    'reports.list': { status: STATUS_OK, list: [] },
    'chart.list': { status: STATUS_OK, list: [] },
  };

  function parseKey(url, body) {
    const endpoint = (url.match(/api\/([a-z0-9_]+)_json\.php/i) || [])[1] || '';
    const action = body && typeof body === 'object' ? Object.keys(body)[0] : '';
    return `${endpoint}.${action}`;
  }

  function defaultForAction(action) {
    if (action === 'list' || action === 'items' || action === 'search') {
      return { status: STATUS_OK, role: 'admin', numPages: 1, page: 1, list: [] };
    }
    if (action === 'object' || action === 'rprg_detalies') {
      return { status: STATUS_OK, object: {}, result: {} };
    }
    if (action === 'delete' || action === 'save' || action === 'insert' || action === 'update' ||
        action === 'replace' || action === 'archive' || action === 'auth' || action === 'html_save') {
      return { status: STATUS_OK };
    }
    if (action === 'is_auth' || action === 'info') {
      return { role: 'admin', login: 'dev' };
    }
    return { status: STATUS_OK, list: [], numPages: 1, page: 1 };
  }

  function resolveMock(key, body) {
    if (MOCKS[key] !== undefined) {
      return MOCKS[key];
    }

    const action = key.split('.').pop() || '';
    const endpoint = key.split('.')[0] || '';

    if (endpoint === 'counterparty' && action === 'list') {
      const where = body?.list?.where || '';
      if (String(where).includes('type`=1')) {
        return { status: STATUS_OK, list: MOCK_PERFORMERS };
      }
      return { status: STATUS_OK, list: MOCK_COUNTERPARTIES };
    }

    if (endpoint === 'news' && action === 'list') {
      const table = body?.list?.table || '';
      if (table === 'b_news_world') return MOCK_NEWS_WORLD;
      return MOCK_NEWS_CED;
    }

    if (endpoint === 'helpers') {
      return {};
    }

    return defaultForAction(action);
  }

  const _post = axios.post.bind(axios);
  axios.post = function (url, body, config) {
    if (config && config.headers && config.headers['Content-Type'] === 'multipart/form-data') {
      console.log('[mock] multipart upload', url);
      return Promise.resolve({ data: { status: STATUS_OK } });
    }

    const key = parseKey(url, body);
    const data = resolveMock(key, body);
    const hasMock = MOCKS[key] !== undefined;
    console[hasMock ? 'log' : 'warn']('[mock]', key, hasMock ? '✓' : '(fallback)', body);
    return Promise.resolve({ data, status: 200, statusText: 'OK', headers: {}, config: {} });
  };

  const _get = axios.get.bind(axios);
  axios.get = function (url, config) {
    console.log('[mock GET]', url);
    return Promise.resolve({ data: {}, status: 200 });
  };

  window.__devMocks = { MOCKS, parseKey, resolveMock, PRIVILEGE_KEYS };
  console.info('[dev-mocks] axios перехвачен. Добавляйте фикстуры в dev/dev-mocks.js → MOCKS["endpoint.action"]');
})();
