var CoursesList  = {
  data: function () {
    return {
      info: [],
      role: '',
      info2: [],
      info3: [],
      info4: [],
      info5: [],
      info9: [],
      counterparty_id: 0,
      category: 0,
      performer_id: 0,
      is_rank_of_profession: '',
      is_modules: '',
      //parent_txt: '',
      //parent: '',
      message: '',
      namesearch: '',
      description: '',
      is_LMS: IS_LMS,
      numPages: 0,
      page: 1,
      role_privileges: {
          accountedit: 0,
          self_list: 0,
          rolelist: 0,
          accountslist: 0,
          set_template_contract: 0,
          validity_period_counterparty_list: 0,
          course_category_list: 0,
          courses_list: 0,
          teachers_commission_list: 0,
          teacher_list: 0,
          template_list: 0,
          counterparty_list: 0,
          students_list: 0,
          orders_analytics: 0,
          orders_table: 0,
          stat_report: 0,
          groups_list: 0,
          lstream_list: 0,
          calendar: 0,
          eisot_import: 0,
          orders_list: 0,
          orders_list_buh: 0
        },
     }
   },


   mounted() {
    this.counterparty_id = Number(this.$route.params.counterpartyid)
    this.role_privileges =  session_role_privileges
    if(isNaN(this.counterparty_id))
                this.counterparty_id = 0

/*    this.category = Number(this.$route.params.categoryid)
    if(isNaN(this.a_category_id))
                this.a_category_id = 0*/

     this.performer_id = session_var.courses_performer_id
     this.category = session_var.courses_course_category
     this.namesearch = session_var.courses_search

    if(this.category>0)
          var filter =  "`category_id`="+this.category;
    else
          var filter = "`main_module`=0";

    axios
      .post(JsonApiURL+'api/moodle_json.php', {report_sync: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info9 = response.data
       })
      .catch(error => {
              console.log(error.response)
            }),


    axios
      .post(JsonApiURL+'api/courses_json.php', {list: {where: filter, search: this.namesearch, page: this.page,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
            this.numPages = this.info.numPages
            this.page = this.info.page
       })
      .catch(error => {
              console.log(error.response)
        }),

    axios
      .post(JsonApiURL+'api/course_category_json.php', {list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info4 = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
       })
       


       if(this.counterparty_id > 0) {
    	    axios
    		.post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.counterparty_id, sessionId: session_t.sessionId } })
    		.then(response3 => { 
        	    console.log(response3)
        	    this.info3 = response3.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	}),

    	    axios
    		.post(JsonApiURL+'api/price_json.php', {list: { where: "`counterparty_id`="+this.counterparty_id,   sessionId: session_t.sessionId } })
    		.then(response5 => { 
        	    console.log(response5)
        	    this.info5 = response5.data
        	    //this.price2 = this.info5.result.price
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
       }

     
        axios
        .post(JsonApiURL+'api/counterparty_json.php', {list: { where: "`type`=1 AND counterparty_id>1", sessionId: session_t.sessionId }})
        .then(response => { 
            this.info2 = response.data
        })
        .catch(error => {
              console.log(error.response)
        })

  },

//    updated() {

//  },


  methods: {
    search_go(fl) {
    if(fl == 0)
         this.namesearch = "";

    session_var.courses_performer_id = this.performer_id
    session_var.courses_course_category = this.category
    session_var.courses_search = this.namesearch

    if(this.category>0)
          var filter =  "`category_id`="+this.category;
    else
          var filter = "`main_module`=0";

    if(this.performer_id>1)
          var filter = filter +  " AND `performer_id`="+this.performer_id;




    axios
      .post(JsonApiURL+'api/courses_json.php', {list: {where: filter, search: this.namesearch, page: 1,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
              console.log(response.data)
            this.numPages = this.info.numPages
            this.page = 1
       })
      .catch(error => {
              console.log(error.response)
            })
    },



    page_go(page) {
    this.page = page
    //session_var.courses_page = page

    session_var.courses_performer_id = this.performer_id
    session_var.courses_course_category = this.category
    session_var.courses_search = this.namesearch

    if(this.category>0)
          var filter =  "`category_id`="+this.category;
    else
          var filter = "`main_module`=0";

    if(this.performer_id>1)
          var filter = filter +  " AND `performer_id`="+this.performer_id;


    axios
      .post(JsonApiURL+'api/courses_json.php', {list: {where: filter, search: this.namesearch, page: page,   sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
              console.log(response.data)
       })
      .catch(error => {
              console.log(error.response)
            })
    },







    categories_go() {
       session_var.courses_performer_id = this.performer_id
       session_var.courses_course_category = this.category
       session_var.courses_search = this.namesearch


       if(this.category>0)
          var filter =  "`category_id`="+this.category;
       else
          var filter = "`main_module`=0";

       if(this.performer_id>1)
          var filter = filter +  " AND `performer_id`="+this.performer_id;
    
       this.is_rank_of_profession = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].is_rank_of_profession
       this.is_modules = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].modules
    
    //is_rank_of_profession    info4.list   category_id==      {{item.rank_of_profession}}

      axios
      .post(JsonApiURL+'api/courses_json.php', {list: {where: filter, search: this.namesearch,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.parent_txt =  this.info.categories_txt
            //this.parent = this.info.parent_id
       })
      .catch(error => {
              console.log(error.response)
            })
    },

    courseDelete(courseId, name) {
       //session_var.courses_performer_id = this.performer_id
       //session_var.courses_course_category = this.category
       //session_var.courses_search = this.namesearch



      if(this.category>0)
          var filter =  "`category_id`="+this.category;
      else
          var filter = "`main_module`=0";

     if( this.role!='admin' && this.role!='metodist' ) {
           alert('Нет прав удаления курса')
           return
     }

      var fl = confirm('Удалить: ' + name + '?');
      if(fl && courseId > 0 ){
        axios
        .post(JsonApiURL+'api/courses_json.php', {delete: {objectId: courseId, sessionId: session_t.sessionId } })
        .then(response => { 
            //this.info6 = response.data
            console.log(response.data)
            axios
            .post(JsonApiURL+'api/courses_json.php', {list: {where: filter,  sessionId: session_t.sessionId }})
            .then(response2 => { 
                console.log(response2.data)
                this.info = response2.data
            })
            .catch(error => {
              console.log(error.response)
            })

        })
        .catch(error => {
              console.log(error.response)
        })
      }
    },


     price2(course_id) {
        for (var i = 0; i < this.info5.list.length; i += 1) {
           if( this.info5.list[i].course_id == course_id) {
                 return( Number(this.info5.list[i].price) );
           }
        }
            return('');
     },
     
    price1(course_id, price1) {
        if(price1 == 0)
            return(price2(course_id));
        else
            return(price1);
     },
     
     descriptionRead(course_id) {
        if(course_id > 0 ){
            axios
            .post(JsonApiURL+'api/courses_json.php', {object: {objectId: course_id, sessionId: session_t.sessionId } })
            .then(response => { 
                this.description = response.data.result.description

            })
            .catch(error => {
                this.description = ''
                console.log(error.response)
            })
        }
        else 
           this.description = ''
     }

   },


	template: `
  <container v-if="role_privileges.courses_list != 0">
  <div><navigation></navigation><h3 v-if="counterparty_id>1">Персональная cтоимость обучения по курсам</h3><h3 v-else >Курсы</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

  <div align="left">
    <br />
    <h4 v-if="counterparty_id>0"  style="text-align: right;" > {{info3.result.shortname}} </h4>

    <p v-if="role!='counterparty'"  ><select  v-model="performer_id" @change="search_go(1)" >
        <option value="0"> - Учебный центр - </option>
        <option v-for="item_p in info2.list" :value="item_p.counterparty_id">{{item_p.name}}</option>
    </select></p>


    <p><select v-model="category" @change="categories_go()" ><option value="0"> - Категория - </option> 
            <option v-for="item_o in info4.list" :value="item_o.category_id">{{item_o.name}}</option>
        </select></p>

    <table border="0">
        <tr>
            <td  style="padding-left: 0px; padding-right: 5x;"><input type="text" v-model="namesearch" placeholder="Поиск"   size="50"></td>
            <td  style="padding-left: 15px; padding-right: 15x;" align="left"> <button  @click="search_go(1)">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</button></td>
            <td  style="padding-left: 15px; padding-right: 0x;" align="right"> <button  @click="search_go(0)" title="Сбросить все фильтры"   >&nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;Сбросить фильтры&nbsp;</button> </td>
        </tr>
    </table>

    <div  v-if="role_privileges.courses_list == 2 "   style="text-align: left; padding-top: 15px;"><button  class="btn btn-light" > <router-link  :to="{ name: 'course_edit', params: { courseid: 0, counterpartyid: 0, categoryid: this.category }}"   title="Добавить курс" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новый курс </nobr></div></router-link >  </button></div>

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Курс  </th>
          <th v-if="role!='counterparty' && counterparty_id==0"  scope="col"  style="text-align: center"> Учебный план  </th>
          <th scope="col"  style="text-align: center"> Стоимость обучения  </th>
          <th v-if="counterparty_id>0 && role!='counterparty'"  scope="col"  style="text-align: center"><small>Персональная cтоимость <br>обучения</small></th>
          <th v-if="role!='counterparty'"   scope="col">  </th>
          <th v-if="role=='counterparty'"   scope="col" width="5%"   style="text-align: center"> Описание курса </th>
        </tr>
      </thead>
     
     <tbody>   
      <tr v-for="item in info.list"  align="left">  
            <td><span v-if="item.main_module==1"><i class="fa-solid fa-bars"></i>&nbsp;</span>{{item.shortname}} <span v-if="is_rank_of_profession=='true' && item.rank_of_profession>0">({{item.rank_of_profession}} разряд)</span>
                   &nbsp;<router-link   v-if="role_privileges.courses_list == 2 "     :to="{ name: 'course_edit', params: { courseid: item.course_id, counterpartyid: this.counterparty_id, categoryid: this.category }}"   title="Редактировать" ><i class="fa-solid fa-pencil"></i></router-link >
                    &nbsp;&nbsp;&nbsp;&nbsp;<router-link   v-if="role_privileges.courses_list == 2 "  :to="{ name: 'course_edit', params: { courseid: 0, copyid: item.course_id, counterpartyid: this.counterparty_id, categoryid: this.category }}" title="Создать копию" ><i class="fa-solid fa-clone"></i></router-link>
            </td>
            
            <td v-if="role!='counterparty' && counterparty_id==0"   style="padding-left: 0px; padding-right: 0px; text-align: center">
                <div  class="btn-group"  style="padding-top: 1px;">
                  <router-link  :to="{ name: 'course_calendar_edit', params: { courseid: item.course_id, variation: 1 }}" class="nav-link"   title="Учебный план" ><button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-clock"></i></button></router-link>
                    <button type="button" class="btn btn-outline-primary  btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <ul class="dropdown-menu"  style="--bs-dropdown-min-width: 40rem; padding: 7px;">
                         <li><a :href="'d-api/documents-course.php?courseid='+item.course_id+'&print_v='+this.print_v"   target="_blank"  class="nav-link">&nbsp;<i class="fa-regular fa-file-lines"></i> Экспорт </a></li>
                      </ul>
                 </div>
                 <!--<div class="btn-group"  style="padding-top: 3px;">
                  <router-link :to="{ name: 'course_calendar_import', params: { courseid: item.course_id, categoryid: this.category }}"  type="button" class="btn btn-outline-primary  btn-sm" title="Учебный план"><i class="fa-solid fa-clock"></i></router-link>
                  <button type="button" class="btn btn-outline-primary  btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu"  style="--bs-dropdown-min-width: 20rem;">
                         <li><router-link  :to="{ name: 'course_calendar_edit', params: { courseid: item.course_id, variation: 1 }}" class="nav-link"   title="Редактировать" > &nbsp;<i class="fa-solid fa-pen-to-square"></i> Редактировать</router-link></li>
                         <li><router-link  :to="{ name: 'plan_edit', params: { courseid: item.course_id }}" class="nav-link"   title="Редактировать" > &nbsp;<i class="fa-solid fa-pen-to-square"></i> Редактировать</router-link></li>
                    </ul>
                 </div>-->
            </td>
            
            <td  v-if="role!='counterparty'"   align="center">{{item.price}}</td>
            <td  v-if="role=='counterparty'"   align="center">{{price1(item.course_id, item.price)}}</td>
            <td v-if="counterparty_id>0 && role!='counterparty'"  align="center">{{price2(item.course_id)}}</td>

            <td  v-if="role!='counterparty'"  align="right"> 
		        <table border="0" ><tr style="padding: 15px; 15px; 15px; 15px;">
                        <td  style="padding-left: 9px; padding-right: 14x;">
		            <router-link  v-if="is_LMS==1"  :to="{ name: 'course_report', params: { courseid: item.course_id }}"   title="Отчет о результатах обучения" ><i class="fa-solid fa-file-circle-check"></i></router-link >
		        </td>
		        <td style="padding-left: 15px; padding-right: 0px;">
		            <a  v-if="role_privileges.courses_list == 2 "   @click="courseDelete(item.course_id, item.name )"   title="Удалить" style="color: red;" ><i class="fa-solid fa-trash-can"></i></a>
		        </td></tr></table>
	        </td>
            <td  v-if="role=='counterparty'"   align="center"> 
                <button v-if="role=='counterparty'"   @click="descriptionRead(item.course_id)"  type="button"  class="btn btn-light btn-sm"  data-bs-toggle="modal" data-bs-target="#statusModal"><a title="Описание курса"><i class="fa-solid fa-file-lines"></i></a></button>
	        </td>
      </tr>
  </tbody>
  </table>
 </div>



<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Описание курса</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        {{description}}      

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Закрыть </button>
      </div>
    </div>
  </div>
</div>





<span v-if="counterparty_id>0">
  <br />
  <div class="mb-2">	
    <div align="right">
      <router-link :to="{ name: 'counterparty_list'}" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
    </div>
  </div>
 </div>
</span>


<div class="row">
<div class="col"> </div>
<div  v-if="numPages>1" aria-label="Page navigation" class="btn-toolbar col"  aria-label="pages"   style="text-align: center;">
  <ul class="pagination" >
    <li v-if="page>1" class="page-item">
      <button  @click="page_go(this.page-1)"  class="page-link" aria-label="Previous"> <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span> </button>
    </li>
    <li v-if="page>1"class="page-item"><button @click="page_go(1)"  class="page-link" >1</button></li>
    <li v-if="page>2" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page-2>2"class="page-item"><button @click="page_go(this.page-2)"  class="page-link" >{{page-2}}</button></li>
    <li v-if="page-1>2"class="page-item"><button @click="page_go(this.page-1)"  class="page-link" >{{page-1}}</button></li>
    <li class="page-item active" aria-current="page"><button   class="page-link">{{page}}</button></li>
    <li v-if="page+1<numPages"class="page-item"><button @click="page_go(this.page+1)"  class="page-link" >{{page+1}}</button></li>
    <li v-if="page+2<numPages"  class="page-item"><button @click="page_go(this.page+2)"  class="page-link" >{{page+2}}</button></li>
    <li v-if="page+3<numPages" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page<numPages" class="page-item"><button @click="page_go(this.numPages)"  class="page-link" >{{numPages}}</button></li>
    <li v-if="page<numPages" class="page-item">
      <button  @click="page_go(this.page+1)"  class="page-link"  aria-label="Next">  <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>  </button>
    </li>
  </ul>
</div>
<div class="col-2"> </div>
</div>



	</div>
  </container>`





};




