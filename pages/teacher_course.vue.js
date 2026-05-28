var TeacherCourse = {
  data: function () {
    return {
      role: '', 
      info: [],
      info2: [],
      info4: [],
      info5: [],
      info9: [],
      message: '',
      user_id: 0,
      lastname: '',
      firstname: '',
      middlename: '',
      user_status_id: 0,

      course_list: [],
      category: 0,
      course: '',
      priority: 1,
      
      wait: 0,
      seachcount: 0,
      msg_result: '',

      helpers: {},
      normative: {},
     }
   },

   mounted() {
    this.user_id = Number(this.$route.params.userid)

    if(this.user_id >0 ) {
        axios
            .post(JsonApiURL+'api/teacher_json.php', {object: {objectId: this.user_id, sessionId: session_t.sessionId } })
            .then(response => { 
                this.info = response.data
	            this.role = this.info.role
	            this.lastname = this.info.result.lastname
                this.firstname = this.info.result.firstname
                this.middlename = this.info.result.middlename
                this.on_staff = this.info.result.on_staff
                if( this.on_staff >= 1)
                     this.on_staff = true
                
    	        axios
    		    .post(JsonApiURL+'api/teacher2_json.php', {items: {objectId: this.user_id, sessionId: session_t.sessionId } })
    		    .then(response2 => { 
        	         this.info5 = response2.data
        	         this.course_list = this.info5.list
    		    })
    		    .catch(error => {
            	         console.log(error.response)
                })


            })
            .catch(error => {
                console.log(error.response)
            }),
            
            axios
            .post(JsonApiURL+'api/course_category_json.php', {list: { sessionId: session_t.sessionId }})
            .then(response => { 
                this.info4 = response.data
            })
                .catch(error => {
              console.log(error.response)
            })
            
/*            axios
            .post(JsonApiURL+'api/courses_json.php', {list: {where: "`main_module`=0",  sessionId: session_t.sessionId }})
            .then(response => { 
                this.info2 = response.data
            })
            .catch(error => {
                console.log(error.response)
            })*/
            
    }

     axios
      .post(JsonApiURL+'api/helpers_json.php', {table: 'a_teacher_course'})
      .then(response => {
            const data = response.data;
            console.log(data);

            if(Object.keys(data).length) {

                const values = Object.values(data);
                for (const value of values) {
                    if(value['description']) this.helpers[value['name']] = value['description'];
                    if(value['normative']) this.normative[value['name']] = value['normative'];
                }
                console.log(this.helpers);
                console.log(this.normative);

                const labels = document.querySelectorAll('label');
                labels.forEach(label => {
                    const getFor = label.htmlFor;

                    // добавляем подсказку
                    if(getFor in this.helpers) {
                        const help_block = ` <a title='${this.helpers[getFor]}'><span class="text-primary"><i class="fa-regular fa-circle-question"></i></span></a>`;
                        label.innerHTML = label.innerHTML + (help_block);
                    }
                    // добавляем нормативку
                    if(getFor in this.normative) {
                        const help_block = ` <a title='${this.helpers[getFor]}'><span class="text-primary"><i class="fa-solid fa-book"></i></span></a>`;
                        label.innerHTML = label.innerHTML + (help_block);
                    }

                })

            } else {
                console.log('NO DATA')
            }

       })
      .catch(error => {
              console.log(error.response)
      })




  },


  methods: {
    userCourse(){
        axios
          .post(JsonApiURL+'api/teacher2_json.php', {user_course: {objectId: this.user_id, category_id: this.category, course_id: this.course, priority: this.priority,  sessionId: session_t.sessionId }})
          .then(response => { 
	         console.log(response)
                this.info9 = response.data
                if(this.info9.result > 0) {
                    this.msg_result = 'Записано '+this.info9.result+' слушателей'
                }

    	        axios
    		    .post(JsonApiURL+'api/teacher2_json.php', {items: {objectId: this.user_id, sessionId: session_t.sessionId } })
    		    .then(response2 => { 
        	         this.info5 = response2.data
        	         this.course_list = this.info5.list
    		    })
    		    .catch(error => {
            	         console.log(error.response)
                })

            })
          .catch(error => {
              console.log(error.response)
            })

    },

    userCourseDel(userId, categoryId, courseId){
        axios
          .post(JsonApiURL+'api/teacher2_json.php', {user_course_del: {objectId: this.user_id, category_id: categoryId, course_id: courseId,  sessionId: session_t.sessionId }})
          .then(response => { 
	         console.log(response)
            //this.info = response.data

    	        axios
    		    .post(JsonApiURL+'api/teacher2_json.php', {items: {objectId: this.user_id, sessionId: session_t.sessionId } })
    		    .then(response2 => { 
        	         this.info5 = response2.data
        	         this.course_list = this.info5.list
    		    })
    		    .catch(error => {
            	         console.log(error.response)
                })

            })
          .catch(error => {
              console.log(error.response)
            })

    },


/*
    search_go(fl) {
    var conditions = {}

    if(fl == 0)
         this.namesearch = "";

    if(this.category>0){
        conditions =  {"category_id": this.category, "main_module": 0 }
    }
    else {
        conditions = {}
    }

    this.seachcount = 0 
    this.msg_result = ''

    if( this.namesearch != '' ){
      axios
         .post(JsonApiURL+'api/courses_json.php', {list: {conditions, search: this.namesearch,   sessionId: session_t.sessionId }}, {withCredentials: true})
         .then(response => { 
            this.info2 = response.data
            this.seachcount = this.info2.list.length
              console.log(response.data)
          })
         .catch(error => {
              console.log(error.response)
         })
      }
    },
*/




    search_go(fl) {
    this.msg_result = ''

    if(fl == 0){
         this.namesearch = "";
         this.category = 0;
    }

    if(this.category>0)
          var filter =  "`category_id`="+this.category+" AND `main_module`=0";
    else
          var filter = "";
    
    if(this.namesearch!=''){

      axios
        .post(JsonApiURL+'api/courses_json.php', {list: {where: filter, search: this.namesearch,   sessionId: session_t.sessionId }})
        .then(response => { 
            this.info2 = response.data
              this.seachcount = this.info2.list.length
              console.log(response.data)
         })
        .catch(error => {
              console.log(error.response)
        })
     }
     else{
          this.seachcount = 0
     }

      },


    categories_go() {
    this.msg_result = ''
    var filter = ''
    this.is_modules = 0
    this.seachcount = 0
  
    if(this.category>0) {
            filter =  "`category_id`="+this.category+" AND `main_module`=0";
            this.is_rank_of_profession = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].is_rank_of_profession
            this.is_modules = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].modules
    }     
    else {
        filter = "`main_module`=0";
        if(this.course>0){
            var current_category = this.info2.list[this.info2.list.findIndex(item => item.course_id == this.course)].category_id
            this.is_rank_of_profession = this.info4.list[this.info4.list.findIndex(item => item.category_id == current_category)].is_rank_of_profession
            this.is_modules = this.info4.list[this.info4.list.findIndex(item => item.category_id == current_category)].modules
        }
    }      
 
    axios
      .post(JsonApiURL+'api/courses_json.php', {list: {where: filter,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info2 = response.data
            //this.parent_txt =  this.info.categories_txt
            //this.parent = this.info.parent_id
       })
      .catch(error => {
              console.log(error.response)
      })

    },
    
    course_go() {
        this.msg_result = ''

        if(this.category>0){
            this.is_rank_of_profession = this.info4.list[this.info4.list.findIndex(item => item.category_id == this.category)].is_rank_of_profession
        }
        else {
            var current_category = this.info2.list[this.info2.list.findIndex(item => item.course_id == this.course)].category_id
            this.is_rank_of_profession = this.info4.list[this.info4.list.findIndex(item => item.category_id == current_category)].is_rank_of_profession
            this.is_modules = this.info4.list[this.info4.list.findIndex(item => item.category_id == current_category)].modules
        }
    },

    userUnlink(userId, name ){
	var fl = confirm('Исключить: ' + name + '?');
	    if(fl) {
	    axios
          .post(JsonApiURL+'api/teacher2_json.php', {item_del: {objectId:  this.order_id, user_id: userId, sessionId: session_t.sessionId }})
          .then(response => { 
	         console.log(response)
            //this.info = response.data

    	        axios
    		    .post(JsonApiURL+'api/teacher2_json.php', {items: {objectId: this.order_id, sessionId: session_t.sessionId } })
    		    .then(response5 => { 
        	         console.log(response5)
        	         this.info5 = response5.data
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



	template: `<div><navigation></navigation><h3>Курсы</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div class="container" align="left">
 
 <h5>{{lastname}} {{firstname}} {{middlename}}</h5> 
 <br />
 <span v-for="item2 in course_list">
 <div class="row">
    <div class="col-1">
        <i class="fa-solid fa-check"></i>
    </div>
    <div class="col">
        {{item2.category_name}} 
    </div>
    <div class="col">
        <span v-if="item2.shortname!='' ">
            {{item2.shortname}} 
        </span>
        <span v-else>
            <i>все курсы категории</i>
        </span>
    </div>
    <div class="col-1">
        {{item2.priority}}
    </div>

    <div class="col-1" style="color: red;"><a   @click="userCourseDel(this.user_id, item2.category_id, item2.course_id)" title="Отменить выбор курса"><i class="fa-solid fa-link-slash"></i></a> </div>
 </div>
 </span>


  <br />
  <h6>Выбрать курсы</h6> 
  <hr />
  <p align="center"><i>{{msg_result}}</i></p>

<!--
  <div class="mb-2">	
    <label for="input_category" class="form-label"> Категория </label><br />
        <select v-model="category" @change="categories_go()" >
              <option value="0"> - Категория - </option> 
              <option v-for="item_o in info4.list" :value="item_o.category_id">{{item_o.name}}</option>
        </select>
  </div>

    <br />
    <table width="100%">
        <tr  align="right">
            <td><input type="text" v-model="namesearch" placeholder="Поиск курса"   size="50"></td>
            <td align="center" width="10%"> <button  @click="search_go(1)">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</button></td>
            <td align="right"  width="5%">  <button  @click="search_go(0)" title="Сбросить все фильтры"   ><i class="fa-solid fa-text-slash"></i> </button> </td>
        </tr>
    </table>


  <div class="mb-2">	
    <label for="input_course" class="form-label"> Курс </label><br />
    <select v-if="category>0  || namesearch!='' " v-model="course"  @change="course_go()"  > 
        <option  value="0">  </option> 
        <option  value="0"> - Все курсы категории - </option> 
        <option v-for="(item2, index) in info2.list" :value="item2.course_id">
	        {{item2.shortname}}
	    </option>
    </select>
    <select v-else v-model="course"  > 
        <option  value="">  </option> 
    </select>
  </div>

  <div class="mb-2">	
    <label for="input_priority" class="form-label"> Приоритет </label><br />
    <select  v-model="priority"  > 
        <option  value="1"> 1 </option> 
        <option  value="2"> 2 </option> 
        <option  value="3"> 3 </option> 
        <option  value="4"> 4 </option> 
        <option  value="5"> 5 </option> 
        <option  value="6"> 6 </option> 
        <option  value="7"> 7 </option> 
        <option  value="8"> 8 </option> 
        <option  value="9"> 9 </option> 
    </select>
  </div>




  <div align="right">
    <button v-if="course==0 &&  course!='' " @click="userCourse()" class="btn btn-secondary"> Отметить все курсы категории </button></router-link>
    <button v-if="course>0  " @click="userCourse()" class="btn btn-secondary"> Отметить курс </button></router-link>
  </div>
  <hr />
 
-->


  <p align="center"><i>{{msg_result}}</i></p>
    <table width="100%">
        <tr  align="left">
            <td width="10%"> </td>
            <td ><input type="text" v-model="namesearch" placeholder="Поиск курса"   size="83"></td>
            <td align="center" width="10%"> <button  @click="search_go(1)">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</button></td>
            <td align="right"  width="20%">  <button  @click="search_go(0)" title="Сбросить все фильтры"   ><i class="fa-solid fa-xmark"></i>Сбросить фильтры</button></td>
        </tr>
    </table>
<br />
  <div class="mb-2">	
    <label for="input_category" class="form-label"> Категория </label><br />
        <select v-model="category" @change="categories_go()" >
              <option value="0"> - Категория - </option> 
              <option v-for="item_o in info4.list" :value="item_o.category_id">{{item_o.name}}</option>
        </select>
  </div>

  <p v-if="seachcount>0"><br />Найдено {{seachcount}} курсов</p>
  <div class="mb-2">	
    <label for="input_course" class="form-label"> Курс</label><br />
    <select v-if="category>0 || namesearch!='' "  v-model="course"  @change="course_go()"  > 
        <option v-if="category>0" value="0"> - Все курсы категории - </option> 
        <option v-for="(item2, index) in info2.list" :value="item2.course_id">
	        {{item2.shortname}}
	    </option>
    </select>
    <select v-else  v-model="course"   > 
        <option  value="0">  </option> 
    </select>
  </div>

  <div class="mb-2">	
    <label for="input_priority" class="form-label"> Приоритет </label><br />
    <select  v-model="priority"  > 
        <option  value="1"> 1 </option> 
        <option  value="2"> 2 </option> 
        <option  value="3"> 3 </option> 
        <option  value="4"> 4 </option> 
        <option  value="5"> 5 </option> 
        <option  value="6"> 6 </option> 
        <option  value="7"> 7 </option> 
        <option  value="8"> 8 </option> 
        <option  value="9"> 9 </option> 
    </select>
  </div>


  <div align="right">
    <button v-if="course==0 &&  course!='' " @click="userCourse()" class="btn btn-secondary"> Отметить все курсы категории </button></router-link>
    <button v-if="course>0  " @click="userCourse()" class="btn btn-secondary"> Отметить курс </button></router-link>
  </div>
  <hr />
 













 
  <div class="mb-2">	
    <div align="right">
    <!--<button  class="btn btn-primary"    @click="userSave()"> Сохранить </button>-->
      &nbsp<router-link :to="{ name: 'teacher_list'}" ><button  class="btn btn-outline-primary">Закрыть</button></router-link>
    </div>
  </div>
 </div>



 
</div>
	</div>`

};




