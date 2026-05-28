var CourseCalendarImport  = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info4: [],
      info9: [],
      message: '',
      course_id: 0,
      a_category_id: 0,
      cohort_id: 0,
      performer_id: 0,
      performer_name: '-',
      name: '',
      shortname: '',
      description: '',
      competence: '',
      moodle_course_id: 0,
      hours: '',
      category: 0,
      calendar_count: 0,

      importfile1: '',
      importfile1_filename: '',
      importfile1_file_type: '',
      importfile2: '',
      importfile2_filename: '',
      importfile2_file_type: '',
      importfile3: '',
      importfile3_filename: '',
      importfile3_file_type: '',
      }
   },

   mounted() {
    this.course_id = Number(this.$route.params.courseid)
    this.a_category_id = Number(this.$route.params.categoryid)
    if(isNaN(this.a_category_id))
                this.a_category_id = 0
    this.cohort_id = Number(this.$route.params.groupid)
    if(isNaN(this.cohort_id))
                this.cohort_id = 0
    this.order_id  = Number(this.$route.params.orderid)
                  

    if(this.course_id > 0 ){
      axios
      .post(JsonApiURL+'api/courses_json.php', {object: {objectId: this.course_id, sessionId: session_t.sessionId } })
      .then(response => { 
            var main_module=0
            this.info = response.data
            this.name = this.info.result.name
            this.shortname = this.info.result.shortname
            this.category = this.info.result.category_id
            this.performer_id = this.info.result.performer_id
            this.moodle_course_id = this.info.result.moodle_course_id
            this.hours = this.info.result.hours
            this.hours_l = this.info.result.hours_l
            this.hours_p = this.info.result.hours_p
            this.form_id = this.info.result.form_of_study
            this.name_common = this.info.result.name_common
            this.description = this.info.result.description
            this.competence = this.info.result.competence
            main_module = this.info.result.main_module

            if(main_module == 1)
                this.is_main_module = 'true'
                
            if(this.performer_id>0){
                axios
                  .post(JsonApiURL+'api/counterparty_json.php', {object: {objectId: this.performer_id, sessionId: session_t.sessionId } })
                  .then(response3 => { 
                    this.info3 = response3.data
                    this.performer_name = this.info3.result.shortname
                  })
                  .catch(error => {
                        console.log(error.response)
                  })
            }

       })
      .catch(error => {
              console.log(error.response)
      }),


      axios
      .post(JsonApiURL+'api/course_calendar_json.php', {list: { conditions: {"course_id": this.course_id},  sessionId: session_t.sessionId } })
      .then(response => { 
              //console.log(response)
            this.info4 = response.data
            this.calendar_count = this.info4.list.length


       })
      .catch(error => {
              console.log(error.response)
       })


    }


  },

//    updated() {

//  },


  methods: {
        objectSave() {
            this.wait = 1;
            const formData = new FormData();
            if(this.importfile1_file_type!=''){
                formData.append('upload1', this.importfile1)
                formData.append('name1', this.importfile1_filename)
            }
            /*if(this.importfile2_file_type!=''){
                formData.append('upload2', this.importfile2)
                formData.append('name2', this.importfile2_filename)
            }
            if(this.importfile3_file_type!=''){
                formData.append('upload3', this.importfile2)
                formData.append('name3', this.importfile2_filename)
            }*/
            formData.append('course_id', this.course_id); 
            formData.append('cohort_id', this.cohort_id); 
            formData.append('sessionId', session_t.sessionId); 
            
	        axios
	           .post(JsonApiURL+'api/course_calendar_import_json.php', formData, {headers: {'Content-Type': 'multipart/form-data'}})
               .then(response => {
                    this.info9 = response.data
                    this.wait = 0;
                    console.log(response)
                    if(this.order_id>0 && this.cohort_id>0)
                            this.$router.push({ name: 'groups_list', params: {orderid: this.order_id, make: 0, counterpartyid: 0  }}) 
                    else
                            this.$router.push({ name: 'courses_list', params: { }}) 
                })
              .catch(error => {
                    console.log(error.response)
                })
         },
         

        handleC1Upload() {
            this.importfile1 = document.getElementById('input_calendar1').files[0];
            this.importfile1_filename = this.importfile1.name;
            this.importfile1_file_type = this.importfile1.type;
        },
        
        handleC2Upload() {
            this.importfile2 = document.getElementById('input_calendar2').files[0];
            this.importfile2_filename = this.importfile2.name;
            this.importfile2_file_type = this.importfile2.type;
        },

        handleC3Upload() {
            this.importfile3 = document.getElementById('input_calendar3').files[0];
            this.importfile3_filename = this.importfile3.name;
            this.importfile3_file_type = this.importfile3.type;
        }         

   },


	template: `<div><navigation></navigation><h3>Учебный план  курса </h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>


<div align="left">
<div class="container">


  <div   class="mb-2 row">
  <label for="input_type" class="form-label col-sm-3">Учебный центр</label>
  {{performer_name}}
  </div>



  <div class="mb-2 row">	
  <label for="input_name"  class="form-label col-sm-3"   >Название курса </label>
    {{name}}
  </div>


  <div class="mb-2 row">	
  <label for="input_shortname"  class="form-label col-sm-3"   >Краткое название </label>
    {{shortname}}
  </div>

  <div class="mb-2 row">	
  <label for="input_shortname"  class="form-label col-sm-3"   > </label>
    <p v-if="calendar_count==0"   class="col" ><b> учебный план  курса не загружен</b></p>
    <p v-else class="col" ><b> {{calendar_count}} разделов </b></p>
  </div>

<br />
  <div class="mb-2 row">	
  <label for="input_calendar1"  class="form-label col-sm-4"   >Учебный план занятий</label>
       <input name="importfile1"  class="form-control col" id="input_calendar1"    type="file"     @change="handleC1Upload()" >
  </div>

  <div class="mb-2 row">	
  <label for="input_calendar2"  class="form-label col-sm-4"   >Индивидуальный учебный план 1</label>
       <input name="importfile2"  class="form-control col" id="input_calendar2"    type="file"     @change="handleC2Upload()" >
  </div>

  <div class="mb-2 row">	
  <label for="input_calendar3"  class="form-label col-sm-4"   >Индивидуальный учебный план 2</label>
       <input name="importfile3"  class="form-control col" id="input_calendar3"    type="file"     @change="handleC3Upload()" >
  </div>

  <br / >
  <div class="mb-2">	
    <div align="right">
    <button v-if="course_id>0"   class="btn btn-primary"    @click="objectSave()"> Сохранить </button>
      &nbsp <router-link v-if="order_id>0 && cohort_id>0"  :to="{ name: 'groups_list', params: {orderid: this.order_id, make: 0, counterpartyid: 0 } }" > <button  class="btn btn-outline-primary">Отмена</button></router-link>
      <router-link v-else :to="{ name: 'courses_list', params: {  }}" ><button  class="btn btn-outline-primary">Отмена</button></router-link>
    </div>
  </div>
 </div>
</span>


</div>
	</div>`

};








