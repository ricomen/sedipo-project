var LstreamTeacher = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info4: [],
      message: '',
      lstream_id: 0,
      name: '',
      date_begin: '',
      date_end: '',
      date_protocol: '',
      //status: 0,
      main_teacher: '',
      main_teacher_id: 0,
      course_id: '',
      course_name: '',
      course_shortname: '',
      category: 0,
      moodle_cohort_id: '',
      list_length: 0,
      commission_id: '',
      l_id: 0,
      current_date: 0,
      topic_id: 0,
      teacher: '',
      teacher_id: 0,
      //a_counterparty_id: 0,
      //counterparty_name: '',
     }
   },

   mounted() {
    this.lstream_id = Number(this.$route.params.lstreamid)
    this.current_date  = this.$route.params.date
    //this.a_counterparty_id = Number(this.$route.params.counterpartyid)

    if(this.lstream_id > 0){
	axios
    		.post(JsonApiURL+'api/lstream_json.php', {object: {objectId: this.lstream_id, sessionId: session_t.sessionId } })
                .then(response => { 
        	    this.info = response.data
        	    this.status = this.info.result.status
        	    this.name = this.info.result.name
                    this.date_begin = this.info.result.date_begin
                    this.date_end = this.info.result.date_end
                    this.date_protocol = this.info.result.date_protocol
        	    this.main_teacher = this.info.result.main_teacher
        	    this.main_teacher_id = this.info.result.main_teacher_id
                    this.category = this.info.result.category
            	    this.course_id = this.info.result.course_id
            	    this.course_name = this.info.result.course_name
            	    this.course_shortname = this.info.result.course_shortname
                    this.moodle_cohort_id = this.info.result.moodle_cohort_id
                    this.commission_id = this.info.result.commission_id
                
/*     		    if( this.date_end!='' && this.date_begin2==''){
		            var currentDate = new Date(this.date_end)
		            currentDate.setDate(currentDate.getDate() - this.days )
		            this.date_begin2 = currentDate.toLocaleDateString('en-CA')
                    }
*/
                     if(this.course_id >0 ) {
                     //if(this.course_id >0 &&  this.teacher_id<=0 ) {

                        axios
                        .post(JsonApiURL+'api/teacher2_json.php', {search: {course_id: this.course_id, limit: 25, sessionId: session_t.sessionId }})
                        .then(response => { 
                           this.info2 = response.data

                           console.log(response)
                         })
                         .catch(error => {
                             console.log(error.response)
                        });
                     }



    	    })
    	    .catch(error => {
        	  console.log(error.response)
            })

        this.l_id = 0
        conditions =  {lstream_id: this.lstream_id }
	axios
	.post(JsonApiURL+'api/lstream_teacher_json.php', {list: {conditions, search: this.current_date, sessionId: session_t.sessionId } })
            .then(response => { 
                 this.info3 = response.data
                 if(this.info3.list.lenght>0)
                     this.l_id = this.info3.list[0].l_id
                     this.teacher_id = this.info3.list[0].user_id
                     this.topic_id = this.info3.list[0].topic_id

                     if(this.teacher_id>0){
                        axios
                        .post(JsonApiURL+'api/teacher_json.php', {object: {objectId: this.teacher_id, sessionId: session_t.sessionId }})
                        .then(response => { 
                           this.info4 = response.data
                           this.teacher = this.info4.result.lastname + ' ' + this.info4.result.firstname + ' ' + this.info4.result.middlename

                           console.log(response)
                         })
                         .catch(error => {
                             console.log(error.response)
                        });
                     }


    	    })
    	    .catch(error => {
        	  console.log(error.response)
            })


    }



  },


  methods: {
        lstreamSave () {
         if(this.lstream_id > 0){
	      axios
              .post(JsonApiURL+'api/lstream_json.php', {lstream_teacher_unlink: {lstream_id: this.lstream_id, date: this.current_date, topic_id: 0,  sessionId: session_t.sessionId } })
              .then(response => {
                  console.log(response)
    	          //this.info9 = response.data

                axios
                  .post(JsonApiURL+'api/lstream_teacher_json.php', {insert: {lstream_id: this.lstream_id, date: this.current_date, topic_id: 0, user_id: this.teacher_id, sessionId: session_t.sessionId } })
                  .then(response => {
                     console.log(response)
    	             //this.info9 = response.data
                     this.$router.push({ name: 'calendar' } ) 

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


         teacherUnlink() {
         if(this.lstream_id > 0){
              this.teacher = ''
              this.teacher_id = 0
	      /*axios
              .post(JsonApiURL+'api/lstream_teacher_json.php', {lstream_teacher_unlink: {lstream_id: this.lstream_id, date: this.current_date, topic_id: 0,  sessionId: session_t.sessionId } })
              .then(response => {
                  console.log(response)
    	          //this.info9 = response.data

              })
              .catch(error => {
                console.log(error.response)
              })*/
           }
        },





    search2(index){
       this.teacher = this.info2.list[index].lastname + ' ' + this.info2.list[index].firstname + ' ' + this.info2.list[index].middlename
       this.teacher_id = this.info2.list[index].user_id
       this.info2.list = []
    },


    load_combo(){
      if(this.teacher!=''){

       axios
             .post(JsonApiURL+'api/teacher2_json.php', {search: {course_id:  this.course_id, search: this.teacher, limit: 25, sessionId: session_t.sessionId }})
             .then(response2 => { 
               this.info2 = response2.data
              console.log(response2)
        })
        .catch(error => {
              console.log(error.response)
        })
      }
      else {
          this.info2.list = []
      }
    },




  },



template: `<div><navigation></navigation><h4>Учебный поток: {{name}}</h4> 
    <!--v-if="role!='counterparty' "-->
    <h4 style="text-align: right;" > {{counterparty_name}} </h4>

<div align="left">

  <div class="mb-2">	
    <h5> {{course_name}}</h5>


  <br />
  <div class="row">
    <div class="col-2">Дата: {{new Date(this.current_date).toLocaleDateString("ru")}}</div>
    <div v-if="main_teacher!=''" class="col">Преподаватель закрепленный за потоком: {{main_teacher}}</div>
  </div>


  <br /><hr />
  <div class="row">
   <div class="mb-4 col-6">	
    <label for="input_main_teacher" class="form-label">Преподаватель</label>
      <div class ="container">
        <input  v-model="teacher"   class="form-control" id="input_main_teacher"  type="text" @input="load_combo()">
		<ul class ="list-group" id ="listItem" style="text-align: left;">
			<li  v-for="(item, index) in info2.list" class ="list-group-item" @click="search2(index)" > {{item.lastname}} {{item.firstname}} {{item.middlename}}</li>
		</ul>
      </div>
   </div>
   <div class="mb-1 col"><br /><div style="padding-top: 12px;"><button @click="teacherUnlink()" >&nbsp;<i class="fa-solid fa-xmark"></i>&nbsp;</button></div> </div>
   <div class="mb-3 col"> </div>
  </div>
 

 

  <br />
  <div class="mb-2">	
    <div align="right">
    <button  class="btn btn-primary"    @click="lstreamSave()"> Сохранить </button>
    &nbsp<router-link  :to="{ name: 'calendar' }" > <button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>

 </div>

	</div>`

};




