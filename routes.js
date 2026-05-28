/**
 * Единый список роутов для Sedipo SPA.
 *
 * Используется:
 * - `index.php` (prod, PHP отдаёт страницы и глобальные скрипты)
 * - `dev.html` (локально без PHP)
 *
 * Важно: компоненты (Login, Home, OrdersList, ...) должны быть уже загружены
 * через `<script src="pages/*.vue.js">` до подключения этого файла.
 */
(function () {
  'use strict';

  /** @type {Array<any>} */
  const routes = [
    { path: '/login/:key?', component: Login, props: true },
    { path: '/', component: Home },
    { path: '/logout', name: 'logout', component: Logout },

    { path: '/courses_list/:counterpartyid?', name: 'courses_list', component: CoursesList, props: true },
    { path: '/course_edit/:courseid/:counterpartyid?/:categoryid?/:copyid?', name: 'course_edit', component: CourseEdit },
    { path: '/course_report/:courseid', name: 'course_report', component: CourseReport, props: true },
    { path: '/course_category_list', name: 'course_category_list', component: CourseCategoryList },
    { path: '/course_category_edit/:categoryid', name: 'course_category_edit', component: CourseCategoryEdit, props: true },
    { path: '/course_calendar_edit/:courseid/:variation?', name: 'course_calendar_edit', component: CourseCalendarEdit, props: true },
    { path: '/teachers_commission_list', name: 'teachers_commission_list', component: TeachersCommissionList },
    { path: '/teachers_commission_edit/:commissionid/:copyid?', name: 'teachers_commission_edit', component: TeachersCommissionEdit },
    { path: '/counterparty_list', name: 'counterparty_list', component: CounterpartyList, props: true },
    { path: '/counterparty_base_edit/:counterpartyid/:make?', name: 'counterparty_base_edit', component: CounterpartyBaseEdit, props: true },
    { path: '/self_list/', name: 'self_list', component: SelfList, props: true },
    { path: '/self_edit/:editionid/:maxid?', name: 'self_edit', component: SelfEdit, props: true },
    { path: '/job_title_list', component: JobTitleList },
    { path: '/job_title_edit/:job_titleid', name: 'job_title_edit', component: JobTitleEdit, props: true },
    { path: '/import', component: Import },
    { path: '/students_list/:counterpartyid?', name: 'students_list', component: UserList, props: true },
    { path: '/students_list_home/:counterpartyid?', name: 'students_list_home', component: UserListHome, props: true },
    { path: '/student_edit/:userid/:counterpartyid?/:usercounterpartyid?/:orderid?/:itemid?/:lastname?/:firstname?/:middlename?/:snils?/:job_title?', name: 'student_edit', component: UserEdit, props: true },
    { path: '/student_report/:userid?/:counterpartyid?', name: 'student_report', component: UserReport, props: true },
    { path: '/students_import/:counterpartyid/:orderid?/:item?', name: 'students_import', component: StudentsImport, props: true },
    { path: '/teacher_list', name: 'teacher_list', component: TeacherList, props: true },
    { path: '/teacher_edit/:userid', name: 'teacher_edit', component: TeacherEdit, props: true },
    { path: '/teacher_course/:userid', name: 'teacher_course', component: TeacherCourse, props: true },
    { path: '/orders_list/:counterpartyid?/:orderid?/:sort?', name: 'orders_list', component: OrdersList, props: true },
    { path: '/orders_list_redirect/:counterpartyid?/:orderid?/:sort?', name: 'orders_list_redirect', component: OrdersListRedirect, props: true },
    { path: '/order_edit/:orderid/:counterpartyid?', name: 'order_edit', component: OrderEdit, props: true },
    { path: '/order_items/:orderid/:counterpartyid?', name: 'order_items', component: OrderItems, props: true },
    { path: '/order_discounts/:orderid/:counterpartyid?', name: 'order_discounts', component: OrderDiscounts, props: true },
    { path: '/order_student/:orderid/:item?', name: 'order_student', component: OrderUser, props: true },
    { path: '/groups_list/:orderid?/:make?/:counterpartyid?', name: 'groups_list', component: GroupsList, props: true },
    { path: '/groups_list_redirect/:orderid?/:make?/:counterpartyid?', name: 'groups_list_redirect', component: GroupsListRedirect, props: true },
    { path: '/groupuser/:groupid', name: 'groupuser', component: GroupUser, props: true },
    { path: '/groupdel', name: 'groupdel', component: Home },
    { path: '/group_scheduler/:groupid/:orderid?/:counterpartyid?', name: 'group_scheduler', component: GroupScheduler, props: true },
    { path: '/group_report/:groupid', name: 'group_report', component: GroupReport, props: true },
    { path: '/lstream_list/:orderid?/:counterpartyid?/:lstreamid?', name: 'lstream_list', component: LstreamList, props: true },
    { path: '/lstream_list_redirect/:orderid?/:counterpartyid?/:lstreamid?', name: 'lstream_list_redirect', component: LstreamListRedirect, props: true },
    { path: '/lstream_edit/:lstreamid/:orderid?', name: 'lstream_edit', component: LstreamEdit, props: true },
    { path: '/lstream_items/:lstreamid', name: 'lstream_items', component: LstreamItems, props: true },
    { path: '/lstream_teacher/:lstreamid/:date?', name: 'lstream_teacher', component: LstreamTeacher, props: true },
    { path: '/template_list', name: 'template_list', component: TemplateList, props: true },
    { path: '/template1_edit/:templateid/:copyid?', name: 'template1_edit', component: Template1Edit, props: true },
    { path: '/template2_edit/:templateid', name: 'template2_edit', component: Template2Edit, props: true },
    { path: '/plan_edit/:courseid', name: 'plan_edit', component: PlanEdit, props: true },
    { path: '/set_template_contract', name: 'set_template_contract', component: SetTemplateContract, props: false },
    { path: '/orders_analytics/:counterpartyid?/:orderid?', name: 'orders_analytics', component: OrdersAnalytics, props: true },
    { path: '/orders_table', name: 'orders_table', component: OrdersTable, props: false },
    { path: '/stat_report', name: 'stat_report', component: StatReport, props: false },
    { path: '/eisot_import', name: 'eisot_import', component: EisotImport, props: false },
    { path: '/calendar', name: 'calendar', component: Calendar, props: false },
    { path: '/validity_period_counterparty_list', name: 'validity_period_counterparty_list', component: ValidityPeriodCounterpartyList, props: false },
    { path: '/validity_period/:counterpartyid?', name: 'validity_period', component: ValidityPeriod, props: true },
    { path: '/reports', component: Reports },
    { path: '/accountslist', name: 'accountslist', component: AccountsList },
    { path: '/rolelist', name: 'rolelist', component: RoleList },
    { path: '/accountedit/:accountid', name: 'accountedit', component: AccountEdit, props: true },
    { path: '/roleedit/:roleid', name: 'roleedit', component: RoleEdit, props: true },
    { path: '/about', name: 'about', component: About },
    { path: '/docs', name: 'docs', component: Docs },
    { path: '/contacts', name: 'contacts', component: Contacts },
    { path: '/wordvariables', name: 'wordvariables', component: Wordvariables },
  ];

  window.SEDIPO_ROUTES = routes;
})();

