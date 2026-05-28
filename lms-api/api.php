<?php

define('TOKEN', 'be1f29579a0fc77619a5fff857b53d5e');
define('MOODLE_URL', 'https://demosdo.sedipo.ru');
define('API_URL', MOODLE_URL . '/webservice/rest/server.php');

/**
 * Базовый метод для вызова API Moodle
 *
 * @param string $function Название функции API
 * @param array $params Параметры запроса
 * @return array Ответ от API в виде массива
 */
function call_api($function, $params = []) {
    $payload = [
        'wstoken' => TOKEN,
        'wsfunction' => $function,
        'moodlewsrestformat' => 'json'
    ];

    $payload = array_merge($payload, $params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception("HTTP Error: $httpCode - $response");
    }

    $result = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON decode error: ' . json_last_error_msg());
    }

    if (isset($result['exception'])) {
        throw new Exception('Moodle API error: ' . $result['message']);
    }

    return $result;
}

/**
 * Создание пользователей
 *
 * @param array $users_list Список массивов с данными пользователей
 *                        Пример: [[
 *                            'username' => 'student1',
 *                            'password' => 'TempPass123!',
 *                            'firstname' => 'Иван',
 *                            'lastname' => 'Иванов',
 *                            'email' => 'ivanov@example.com',
 *                        ]]
 * @return array Список созданных пользователей с их ID
 *              Пример: [['id' => 1587, 'username' => 'student1']]
 */
function create_users($users_list) {
    $params = [];

    foreach ($users_list as $i => $user) {
        // Обязательные поля
        $params["users[$i][username]"] = $user['username'];
        $params["users[$i][password]"] = $user['password'];
        $params["users[$i][firstname]"] = $user['firstname'];
        $params["users[$i][lastname]"] = $user['lastname'];
        $params["users[$i][email]"] = $user['email'];
        $params["users[$i][auth]"] = 'manual';  // По умолчанию
    }

    $result = call_api('core_user_create_users', $params);
    return $result;
}

/**
 * Запись пользователей в курс
 *
 * @param int $course_id ID курса
 * @param array $userids Список ID пользователей для зачисления
 * @param int $role_id ID роли (по умолчанию 5 - студент)
 *                    Другие роли: 1 - менеджер, 2 - преподаватель, 3 - ассистент, 4 - студент
 * @param int $suspend 0 - активен, 1 - приостановлен
 * @return array Результат операции
 */
function enroll_users_course($course_id, $userids, $role_id = 5, $suspend = 0) {
    $params = [];

    foreach ($userids as $i => $user_id) {
        $params["enrolments[$i][courseid]"] = $course_id;
        $params["enrolments[$i][userid]"] = $user_id;
        $params["enrolments[$i][roleid]"] = $role_id;
        $params["enrolments[$i][suspend]"] = $suspend;
    }

    return call_api('enrol_manual_enrol_users', $params);
}

/**
 * Отчислить пользователей из курса
 *
 * @param int $course_id ID курса
 * @param array $userids Список ID пользователей для отчисления
 * @return array Результат операции
 */
function unenrol_users_course($course_id, $userids) {
    $params = [];

    foreach ($userids as $i => $user_id) {
        $params["enrolments[$i][courseid]"] = $course_id;
        $params["enrolments[$i][userid]"] = $user_id;
    }

    return call_api('enrol_manual_unenrol_users', $params);
}

/**
 * Оценки пользователей
 *
 * @param int $course_id ID курса
 * @param int $userid ID пользователя (0 для всех)
 * @param int $groupid ID группы
 * @return array Список оценок пользователей
 */
function get_grades_users($course_id, $userid = 0, $groupid = 0) {
    $params = [
        'courseid' => $course_id,
        'userid' => $userid,
        'groupid' => $groupid,
    ];

    $response = call_api('gradereport_user_get_grade_items', $params);
    $result = [];

    foreach ($response['usergrades'] as $item) {
        $grade_user = [
            'userid' => $item['userid'],
            'fullname' => $item['userfullname'],
            'grades' => [],
            'grade' => null
        ];

        foreach ($item['gradeitems'] as $grade) {
            if ($grade['itemtype'] === 'course') {
                $grade_user['grade'] = $grade['percentageformatted'];
            } else {
                $grade_user['grades'][] = [
                    'itemname' => $grade['itemname'],
                    'grade' => $grade['percentageformatted'],
                ];
            }
        }

        $result[] = $grade_user;
    }

    return $result;
}

