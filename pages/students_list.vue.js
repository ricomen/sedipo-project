var UserList = {
  data: function () {
    return {
      IS_SUBDIVISION: 0,
      role: '',
      info: [],
      info2: [],
      info3: [],
      info4: [],
      info5: [],
      info6: [],
      info9: [],
      message: '',
      lastnamesearch: '',
      firstnamesearch: '',
      middlenamesearch: '',
      a_counterparty_id: 0,
      status: 0,
      search_fl: 0,
      s_counterparty_id: 0,
      counterparty_list: [],
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

      this.role_privileges =  session_role_privileges
      this.a_counterparty_id = Number(this.$route.params.counterpartyid)

      this.page = session_var.student_page
      this.lastnamesearch = session_var.student_lastnamesearch
      this.firstnamesearch = session_var.student_firstnamesearch
      this.middlenamesearch = session_var.student_middlenamesearch


    axios
      .post(JsonApiURL+'api/students_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,  counterparty_id: this.a_counterparty_id, page: this.page, status: 0,  sessionId: session_t.sessionId }}, {withCredentials: true})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
	        this.numPages = this.info.numPages
	        this.page = this.info.page

            //if(this.role == 'counterparty')
              //      this.a_counterparty_id = this.info.counterparty_id
            

            console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            });


/*      if(this.a_counterparty_id == 0) {
	    axios
                .post(JsonApiURL+'api/counterparty_json.php', {list: { sessionId: session_t.sessionId }})
    		.then(response2 => { 
        	    this.info2 = response2.data
        	    console.log(response2)
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
       }*/
       if(this.a_counterparty_id > 0) {
    	    axios
    		.post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.a_counterparty_id, sessionId: session_t.sessionId } }, {withCredentials: true})
    		.then(response3 => { 
        	    console.log(response3)
        	    this.info3 = response3.data
    		})
    		.catch(error => {
            	    console.log(error.response)
        	})
       }


    axios
      .post(JsonApiURL+'api/moodle_json.php', {report_sync: { sessionId: session_t.sessionId }}, {withCredentials: true})
      .then(response => { 
            this.info9 = response.data
       })
      .catch(error => {
              console.log(error.response)
            })


