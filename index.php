<?php
session_start();
header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-store");
header("Pragma: no-cache");
header("Expires: 0");

if(isset($_GET['logout'])  ) {
    session_regenerate_id();
}

require_once '../config/config-auth.php';
$dbhost_a = $cfg_auth->host;
$dbuser_a = $cfg_auth->user;
$dbpassword_a = $cfg_auth->password;
$dbname_a = $cfg_auth->name;

try {  
    $dbh_a = new PDO("mysql:host=$dbhost_a;dbname=$dbname_a;charset=utf8", $dbuser_a, $dbpassword_a);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}

require_once 'config.php';
$dbhost = $cfg_auth->host;
$dbuser = $cfg_auth->user;
$dbpassword = $cfg_auth->password;
$dbname = $cfg_auth->name;

try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


$auth_session = 0;
$session_privileges = [];
$session_login = '';
$session_account_id = 0;
$session_counterparty_id = 0;
$session_role = '';
function is_auth($first) {
    global    $dbh, $dbh_a, $auth_session, $session_privileges, $session_login, $session_account_id, $session_counterparty_id, $session_role;

    if($first==0)
       return $auth_session;

    $php_session_id = session_id();
    $auth_session = 0;
    $session_privileges = [];
    $stmt = $dbh_a->prepare('SELECT `login`,  `a_account`.`account_id`, `a_account`.`role_id`, `a_account`.`customer_id`, `counterparty_id`, `billing`, `token`, `disk_used`	    FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)   LEFT JOIN `a_customer` USING(`customer_id`)   WHERE `session_id`=? ');
    $stmt->execute([ $php_session_id ]);
    if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
            //$auth_session = $row->token;
            $auth_session = 1;
            $session_counterparty_id = $row->counterparty_id;
            $session_account_id = $row->account_id;
            $session_login = $row->login;
            $session_role_id = $row->role_id;
            if($row->role_id > 0){
                $stmt2 = $dbh->prepare('SELECT  `role_name`,   `accountedit`, `self_list`, `rolelist`, `accountslist`, `set_template_contract`, `validity_period_counterparty_list`, `course_category_list`, `courses_list`, `teachers_commission_list`, `teacher_list`, `template_list`, `counterparty_list`, `students_list`, `orders_analytics`, `orders_table`, `stat_report`, `groups_list`, `lstream_list`, `calendar`, `eisot_import`, `orders_list`, `orders_list_buh`, `upload_flag`    FROM  `a_role`   WHERE `role_id`=? ');
                $stmt2->execute([ $row->role_id ]);
                if($row2 = $stmt2->fetch(PDO::FETCH_OBJ)) {  
                    $session_role = $row2->role_name;
                    $session_privileges = [
          "accountedit"=>$row2->accountedit,
          "self_list"=>$row2->self_list,
          "rolelist"=>$row2->rolelist,
          "accountslist"=>$row2->accountslist,
          "set_template_contract"=>$row2->set_template_contract,
          "validity_period_counterparty_list"=>$row2->validity_period_counterparty_list,
          "course_category_list"=>$row2->course_category_list,
          "courses_list"=>$row2->courses_list,
          "teachers_commission_list"=>$row2->teachers_commission_list,
          "teacher_list"=>$row2->teacher_list,
          "template_list"=>$row2->template_list,
          "counterparty_list"=>$row2->counterparty_list,
          "students_list"=>$row2->students_list,
          "orders_analytics"=>$row2->orders_analytics,
          "orders_table"=>$row2->orders_table,
          "stat_report"=>$row2->stat_report,
          "groups_list"=>$row2->groups_list,
          "lstream_list"=>$row2->lstream_list,
          "calendar"=>$row2->calendar,
          "eisot_import"=>$row2->eisot_import,
          "orders_list"=>$row2->orders_list,
          "orders_list_buh"=>$row2->orders_list_buh,
          "upload_flag"=>$row2->upload_flag
                    ];
                }
        }
        return $auth_session;
    }
    return 0;
}
is_auth(1);
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <title> <?php echo $BrowserTitle; ?> </title>
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />

    <!--<link href="css/bootstrap.min.css" rel="stylesheet" >-->
    <link href="css/bootstrap-lumen.min.css" rel="stylesheet" >
    <script src="css/bootstrap.bundle.min.js" ></script>


    <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext&amp;display=swap">-->

    <link rel="stylesheet" href="/font-awesome/css/all.min.css"  />

    <link rel="stylesheet" href="/css/style.css"  />
    <link rel="stylesheet" href="/css/home.css"  />
    <link rel="stylesheet" href="/css/polina.css"  />

