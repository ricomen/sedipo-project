# Матрица прав (role_privileges) → страницы

Источник: ключи прав берутся из `index.php` (заполнение `$session_privileges`), маршруты — из массива `routes` в `index.php` (vue-router), дополнительные UI-точки — из `pages/*.vue.js`.

Семантика значений прав в UI: `0` (нет), `1` (чтение), `2` (запись).

> Важно: роутер сам по себе не блокирует переходы; ограничения в основном реализованы через UI (`v-if`) и проверки на стороне API.

## orders_list — Заявки

- `/orders_list_redirect/:counterpartyid?/:orderid?/:sort? (name: orders_list_redirect)`
- `/orders_list/:counterpartyid?/:orderid?/:sort? (name: orders_list)`
- `/order_edit/:orderid/:counterpartyid? (name: order_edit)`
- `/order_items/:orderid/:counterpartyid? (name: order_items)`
- `/order_discounts/:orderid/:counterpartyid? (name: order_discounts)`
- `/order_student/:orderid/:item? (name: order_student)`
- `/order_import/:orderid/:counterpartyid? (name: order_import)`

## groups_list — Учебные группы

- `/groups_list_redirect/:orderid?/:make?/:counterpartyid? (name: groups_list_redirect)`
- `/groups_list/:orderid?/:make?/:counterpartyid? (name: groups_list)`
- `/group_scheduler/:groupid/:orderid?/:counterpartyid? (name: group_scheduler)`
- `/group_report/:groupid (name: group_report)`
- `/groupuser/:groupid (name: groupuser)`

## lstream_list — Учебные потоки

- `/lstream_list_redirect/:orderid?/:counterpartyid?/:lstreamid? (name: lstream_list_redirect)`
- `/lstream_list/:orderid?/:counterpartyid?/:lstreamid? (name: lstream_list)`
- `/lstream_edit/:lstreamid/:orderid? (name: lstream_edit)`
- `/lstream_items/:lstreamid (name: lstream_items)`
- `/lstream_teacher/:lstreamid/:date? (name: lstream_teacher)`

## calendar — Расписание

- `/calendar (name: calendar)`

## eisot_import — Импорт ЕИСОТ

- `/eisot_import (name: eisot_import)`

## orders_analytics — Аналитика по заявкам

- `/orders_analytics/:counterpartyid?/:orderid? (name: orders_analytics)`

## orders_table — Сводная таблица по заявкам

- `/orders_table (name: orders_table)`

## stat_report — ФСН статистика

- `/stat_report (name: stat_report)`

## counterparty_list — Контрагенты

- `/counterparty_list (name: counterparty_list)`
- `/counterparty_base_edit/:counterpartyid/:make? (name: counterparty_base_edit)`
- `/counterparty_edit/:counterpartyid/:make? (name: counterparty_edit)`

## students_list — Слушатели

- `/students_list_home/:counterpartyid? (name: students_list_home)`
- `/students_list/:counterpartyid? (name: students_list)`
- `/student_edit/:userid/:counterpartyid?/:usercounterpartyid?/:orderid?/:itemid?/:lastname?/:firstname?/:middlename?/:snils?/:job_title? (name: student_edit)`
- `/student_report/:userid?/:counterpartyid? (name: student_report)`
- `/students_import/:counterpartyid/:orderid?/:item? (name: students_import)`

## course_category_list — Категории

- `/course_category_list (name: course_category_list)`
- `/course_category_edit/:categoryid (name: course_category_edit)`

## courses_list — Курсы/календарь

- `/courses_list/:counterpartyid? (name: courses_list)`
- `/course_edit/:courseid/:counterpartyid?/:categoryid?/:copyid? (name: course_edit)`
- `/course_report/:courseid (name: course_report)`
- `/course_calendar_edit/:courseid/:variation? (name: course_calendar_edit)`
- `/course_calendar_import/:courseid/:categoryid?/:groupid?/:orderid? (name: course_calendar_import)`
- `/plan_edit/:courseid (name: plan_edit)`

## teachers_commission_list — Состав комиссии

- `/teachers_commission_list (name: teachers_commission_list)`
- `/teachers_commission_edit/:commissionid/:copyid? (name: teachers_commission_edit)`

## teacher_list — Преподаватели

- `/teacher_list (name: teacher_list)`
- `/teacher_edit/:userid (name: teacher_edit)`
- `/teacher_course/:userid (name: teacher_course)`

## template_list — Шаблоны документов

- `/template_list (name: template_list)`
- `/template1_edit/:templateid/:copyid? (name: template1_edit)`
- `/template2_edit/:templateid (name: template2_edit)`

## set_template_contract — Назначение шаблонов договоров

- `/set_template_contract (name: set_template_contract)`

## validity_period_counterparty_list — Уведомления/сроки документов

- `/validity_period_counterparty_list (name: validity_period_counterparty_list)`
- `/validity_period/:counterpartyid? (name: validity_period)`

## accountedit — Настройки аккаунта

- `/accountedit/:accountid (name: accountedit)`

## self_list — Реквизиты организации

- `/self_list/ (name: self_list)`
- `/self_edit/:editionid/:maxid? (name: self_edit)`

## accountslist — Настройки доступа администраторов

- `/accountslist (name: accountslist)`

## rolelist — Роли пользователей

- `/rolelist (name: rolelist)`
- `/roleedit/:roleid (name: roleedit)`

## orders_list_buh — Бухгалтерские элементы в заявках

- (внутри страницы `pages/orders_list.vue.js`: блоки, печать, счёт)

## upload_flag — Загрузка документов

- (встречается в управлении ролями `pages/role_edit.vue.js` и используется в UI загрузок/эндпоинтах)

