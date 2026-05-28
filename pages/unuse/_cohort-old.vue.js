var Cohort = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info4: [],
      message: '',
      cohort_id: 0,
      group_id: 0,
      name: '',
      status: 0,
      organization_id: 0,
      date_begin: '',
      date_end: '',
      date_protocol: '',
      course_id: '',
      course_name: '',
      course_shortname: '',
      category: 0,
      cohort_name: '',
      moodle_cohort_id: '',
      list_length: 0,
      directive: '',
      chairman: '',
      teacher: '',
      order_id: 0,
      a_counterparty_id: 0,
     }
   },

   mounted() {
    this.group_id = Number(this.$route.params.groupid)
    this.cohort_id = Number(this.$route.params.cohortid)
    this.a_counterparty_id = Number(this.$route.params.counterpartyid)

    if(this.group_id > 0){
	axios
    	    .post(JsonApiURL+'api/groups_json.php', {object: {objectId: this.group_id, sessionId: session_t.sessionId } })
    	    .then(response => { 
        	    this.info = response.data
		        this.name = this.info.result.name
        	    this.status = this.info.result.status
                this.organization_id = this.info.result.organization_id
        	    this.date_begin = this.info.result.date_begin
        	    this.date_end = this.info.result.date_end
        	    this.order_id = this.info.result.order_id

		        if( this.cohort_id == 0){
        	        axios
                    .post(JsonApiURL+'api/courses_json.php', {list: {where: "",  sessionId: session_t.sessionId }})
                    .then(response3 => { 
            	        this.info3 = response3.data
            	        this.parent_txt =  this.info3.categories_txt
            	        this.parent = this.info3.parent
            	        this.list_length = this.info3.list.length
        	        })
                    .catch(error => {
            	         console.log(error.response)
                    })
		        }

		        if( this.cohort_id > 0){
		            axios
    			    .post(JsonApiURL+'api/cohort_json.php', {object: {objectId: this.cohort_id, sessionId: session_t.sessionId } })
    			    .then(response2 => { 
        		        this.info2 = response2.data
			            this.cohort_name = this.info2.result.name
        		        this.category = this.info2.result.category
            		    this.course_id = this.info2.result.course_id
            		    this.course_name = this.info2.result.course_name
            		    this.course_shortname = this.info2.result.course_shortname
        		        this.date_begin = this.info2.result.date_begin
        		        this.date_end = this.info2.result.date_end
        	            this.date_protocol = this.info2.result.date_protocol
                        this.moodle_cohort_id = this.info2.result.moodle_cohort_id
                        this.directive = this.info2.result.directive
                        this.chairman = this.info2.result.chairman
                        this.teacher = this.info2.result.teacher
                    
    			    })
    			    .catch(error => {
        		        console.log(error.response)
        		    })
		        }

    	    })
    	    .catch(error => {
        	  console.log(error.response)
            })
    }


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





/*    axios
      .post(JsonApiURL+'api/facultet_json.php', {list: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info2 = response.data
       })
      .catch(error => {
              console.log(error.response)
            })*/

  },


  methods: {
        cohortSave () {
         if(this.cohort_id > 0){
           axios
            .post(JsonApiURL+'api/cohort_json.php', {update: {cohortId: this.cohort_id,  name: this.cohort_name,   date_begin: this.date_begin,  date_end: this.date_end, date_protocol: this.date_protocol, directive: this.directive, chairman: this.chairman, teacher: this.teacher, sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
              if(response.data.status==0) {
                   this.$router.push({ name: 'groups_list', params: {orderid: this.order_id, make: 0, counterpartyid: this.a_counterparty_id }} ) 
              }
              else {
                    this.message = response.data.error
              }
            })
            .catch(error => {
              console.log(error.response)
            })
         }
         else {
           axios
            .post(JsonApiURL+'api/cohort_json.php', {insert: {groupId: this.group_id,  name: this.cohort_name,  courseId: this.course_id, category: this.category,  date_begin: this.date_begin,  date_end: this.date_end, date_protocol: this.date_protocol, directive: this.directive, chairman: this.chairman, teacher: this.teacher,  sessionId: session_t.sessionId } })
            .then(response => {
              console.log(response)
              if(response.data.status==0) {
                   this.$router.push({ name: 'groups_list', params: {orderid: this.order_id, make: 0, counterpartyid: this.a_counterparty_id }} ) 
              }
              else {
                    this.message = response.data.error
              }
            })
            .catch(error => {
              console.log(error.response)
            })
          }
        },

        onChangeGroup(){
	   if(this.cohort_name == '' && this.course != undefined &&  this.date_g  != undefined) {
		for (index = 0; index < this.info3.list.length; ++index) {
		    if(this.info3.list[index].courseId == this.course_id){
			var  current_name = this.info3.list[index].shortname
			break
		    }
		}
		if(current_name != undefined )
		    this.cohort_name = current_name + '_[' + this.group_id + ']_' + this.date_g
                else
		    this.cohort_name = '_' + this.parent_txt + '_[' + this.group_id + ']_' + this.date_g
            }
       },

       search_go(fl) {
       if(fl == 0)
            this.namesearch = "";

       if(this.category>0)
          var filter =  "`category_id`="+this.category;
       else
          var filter = "";

       axios
         .post(JsonApiURL+'api/courses_json.php', {list: {where: filter, search: this.namesearch,   sessionId: session_t.sessionId }})
         .then(response => { 
            this.info3 = response.data
            this.parent_txt =  this.info3.categories_txt
            this.parent = this.info3.parent_id
            this.list_length = this.info3.list.length
              console.log(response.data)
          })
         .catch(error => {
              console.log(error.response)
            })
    },



    categories_go() {
    if(this.category>0)
          var filter =  "`category_id`="+this.category;
    else
          var filter = "";

    axios
      .post(JsonApiURL+'api/courses_json.php', {list: {where: filter,  sessionId: session_t.sessionId }})
      .then(response => { 
            this.info3 = response.data
            this.parent_txt =  this.info3.categories_txt
            this.parent = this.info3.parent_id
            this.list_length = this.info3.list.length
       })
      .catch(error => {
              console.log(error.response)
            })
    }




  },



	template: `<div><navigation></navigation><h3>Курс</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">
<div class="container">

  <div class="mb-2">	
  <h5 align="right">Группа: <b>{{name}}</b> </h5>


  <span v-if="cohort_id==0">
  <div class="mb-2">	
    <label for="input_category" class="form-label"> Категория </label><br />
        <select v-model="category" @change="categories_go()" ><option value="0"> - Категория - </option> 
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
    <select  v-model="course"  @change="onChangeGroup()" > 
        <option v-if="list_length>0 && category>0" value="0"> - Все курсы категории - </option> 
        <option v-for="(item2, index) in info3.list" :value="item2.course_id">
	    {{item2.name}} 
	</option>
    </select>
  </div>
  </span>
  <span v-else>
    <h5> {{course_name}}<h5>
  </span>
  
  
  <br />
  <div class="row">
   <div class="mb-2 col-4">	
    <label for="input_date" class="form-label">Дата начала обучения</label>
      <input v-model="date_begin"  class="form-control" id="input_date"  type="date"   @input="onChangeGroup()">
   </div>
   <div class="mb-4 col"> </div>
   <div class="mb-2 col-4">	
    <label for="input_date_end" class="form-label">Дата завершения</label>
      <input v-model="date_end"  class="form-control" id="input_date_end"  type="date">
   </div>
   <div class="mb-4 col"> </div>
  </div>

  <div class="row">
   <div class="mb-2 col-4">	
    <label for="input_date_protocol" class="form-label">Дата оформления протокола</label>
      <input v-model="date_protocol"  class="form-control" id="input_date_protocol"  type="date" >
   </div>
   <div class="mb-4 col"> </div>
  </div>


  <br />
  <div class="row">
   <div class="mb-4 col-10">	
    <label for="input_cohort" class="form-label">Глобальная группа</label>
      <input v-if="moodle_cohort_id==0"  v-model="cohort_name"  class="form-control" id="input_cohort" >
      <input v-if="moodle_cohort_id>0"   v-model="cohort_name"  class="form-control" id="input_cohort" readonly >
   </div>
   <div class="mb-4 col"> </div>
  </div>
 
 
 
  <hr >
  <div class="mb-2 ">	
      <label for="input_directive" class="form-label ">Абзац протокола / <small>Номер приказа</label>
      <input v-model="directive"   class="form-control " id="input_directive" type="text" />
  </div>
  
  <div class="mb-2 ">	
      <label for="input_chairman" class="form-label ">Председатель комиссии</label>
      <input v-model="chairman"   class="form-control" id="input_chairman"   type="text"  >
  </div>

  <div class="mb-2 ">	
      <label for="input_teacher" class="form-label ">Члены комиссии</label>
      <textarea v-model="teacher"    class="form-control"  id="input_teacher"   ></textarea>
  </div>


  <br />
  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="cohortSave()"> Сохранить </button>
    &nbsp<router-link  :to="{ name: 'groups_list', params: {orderid: this.order_id, make: 0, counterpartyid: this.a_counterparty_id } }" > <button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>


 </div>

</div>
	</div>`

};




