<?php
header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-store");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

if(isset($_GET['logout'])  ) {
session_regenerate_id();
}



require_once 'config.php';
$dbhost = $cfg->host;
$dbuser = $cfg->user;
$dbpassword = $cfg->password;
$dbname = $cfg->name;


try {  
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);  
}  
catch(PDOException $e) {  
    echo $e->getMessage();  
}


function is_auth() {
    global    $dbh;

    $sessionid = session_id();
//echo $sessionid;
	$stmt = $dbh->prepare('SELECT `login`, `role`, `a_account`.`account_id`, `counterparty_id`  FROM `a_session` LEFT JOIN  `a_account` USING(`account_id`)  WHERE `token`=? ');
    $stmt->execute([$sessionid]);
	if($row = $stmt->fetch(PDO::FETCH_OBJ)) {  
//print_r($row);
	    return 1;
	}

    return 0;
}

?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Институт Профессионального Образования </title>

    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>-->

    <link href="js/bootstrap.min.css" rel="stylesheet" >
    <script src="js/bootstrap.bundle.min.js" ></script>

    <!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>-->

    <link href='https://fonts.googleapis.com/css?family=Manrope' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext&amp;display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"  />

    <link rel="stylesheet" href="/css/style.css"  />

    <script src="https://unpkg.com/vue@3"></script>
    <script src="https://unpkg.com/vue-router@4"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<!--    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />
-->
</head>
<body>
<link rel="stylesheet" type="text/css" href="file-explorer/file-explorer.css">
<script type="text/javascript" src="file-explorer/file-explorer.js"></script>
<script type="text/javascript" src="file-explorer/filemanager.js"></script>

<!--<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://unpkg.com/vue3-sortablejs/dist/vue3-sortablejs.global.js"></script>-->

<script src="js/Sortable.min.js"></script>
<script src="js/vue3-sortablejs.global.js"></script>


<div id="app" class="container-lg" style="margin-top: 5px;">
	<div class="text-center" style="margin-top: 2px;">
		<router-view></router-view>
	</div>
</div>


<!-- Vue Pages -->
<script src="pages/login.vue.js?v=1"></script>
<script src="pages/logout.vue.js?v=1"></script>
<script src="pages/home.vue.js?v=1"></script>

<?php if(is_auth() ){ ?>
<script src="pages/courses_list.vue.js?v=1"></script>
<script src="pages/course_edit.vue.js?v=1"></script>
<script src="pages/course_report.vue.js?v=1"></script>
<script src="pages/course_category_list.vue.js?v=1"></script>
<script src="pages/course_category_edit.vue.js?v=1"></script>
<script src="pages/course_calendar_import.vue.js?v=1"></script>
<script src="pages/course_calendar_edit.vue.js?v=1"></script>
<script src="pages/counterparty_list.vue.js?v=1"></script>
<script src="pages/counterparty_edit.vue.js?v=1"></script>
<script src="pages/self_edit.vue.js?v=1"></script>
<script src="pages/positions.vue.js"></script>
<script src="pages/position_edit.vue.js"></script>
<!--<script src="pages/organizations.vue.js"></script>
<script src="pages/organization_edit.vue.js"></script>-->
<script src="pages/students_list.vue.js?v=1"></script>
<script src="pages/student_edit.vue.js?v=1"></script>
<script src="pages/student_report.vue.js?v=1"></script>
<script src="pages/students_import.vue.js?v=1"></script>
<script src="pages/teachers_list.vue.js?v=1"></script>
<script src="pages/teacher_edit.vue.js?v=1"></script>
<script src="pages/teacher_course.vue.js?v=1"></script>
<script src="pages/orders_list.vue.js?v=1"></script>
<script src="pages/order_edit.vue.js?v=1"></script>
<script src="pages/order_items.vue.js?v=1"></script>
<script src="pages/order_discounts.vue.js?v=1"></script>
<script src="pages/order_student.vue.js?v=1"></script>
<!--<script src="pages/order_import.vue.js?v=1"></script>-->
<script src="pages/groups_list.vue.js?v=1"></script>
<script src="pages/group_edit.vue.js?v=1"></script>
<script src="pages/group_scheduler.vue.js?v=1"></script>
<script src="pages/group_items.vue.js?v=1"></script>
<script src="pages/group_student.vue.js?v=1"></script>
<script src="pages/group_report.vue.js?v=1"></script>
<script src="pages/lstream_list.vue.js?v=1"></script>
<script src="pages/lstream_edit.vue.js?v=1"></script>
<script src="pages/lstream_items.vue.js?v=1"></script>
<!--<script src="pages/cohort.vue.js?v=12"></script>-->
<script src="pages/reports.vue.js?v=2" ></script>
<script src="pages/accounts_list.vue.js?v=1"></script>
<script src="pages/account_edit.vue.js?v=1"></script>

<script src="pages/orders_analytics.vue.js?v=1"></script>

<script src="pages/import.vue.js"></script>
<script src="pages/about.vue.js"></script>
<script src="pages/contact.vue.js"></script>
<?php } ?>


