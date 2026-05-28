var GroupReport = {
  data: function () {
    return {
      group_id: 0,
      info: [],
      info2: [],
      name: '',
      date_g: '',
      course: '',
      message: ''
     }
   },


   mounted() {
//    updated() {
    this.group_id = this.$route.params.groupid

    axios
      .post(JsonApiURL+'api_json.php', {group_detalies: {groupId: this.group_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info = response.data
	    this.name = this.info.groupInfo.name
	    this.date_g = this.info.groupInfo.date
	    this.course = this.info.groupInfo.courseId

            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            }),

    axios
      .post(JsonApiURL+'api_json.php', {group_items: {groupId: this.group_id, sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })

    axios
      .post(JsonApiURL+'api_json.php', {group_report: {groupId: this.group_id, sessionId: session_t.sessionId } })
      .then(response => { 
            this.info2 = response.data.groupReport

       })
      .catch(error => {
              console.log(error.response)
            })

  },


  methods: {

    userUnlink(userId, name ){
	var fl = confirm('Исключить: ' + name + '?');
	if(fl) {
	axios
          .post(JsonApiURL+'api_json.php', {group_item_del: {groupId: this.group_id, userId: userId, sessionId: session_t.sessionId }})
          .then(response => { 
            //this.info = response.data
        })
          .catch(error => {
              console.log(error.response)
            }),

	axios
          .post(JsonApiURL+'api_json.php', {group_items: {groupId: this.group_id}})
          .then(response => { 
            this.info = response.data
        })
        .catch(error => {
              console.log(error.response)
            })


	}
    }

},




	template: `<div><navigation></navigation><h3>Протокол об обучении</h3> 
  <h4 style="text-align: center; color: red;">{{message}}</h4>

<div class="container">

 <div class="row justify-content-between">
    <div class="col-8">
  <div align="left">
  <h5>{{name}}</h5>
  </div>
    </div>
  </div>

    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Фамилия </th>
          <th scope="col"> Имя </th>
          <th scope="col"> Отчество </th>
          <th scope="col"> Место работы </th>
          <th scope="col"> Подразделение </th>
          <th scope="col"> Должность </th>
          <th scope="col"> </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left">  
            <td>{{item.lastname}}</td>
            <td>{{item.firstname}}</td>
            <td>{{item.middlename}}</td>
            <td>{{item.organization}}</td>
            <td>{{item.subdivision}}</td>
            <td>{{item.position}}</td>
            <td> 
		<span v-for="item2 in info2.list">
		    <span v-if="item2.userId==item.userId">
			<span v-for="item3 in item2.result">
			    <nobr>{{item3.result}}  {{item3.date}}</nobr> <br /><nobr style="font-size: smaller;">( код проверки: {{item3.num}} )</nobr><br />
			</span>
		    </span>
		</span>
            </td>
          </tr>
  </tbody>
  </table>


    <div align="right">
    <table border="0"><tr><td>
      <a :href="'/reports/group_report_mo.php?groupId='+this.group_id"  target="_blank"  class="nav-link"  title="Отчет о результатах обучения" ><button  class="btn btn-secondary"><i class="fa-solid fa-print"></i> Печатать</button></a>
    </td><td>
      <router-link class="nav-link" to="/groupslist/"><button  class="btn btn-outline-primary"><i class="fa-solid fa-circle-xmark"></i> Закрыть</button></router-link>
    </td><tr></table>
  </div>


 </div>

</div>`

};




