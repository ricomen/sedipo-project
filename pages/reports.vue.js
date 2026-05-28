var Reports  = {
  data: function () {
    return {
      info: [],
      message: '',
      namesearch: '',
     }
   },


   mounted() {
//    updated() {
    axios
      .post(JsonApiURL+'api_json.php', {organizations: { sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
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
    search_go() {
    axios
      .post(JsonApiURL+'api_json.php', {organizations: {search: 1, name: this.namesearch, sessionId: session_t.sessionId }})
      .then(response => { 
            this.info = response.data
            //this.isSysAdmin = this.info.isSysAdmin
            //this.userId = this.info.userId
       })
      .catch(error => {
              console.log(error.response)
            })
    },


},


	template: `<div><navigation></navigation><h3>Отчет по Курсам</h3> 

  <h4 style="text-align: center; color: red;">{{message}}</h4>

    <br />
    <table width="100%">
        <tr  align="left">
            <td><input type="text" v-model="namesearch" placeholder="Организация"   size="20"></td>
	    <td width="15%"> </td>
            <td align="left"> <button  @click="search_go()">&nbsp;<i class="fa-solid fa-magnifying-glass"></i>&nbsp;Поиск&nbsp;</button></td>
	    <td width="15%"> </td>
            <td align="right"> <button  title="Сбросить все фильтры"   ><i class="fa-solid fa-text-slash"></i> </button> </td>
        </tr>
    </table>
    <hr />


    <table class="table">
      <thead>
        <tr  align="left">
          <th scope="col"> Организация  </th>
          <th scope="col"> <div style="float: right"><router-link  :to="{ name: 'organizationedit', params: { organizationid:0 }}"   title="Добавить Организацию" ><b style=" font-size: larger;"><i class="far fa-plus-square"></i></b></router-link ></div>  </th>
        </tr>
      </thead>
     
     <tbody>   
        <tr v-for="item in info.list"  align="left">  
            <td>{{item.name}}</td>

            <td> <div style="float: right">
		<table border="0"><tr>
		<td><a :href="'organization_report.php?organizationId='+item.organizationId"  target="_blank"  class="nav-link"   title="Отчет о результатах обучения по организации" ><button class="btn btn-outline-primary"><i class="fa-solid fa-file-circle-check"></i> Отчет о результатах обучения по организации </button></a></td>
		<td><a :href="'organization_course_report.php?organizationId='+item.organizationId"  target="_blank"  class="nav-link"   title="Количество человек прошедших обучение" ><button class="btn btn-outline-primary"><i class="fa-solid fa-file-circle-check"></i> Количество человек прошедших обучение </button></a></td>
		</tr></table>
	    </div></td>
          </tr>
  </tbody>
  </table>

	</div>`





};





