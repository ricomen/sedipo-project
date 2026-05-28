var CourseReport = {
  data: function () {
    return {
      course_id: 0,
      info: [],
      info2: [],
      info9: [],
      name: '',
      date1: '',
      date2: '',
      completed: 'false',
      course: '',
      message: ''
     }
   },


   mounted() {
//    updated() {
    this.course_id = this.$route.params.courseid


    axios
      .post(JsonApiURL+'api_json.php', {course_report2: {courseId: this.course_id, date1: this.date1, date2: this.date2, completed: this.completed, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data

       })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(JsonApiURL+'moodle_json.php', {report_sync: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info9 = response.data
       })
      .catch(error => {
              console.log(error.response)
            })


  },


  methods: {
   date_refresh(){
    axios
      .post(JsonApiURL+'api_json.php', {course_report2: {courseId: this.course_id, date1: this.date1, date2: this.date2, completed: this.completed, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data

       })
      .catch(error => {
              console.log(error.response)
            })
   },

   onChangeDate(){
        if(this.date1 != '')
    	    this.completed = 'true'
   },

   date_clean(){
        this.date1 = ''
        this.date2 = ''
    	this.completed = 'false'
	this.date_refresh()
   }

},




	template: `<div><navigation></navigation><h3>Отчет о проведении обучения и проверки уровня знаний</h3> 
  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div class="container"  style="text-align: left;">
 <h5 style="text-align: right;">{{info.course}}</h5>
<hr />

<div class="row">
  <div class="col"> <input type="date" v-model="date1"  id="date1" pattern="\d{4}-\d{2}-\d{2}"   @input="onChangeDate()" /> </div>
  <div class="col"> <input type="date" v-model="date2"  id="date2" pattern="\d{4}-\d{2}-\d{2}"   @input="onChangeDate()" /> </div>
  <div class="col"> <input type="checkbox" v-model="completed" > - курс пройден </div>
  <div class="col"> <button @click="date_refresh()" > Показать </button> </div>
  <div class="col">  <button @click="date_clean()"  title="Сбросить все фильтры"   ><i class="fa-solid fa-text-slash"></i> </button> </div>
  <div class="col-4"> </div>
</div>
<hr />

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col">  </th>
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <th scope="col"> Место работы </th>
          <!--<th scope="col"> Подразделение </th>-->
          <th scope="col"> Должность </th>
          <th scope="col">Результат прохождения </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="(item, index) in info.userReport"  align="left">  
            <td>{{index+1}}.</td>
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <td>{{item.organization}}</td>
            <!--<td>{{item.subdivision}}</td>-->
            <td>{{item.position}}</td>
            <!--  --- <td><span v-if="item.num!=null">{{item.result}}  {{item.date}} <br /><small><nobr>(№ протокола {{item.num}})</nobr></small></span><span v-else-if="item.progress==0"> <center>0%</center> </span><span v-else-if="item.progress==100"> <b>Пройден</b> </span><span v-else><center> <div class="progress"><div class="progress-bar" role="progressbar" aria-label="Example with label" :style="'width: '+item.progress+'%;'" :aria-valuenow="item.progress" aria-valuemin="0" aria-valuemax="100">{{item.progress}}%</div></div> </center></span></td>-->
            <td><span v-if="item.result!=null">{{item.result}}  {{item.date}}<span v-if="item.num!=''"> <br /><small><nobr>(№ протокола {{item.num}})</nobr></small></span> </span><span v-else-if="item.progress==0"> <center>0%</center> </span><span v-else-if="item.progress==100"> <b>Пройден</b> </span><span v-else-if="item.progress<15"><center> <div class="progress"><div class="progress-bar" role="progressbar" aria-label="Example with label" aria-valuemin="0" aria-valuemax="100">{{item.progress}}%</div></div> </center></span> <span v-else><center> <div class="progress"><div class="progress-bar" role="progressbar" aria-label="Example with label" :style="'width: '+item.progress+'%;'" :aria-valuenow="item.progress" aria-valuemin="0" aria-valuemax="100">{{item.progress}}%</div></div>    <font color="#aaa"><small>({{item.progress_test}}%)</small>    </center></span></td>
            <!--<td><span v-if="item.result!=null">{{item.result}}  {{item.date}}<span v-if="item.num!=''"> <br /><small><nobr>(№ протокола {{item.num}})</nobr></small></span> </span><span v-else-if="item.progress==0"> <center>0%</center> </span><span v-else-if="item.progress==100"> <b>Пройден</b> </span><span v-else-if="item.progress<15"><center> <div class="progress"><div class="progress-bar" role="progressbar" aria-label="Example with label" aria-valuemin="0" aria-valuemax="100">{{item.progress}}%</div></div> </center></span> <span v-else><center> <div class="progress"><div class="progress-bar" role="progressbar" aria-label="Example with label" :style="'width: '+item.progress+'%;'" :aria-valuenow="item.progress" aria-valuemin="0" aria-valuemax="100">{{item.progress}}%</div></div> </center> </span></td>-->
          </tr>
  </tbody>
  </table>



    <div align="right">
    <table border="0"><tr><td>
      <a :href="'/users/reports/course_report.php?courseId='+this.course_id+'&completed='+this.completed+'&date1='+this.date1+'&date2='+this.date2"  target="_blank"  class="nav-link"  title="Отчет о результатах обучения" ><button  class="btn btn-secondary"><i class="fa-solid fa-print"></i> Печатать</button></a>
    </td><td>
      <router-link class="nav-link" to="/courses_list/"><button  class="btn btn-outline-primary"><i class="fa-solid fa-circle-xmark"></i> Закрыть</button></router-link>
    </td><tr></table>
  </div>


 </div>

</div>`

};