<?php if($ESM){ ?>
    <script type="importmap"> {
        "imports": {
             "vue": "/node_modules/vue/dist/vue.esm-browser.prod.js",
             "vue-router": "/node_modules/vue-router/dist/vue-router.esm-browser.prod.js"
        }
    }
    </script>
    <script src="/node_modules/axios/dist/axios.min.js"></script>
<?php }else{ ?>
    <script src="js/vue.global.min.js"></script>
    <script src="js/vue-router.global.min.js"></script>
    <script src="js/axios.min.js"></script>
<?php } ?>

    <link  rel="stylesheet" href="js/jodit.min.css"/>
    <script src="js/jodit.min.js"></script>
    <style type="text/css"> .hidden { display: none; }</style>

</head>
<body>
<!--<link rel="stylesheet" type="text/css" href="file-explorer/file-explorer.css">
<script type="text/javascript" src="file-explorer/file-explorer.js"></script>
<script type="text/javascript" src="file-explorer/filemanager.js"></script>-->

<!--<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://unpkg.com/vue3-sortablejs/dist/vue3-sortablejs.global.js"></script>-->

<!--<script src="js/Sortable.min.js"></script>
<script src="js/vue3-sortablejs.global.js"></script>-->


<div id="app" class="container-lg" style="margin-top: 5px;">
	<div class="text-center" style="margin-top: 2px;">
		<router-view></router-view>
	</div>
</div>


<!-- Vue Pages -->
<script src="pages/login.vue.js?v=1"></script>
<script src="pages/logout.vue.js?v=1"></script>