/*    axios
      .post(JsonApiURL+'api/facultet_json.php', {list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info4 = response.data
       })
      .catch(error => {
              console.log(error.response)
            })
*/

  },


  methods: {
    search_go(clean, page, s_counterparty_id) {
        var counterparty_id = s_counterparty_id

        this.page = page
        if(clean){
            this.lastnamesearch = ''
            this.firstnamesearch = ''
            this.middlenamesearch = ''
            this.organizationsearch = 0
            this.search_fl = 0
            this.counterparty_list = []
        }


      session_var.student_page = this.page 
      session_var.student_lastnamesearch = this.lastnamesearch 
      session_var.student_firstnamesearch = this.firstnamesearch
      session_var.student_middlenamesearch = this.middlenamesearch


      if(s_counterparty_id==0 ){
             counterparty_id = this.a_counterparty_id
             this.s_counterparty_id = 0
      }

      axios
      .post(JsonApiURL+'api/students_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,  counterparty_id: counterparty_id, page: this.page, status: this.status, sessionId: session_t.sessionId }}, {withCredentials: true})
      .then(response => { 
            this.info = response.data
            this.numPages = this.info.numPages
            this.page = this.info.page
            if ((this.lastnamesearch!='' ||  this.firstnamesearch!=''  || this.middlenamesearch!='') && this.a_counterparty_id==0 ){
                this.counterparty_list = this.info.counterparty_list
                this.search_fl = 1
            }
            else {
                this.search_fl = 0
                this.counterparty_list = []
            }
              console.log(response)
       })
      .catch(error => {
              console.log(error.response)
      })
    },



    userDelete(userId, name ){ 
	var fl = confirm('Удалить учетную запись: ' + name + '?');
	if(fl) {
	    axios
          .post(JsonApiURL+'api/students_json.php', {delete: {userId: userId}})
          .then(response => { 
            //this.info9 = response.data
            axios
            .post(JsonApiURL+'api/students_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,  counterparty_id: this.a_counterparty_id, status: this.status, sessionId: session_t.sessionId }}, {withCredentials: true})
            .then(response2 => { 
                this.info = response2.data
	            this.numPages = this.info.numPages
    	        this.page = this.info.page

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
    

    userArchive(userId, name ){ 
	var fl = confirm('Переместить в архив учетную запись: ' + name + '?');
	if(fl) {
	    axios
          .post(JsonApiURL+'api/students_json.php', {archive: {userId: userId, status: 1 }}, {withCredentials: true})
          .then(response => { 
            //this.info9 = response.data
            axios
            .post(JsonApiURL+'api/students_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,  counterparty_id: this.a_counterparty_id, status: this.status, sessionId: session_t.sessionId }}, {withCredentials: true})
            .then(response2 => { 
                this.info = response2.data
	            this.numPages = this.info.numPages
    	        this.page = this.info.page

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

    userActive(userId, name ){ 
	var fl = confirm('Переместить учетную запись в список активных: ' + name + '?');
	if(fl) {
	    axios
          .post(JsonApiURL+'api/students_json.php', {archive: {userId: userId, status: 0 }}, {withCredentials: true})
          .then(response => { 
            //this.info9 = response.data
            axios
            .post(JsonApiURL+'api/students_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,  counterparty_id: this.a_counterparty_id, status: this.status, sessionId: session_t.sessionId }}, {withCredentials: true})
            .then(response2 => { 
                this.info = response2.data
	            this.numPages = this.info.numPages
    	        this.page = this.info.page

            })
            .catch(error => {
                  console.log(error.response)
            })

        })
        .catch(error => {
              console.log(error.response)
        })

      }
    }

    
},




	template: `
  <div><navigation></navigation><h3>Список слушателей</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <h4 v-if="role!='counterparty' && a_counterparty_id>0"  style="text-align: right;" > {{info3.result.shortname}} </h4>

 <div class="row justify-content-between">
    <div class="col-11"  align="left">
      <table >
        <tr  align="left">
            <td><small><input type="text" v-model="lastnamesearch" placeholder="Фамилия" size="25"></small></td>
            <td><small><input type="text" v-model="firstnamesearch" placeholder="Имя" size="25"></small></td>
            <td><small><input type="text" v-model="middlenamesearch" placeholder="Отчество" size="25"></small></td>
            <td  width="1%"> </td>
            <td align="left"><small><button  @click="search_go(0, '', 0)"><nobr>&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</nobr></button></small></td>
            <td style="padding-left: 5px;"><small><button @click="search_go(1, '', 0)" title="Сбросить все фильтры"   ><nobr>&nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;Сбросить фильтры&nbsp;</nobr></button></small></td>
        </tr>
        <tr v-if="search_fl">
            <td colspan="4" v-if="a_counterparty_id==0 && role!='counterparty'"  ><small>
               <select v-model="s_counterparty_id" @change="search_go(0, '', this.s_counterparty_id)" >
                <option value="0"> -  Организация  - </option>
                <option v-for="item2 in counterparty_list" :value="item2.counterparty_id">
	                {{item2.counterparty_name}} 
               </option>
            </select>
           </small></td>
        </tr>
    </table>
   </div>
   <div  v-if="a_counterparty_id>0"  class="col-1"></div>
 </div>

<div  v-if="a_counterparty_id>0" class="row">
  <div class="col-4">
    <div v-if="role!='counterparty'"  style="text-align: left; padding-top: 15px;"><button  class="btn btn-light" ><router-link  :to="{ name: 'student_edit', params: { userid: 0, counterpartyid: this.a_counterparty_id  }}"  title="Добавить учетную запись" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новый слушатель </nobr></div></router-link >  </button></div>
    <div v-else  style="text-align: left; padding-top: 15px;"><button  class="btn btn-light" ><router-link  :to="{ name: 'student_edit', params: { userid: 0, counterpartyid: this.info.counterparty_id  }}"  title="Добавить учетную запись" ><div><nobr><i class="fa-solid fa-file-circle-plus"></i> Новый слушатель </nobr></div></router-link >  </button></div>
  </div>
  <div class="col"></div>
  <div class="col" style="text-align: right; padding-top: 15px;"><router-link :to="{ name: 'students_import', params:{ counterpartyid: a_counterparty_id  }}" class="nav-link" title="Импорт списка сотрудников / результатов обучения" ><button  class="btn btn-primary btn-sm">Импорт списка сотрудников</button></router-link></div>
</div>



    <br />
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <th scope="col"> </th>
          <th  v-if="a_counterparty_id==0 && role!='counterparty'" scope="col"> Организация, должность </th>     <!-- <th  v-if="IS_SUBDIVISION"  scope="col"> Должность, подразделение </th>-->
          <th  v-else scope="col"> Должность </th>
          <th scope="col" align="center"> <center>Документы <br />по обучению </center> </th>
          <th  scope="col">  </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left" style="float: none;" >  
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <td v-if="role_privileges.template_list == 2" style="padding-left: 0px; padding-right: 10px;"> <router-link  :to="{ name: 'student_edit', params: { userid: item.userId, counterpartyid: this.a_counterparty_id }}"   title="Редактировать учетную запись" ><i class="fa-solid fa-pencil"></i></router-link ></td>
            <td  ><ul><li v-for="item2 in item.jobs"> <span v-if="a_counterparty_id==0 && role!='counterparty'" >{{item2.counterparty_name}} - </span>  {{item2.job_title}} <span v-if="IS_SUBDIVISION">, {{item2.subdivision}}</span> </li></ul></td>
            <td align="center"> <table border="0" ><tr style="padding: 15px; 15px; 15px; 15px;"><td style="padding-right: 5px;">
		<router-link    :to="{ name: 'student_report', params: { userid: item.userId, counterpartyid: this.a_counterparty_id }}"   title="Отчет о результатах обучения" ><i class="fa-solid fa-file-circle-check"></i></router-link >
	    </td></tr></table> </td>
            <td align="right"> 
		    <table border="0" ><tr style="padding: 15px; 15px; 15px; 15px;">
		    <td style="padding-left: 5px; padding-right: 10px;">
		      <a v-if="status>0"  @click="userActive(item.userId, item.lastname + ' ' + item.firstname + '...')"   title="Восстановить учетную запись из архива" style="color: green;" ><i class="fa-solid fa-user-gear"></i></a>
		    </td>
                    <td style="padding-left: 10px; padding-right: 0px;">
		      <a v-if="status==0 && item.orders_count==0"  @click="userArchive(item.userId, item.lastname + ' ' + item.firstname + '...')"   title="Переместить учетную запись в архив" style="color: red;" ><i class="fas fa-user-slash"></i></a>
		      <a v-else-if="status==0"     title="Учетная запись используется" style="color: gray;" ><i class="fas fa-user-slash"></i></a>
		      <a v-else-if="status>0 && item.orders_count>0"    title="Учетная запись используется" style="color: gray;" ><i class="fa-solid fa-trash-can"></i></a>
		      <a v-else-if="status>0"   @click="userDelete(item.userId, item.lastname + ' ' + item.firstname + '...')"   title="Удалить учетную запись" style="color: red;" ><i class="fa-solid fa-trash-can"></i></a>
		    </td></tr></table>
               </td>
        </tr>
        <tr>
            <td colspan="5">
            </td>
            <td style="text-align: right" colspan="3">
	            <small><select  v-model="status"  id="input_user_status" @change="search_go(1, '', 0)" >
	            <option value="0"> Активные записи </option>
	            <option value="1"> Архив </option>
	            </select></small>
            </td>
        </tr>    
  </tbody>
  </table>


<span v-if="role!='counterparty' && a_counterparty_id>0" >
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
  <ul class="pagination">
    <li v-if="page>1" class="page-item">
      <button  @click="search_go(0, this.page-1, 0)"  class="page-link" aria-label="Previous"> <span aria-hidden="true"><i class="fa-solid fa-caret-left"></i></span> </button>
    </li>
    <li v-if="page>1"class="page-item"><button @click="search_go(0, 1, 0)"  class="page-link" >1</button></li>
    <li v-if="page>2" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page-2>2"class="page-item"><button @click="search_go(0, this.page-2, 0)"  class="page-link" >{{page-2}}</button></li>
    <li v-if="page-1>2"class="page-item"><button @click="search_go(0, this.page-1, 0)"  class="page-link" >{{page-1}}</button></li>
    <li class="page-item active" aria-current="page"><button   class="page-link">{{page}}</button></li>
    <li v-if="page+1<numPages"class="page-item"><button @click="search_go(0, this.page+1, 0)"  class="page-link" >{{page+1}}</button></li>
    <li v-if="page+2<numPages"  class="page-item"><button @click="search_go(0, this.page+2, 0)"  class="page-link" >{{page+2}}</button></li>
    <li v-if="page+3<numPages" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page<numPages" class="page-item"><button @click="search_go(0, this.numPages, 0)"  class="page-link" >{{numPages}}</button></li>
    <li v-if="page<numPages" class="page-item">
      <button  @click="search_go(0, this.page+1, 0)"  class="page-link"  aria-label="Next">  <span aria-hidden="true"><i class="fa-solid fa-caret-right"></i></span>  </button>
    </li>
  </ul>
</div>
<div class="col-2"> </div>
</div>

    </div>`
};




