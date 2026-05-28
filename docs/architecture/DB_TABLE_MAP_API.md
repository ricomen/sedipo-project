# Карта таблиц БД (по SQL из api/)

Источник: парсинг SQL-строк, переданных первым аргументом в `$dbh->prepare('...')` и `$dbh->query('...')` внутри **активных** файлов `api/*.php` (без бэкап-суффиксов).

- Просканировано файлов: **59**
- Найдено таблиц: **58**

> Важно: это карта *использования* таблиц в API, а не полная схема БД.
> Также SQL иногда собирается конкатенацией, поэтому часть ссылок может не попасть.

## Auth/ACL (БД аутентификации)

Всего: **4** таблиц.

- **a_session**: 51 упоминаний; файлы: api/account1_json.php, api/auth_json.php, api/counterparty2_json.php, api/counterparty_check_json.php, api/counterparty_contract_json.php, api/course_calendar2_json.php, api/fm_json.php, api/groups_json.php, … (+12)
- **a_account**: 36 упоминаний; файлы: api/account1_json.php, api/auth_json.php, api/counterparty2_json.php, api/counterparty_check_json.php, api/counterparty_contract_json.php, api/course_calendar2_json.php, api/fm_json.php, api/groups_json.php, … (+12)
- **a_customer**: 5 упоминаний; файлы: api/account1_json.php, api/auth_json.php
- **a_role**: 4 упоминаний; файлы: api/account1_json.php, api/orders_json.php

## Основные сущности (a_*)

Всего: **37** таблиц.

- **a_order_users**: 114 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/orders_json.php, api/students_import_json.php, api/students_json.php, api/students_lib.php
- **a_cohort**: 86 упоминаний; файлы: api/groups_json.php, api/groups_upload_json.php, api/lstream_json.php, api/orders_json.php, api/orders_upload_json.php
- **a_order_course**: 76 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/orders_json.php, api/students_lib.php
- **a_order**: 70 упоминаний; файлы: api/chart_json.php, api/groups_json.php, api/lstream_json.php, api/order_contract_upload_json.php, api/orders_json.php, api/orders_upload_json.php, api/payment_1c.php, api/students_json.php, … (+1)
- **a_course**: 51 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/moodle_json.php, api/orders_json.php, api/teacher2_json.php
- **a_user_counterparty**: 47 упоминаний; файлы: api/groups_json.php, api/orders_json.php, api/reports_json.php, api/students_import_json.php, api/students_json.php, api/students_lib.php
- **a_lstream**: 43 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/orders_json.php
- **a_users**: 36 упоминаний; файлы: api/api_core.php, api/counterparty2_json.php, api/groups_json.php, api/moodle_json.php, api/orders_json.php, api/plan_json.php, api/reports_json.php, api/students_import_json.php, … (+1)
- **a_course_category**: 31 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/moodle_json.php, api/orders_json.php, api/teacher2_json.php
- **a_counterparty_contract**: 20 упоминаний; файлы: api/counterparty_contract_json.php, api/counterparty_contract_upload_json.php, api/order_contract_upload_json.php, api/orders_json.php
- **a_cohort_scheduler**: 17 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/orders_json.php
- **a_counterparty**: 15 упоминаний; файлы: api/counterparty_check_json.php, api/groups_json.php, api/lstream_json.php, api/orders_json.php, api/students_json.php
- **a_reports**: 13 упоминаний; файлы: api/api_core.php, api/counterparty2_json.php, api/groups_json.php, api/lstream_json.php, api/moodle_json.php, api/orders_json.php, api/reports_json.php, api/students_json.php
- **a_order_group**: 9 упоминаний; файлы: api/orders_json.php
- **a_teacher_course**: 7 упоминаний; файлы: api/teacher2_json.php, api/teachers_commission1_json.php
- **a_groups_users**: 6 упоминаний; файлы: api/api_core.php, api/groups_json.php, api/students_import_json.php
- **a_order_cash**: 6 упоминаний; файлы: api/orders_json.php
- **a_order_price**: 6 упоминаний; файлы: api/orders_json.php
- **a_template**: 6 упоминаний; файлы: api/orders_json.php, api/template2_json.php
- **a_cache_course**: 5 упоминаний; файлы: api/api_core.php, api/moodle_json.php
- **a_contract**: 5 упоминаний; файлы: api/lstream_json.php, api/order_contract_upload_json.php, api/orders_json.php
- **a_job_title**: 5 упоминаний; файлы: api/groups_json.php, api/orders_json.php, api/students_json.php
- **a_course_calendar**: 4 упоминаний; файлы: api/course_calendar2_json.php
- **a_teacher**: 4 упоминаний; файлы: api/groups_json.php, api/lstream_json.php, api/teacher2_json.php
- **a_okpdtr**: 3 упоминаний; файлы: api/okpdtr_json.php
- **a_order_discounts**: 3 упоминаний; файлы: api/orders_json.php
- **a_progress**: 3 упоминаний; файлы: api/api_core.php, api/moodle_json.php
- **a_status**: 3 упоминаний; файлы: api/chart_json.php, api/orders_json.php
- **a_self**: 2 упоминаний; файлы: api/self_upload_json.php
- **a_users_passwd**: 2 упоминаний; файлы: api/orders_json.php, api/students_lib.php
- **a_groups**: 1 упоминаний; файлы: api/api_core.php
- **a_journal**: 1 упоминаний; файлы: api/lib.php
- **a_lstream_teacher**: 1 упоминаний; файлы: api/lstream_json.php
- **a_organizations**: 1 упоминаний; файлы: api/api_core.php
- **a_positions**: 1 упоминаний; файлы: api/api_core.php
- **a_teachers_commission**: 1 упоминаний; файлы: api/lstream_json.php
- **a_teachers_commission_teacher**: 1 упоминаний; файлы: api/teachers_commission_upload_json.php

## План/учебные программы (p_*)

Всего: **3** таблиц.

- **p_item**: 29 упоминаний; файлы: api/plan_json.php
- **p_rprg**: 10 упоминаний; файлы: api/plan_json.php
- **p__items**: 1 упоминаний; файлы: api/plan_json.php

## Moodle (mdl_*/md_*)

Всего: **11** таблиц.

- **mdl_course**: 8 упоминаний; файлы: api/moodle_json.php
- **mdl_course_modules**: 7 упоминаний; файлы: api/moodle_json.php
- **mdl_course_modules_completion**: 5 упоминаний; файлы: api/moodle_json.php
- **mdl_course_categories**: 4 упоминаний; файлы: api/moodle_json.php
- **md_course_categories**: 2 упоминаний; файлы: api/moodle_categories.php
- **mdl_context**: 2 упоминаний; файлы: api/moodle_json.php
- **mdl_user**: 2 упоминаний; файлы: api/moodle_json.php
- **mdl_customcert**: 1 упоминаний; файлы: api/moodle_json.php
- **mdl_customcert_issues**: 1 упоминаний; файлы: api/moodle_json.php
- **mdl_grade_grades**: 1 упоминаний; файлы: api/moodle_json.php
- **mdl_grade_items**: 1 упоминаний; файлы: api/moodle_json.php

## Прочее (не a_/p_/mdl_)

Всего: **3** таблиц.

- **users**: 11 упоминаний; файлы: api/json_lib.php, api/plan_json.php, api/students_json.php
- **fullname**: 2 упоминаний; файлы: api/json_lib.php, api/students_json.php
- **teachers_commission_teacher**: 1 упоминаний; файлы: api/teachers_commission1_json.php