<?php if(is_auth(0) ){ ?>
<script src="pages/home.vue.js?v=1"></script>
<script src="pages/courses_list.vue.js?v=1"></script>
<script src="pages/course_edit.vue.js?v=1"></script>
<script src="pages/course_report.vue.js?v=1"></script>
<script src="pages/course_category_list.vue.js?v=1"></script>
<script src="pages/course_category_edit.vue.js?v=1"></script>
<!--<script src="pages/course_calendar_import.vue.js?v=1"></script>-->
<script src="pages/course_calendar_edit.vue.js?v=1"></script>
<script src="pages/teachers_commission_list.vue.js?v=1"></script>
<script src="pages/teachers_commission_edit.vue.js?v=1"></script>
<script src="pages/counterparty_list.vue.js?v=1"></script>
<!--<script src="pages/counterparty_edit.vue.js?v=1"></script>-->
<script src="pages/counterparty_base_edit.vue.js?v=1"></script>
<script src="pages/self_list.vue.js?v=1"></script>
<script src="pages/self_edit.vue.js?v=1"></script>
<script src="pages/job_title_list.vue.js"></script>
<script src="pages/job_title_edit.vue.js"></script>
<script src="pages/students_list.vue.js?v=1"></script>
<script src="pages/students_list_home.vue.js?v=1"></script>
<script src="pages/student_edit.vue.js?v=1"></script>
<script src="pages/student_report.vue.js?v=1"></script>
<script src="pages/students_import.vue.js?v=1"></script>
<script src="pages/teachers_list.vue.js?v=1"></script>
<script src="pages/teacher_edit.vue.js?v=1"></script>
<script src="pages/teacher_course.vue.js?v=1"></script>
<script src="pages/orders_list.vue.js?v=1"></script>
<script src="pages/orders_list_redirect.vue.js?v=1"></script>
<script src="pages/order_edit.vue.js?v=1"></script>
<script src="pages/order_items.vue.js?v=1"></script>
<script src="pages/order_discounts.vue.js?v=1"></script>
<script src="pages/order_student.vue.js?v=1"></script>
<!--<script src="pages/order_import.vue.js?v=1"></script>-->
<script src="pages/groups_list.vue.js?v=1"></script>
<script src="pages/groups_list_redirect.vue.js?v=1"></script>
<!--<script src="pages/group_edit.vue.js?v=1"></script>-->
<script src="pages/group_scheduler.vue.js?v=1"></script>
<!--<script src="pages/group_items.vue.js?v=1"></script>-->
<script src="pages/group_student.vue.js?v=1"></script>
<script src="pages/group_report.vue.js?v=1"></script>
<script src="pages/lstream_list.vue.js?v=1"></script>
<script src="pages/lstream_list_redirect.vue.js?v=1"></script>
<script src="pages/lstream_edit.vue.js?v=1"></script>
<script src="pages/lstream_items.vue.js?v=1"></script>
<script src="pages/lstream_teacher.vue.js?v=1"></script>
<!--<script src="pages/cohort.vue.js?v=12"></script>-->
<script src="pages/reports.vue.js?v=2" ></script>
<script src="pages/accounts_list.vue.js?v=1"></script>
<script src="pages/role_list.vue.js?v=1"></script>
<script src="pages/account_edit.vue.js?v=1"></script>
<script src="pages/role_edit.vue.js?v=1"></script>
<script src="pages/template_list.vue.js?v=1"></script>
<script src="pages/template1_edit.vue.js?v=1"></script>
<script src="pages/template2_edit.vue.js?v=1"></script>
<script src="pages/plan_edit.vue.js?v=1"></script>
<script src="pages/set_template_contract.vue.js?v=1"></script>

<script src="pages/orders_analytics.vue.js?v=1"></script>
<script src="pages/orders_table.vue.js?v=1"></script>
<script src="pages/stat_report.vue.js?v=1"></script>
<script src="pages/eisot_import.vue.js?v=1"></script>
<script src="pages/calendar.vue.js?v=1"></script>
<script src="pages/validity_period_vue.js?v=1"></script>
<script src="pages/validity_period_counterparty_list_vue.js?v=1"></script>


<script src="pages/import.vue.js"></script>
<script src="pages/about.vue.js?v=1"></script>
<script src="pages/contacts.vue.js?v=1"></script>
<script src="pages/docs.vue.js?v=1"></script>
<script src="pages/wordvariables.vue.js?v=1"></script>
<!--<script src="pages/docs-howto.vue.js?v=1"></script>-->
<?php }else{  ?>
<script src="pages/home_redirect.vue.js?v=1"></script>
    <div align="center" id="sessionstart"><a  href="<?php echo $JsonApiURL; ?>#/login"  >
        <script>
            window.onload = function() {
               window.location.href = "<?php echo $JsonApiURL; ?>#/login";
            }
        </script>
    </a></div>
<?php } ?>



<script>
const AuthTitle =    '<?php echo $AuthTitle; ?>'
const BrowserTitle = '<?php echo $BrowserTitle; ?>'
const ShortTitle =   '<?php echo $ShortTitle; ?>'
const JsonApiURL =   '<?php echo $JsonApiURL; ?>'
const MoodleApiURL = '<?php echo $MoodleApiURL; ?>'
const LoginLogoImg = '<?php echo $LoginLogoImg; ?>'
const HoursPerDay = '<?php echo $HoursPerDay; ?>'
const IS_1C = <?php echo $IS_1C; ?>;
const IS_LMS = <?php echo $IS_LMS; ?>;
const IS_DEVELOPMENT = <?php echo $IS_DEVELOPMENT; ?>;
const IS_DEPRECATED = <?php echo $IS_DEPRECATED; ?>;
const IS_SECR = <?php echo $IS_SECR; ?>;
const IS_LITTLE = 0;

var session_t = {
      login: '<?php echo $session_login; ?>',
      sessionId: '<?php echo $auth_session; ?>',
      role: '<?php echo $session_role; ?>',
      //role_privileges: {},
};