/**
 * Создание когорты (глобальной группы)
 *
 * @param string $name Название когорты
 * @param string $idnumber Уникальный идентификатор
 * @param string $description Описание
 * @return array Информация о созданной когорте
 *              Пример: ['id' => 42, 'name' => 'Когорта 1', 'idnumber' => 'gg_99', 'description' => '', 'descriptionformat' => 1, 'visible' => true, 'theme' => '']
 */
function create_cohort($name, $idnumber, $description = '') {
    $params = [
        'cohorts[0][categorytype][type]' => 'system',
        'cohorts[0][categorytype][value]' => 0,
        'cohorts[0][name]' => $name,
        'cohorts[0][idnumber]' => $idnumber,
        'cohorts[0][description]' => $description,
    ];

    $result = call_api('core_cohort_create_cohorts', $params);
    return !empty($result) ? $result[0] : null;
}

/**
 * Добавление пользователей в когорту
 *
 * @param int $cohortid ID когорты
 * @param array $userids Список ID пользователей [10, 11, 12, 13, 14]
 * @return array Результат операции
 */
function add_users_to_cohort($cohortid, $userids) {
    $params = [];

    foreach ($userids as $i => $userid) {
        $params["members[$i][cohorttype][type]"] = 'id';
        $params["members[$i][cohorttype][value]"] = (string)$cohortid;
        $params["members[$i][usertype][type]"] = 'id';
        $params["members[$i][usertype][value]"] = (string)$userid;
    }

    return call_api('core_cohort_add_cohort_members', $params);
}

/**
 * Создание группы в курсе
 *
 * @param int $courseid ID курса
 * @param string $name Название группы
 * @param string $description Описание группы
 * @param string $idnumber Идентификатор группы
 * @return array Информация о созданной группе
 *              Пример: ['id' => 22, 'courseid' => 4, 'name' => 'Group 5', 'description' => '', 'descriptionformat' => 1, 'enrolmentkey' => '', 'idnumber' => '', 'visibility' => 0, 'participation' => true]
 */
function create_group($courseid, $name, $description = '', $idnumber = '') {
    $params = [
        'groups[0][courseid]' => $courseid,
        'groups[0][name]' => $name,
        'groups[0][description]' => $description,
        'groups[0][idnumber]' => $idnumber,
    ];

    $result = call_api('core_group_create_groups', $params);
    return !empty($result) ? $result[0] : null;
}

/**
 * Добавление пользователей в группу
 *
 * @param int $groupid ID группы
 * @param array $userids Список ID пользователей [10, 11, 12, 13, 14]
 * @return array Результат операции
 */
function add_users_to_group($groupid, $userids) {
    $params = [];

    foreach ($userids as $i => $userid) {
        $params["members[$i][groupid]"] = $groupid;
        $params["members[$i][userid]"] = $userid;
    }

    return call_api('core_group_add_group_members', $params);
}

/**
 * Получение списка курсов
 *
 * @return array Список курсов
 *              Пример: [['id' => 1, 'title' => 'Система дистанционного обучения ИТЦ Безопасность', 'shortname' => 'ИТЦ']]
 */
function get_course_list() {
    $result = call_api('core_course_get_courses');

    $courses = [];
    foreach ($result as $item) {
        $courses[] = [
            'id' => $item['id'],
            'title' => $item['fullname'],
            'shortname' => $item['shortname']
        ];
    }

    return $courses;
}

/**
 * Получение списка категорий
 *
 * @return array Список категорий
 *              Пример: [['id' => 1, 'title' => 'Категория 1']]
 */
function get_category_list() {
    $result = call_api('core_course_get_categories');

    $categories = [];
    foreach ($result as $item) {
        $categories[] = [
            'id' => $item['id'],
            'title' => $item['name']
        ];
    }

    return $categories;
}

/**
 * Создание курса
 *
 * @param string $fullname Полное название курса
 * @param string $shortname Короткое название курса - УНИКАЛЬНОЕ ЗНАЧЕНИЕ
 * @param int $categoryid ID категории Moodle category
 * @return int ID созданного курса Moodle course
 */
function create_course($fullname, $shortname, $categoryid = 1) {
    $params = [
        'courses[0][fullname]' => $fullname,
        'courses[0][shortname]' => $shortname,
        'courses[0][categoryid]' => $categoryid,
    ];

    $result = call_api('core_course_create_courses', $params);
    return $result[0]['id'];
}

?>