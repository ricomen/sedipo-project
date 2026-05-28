var UserReport = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info9: [],
      role: '',
      message: '',
      user_id: 0,
      a_counterparty_id: 0,
      group_id: 0,
      lastname: '',
      firstname: '',
      middlename: '',
      snils: '',
      address: '',
      date_of_birth: '',
     }
   },

   mounted() {
    this.user_id = Number(this.$route.params.userid)
    this.a_counterparty_id = Number(this.$route.params.counterpartyid)

    //this.group_id = this.$route.params.groupid

    if(this.user_id ==0 ) {
        this.user_id = session_var.student_report_user_id
        this.a_counterparty_id = session_var.student_report_counterparty_id
    }

    if(this.user_id >0 ) {
        session_var.student_report_user_id = this.user_id
        session_var.student_report_counterparty_id =  this.a_counterparty_id

        axios
            .post(JsonApiURL+'api/students_json.php', {object: {objectId: this.user_id, sessionId: session_t.sessionId } })
            .then(response => { 
                this.info = response.data
                this.role = this.info.role
                this.lastname = this.info.result.lastname
                this.firstname = this.info.result.firstname
                this.middlename = this.info.result.middlename
                this.date_of_birth = this.info.result.date_of_birth
                this.snils = this.info.result.snils
                this.address = this.info.result.address

                axios
                    .post(JsonApiURL+'api/students_json.php', {user_jobs: {objectId: this.user_id,   counterparty_id: this.a_counterparty_id,  sessionId: session_t.sessionId } })
                    .then(response2 => { 
                        this.info3 = response2.data

                    })
                   .catch(error => {
                        console.log(error.response)
                    })



            })
            .catch(error => {
                console.log(error.response)
            }),


        axios
          .post(JsonApiURL+'api/students_json.php', {report: {objectId: this.user_id, sessionId: session_t.sessionId } })
            .then(response => { 
                this.info2 = response.data

            })
            .catch(error => {
                  console.log(error.response)
            })
    }


  },


  methods: {

  },



	template: `<div><navigation></navigation><h3>Результаты обучения</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">

<div class="container">


   <p>Фамилия Имя Отчество: <b>{{lastname}} {{firstname}} {{middlename}}</b></p>
   <p>СНИЛС: <b>{{snils}}</b></p>

    <table class="table">
      <thead>
        <tr  align="center">
          <th scope="col"> Организация </th>
          <th scope="col" style="text-align: center;"> Должность </th>
        </tr>
      </thead>
     <tbody>   
        <tr v-for="item3 in info3.result"  align="left" style="float: none;" >  
            <td style="text-align: center;">{{item3.counterparty_name}}</td>
            <td style="text-align: center;">{{item3.job_title}}</td>
          </tr>
  </tbody>
  </table>


    <br />
    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Курс </th>
          <th scope="col" style="text-align: center;"> Номер Удостоверения/ <br />Сертификата </th>
          <th scope="col" style="text-align: center;"> Дата протокола </th>
          <th scope="col" style="text-align: center;"> Дата повторного прохождения </th>
        </tr>
      </thead>
     <tbody>   
        <tr v-for="item in info2.report"  align="left" style="float: none;" >  
            <td>{{item.course}}</td>
            <td style="text-align: center;">{{item.certificate}}</td>
            <td style="text-align: center;">{{item.date_convert}}</td>
            <td style="text-align: center;">{{item.date_finish_convert}}</td>
          </tr>
  </tbody>
  </table>

  <div class="mb-2">	
    <div align="right">
    <span v-if="group_id>0">
      &nbsp<router-link :to="{ name: 'groupitems', params: {groupid: group_id  }}"><button  class="btn btn-outline-primary">Закрыть</button></router-link>
    </span>
    <span v-else>
      <a :href="'reports/student_report.php?userId='+this.user_id" target="_blank"><button  class="btn btn-secondary"><i class="fa-solid fa-print"></i> Печатать</button></a>&nbsp<router-link to="/students_list" ><button  class="btn btn-outline-primary"> Закрыть </button></router-link>
    </span>
    </div>
  </div>
 </div>



 </div>
 </div>

</div>
	</div>`

};