var session_role_privileges = {
          accountedit: <?php echo intval($session_privileges["accountedit"]); ?>,
          self_list: <?php echo intval($session_privileges["self_list"]); ?>,
          rolelist: <?php echo intval($session_privileges["rolelist"]); ?>,
          accountslist: <?php echo intval($session_privileges["accountslist"]); ?>,
          set_template_contract: <?php echo intval($session_privileges["set_template_contract"]); ?>,
          validity_period_counterparty_list: <?php echo intval($session_privileges["validity_period_counterparty_list"]); ?>,
          course_category_list: <?php echo intval($session_privileges["course_category_list"]); ?>,
          courses_list: <?php echo intval($session_privileges["courses_list"]); ?>,
          teachers_commission_list: <?php echo intval($session_privileges["teachers_commission_list"]); ?>,
          teacher_list: <?php echo intval($session_privileges["teacher_list"]); ?>,
          template_list: <?php echo intval($session_privileges["template_list"]); ?>,
          counterparty_list: <?php echo intval($session_privileges["counterparty_list"]); ?>,
          students_list: <?php echo intval($session_privileges["students_list"]); ?>,
          orders_analytics: <?php echo intval($session_privileges["orders_analytics"]); ?>,
          orders_table: <?php echo intval($session_privileges["orders_table"]); ?>,
          stat_report: <?php echo intval($session_privileges["stat_report"]); ?>,
          groups_list: <?php echo intval($session_privileges["groups_list"]); ?>,
          lstream_list: <?php echo intval($session_privileges["lstream_list"]); ?>,
          calendar: <?php echo intval($session_privileges["calendar"]); ?>,
          eisot_import: <?php echo intval($session_privileges["eisot_import"]); ?>,
          orders_list: <?php echo intval($session_privileges["orders_list"]); ?>,
          orders_list_buh: <?php echo intval($session_privileges["orders_list_buh"]); ?>,
};

var session_var = {
    order_page: 1,
    order_ordersearch: '',
    order_invoicesearch: '',
    order_namesearch: '',
    order_counterparty_с_id: '',
    order_counterparty_с: '',
    order_datesearch: '',
    order_datesearch2: '',
    order_status: '',
    order_cancelled: 1,
    order_sort: 0,
    order_posTop: 0,
    counterparty_page: 1,
    counterparty_namesearch: '',
    counterparty_type: -1,
    student_page: 1,
    student_lastnamesearch: '',
    student_firstnamesearch: '',
    student_middlenamesearch: '',
    teacher_page: 1,
    teacher_lastnamesearch: '',
    teacher_firstnamesearch: '',
    teacher_middlenamesearch: '',
    template_page: 1, 
    template_type_id: '',
    courses_performer_id: '',
    courses_course_category: '',
    courses_search: '',
    group_datesearch: '',
    group_datesearch2: '',
    group_namesearch: '',
    group_counterpartysearch: 0,
    lstream_datesearch: '',
    lstream_datesearch2: '',
    lstream_namesearch: '',
    lstream_lstreamsearch: '',
    lstream_counterpartysearch: 0,
    student_report_user_id: 0,
    student_report_counterparty_id: 0,
};

var session_navigation = {
    lstream_lstream_id: 0,
};
</script>

<!-- Единый список роутов (общий с dev.html) -->
<script src="/routes.js?v=1"></script>

<?php if($ESM){ ?>
<script type="module">
    import { createApp  } from 'vue'
    import { createRouter, createWebHashHistory } from 'vue-router'
<?php }else{ ?>
<script>
<?php } ?>

var routes = window.SEDIPO_ROUTES || [];


<?php if($ESM){ ?>
const router = createRouter({
  history: createWebHashHistory(),
  routes, // short for `routes: routes`
});
const app = createApp({});
<?php }else{ ?>
const router = VueRouter.createRouter({
  history: VueRouter.createWebHashHistory(),
  routes, // short for `routes: routes`
})
const app = Vue.createApp({})
<?php } ?>
app.use(router)

//app.use(sortablejs);
//app.use(VueDraggableNext)
//draggable: window['VueDraggableNext'],

