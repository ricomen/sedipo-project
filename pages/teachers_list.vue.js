var TeacherList = {
  data: function () {
    return {
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
      status: 0,
      print_v: 'false',
      numPages: 0,
      page: 1
     }
   },


   mounted() {

      this.lastnamesearch = session_var.teacher_lastnamesearch
      this.firstnamesearch = session_var.teacher_lastnamesearch
      this.middlenamesearch = session_var.teacher_middlenamesearch
      this.page = session_var.teacher_page

    axios
      .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,   page: this.page,   sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            this.role = this.info.role
	        this.numPages = this.info.numPages
	        this.page = this.info.page

            console.log(response)
       })
      .catch(error => {
              console.log(error.response)
            });



  },


  methods: {
    search_go(clean, page) {
        if(clean){
            this.lastnamesearch = ''
            this.firstnamesearch = ''
            this.middlenamesearch = ''
            this.page = 1
        }

      session_var.teacher_lastnamesearch = this.lastnamesearch
      session_var.teacher_lastnamesearch = this.firstnamesearch
      session_var.teacher_middlenamesearch = this.middlenamesearch
      session_var.teacher_page = this.page 

      axios
//      .post(JsonApiURL+'api/users_json.php', {list: {search: 1, lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch, facultetId: this.facultet_id, formId: this.form_id, groupId: this.group_id, organization: this.organizationsearch, page: page, sessionId: session_t.sessionId }})
      .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,   page: this.page, status: this.status, sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
	        this.numPages = this.info.numPages
	        this.page = this.info.page
       })
      .catch(error => {
              console.log(error.response)
      })
    },


    page_go(page) {

       this.page = page
       session_var.teacher_lastnamesearch = this.lastnamesearch
       session_var.teacher_lastnamesearch = this.firstnamesearch
       session_var.teacher_middlenamesearch = this.middlenamesearch
       session_var.teacher_page = this.page 

      axios
      .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,   page: this.page, status: 0,  sessionId: session_t.sessionId }})
