var UserReport = {
  data: function () {
    return {
      info: [],
      info2: [],
      info3: [],
      info9: [],
      message: '',
      user_id: 0,
      group_id: 0,
      lastname: '',
      firstname: '',
      middlename: '',
      email: '',
      login: '',
      password: '',
      organization_id: 0,
      organization_name: '',
      position_id: 0,
      position_name: '',
      subdivision: '',
      date_of_birth: '',
     }
   },

   mounted() {
//    updated() {
    this.user_id = this.$route.params.userid
    this.group_id = this.$route.params.groupid

    axios
      .post(JsonApiURL+'api_json.php', {user_detalies: {userId: this.user_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
	    this.lastname = this.info.userInfo.lastname
            this.firstname = this.info.userInfo.firstname
            this.middlename = this.info.userInfo.middlename
            this.email = this.info.userInfo.email
            this.login = this.info.userInfo.login
            this.password = this.info.userInfo.password
            this.organization_id = this.info.userInfo.organization_id
            this.organization_name = this.info.userInfo.organization
            this.position_id = this.info.userInfo.position_id
            this.position_name = this.info.userInfo.position
            this.subdivision = this.info.userInfo.subdivision
            this.date_of_birth = this.info.userInfo.date_of_birth

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })

    axios
      .post(JsonApiURL+'api_json.php', {user_report: {userId: this.user_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info2 = response.data

       })
      .catch(error => {
              console.log(error.response)
            })

  },


  methods: {

  },



	template: `<div><navigation></navigation><h3>Результаты обучения</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div align="left">

<div class="container">


   <p><b>{{lastname}} {{firstname}} {{middlename}}</b></p>

   <p>Организация:  {{organization_name}}</p>

   <p>Структурное подразделение: {{subdivision}}</p>

   <p>Должность: {{position_name}}</p>

   <p>День месяц рождения: {{date_of_birth}}</p>

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Курс </th>
          <th scope="col"> Номер протокола </th>
          <th scope="col"> Дата протокола </th>
          <th scope="col"> Результат </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info2.userReport"  align="left" style="float: none;" >  
            <td>{{item.course}}</td>
            <td>{{item.num}}</td>
            <td>{{item.date}}</td>
            <td>{{item.result}}</td>
          </tr>
  </tbody>
  </table>

  <div class="mb-2">	
    <div align="right">
    <span v-if="group_id>0">
      &nbsp<router-link :to="{ name: 'groupitems', params: {groupid: group_id  }}"><button  class="btn btn-outline-primary">Закрыть</button></router-link>
    </span>
    <span v-else>
      <a :href="'user_report.php?userId='+this.user_id" target="_blank"><button  class="btn btn-secondary"><i class="fa-solid fa-print"></i> Печатать</button></a>&nbsp<router-link to="/list" ><button  class="btn btn-outline-primary"><i class="fa-solid fa-circle-xmark"></i> Закрыть</button></router-link>
    </span>
    </div>
  </div>
 </div>




    

 </div>
 </div>

</div>
	</div>`

};