<script>
var routes = [
  { path: '/login/:key?', component: Login, props: true  },
  { path: '/', component: Home },
  { path: '/logout', name:'logout', component: Logout },

<?php if(is_auth() ){ ?>
  { path: '/courses_list/:counterpartyid?/:categoryid?', name: 'courses_list' , component: CoursesList, props: true  },
  { path: '/course_edit/:courseid/:counterpartyid?/:categoryid?', name: 'course_edit', component: CourseEdit },
  { path: '/course_report/:courseid', name: 'course_report', component: CourseReport, props: true },
  { path: '/course_category_list', name: 'course_category_list' , component: CourseCategoryList },
  { path: '/course_category_edit/:categoryid', name: 'course_category_edit' , component: CourseCategoryEdit, props: true },
  { path: '/course_calendar_import/:courseid/:categoryid?/:groupid?/:orderid?', name: 'course_calendar_import' , component: CourseCalendarImport, props: true },
  { path: '/course_calendar_edit/:courseid/:variation?', name: 'course_calendar_edit' , component: CourseCalendarEdit, props: true },
  { path: '/counterparty_list', name:'counterparty_list', component: CounterpartyList, props: true },
  { path: '/counterparty_edit/:counterpartyid', name: 'counterparty_edit', component: CounterpartyEdit, props: true },
  { path: '/self_edit/:counterpartyid', name: 'self_edit', component: SelfEdit, props: true },
//  { path: '/students_list/:counterpartyid?/:groupid?/:facultetid?/:formid?', name:'students_list', component: UserList, props: true },
  { path: '/positions', component: Positions },
  { path: '/positionedit/:positionid', name: 'positionedit', component: PositionEdit, props: true },
//  { path: '/organizations', component: Organizations  },
//  { path: '/organizationedit/:organizationid', name: 'organizationedit', component: OrganizationEdit, props: true },
  { path: '/import', component: Import },
  { path: '/students_list/:counterpartyid?', name:'students_list', component: UserList, props: true },
  { path: '/student_edit/:userid/:counterpartyid?/:orderid?/:groupid?/:lastname?/:firstname?/:middlename?/:position?', name: 'student_edit', component: UserEdit, props: true },
  { path: '/student_report/:userid/:counterpartyid?', name: 'student_report', component: UserReport, props: true },
  { path: '/students_import/:counterpartyid/:orderid?', name: 'students_import', component: StudentsImport, props: true },
  { path: '/teacher_list', name:'teacher_list', component: TeacherList, props: true },
  { path: '/teacher_edit/:userid', name: 'teacher_edit', component: TeacherEdit, props: true },
  { path: '/teacher_course/:userid', name: 'teacher_course', component: TeacherCourse, props: true },
  { path: '/orders_list/:counterpartyid?/:orderid?', name:'orders_list', component: OrdersList, props: true },
  { path: '/order_edit/:orderid/:counterpartyid?', name: 'order_edit', component: OrderEdit, props: true },
  { path: '/order_items/:orderid/:counterpartyid?', name: 'order_items', component: OrderItems, props: true },
  { path: '/order_discounts/:orderid/:counterpartyid?', name: 'order_discounts', component: OrderDiscounts, props: true },
  { path: '/order_student/:orderid', name: 'order_student', component: OrderUser, props: true },
//  { path: '/order_import/:orderid/:counterpartyid?', name: 'order_import', component: OrderImport, props: true },
  { path: '/groups_list/:orderid?/:make?/:counterpartyid?', name:'groups_list', component: GroupsList, props: true },
  { path: '/group_items/:groupid/:orderid?', name: 'group_items', component: GroupItems, props: true },
  { path: '/groupuser/:groupid', name: 'groupuser', component: GroupUser, props: true },
  { path: '/groupdel', name: 'groupdel', component: Home },
  { path: '/group_edit/:groupid/:orderid?/:counterpartyid?', name: 'group_edit', component: GroupEdit, props: true },
  { path: '/group_scheduler/:groupid/:orderid?/:counterpartyid?', name: 'group_scheduler', component: GroupScheduler, props: true },
  { path: '/group_report/:groupid', name: 'group_report', component: GroupReport, props: true },
  { path: '/lstream_list/', name:'lstream_list', component: LstreamList, props: true },
  { path: '/lstream_edit/:lstreamid', name: 'lstream_edit', component: LstreamEdit, props: true },
  { path: '/lstream_items/:lstreamid', name: 'lstream_items', component: LstreamItems, props: true },
  //{ path: '/cohort/:groupid/:cohortid?/:counterpartyid?', name: 'cohort', component: Cohort, props: true },
  
  
  { path: '/orders_analytics/:counterpartyid?/:orderid?', name:'orders_analytics', component: OrdersAnalytics, props: true },
  
  { path: '/reports', component: Reports },
  { path: '/accountslist', name: 'accountslist', component: AccountsList },
  { path: '/accountedit/:accountid', name: 'accountedit', component: AccountEdit, props: true },
//  { path: '/about', component: About },
//  { path: '/contact', component: Contact }
<?php } ?>
];