//      .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,  counterparty_id: this.a_counterparty_id, page: this.page, status: this.status, sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
	        this.numPages = this.info.numPages
	        this.page = this.info.page
       })
      .catch(error => {
              console.log(error.response)
            })
    },



    userDelete(userId, name ){ 
	var fl = confirm('Удалить учетную запись: ' + name + '?');
	if(fl) {
	    axios
          .post(JsonApiURL+'api/teacher_json.php', {delete: {userId: userId}})
          .then(response => { 
            //this.info9 = response.data
            axios
            .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,   status: this.status, sessionId: session_t.sessionId }})
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
          .post(JsonApiURL+'api/teacher_json.php', {archive: {userId: userId, status: 1 }})
          .then(response => { 
            //this.info9 = response.data
            axios
            .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,   status: this.status, sessionId: session_t.sessionId }})
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
          .post(JsonApiURL+'api/teacher_json.php', {archive: {userId: userId, status: 0 }})
          .then(response => { 
            //this.info9 = response.data
            axios
            .post(JsonApiURL+'api/teacher_json.php', {list: { lastname: this.lastnamesearch, firstname: this.firstnamesearch, middlename: this.middlenamesearch,   status: this.status, sessionId: session_t.sessionId }})
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




	template: `<div><navigation></navigation><h3>Список преподавателей</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <h4 v-if="role!='counterparty' && a_counterparty_id>0"  style="text-align: right;" > {{info3.result.name}} </h4>

 <div class="row justify-content-between">
    <div class="col-7">
      <div align="left">
      <table >
        <tr  align="left">
            <td><small><input type="text" v-model="lastnamesearch" placeholder="Фамилия" size="10"></small></td>
            <td><small><input type="text" v-model="firstnamesearch" placeholder="Имя" size="10"></small></td>
            <td><small><input type="text" v-model="middlenamesearch" placeholder="Отчество" size="10"></small></td>
            <td  width="2%"> </td>
            <td align="left"><small> <button  @click="search_go(0, '')"><nobr>&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</nobr></button></small></td>
            <td  width="2%"> </td>
            <td ><small> <button @click="search_go(1, '')" title="Сбросить все фильтры"   >&nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;Сбросить фильтры&nbsp;</button> </small></td>
        </tr>
    </table>
  </div>
    </div>
    <div  v-if="a_counterparty_id>0"  class="col-5">
  <div align="right">
    <div class="row"> 
      <div class="col"><router-link :to="{ name: 'students_import', params:{ counterpartyid: a_counterparty_id  }}" class="nav-link" title="Импорт списка сотрудников / результатов обучения" ><button  class="btn btn-primary btn-sm"> Импорт списка сотрудников / результатов обучения </button></router-link></div>
    </div>  
  </div>
    </div>
  </div>

    <br />
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col" width="20%"> Фамилия </th>
          <th scope="col" width="20%"> Имя </th>
          <th scope="col" width="20%"> Отчество </th>
          <th  scope="col"  style="text-align: center;" width="10%"> В штате </th>
          <th scope="col"  style="text-align: center;">Курсы</th>
          <!--<th scope="col"  style="text-align: center;"> Документы </th>-->
          <th v-if="role!='counterparty'" scope="col"> <div style="text-align: right">
            <router-link  :to="{ name: 'teacher_edit', params: { userid: 0  }}"  title="Добавить учетную запись" ><b style=" font-size: larger;"><i class="fa-solid fa-user-plus"></i></b></router-link ></div> 
          </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left" style="float: none;" >  
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <td style="text-align: center;">
                 <span v-if="item.on_staff==1"><i class="fa-solid fa-check"></span></i>
                 <span v-else><i class="fa-regular "></i></span>
            </td>
            <td align="center"> 
		        <router-link :to="{ name: 'teacher_course', params: { userid: item.user_id, }}"   title="Курсы" ><i class="fa-solid fa-chalkboard-user"></i></router-link >
            </td>
            <!--
            <td align="center"  style="padding-topt: 0px;" >
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle dropdown-toggle-split" href="" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;"><i class="fa-regular fa-folder-open"></i>&nbsp;</button>
	            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"  style="--bs-dropdown-min-width: 25rem;">
	                <li v-if="item.type==1"><a :href="'d-api/documents.php?template=dogovor0.html&vars=dogovor0.txt&sql=dogovor0.sql&id='+item.counterparty_id" target="_blank"  class="nav-link" title="Договор об оказании образовательных услуг" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Договор об оказании образовательных услуг </a></li>
	                <li><a :href="'d-api/documents-order.php?contract_id=21&id=1&print_v='+this.print_v" target="_blank"  class="nav-link" title="договор" > &nbsp;<span class="icon"> <i class="fa-regular fa-file-lines"></i></span> Договор с преподавателем на обучение</a></li>
	            </ul>
	            </div>
            </td>
            -->
            

            <td style="text-align: right;"><div align="right"> 
		        <table border="0" ><tr style="padding: 15px; 15px; 15px; 15px;">
		            <td style="padding-right: 5px;">
		                <router-link    :to="{ name: 'teacher_edit', params: { userid: item.user_id, }}"   title="Редактировать учетную запись" ><i class="fas fa-edit"></i></router-link >
		            </td>
		            <td style="padding-left: 10px; padding-right: 0px;">
		                <a  @click="userDelete(item.user_id, item.lastname + ' ' + item.firstname + '...')"   title="Удалить учетную запись" style="color: red;" ><i class="fa-solid fa-trash-can"></i></a>
		            </td>
		        </tr></table>
	        </div></td>
        </tr>
  </tbody>
  </table>



<nav v-if="numPages>100" aria-label="Page navigation">
  <ul class="pagination">
    <li v-if="page>1" class="page-item">
      <button  @click="search_go(0, this.page-1)"  class="page-link" aria-label="Previous"> <span aria-hidden="true">&laquo;</span> </button>
    </li>
    <li v-if="page>1"class="page-item"><button @click="search_go(0, 1)"  class="page-link" >1</button></li>
    <li v-if="page>2" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page-2>2"class="page-item"><button @click="search_go(0, this.page-2)"  class="page-link" >{{page-2}}</button></li>
    <li v-if="page-1>2"class="page-item"><button @click="search_go(0, this.page-1)"  class="page-link" >{{page-1}}</button></li>
    <li class="page-item active" aria-current="page"><button   class="page-link">{{page}}</button></li>
    <li v-if="page+1<numPages"class="page-item"><button @click="search_go(0, this.page+1)"  class="page-link" >{{page+1}}</button></li>
    <li v-if="page+2<numPages"  class="page-item"><button @click="search_go(0, this.page+2)"  class="page-link" >{{page+2}}</button></li>
    <li v-if="page+3<numPages" class="page-item"><button   class="page-link" >...</button></li>
    <li v-if="page<numPages" class="page-item"><button @click="search_go(0, this.numPages)"  class="page-link" >{{numPages}}</button></li>
    <li v-if="page<numPages" class="page-item">
      <button  @click="search_go(0, this.page+1)"  class="page-link"  aria-label="Next">  <span aria-hidden="true">&raquo;</span>  </button>
    </li>
  </ul>
</nav>

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

    </div>`
};




