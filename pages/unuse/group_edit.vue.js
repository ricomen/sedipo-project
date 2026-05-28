var GroupEdit = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info4: [],
      info5: [],
      message: '',
      cohort_id: 0,
      lstream_id: 0,
      group_id: 0,
      name: '',
      status: 0,
      course_id: '',
      course_name: '',
      course_shortname: '',
      category: 0,
      cohort_name: '',
      list_length: 0,
      order_id: 0,
      a_counterparty_id: 0,
      counterparty_name: '',
      days: 0,
     }
   },

   mounted() {
    this.cohort_id = Number(this.$route.params.groupid)
    this.order_id  = Number(this.$route.params.orderid)
    this.a_counterparty_id = Number(this.$route.params.counterpartyid)

    if(this.cohort_id > 0){
	axios
    	    //.post(JsonApiURL+'api/groups_json.php', {object: {objectId: this.group_id, sessionId: session_t.sessionId } })
    		.post(JsonApiURL+'api/groups_json.php', {object: {objectId: this.cohort_id, sessionId: session_t.sessionId } })
                .then(response => { 
        	    this.info = response.data
        	    this.status = this.info.result.status
        	    //this.date_begin = this.info.result.date_begin
        	    //this.date_begin2 = this.info.result.date_begin2
        	    //this.date_end = this.info.result.date_end
        	    this.days = this.info.result.days
        	    //this.order_id = this.info.result.order_id
        	    this.name = this.info.result.name
                    this.cohort_name = this.info.result.name
                    this.category = this.info.result.category
            	    this.course_id = this.info.result.course_id
            	    this.course_name = this.info.result.course_name
            	    this.course_shortname = this.info.result.course_shortname
                
                    }


    	    })
    	    .catch(error => {
        	  console.log(error.response)
            })
    }



  },


  methods: {
/*        cohortSave () {
         if(this.cohort_id > 0){
           axios
            .post(JsonApiURL+'api/groups_json.php', {update: {objectId: this.cohort_id,  name: this.cohort_name,    main_teacher: this.main_teacher_id,  directive_num: this.directive_num, chairman: this.chairman, teachers_commission: this.teachers_commission,   sessionId: session_t.sessionId } })
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
        }
*/

  },



template: `<div><navigation></navigation><h4>Группа: {{name}}</h4> 
    <!--v-if="role!='counterparty' "-->
    <h4 style="text-align: right;" > {{counterparty_name}} </h4>

<div align="left">

  <div class="mb-2">	
    <h5> {{course_name}}<h5>

  <br />

  <div class="row">
   <div class="mb-4 col-6">	
    <label for="input_main_teacher" class="form-label">Преподаватель</label>
      <div class ="container">
        <input  v-model="main_teacher"   class="form-control" id="input_main_teacher"  type="text" @input="load_combo()">
		<ul class ="list-group" id ="listItem" style="text-align: left;">
			<li  v-for="(item, index) in info2.list" class ="list-group-item" @click="search2(index)" > {{item.lastname}} {{item.firstname}} {{item.middlename}}</li>
		</ul>
      </div>
   </div>
   <div class="mb-4 col"> </div>
  </div>
 
 
  <hr >

  <div class="mb-2">
      <label for="input_directive" class="form-label col-sm-3">Абзац протокола / Номер приказа</label>
      <input v-model="directive_num"   class="form-control col" id="input_directive" type="text" />
  </div>



 <!--<div class="row">
    <div class="mb-4 col">	
      <label for="input_finalexamination" class="form-label ">Итоговая аттестация по умолчанию</label>
      <input v-model="finalexamination"   class="form-control" id="input_finalexamination"   type="text"  >
    </div>
    <div class="mb-4 col"> </div>
 </div>

 <div class="row">
    <div class="mb-4 col">	
      <label for="input_certificate_grade" class="form-label ">Оценка в поле № выданного удостоверения</label>
      <input v-model="certificate_grade"   class="form-control" id="input_certificate_grade"   type="text"  >
  </div>
    <div class="mb-4 col"> </div>
 </div>-->

  <br />
  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="cohortSave()"> Сохранить </button>
    &nbsp<router-link  :to="{ name: 'groups_list', params: {orderid: this.order_id, make: 0, counterpartyid: this.a_counterparty_id } }" > <button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>

 </div>

	</div>`

};