const AuthTitle =    '<?php echo $AuthTitle; ?>'
const ShortTitle =   '<?php echo $ShortTitle; ?>'
const JsonApiURL =   '<?php echo $JsonApiURL; ?>'
const MoodleApiURL = '<?php echo $MoodleApiURL; ?>'
const DPO = <?php echo $DPO; ?>

const router = VueRouter.createRouter({
  history: VueRouter.createWebHashHistory(),
  routes, // short for `routes: routes`
})

var session_t = {
      login: '',
      sessionId: '<?php echo session_id(); ?>',
      role: ''
};

var session_var = {
    order_page: 1,
    order_namesearch: '',
    order_counterparty_с_id: '',
    order_counterparty_с: '',
    order_datesearch: '',
    order_datesearch2: '',
    order_status: '',
    
};

var search_u = {
      customersearch: '',
      lastnamesearch: '',
      firstnamesearch: '',
      middlenamesearch: '',
      facultetId: 0,
      formId: 0,
      organizationsearch: 0
};


const app = Vue.createApp({})
app.use(router)
app.use(sortablejs);
//app.use(VueDraggableNext)


//const fe = FileExplorer.FileExplorer(elem, options);
//draggable: window['VueDraggableNext'],

app.component('navigation',  {
  data() {
    return {
	info: '',
        sessionId: '',
	    account_id: '0',
	    counterparty_id: -1,
	    notefication: 1,
	    role: 'counterparty',
    }
  },

   mounted() {
//    updated() {

      this.sessionId = session_t.sessionId
      axios
        .post(JsonApiURL+'api/auth_json.php', {is_auth: {sessionId: session_t.sessionId}})
        .then(response => { 
            this.info = response.data
	        this.account_id = this.info.account_id
	        this.counterparty_id = this.info.counterparty_id
	        this.role = this.info.role
	    //session_t.sessionId = this.info.sessionId
	    if(this.info.status>0){
                this.sessionId = session_t.sessionId
		window.location.href = '#/login';
            }
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
         })
        .catch(error => {
              console.log(error.response)
            })

    },

  template: `
<?php if(is_auth() ){ ?>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #c5c5c5; ">
    <div class="container-fluid">
      <a class="navbar-brand" href="#" style="margin-left: 8px;"> <img src="/images/logo-ico.png" width="30"> </a>

      <div class="collapse navbar-collapse" id="navbar-content">
        <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link  to="/"  class="nav-link active" aria-current="page" > Главная</router-link>
          </li>

          <li  v-if="role!='counterparty'"  class="nav-item">
              <li ><router-link  to="/counterparty_list"  class="nav-link active" aria-current="page" > Контрагенты </router-link></li>
          </li>

          <li class="nav-item">
            <router-link  to="/orders_list"  class="nav-link active" aria-current="page" > Заявки </router-link>
          </li>
          
          <li v-if="role!='counterparty'"  class="nav-item dropdown">
            <a href="#"  class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" > Курсы </a>
            <ul class="dropdown-menu shadow">
              <li><router-link  to="/courses_list" class="dropdown-item"  >Курсы </router-link ></li>
              <li><router-link  to="/course_category_list" class="dropdown-item"  >Категории </router-link ></li>
            </ul>
          </li>
          

          <li v-if="role!='counterparty'"  class="nav-item dropdown">
            <a href="#"  class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" > Список слушателей </a>
            <ul class="dropdown-menu shadow">
              <li><router-link   :to="{ name: 'students_list', params: { counterpartyid: 0 }}"  class="nav-link active" aria-current="page" > Список слушателей </router-link></li>
              <li ><router-link  to="/groups_list/" class="nav-link active" aria-current="page" > Учебные группы </router-link ></li>
              <li ><router-link  to="/lstream_list/" class="nav-link active" aria-current="page" > Учебные потоки </router-link ></li>
            </ul>
          </li>
          
          <li v-if="role=='counterparty'"  class="nav-item">
            <router-link   :to="{ name: 'students_list', params: { counterpartyid: 0 }}"  class="nav-link active" aria-current="page" > Список сотрудников </router-link>
          </li>
          
          <li v-if="role=='counterparty'"  class="nav-item">
            <router-link  to="/courses_list"  class="nav-link active" aria-current="page" > Курсы </router-link >
          </li>


          <li  v-if="role!='counterparty'"  class="nav-item">
              <li ><router-link  to="/teacher_list"  class="nav-link active" aria-current="page" > Преподаватели </router-link></li>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;"  >Аналитика</a>
            <ul class="dropdown-menu shadow" style="--bs-dropdown-min-width: 20rem;">

              <li><router-link   :to="{ name: 'orders_analytics', params: { counterpartyid: 0 }}"  class="nav-link active" aria-current="page" > Аналитика по заявкам </router-link></li>
              <!--<li><router-link  to="/positions"   class="dropdown-item" > Должности</router-link></li>-->
            </ul>
          </li>
       

          <li v-if="role=='counterparty'" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" >О системе</a>
            <ul class="dropdown-menu shadow">
              <li><a class="dropdown-item" href="#">Контакты</a></li>
              <li><a class="dropdown-item" href="#">Политика конфиденциальности</a></li>
              <li><a class="dropdown-item" href="#">Об институте </a></li>

              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Информация о системе</a></li>
            </ul>
          </li>
        </ul>

        <div class="d-flex ms-auto">
          <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
            <li class="nav-item dropdown"> 
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside"> <span v-if="notefication"><i class="fa-solid fa-bell"></i></span><span v-else><i class="fa-regular fa-bell"></i></span> </a>
              <ul class="dropdown-menu shadow">
                <li v-if="role=='counterparty'  && notefication" > <a  @click="notefication=0;" class="dropdown-item"> Уведомления об окончании срока действия удостоверений сотрудников </a></li>
              </ul>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside"> <i class="fa-solid fa-circle-user" style="font-size: larger;"></i> <span style="color: #212529; font-family: Manrope;">{{info.login}}</span>  </a>
              <ul class="dropdown-menu shadow">
                <li v-if="role!='counterparty'" ><router-link  :to="{ name: 'accountedit', params:{ accountid: account_id }}"  class="dropdown-item" ><router-link  :to="{ name: 'accountedit', params:{ accountid: account_id }}"  class="dropdown-item" >Настройки аккаунта</router-link></li>
                <li v-else ><router-link  :to="{ name: 'counterparty_edit', params:{ counterpartyid: counterparty_id }}"  class="dropdown-item" >Настройки аккаунта</router-link></li>
                <li v-if="role=='admin'" ><router-link  :to="{ name: 'self_edit', params: { counterpartyid: 1 }}"  class="dropdown-item" >Реквизиты организации</router-link></li>
                <li v-if="role=='admin'" ><hr class="dropdown-divider"></li>
                <li v-if="role=='admin'" ><router-link  to="/accountslist" class="dropdown-item"> Настройки доступа администраторов</router-link></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#/logout">Выход</a></li>
              </ul>
            </li>
          </ul>
        </div>

      </div>
      <div class="navbar-brand" style="margin-left: 8px;">  <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas"><span class="navbar-toggler-icon"></span></button></div>
    </div>
  </nav>

<?php }else{ ?>
	    <a  href="<?php echo $JsonApiURL; ?>#/login"  >
		<button> Авторизация </button>
	    </a>
<?php } ?>


<br />`
})