app.component('navigation',  {
  data() {
    return {
        sessionId: '<?php echo $auth_session; ?>',
        my_login: '<?php echo $session_login; ?>',
        role_privileges: {
          accountedit: <?php echo intval($session_privileges["accountedit"]); ?>,
          self_list: <?php echo intval($session_privileges["self_list"]); ?>,
          rolelist: <?php echo intval($session_privileges["rolelist"]); ?>,
          accountslist: <?php echo intval($session_privileges["accountslist"]); ?>,
          set_template_contract: <?php echo intval($session_privileges["set_template_contract"]); ?>,
          validity_period_counterparty_list: <?php echo intval($session_privileges["validity_period_counterparty_list"]); ?>,
          course_category_list: <?php echo intval($session_privileges["course_category_list"]); ?>,
          courses_list: <?php echo intval($session_privileges["courses_list"]); ?>,
          teachers_commission_list: <?php echo intval($session_privileges["teachers_commission_list"]); ?>,
          teacher_list: <?php echo intval($session_privileges["teacher_list"]); ?>,
          template_list: <?php echo intval($session_privileges["template_list"]); ?>,
          counterparty_list: <?php echo intval($session_privileges["counterparty_list"]); ?>,
          students_list: <?php echo intval($session_privileges["students_list"]); ?>,
          orders_analytics: <?php echo intval($session_privileges["orders_analytics"]); ?>,
          orders_table: <?php echo intval($session_privileges["orders_table"]); ?>,
          stat_report: <?php echo intval($session_privileges["stat_report"]); ?>,
          groups_list: <?php echo intval($session_privileges["groups_list"]); ?>,
          lstream_list: <?php echo intval($session_privileges["lstream_list"]); ?>,
          calendar: <?php echo intval($session_privileges["calendar"]); ?>,
          eisot_import: <?php echo intval($session_privileges["eisot_import"]); ?>,
          orders_list: <?php echo intval($session_privileges["orders_list"]); ?>,
          orders_list_buh: <?php echo intval($session_privileges["orders_list_buh"]); ?>,
          upload_flag : <?php echo intval($session_privileges["upload_flag"]); ?>,
        },

        account_id: <?php echo intval($session_account_id); ?>,
        counterparty_id: <?php echo intval($session_counterparty_id); ?>,
        role: '<?php echo $session_role; ?>',
        notefication: 1,
    }
  },

   mounted() {
      this.my_login==' '
      setTimeout(() => { if(this.my_login==' ') this.my_login='' }, 15000);
    },

  template: `
<?php if(is_auth(0) ){ ?>
<nav class="navbar navbar-expand-lg bg-light bg-body-tertiary " data-bs-theme="light"  style="background-color: #c5c5c5; ">
    <div class="container-fluid">
      <a class="navbar-brand" href="/" style="margin-left: 8px;"> <img src="<?php echo $LogoImg;?>" width="30"> </a>

      <div class="collapse navbar-collapse" id="navbar-content">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
          <!--<li class="nav-item">
            <router-link  to="/"  class="nav-link active" aria-current="page" > Главная</router-link>
          </li>-->

          <li v-if="role_privileges.orders_list==2 || role_privileges.orders_list==1" class="nav-item">
            <router-link  to="/orders_list_redirect/"  class="nav-link active" aria-current="page" > Заявки </router-link>
          </li>

          <li v-if="role_privileges.groups_list==2 || role_privileges.groups_list==1 || role_privileges.lstream_list==2 || role_privileges.lstream_list==1 || role_privileges.calendar==2 || role_privileges.calendar==1 || role_privileges.eisot_import==2 || role_privileges.eisot_import==1" class="nav-item dropdown">
            <a href="#"  class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" > Обучение </a>
            <ul v-if="role!='counterparty'"   class="dropdown-menu shadow" style="--bs-dropdown-min-width: 30rem;">
              <li v-if="role_privileges.groups_list==2 || role_privileges.groups_list==1" ><router-link  to="/groups_list_redirect/"  class="dropdown-item"  aria-current="page" > Учебные группы </router-link ></li>
              <li v-if="role_privileges.lstream_list==2 || role_privileges.lstream_list==1" ><router-link  to="/lstream_list_redirect/"  class="dropdown-item"  aria-current="page" > Учебные потоки </router-link ></li>
              <li v-if="role_privileges.calendar==2 || role_privileges.calendar==1" ><router-link  :to="{ name: 'calendar' }"  class="dropdown-item"   > Расписание </router-link></li>
              <li v-if="role_privileges.eisot_import==2 || role_privileges.eisot_import==1" ><router-link  to="/eisot_import/"  class="dropdown-item"  aria-current="page" > Импорт номеров удостоверений из ЕИСОТ </router-link ></li>
            </ul>
          </li>

          <li v-if="role_privileges.orders_analytics==2 || role_privileges.orders_analytics==1 || role_privileges.orders_table==2 || role_privileges.orders_table==1 || role_privileges.stat_report==2 || role_privileges.stat_report==1 "  class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;"  >Аналитика</a>
            <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 30rem;">
              <li v-if="role_privileges.orders_analytics==2 || role_privileges.orders_analytics==1" ><router-link   :to="{ name: 'orders_analytics', params: { counterpartyid: 0 }}"  class="dropdown-item"  aria-current="page" > Аналитика по заявкам </router-link></li>
              <li v-if="role_privileges.orders_table==2 || role_privileges.orders_table==1" ><router-link   :to="{ name: 'orders_table'}"   class="dropdown-item"  aria-current="page" > Сводная таблица по заявкам </router-link></li>
              <li v-if="role_privileges.stat_report==2 || role_privileges.stat_report==1" ><router-link   :to="{ name: 'stat_report' }"   class="dropdown-item"   > Отчет - Федеральное статистическое наблюдение</router-link></li>
              <!--<li><router-link  to="/job_title_list"   class="dropdown-item" > Должности</router-link></li>-->
            </ul>
          </li>

          <li v-if="role!='counterparty' && (role_privileges.counterparty_list==2 || role_privileges.counterparty_list==1 || role_privileges.students_list==2 || role_privileges.students_list==1)"  class="nav-item dropdown">
            <a href="#"  class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" > Контрагенты </a>
            <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 20rem;" >
              <li v-if="role_privileges.counterparty_list==2 || role_privileges.counterparty_list==1" ><router-link  to="/counterparty_list"  class="dropdown-item"   >Контрагенты </router-link ></li>
              <li v-if="role_privileges.students_list==2 || role_privileges.students_list==1" ><router-link  :to="{ name: 'students_list_home', params: { counterpartyid: 0 }}"  class="dropdown-item"  aria-current="page" > Cписок слушателей </router-link></li>
            </ul>
          </li>
 
          <li v-if="role_privileges.course_category_list==2 || role_privileges.course_category_list==1 || role_privileges.courses_list==2 || role_privileges.courses_list==1 || role_privileges.teachers_commission_list==2 || role_privileges.teachers_commission_list==1 || role_privileges.teacher_list==2 || role_privileges.teacher_list==1 || role_privileges.template_list==2 || role_privileges.template_list==1" class="nav-item dropdown">
            <a href="#"  class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" > Настройки </a>
            <ul v-if="role!='counterparty'"  class="dropdown-menu shadow" style="--bs-dropdown-min-width: 20rem;" >
              <li v-if="role_privileges.course_category_list==2 || role_privileges.course_category_list==1" ><router-link  to="/course_category_list"  class="dropdown-item"   >Категории </router-link ></li>
              <li v-if="role_privileges.courses_list==2 || role_privileges.courses_list==1" ><router-link  to="/courses_list"  class="dropdown-item"   >Курсы </router-link ></li>
              <li v-if="role_privileges.teachers_commission_list==2 || role_privileges.teachers_commission_list==1" ><router-link  to="/teachers_commission_list"  class="dropdown-item"   >Состав комиссии </router-link ></li>
              <li v-if="role_privileges.teacher_list==2 || role_privileges.teacher_list==1" ><router-link  to="/teacher_list"  class="dropdown-item"  aria-current="page" > Преподаватели </router-link></li>
              <li v-if="role_privileges.template_list==2 || role_privileges.template_list==1" ><router-link  to="/template_list"  class="dropdown-item"  >Шаблоны документов </router-link ></li>
            </ul>
          </li>

          <li   class="nav-item">
          </li>

        
         <li  class="nav-item dropdown"><a class="nav-link " href="/#/contacts/#">Контакты</a> <!-- Здесь размещена информация обязательная к публикации согласно 152-ФЗ.  Этот пункт должен отображаться всегда без ограничениия по ролям -->
            <!--<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" >О системе</a>
            <ul class="dropdown-menu shadow">
              <li><a class="dropdown-item" href="/#/contacts/#">Контакты</a></li>
              <li><a class="dropdown-item" href="#">Политика конфиденциальности</a></li>
              <li><a class="dropdown-item" href="#">Политика обработки персональных данных</a></li>
              <li><a class="dropdown-item" href="#">Реквизиты</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Информация о системе</a></li>-->
            </ul>
          </li>
        </ul>

        <div class="d-flex ms-auto">
          <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
           <!--<li  > <a  href="https://cloud.sedipo.ru" target="_blank" class="nav-link" title="Облачное хранилище"> <i class="fa-solid fa-cloud"></i> </a></li>-->
            <li class="nav-item dropdown"> 
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside"> <span v-if="notefication"><i class="fa-solid fa-bell"></i></span><span v-else><i class="fa-regular fa-bell"></i></span> </a>
              <ul class="dropdown-menu shadow dropdown-menu-end" style="padding: 10px; --bs-dropdown-min-width: 45rem;" >
                <li v-if="notefication" ><router-link  to="/validity_period_counterparty_list" class="nav-link active"  >Уведомления об окончании срока действия документов сотрудников организаций</router-link></li>
              </ul>
            </li>
            <li  class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside"> <i class="fa-solid fa-circle-user" style="font-size: larger;"></i> <span style="color: #212529; ">{{my_login}} 
              <!--id= {{this.account_id}}  --></span></a>
              <ul class="dropdown-menu shadow dropdown-menu-end" style="--bs-dropdown-min-width: 15rem;" >
                <li v-if="role!='counterparty' && role_privileges.accountedit !=0 || role_privileges.accountedit !=0 " ><router-link  :to="{ name: 'accountedit', params:{ accountid: account_id }}"  class="dropdown-item"  > Настройки аккаунта</router-link></li>
                <!--<li v-else ><router-link  :to="{ name: 'counterparty_base_edit', params:{ counterpartyid: counterparty_id }}"   class="dropdown-item"  >Настройки аккаунта</router-link></li>-->
                <li v-if="role_privileges.self_list==2 || role_privileges.self_list==1" ><router-link  :to="{ name: 'self_list', params: {  }}"   class="dropdown-item"  >Реквизиты организации</router-link></li>
                <li v-if="role=='admin'" ><hr class="dropdown-divider"></li>
                <li v-if="role_privileges.rolelist==2 || role_privileges.rolelist==1" ><router-link  to="/rolelist" class="dropdown-item" > Роли пользователей</router-link></li>
                <li v-if="role_privileges.accountslist==2 || role_privileges.accountslist==1" ><router-link  to="/accountslist"  class="dropdown-item"  > Настройки доступа администраторов</router-link></li>
                <li><hr class="dropdown-divider"></li>
                <li><a  href="#/logout?logout=1"  class="dropdown-item" >Выход</a></li>
              </ul>
            </li>
            <li v-if="my_login==''" >
               <a  href="#/logout"><button class="btn btn-danger">Авторизация</button></a>
            </li>

           
          </ul>
        </div>

      </div>
      <div class="navbar-brand" style="margin-left: 8px;">  <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas"><span class="navbar-toggler-icon"></span></button></div>
    </div>
  </nav>



<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasLabel"></h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
  </div>
  <div class="offcanvas-body">

        <ul class="navbar-nav" style="text-align: left;" >
          <li><router-link  to="/orders_list_red"  class="nav-link active" aria-current="page"> Заявки </router-link></li>

          <li v-if="role_privileges.groups_list==2 || role_privileges.groups_list==1" ><router-link  to="/groups_list/" class="nav-link active" aria-current="page" > Учебные группы </router-link ></li>
          <li v-if="role_privileges.lstream_list==2 || role_privileges.lstream_list==1" ><router-link  to="/lstream_list/" class="nav-link active" aria-current="page" > Учебные потоки </router-link ></li>
          <li v-if="role_privileges.calendar==2 || role_privileges.calendar==1" ><router-link  :to="{ name: 'calendar' }"  class="nav-link active"  > Расписание </router-link></li>
          <li v-if="role_privileges.eisot_import==2 || role_privileges.eisot_import==1" ><router-link  to="/eisot_import/" class="nav-link active" aria-current="page" > Импорт номеров удостоверений из ЕИСОТ </router-link ></li>

          <li v-if="role_privileges.counterparty_list==2 || role_privileges.counterparty_list==1" ><router-link  to="/counterparty_list" class="nav-link active"  >Контрагенты </router-link ></li>
          <li v-if="role_privileges.students_list==2 || role_privileges.students_list==1" ><router-link  :to="{ name: 'students_list_home', params: { counterpartyid: 0 }}"  class="nav-link active" aria-current="page" > Cписок слушателей </router-link></li>

          <li v-if="role_privileges.course_category_list==2 || role_privileges.course_category_list==1" ><router-link  to="/course_category_list" class="nav-link active"  >Категории </router-link ></li>
          <li v-if="role_privileges.courses_list==2 || role_privileges.courses_list==1" ><router-link  to="/courses_list" class="nav-link active" >Курсы </router-link ></li>
          <li v-if="role_privileges.teachers_commission_list==2 || role_privileges.teachers_commission_list==1" ><router-link  to="/teachers_commission_list" class="nav-link active"  >Состав комиссии </router-link ></li>
          <li v-if="role_privileges.teacher_list==2 || role_privileges.teacher_list==1" ><router-link  to="/teacher_list"  class="nav-link active" aria-current="page" > Преподаватели </router-link></li>

          <li v-if="role!='counterparty'" ><router-link  :to="{ name: 'accountedit', params:{ accountid: account_id }}" class="nav-link active" > Настройки аккаунта </router-link></li>
          <li v-else ><router-link  :to="{ name: 'counterparty_base_edit', params:{ counterpartyid: counterparty_id }}"  class="nav-link active" >Настройки аккаунта</router-link></li>
          <li v-if="role_privileges.self_list==2 || role_privileges.self_list==1" ><router-link  :to="{ name: 'self_list', params: {  }}"  class="nav-link active" >Реквизиты организации</router-link></li>
          <li v-if="role_privileges.accountslist==2 || role_privileges.accountslist==1" ><router-link  to="/accountslist" class="nav-link active" > Настройки доступа администраторов</router-link></li>
          <li><a  href="#/logout" class="nav-link active">Выход</a></li>
      </ul>

  </div>
</div>

<?php }else{ ?>
	    <a  href="<?php echo $JsonApiURL; ?>#/login"  >
		<button class="btn btn-link"> Авторизация </button>
	    </a>
<?php } ?>

<br />`
})