app.mount('#app')

</script>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasLabel">Offcanvas</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
  </div>
  <div class="offcanvas-body">
    ...
  </div>
</div>





<div style="background-color: #212529; ">
<div id="app" class="container" style="margin-top: 5px;">
<nav  style="background-color: #212529; color: white; ">
  <div class="container-fluid">    
   <div class="row">
       <div class="col-3">
<p style="margin-top: 10px;">
Контакты:<br />
450075, г. Уфа, ул. 50 лет СССР, 2<br />	
+7(347)258-8900, +7(919)612-8448<br />	
ipo@ipo5.ru<br />	
</p>
       </div>
       <div class="col-3">
 <p style="margin-top: 10px; margin-left: 70px;">   
           <div class="nav-item dropdown" style="color: white;>
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside" style="color: #212529;" >О системе <i class="fa-solid fa-caret-down"></i></a>
            <ul class="dropdown-menu shadow">
              <li><a class="dropdown-item" href="#">Контакты</a></li>
              <li><a class="dropdown-item" href="#">Политика конфиденциальности</a></li>
              <li><a class="dropdown-item" href="#">Об институте </a></li>

              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Информация о системе</a></li>
            </ul>
          </div>

 
 Новости<br /> 
 Обучение
 </p>
       </div>
       <div class="col-6">
<p style="margin-top: 77px; text-align: right">© 2024 ООО «Институт Профессионального Образования»</p>            
       </div>
   </div> 
 </div> 
</nav>   
</div>
</div>
</body>
</html>