app.mount('#app')
</script>



<script src="js/chart.js"></script>
<script>
  function displayChart(type, ordersCount, searchDate) {
    if (ordersCount) {
  // вывод диаграммы
  let chartType = 'bar';
  if(type != '') chartType = type;
  const ctx = document.getElementById('myChart');
  //console.log(ctx);

  let myChart = Chart.getChart("myChart");
  if( myChart ) { myChart.destroy(); }

  new Chart(ctx, {
    type: chartType,
    data: {
      labels: ordersCount.map(row => row.status),
      datasets: [{
        label: searchDate,
        data: ordersCount.map(row => row.count),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      barThickness:30,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  })

  }
};
</script>



<?php if(is_auth(0) ){ ?>
<div style="background-color: #212529; ">
<div id="app" class="container" style="margin-top: 5px;">
<nav  style="background-color: #212529; color: white; ">
  <div class="container-fluid">    
   <div class="row">
       <div class="col-3">
         <p style="margin-top: 10px;">г. Москва	</p>
       </div>
       <div class="col-3">
           <p style="margin-top: 10px; margin-left: 70px;">   

           <div style="text-align: left;"><a href="/#" class="nav-link">Главная</a></div>
           <div style="text-align: left;"><a href="/#/docs" class="nav-link" aria-current="page">Инструкции по работе в СЭД</a></div>
           <div style="text-align: left;"><a href="/#" class="nav-link" aria-current="page">Политика конфиденциальности</a></div>
           <div style="text-align: left;"><a href="/#/contacts/#" class="nav-link" aria-current="page">Контакты</a></div>
       </div>
       <div class="col">
         <p style="margin-top: 42px; text-align: right">© 2024-<script>document.write( new Date().getFullYear() );</script> ООО «Интегрированное программное обеспечение»</p>            
       </div>
   </div> 
 </div> 
</nav>   
</div>
</div>
<?php } ?>


</body>
</html>
